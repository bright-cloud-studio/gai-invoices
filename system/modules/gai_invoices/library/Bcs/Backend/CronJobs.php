<?php

namespace Bcs\Backend;

use Google;
use Contao\System;
use Contao\MemberModel;

class CronJobs extends System
{
    
    // If there are X $days_before the end of the month then send reminder emails to all psychologists
    public function sendReminderEmails(): void
    {
       
        // Add a log entry so we know things are going as expected
        \Controller::log('GAI: Sending Reminder Emails', __CLASS__ . '::' . __FUNCTION__, 'GENERAL');
        
        // if our date is X days before end of month
        $days_before = 7;
        $how_many_days = date('t') - date('j');
        
        if($days_before == $how_many_days) {
            
            // add Log that it is the right day!
            \Controller::log('GAI: Today is the Reminder day!', __CLASS__ . '::' . __FUNCTION__, 'GENERAL');
            
            // get all of the Members
            $members = MemberModel::findAll();
            
            foreach($members as $member) {

                // Get the email
                $objNotification = \NotificationCenter\Model\Notification::findByPk(9);
                if (null !== $objNotification) {
                    
                    // Sender info
                    $arrTokens['sender_name'] = 'Global Assessments, Inc';
                    $arrTokens['sender_address'] = 'billing@globalassessmentsinc.com';
                    $arrTokens['reply_to_address'] = 'billing@globalassessmentsinc.com';
                    
                    // Recipient info
                    $arrTokens['recipient_name'] = $member->firstname . " " . $member->lastname;
                    $arrTokens['recipient_email'] = $member->email;
                    $arrTokens['recipient_cc'] = 'ed@globalassessmentsinc.com';

                    // Send out the email using our tokens
                    $objNotification->send($arrTokens);
                    
                }
            }
        }
    }
    
    public function importPsychologistsAndServices(): void
    {
       
        // Log entry to confirm this is working
        \Controller::log('GAI IMPORT: Importing from Sheets', __CLASS__ . '::' . __FUNCTION__, 'GENERAL');
        
        // Establish connection to Sheets
        $client = new Google\Client();
        $client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/key.json');
        $client->addScope(Google\Service\Sheets::SPREADSHEETS);
        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
        
        // Get "Psychologists" data from Sheets
        $range = 'Psychologists';
        $response = $this->$service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();
        
        // Loop through our Psychologists
        $counter = 0;
        foreach($values as $entry) {
            
            // Connect to Contao's database
            $dbh = new mysqli("localhost", "globalassinc_user", "Z2rc^wQ}98TS9mtl5y", "globalassinc_contao_4_13");
            if ($dbh->connect_error) {
                die("Connection failed: " . $dbh->connect_error);
            }
            
            // Insert our Psychologist information into the DBH
            $query = "INSERT INTO tl_transactions (tstamp, invoices, name, address, address_2, city, state, zip, email, last_month_processed, price_tier)
                      VALUES ('".time()."', '".$entry[0]."', '".$entry[1]."', '".$entry[2]."', '".$entry[3]."', '".$entry[4]."', '".$entry[5]."', '".$entry[6]."', '".$entry[7]."', '".$entry[8]."', '".$entry[9]."' )";
            $result = $dbh->query($query);
            
            $counter++;
            
        }
        
        \Controller::log('GAI: Imported ('.$counter.') Psychologists', __CLASS__ . '::' . __FUNCTION__, 'GENERAL');
        
        
        

    }
}
