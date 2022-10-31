<?php

namespace Bcs;

use Contao\Database;
use Google;

class Handler
{
    protected static $arrUserOptions = array();
    // DEV MODE: 0 - Off, 1 - On
    public static $dev_mode = 1;
    
    // when our form is submitted
    public function onProcessForm($submittedData, $formData, $files, $labels, $form)
    {

        // Form - Submit Invoice
        if($formData['formID'] == 'create_invoice') {
          
            // Get data = $submittedData['first_name']
           
            // Create a client connection to Google
            $client = new Google\Client();
            // Load our auth key
            $client->setAuthConfig('key.json');
            // Set our scope to use the Sheets service
            $client->addScope(Google\Service\Sheets::SPREADSHEETS);
            
            // configure the Sheets Service
            $service = new \Google_Service_Sheets($client);
            
            
            // lets test by reading data from our sheet
            //$spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
            //$spreadsheet = $service->spreadsheets->get($spreadsheetId);
            //echo '<pre>' , var_dump($spreadsheet) , '</pre>';
            
            // Lets test by adding a new entry into the Transactions table
            $newRow = [
                'October',
                $submittedData['school_id'],
                $submittedData['student_id'],
                $submittedData['service_provided'],
                $submittedData['meeting_date'],
                $submittedData['meeting_start'],
                $submittedData['meeting_end'],
                '999',
                $submittedData['notes'],
            ];
            $rows = [$newRow]; // you can append several rows at once
            $valueRange = new \Google_Service_Sheets_ValueRange();
            $valueRange->setValues($rows);
            $range = 'Transactions'; // the service will detect the last row of this sheet
            $options = ['valueInputOption' => 'USER_ENTERED'];
            $spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
            $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $options);
            
            
            
            
            
            // show the values for testing purposes
            if($dev_mode == 1)
                echo '<pre>' , var_dump($client) , '</pre>';
                
            if($dev_mode == 1)
                die();
          
        }
    }
    
    
    // when the fields are created
    public function onCompileFormFields($fields, $formId, $form)
    {
         if($formId == 'create_invoice') {
             foreach($fields as $field) {
                 echo "Field: " . $field . "<br>";
             }
         }
             
         die();
    }
    
    
}
