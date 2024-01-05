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

/* Register the classes */
ClassLoader::addClasses(array
(
    // Our Frontend Modules
    'Bcs\Module\ModWorkAssignments'             => 'system/modules/gai_invoices/library/Bcs/Module/ModWorkAssignments.php',
    'Bcs\Module\ModAddMeetings'                 => 'system/modules/gai_invoices/library/Bcs/Module/ModAddMeetings.php',

    // Manually approved services
    'Bcs\Module\ModMiscBilling'                 => 'system/modules/gai_invoices/library/Bcs/Module/ModMiscBilling.php',
    'Bcs\Module\ModMiscTravelExpenses'          => 'system/modules/gai_invoices/library/Bcs/Module/ModMiscTravelExpenses.php',
    'Bcs\Module\Parking'                        => 'system/modules/gai_invoices/library/Bcs/Module/ModParking.php',
    'Bcs\Module\Manager'                        => 'system/modules/gai_invoices/library/Bcs/Module/ModManager.php',
    'Bcs\Module\EditingServices'                => 'system/modules/gai_invoices/library/Bcs/Module/ModEditingServices.php',
    'Bcs\Module\TestLateCancelFirst'            => 'system/modules/gai_invoices/library/Bcs/Module/ModTestLateCancelFirst.php',
    'Bcs\Module\TestLateCancelAdditional'       => 'system/modules/gai_invoices/library/Bcs/Module/ModTestLateCancelAdditional.php',
    
    
    
    'Bcs\Module\ModInvoiceHistory'              => 'system/modules/gai_invoices/library/Bcs/Module/ModInvoiceHistory.php',
    'Bcs\Module\ModTransactionReview'           => 'system/modules/gai_invoices/library/Bcs/Module/ModTransactionReview.php',
    'Bcs\Module\ModAdminReview'                 => 'system/modules/gai_invoices/library/Bcs/Module/ModAdminReview.php',
    'Bcs\Module\ModSendInvoiceEmails'           => 'system/modules/gai_invoices/library/Bcs/Module/ModSendInvoiceEmails.php',
    'Bcs\Frontend\SendInvoiceEmails'            => 'system/modules/gai_invoices/library/Bcs/Frontend/SendInvoiceEmails.php',

    
    // Our Cron Jobs
    'Bcs\Backend\CronJobs'                      => 'system/modules/gai_invoices/library/Bcs/Backend/CronJobs.php',
    // Our Transactions
    'Bcs\Backend\TransactionsBackend'           => 'system/modules/gai_invoices/library/Bcs/Backend/TransactionsBackend.php',
    'Bcs\Model\Transactions'                    => 'system/modules/gai_invoices/library/Bcs/Model/Transactions.php',
    // Our Psychologists
    'Bcs\Model\Psychologists'                   => 'system/modules/gai_invoices/library/Bcs/Model/Psychologists.php',
    // Our Services
    'Bcs\Model\Services'                    => 'system/modules/gai_invoices/library/Bcs/Model/Services.php'
    
));

/* Register the templates */
TemplateLoader::addFiles(array
(
    'mod_work_assignments'          => 'system/modules/gai_invoices/templates/modules',
    'work_assignment_list'          => 'system/modules/gai_invoices/templates/items',
    'work_assignment_form'          => 'system/modules/gai_invoices/templates/items',
    
    'mod_add_meetings'              => 'system/modules/gai_invoices/templates/modules',
    
    // Manually approved services
    'mod_misc_billing'              => 'system/modules/gai_invoices/templates/modules',
    'mod_misc_travel_expenses'      => 'system/modules/gai_invoices/templates/modules',
    'mod_parking'                   => 'system/modules/gai_invoices/templates/modules',
    'mod_manager'                   => 'system/modules/gai_invoices/templates/modules',
    'mod_editing_services'          => 'system/modules/gai_invoices/templates/modules',
    'mod_test_late_cancel_first'    => 'system/modules/gai_invoices/templates/modules',
    'mod_test_late_cancel_additional' => 'system/modules/gai_invoices/templates/modules',

    
	'mod_invoice_history'           => 'system/modules/gai_invoices/templates/modules',
	'invoice_history_list'          => 'system/modules/gai_invoices/templates/items',
    'invoice_preview_list'          => 'system/modules/gai_invoices/templates/items',
    
	'mod_transaction_review'        => 'system/modules/gai_invoices/templates/modules',
	'transaction_review_list'       => 'system/modules/gai_invoices/templates/items',
    
    'mod_admin_review'               => 'system/modules/gai_invoices/templates/modules',
	'admin_review_list'              => 'system/modules/gai_invoices/templates/items',
    
    'mod_send_invoice_emails'        => 'system/modules/gai_invoices/templates/modules',
    'send_invoice_emails_list'       => 'system/modules/gai_invoices/templates/items',
));
