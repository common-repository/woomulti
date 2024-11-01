<?php
/**
 * @package GenBuz WooMulti
 */

/**
 * this is only called on uninstall
 */

 if(! defined('WP_UNINSTALL_PLUGIN') ){
    die;
 }

/**
 * lets remove the plugin options
 */

// main plugin settings
delete_option( 'gbwm_plugin_settings' );

// listings status setting
delete_option( 'gbwm_orders_listings_status' );

// version setting
delete_option( 'gbwm_version' );





/**
 * lets remove the retention cron
 */

// Get the timestamp for the next event.
$timestamp = wp_next_scheduled( 'woomulti_files_retention_hook' );

// we dont want the same cron twice so remove old event if any
wp_unschedule_event( $timestamp, 'woomulti_files_retention_hook' );



/**
 * lets remove the database
 */

global $wpdb;

// drop sites table
$wpdb->query( 'DROP TABLE IF EXISTS '.$wpdb->prefix.'gbwm_couriers' );

// drop couriers table
$wpdb->query( 'DROP TABLE IF EXISTS '.$wpdb->prefix.'gbwm_sites' );

// drop templates table
$wpdb->query( 'DROP TABLE IF EXISTS '.$wpdb->prefix.'gbwm_templates' );