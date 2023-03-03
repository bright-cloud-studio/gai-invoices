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
    
   // Get all of the School entries on the master list
    $range = 'Schools';
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();
    
    // Store our names in this array
    $names = array();
    
    // Count our index each tick of the foreach loop
    $names_index = 0;
    foreach($values as $entry) {
        
        // Only do stuff after our first run, as the first run is just the table header information
        if($names_index >= 1) {
            // if our value isnt empty
            if($entry['2'] != null) {
                // if this name isn't already in the array
                if(!in_array($entry['2'], $names)) {
                    // add our value to our Names array
                    array_push($names, $entry['2']);
                }
            }
        }
        // tick up our index counter
        $names_index++;

    }
    
    // encode our PHP array into a JSON format and send that puppy back to the AJAX call
    echo json_encode($names);
