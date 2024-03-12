<?php

/**
* Bright Cloud Studio's GAI Invoices
*
* Copyright (C) 2023-2024 Bright Cloud Studio
*
* @package    bright-cloud-studio/modal-gallery
* @link       https://www.brightcloudstudio.com/
* @license    http://opensource.org/licenses/lgpl-3.0.html
**/

  
namespace Bcs\Module;

use Google;


 
class ModAddMeetings extends \Contao\Module
{
 
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_add_meetings';
  
    // our google api stuffs
    protected $client;
    protected $service;
    public static $spreadsheetId;
 
	protected $arrStates = array();
 
	/**
	 * Initialize the object
	 *
	 * @param \ModuleModel $objModule
	 * @param string       $strColumn
	 */
	public function __construct($objModule, $strColumn='main')
	{
        parent::__construct($objModule, $strColumn);

        // Create a client connection to Google
        $this->$client = new Google\Client();
        // Load our auth key
        $this->$client->setAuthConfig('key.json');
        // Set our scope to use the Sheets service
        $this->$client->addScope(Google\Service\Sheets::SPREADSHEETS);
        // Assign our client to a service
        $this->$service = new \Google_Service_Sheets($this->$client);
        // Set the ID for our specific spreadsheet
        ModAddMeetings::$spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
		
	}
	
    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
 
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['add_meetings'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&table=tl_module&act=edit&id=' . $this->id;
 
            return $objTemplate->parse();
        }
 
        return parent::generate();
    }
 
    /* Generate the module */
    protected function compile()
    {
        // Import the Database stuffs so we can make queries
        $this->import('Database');
        
        // Include our JS with a unique code to prefent caching
        $rand_ver = rand(1,9999);
        $GLOBALS['TL_BODY']['add_meeting'] = '<script src="system/modules/gai_invoices/assets/js/gai_invoice.js?v='.$rand_ver.'"></script>';
        
        // get the user and build their name
        $objUser = \FrontendUser::getInstance();
        $user = $objUser->firstname . " " . $objUser->lastname;
        
        $result = $this->Database->prepare("SELECT * FROM tl_psychologists WHERE name=?")->execute($user);
        while($result->next()) {
            
            $clean_string = strtolower($result->price_tier);
            $clean_string = str_replace(' ', '_', $clean_string);

            $this->Template->price_tier = $clean_string;
            
        }
        
        // Load all of services and their prices and add it to the module
        $arrData = array();
        $result = $this->Database->prepare("SELECT * FROM tl_services")->execute();
        while($result->next()) {
            
            $arrData[$result->service_code]['service_code'] = $result->service_code;
            $arrData[$result->service_code]['name'] = $result->name;
            $arrData[$result->service_code]['psychologist_tier_1'] = $result->psychologist_tier_1;
            $arrData[$result->service_code]['psychologist_tier_2'] = $result->psychologist_tier_2;
            $arrData[$result->service_code]['psychologist_tier_3'] = $result->psychologist_tier_3;
            $arrData[$result->service_code]['psychologist_tier_4'] = $result->psychologist_tier_4;
            $arrData[$result->service_code]['psychologist_tier_5'] = $result->psychologist_tier_5;
            $arrData[$result->service_code]['psychologist_tier_6'] = $result->psychologist_tier_6;
            $arrData[$result->service_code]['psychologist_tier_7'] = $result->psychologist_tier_7;
            $arrData[$result->service_code]['psychologist_tier_8'] = $result->psychologist_tier_8;
            $arrData[$result->service_code]['psychologist_tier_9'] = $result->psychologist_tier_9;
            $arrData[$result->service_code]['psychologist_tier_10'] = $result->psychologist_tier_10;
            $arrData[$result->service_code]['school_tier_1'] = $result->school_tier_1;
            $arrData[$result->service_code]['school_tier_2'] = $result->school_tier_2;
            $arrData[$result->service_code]['school_tier_3'] = $result->school_tier_3;
            
        }
        $this->Template->services = $arrData;
        
	}
} 
