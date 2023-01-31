<?php
    
    // Start PHP session and include Composer, which also brings in our Google Sheets PHP stuffs
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
	
	$vars = $_POST;
	
	

    
    // Create a client connection to Google
    $client = new Google\Client();
    $client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/key.json');
    $client->addScope(Google\Service\Sheets::SPREADSHEETS);
    $service = new \Google_Service_Sheets($client);
    $spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
    
    
    //$range = 'Transactions!A' . $vars['row_id'];
    $range = 'Transactions!' . $vars['row_id'] . ':' . $vars['row_id'];
    $requestBody = new Google_Service_Sheets_ClearValuesRequest();
    $response = $service->spreadsheets_values->clear($spreadsheetId, $range, $requestBody);
    
    

    // display some text to return back to the ajax call
    echo "success";
