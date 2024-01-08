<?php
  
  // Start PHP session and include Composer, which also brings in our Google Sheets PHP stuffs
  session_start();
  require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
  
  // Connect to DB
  $dbh = new mysqli("localhost", "globalassinc_user", "Z2rc^wQ}98TS9mtl5y", "globalassinc_contao_4_13");
  if ($dbh->connect_error) { die("Connection failed: " . $dbh->connect_error); }
  
  // Store the passed form values
  $vars = $_POST;
  
  // Clean up our psychologist name so it can be used as a filename
  $cleanName = str_replace(' ', '_', $vars['psychologist']);
  $cleanName = str_replace('.', '', $cleanName);
  $cleanName = preg_replace('/[^a-zA-Z0-9_.]/', '_', strtolower($cleanName));
  
  // Clean the price, remove symbols and decimals if there are any
  $price = '';
  if($vars['price'] != '') {
    // first remove dollar sign
    $price = str_replace('$','',$vars['price']);
    // remove decimal and trailing numbers
    $price = floor($price);
  }
  
  // create a file with the name "psy_datetime.txt" to log our transaction data
  $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . '/../transaction_logs/editing_services_'.$cleanName."_".strtolower(date('l_F_d_Y_H:m:s')).".txt", "w") or die("Unable to open file!");
  // loop through our $vars and write the key => value to our created file
  foreach($vars as $key => $var) {
    fwrite($myfile, "Key: " . $key . "  | Value: " . $var . "\n");
  }
  
  // were done logging, close the file we just created
  fclose($myfile);
  
  // Make time from our passed in date field
  $curTime = strtotime($vars['date']);
  $curMonth = date("m",$curTime);
  $curYear = date("Y",$curTime);
  
  // Check for duplicate
  $duplicate = false;
  
  $query =  "select * from tl_transactions WHERE deleted=''";
  $result = $dbh->query($query);
  if($result) {
    
    while($row = $result->fetch_assoc()) {
      
      // If Psychologists match
      if($vars['psychologist'] == $row['psychologist']) {
        
        // make time from our database field
        $dbTime = strtotime($row['date']);
        $dbMonth = date("m",$dbTime);
        $dbYear = date("Y",$dbTime);
        
        // if our db date is from the current month and current year
        if($curMonth == $dbMonth && $curYear == $dbYear) {
          
          // if this service is a misc. billing entry
          if($vars['service_provided'] == 19) {
            if($price == $row['price']){
              if($vars['total_minutes'] == $row['meeting_duration']) {
                $duplicate = true;
                
                $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . '/../duplicate_checks/editing_services_'.$cleanName."_".strtolower(date('l_F_d_Y_H:m:s')).".txt", "w") or die("Unable to open file!");
                foreach($vars as $key => $var) {
                  fwrite($myfile, "Key: " . $key . "  | Value: " . $var . "\n");
                }
                fwrite($myfile, "\n\n");
                foreach($row as $key => $var) {
                  fwrite($myfile, "Key: " . $key . "  | Value: " . $var . "\n");
                }
                fclose($myfile);
              }
            }
          }
          
        }
        
      }
      
    }
    
  }
  
  if($duplicate == "true") {
    echo "duplicate";
    return;
  } else {
    
    // Create a client connection to Google
    $client = new Google\Client();
    $client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/key.json');
    $client->addScope(Google\Service\Sheets::SPREADSHEETS);
    $service = new \Google_Service_Sheets($client);
    $spreadsheetId = '1PEJN5ZGlzooQrtIEdeo4_nZH73W0aJTUbRIoibzl3Lo';
    
    
    // store our newly created Transaction with the filled in data.
    // transactions have more fields than we need so fill in the blanks with ''
    $newRow = [
    date('F'),
    $vars['date'],
    $vars['psychologist'],
    '',
    '',
    '',
    $vars['service_provided'],
    $price,
    '',
    '',
    '',
    '',
    '',
    $vars['total_minutes'],
    $vars['notes'],
    '',
    '',
    'Editing Services'
    ];
    
    $rows = [$newRow];
    $valueRange = new \Google_Service_Sheets_ValueRange();
    $valueRange->setValues($rows);
    $range = 'Transactions';
    $options = ['valueInputOption' => 'USER_ENTERED'];
    $service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $options);
        
    // insert into the tl_transactions table
    $query = "INSERT INTO tl_transactions (tstamp, date, psychologist, service_provided, price, meeting_duration, notes, misc_billing, published)
    VALUES ('".time()."', '".$vars['date']."', '".$vars['psychologist']."', '19', '".$price."', '".$vars['total_minutes']."', '".$vars['notes']."', 'Editing Services',  '1')";
    $result = $dbh->query($query);
    
    // display some text to return back to the ajax call
    echo "success";
    
  }
