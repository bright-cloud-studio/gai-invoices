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

// Manually Approved Transactions
$GLOBALS['FE_MOD']['gai_invoices']['mod_misc_billing']              = 'Bcs\Module\ModMiscBilling';
$GLOBALS['FE_MOD']['gai_invoices']['mod_misc_travel_expenses']      = 'Bcs\Module\ModMiscTravelExpenses';
$GLOBALS['FE_MOD']['gai_invoices']['mod_parking']                   = 'Bcs\Module\ModParking';
$GLOBALS['FE_MOD']['gai_invoices']['mod_manager']                   = 'Bcs\Module\ModManager';
$GLOBALS['FE_MOD']['gai_invoices']['mod_editing_services']          = 'Bcs\Module\ModEditingServices';

$GLOBALS['FE_MOD']['gai_invoices']['mod_test_late_cancel_first']    = 'Bcs\Module\ModTestLateCancelFirst';
$GLOBALS['FE_MOD']['gai_invoices']['mod_test_late_cancel_additional']    = 'Bcs\Module\ModTestLateCancelAdditional';

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
	'tables' => array('tl_transactions')
);
$GLOBALS['BE_MOD']['content']['psychologists'] = array(
	'tables' => array('tl_psychologists')
);
$GLOBALS['BE_MOD']['content']['services'] = array(
	'tables' => array('tl_services')
);

/* Models */
$GLOBALS['TL_MODELS']['tl_transactions']    = 'Bcs\Model\Transactions';
$GLOBALS['TL_MODELS']['tl_psychologists']   = 'Bcs\Model\Psychologists';
$GLOBALS['TL_MODELS']['tl_services']        = 'Bcs\Model\Services';
$GLOBALS['TL_MODELS']['tl_cron_tracker']    = 'Bcs\Model\CronTracker';

/* Hooks */
$GLOBALS['TL_HOOKS']['initializeSystem'][] 	= array('Bcs\Frontend\SendInvoiceEmails', 'sendEmails');

/* Cron Jobs */
$GLOBALS['TL_CRON']['daily'][] = ['Bcs\Backend\CronJobs', 'sendReminderEmails'];
$GLOBALS['TL_CRON']['minutely'][] = ['Bcs\Backend\CronJobs', 'importPsychologists'];
$GLOBALS['TL_CRON']['minutely'][] = ['Bcs\Backend\CronJobs', 'importServices'];


/* When in the Backend, add our custom style sheet */
if (TL_MODE == 'BE')
{
    $GLOBALS['TL_CSS'][]					= 'system/modules/gai_invoices/assets/css/gai_backend.css';
}
