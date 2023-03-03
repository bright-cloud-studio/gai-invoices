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
    
    // Get all of the Psychologists from the Psychologists master sheet
    $range = 'Psychologists';
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();
    
    // Store our names in an array
    $names = array();
    
    // count our index each tick of the foreach loop
    $names_index = 0;
    foreach($values as $entry) {
        
        // if this is not the first tick, as the first is only the table headers
        if($names_index >= 1) {
            // Add our name to the array.
            // No need to check for duplicates, as there is only one psychologist with each name
            array_push($names, $entry['1']);
        }
        
        // tick up our index counter
        $names_index++;
        
    }
    
    // encode our PHP array in a JSON format and send them psychologists back to our AJAX call
    echo json_encode($names);
