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
        // Get this user's unprocessed listings from Sheets
        $spreadsheet = $this->$service->spreadsheets->get(ModCreateInvoice::$spreadsheetId);
        
        // get all of our unarchived Transactions
        $range = 'Work Assignment';
        $response = $this->$service->spreadsheets_values->get(ModCreateInvoice::$spreadsheetId, $range);
        $values = $response->getValues();
        
        // an array to store this users entries
        $entries = array();
        $objUser = \FrontendUser::getInstance();
        
        $entry_id = 0;
        foreach($values as $entry) {
            
            // if the id matches this entry, it is related to our user
            //if($entry[9] == $objUser->id) {
                
                array_push($entries,$entry);
                
                $strListingKey = $entry_id;
                if (!array_key_exists($strListingKey, $entries)) {
                    $arrListings[$strListingKey] = array(
                        "date"              => $entry[0],
                        '30_days'           => $entry[1],
                        '45_days'           => $entry[2],
                        'psychologist'      => $entry[3],
                        'district'          => $entry[4],
                        'student_name'      => $entry[5],
                        'date_of_birth'     => $entry[6],
                        'grade'             => $entry[7],
                        'lasid'             => $entry[8],
                        'sasid'             => $entry[9],
                        'initial'           => $entry[10],
                        'type_of_testing'   => $entry[11],
                        'testing_date'      => $entry[12],
                        'meeting_required'  => $entry[13],
                        'meeting_date'      => $entry[14],
                        'parent_info'       => $entry[15],
                        'teacher_info'      => $entry[16],
                        'team_chair'        => $entry[17],
                        'email'             => $entry[18],
                        'report_submitted'  => $entry[19],
                        'invoiced_to_gai'		=> $entry[20],
                        'district_invoice'  => $entry[21],
                        'notes'             => $entry[22]
                    );
                    
                    $strItemTemplate = ($this->entry_customItemTpl != '' ? $this->entry_customItemTpl : 'item_work_assignment');
                    $objTemplate = new \FrontendTemplate($strItemTemplate);
                    $objTemplate->setData($entries[$strListingKey]);
                    $entries[$strListingKey] = $objTemplate->parse();
                    
                }
                
           // }
            
            $entry_id++;
        }
        
        // set this users entries to the template
        $this->Template->entries = $entries;

	}
} 
