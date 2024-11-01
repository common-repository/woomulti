<?php

 /**
 * @package GenBuz WooMulti
 */

// has to be the very first php on the page
namespace GBWM_Inc\Base;

class GBWM_DownloadsFunctions
{
    /**
     * download orders
     */

    public static function gbwm_download_orders( $siteID ){

        if ( isset( $_REQUEST['status'] ) )
        {
            $status = $_REQUEST['status'];
        }

        if ( isset( $_REQUEST['downloadtype'] ) )
        {
            $type = $_REQUEST['downloadtype'];
        }

        if ( isset( $_REQUEST['idsArr'] ) )
        {
            $idsArr = $_REQUEST['idsArr'];
        }

        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_templates';

        // check if there is a site template in the database
        $sql = 'SELECT * FROM '. $table_name .' WHERE siteID = '. $siteID;

        // now do the database call
        $Template = $wpdb->get_row( $sql );

        // if there is an error
        if ($wpdb->last_error) {

            $errors = $wpdb->last_error;

            $return = array(
                'debug'  => $siteID.' - '.$template_type,
                'message'           => $errors,
                'ID'                => 0,
            );

            wp_send_json($return);
            die();

        }else{// if there was no error

            // if there was a saved template for this site
            if( ! empty( $Template ) ){

                $templateData = json_decode( $Template->templateData );

            }else{// no saved template so use default

                // no saved template for this site so load the default
                $sql = "SELECT * FROM ". $table_name ." WHERE siteID = 0 AND active = 1 ORDER BY 'id' ASC LIMIT 1";

                // now do the database call
                $Template = $wpdb->get_row( $sql );

                $templateData = json_decode( $Template->templateData );

            }// end else no saved

                // if this is selected orders
                if( $type == 'selected' ){

                    // function to call
                    \GBWM_Templates\word\classic_word\GBWM_Create_Selected::gbwm_create_selected( $siteID, $idsArr, $templateData );

                }

                // if this is all orders of a status
                if( $type == 'status' ){

                    // function to call
                    \GBWM_Templates\word\classic_word\GBWM_Create_Status::gbwm_create_status( $siteID, $status, $templateData );

                }

        }// end else no error

    } //end gbwm_download_orders function





    /**
     * delete selected file(s)
     */

    public static function gbwm_delete_selected_files(){

        // get the filename(s)
        if ( isset( $_REQUEST['idsArr'] ) )
        {
            $idsArr = $_REQUEST['idsArr'];
        }

        // now remove the file(s)
        foreach($idsArr as $filename){
            unlink( GBWM_UPLOADS_PATH.$filename );
        }

        $return = array(
            'debug'  => $filename.' - '.GBWM_UPLOADS_PATH,
            'message'           => 'Success',
            'ID'                => 1,
        );

        wp_send_json($return);
        die();


    } //end delete selected file(s) function





    /**
     * delete file
     */

    public static function gbwm_delete_file(){

        // get the filename
        if ( isset( $_REQUEST['filename'] ) )
        {
            $filename = $_REQUEST['filename'];
        }

        // now remove the file
        unlink( GBWM_UPLOADS_PATH.$filename );

        $return = array(
            'debug'  => $filename.' - '.GBWM_UPLOADS_PATH,
            'message'           => 'Success',
            'ID'                => 1,
        );

        wp_send_json($return);
        die();


    } //end delete file function




}// end class