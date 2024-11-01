<?php
/**
 * @package GenBuz WooMulti
 */

// has to be the very first php on the page
namespace GBWM_Inc\Base;

class GBWM_Cron {

    public function gbwm_register()
    {
        // the hook
        add_action ( 'woomulti_create_files_hook', array( $this, 'gbwm_create_files_action' ), 10, 4 );
    }

    public static function gbwm_create_files_action( $siteID, $status, $type, $idsArr )
    {
        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_templates';

        // check if there is a site template in the database
        $sql = 'SELECT * FROM '. $table_name .' WHERE siteID = '. $siteID;

        // now do the database call
        $Template = $wpdb->get_row( $sql );

        // if there was a saved template for this site
        if( ! empty( $Template ) ){

            $templateData = json_decode( $Template->templateData );

        }else{// no saved template so use default

            // no saved template for this site so load the first default
            $sql = "SELECT * FROM ". $table_name ." WHERE siteID = 0 AND active = 1 ORDER BY 'id' ASC LIMIT 1";

            // now do the database call
            $Template = $wpdb->get_row( $sql );

            $templateData = json_decode( $Template->templateData );

        }// end else no saved

        //$GBWM_Create_Selected = '\\GBWM_Templates\\'.$Template->template_type.'\\'.$Template->templateID.'\\GBWM_Create_Test';

        $GBWM_Create_Selected = '\\GBWM_Templates\\'.$Template->template_type.'\\'.$Template->templateID.'\\GBWM_Create_Selected';

        $GBWM_Create_Status = '\\GBWM_Templates\\'.$Template->template_type.'\\'.$Template->templateID.'\\GBWM_Create_Status';

        // if this is selected orders
        if( $type == 'selected' ){

            // function to call
            $GBWM_Create_Selected::gbwm_create_selected( $siteID, $idsArr, $templateData );

        }

        // if this is all orders of a status
        if( $type == 'status' ){

            // function to call
            $GBWM_Create_Status::gbwm_create_status( $siteID, $status, $templateData );

        }

    } //end gbwm_download_orders function



    // this is called directly via ajax
    public static function gbwm_create_files_cron( $siteID, $status = null, $type, $idsArr = null )
    {

        // the arguments.
        $args = array( $siteID, $status, $type, $idsArr );

        // Get the timestamp for the next event.
        $timestamp = wp_next_scheduled( 'woomulti_create_files_hook', $args );

        // we dont want the same cron twice so remove old event if any
        wp_unschedule_event( $timestamp, 'woomulti_create_files_hook', $args );

        // create the cron
        wp_schedule_single_event( time() - 3600, 'woomulti_create_files_hook', $args );

    }


}// end class