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
    
    public $services = array();
    public $schools = array();
    public $psys = array();
    public $districts = array();
 
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
        
        $services_id = 0;
        $range_serv = 'Services';
        $response_serv = $this->$service->spreadsheets_values->get(ModJobCosting::$spreadsheetId, $range_serv);
        $values_serv = $response_serv->getValues();
        
        foreach($values_serv as $entry_serv) {
            if($services_id >= 1) {
                $services[$entry_serv[0]]['name'] = $entry_serv[1];
                
                $services[$entry_serv[0]]['Psychologist Tier 1'] = str_replace('$','',$entry_serv[1]);
                $services[$entry_serv[0]]['Psychologist Tier 2'] = str_replace('$','',$entry_serv[2]);
                $services[$entry_serv[0]]['Psychologist Tier 3'] = str_replace('$','',$entry_serv[3]);
                $services[$entry_serv[0]]['Psychologist Tier 4'] = str_replace('$','',$entry_serv[4]);
                $services[$entry_serv[0]]['Psychologist Tier 5'] = str_replace('$','',$entry_serv[5]);
                $services[$entry_serv[0]]['Psychologist Tier 6'] = str_replace('$','',$entry_serv[6]);
                $services[$entry_serv[0]]['Psychologist Tier 7'] = str_replace('$','',$entry_serv[7]);
                $services[$entry_serv[0]]['Psychologist Tier 8'] = str_replace('$','',$entry_serv[8]);
                $services[$entry_serv[0]]['Psychologist Tier 9'] = str_replace('$','',$entry_serv[9]);
                $services[$entry_serv[0]]['Psychologist Tier 10'] = str_replace('$','',$entry_serv[10]);
                
                $services[$entry_serv[0]]['School Tier 1'] = str_replace('$','',$entry_serv[11]);
                $services[$entry_serv[0]]['School Tier 2'] = str_replace('$','',$entry_serv[12]);
                $services[$entry_serv[0]]['School Tier 3'] = str_replace('$','',$entry_serv[13]);
            }
                
            $services_id++;
        }
        
        
        
        
        
        $schools_id = 0;
        $range_schools = 'Schools';
        $response_schools = $this->$service->spreadsheets_values->get(ModJobCosting::$spreadsheetId, $range_schools);
        $values_schools = $response_schools->getValues();
        
        foreach($values_schools as $entry_school) {
            if($schools_id >= 1) {
                $schools[trim($entry_school[0])]['name'] = $entry_school[1];
                $schools[trim($entry_school[2])]['tier'] = $entry_school[13];
            }
            $schools_id++;
        }
        
        
        
        $entry_id = 0;
        $range = 'Transactions';
        $response = $this->$service->spreadsheets_values->get(ModJobCosting::$spreadsheetId, $range);
        $values = $response->getValues();

        foreach($values as $entry) {
           if($entry_id >= 1) {
               
               if($entry[16] != '1') {
                   
                    $psys[trim($entry[2])]['price'] += $this->calculatePrice($entry[6], intval(trim($entry[7])), $entry[13] );
                    $psys[trim($entry[2])]['total_meeting_minutes'] += intval($entry[13]);
                    
                    if($entry[6] != '99') {
                        
                        $districts[trim($entry[3])]['price'] += $this->calculateSchoolPrice($entry[6], intval($services[$entry[6]][$schools[$entry[3]]['tier']]), $entry[13]);
                        $districts[trim($entry[3])]['total_meeting_minutes'] += intval($entry[13]);
                        $districts[trim($entry[3])]['tier'] =  $schools[trim($entry[3])]['tier'];
                    }
                    
                    $services[$entry[6]]['total_usage'] += 1;
                   
               }
               
                
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
        	          label: "Total Billed",
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
        	      
        	      foreach($services as $service) {
        	          if($service['total_usage'] > 0)
        	            $config2 .= '"' . $service['name'] . '", ';
        	      }
        	      
        $config2 .= '],
        	      datasets: [
        	        {
        	          label: "Total Usage",
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
        	          
        	          foreach($services as $service) {
        	              if($service['total_usage'] > 0)
            	            $config2 .= '"' . $service['total_usage'] . '", ';
            	      }
        	          
        	          $config2 .= ']
        	        }
        	      ]
        	    },
        	    options: {
        	        plugins: {
        	            legend: {
        	                display: false
        	            }
        	        }
        	    }
        	});
        ';
        $GLOBALS['TL_BODY'][] = '<script>' . $config2 . '</script>';
        
        
        
        
        
        
        
        
        
        
        // Chart 3
        $config3 = '
            const chb3 = document.getElementById("chart_horizontal_bar_3");
	
        	new Chart(chb3, {
        		type: "bar",
        	    data: {
        	      labels: [';
        	      
        	      foreach($psys as $key=>$psy) {
        	          if($psy['total_meeting_minutes'] > 0)
        	            $config3 .= '"' . $key . '", ';
        	      }
        	      
        $config3 .= '],
        	      datasets: [
        	        {
        	          label: "Total Meeting Minutes",
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
        	              if($psy['total_meeting_minutes'] > 0)
            	            $config3 .= '"' . $psy['total_meeting_minutes'] . '", ';
            	      }
        	          
        	          $config3 .= ']
        	        }
        	      ]
        	    },
        	    options: {
        	        plugins: {

        	            legend: {
        	                display: false
        	            }
        	        }
        	    }
        	});
        ';
        $GLOBALS['TL_BODY'][] = '<script>' . $config3 . '</script>';
        
        
        
        
        
        // Chart 4
        $config4 = '
            const chb4 = document.getElementById("chart_horizontal_bar_4");
	
        	new Chart(chb4, {
        		type: "bar",
        	    data: {
        	      labels: [';
        	      
        	      foreach($districts as $key=>$district) {
        	          $config4 .= '"' . $key . '", ';
        	      }
        	      
        $config4 .= '],
        	      datasets: [
        	        {
        	          label: "Total Billed",
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
        	          
        	          foreach($districts as $district) {
            	          $config4 .= '"' . $district['price'] . '", ';
            	      }
        	          
        	          $config4 .= ']
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
        $GLOBALS['TL_BODY'][] = '<script>' . $config4 . '</script>';
        
        
        
        
        
        
        
        // Chart 5
        $config5 = '
            const chb5 = document.getElementById("chart_horizontal_bar_5");
	
        	new Chart(chb5, {
        		type: "bar",
        	    data: {
        	      labels: [';
        	      
        	      foreach($districts as $key=>$district) {
        	          if($district['total_meeting_minutes'] > 0)
        	            $config5 .= '"' . $key . '", ';
        	      }
        	      
        $config5 .= '],
        	      datasets: [
        	        {
        	          label: "Total Meeting Minutes",
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
        	          
        	          foreach($districts as $district) {
        	              if($district['total_meeting_minutes'] > 0)
            	            $config5 .= '"' . $district['total_meeting_minutes'] . '", ';
            	      }
        	          
        	          $config5 .= ']
        	        }
        	      ]
        	    },
        	    options: {
        	        plugins: {

        	            legend: {
        	                display: false
        	            }
        	        }
        	    }
        	});
        ';
        $GLOBALS['TL_BODY'][] = '<script>' . $config5 . '</script>';
        
        
        
        
        
        
        
        
        
        // Chart 6
        /*
        $config6 = '
            const chb6 = document.getElementById("chart_horizontal_bar_6");
	
        	new Chart(chb6, {
        		type: "bar",
        	    data: {
        	      labels: [';
        	      
        	      foreach($services as $service) {
        	          if($service['total_usage'] > 0)
        	            $config6 .= '"' . $service['name'] . '", ';
        	      }
        	      
        $config6 .= '],
        	      datasets: [
        	        {
        	          label: "Total Usage",
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
        	          
        	          foreach($services as $service) {
        	              if($service['total_usage'] > 0)
            	            $config6 .= '"' . $service['total_usage'] . '", ';
            	      }
        	          
        	          $config6 .= ']
        	        }
        	      ]
        	    },
        	    options: {
        	        plugins: {
        	            legend: {
        	                display: false
        	            }
        	        }
        	    }
        	});
        ';
        $GLOBALS['TL_BODY'][] = '<script>' . $config6 . '</script>';
        
        */
        
        
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
	
	
	public function calculateSchoolPrice($service_code, $price, $meeting_duration) {
	    
        switch ($service_code) {
            case 1:
                
                $half_rate = $price / 2;
                $quarter_rate = $price / 4;
                
                if($meeting_duration <= 30) {
                    return $half_rate;
                } else {
                    $dur = ceil(($meeting_duration - 30) / 15);
                    return $half_rate + ($dur * $quarter_rate);
                }
                
                break;
            default:
                return $price;
                //code block
        }
        
	}

} 
