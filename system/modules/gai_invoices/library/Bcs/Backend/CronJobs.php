<?php

namespace Bcs\Backend;

use Contao\System;
use Contao\MemberModel;

class CronJobs extends System
{
    public function importFromSheets(): void
    {
       
        // Add a log entry so we know things are going as expected
        \Controller::log('GAI IMPORT: Importing from SHeets', __CLASS__ . '::' . __FUNCTION__, 'GENERAL');

    }
}
