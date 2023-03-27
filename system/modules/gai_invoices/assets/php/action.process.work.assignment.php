<?php
    
    // Start PHP session and include Composer, which also brings in our Google Sheets PHP stuffs
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
    
    
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
    $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . '/../transaction_logs/'.$cleanName."_".date('m_d_Y_hia').".txt", "w") or die("Unable to open file!");
    // loop through our $vars and write the key => value to our created file
    foreach($vars as $key => $var) {
        fwrite($myfile, "Key: " . $key . "  | Value: " . $var . "\n");
    }
    // were done logging, close the file we just created
    fclose($myfile);
    
    
    
    

    // Create a client connection to Google
    $client = new Google\Client();
    $client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/key.json');
    $client->addScope(Google\Service\Sheets::SPREADSHEETS);
    $service = new \Google_Service_Sheets($client);
    $spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
    
    $meeting_duration = 0;
    
    
    $service_provided = '';
    if(isset($vars['service_provided'])) {
        $service_provided = $vars['service_provided'];
    }

            
	if($vars['meeting_end'] != null)
		$meeting_duration = hoursToMinutes(date("H:i",strtotime($vars['meeting_end']) - strtotime($vars['meeting_start'])));
	else
		$meeting_duration = 0;
		
	$service_provided = '';
	if(isset($vars['service_provided']))
	    $service_provided = $vars['service_provided'];
	
	$newRow = [
		date('F'),
		$vars['date'],
		$vars['psychologist'],
		$vars['district'],
		$vars['school'],
		$vars['student_name'],
		$service_provided,
		$price,
		$vars['lasid'],
		$vars['sasid'],
		$vars['meeting_date'],
		$vars['meeting_start'],
		$vars['meeting_end'],
		$meeting_duration,
		$vars['notes'],
		'', // Reviewed
		'', // Deleted
		'', // Misc Billing
		$vars['sheet_row'] // Work Assignment ID
	];
	
	$rows = [$newRow];
	$valueRange = new \Google_Service_Sheets_ValueRange();
	$valueRange->setValues($rows);
	$range = 'Transactions';
	$options = ['valueInputOption' => 'USER_ENTERED'];
	
	if(!isset($vars['complete_work_assignment']))
        $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $options);



    
    
    // Mark this Work Assignment as Processed
    $processed = 1;
    if(isset($vars['complete_work_assignment'])) {
        if($vars['complete_work_assignment'] == "yes") { $processed = 2; }
    }
    $updateRow = [
        $processed
    ];
    $rows = [$updateRow];
    $valueRange = new \Google_Service_Sheets_ValueRange();
    $valueRange->setValues($rows);
    
    $range = 'Fall!AA' . $vars['sheet_row'];
    $spreadsheetId = '1erZUWlCgpWd67E1PIwwKNCYT0yCm2QiV2DL28VA8oVU';
    
    if($vars['psychologist'] == $vars['shared_1']) { $range = 'Fall!AC' . $vars['sheet_row']; }
    if($vars['psychologist'] == $vars['shared_2']) { $range = 'Fall!AE' . $vars['sheet_row']; }
    if($vars['psychologist'] == $vars['shared_3']) { $range = 'Fall!AG' . $vars['sheet_row']; }
    if($vars['psychologist'] == $vars['shared_4']) { $range = 'Fall!AI' . $vars['sheet_row']; }
    if($vars['psychologist'] == $vars['shared_5']) { $range = 'Fall!AK' . $vars['sheet_row']; }
    
    $options = ['valueInputOption' => 'USER_ENTERED'];
    $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
    
    
    
    
    
    // insert into the tl_transactions table
    $dbh = new mysqli("localhost", "globalassinc_user", "Z2rc^wQ}98TS9mtl5y", "globalassinc_contao_4_13");
    if ($dbh->connect_error) {
        die("Connection failed: " . $dbh->connect_error);
    }
    $query = "INSERT INTO tl_transactions (tstamp, date, psychologist, district, school, student_name, service_provided, price, lasid, sasid, meeting_date, meeting_start, meeting_end, meeting_duration, notes, sheet_row, published)
                                   VALUES ('".time()."', '".$vars['date']."', '".$vars['psychologist']."', '".$vars['district']."', '".$vars['school']."', '".$vars['student_name']."', '".$service_provided."', '".$price."', '".$vars['lasid']."', '".$vars['sasid']."', '".$vars['meeting_date']."', '".$vars['meeting_start']."', '".$vars['meeting_end']."', '".$meeting_duration."', '".$vars['notes']."', '".$vars['sheet_row']."',  '1')";
    $result = $dbh->query($query);




    // display some text to return back to the ajax call
    echo "success";
    
    
    
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
