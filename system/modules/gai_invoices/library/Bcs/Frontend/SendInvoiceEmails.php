<?php

namespace Bcs\Frontend;


use Google;

use Contao\System;
use Contao\Frontend as Contao_Frontend;



class SendInvoiceEmails extends Contao_Frontend {

    public function sendEmails() {
        
        if (substr(\Environment::get('request'), 39, 27) == "send-invoice-emails-success") {
            
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
           
            
            
            $sent_to_name = '';
            $sent_to_inv_num = '';
            
            // Loop through all of the psys
            for ($i = 1; $i <= $_POST['psy_total']; $i++) {
                
                if($_POST['send_psy_' . $i] == 'yes') {
                    
                    // send our user their email
                    $objNotification = \NotificationCenter\Model\Notification::findByPk(5);
                    if (null !== $objNotification) {
                        
                        $arrTokens['gai_from_name'] = 'Global Assessments, Inc';
                        $arrTokens['gai_from_address'] = 'billing@globalassessmentsinc.com';
                        
                        $arrTokens['recipient_name'] = $_POST['name_'.$i];
                        $sent_to_name = $arrTokens['recipient_name'];
                        
                        $arrTokens['recipient_email'] = $_POST['email_psy_'.$i];
                        $arrTokens['recipient_cc'] = 'mark@brightcloudstudio.com';
    
                        $arrTokens['invoice_number'] = $_POST['invoice_number_'.$i];
                        $sent_to_inv_num = $arrTokens['invoice_number'];
                        $arrTokens['invoice_url'] = $_POST['url_psy_'.$i];
                        
                        $objNotification->send($arrTokens); // Language is optional
                        
                        
                        
                        
                        $range = 'Invoices - Psy!H' . $_POST['row_id_psy_'.$i];
                        $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
                        
                        
                    }
                    
                    // Send Ed his own notification
                    $objNotification = \NotificationCenter\Model\Notification::findByPk(6);
                    if (null !== $objNotification) {
                        
                        $arrTokens['gai_from_name'] = 'Global Assessments, Inc';
                        $arrTokens['gai_from_address'] = 'billing@globalassessmentsinc.com';
                        
                        $arrTokens['recipient_name'] = $sent_to_name;
                        $arrTokens['recipient_email'] = 'ed@globalassessmentsinc.com';
    
                        $arrTokens['invoice_number'] = $sent_to_inv_num;
                        
                        $objNotification->send($arrTokens); // Language is optional
                    }
                    
                }

            }
            
            
            
            // Loop through all of the schools
            for ($i = 1; $i <= $_POST['school_total']; $i++) {
                
                if($_POST['send_school_' . $i] == 'yes') {
                    
                    // send our user their email
                    $objNotification = \NotificationCenter\Model\Notification::findByPk(5);
                    if (null !== $objNotification) {
                        
                        $arrTokens['gai_from_name'] = 'Global Assessments, Inc';
                        $arrTokens['gai_from_address'] = 'billing@globalassessmentsinc.com';
                        
                        $arrTokens['recipient_name'] = $_POST['district_name_'.$i] . " - " . $_POST['school_name_'.$i];
                        $sent_to_name = $arrTokens['recipient_name'];
                        
                        /*
                        $arrTokens['recipient_email'] = $_POST['email_school_'.$i];
                        $arrTokens['recipient_cc'] = '';
                        if($_POST['cc_school_'.$i] != '')
                            $arrTokens['recipient_cc'] = $_POST['cc_school_'.$i];
                        */
                        
                        $arrTokens['recipient_email'] = "mark@brightcloudstudio.com";
                        $arrTokens['recipient_cc'] = 'stjeanmark@gmail.com';
    
    
                        $arrTokens['invoice_number'] = $_POST['invoice_number_'.$i];
                        $sent_to_inv_num = $arrTokens['invoice_number'];
                        $arrTokens['invoice_url'] = $_POST['url_school_'.$i];
                        
                        $objNotification->send($arrTokens); // Language is optional
                        

                        $range = 'Invoices - School!I' . $_POST['row_id_school_'.$x];
                        $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
                        
                        
                    }
                    
                    // Send Ed his own notification
                    $objNotification = \NotificationCenter\Model\Notification::findByPk(6);
                    if (null !== $objNotification) {
                        
                        $arrTokens['gai_from_name'] = 'Global Assessments, Inc';
                        $arrTokens['gai_from_address'] = 'billing@globalassessmentsinc.com';
                        
                        $arrTokens['recipient_name'] = $sent_to_name;
                        $arrTokens['recipient_email'] = 'ed@globalassessmentsinc.com';
    
                        $arrTokens['invoice_number'] = $sent_to_inv_num;
                        
                        $objNotification->send($arrTokens); // Language is optional
                    }
                    
                }

            }
			
			
			
			
			
			
        }

        
    }

}
