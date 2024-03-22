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
 
class ModJobCosting extends \Contao\Module
{
 
    /**
     * Template3
     * @var string
     */
    protected $strTemplate = 'mod_job_costing';
  
    // our google api stuffs
    protected $client;
    protected $service;
    public static $spreadsheetId;
 
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
        ModJobCosting::$spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';

        // Include Chart.js and our configuration script
        $GLOBALS['TL_JAVASCRIPT']['chart_cdn'] = 'https://cdn.jsdelivr.net/npm/chart.js';
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
 
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['job_costing'][0]) . ' ###';
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
        date_default_timezone_set('America/Los_Angeles')
        
        // Import the Database stuffs so we can make queries
        //$this->import('Database');
        
        // Include our JS with a unique code to prefent caching
        $rand_ver = rand(1,9999);
        $GLOBALS['TL_BODY']['work_assignments'] = '<script src="system/modules/gai_invoices/assets/js/gai_invoice.js?v='.$rand_ver.'"></script>';
        
        // get the user and build their name
        $objUser = \FrontendUser::getInstance();
        $user = $objUser->firstname . " " . $objUser->lastname;
        
        // Get this user's unprocessed listings from Sheets
        $spreadsheet = $this->$service->spreadsheets->get(ModJobCosting::$spreadsheetId);
        
        // LIVE
        $range = 'Transactions';
        
        $response = $this->$service->spreadsheets_values->get(ModJobCosting::$spreadsheetId, $range);
        $values = $response->getValues();

        $entry_id = 1;
        /* Loop through our sheets data */
        foreach($values as $entry) {

            // if the id matches this entry, it is related to our user
            if($entry_id != 1) {

                if($entry[16] != 1) {

                    echo $entry;
                    echo "<br><br>";
                    
                }
                
            }
            
        }

        
	}

} 
