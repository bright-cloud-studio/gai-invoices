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
    $spreadsheetId = '1erZUWlCgpWd67E1PIwwKNCYT0yCm2QiV2DL28VA8oVU';
    
    // Store the name of the psychologist we are trying to share with
    $updateRow = [
        $vars['handoff'],
    ];
    $rows = [$updateRow];
    $valueRange = new \Google_Service_Sheets_ValueRange();
    $valueRange->setValues($rows);
    
    // make a range variable but keep it blank for now
    $range = '';
    
    // Update the range to match which of the five Shared fields this will fit into
    if($vars['shared_total'] == 0) { $range = '2022-2023!AB' . $vars['sheet_row']; }
    if($vars['shared_total'] == 1) { $range = '2022-2023!AD' . $vars['sheet_row']; }
    if($vars['shared_total'] == 2) { $range = '2022-2023!AF' . $vars['sheet_row']; }
    if($vars['shared_total'] == 3) { $range = '2022-2023!AH' . $vars['sheet_row']; }
    if($vars['shared_total'] == 4) { $range = '2022-2023!AJ' . $vars['sheet_row']; }
    
    $options = ['valueInputOption' => 'USER_ENTERED'];
    $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);

    // display some text to return back to the ajax call
    echo "success";
