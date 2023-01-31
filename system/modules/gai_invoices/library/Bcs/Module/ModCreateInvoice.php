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
        //$objUser = \FrontendUser::getInstance();
        
        $show = 0;
        $entry_id = 1;
        $index = 1;
        foreach($values as $entry) {
            
            // if the id matches this entry, it is related to our user
            if($entry_id != 1) {
                
                // if the psychologist name matches
                if($user == $entry[3] && $entry[24] != 2) { $show = 1; }
                
                if($user == $entry[25] && $entry[26] != 2) { $show = 1; }
                if($user == $entry[27] && $entry[28] != 2) { $show = 1; }
                if($user == $entry[29] && $entry[30] != 2) { $show = 1; }
                if($user == $entry[31] && $entry[32] != 2) { $show = 1; }
                if($user == $entry[33] && $entry[34] != 2) { $show = 1; }
                
                
                if($show == 1) {

                    // if Report Submiited is yes or Yes
                    if($entry[20] == 'yes' || $entry[20] == "Yes") {
                        
                        $arrData = array();
                        $arrData['id']                  = $index;
                        $arrData['sheet_row']           = $entry_id;
                        $arrData['date']                = $entry[0];
                        $arrData['30_days']             = $entry[1];
                        $arrData['45_days']             = $entry[2];
                        $arrData['psychologist']        = $entry[3];
                        $arrData['district']            = $entry[4];
                        $arrData['school']              = $entry[5];
                        $arrData['student_name']        = $entry[6];
                        $arrData['date_of_birth']       = $entry[7];
                        $arrData['grade']               = $entry[8];
                        $arrData['lasid']               = $entry[9];
                        $arrData['sasid']               = $entry[10];
                        $arrData['initial']             = $entry[11];
                        $arrData['type_of_testing']     = $entry[12];
                        $arrData['testing_date']        = $entry[13];
                        $arrData['meeting_required']    = $entry[14];
                        $arrData['meeting_date']        = $entry[15];
                        $arrData['parent_info']         = $entry[16];
                        $arrData['teacher_info']        = $entry[17];
                        $arrData['team_chair']          = $entry[18];
                        $arrData['email']               = $entry[19];
                        $arrData['report_submitted']    = $entry[20];
                        $arrData['invoiced_to_gai']     = $entry[21];
                        $arrData['district_invoice']    = $entry[22];
                        $arrData['notes']               = $entry[23];
                        $arrData['processed']           = $entry[24];
                        
                        // Sharing
                        $arrData['shared_1']            = $entry[25];
                        $arrData['processed_1']         = $entry[26];
                        $arrData['shared_2']            = $entry[27];
                        $arrData['processed_2']         = $entry[28];
                        $arrData['shared_3']            = $entry[29];
                        $arrData['processed_3']         = $entry[30];
                        $arrData['shared_4']            = $entry[31];
                        $arrData['processed_4']         = $entry[32];
                        $arrData['shared_5']            = $entry[33];
                        $arrData['processed_5']         = $entry[34];
                        
                        $shared_total = 0;
                        if($arrData['shared_1'] != '') { $shared_total++; }
                        if($arrData['shared_2'] != '') { $shared_total++; }
                        if($arrData['shared_3'] != '') { $shared_total++; }
                        if($arrData['shared_4'] != '') { $shared_total++; }
                        if($arrData['shared_5'] != '') { $shared_total++; }
                        $arrData['shared_total'] = $shared_total;
                        
                        
                        // Generate as "List"
                        $strListTemplate = ($this->entry_customItemTpl != '' ? $this->entry_customItemTpl : 'work_assignment_list');
                        $objListTemplate = new \FrontendTemplate($strListTemplate);
                        $objListTemplate->setData($arrData);
                        $entryList[$entry_id] = $objListTemplate->parse();
                        
                        $index++;
                        
                        // Generate as "Form"
                        $strFormTemplate = ($this->entry_customItemTpl != '' ? $this->entry_customItemTpl : 'work_assignment_form');
                        $objFormTemplate = new \FrontendTemplate($strFormTemplate);
                        $objFormTemplate->setData($arrData);
                        $entryForm[$entry_id] = $objFormTemplate->parse();
                    }
                }

            }
            
            $entry_id++;
            $show = 0;
        }
        
        // set this users entries to the template
        $this->Template->workAssignmentList = $entryList;
        $this->Template->workAssignmentForm = $entryForm;

	}
} 
