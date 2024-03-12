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
 
class ModWorkAssignments extends \Contao\Module
{
 
    /**
     * Template3
     * @var string
     */
    protected $strTemplate = 'mod_work_assignments';
  
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
        ModWorkAssignments::$spreadsheetId = '1erZUWlCgpWd67E1PIwwKNCYT0yCm2QiV2DL28VA8oVU';
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
 
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['work_assignments'][0]) . ' ###';
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
        
        // Manually set the server's time zone
        date_default_timezone_set('America/Los_Angeles');
        
        // Import the Database stuffs so we can make queries
        $this->import('Database');
        
        // Include our JS with a unique code to prefent caching
        $rand_ver = rand(1,9999);
        $GLOBALS['TL_BODY']['work_assignments'] = '<script src="system/modules/gai_invoices/assets/js/gai_invoice.js?v='.$rand_ver.'"></script>';
        
        // get the user and build their name
        $objUser = \FrontendUser::getInstance();
        $user = $objUser->firstname . " " . $objUser->lastname;
        
        // Get this user's unprocessed listings from Sheets
        $spreadsheet = $this->$service->spreadsheets->get(ModWorkAssignments::$spreadsheetId);
        
        // get all of our unarchived Transactions
        // DEV
        //$range = 'Work Assignment';
        
        // LIVE
        $range = 'SY2022-2023';
        
        $response = $this->$service->spreadsheets_values->get(ModWorkAssignments::$spreadsheetId, $range);
        $values = $response->getValues();
        
        // an array to store this users entries
        $entryList = array();
        
        // SORTING
        // 1st - NEW
        // 2nd - Non-new
        // 3rd - NEW Shared
        // 4th - Non-new Shared
        $listNew = array();
        $listNonNew = array();
        $listNewShared = array();
        $listNonNewShared = array();
        
        $filterDistricts = array();
        $filterSchools = array();
        $filterStudents = array();
        
        $entryForm = array();
        
        // store our random IDs so we can generate a new one if it exists already
        $randomIDs = array();
        
        $show = 0;
        $entry_id = 1;
        $index = 1;
        
        $is_primary_psy = true;
        
        foreach($values as $entry) {

            // if the id matches this entry, it is related to our user
            if($entry_id != 1) {
                
                // If user matches Psychologist (3) and Processed (26) isnt completed (2)
                if($user == trim($entry[3]) && $entry[26] != 2) { $show = 1; }
                
                // Shared
                if($user == $entry[27] && $entry[28] != 2) { $show = 1; }
                if($user == $entry[29] && $entry[30] != 2) { $show = 1; }
                if($user == $entry[31] && $entry[32] != 2) { $show = 1; }
                if($user == $entry[33] && $entry[34] != 2) { $show = 1; }
                if($user == $entry[35] && $entry[36] != 2) { $show = 1; }
                
                // Initial Pull
                if($entry[25] == 1) {
                
                    if($show == 1) {
    
                        $arrData = array();
                        $arrData['id']                  = $index;
                        $arrData['sheet_row']           = $entry_id;
                        
                        $arrData['date']                = $entry[0];
                        $arrData['30_days']             = $entry[1];
                        $arrData['45_days']             = $entry[2];
                        $arrData['psychologist']        = trim($entry[3]);
                        $arrData['district']            = trim($entry[4]);
                        $arrData['school']              = trim($entry[5]);
                        $arrData['student_name']        = trim($entry[6]);
                        $arrData['date_of_birth']       = $entry[7];
                        $arrData['gender']              = $entry[8];
                        $arrData['grade']               = $entry[9];
                        $arrData['lasid']               = trim($entry[10]);
                        $arrData['sasid']               = trim($entry[11]);
                        $arrData['initial']             = trim($entry[12]);
                        $arrData['type_of_testing']     = trim($entry[13]);
                        $arrData['testing_date']        = $entry[14];
                        $arrData['meeting_required']    = $entry[15];
                        $arrData['meeting_date']        = $entry[16];
                        $arrData['parent_info']         = $entry[17];
                        $arrData['teacher_info']        = $entry[18];
                        $arrData['team_chair']          = $entry[19];
                        $arrData['email']               = $entry[20];
                        $arrData['report_submitted']    = trim($entry[21]);
                        $arrData['invoiced_to_gai']     = $entry[22];
                        $arrData['district_invoice']    = $entry[23];
                        $arrData['notes']               = $entry[24];
                        
                        $arrData['initial_pull']        = $entry[25];
                        $arrData['processed']           = $entry[26];
                        
                        // Sharing
                        $arrData['shared_1']            = $entry[27];
                        $arrData['processed_1']         = $entry[28];
                        $arrData['shared_2']            = $entry[29];
                        $arrData['processed_2']         = $entry[30];
                        $arrData['shared_3']            = $entry[31];
                        $arrData['processed_3']         = $entry[32];
                        $arrData['shared_4']            = $entry[33];
                        $arrData['processed_4']         = $entry[34];
                        $arrData['shared_5']            = $entry[35];
                        $arrData['processed_5']         = $entry[36];
                        
                        $shared_total = 0;
                        if($arrData['shared_1'] != '') { $shared_total++; }
                        if($arrData['shared_2'] != '') { $shared_total++; }
                        if($arrData['shared_3'] != '') { $shared_total++; }
                        if($arrData['shared_4'] != '') { $shared_total++; }
                        if($arrData['shared_5'] != '') { $shared_total++; }
                        $arrData['shared_total'] = $shared_total;
                        
                        
                        // Price Tier
                        $result = $this->Database->prepare("SELECT * FROM tl_psychologists WHERE name=?")->execute($user);
                        while($result->next()) {
                            
                            $dirty_string = strtolower($result->price_tier);
                            $dirty_string = str_replace(' ', '_', $dirty_string);
                            
                            $arrData['price_tier'] = $dirty_string;
                            
                        }
                        
                        
                        // Filters
                        $filterDistricts[$arrData['district']] = $arrData['district'];
                        $filterSchools[$arrData['school']] = $arrData['school'];
                        $filterStudents[$arrData['student_name']] = $arrData['student_name'];
                        
                        // First, generate a random 9 digit number (000000000-999999999)
                		$generatedID = $this->randomNumber(9,$randomIDs);
                		// if this ID has been generated previously and is stored in our array
                		while(in_array($generatedID, $randomIDs)) {
                		    // generate a number number again
                		    $generatedID = $this->randomNumber(9,$randomIDs);
                		}
                		// We got out of the loop, we have a unique ID so store it in the array
                		array_push($randomIDs, $generatedID);
                		// Add this truly unique ID to our template
                		$arrData['random_id'] = $generatedID;
                        
                        
                        // Generate as "List"
                        $strListTemplate = ($this->entry_customItemTpl != '' ? $this->entry_customItemTpl : 'work_assignment_list');
                        $objListTemplate = new \FrontendTemplate($strListTemplate);
                        $objListTemplate->setData($arrData);
                        
                        $entryList[$entry_id] = $objListTemplate->parse();

                        // SORTING
                        // if this is shared
                        if($shared_total > 0) {
                            
                            if($arrData['psychologist'] == $user) {
                                if($arrData['processed'] == '') {
                                    $listNewShared[$entry_id] = $objListTemplate->parse();
                                } else {
                                    $listNonNewShared[$entry_id] = $objListTemplate->parse();
                                }
                            } else {
                                
                                // shared user 1
                                if($arrData['shared_1'] == $user) {
                                    if($arrData['processed_1'] == 1) {
                                        $listNonNewShared[$entry_id] = $objListTemplate->parse();
                                    } else {
                                        $listNewShared[$entry_id] = $objListTemplate->parse();
                                    }
                                }
                                
                                // shared user 1
                                if($arrData['shared_2'] == $user) {
                                    if($arrData['processed_2'] == 1) {
                                        $listNonNewShared[$entry_id] = $objListTemplate->parse();
                                    } else {
                                        $listNewShared[$entry_id] = $objListTemplate->parse();
                                    }
                                    $is_primary_psy = false;
                                }
                                
                                // shared user 1
                                if($arrData['shared_3'] == $user) {
                                    if($arrData['processed_3'] == 1) {
                                        $listNonNewShared[$entry_id] = $objListTemplate->parse();
                                    } else {
                                        $listNewShared[$entry_id] = $objListTemplate->parse();
                                    }
                                }
                                
                                // shared user 1
                                if($arrData['shared_4'] == $user) {
                                    if($arrData['processed_4'] == 1) {
                                        $listNonNewShared[$entry_id] = $objListTemplate->parse();
                                    } else {
                                        $listNewShared[$entry_id] = $objListTemplate->parse();
                                    }
                                }
                                
                                // shared user 1
                                if($arrData['shared_5'] == $user) {
                                    if($arrData['processed_5'] == 1) {
                                        $listNonNewShared[$entry_id] = $objListTemplate->parse();
                                    } else {
                                        $listNewShared[$entry_id] = $objListTemplate->parse();
                                    }
                                }
                                
                            }


                        } else {
                            
                            // unprocessed and not shared
                            if($arrData['processed'] == '') {
                                $listNew[$entry_id] = $objListTemplate->parse();
                            } else {
                                $listNonNew[$entry_id] = $objListTemplate->parse();
                            }
                            
                        }
                        
                        if($arrData['psychologist'] == $user)
                            $arrData['is_primary_psy'] = 'true';
                        else
                            $arrData['is_primary_psy'] = 'false';
                
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
        
        
        // SORTING
        $this->Template->workAssignmentListNew = $listNew;
        $this->Template->workAssignmentListNonNew = $listNonNew;
        $this->Template->workAssignmentListNewShared = $listNewShared;
        $this->Template->workAssignmentListNonNewShared = $listNonNewShared;
        
        // Filters
        ksort($filterDistricts);
        ksort($filterSchools);
        ksort($filterStudents);
        $this->Template->workAssignmentFilterDistricts = $filterDistricts;
        $this->Template->workAssignmentFilterSchools = $filterSchools;
        $this->Template->workAssignmentFilterStudents = $filterStudents;

	}
	
	/* Generates a random number of the specified $digits length */
    public function randomNumber($digits, $randomIDs) {
        
        /* Store our numbers in an array */
        $nums = array();
        
        /* loop $digits times */
        for($x = 0; $x < $digits; $x++) {
            /* Add to our array a random number between 0-9 */
            array_push($nums, mt_rand(0,9));
        }

        /* return the array, as an int, padded out to 9 digits with zeroes if needed */
        return str_pad((int)implode("",$nums), 9, '0', STR_PAD_LEFT);
    }

} 
