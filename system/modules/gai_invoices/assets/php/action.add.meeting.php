<?php

    // Start PHP session and include Composer, which also brings in our Google Sheets PHP stuffs
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
    
    // Connect to DB
    $dbh = new mysqli("localhost", "globalassinc_user", "Z2rc^wQ}98TS9mtl5y", "globalassinc_contao_4_13");
    if ($dbh->connect_error) {
        die("Connection failed: " . $dbh->connect_error);
    }
    
    // Store the passed form values
    $vars = $_POST;

    // Clean up our psychologist name so it can be used as a filename
	$cleanName = str_replace(' ', '_', $vars['psychologist']);
	$cleanName = str_replace('.', '', $cleanName);
	$cleanName = preg_replace('/[^a-zA-Z0-9_.]/', '_', strtolower($cleanName));
    
    // Clean the price, remove symbols and decimals if there are any
	$price = '';
    if($vars['price'] != '') {
        // first remove dollar sign
        $price = str_replace('$','',$vars['price']);
        // remove decimal and trailing numbers
        $price = floor($price);
    }
    
    // create a file with the name "psy_datetime.txt" to log our transaction data
    $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . '/../transaction_logs/add_meeting_'.$cleanName."_".strtolower(date('l_F_d_Y_H:m:s')).".txt", "w") or die("Unable to open file!");
    // loop through our $vars and write the key => value to our created file
    foreach($vars as $key => $var) {
        fwrite($myfile, "Key: " . $key . "  | Value: " . $var . "\n");
    }
    // were done logging, close the file we just created
    fclose($myfile);
    
    // Check for duplicate
    $duplicate = false;
    
    $query =  "select * from tl_transactions WHERE deleted=''";
    $result = $dbh->query($query);
    if($result) {
        while($row = $result->fetch_assoc()) {
            
            // combine our lasid and said for both the vars and row
            $lasidSasidVar = $vars['lasid'] . $vars['sasid'];
            $lasidSasidRow = $row['lasid'] . $row['sasid'];
            
            // If Psychologists match
            if($vars['psychologist'] == $row['psychologist']) {
                // If District matches
                if($vars['district'] == $row['district']) {
                    // If School matches
                    if($vars['school'] == $row['school']) {
                        // If Student matches
                        if($vars['student_name'] == $row['student_name']) {
                            // If Lasid + sasid matches
                            if($lasidSasidVar == $lasidSasidRow) {
                                // If Service Provided
                                if($vars['service_provided'] == $row['service_provided']) {
                                    // If Price matches
                                    if($price == $row['price']) {
                                        // If Meeting Date matches
                                        if($vars['meeting_date'] == $row['meeting_date']) {
                                            // If Meeting Start
                                            if($vars['meeting_start'] == $row['meeting_start']) {
                                                // If Meeting End
                                                if($vars['meeting_end'] == $row['meeting_end']) {
                                                    
                                                    $duplicate = true;
                                                    
                                                    $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . '/../duplicate_checks/add_meeting_'.$cleanName."_".strtolower(date('l_F_d_Y_H:m:s')).".txt", "w") or die("Unable to open file!");
                                                    foreach($vars as $key => $var) {
                                                        fwrite($myfile, "Key: " . $key . "  | Value: " . $var . "\n");
                                                    }
                                                    fwrite($myfile, "\n\n");
                                                    foreach($row as $key => $var) {
                                                        fwrite($myfile, "Key: " . $key . "  | Value: " . $var . "\n");
                                                    }
                                                    fclose($myfile);
                                                    
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            
        }
    }
    

    if($duplicate == "true") {
        
        echo "duplicate";
        return;
        
    } else {
    
        // Create a client connection to Google
        $client = new Google\Client();
        $client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/key.json');
        $client->addScope(Google\Service\Sheets::SPREADSHEETS);
        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
        
        $meeting_duration = 0;
        if($vars['meeting_end'] != null)
            $meeting_duration = hoursToMinutes(date("H:i",strtotime($vars['meeting_end']) - strtotime($vars['meeting_start'])));
        else
            $meeting_duration = 0;
        
        // remove periods and spaces from the student initials in the event they were fancy and added them.
        $studentName = str_replace( array('.', ' '), '', $vars['student_name']);
        
        $newRow = [
            date('F'),
            $vars['date'],
            $vars['psychologist'],
            $vars['district'],
            $vars['school'],
            $studentName,
            $vars['service_provided'],
            $price,
            $vars['lasid'],
            $vars['sasid'],
            $vars['meeting_date'],
            $vars['meeting_start'],
            $vars['meeting_end'],
            $meeting_duration,
            $vars['notes']
        ];
        
        $rows = [$newRow];
        $valueRange = new \Google_Service_Sheets_ValueRange();
        $valueRange->setValues($rows);
        $range = 'Transactions';
        $options = ['valueInputOption' => 'USER_ENTERED'];
        $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $options);
        
        // insert into the tl_transactions table
        $query = "INSERT INTO tl_transactions (tstamp, date, psychologist, district, school, student_name, service_provided, price, lasid, sasid, meeting_date, meeting_start, meeting_end, meeting_duration, notes, published)
                                   VALUES ('".time()."', '".$vars['date']."', '".$vars['psychologist']."', '".$vars['district']."', '".$vars['school']."', '".$vars['student_name']."', '".$vars['service_provided']."', '".$price."', '".$vars['lasid']."', '".$vars['sasid']."', '".$vars['meeting_date']."', '".$vars['meeting_start']."', '".$vars['meeting_end']."', '".$meeting_duration."', '".$vars['notes']."',  '1')";
        $result = $dbh->query($query);
        
     
        // display some text to return back to the ajax call
        echo "success";
    
    }
    
    
    
    // Converts our H:i format into pure minutes
    function hoursToMinutes($hours) 
    { 
        $minutes = 0; 
        if (strpos($hours, ':') !== false) 
        { 
            // Split hours and minutes. 
            list($hours, $minutes) = explode(':', $hours); 
        } 
        return $hours * 60 + $minutes; 
    } 
