<?php

namespace Bcs;

use Contao\Database;

class Handler
{
    protected static $arrUserOptions = array();

    public function onProcessForm($submittedData, $formData, $files, $labels, $form)
    {
        
        if($formData['formID'] == 'directory_submission') {
          //$submittedData['first_name']
          
          echo "GAI Invoices: Triggered";
          
        }
    }
}
