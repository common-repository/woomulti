<?php

 /**
 * @package GenBuz WooMulti
 */


// has to be the very first php on the page
namespace GBWM_Inc\Base;

class GBWM_CouriersFunctions
{
    /**
     * add courier function
     */
    public static function gbwm_add_courier($title, $url, $active)
    {
        // load wordpress database object and make it global
        global $wpdb;
        
        $table_name = $wpdb->prefix.'gbwm_couriers';
        
        //%s as string; %d as integer (whole number) and %f as float
        
            $values = array(
                'title' => $title,
                'url' => $url,
                'active' => (int)$active
            );
        
            // %d = int, %f = float, %s = string, eg array('%d', '%f', '%s')
            $format = array('%s','%s','%d');
            // table name, data to insert, format
            $wpdb->insert($table_name, $values, $format);
        
        // if there is an error
        if ($wpdb->last_error) {
            
            $errors = $wpdb->last_error;
        
            $return = array(
                'debug'  => $title.' - '.$url.' - '.$active,
                'message'           => $errors,
                'siteID'            => $siteID,
                'ActiveStatus'      => $ActiveStatus,
                'ID'                => 0,
            );
        
            wp_send_json($return);
            die();
        
        }else{// if there was no error
        
            $return = array(
                'debug'  => $title.' - '.$url.' - '.$active,
                'message'  => __( 'Success', 'woomulti' ),
                'ID'       => 1
            );
        
            wp_send_json($return);
            die();
        
        }

    } //add courier function




    /**
     * change courier status function
     */
    public static function gbwm_change_courier_status($siteID, $ActiveStatus)
    {
        // load wordpress database object and make it global
        global $wpdb;
        
        $table_name = $wpdb->prefix.'gbwm_couriers';
        
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
                'message'  => __( 'Success', 'woomulti' ),
                'ID'       => 1
            );
        
            wp_send_json($return);
            die();
        
        }

    } //change courier status function




    /**
     * edit courier function
     */
    public static function gbwm_edit_courier($siteID, $title, $url, $active)
    {
        // load wordpress database object and make it global
        global $wpdb;
        
        $table_name = $wpdb->prefix.'gbwm_couriers';
        
        $wpdb->update(
            $table_name,
            array(
                'title'     => $title,
                'url'       => $url,
                'active'    => (int)$active
            ),
            array(
                "id" => (int)$siteID
            )
        );
        
        // if there is an error
        if ($wpdb->last_error) {
            
            $errors = $wpdb->last_error;
        
            $return = array(
                'debug'     => $title,
                'message'   => $errors,
                'ID'        => 0
            );
        
            wp_send_json($return);
            die();
        
        }else{// if there was no error
        
            $return = array(
                'debug'     => $title,
                'message'  => __( 'Success', 'woomulti' ),
                'ID'       => 1
            );
        
            wp_send_json($return);
            die();
        
        }

    } //end edit courier




    /**
     * delete courier
     */
    public static function gbwm_delete_courier($siteID)
    {
        // load wordpress database object and make it global
        global $wpdb;
            
        $table_name = $wpdb->prefix.'gbwm_couriers';

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
                'message'  => __( 'Success', 'woomulti' ),
                'ID'       => 1
            );

            wp_send_json($return);
            die();

        }

    } //end delete courier function

}// end class