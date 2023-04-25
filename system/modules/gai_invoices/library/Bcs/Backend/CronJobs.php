<?php

namespace Bcs\Backend;

use Contao\System;

class CronJobs extends System
{
    public function sendReminderEmails(): void
    {
       
        // Testing the controller log
        \Controller::log('GAI: Sending Reminder Emails',
            __CLASS__ . '::' . __FUNCTION__,
            'GENERAL'
        );
       
    }
}
