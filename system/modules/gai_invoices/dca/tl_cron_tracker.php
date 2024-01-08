<?php

/**
 * Bright Cloud Studio's GAI Invoices
 *
 * Copyright (C) 2023-2024 Bright Cloud Studio
 *
 * @package    bright-cloud-studio/gai-invoices
 * @link       https://www.brightcloudstudio.com/
 * @license    http://opensource.org/licenses/lgpl-3.0.html
**/

 
/* Table tl_price_chart */
$GLOBALS['TL_DCA']['tl_cron_tracker'] = array
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
            // Mode 2 - Records are sotrted by a switchable field
            // Flag 12 - Sort descending
            'mode'                    => 2,
            'flag'                    => 11,
            'fields'                  => array('task_name', 'last_run_date'),
            'panelLayout'             => 'sort,filter;search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('task_name', 'last_run_date'),
            'format'                  => '<span style="font-weight: bold;">Task:</span> %s <span style="font-weight: bold;">Last Run Date:</span> %s'
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
                'label'               => &$GLOBALS['TL_LANG']['tl_cron_tracker']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
			
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_cron_tracker']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_cron_tracker']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array
      			(
                'label'               => &$GLOBALS['TL_LANG']['tl_cron_tracker']['toggle'],
                'icon'                => 'visible.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback'     => array('Bcs\Backend\TransactionsBackend', 'toggleIcon')
      			),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_cron_tracker']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),
 
    // Palettes
    'palettes' => array
    (
        'default'                     => '{cron_tracker_legend},task_name,last_run_date;'
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
        'task_name' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_cron_tracker']['task_name'],
            'inputType'               => 'text',
            'default'                 => '',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        )
        'last_run_date' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_cron_tracker']['last_run_date'],
            'inputType'               => 'text',
            'default'                 => '',
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        )
    )
);
