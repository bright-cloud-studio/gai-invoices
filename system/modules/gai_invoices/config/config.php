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
$GLOBALS['FE_MOD']['gai_invoices']['mod_invoice_history'] 	        = 'Bcs\Module\ModInvoiceHistory';
$GLOBALS['FE_MOD']['gai_invoices']['mod_transaction_review'] 	    = 'Bcs\Module\ModTransactionReview';
$GLOBALS['FE_MOD']['gai_invoices']['mod_admin_review'] 	            = 'Bcs\Module\ModAdminReview';
$GLOBALS['FE_MOD']['gai_invoices']['mod_send_invoice_emails'] 	    = 'Bcs\Module\ModSendInvoiceEmails';

/** Add new notification type */
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['gai_invoices']['send_email'] = array
(
    'recipients'           => array('recipient_email'),
    'email_subject'        => array('recipient_name', 'invoice_number', 'billing_month'),
    'email_text'           => array('recipient_name', 'invoice_number', 'invoice_url', 'invoice_total', 'billing_month'),
    'email_html'           => array('recipient_name', 'invoice_number', 'invoice_url'),
    'file_name'            => array('invoice_filename', 'invoice_url'),
    'file_content'         => array('invoice_url', 'invoice_file'),
    'email_sender_name'    => array('sender_name'),
    'email_sender_address' => array('sender_address'),
    'email_recipient_cc'   => array('recipient_cc'),
    'email_replyTo'        => array('reply_to_address'),
    'attachment_tokens'    => array('invoice_token'),
    
);

/* Back end modules */
$GLOBALS['BE_MOD']['content']['transactions'] = array(
	'tables' => array('tl_transactions'),
	'icon'   => 'system/modules/gai_invoices/assets/icons/location.png',
	'exportLocations' => array('Bcs\Backend\TransactionsBackend', 'exportListings')
);

/* Models */
$GLOBALS['TL_MODELS']['tl_transactions'] = 'Bcs\Model\Transactions';

/* Hooks */
$GLOBALS['TL_HOOKS']['initializeSystem'][] 	= array('Bcs\Frontend\SendInvoiceEmails', 'sendEmails');


/* Cron Jobs */
$GLOBALS['TL_CRON']['daily'][] = ['Bcs\Backend\CronJobs', 'sendReminderEmails'];
