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
    $options = ['valueInputOption' => 'USER_ENTERED'];
    
    // Get the IDs of which rows need to be updated, split them into an array and loop through them
    $rowIds = preg_split ("/\,/", $vars['rows']); 
    foreach($rowIds as $id) {
        
        // if the delete checkbox isn't ticked
        if(empty($vars['delete_'.$id])) {
            
            // update the service provided and price that was entered
            $updateRow = [
                $vars['service_provided_'.$id],
                $vars['price_'.$id]
            ];
            $rows = [$updateRow];
            $valueRange = new \Google_Service_Sheets_ValueRange();
            $valueRange->setValues($rows);
            $range = 'Transactions!G' . $id . ':H' . $id;
            
            $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
            
        } else {
            
            // the delete checkbox was ticked, go ahead and flag the row as Deleted.
            // due to issues with ranges and not being able to update rows out of order, just flag as Deleted and ignore the other update
            $updateRow = [
                "1",
            ];
            $rows = [$updateRow];
            $valueRange = new \Google_Service_Sheets_ValueRange();
            $valueRange->setValues($rows);
            $range = 'Transactions!Q' . $id;
            
            $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
            
        }

    }
    
    // display some text to return back to the ajax call
    echo "success";
