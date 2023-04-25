<?php
    
    // Start PHP session and include Composer, which also brings in our Google Sheets PHP stuffs
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
    
    // Store the passed form values
    $vars = $_POST;
    
    // create a file with the name "psy_datetime.txt" to log our transaction data
    $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . '/../delete_transaction/'.$vars['psychologist']."_".strtolower(date('l_F_d_Y_H:m:s')).".txt", "w") or die("Unable to open file!");
    // loop through our $vars and write the key => value to our created file
    foreach($vars as $key => $var) {
        fwrite($myfile, "Key: " . $key . "  | Value: " . $var . "\n");
    }
    // were done logging, close the file we just created
    fclose($myfile);
    
    
    
    // we need to update our local transactions to reflect the change
    
    

    // Create a client connection to Google
    $client = new Google\Client();
    $client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/key.json');
    $client->addScope(Google\Service\Sheets::SPREADSHEETS);
    $service = new \Google_Service_Sheets($client);
    $spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
    
    // Flag: 1 = Deleted, 0 or NULL = Not Deleted
    $updateRow = [
       "1",
    ];
    $rows = [$updateRow];
    $valueRange = new \Google_Service_Sheets_ValueRange();
    $valueRange->setValues($rows);
    
    $options = ['valueInputOption' => 'USER_ENTERED'];
    
    $range = 'Transactions!Q' . $vars['row_id'];
    $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);

    
    // if our work assignment id is present
    if($vars['work_assignment_id'] != 0) {
        
        // update the work assignment master list to set this to no longer be hidden
        $updateRow = [
            "1",
        ];
        $rows = [$updateRow];
        $valueRange = new \Google_Service_Sheets_ValueRange();
        $valueRange->setValues($rows);
        $range = '2022-2023!AA' . $vars['work_assignment_id'];
        $spreadsheetId = '1erZUWlCgpWd67E1PIwwKNCYT0yCm2QiV2DL28VA8oVU';
        $options = ['valueInputOption' => 'USER_ENTERED'];
        $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
        
    }
    
    
    
    // Connect to DB
    $dbh = new mysqli("localhost", "globalassinc_user", "Z2rc^wQ}98TS9mtl5y", "globalassinc_contao_4_13");
    if ($dbh->connect_error) {
        die("Connection failed: " . $dbh->connect_error);
    }
    // insert into the tl_transactions table
    $query = "UPDATE tl_transactions SET deleted='1' WHERE psychologist='".$vars['psychologist']."' AND district='".$vars['district']."' AND school='".$vars['school']."' AND student_name='".$vars['student']."' AND service_provided='".$vars['service']."' AND price='".$vars['price']."' AND meeting_date='".$vars['meeting_date']."' AND meeting_start='".$vars['meeting_start']."' AND meeting_end='".$vars['meeting_end']."'";
    $result = $dbh->query($query);
   
    // display some text to return back to the ajax call
    echo "success";
