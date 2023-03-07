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
        // DEV
        // ModCreateInvoice::$spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
        // LIVE
        ModCreateInvoice::$spreadsheetId = '1erZUWlCgpWd67E1PIwwKNCYT0yCm2QiV2DL28VA8oVU';
		
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
        $GLOBALS['TL_BODY']['gai_invoice'] = '<script src="system/modules/gai_invoices/assets/js/gai_invoice.js?v='.$rand_ver.'"></script>';
        
        // get the user and build their name
        $objUser = \FrontendUser::getInstance();
        $user = $objUser->firstname . " " . $objUser->lastname;
        
        // Get this user's unprocessed listings from Sheets
        $spreadsheet = $this->$service->spreadsheets->get(ModCreateInvoice::$spreadsheetId);
        
        // get all of our unarchived Transactions
        // DEV
        //$range = 'Work Assignment';
        
        // LIVE
        $range = 'Fall';
        
        $response = $this->$service->spreadsheets_values->get(ModCreateInvoice::$spreadsheetId, $range);
        $values = $response->getValues();
        
        
        // to get NEW UNSHARED FIRST, do this
        
        // move shared new to the end
        //$values = $this->sharedNewToEnd($user, $values);
        // move shared non-new to the end
       // $values = $this->sharedToEnd($user, $values);
        // move non-new entries to the end
        //$values = $this->processedToEnd($user, $values);
        
        
        

        
        
        
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
        
        $entryForm = array();
        //$objUser = \FrontendUser::getInstance();
        
        $show = 0;
        $entry_id = 1;
        $index = 1;
        
        foreach($values as $entry) {
            
            // if the id matches this entry, it is related to our user
            if($entry_id != 1) {
                
                // If user matches Psychologist (3) and Processed (26) isnt completed (2)
                if($user == $entry[3] && $entry[26] != 2) { $show = 1; }
                
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
                        $arrData['psychologist']        = $entry[3];
                        $arrData['district']            = $entry[4];
                        $arrData['school']              = $entry[5];
                        $arrData['student_name']        = $entry[6];
                        $arrData['date_of_birth']       = $entry[7];
                        $arrData['gender']              = $entry[8];
                        $arrData['grade']               = $entry[9];
                        $arrData['lasid']               = $entry[10];
                        $arrData['sasid']               = $entry[11];
                        $arrData['initial']             = $entry[12];
                        $arrData['type_of_testing']     = $entry[13];
                        $arrData['testing_date']        = $entry[14];
                        $arrData['meeting_required']    = $entry[15];
                        $arrData['meeting_date']        = $entry[16];
                        $arrData['parent_info']         = $entry[17];
                        $arrData['teacher_info']        = $entry[18];
                        $arrData['team_chair']          = $entry[19];
                        $arrData['email']               = $entry[20];
                        $arrData['report_submitted']    = $entry[21];
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
        
        // SORTING
        $this->Template->workAssignmentListNew = $listNew;
        $this->Template->workAssignmentListNonNew = $listNonNew;
        $this->Template->workAssignmentListNewShared = $listNewShared;
        $this->Template->workAssignmentListNonNewShared = $listNonNewShared;

	}
	
    // Pushes certain Transaction entries to the END of an array
    function sharedNewToEnd($user, $array) {
        foreach ($array as $key => $val) {
            
            
            // if you are the primary psy
            if($val['3'] == $user) {
                // if this is unprocessed and has been shared
                if($val['26'] == "" && $val["27"] != "") {
                    $item = $array[$key];
                    unset($array[$key]);
                    array_push($array, $item);
                }
            } else {
                
                $sharedNew = false;
                
                // if shared 1 is you and unprocessed
                if($val['27'] == $user && $val['28'] == "")
                    $sharedNew = true;
                // if shared 2 is you and unprocessed
                if($val['29'] == $user && $val['30'] == "")
                    $sharedNew = true;
                // if shared 3 is you and unprocessed
                if($val['31'] == $user && $val['32'] == "")
                    $sharedNew = true;
                // if shared 4 is you and unprocessed
                if($val['33'] == $user && $val['34'] == "")
                    $sharedNew = true;
                // if shared 5 is you and unprocessed
                if($val['35'] == $user && $val['36'] == "")
                    $sharedNew = true;
                    
                if($sharedNew == true) {
                    $item = $array[$key];
                    unset($array[$key]);
                    array_push($array, $item);
                }
            }
        }
        return $array;
    }
    
    // Pushes certain Transaction entries to the END of an array
    function sharedToEnd($user, $array) {
        foreach ($array as $key => $val) {
            
            // if you are the primary psy
            if($val['3'] == $user) {
                // if this is unprocessed and has been shared
                if($val['26'] == "1" && $val["27"] != "") {
                    $item = $array[$key];
                    unset($array[$key]);
                    array_push($array, $item);
                }
            } else {
                
                $sharedProcessed = false;
                
                // if shared 1 is you and unprocessed
                if($val['27'] == $user && $val['28'] != "")
                    $sharedProcessed = true;
                // if shared 2 is you and unprocessed
                if($val['29'] == $user && $val['30'] != "")
                    $sharedProcessed = true;
                // if shared 3 is you and unprocessed
                if($val['31'] == $user && $val['32'] != "")
                    $sharedProcessed = true;
                // if shared 4 is you and unprocessed
                if($val['33'] == $user && $val['34'] != "")
                    $sharedProcessed = true;
                // if shared 5 is you and unprocessed
                if($val['35'] == $user && $val['36'] != "")
                    $sharedProcessed = true;
                    
                if($sharedProcessed == true) {
                    $item = $array[$key];
                    unset($array[$key]);
                    array_push($array, $item);
                }
            }
            
        }
        return $array;
    }
    
    // Pushes certain Transaction entries to the END of an array
    function processedToEnd($user, $array) {
        foreach ($array as $key => $val) {
            
            // if you are the primary psy
            if($val['3'] == $user) {
                // if this is processed and has not been shared
                if($val['26'] == "1" && $val["27"] == "") {
                    $item = $array[$key];
                    unset($array[$key]);
                    array_push($array, $item);
                }
            }
            
        }
        return $array;
    }

} 



