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
    
    // Mark this Work Assignment as Processed
    $updateRow = [
       "1",
    ];
    $rows = [$updateRow];
    $valueRange = new \Google_Service_Sheets_ValueRange();
    $valueRange->setValues($rows);
    
    $options = ['valueInputOption' => 'USER_ENTERED'];
    
    
    
    for ($x = 1; $x <= $vars['trans_total']; $x++) {
        $range = 'Transactions!P' . $vars['row_id_'.$x];
        $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
    }
    
    
    /*
    foreach($vars['row_id'] as $row_id) {
        $range = 'Work Assignment!O' . $row_id;
        $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
    }
    */

    // display some text to return back to the ajax call
    echo "success";
