<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

$settings_options=[   
    'slide_id'=>array('default'=>''),
  ];

foreach ($settings_options as $opt=>$val)
{
    delete_option(   $opt );
}

?>