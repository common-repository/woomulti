<?php

 /**
 * @package GenBuz WooMulti
 */

// has to be the very first php on the page
namespace GBWM_Inc\Base;

class GBWM_SitesFunctions
{
    /**
     * Add new site
     */

    public static function gbwm_add_site($url, $SiteCK, $SiteCS, $WooSite=0, $active=1){

        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_sites';

        // do some work on the $url
        $arrURL = parse_url( $url );

        //%s as string; %d as integer (whole number) and %f as float

        $values = array(
            'url' => $url,
            'Consumer_key' => $SiteCK,
            'Consumer_secret' => $SiteCS,
            'woosite' => (int)$WooSite,
            'active' => (int)$active
        );

            // %d = int, %f = float, %s = string, eg array('%d', '%f', '%s')
            $format = array('%s','%s','%s','%d');
            // table name, data to insert, format
            $wpdb->insert($table_name, $values, $format);

        // if there is an error
        if ($wpdb->last_error) {

            $errors = $wpdb->last_error;

            $return = array(
                'debug'  => $url.' - '.$SiteCK.' - '.$SiteCS.' - '.$WooSite.' - '.$active,
                'message'           => $errors,
                'ID'                => 0,
            );

            wp_send_json($return);
            die();

        }else{// if there was no error

            $return = array(
                'debug'  => $url.' - '.$SiteCK.' - '.$SiteCS.' - '.$WooSite.' - '.$active,
                'message'  => __('Success', 'woomulti'),
                'ID'       => 1
            );

            wp_send_json($return);
            die();

        }

    } //end add site function




    /**
     * change woosite status function
     */

    public static function gbwm_change_woosite_status($siteID, $WooSiteStatus){

        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_sites';

        //%s as string; %d as integer (whole number) and %f as float

        if( (int)$WooSiteStatus === 0 ){
            $WooSiteStatus = 1;
        }else{
            $WooSiteStatus = 0;
        }

        $wpdb->update(
            $table_name,
            array(
                'woosite' => (int)$WooSiteStatus
            ),
            array(
                "id" => (int)$siteID
            )
        );

        // if there is an error
        if ($wpdb->last_error) {

            $errors = $wpdb->last_error;

            $return = array(
                'message'           => $errors,
                'siteID'            => $siteID,
                'WooSiteStatus'     => $WooSiteStatus,
                'ID'                => 0,
            );

            wp_send_json($return);
            die();

        }else{// if there was no error

            $return = array(
                'message'  => __('Success', 'woomulti'),
                'ID'       => 1
            );

            wp_send_json($return);
            die();

        }

    } //end change woosite status




    /**
     * change site status function
     */

    public static function gbwm_change_site_status($siteID, $ActiveStatus){

        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_sites';

        //%s as string; %d as integer (whole number) and %f as float

        if( (int)$ActiveStatus === 0 ){
            $ActiveStatus = 1;
        }else{
            $ActiveStatus = 0;
        }

        $wpdb->update(
            $table_name,
            array(
                'active' => (int)$ActiveStatus
            ),
            array(
                "id" => (int)$siteID
            )
        );

        // if there is an error
        if ($wpdb->last_error) {

            $errors = $wpdb->last_error;

            $return = array(
                'message'           => $errors,
                'siteID'            => $siteID,
                'ActiveStatus'      => $ActiveStatus,
                'ID'                => 0,
            );

            wp_send_json($return);
            die();

        }else{// if there was no error

            $return = array(
                'message'  => __('Success', 'woomulti'),
                'ID'       => 1
            );

            wp_send_json($return);
            die();

        }

    } //end change site status




    /**
     * edit site
     */

    public static function gbwm_edit_site($siteID, $url, $Consumer_key, $Consumer_secret, $WooSite, $active){

        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_sites';

        $siteID = $_REQUEST['siteID'];
        $url = $_REQUEST['url'];

        //%s as string; %d as integer (whole number) and %f as float

        $wpdb->update(
            $table_name,
            array(
                'url' => $url,
                'Consumer_key' => $Consumer_key,
                'Consumer_secret' => $Consumer_secret,
                'woosite' => (int)$WooSite,
                'active' => (int)$active,
            ),
            array(
                "id" => (int)$siteID
            )
        );

        // if there is an error
        if ($wpdb->last_error) {

            $errors = $wpdb->last_error;

            $return = array(
                'message'           => $errors,
                'siteID'            => $siteID,
                'url'               => $url,
                'Consumer_key'      => $Consumer_key,
                'Consumer_secret'   => $Consumer_secret,
                'WooSite'           => $WooSite,
                'active'            => $active,
                'ID'                => 0,
            );

            wp_send_json($return);
            die();

        }else{// if there was no error

            $return = array(
                'message'  => __('Success', 'woomulti'),
                'ID'       => 1
            );

            wp_send_json($return);
            die();

        }

    } //end gbwm_edit_site function




    /**
     * delete site
     */

    public static function gbwm_delete_site($siteID){

    // load wordpress database object and make it global
    global $wpdb;

    $table_name = $wpdb->prefix.'gbwm_sites';

    $deleteSite = $wpdb->delete( $table_name, array( 'id' => $siteID ) );

    // if there is an error
    if ($wpdb->last_error) {

        $errors = $wpdb->last_error;

        $return = array(
            'message'  => $errors,
            'ID'       => 0
        );

        wp_send_json($return);
        die();

    }else{// if there was no error

        $return = array(
            'message'  => __('Success', 'woomulti'),
            'ID'       => 1
        );

        wp_send_json($return);
        die();

    }

    } //end delete site function

}// end class