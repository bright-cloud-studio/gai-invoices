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
    // this is the hooks class
    'Bcs\Handler'                               => 'system/modules/gai_invoices/library/Bcs/Handler.php',
    'Bcs\Module\ModCreateInvoice'               => 'system/modules/gai_invoices/library/Bcs/Module/ModCreateInvoice.php',
    'Bcs\Module\ModWorkAssignmentHistory'       => 'system/modules/gai_invoices/library/Bcs/Module/ModWorkAssignmentHistory.php',
    'Bcs\Module\ModTransactionReview'           => 'system/modules/gai_invoices/library/Bcs/Module/ModTransactionReview.php',
));

/* Register the templates */
TemplateLoader::addFiles(array
(
    'mod_create_invoice'            => 'system/modules/gai_invoices/templates/modules',
    'work_assignment_list'          => 'system/modules/gai_invoices/templates/items',
    'work_assignment_form'          => 'system/modules/gai_invoices/templates/items',
    
    // this is the template for the recent invoices module
	'mod_work_assignment_history'   => 'system/modules/gai_invoices/templates/modules',
    // this is the template for the selected recent invoice inside the recent invoices module
	'work_assignment_history'       => 'system/modules/gai_invoices/templates/items',
    
	'mod_transaction_review'        => 'system/modules/gai_invoices/templates/modules',
	'transaction_review_list'       => 'system/modules/gai_invoices/templates/items',
));
