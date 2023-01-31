<?php
    
    // Start PHP session and include Composer, which also brings in our Google Sheets PHP stuffs
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
    

    // Create a client connection to Google
    $client = new Google\Client();
    $client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/key.json');
    $client->addScope(Google\Service\Sheets::SPREADSHEETS);
    $service = new \Google_Service_Sheets($client);
    $spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
    
   
   
    $range = 'Schools';
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();
   
    $districts = array();
   
    $districts_index = 0;
    foreach($values as $entry) {
        
        if($districts_index >= 1) {
            array_push($districts, $entry['2']);
        }
        
        $districts_index++;
        
    }
    
    echo json_encode($districts);
