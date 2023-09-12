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
    
    // Get all of the Schools from the Schools master sheet
    $range = 'Schools';
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();
    
    // Store the Schools in our array
    $schools = array();
    
    // count our ticks through the foreach loop
    $schools_index = 0;
    foreach($values as $entry) {
        
        // ignore our first run, its just headers
        if($schools_index >= 1) {
            // if this school is apart of our selected district
            if($entry['2'] == $vars['selected_district']) {
                // if the name isnt empty, that would be bad
                if($entry['3'] != null) {
                    // check if this is already stored or not
                    if(!in_array($entry['3'], $schools)) {
                        // this isn't already stored, add it to our list
                        array_push($schools, $entry['3']);
                    }
                }
            }
        }
        
        // tick up our index counter
        $schools_index++;
    }
    
    // encode our PHP into a JSON format and fire it back to AJAX, as it's waiting patiently for this information
    echo json_encode($schools);
