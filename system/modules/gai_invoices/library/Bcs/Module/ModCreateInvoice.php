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


 
class ModCreateInvoice extends \Contao\Module
{
 
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_create_invoice';
  
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
 
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['create_invoice'][0]) . ' ###';
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
        // Include our JS with a unique code to prefent caching
        $rand_ver = rand(1,9999);
        $GLOBALS['TL_BODY'][] = '<script src="system/modules/gai_invoices/assets/js/gai_invoice.js?v='.$rand_ver.'"></script>';
        
        // get the user and build their name
        $objUser = \FrontendUser::getInstance();
        $user = $objUser->firstname . " " . $objUser->lastname;
        
        // Get this user's unprocessed listings from Sheets
        $spreadsheet = $this->$service->spreadsheets->get(ModCreateInvoice::$spreadsheetId);
        
        // get all of our unarchived Transactions
        $range = 'Work Assignment';
        $response = $this->$service->spreadsheets_values->get(ModCreateInvoice::$spreadsheetId, $range);
        $values = $response->getValues();
        
        // an array to store this users entries
        $entryList = array();
        $entryForm = array();
        $objUser = \FrontendUser::getInstance();
        
        $entry_id = 1;
        foreach($values as $entry) {
            
            // if the id matches this entry, it is related to our user
            if($entry_id != 1) {
                
                if($user == $entry[3] && $entry[23] != 1) {
                    $arrData = array();
                    $arrData['sheet_row']           = $entry_id;
                    $arrData['date']                = $entry[0];
                    $arrData['30_days']             = $entry[1];
                    $arrData['45_days']             = $entry[2];
                    $arrData['psychologist']        = $entry[3];
                    $arrData['district']            = $entry[4];
                    $arrData['student_name']        = $entry[5];
                    $arrData['date_of_birth']       = $entry[6];
                    $arrData['grade']               = $entry[7];
                    $arrData['lasid']               = $entry[8];
                    $arrData['sasid']               = $entry[9];
                    $arrData['initial']             = $entry[10];
                    $arrData['type_of_testing']     = $entry[11];
                    $arrData['testing_date']        = $entry[12];
                    $arrData['meeting_required']    = $entry[13];
                    $arrData['meeting_date']        = $entry[14];
                    $arrData['parent_info']         = $entry[15];
                    $arrData['teacher_info']        = $entry[16];
                    $arrData['team_chair']          = $entry[17];
                    $arrData['email']               = $entry[18];
                    $arrData['report_submitted']    = $entry[19];
                    $arrData['invoiced_to_gai']     = $entry[20];
                    $arrData['district_invoice']    = $entry[21];
                    $arrData['notes']               = $entry[22];
                    
                    // Generate as "List"
                    $strListTemplate = ($this->entry_customItemTpl != '' ? $this->entry_customItemTpl : 'work_assignment_list');
                    $objListTemplate = new \FrontendTemplate($strListTemplate);
                    $objListTemplate->setData($arrData);
                    $entryList[$entry_id] = $objListTemplate->parse();
                    
                    // Generate as "Form"
                    $strFormTemplate = ($this->entry_customItemTpl != '' ? $this->entry_customItemTpl : 'work_assignment_form');
                    $objFormTemplate = new \FrontendTemplate($strFormTemplate);
                    $objFormTemplate->setData($arrData);
                    $entryForm[$entry_id] = $objFormTemplate->parse();
                }

            }
            
            $entry_id++;
        }
        
        // set this users entries to the template
        $this->Template->workAssignmentList = $entryList;
        $this->Template->workAssignmentForm = $entryForm;

	}
} 
