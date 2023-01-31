<??

/**
 * Bright Cloud Studio's Keap Integration
 *
 * @copyright  2022 Bright Cloud Studio
 * @package    keap_integration
 * @license    GNU/LGPL
 * @filesource
 */

/* Add fields to tl_user */
$GLOBALS['TL_DCA']['tl_member']['fields']['state'] = array
(
  'sql'                     => "varchar(255) NOT NULL default ''",
  'label'                   => &$GLOBALS['TL_LANG']['tl_child_category']['linked_parent'],
  'inputType'               => 'checkbox',
  'options_callback'		  => array('Bcs\Backend\ChildCategoryBackend', 'getParentCategories'),										
  'eval'                    => array('multiple'=>true, 'mandatory'=>true,'tl_class'=>'clr') 
);
