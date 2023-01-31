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
    
    $meeting_duration = 0;
    if($vars['meeting_end'] != null)
        $meeting_duration = hoursToMinutes(date("H:i",strtotime($vars['meeting_end']) - strtotime($vars['meeting_start'])));
    else
        $meeting_duration = 0;
    
    $newRow = [
        date('F'),
        $vars['date'],
        $vars['psychologist'],
        $vars['district'],
        $vars['school'],
        $vars['student_name'],
        $vars['service_provided'],
        $vars['price'],
        $vars['lasid'],
        $vars['sasid'],
        $vars['meeting_date'],
        $vars['meeting_start'],
        $vars['meeting_end'],
        $meeting_duration,
        $vars['notes']
    ];
    
    $rows = [$newRow];
    $valueRange = new \Google_Service_Sheets_ValueRange();
    $valueRange->setValues($rows);
    $range = 'Transactions';
    $options = ['valueInputOption' => 'USER_ENTERED'];
    $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $options);
 
    // display some text to return back to the ajax call
    echo "success";
    
    
    // Converts our H:i format into pure minutes
    function hoursToMinutes($hours) 
    { 
        $minutes = 0; 
        if (strpos($hours, ':') !== false) 
        { 
            // Split hours and minutes. 
            list($hours, $minutes) = explode(':', $hours); 
        } 
        return $hours * 60 + $minutes; 
    } 
