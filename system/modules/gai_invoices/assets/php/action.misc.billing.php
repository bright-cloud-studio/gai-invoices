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
    
    // first remove dollar sign
    $price = str_replace('$','',$vars['price']);
    // remove decimal and trailing numbers
    $price = floor($price);
    
    // store our newly created Transaction with the filled in data.
    // transactions have more fields than we need so fill in the blanks with ''
    $newRow = [
        date('F'),
        $vars['date'],
        $vars['psychologist'],
        '',
        '',
        '',
        $vars['service_provided'],
        $price,
        '',
        '',
        '',
        '',
        '',
        '',
        $vars['notes'],
        '',
        '',
        $vars['label']
    ];
    
    $rows = [$newRow];
    $valueRange = new \Google_Service_Sheets_ValueRange();
    $valueRange->setValues($rows);
    $range = 'Transactions';
    $options = ['valueInputOption' => 'USER_ENTERED'];
    $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $options);
 
    // display some text to return back to the ajax call
    echo "success";
    
