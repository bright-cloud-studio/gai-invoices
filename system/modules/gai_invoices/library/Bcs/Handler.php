<?php

namespace Bcs;

use Contao\Database;
use Google;

class Handler
{
    protected static $arrUserOptions = array();
    public static $dev_mode = true;
    
    // our google api stuffs
    protected $client;
    protected $service;
    public static $spreadsheetId;
    
    
    function __construct() {
        // Create a client connection to Google
        $this->$client = new Google\Client();
        // Load our auth key
        $this->$client->setAuthConfig('key.json');
        // Set our scope to use the Sheets service
        $this->$client->addScope(Google\Service\Sheets::SPREADSHEETS);
        // Assign our client to a service
        $this->$service = new \Google_Service_Sheets($this->$client);
        // Set the ID for our specific spreadsheet
        Handler::$spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';

    }
    
    
    // HOOK: when a form is submitted and being processed
    public function onProcessForm($submittedData, $formData, $files, $labels, $form)
    {
        // detect the correct form based on the id
        if($formData['formID'] == 'create_invoice') {
            
            $objUser = \FrontendUser::getInstance();
           
            
            // Build out a "Transactions" row using the form data
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
                $objUser->id
            ];
            
            // append the new row to the existing ones
            $rows = [$newRow];
            // Set the modified rows
            $valueRange = new \Google_Service_Sheets_ValueRange();
            $valueRange->setValues($rows);
            $range = 'Transactions';
            $options = ['valueInputOption' => 'USER_ENTERED'];
            $this->$service->spreadsheets_values->append(Handler::$spreadsheetId, $range, $valueRange, $options);

            // show variables in dev mode            
            if(Handler::$dev_mode) {
                echo '<pre>' , var_dump($this->$client) , '</pre>';
                die();
            }
            

        }
    }
    
    
    // HOOK: when compiling the form fields so they can be modified
    public function onCompileFormFields($fields, $formId, $form)
    {
        
        // If this is our submit invoice form
        if($form->id == 2) {
            
            // loop through the form's fields
            foreach($fields as $field) {
                
                // modify our school_id field
                if($field->name == "school_id") {
                    
                    $range = 'Schools!A2:D';
                    $response = $this->$service->spreadsheets_values->get(Handler::$spreadsheetId, $range);
                    $schools = $response->getValues();
                    
                    foreach($schools as $school) {
                        $options[$school[0]]['value'] = $school[0];
                        $options[$school[0]]['label'] = $school[2];
                    }
                    
                    // override the options with our new ones
                    $field->options = serialize($options);
                }
                
                // modify our student_id field
                if($field->name == "student_id") {
                    
                    $range = 'Students!A2:D';
                    $response = $this->$service->spreadsheets_values->get(Handler::$spreadsheetId, $range);
                    $students = $response->getValues();
                    
                    foreach($students as $student) {
                        $options[$student[0]]['value'] = $student[0];
                        $options[$student[0]]['label'] = $student[1];
                    }
                    
                    // override the options with our new ones
                    $field->options = serialize($options);
                }
                
                // modify our service_provided field
                if($field->name == "service_provided") {
                    
                    $range = 'Services!A2:D';
                    $response = $this->$service->spreadsheets_values->get(Handler::$spreadsheetId, $range);
                    $services = $response->getValues();
                    
                    foreach($services as $service) {
                        $options[$service[0]]['value'] = $service[0];
                        $options[$service[0]]['label'] = $service[2];
                    }
                    
                    // override the options with our new ones
                    $field->options = serialize($options);
                }
            
            }
            
            return $fields;
        }

        return $fields;
    }
    
    
}
