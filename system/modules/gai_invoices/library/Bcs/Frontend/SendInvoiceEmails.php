<?php

namespace Bcs\Frontend;

use Contao\System;


class sendInvoiceEmails extends Contao_Frontend {

    protected $store_id = 0;

    protected $strCookie = 'FE_USER_AUTH';

    public function initializeApi() {
        if (substr(\Environment::get('request'), 0, 4) == "api/") {

        }
    }

}
