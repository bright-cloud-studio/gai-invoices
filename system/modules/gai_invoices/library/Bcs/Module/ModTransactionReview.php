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
        ModTransactionReview::$spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
		
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
        $rand_ver = rand(1,9999);
        $GLOBALS['TL_BODY']['transaction_review'] = '<script src="system/modules/gai_invoices/assets/js/gai_invoice.js?v='.$rand_ver.'"></script>';
     
        // get the user and build their name
        $objUser = \FrontendUser::getInstance();
        $user = $objUser->firstname . " " . $objUser->lastname;
        
        // Get this user's unprocessed listings from Sheets
        $spreadsheet = $this->$service->spreadsheets->get(ModTransactionReview::$spreadsheetId);
        
        // get all of our unarchived Transactions
        $range = 'Transactions';
        $response = $this->$service->spreadsheets_values->get(ModTransactionReview::$spreadsheetId, $range);
        $values = $response->getValues();
        
        // an array to store this users entries
        $entryHistory = array();
        $entryHistoryMisc = array();
        $entryHistoryForm = array();
        $trans_ids = array();
        $objUser = \FrontendUser::getInstance();
        
        // get the current month
        $month = date('F');
        //$month = 'February';
        
        
        
        
        // get all of our services and store them in an array
        $services = array();
        $range_serv = 'Services';
        $response_serv = $this->$service->spreadsheets_values->get(ModTransactionReview::$spreadsheetId, $range_serv);
        $values_serv = $response_serv->getValues();
        

        foreach($values_serv as $entry_serv) {
            $services[$entry_serv[0]] = $entry_serv[1];
        }
        
        
        
        $entry_id = 1;
        $transaction_id = 1;
        foreach($values as $entry) {
            
            // if the id matches this entry, it is related to our user
            if($entry_id != 1) {
                // if this isnt flagged as Deleted
                if($entry[16] != 1) {
                    // if the billing month on this transaction matches the current month
                    if($entry[0] == $month) {
                        if($user == $entry[2]) {
                            $arrData = array();
                            $arrData['row_id']              = $entry_id;
                            $arrData['transaction_id']      = $transaction_id;
                            $arrData['billing_month']       = $entry[0];
                            $arrData['date_submitted']      = $entry[1];
                            $arrData['psychologist']        = $entry[2];
                            $arrData['district']            = $entry[3];
                            $arrData['school']              = $entry[4];
                            $arrData['student_initials']    = $entry[5];
                            $arrData['service']             = $services[$entry[6]];
                            $arrData['price']               = $entry[7];
                            $arrData['lasid']               = $entry[8];
                            $arrData['sasid']               = $entry[9];
                            $arrData['meeting_date']        = $entry[10];
                            $arrData['meeting_start']       = $entry[11];
                            $arrData['meeting_end']         = $entry[12];
                            $arrData['meeting_duration']    = $entry[13];
                            $arrData['notes']               = $entry[14];
                            $arrData['reviewed']            = $entry[15];
                            $arrData['deleted']             = $entry[16];
                            $arrData['label']               = $entry[17];
                            $arrData['work_assignment_id']  = $entry[18];
                            
                            // If this is a meeting service
                            if($entry[6] == 1) {
                                $dur = ceil($arrData['meeting_duration'] / 60);
                                $arrData['price'] = $dur * $arrData['price'];
                            }
                            // If this is an Editing Service transaction
                            else if($entry[6] == 19) {
                                // Get the total of quarters
                                //$dur = ceil($arrData['meeting_duration'] / 15);
                                $dur = $arrData['meeting_duration'];
                                // Quartered hourly rate
                                $arrData['price'] = $dur * 0.5;
                            }
                            
                            // Generate as "List"
                            $strListTemplate = ($this->entry_customItemTpl != '' ? $this->entry_customItemTpl : 'transaction_review_list');
                            $objListTemplate = new \FrontendTemplate($strListTemplate);
                            $objListTemplate->setData($arrData);
                            
                            if($arrData['service'] == 'Misc. Billing')
                                $entryHistoryMisc[$entry_id] = $objListTemplate->parse();
                            else
                                $entryHistory[$entry_id] = $objListTemplate->parse();
                            
                            
                            $trans_ids[$transaction_id] = $entry_id;
                            
                            
                            
                            $arrData['service']             = $entry[6];
                            
                            $strListTemplate = ($this->entry_customItemTpl != '' ? $this->entry_customItemTpl : 'transaction_review_form');
                            $objListTemplate = new \FrontendTemplate($strListTemplate);
                            $objListTemplate->setData($arrData);
                            $entryHistoryForm[$entry_id] = $objListTemplate->parse();
                            
                            $transaction_id++;
                        }
                    }
                    
                }
                
            }
            
            $entry_id++;
        }
        
        // set this users entries to the template
        $this->Template->transactionReviewForms = $entryHistoryForm;
        $this->Template->transactionReview = $entryHistory;
        $this->Template->transactionReviewMisc = $entryHistoryMisc;
        $this->Template->transactionRowIDs = $trans_ids;
        
        
        
        
        // get invoice number from Psychologist sheet and add to template
        $range = 'Psychologists';
        $response = $this->$service->spreadsheets_values->get(ModTransactionReview::$spreadsheetId, $range);
        $values = $response->getValues();
        
        $entry_id = 1;
        foreach($values as $entry) {
            
            // if the id matches this entry, it is related to our user
            if($entry_id != 1) {
                
                if($user == $entry[1]) {
                    
                    if($entry[0] != '')
                        $this->Template->invoiceNumber = sprintf('%06d', $entry[0]);
                    else
                        $this->Template->invoiceNumber = "000001";
                }
                
            }
            
            $entry_id++;
        }
        
        
        
        
        
        
        
        
	}
	
	function getServiceNameFromCode($service_code){
    	switch ($service_code) {
    		case 1:
    			return 'Meeting';
    			break;
    		case 2:
    			return 'Psych/Achvmt';
    			break;
    		case 3:
    			return 'Psych';
    			break;
    		case 4:
    			return 'Achvmt';
    			break;
    		case 5:
    			return 'Psych/Achvmt/Obs';
    			break;
    		case 6:
    			return 'Psych/Obs';
    			break;
    		case 7:
    			return 'Achvmt/Obs';
    			break;
    		case 8:
    			return 'Psych/Achvmt/Additional';
    			break;
    		case 9:
    			return 'Psych/Additional';
    			break;
    		case 10:
    			return 'Achvmt/Additional';
    			break;
    		case 11:
    			return 'Rating Scales';
    			break;
    		case 12:
    			return 'Mtg Late Cancel';
    			break;
    		case 13:
    			return 'Test Late Cancel';
    			break;
    		case 14:
    			return 'Parking';
    			break;
    		case 15:
    			return 'Review District Report';
    			break;
    		case 16:
    			return 'Obs';
    			break;
    		case 17:
    			return 'Record Review';
    			break;
    		case 99:
    			return 'Misc. Billing';
    			break;
    		default:
    		    return 'Invalid Service Code';
    		    break;
    	}
    }

	
} 
