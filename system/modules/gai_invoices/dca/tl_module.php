<?php

/**
 * Bright Cloud Studio's Modal Gallery
 *
 * Copyright (C) 2021 Bright Cloud Studio
 *
 * @package    bright-cloud-studio/modal-gallery
 * @link       https://www.brightcloudstudio.com/
 * @license    http://opensource.org/licenses/lgpl-3.0.html
**/

/* Add a palette to tl_module */

// Create Invoice
$GLOBALS['TL_DCA']['tl_module']['palettes']['mod_create_invoice'] 		    = '{title_legend},name,headline,type;{template_legend:hide},customTpl;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['mod_add_meetings'] 		    = '{title_legend},name,headline,type;{template_legend:hide},customTpl;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['mod_misc_billing'] 		    = '{title_legend},name,headline,type;{template_legend:hide},customTpl;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['mod_work_assignment_history'] 	= '{title_legend},name,headline,type;{template_legend:hide},customTpl;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['mod_transaction_review'] 		= '{title_legend},name,headline,type;{template_legend:hide},customTpl;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['mod_admin_review'] 		    = '{title_legend},name,headline,type;{template_legend:hide},customTpl;{expert_legend:hide},guests,cssID,space';
