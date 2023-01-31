<?php

/**
* Bright Cloud Studio's Keap Integration
*
* @copyright  2022 Bright Cloud Studio
* @package    keap_integration
* @license    GNU/LGPL
* @filesource
*/

 /* Extend the tl_user palettes */
foreach ($GLOBALS['TL_DCA']['tl_member']['palettes'] as $k => $v) {
    $GLOBALS['TL_DCA']['tl_member']['palettes'][$k] = str_replace('groups;', 'groups;{meetings_legend},meeting_option;', $v);
}
    
/* Add fields to tl_user */
$GLOBALS['TL_DCA']['tl_member']['fields']['meeting_options'] = array
(
    'sql'                     => "varchar(255) NOT NULL default ''",
    'label'                   => &$GLOBALS['TL_LANG']['tl_member']['meeting_options'],
    'inputType'               => 'checkbox',
    'options'                 => array('misc_travel_expenses' => 'Misc. Travel Expenses', 'editing_services' => 'Editing Services', 'meeting_coverage_coordinator' => 'Meeting Coverage Coordinator'),								
    'eval'                    => array('multiple'=>true, 'mandatory'=>false,'tl_class'=>'clr') 
);
