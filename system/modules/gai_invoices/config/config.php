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
$GLOBALS['FE_MOD']['gai_invoices']['mod_create_invoice'] 	= 'Bcs\Module\ModCreateInvoices';
// this is the front end module that displays recent invoices
$GLOBALS['FE_MOD']['gai_invoices']['mod_recent_invoices'] 	= 'Bcs\Module\ModRecentInvoices';

/* Hooks */
$GLOBALS['TL_HOOKS']['processFormData'][]           = array('Bcs\Handler', 'onProcessForm');
$GLOBALS['TL_HOOKS']['compileFormFields'][]         = array('Bcs\Handler', 'onCompileFormFields');
