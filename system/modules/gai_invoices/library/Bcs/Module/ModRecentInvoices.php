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

  
namespace Bcs\Module;

use Google;


 
class ModRecentInvoices extends \Contao\Module
{
 
    /**
     * Template
     * @var string
     */
  protected $strTemplate = 'mod_recent_invoices';
  
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
        ModRecentInvoices::$spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
		
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
 
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['recent_invoices'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&table=tl_module&act=edit&id=' . $this->id;
 
            return $objTemplate->parse();
        }
 
        return parent::generate();
    }
 
 
    /**
     * Generate the module
     */
    protected function compile()
    {
        
        // Get this user's unprocessed listings from Sheets
        $spreadsheet = $this->$service->spreadsheets->get(ModRecentInvoices::$spreadsheetId);
        
        // get all of our unarchived Transactions
        $range = 'Transactions';
        $response = $this->$service->spreadsheets_values->get(ModRecentInvoices::$spreadsheetId, $range);
        $values = $response->getValues();
        
        // an array to store this users entries
        $entries = array();
        $objUser = \FrontendUser::getInstance();
        
        $entry_id = 0;
        foreach($values as $entry) {
            
            // if the id matches this entry, it is related to our user
            if($entry[9] == $objUser->id) {
                
                $arrData = array();
                $arrData['billing_month']       = $entry[0];
                $arrData['school_id']           = $entry[1];
                $arrData['student_id']          = $entry[2];
                $arrData['service_provided']    = $entry[3];
                $arrData['meeting_date']        = $entry[4];
                $arrData['meeting_start']       = $entry[5];
                $arrData['meeting_end']         = $entry[6];
                $arrData['meeting_duration']    = $entry[7];
                $arrData['notes']               = $entry[8];
                $arrData['client_id']           = $entry[9];
                
                $strItemTemplate = ($this->entry_customItemTpl != '' ? $this->entry_customItemTpl : 'item_recent_invoice');
                $objTemplate = new \FrontendTemplate($strItemTemplate);
                $objTemplate->setData($arrData);
                $entries[$entry_id] = $objTemplate->parse();

            }
            
            $entry_id++;
        }
        
        // set this users entries to the template
        $this->Template->entries = $entries;
        
        
	}

} 
