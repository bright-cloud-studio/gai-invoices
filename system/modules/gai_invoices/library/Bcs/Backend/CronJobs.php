<?php

namespace Bcs\Backend;

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
       
        // Add a log entry so we know things are going as expected
        \Controller::log('GAI IMPORT: Importing from Sheets', __CLASS__ . '::' . __FUNCTION__, 'GENERAL');
        
        // Connect to Sheets
        
        
        
        
        // Import Psychologist data from the "Psychologists" sheet
            
            // If existing entry, update it
        
            // Otherwise, create new entry
        
        
        
        
        // Import Services data from the "Services" sheet
        
            // If existing entry, update it
        
            // Otherwise, create new entry
        
        
        
        
    }
}
