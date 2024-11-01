<?php

 /**
 * @package GenBuz WooMulti
 */

namespace GBWM_Inc\Base;

class GBWM_Activate
{

    public function gbwm_register()
    {
        // add our file retention cron if it does not exist
        if ( ! wp_next_scheduled( 'woomulti_files_retention_hook' ) ) {
            wp_schedule_event( time(), 'hourly', 'woomulti_files_retention_hook' );
        }

        // the hook and action for file retention cron
        add_action( 'woomulti_files_retention_hook', array( $this, 'gbwm_files_retention_action' ) );
    }




    // no output allowed in this function
    public static function gbwm_activate()
    {
        // create database tables
        global $wpdb;

        // do the install/upgrade through wordpress
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );


        /**
        * gbwm_sites table
        */

        $sites_table = 'gbwm_sites';
        $wp_gbwm_sites = $wpdb->prefix . $sites_table;

        $sql ='';

        // gbwm_sites table sql
        $sql .= 'CREATE TABLE '.$wp_gbwm_sites.' ( ';
        $sql .= '  `id` int(9) NOT NULL AUTO_INCREMENT,';
        $sql .= '  `url` varchar(150) NOT NULL,';
        $sql .= '  `Consumer_key` varchar(999) NOT NULL,';
        $sql .= '  `Consumer_secret` varchar(999) NOT NULL,';
        $sql .= '  `active` tinyint(1) NOT NULL DEFAULT "1" COMMENT "true=1, false=0",';
        $sql .= '  `woosite` tinyint(1) NOT NULL DEFAULT "0" COMMENT "true=1, false=0",';
        $sql .= '  `json` longtext NOT NULL,';
        $sql .= '  PRIMARY KEY  (`id`),';
        $sql .= '  UNIQUE KEY `id` (`id`),';
        $sql .= '  UNIQUE KEY `url` (`url`)';
        $sql .= ') ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;';

        // do the install/upgrade
        dbDelta( $sql );




        /**
        * gbwm_couriers table
        */

        $couriers_table = 'gbwm_couriers';
        $wp_gbwm_couriers = $wpdb->prefix . $couriers_table;

        // empty the $sql for next table
        $sql ='';

        // gbwm_couriers table sql
        $sql = 'CREATE TABLE '.$wp_gbwm_couriers.' ( ';
        $sql .= '  `id` int(9) NOT NULL AUTO_INCREMENT,';
        $sql .= '  `title` varchar(250) NOT NULL,';
        $sql .= '  `url` varchar(250) NOT NULL,';
        $sql .= '  `active` tinyint(1) NOT NULL DEFAULT "1" COMMENT "true=1, false=0",';
        $sql .= '  PRIMARY KEY  (`id`)';
        $sql .= '  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;';

        // do the install/upgrade
        dbDelta($sql);




        /**
        * gbwm_templates table
        */

        $templates_table = 'gbwm_templates';
        $wp_gbwm_templates = $wpdb->prefix . $templates_table;

        // empty the $sql for next table
        $sql ='';

        // gbwm_couriers table sql
        $sql = 'CREATE TABLE '.$wp_gbwm_templates.' ( ';
        $sql .= '  `id` int(10) NOT NULL AUTO_INCREMENT,';
        $sql .= '  `siteID` int(10) NOT NULL,';
        $sql .= '  `templateID` varchar(30) NOT NULL,';
        $sql .= '  `template_name` varchar(30) NOT NULL,';
        $sql .= '  `template_type` varchar(15) NOT NULL,';
        $sql .= '  `templateData` longtext NOT NULL,';
        $sql .= '  `active` tinyint(1) NOT NULL DEFAULT "1" COMMENT "true=1, false=0",';
        $sql .= '  PRIMARY KEY  (`id`)';
        $sql .= '  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;';

        // do the install/upgrade
        dbDelta($sql);




        /**
         * Default options
         *
         * these are the default settings for the settings page
         * they are loaded on plugin activation
         */

        // get current settings from database (if any)
        @ $orig_settings = get_option( 'gbwm_plugin_settings' );

        // if there are no settings saved in the database
        if( empty( $orig_settings ) ) {

            $arr = array(
                'gbwm_pagination'           => 30,// per page
                'gbwm_enable_tracking'      => 'Yes',
                'gbwm_default_courier'      => 0,
                // below added in ver 1.7
                'gbwm_enable_downloads'     => 'Yes',
                'gbwm_downloads_retention'  => 7,// 7 days
                'gbwm_default_courier'      => 0,// 0 = no tracking
            );

            // main settings
            update_option( 'gbwm_plugin_settings', $arr, 'no' );

        // if the version is less than or equal to 1.7 add the new settings
        }else if( GBWM_VERSION <= '1.7' ){

            // get the current setings values if any
            $pagination	= $orig_settings['gbwm_pagination'];
            $tracking	= $orig_settings['gbwm_enable_tracking'];
            $courier	= $orig_settings['gbwm_default_courier'];

            $arr = array(
                'gbwm_pagination'           => ( empty( $pagination ) ) ? 30 : $pagination,
                'gbwm_enable_tracking'      => ( empty( $tracking ) ) ? 'yes' : $tracking,
                'gbwm_default_courier'      => ( empty( $courier ) ) ? 0 : $courier,
                // below added in ver 1.7
                'gbwm_enable_downloads'     => 'Yes',
                'gbwm_downloads_retention'  => 7,// 7 days
                'gbwm_default_courier'      => 0,// 0 = no default courier
            );

            // create/update the setting
            update_option( 'gbwm_plugin_settings', $arr, 'no' );
        }

        // listings orders status
        // get current setting from database (if any)
        if( !empty( get_option( 'gbwm_orders_listings_status' ) ) && get_option( 'gbwm_orders_listings_status' ) == 'all' ){
                update_option( 'gbwm_orders_listings_status', 'any', 'no' );
        }




        /**
        * plugin/database versions option
        */

        // set the plugin version
        update_option( 'gbwm_version', GBWM_VERSION, 'no' );

        // delete old db versioning
        delete_option('gbwm_db_version');


    }// end gbwm_activate function




    /**
    * files retention cron hook action
    */
    public function gbwm_files_retention_action() {

        // get the plugin options
        $retention_value = get_option('gbwm_plugin_settings');

        // get retention option value
        $retention_value = $retention_value['gbwm_downloads_retention'];

        // remove the files for that retention period
        $fileSystemIterator = new \FilesystemIterator(GBWM_UPLOADS_PATH, \FilesystemIterator::SKIP_DOTS);

        $now = time();

        foreach ( $fileSystemIterator as $file ) {

            if( $file->isFile() ){

                // lets make sure we are getting this far
                //$txt = 'Filename: '.$file->getFilename().' - Created Time: '.date( "d/m/Y", $file->getCTime() ).' - Modified Time: '.date( "d/m/Y", $file->getMTime() );

                //$myfile = file_put_contents(GBWM_UPLOADS_PATH.'cron-retention.txt', $txt.PHP_EOL, FILE_APPEND | LOCK_EX);

                if ( $now - $file->getCTime() >= 60 * 60 * 24 * $retention_value ){ // x days

                    // now delete the file
                    unlink( GBWM_UPLOADS_PATH.$file->getFilename() );

                }// end if

            }// end is file
        }// end foreach
        die;
    }// end gbwm_files_retention_hook


}// end class GBWM_Activate