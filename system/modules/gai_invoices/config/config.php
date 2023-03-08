<?php

/**
 * Bright Cloud Studio's GAI Invoices
 *
 * Copyright (C) 2021 Bright Cloud Studio
 *
 * @package    bright-cloud-studio/gai-invoices
 * @link       https://www.brightcloudstudio.com/
 * @license    http://opensource.org/licenses/lgpl-3.0.html
**/

/* Front end modules */

// Create Invoice - List's a psychologists entries in the Work Assignment sheet
$GLOBALS['FE_MOD']['gai_invoices']['mod_work_assignments']          = 'Bcs\Module\ModWorkAssignments';
$GLOBALS['FE_MOD']['gai_invoices']['mod_add_meetings']              = 'Bcs\Module\ModAddMeetings';
$GLOBALS['FE_MOD']['gai_invoices']['mod_misc_billing']              = 'Bcs\Module\ModMiscBilling';
$GLOBALS['FE_MOD']['gai_invoices']['mod_work_assignment_history'] 	= 'Bcs\Module\ModWorkAssignmentHistory';
$GLOBALS['FE_MOD']['gai_invoices']['mod_transaction_review'] 	    = 'Bcs\Module\ModTransactionReview';
$GLOBALS['FE_MOD']['gai_invoices']['mod_admin_review'] 	            = 'Bcs\Module\ModAdminReview';
$GLOBALS['FE_MOD']['gai_invoices']['mod_send_invoice_emails'] 	    = 'Bcs\Module\ModSendInvoiceEmails';

/** Add new notification type */
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['gai_invoices']['send_email'] = array
(
    'recipients'           => array('recipient_email'),
    'email_subject'        => array('recipient_name', 'invoice_number'),
    'email_text'           => array('recipient_name', 'invoice_number', 'invoice_url'),
    'email_html'           => array('recipient_name', 'invoice_number', 'invoice_url'),
    'file_name'            => array('invoice_filename', 'invoice_url'),
    'file_content'         => array('invoice_url', 'invoice_file'),
    'email_sender_name'    => array('gai_from_name'),
    'email_sender_address' => array('gai_from_address'),
    'email_recipient_cc'   => array('recipient_cc'),
    'email_recipient_bcc'  => array('gai_bcc'),
    'email_replyTo'        => array('gai_from_address'),
    'attachment_tokens'    => array(),
    
);
