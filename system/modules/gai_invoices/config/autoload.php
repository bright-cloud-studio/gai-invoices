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
    // Create Invoice
    'Bcs\Module\ModCreateInvoice'              => 'system/modules/gai_invoices/library/Bcs/Module/ModCreateInvoice.php',
    // this is the front end module for displaying recent invoices for editing
    'Bcs\Module\ModRecentInvoices'              => 'system/modules/gai_invoices/library/Bcs/Module/ModRecentInvoices.php',
));

/* Register the templates */
TemplateLoader::addFiles(array
(
    // Create Invoices
    'mod_create_invoice'            => 'system/modules/gai_invoices/templates/modules',
    'item_work_assignment'          => 'system/modules/gai_invoices/templates/items',
    
    
    // this is the template for the recent invoices module
	'mod_recent_invoices'                   => 'system/modules/gai_invoices/templates/modules',
    // this is the template for the selected recent invoice inside the recent invoices module
	'item_recent_selected_invoice'          => 'system/modules/gai_invoices/templates/items',
));
