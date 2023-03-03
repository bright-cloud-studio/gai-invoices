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
$GLOBALS['FE_MOD']['gai_invoices']['mod_create_invoice']            = 'Bcs\Module\ModCreateInvoice';
$GLOBALS['FE_MOD']['gai_invoices']['mod_add_meetings']              = 'Bcs\Module\ModAddMeetings';
$GLOBALS['FE_MOD']['gai_invoices']['mod_misc_billing']              = 'Bcs\Module\ModMiscBilling';
$GLOBALS['FE_MOD']['gai_invoices']['mod_work_assignment_history'] 	= 'Bcs\Module\ModWorkAssignmentHistory';
$GLOBALS['FE_MOD']['gai_invoices']['mod_transaction_review'] 	    = 'Bcs\Module\ModTransactionReview';
$GLOBALS['FE_MOD']['gai_invoices']['mod_admin_review'] 	            = 'Bcs\Module\ModAdminReview';
$GLOBALS['FE_MOD']['gai_invoices']['mod_send_invoice_emails'] 	    = 'Bcs\Module\ModAdminReview';

/* Hooks */
$GLOBALS['TL_HOOKS']['processFormData'][]           = array('Bcs\Handler', 'onProcessForm');
$GLOBALS['TL_HOOKS']['compileFormFields'][]         = array('Bcs\Handler', 'onCompileFormFields');
