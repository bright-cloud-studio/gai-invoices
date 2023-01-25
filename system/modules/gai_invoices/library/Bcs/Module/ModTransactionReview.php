<?php

/**
 * Bright Cloud Studio's GAI Invoices
 *
 * Copyright (C) 2022-2023 Bright Cloud Studio
 *
 * @package    bright-cloud-studio/gai-invoices
 * @link       https://www.brightcloudstudio.com/
 * @license    http://opensource.org/licenses/lgpl-3.0.html
**/

  
namespace Bcs\Module;

use Google;
 
class ModTransactionReview extends \Contao\Module
{
 
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_transaction_review';
  
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
        ModCreateInvoice::$spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
		
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
 
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['transaction_review'][0]) . ' ###';
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
     
        // get the user and build their name
        $objUser = \FrontendUser::getInstance();
        $user = $objUser->firstname . " " . $objUser->lastname;
        
        // Get this user's unprocessed listings from Sheets
        $spreadsheet = $this->$service->spreadsheets->get(ModCreateInvoice::$spreadsheetId);
        
        // get all of our unarchived Transactions
        $range = 'Invoices - Psy';
        $response = $this->$service->spreadsheets_values->get(ModCreateInvoice::$spreadsheetId, $range);
        $values = $response->getValues();
        
        // an array to store this users entries
        $entryHistory = array();
        $objUser = \FrontendUser::getInstance();
        
        $entry_id = 0;
        foreach($values as $entry) {
            
            // if the id matches this entry, it is related to our user
            if($entry_id != 0) {
                
                if($user == $entry[3]) {
                    $arrData = array();
                    $arrData['billing_year']    = $entry[0];
                    $arrData['billing_month']   = $entry[1];
                    $arrData['invoice_number']  = $entry[2];
                    $arrData['psychologist']    = $entry[3];
                    $arrData['date_issued']     = $entry[4];
                    $arrData['date_due']        = $entry[5];
                    $arrData['invoice_link']    = $entry[6];
                    
                    // Generate as "List"
                    $strListTemplate = ($this->entry_customItemTpl != '' ? $this->entry_customItemTpl : 'transaction_review_list');
                    $objListTemplate = new \FrontendTemplate($strListTemplate);
                    $objListTemplate->setData($arrData);
                    $entryHistory[$entry_id] = $objListTemplate->parse();
                }

            }
            
            $entry_id++;
        }
        
        // set this users entries to the template
        $this->Template->transactionReview = $entryHistory;
        
	}
} 
