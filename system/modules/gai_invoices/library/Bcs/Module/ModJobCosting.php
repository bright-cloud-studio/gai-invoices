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
           
                //$psys[trim($entry[2])]['price'] += intval(trim($entry[7]));
                
                $psys[trim($entry[2])]['price'] += $this->calculatePrice($entry[6], intval(trim($entry[7])), $entry[13] );
                
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
        	          label: "Total Billed (Month-to-Date)",
        	          backgroundColor: [
                        "#C0392B",
                        "#ffd76a",
                        "#9B59B6",
                        "#2980B9",
                        "#42c8b0",
                        "#27AE60",
                        "#d36f88",
                        "#F1C40F",
                        "#E67E22",
                        "#fc8d45",
                        "#E67E22",
                        "#8E44AD",
                        "#3498DB",
                        "#16A085",
                        "#4575f3",
                        "#2ECC71",
                        "#F39C12",
                        "#D35400",
                        "#ff9933",
                        "#e4007c",
                        "#881c9e",
                        "#1eebc9",
                        "#6933b0",
                        "#d0ff14",
                        "#008b8b",
                        "#01027b",
                        "#95bedd",
                        "#1ABC9C",
                        "#ec833f"
                      ],
        	          data: [';
        	          
        	          foreach($psys as $psy) {
            	          $config .= '"' . $psy['price'] . '", ';
            	      }
        	          
        	          $config .= ']
        	        }
        	      ]
        	    },
        	    options: {
        	        plugins: {
        	        
        	            tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || "";
            
                                    if (label) {
                                        label += ": ";
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        },
        	        
        	        
        	            legend: {
        	                display: false
        	            }
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
	
	
	public function calculatePrice($service_code, $price, $meeting_duration) {
	    
        switch ($service_code) {
            case 1:
                
                //echo "Service Code: " . $service_code . "<br>";
                //echo "Price: " . $price . "<br>";
                //echo "Meeting Duration: " . $meeting_duration . "<br>";
                
                $dur = ceil($meeting_duration / 60);
        
                //echo "Dur: " . $dur . "<br>";
                //echo "Calculated: " . $dur * $price . "<br>";
                //echo "<br><br>";

                // Get our quarters, rounded up
                return $dur * $price;
                
                break;
            case 19:
                
                   return $meeting_duration * 0.5; 
                    
                break;
            default:
                return $price;
                //code block
        }
	    
	    
	    
	}

} 
