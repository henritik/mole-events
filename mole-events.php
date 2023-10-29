<?php
/*
Plugin Name: Mole Events
Description: A simple and straightforward events plugin with customizable widgets and shortcodes.
Version: 0.1.0
Author: Henri Tikkanen
Text Domain: mole-events
Domain Path: /languages
License: GPLv2
*/

defined( 'ABSPATH' ) or die();

require_once('includes/mole-events-post-type.php');
require_once('includes/mole-events-shortcodes.php');
require_once('includes/mole-events-widget.php');
require_once('includes/mole-events-admin.php');

function me_plugin_init() {
	wp_register_script(
		'me-custom-postmeta',
		plugin_dir_url( __FILE__ ) . 'js/index.js', 
		array(
			'wp-edit-post'
		)
	);
	load_plugin_textdomain( 'mole-events', false, false . '/languages' ); 
}
add_action( 'init', 'me_plugin_init' );

function sidebar_plugin_script_enqueue() {
	wp_enqueue_script( 'me-custom-postmeta' );
}
add_action( 'enqueue_block_editor_assets', 'sidebar_plugin_script_enqueue' );

function me_assets() {
	wp_enqueue_style( 'mole-events-css', plugin_dir_url(__FILE__) . 'css/mole-events.css');
}
add_action('wp_enqueue_scripts', 'me_assets');

?>