<?php
    
    // Start PHP session and include Composer, which also brings in our Google Sheets PHP stuffs
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
	
	use Contao\Model;
	use NotificationCenter\Model\Notification;
	
    
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
       "Yes",
    ];
    $rows = [$updateRow];
    $valueRange = new \Google_Service_Sheets_ValueRange();
    $valueRange->setValues($rows);
    $options = ['valueInputOption' => 'USER_ENTERED'];
    
    
    // Get date in desired format "06_19_1987"
    $date = date("m_d_y");
    // Write to our file, as email logging is complicated
    $myfile = fopen("email_".$date.".txt", "w") or die("Unable to open file!");
    
    
    
    $objNotification = Notification::findByPk(5);
    
    
    /*
    // loop through psys and send emails
    for ($x = 1; $x <= $vars['psy_total']; $x++) {
        
        if(!empty($vars['send_psy_'.$x])) {
            $to = $vars['email_psy_'.$x];
            //$to = "mark@brightcloudstudio.com";
            $subject = "Global Assessments, Inc. - A new invoice is ready for you";
            $txt = "Global Assessments, Inc. has a new invoice for you. Click the link below to view it: \n\n" . $vars['url_psy_'.$x];
            $headers = "From: ed@globalassessmentsinc.com" . "\r\n" ."CC: ed@globalassessmentsinc.com";
            
            $success = mail($to,$subject,$txt,$headers);
            if($success){
               
               // mail successful, update Sent on Sheets
               $range = 'Invoices - Psy!H' . $vars['row_id_psy_'.$x];
               $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
               
                fwrite($myfile, "Email Sent - " . $to . ", " . $headers . "\r\n");

            } else {
                error_log("Failure Sending Psy Mail - " . $vars['email_psy_'.$x]);
                fwrite($myfile, "Failure - " . $to . ", " . $headers . "\r\n");
            }

        }
        
    }
    
    // loop through schools and send emails
    for ($x = 1; $x <= $vars['school_total']; $x++) {
        
        if(!empty($vars['send_school_'.$x])) {
            $to = $vars['email_school_'.$x];
            //$to = "mark@brightcloudstudio.com";
            $subject = "Global Assessments, Inc. - A new invoice is ready for you";
            $txt = "Global Assessments, Inc. has a new invoice for you. Click the link below to view it: \n\n" . $vars['url_school_'.$x];
            
            $headers = '';
            
            if($vars['cc_school_'.$x] != '')
                $headers = "From: ed@globalassessmentsinc.com" . "\r\n" ."CC: ". $vars['cc_school_'.$x] . ", ed@globalassessmentsinc.com";
            else
                $headers = "From: ed@globalassessmentsinc.com" . "\r\n" ."CC: ed@globalassessmentsinc.com";
            
            $success = mail($to,$subject,$txt,$headers);
            if($success){
               
               // mail successful, update Sent on Sheets
               $range = 'Invoices - School!I' . $vars['row_id_school_'.$x];
               $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
               
               fwrite($myfile, "Email Sent - " . $to . ", " . $headers . "\r\n");
               
            } else {
                error_log("Failure Sending School Mail - " . $vars['email_school_'.$x]);
                fwrite($myfile, "Failure - " . $to . ", " . $headers . "\r\n");
            }
            
        }
        
    }
    
    */

    fclose($myfile);
    
    // display some text to return back to the ajax call
    echo "success";
