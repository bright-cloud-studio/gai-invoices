<?php

    // Start PHP session and include Composer, which also brings in our Google Sheets PHP stuffs
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
    
    // Store the passed form values
    $vars = $_POST;

    // Create a client connection to Google
    $client = new Google\Client();
    $client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/key.json');
    $client->addScope(Google\Service\Sheets::SPREADSHEETS);
    $service = new \Google_Service_Sheets($client);
    $spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
    
    
    $meeting_duration = 0;
    
    $price = '';
    if($vars['price'] != '') {
        // first remove dollar sign
        $price = str_replace('$','',$vars['price']);
        // remove decimal and trailing numbers
        $price = floor($price);
    }
    
    
    
    // For each transaction fieldset on the form, create a new row in the Transactions table on Sheets
    for ($x = 1; $x <= $vars["transactions"]; $x++) {
        
        //if this is the first transaction
        if($x == 1) {
            
            if($vars['meeting_end'] != null)
                $meeting_duration = hoursToMinutes(date("H:i",strtotime($vars['meeting_end']) - strtotime($vars['meeting_start'])));
            else
                $meeting_duration = 0;
            
            $newRow = [
                date('F'),
                $vars['date'],
                $vars['psychologist'],
                $vars['district'],
                $vars['school'],
                $vars['student_name'],
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
            if($vars['price'] != '') {
                $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $options);
            }
        } else {
            
            if($vars['meeting_end_' . $x] != null)
                $meeting_duration = hoursToMinutes(date("H:i",strtotime($vars['meeting_end_' . $x]) - strtotime($vars['meeting_start_' . $x])));
            else
                $meeting_duration = 0;
                
             // first remove dollar sign
            $price = str_replace('$','',$vars['price_' . $x]);
            // remove decimal and trailing numbers
            $price = floor($price);
            
            $newRow = [
                date('F'),
                $vars['date'],
                $vars['psychologist'],
                $vars['district'],
                $vars['school'],
                $vars['student_name'],
                $vars['service_provided_' . $x],
                $vars['price_' . $x],
                $vars['lasid'],
                $vars['sasid'],
                $vars['meeting_date_' . $x],
                $vars['meeting_start_' . $x],
                $vars['meeting_end_' . $x],
                $meeting_duration,
                $vars['notes_' . $x]
            ];

            $rows = [$newRow];
            $valueRange = new \Google_Service_Sheets_ValueRange();
            $valueRange->setValues($rows);
            $range = 'Transactions';
            $options = ['valueInputOption' => 'USER_ENTERED'];
            if($vars['price'] != '') {
                $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $options);
            }
        }

    }
    
    
    // Mark this Work Assignment as Processed
    $processed = 1;
    if($vars['complete_work_assignment'] == "yes") { $processed = 2; }
    $updateRow = [
        $processed
    ];
    $rows = [$updateRow];
    $valueRange = new \Google_Service_Sheets_ValueRange();
    $valueRange->setValues($rows);
    
    //$range = 'Work Assignment!Y' . $vars['sheet_row']; // where the replacement will start, here, first column and second line
    
    $range = 'Fall!AA' . $vars['sheet_row'];
    $spreadsheetId = '1erZUWlCgpWd67E1PIwwKNCYT0yCm2QiV2DL28VA8oVU';
    
    if($vars['psychologist'] == $vars['shared_1']) { $range = 'Work Assignment!AB' . $vars['sheet_row']; }
    if($vars['psychologist'] == $vars['shared_2']) { $range = 'Work Assignment!AD' . $vars['sheet_row']; }
    if($vars['psychologist'] == $vars['shared_3']) { $range = 'Work Assignment!AF' . $vars['sheet_row']; }
    if($vars['psychologist'] == $vars['shared_4']) { $range = 'Work Assignment!AH' . $vars['sheet_row']; }
    if($vars['psychologist'] == $vars['shared_5']) { $range = 'Work Assignment!AJ' . $vars['sheet_row']; }
    
    
    $options = ['valueInputOption' => 'USER_ENTERED'];
    $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
    

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
