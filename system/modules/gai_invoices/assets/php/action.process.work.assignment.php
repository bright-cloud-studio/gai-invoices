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
    
    
    // For each transaction fieldset on the form, create a new row in the Transactions table on Sheets
    for ($x = 1; $x <= $vars["transactions"]; $x++) {
        
        //if this is the first transaction
        if($x == 1) {
            $newRow = [
                'October',
                $vars['district'],
                $vars['student_name'],
                $vars['service_provided'],
                $vars['price'],
                '0',
                '0',
                '0',
                $vars['notes']
            ];
            
            $rows = [$newRow];
            $valueRange = new \Google_Service_Sheets_ValueRange();
            $valueRange->setValues($rows);
            $range = 'Transactions';
            $options = ['valueInputOption' => 'USER_ENTERED'];
            $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $options);
        } else {
            $newRow = [
                'October',
                $vars['district'],
                $vars['student_name'],
                $vars['service_provided_' . $x],
                $vars['price_' . $x],
                '0',
                '0',
                '0',
                $vars['notes_' . $x]
            ];

            $rows = [$newRow];
            $valueRange = new \Google_Service_Sheets_ValueRange();
            $valueRange->setValues($rows);
            $range = 'Transactions';
            $options = ['valueInputOption' => 'USER_ENTERED'];
            $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $options);
        }

    }
    
    
    // Mark this Work Assignment as Processed
    $updateRow = [
        '1'
    ];
    $rows = [$updateRow];
    $valueRange = new \Google_Service_Sheets_ValueRange();
    $valueRange->setValues($rows);
    $range = 'Work Assignment!X' . $vars['sheet_row']; // where the replacement will start, here, first column and second line
    $options = ['valueInputOption' => 'USER_ENTERED'];
    $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
    

    // display some text to return back to the ajax call
    echo "success";
