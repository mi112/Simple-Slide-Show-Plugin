<?php
/**
 * Uninstall My Sldie Show Plugin
 *
 * @package my-slide-show
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$settings_options = array(
	'slide_id' => array( 'default' => '' ),
);

foreach ( $settings_options as $opt => $val ) {
	delete_option( $opt );
}


