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
    'Bcs\Handler'                               => 'system/modules/gai_invoices/library/Bcs/Handler.php',
    'Bcs\Module\ModRecentInvoices'              => 'system/modules/gai_invoices/library/Bcs/Module/ModRecentInvoices.php',
    'Bcs\Model\ItemRecentSelectedInvoice'       => 'system/modules/gai_invoices/library/Bcs/Model/ItemRecentSelectedInvoice.php'
));

/* Register the templates */
TemplateLoader::addFiles(array
(
	'mod_recent_invoices'                   => 'system/modules/gai_invoices/templates/modules',
	'item_recent_selected_invoice'          => 'system/modules/gai_invoices/templates/items',
));
