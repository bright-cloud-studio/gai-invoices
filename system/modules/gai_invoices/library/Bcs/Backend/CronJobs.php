<?php

namespace Bcs\Backend;

use Contao\System;
use Contao\MemberModel;

class CronJobs extends System
{
    public function importPsychologistsAndServices(): void
    {
       
        // Add a log entry so we know things are going as expected
        \Controller::log('GAI IMPORT: Importing from Sheets', __CLASS__ . '::' . __FUNCTION__, 'GENERAL');
        
        // Connect to Sheets
        
        
        
        
        // Import Psychologist data from the "Psychologists" sheet
            
            // If existing entry, update it
        
            // Otherwise, create new entry
        
        
        
        
        // Import Services data from the "Services" sheet
        
            // If existing entry, update it
        
            // Otherwise, create new entry
        
        
        
        
    }
}
