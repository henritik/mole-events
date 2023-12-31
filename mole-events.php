<?php
/*
Plugin Name: Mole Events
Description: A simple and straightforward events plugin with customizable widgets and shortcodes.
Version: 0.2.0
Author: Henri Tikkanen
Author URI: https://github.com/henritik/
Requires PHP: 7.4
Tested up to: WordPress 6.3.1
Text Domain: mole-events
License: License: GPLv2
*/

defined( 'ABSPATH' ) or die();

require_once('includes/mole-events-post-type.php');
require_once('includes/mole-events-shortcodes.php');
require_once('includes/mole-events-widget.php');
require_once('includes/mole-events-admin.php');

function me_plugin_init() {
    wp_register_script(
        'me-custom-postmeta',
        plugins_url( 'js/index.js', __FILE__ ),
        array( 'wp-edit-post' )
    );
	wp_register_style(
        'mole-events-css',
        plugins_url( 'css/mole-events.css', __FILE__ )
    );
}
add_action( 'init', 'me_plugin_init' );

function me_script_enqueue() {
	wp_enqueue_script( 'me-custom-postmeta' );
}
add_action( 'enqueue_block_editor_assets', 'me_script_enqueue' );

function me_assets() {
	wp_enqueue_style( 'mole-events-css' );
}
add_action('wp_enqueue_scripts', 'me_assets');

?>
