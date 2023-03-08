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
    'email_subject'        => array('domain', 'member_*', 'recipient_email'),
    'email_text'           => array('domain', 'member_*', 'recipient_email'),
    'email_html'           => array('domain', 'member_*', 'recipient_email'),
    'file_name'            => array('domain', 'member_*', 'recipient_email'),
    'file_content'         => array('domain', 'member_*', 'recipient_email'),
    'email_sender_name'    => array('recipient_email'),
    'email_sender_address' => array('recipient_email'),
    'email_recipient_cc'   => array('recipient_email'),
    'email_recipient_bcc'  => array('recipient_email'),
    'email_replyTo'        => array('recipient_email'),
    'attachment_tokens'    => array(),
    
);
