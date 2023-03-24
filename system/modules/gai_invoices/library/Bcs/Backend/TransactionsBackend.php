<?php

/**
 * Bright Cloud Studio's GAI Invoices
 *
 * Copyright (C) 2022-2023 Bright Cloud Studio
 *
 * @package    bright-cloud-studio/gai-invoices
 * @link       https://www.brightcloudstudio.com/
 * @license    http://opensource.org/licenses/lgpl-3.0.html
**/

namespace Bcs\Backend;

use Contao\DataContainer;
use Bcs\Model\Transaction;


class TransactionsBackend extends \Backend
{
  
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(\Input::get('tid')))
		{
			$this->toggleVisibility(\Input::get('tid'), (\Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ';
	}	
	

	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
	{
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_listing']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_listing']['fields']['published']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, ($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_listing SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")->execute($intId);
		$this->log('A new version of record "tl_listing.id='.$intId.'" has been created'.$this->getParentEntries('tl_listing', $intId), __METHOD__, TL_GENERAL);
	}
	
	public function exportListings()
	{
		$objListing = Listing::findAll();
		$strDelimiter = ',';
	
		if ($objListing) {
			$strFilename = "listings_" .(date('Y-m-d_Hi')) ."csv";
			$tmpFile = fopen('php://memory', 'w');
			
			$count = 0;
			while($objListing->next()) {
				$row = $objListing->row();
				if ($count == 0) {
					$arrColumns = array();
					foreach ($row as $key => $value) {
						$arrColumns[] = $key;
					}
					fputcsv($tmpFile, $arrColumns, $strDelimiter);
				}
				$count ++;
				fputcsv($tmpFile, $row, $strDelimiter);
			}
			
			fseek($tmpFile, 0);
			
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="' . $strFilename . '";');
			fpassthru($tmpFile);
			exit();
		} else {
			return "Nothing to export";
		}
	}
	
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;
		
		// Generate an alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$varValue = standardize(\StringUtil::restoreBasicEntities($dc->activeRecord->name));
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_listing WHERE id=? OR alias=?")->execute($dc->id, $varValue);

		// Check whether the page alias exists
		if ($objAlias->numRows > 1)
		{
			if (!$autoAlias)
			{
				throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
			}

			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}
	
    public function getProfessions() {
        return array(
            'Psychiatrist' => 'Psychiatrist',
            'Psychiatric nurse practitioner' => 'Psychiatric nurse practitioner',
            'Medical nurse practitioner' => 'Medical nurse practitioner',
            'Registered nurse' => 'Registered nurse',
            'Licensed practical nurse' => 'Licensed practical nurse',
            'Physician - internal medicine' => 'Physician - internal medicine',
            'Physician - family medicine' => 'Physician - family medicine',
            'Physician - obesity medicine' => 'Physician - obesity medicine',
            'Neurologist' => 'Neurologist',
            'Physician - specialist' => 'Physician - specialist',
            'Physician assistant' => 'Physician assistant',
            'Registered dietitian' => 'Registered dietitian',
            'Nutritional therapist' => 'Nutritional therapist',
            'Naturopathic doctor' => 'Naturopathic doctor',
            'Psychologist' => 'Psychologist',
            'Social worker' => 'Social worker',
            'Licensed counselor' => 'Licensed counselor',
            'Marriage and family therapist' => 'Marriage and family therapist',
            'Food addiction specialist' => 'Food addiction specialist',
            'Health coach' => 'Health coach',
            'Peer support specialist' => 'Peer support specialist'
        );
    }
    
    public function optionsStates() {
		return array(
			'United States' => array(
				'Alabama' => 'Alabama',
				'Alaska' => 'Alaska',
				'Arizona' => 'Arizona',
				'Arkansas' => 'Arkansas',
				'California' => 'California',
				'Colorado' => 'Colorado',
				'Connecticut' => 'Connecticut',
				'Delaware' => 'Delaware',
				'Florida' => 'Florida',
				'Georgia' => 'Georgia',
				'Hawaii' => 'Hawaii',
				'Idaho' => 'Idaho',
				'Illinois' => 'Illinois',
				'Indiana' => 'Indiana',
				'Iowa' => 'Iowa',
				'Kansas' => 'Kansas',
				'Kentucky' => 'Kentucky',
				'Louisiana' => 'Louisiana',
				'Maine' => 'Maine',
				'Maryland' => 'Maryland',
				'Massachusetts' => 'Massachusetts',
				'Michigan' => 'Michigan',
				'Minnesota' => 'Minnesota',
				'Mississippi' => 'Mississippi',
				'Missouri' => 'Missouri',
				'Montana' => 'Montana',
				'Nebraska' => 'Nebraska',
				'Nevada' => 'Nevada',
				'New Hampshire' => 'New Hampshire',
				'New Jersey' => 'New Jersey',
				'New Mexico' => 'New Mexico',
				'New York' => 'New York',
				'North Carolina' => 'North Carolina',
				'North Dakota' => 'North Dakota',
				'Ohio' => 'Ohio',
				'Oklahoma' => 'Oklahoma',
				'Oregon' => 'Oregon',
				'Pennsylvania' => 'Pennsylvania',
				'Rhode Island' => 'Rhode Island',
				'South Carolina' => 'South Carolina',
				'South Dakota' => 'South Dakota',
				'Tennessee' => 'Tennessee',
				'Texas' => 'Texas',
				'Utah' => 'Utah',
				'Vermont' => 'Vermont',
				'Virginia' => 'Virginia',
				'Washington' => 'Washington',
				'West Virginia' => 'West Virginia',
				'Wisconsin' => 'Wisconsin',
				'Wyoming' => 'Wyoming',
				'American Samoa' => 'American Samoa',
				'District of Columbia' => 'District of Columbia',
				'Federated States of Micronesia' => 'Federated States of Micronesia',
				'Guam' => 'Guam',
				'Marshall Islands' => 'Marshall Islands',
				'Northern Mariana Islands' => 'Northern Mariana Islands',
				'Palau' => 'Palau',
				'Puerto Rico' => 'Puerto Rico',
				'Virgin Islands' => 'Virgin Islands'),
			'Canada' => array(
				'Alberta' => 'Alberta',
				'British Columbia' => 'British Columbia',
				'Manitoba' => 'Manitoba',
				'New Brunswick' => 'New Brunswick',
				'Newfoundland and Labrador' => 'Newfoundland and Labrador',
				'Nova Scotia' => 'Nova Scotia',
				'Northwest Territories' => 'Northwest Territories',
				'Nunavut' => 'Nunavut',
				'Ontario' => 'Ontario',
				'Prince Edward Island' => 'Prince Edward Island',
				'Quebec' => 'Quebec',
				'Saskatchewan' => 'Saskatchewan',
				'Yukon' => 'Yukon')
			);
	}
    
    public function optionsCountries() {
		return array(
				'USA'       => 'United States',
				'Afghanistan'    => 'Afghanistan',
				'Albania'    => 'Albania',
				'Algeria'    => 'Algeria',
				'Andorra'    => 'Andorra',
				'Angola'    => 'Angola',
				'Antigua and Barbuda'    => 'Antigua and Barbuda',
				'Argentina'    => 'Argentina',
				'Armenia'    => 'Armenia',
				'Australia'    => 'Australia',
				'Austria'    => 'Austria',
				'Azerbaijan'    => 'Azerbaijan',
				'Bahamas'    => 'Bahamas',
				'Bahrain'    => 'Bahrain',
				'Bangladesh'    => 'Bangladesh',
				'Barbados'    => 'Barbados',
				'Belarus'    => 'Belarus',
				'Belgium'    => 'Belgium',
				'Belize'    => 'Belize',
				'Benin'    => 'Benin',
				'Bhutan'    => 'Bhutan',
				'Bolivia'    => 'Bolivia',
				'Bosnia and Herzegovina'    => 'Bosnia and Herzegovina',
				'Botswana'    => 'Botswana',
				'Brazil'    => 'Brazil',
				'Brunei'    => 'Brunei',
				'Bulgaria'    => 'Bulgaria',
				'Burkina Faso'    => 'Burkina Faso',
				'Burundi'    => 'Burundi',
				"C么te d'Ivoire"    => "C么te d'Ivoire",
				'Cabo Verde'    => 'Cabo Verde',
				'Cambodia'    => 'Cambodia',
				'Cameroon'    => 'Cameroon',
				'Canada'    => 'Canada',
				'Central African Republic'    => 'Central African Republic',
				'Chad'    => 'Chad',
				'Chile'    => 'Chile',
				'China'    => 'China',
				'Colombia'    => 'Colombia',
				'Comoros'    => 'Comoros',
				'Congo (Congo-Brazzaville)'    => 'Congo (Congo-Brazzaville)',
				'Costa Rica'    => 'Costa Rica',
				'Croatia'    => 'Croatia',
				'Cuba'    => 'Cuba',
				'Cyprus'    => 'Cyprus',
				'Czechia (Czech Republic)'    => 'Czechia (Czech Republic)',
				'Democratic Republic of the Congo'    => 'Democratic Republic of the Congo',
				'Denmark'    => 'Denmark',
				'Djibouti'    => 'Djibouti',
				'Dominica'    => 'Dominica',
				'Dominican Republic'    => 'Dominican Republic',
				'Ecuador'    => 'Ecuador',
				'Egypt'    => 'Egypt',
				'El Salvador'    => 'El Salvador',
				'Equatorial Guinea'    => 'Equatorial Guinea',
				'Eritrea'    => 'Eritrea',
				'Estonia'    => 'Estonia',
				'Eswatini'    => 'Eswatini',
				'Ethiopia'    => 'Ethiopia',
				'Fiji'    => 'Fiji',
				'Finland'    => 'Finland',
				'France'    => 'France',
				'Gabon'    => 'Gabon',
				'Gambia'    => 'Gambia',
				'Georgia'    => 'Georgia',
				'Germany'    => 'Germany',
				'Ghana'    => 'Ghana',
				'Greece'    => 'Greece',
				'Grenada'    => 'Grenada',
				'Guatemala'    => 'Guatemala',
				'Guinea'    => 'Guinea',
				'Guinea-Bissau'    => 'Guinea-Bissau',
				'Guyana'    => 'Guyana',
				'Haiti'    => 'Haiti',
				'Holy See'    => 'Holy See',
				'Honduras'    => 'Honduras',
				'Hungary'    => 'Hungary',
				'Iceland'    => 'Iceland',
				'India'    => 'India',
				'Indonesia'    => 'Indonesia',
				'Iran'    => 'Iran',
				'Iraq'    => 'Iraq',
				'Ireland'    => 'Ireland',
				'Israel'    => 'Israel',
				'Italy'    => 'Italy',
				'Jamaica'    => 'Jamaica',
				'Japan'    => 'Japan',
				'Jordan'    => 'Jordan',
				'Kazakhstan'    => 'Kazakhstan',
				'Kenya'    => 'Kenya',
				'Kiribati'    => 'Kiribati',
				'Kuwait'    => 'Kuwait',
				'Kyrgyzstan'    => 'Kyrgyzstan',
				'Laos'    => 'Laos',
				'Latvia'    => 'Latvia',
				'Lebanon'    => 'Lebanon',
				'Lesotho'    => 'Lesotho',
				'Liberia'    => 'Liberia',
				'Libya'    => 'Libya',
				'Liechtenstein'    => 'Liechtenstein',
				'Lithuania'    => 'Lithuania',
				'Luxembourg'    => 'Luxembourg',
				'Madagascar'    => 'Madagascar',
				'Malawi'    => 'Malawi',
				'Malaysia'    => 'Malaysia',
				'Maldives'    => 'Maldives',
				'Mali'    => 'Mali',
				'Malta'    => 'Malta',
				'Marshall Islands'    => 'Marshall Islands',
				'Mauritania'    => 'Mauritania',
				'Mauritius'    => 'Mauritius',
				'Mexico'    => 'Mexico',
				'Micronesia'    => 'Micronesia',
				'Moldova'    => 'Moldova',
				'Monaco'    => 'Monaco',
				'Mongolia'    => 'Mongolia',
				'Montenegro'    => 'Montenegro',
				'Morocco'    => 'Morocco',
				'Mozambique'    => 'Mozambique',
				'Myanmar'    => 'Myanmar',
				'Namibia'    => 'Namibia',
				'Nauru'    => 'Nauru',
				'Nepal'    => 'Nepal',
				'Netherlands'    => 'Netherlands',
				'New Zealand'    => 'New Zealand',
				'Nicaragua'    => 'Nicaragua',
				'Niger'    => 'Niger',
				'Nigeria'    => 'Nigeria',
				'North Korea'    => 'North Korea',
				'North Macedonia'    => 'North Macedonia',
				'Norway'    => 'Norway',
				'Oman'    => 'Oman',
				'Pakistan'    => 'Pakistan',
				'Palau'    => 'Palau',
				'Palestine State'    => 'Palestine State',
				'Panama'    => 'Panama',
				'Papua New Guinea'    => 'Papua New Guinea',
				'Paraguay'    => 'Paraguay',
				'Peru'    => 'Peru',
				'Philippines'    => 'Philippines',
				'Poland'    => 'Poland',
				'Portugal'    => 'Portugal',
				'Qatar'    => 'Qatar',
				'Romania'    => 'Romania',
				'Russia'    => 'Russia',
				'Rwanda'    => 'Rwanda',
				'Saint Kitts and Nevis'    => 'Saint Kitts and Nevis',
				'Saint Lucia'    => 'Saint Lucia',
				'Saint Vincent and the Grenadines'    => 'Saint Vincent and the Grenadines',
				'Samoa'    => 'Samoa',
				'San Marino'    => 'San Marino',
				'Sao Tome and Principe'    => 'Sao Tome and Principe',
				'Saudi Arabia'    => 'Saudi Arabia',
				'Senegal'    => 'Senegal',
				'Serbia'    => 'Serbia',
				'Seychelles'    => 'Seychelles',
				'Sierra Leone'    => 'Sierra Leone',
				'Singapore'    => 'Singapore',
				'Slovakia'    => 'Slovakia',
				'Slovenia'    => 'Slovenia',
				'Solomon Islands'    => 'Solomon Islands',
				'Somalia'    => 'Somalia',
				'South Africa'    => 'South Africa',
				'South Korea'    => 'South Korea',
				'South Sudan'    => 'South Sudan',
				'Spain'    => 'Spain',
				'Sri Lanka'    => 'Sri Lanka',
				'Sudan'    => 'Sudan',
				'Suriname'    => 'Suriname',
				'Sweden'    => 'Sweden',
				'Switzerland'    => 'Switzerland',
				'Syria'    => 'Syria',
				'Tajikistan'    => 'Tajikistan',
				'Tanzania'    => 'Tanzania',
				'Thailand'    => 'Thailand',
				'Timor-Leste'    => 'Timor-Leste',
				'Togo'    => 'Togo',
				'Tonga'    => 'Tonga',
				'Trinidad and Tobago'    => 'Trinidad and Tobago',
				'Tunisia'    => 'Tunisia',
				'Turkey'    => 'Turkey',
				'Turkmenistan'    => 'Turkmenistan',
				'Tuvalu'    => 'Tuvalu',
				'Uganda'    => 'Uganda',
				'Ukraine'    => 'Ukraine',
				'United Arab Emirates'    => 'United Arab Emirates',
				'United Kingdom'    => 'United Kingdom',
				'Uruguay'    => 'Uruguay',
				'Uzbekistan'    => 'Uzbekistan',
				'Vanuatu'    => 'Vanuatu',
				'Venezuela'    => 'Venezuela',
				'Vietnam'    => 'Vietnam',
				'Yemen'    => 'Yemen',
				'Zambia'    => 'Zambia',
				'Zimbabwe'    => 'Zimbabwe');
	}
    
    public function getDateCreated($varValue, DataContainer $dc) {
       $todaysDate = date("Y/m/d");
        return $todaysDate;
    }
    
    public function getDateApproved($varValue, DataContainer $dc) {
        $todaysDate = date("Y/m/d");
        return $todaysDate;
    }
    
}
