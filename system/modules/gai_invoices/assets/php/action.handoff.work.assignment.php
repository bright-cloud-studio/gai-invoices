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
        $vars['handoff'],
    ];
    $rows = [$updateRow];
    $valueRange = new \Google_Service_Sheets_ValueRange();
    $valueRange->setValues($rows);
    
    
    //$range = 'Work Assignment!D' . $vars['sheet_row'];
    
    $range = '';
    
    if($vars['shared_total'] == 0) { $range = 'Work Assignment!Z' . $vars['sheet_row']; }
    if($vars['shared_total'] == 1) { $range = 'Work Assignment!AB' . $vars['sheet_row']; }
    if($vars['shared_total'] == 2) { $range = 'Work Assignment!AD' . $vars['sheet_row']; }
    
    
    
    $options = ['valueInputOption' => 'USER_ENTERED'];
    $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
    

    // display some text to return back to the ajax call
    echo "success";
