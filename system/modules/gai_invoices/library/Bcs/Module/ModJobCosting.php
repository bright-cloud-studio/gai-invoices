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

        $entry_id = 0;
        $psys = array();
        /* Loop through our sheets data */
        foreach($values as $entry) {

           if($entry_id >= 1) {
           
                $psys[trim($entry[2])]['price'] += intval(trim($entry[7]));
                
                //echo "PSYS: " . $psys[trim($entry[2])]['price'] . "<br>";
                //echo "PRICE: " . trim($entry[7]) . "<br>";
                
           }
            
            $entry_id++;
            
        }
        
        
        // Chart 1
        $config = '
            const chb = document.getElementById("chart_horizontal_bar");
	
        	new Chart(chb, {
        		type: "bar",
        	    data: {
        	      labels: [';
        	      
        	      foreach($psys as $key=>$psy) {
        	          $config .= '"' . $key . '", ';
        	      }
        	      
        $config .= '],
        	      datasets: [
        	        {
        	          label: "Dollars Billed (Month-to-Date)",
        	          backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
        	          data: [';
        	          
        	          foreach($psys as $psy) {
            	          $config .= '"' . $psy['price'] . '", ';
            	      }
        	          
        	          $config .= ']
        	        }
        	      ]
        	    },
        	    options: {
        	      legend: { display: true },
        	      title: {
        	        display: true,
        	        text: "Predicted world population (millions) in 2050"
        	      }
        	    }
        	});
        ';
        $GLOBALS['TL_BODY'][] = '<script>' . $config . '</script>';
        
        
        
        // Chart 2
        $config2 = '
            const chb2 = document.getElementById("chart_horizontal_bar_2");
	
        	new Chart(chb2, {
        		type: "bar",
        	    data: {
        	      labels: [';
        	      
        	      foreach($psys as $key=>$psy) {
        	          $config2 .= '"' . $key . '", ';
        	      }
        	      
        $config2 .= '],
        	      datasets: [
        	        {
        	          label: "Services Used (Month-to-Date)",
        	          backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
        	          data: [';
        	          
        	          foreach($psys as $psy) {
            	          $config2 .= '"' . $psy['price'] . '", ';
            	      }
        	          
        	          $config2 .= ']
        	        }
        	      ]
        	    },
        	    options: {
        	      legend: { display: true },
        	      title: {
        	        display: true,
        	        text: "Predicted world population (millions) in 2050"
        	      }
        	    }
        	});
        ';
        $GLOBALS['TL_BODY'][] = '<script>' . $config2 . '</script>';
        
	}

} 
