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
     'recipients'           => array('admin_email', 'recipient_email'),
     'email_subject'        => array('admin_email', 'news_archive_*', 'news_*', 'news_text', 'news_url', 'recipient_email'),
     'email_text'           => array('admin_email', 'news_archive_*', 'news_*', 'news_text', 'news_url', 'recipient_email'),
     'email_html'           => array('admin_email', 'news_archive_*', 'news_*', 'news_text', 'news_url', 'recipient_email'),
     'file_name'            => array('admin_email', 'news_archive_*', 'news_*', 'news_text', 'news_url', 'recipient_email'),
     'file_content'         => array('admin_email', 'news_archive_*', 'news_*', 'news_text', 'news_url', 'recipient_email'),
     'email_recipient_cc'   => array('admin_email', 'news_archive_*', 'news_*', 'news_text', 'news_url', 'recipient_email'),
     'email_recipient_bcc'  => array('admin_email', 'news_archive_*', 'news_*', 'news_text', 'news_url', 'recipient_email'),
     'email_replyTo'        => array('admin_email', 'news_archive_*', 'news_*', 'news_text', 'news_url', 'recipient_email'),
     'attachment_tokens'    => array(),
);
