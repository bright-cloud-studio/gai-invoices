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

 
/* Table tl_price_chart */
$GLOBALS['TL_DCA']['tl_transactions'] = array
(
 
    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' 	=> 	'primary',
                'alias' =>  'index'
            )
        )
    ),
 
    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 0,
            'panelLayout'             => 'filter;search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('width'),
            'format'                  => '<span style="font-weight: bold;">Width:</span> %s'
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_price_chart_large']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
			
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_price_chart_large']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_price_chart_large']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_price_chart_large']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('Bcs\Backend\PriceChartLargeBackend', 'toggleIcon')
			),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_price_chart_large']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),
 
    // Palettes
    'palettes' => array
    (
        'default'                     => '{size_legend},width;{products_legend},1_1,1_2,1_3,1_4;{publish_legend},published;'
    ),
 
    // Fields
    'fields' => array
    (
        'id' => array
        (
		'sql'                     	=> "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
		'sql'                     	=> "int(10) unsigned NOT NULL default '0'"
        ),
	'sorting' => array
	(
		'sql'                    	=> "int(10) unsigned NOT NULL default '0'"
	),
	'alias' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_price_chart_large']['alias'],
		'exclude'                 => true,
		'inputType'               => 'text',
		'search'                  => true,
		'eval'                    => array('unique'=>true, 'rgxp'=>'alias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
		'save_callback' => array
		(
			array('Bcs\Backend\PriceChartLargeBackend', 'generateAlias')
		),
		'sql'                     => "varchar(128) COLLATE utf8_bin NOT NULL default ''"

	),
	'width' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_price_chart_large']['width'],
		'inputType'               => 'text',
		'default'		  => '',
		'search'                  => true,
		'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
	'published' => array
	(
		'exclude'                 => true,
		'label'                   => &$GLOBALS['TL_LANG']['tl_price_chart_large']['published'],
		'inputType'               => 'checkbox',
		'eval'                    => array('submitOnChange'=>true, 'doNotCopy'=>true),
		'sql'                     => "char(1) NOT NULL default ''"
	)		
    )
);
