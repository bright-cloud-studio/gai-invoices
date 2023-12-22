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

class ModMiscTravelExpenses extends \Contao\Module
{

  /** Template  @var string */
  protected $strTemplate = 'mod_misc_travel_expenses';
  
  // our google api stuffs
  protected $client;
  protected $service;
  public static $spreadsheetId;
  
  protected $arrStates = array();
  
  /** @param \ModuleModel $objModule @param string */
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
    ModAddMeetings::$spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
  }
  
  /** Display a wildcard in the back end @return string */
  public function generate()
  {
    
    if (TL_MODE == 'BE')
    {
      $objTemplate = new \BackendTemplate('be_wildcard');
      
      $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['add_meetings'][0]) . ' ###';
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
    $GLOBALS['TL_BODY']['misc_travel_expenses'] = '<script src="system/modules/gai_invoices/assets/js/gai_invoice.js?v='.$rand_ver.'"></script>';
  }
  
} 
