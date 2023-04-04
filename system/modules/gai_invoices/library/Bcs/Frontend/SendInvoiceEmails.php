<?php

namespace Bcs\Frontend;

use Contao\System;
use Contao\Frontend as Contao_Frontend;



class SendInvoiceEmails extends Contao_Frontend {

    public function sendEmails() {
        if (substr(\Environment::get('request'), 0, 4) == "api/") {

        }
    }

}
