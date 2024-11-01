<?php
/**
 * Plugin Name: WooMulti
 * Plugin URI: https://www.genbuz.com/
 * Description: WooMulti is a wordrpess plugin that allows you to process orders for multiple woocommerce stores accross multiple domains from a single plugin.
 * Version: 1.7
 * Author: GenBuz
 * Author URI: https://www.genbuz.com
 * License: GPLv2 or later
 * Text Domain: woomulti
 * Domain Path: /languages
 */

 // are we supposed to be here?
if( ! defined( 'ABSPATH' ) )
{
	die;
}



/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function gbwm_load_textdomain()
{
    load_plugin_textdomain( 'woomulti', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'init', 'gbwm_load_textdomain' );




/**
 * DEFINE OUR CONSTANTS
 */

// plugin name
if ( !defined( 'GBWM_PLUGIN_NAME' ) ){
    define( 'GBWM_PLUGIN_NAME', plugin_basename( __FILE__ ) );// no trailing slash on plugin name
}

// plugin path
if ( !defined( 'GBWM_PLUGIN_PATH' ) ){
    define( 'GBWM_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

// plugin url
if ( !defined( 'GBWM_PLUGIN_URL' ) ){
    define( 'GBWM_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
}

// get the uploads folder
$upload_dir = wp_upload_dir();
$uploadPath = $upload_dir['basedir'];// path
$uploadUrl = $upload_dir['baseurl'];// url
$downloadsPath = trailingslashit( $uploadPath.'/woomulti_downloads' );
$downloadsURL = trailingslashit( $uploadUrl.'/woomulti_downloads' );

// downloads path
if ( !defined( 'GBWM_UPLOADS_PATH' ) ){
    define( 'GBWM_UPLOADS_PATH', $downloadsPath );

    // if the downloads folder does not exist, create it
    if( !file_exists( $downloadsPath ) )wp_mkdir_p( $downloadsPath );
}

// downloads url
if ( !defined( 'GBWM_UPLOADS_URL' ) ){
    define( 'GBWM_UPLOADS_URL', $downloadsURL );
}

// templates path and url
if ( !defined( 'GBWM_TEMPLATES_PATH' ) ){
    define( 'GBWM_TEMPLATES_PATH', GBWM_PLUGIN_PATH . 'templates/' );
}

if ( !defined( 'GBWM_TEMPLATES_URL' ) ){
    define( 'GBWM_TEMPLATES_URL', GBWM_PLUGIN_URL . 'templates/' );
}

// version
if ( !defined( 'GBWM_VERSION' ) ){
    define( 'GBWM_VERSION', '1.7' );
}


// start composer autoload
if( file_exists( GBWM_PLUGIN_PATH . 'vendor/autoload.php' ) )
{
	require_once GBWM_PLUGIN_PATH . 'vendor/autoload.php';
}

/**
 * register_activation_hook and register_deactivation_hook
 * need to be called from the main plugin file
 */

// activation
use GBWM_Inc\Base\GBWM_Activate;

// deactivation
use GBWM_Inc\Base\GBWM_Deactivate;


// on activation (NO OUTPUT ALLOWED)
register_activation_hook( __FILE__, 'GBWM_activate' );

// on deactivation (NO OUTPUT ALLOWED)
register_deactivation_hook( __FILE__, 'GBWM_deactivate' );


function GBWM_activate()
{
	// setup plugin
    GBWM_Activate::gbwm_activate();

    // flush rules
    flush_rewrite_rules();
}

// run on plugin deactivation
function GBWM_deactivate()
{
	GBWM_Deactivate::gbwm_deactivate();

    // flush rules
    flush_rewrite_rules();

}

// check versions match, if not run activation to update
add_action( 'plugins_loaded', 'gbwm_check_version' );

// Checks the version number
function gbwm_check_version() {

    // compare to version in database
	if ( GBWM_VERSION !== get_option( 'gbwm_version' ) ){

        // setup plugin
        GBWM_Activate::gbwm_activate();

        // flush rules
        //flush_rewrite_rules();

        add_action( 'admin_notices', 'gbwm_settings_notice' );

    }
}

// check if settings exist

function gbwm_settings_notice(){

    // the notice if settings have been added
    echo '<div class="notice notice-info is-dismissible">

        <p>New settings have been added to WooMulti, please goto <a href="admin.php?page=gbwm_settings">WooMulti Settings</a> and save the settings for them to take effect.</p>

    </div>';
}




// lets start the ball rolling
if ( class_exists( 'GBWM_Inc\\GBWM_Init' ) ) {
	GBWM_Inc\GBWM_Init::gbwm_register_services();
}