<?php

 /**
 * @package GenBuz WooMulti
 */

// has to be the very first php on the page
namespace GBWM_Inc\Base;

class GBWM_TemplatesFunctions
{
    /**
     * install template
     */

    public static function gbwm_install_template( $template_type, $templateID ){

        // first lets load the templateData
        $templates_path = GBWM_TEMPLATES_PATH;
        $template_type_path = $template_type.'/';

        // get the templateDate based on $template_type, $templateID
        require_once( $templates_path.$template_type_path.$templateID.'/gbwm-template.php' );

        // get the tempate data as an object.
        $template_data = json_decode( json_encode( $templateData ) );

        // load wordpress database object and make it global
        global $wpdb;

        // convert to json
        $json_templateData = json_encode( $templateData );

        // now lets add this template to the database

        $table_name = $wpdb->prefix.'gbwm_templates';

        //%s as string; %d as integer (whole number) and %f as float

        $values = array(
            'siteID' => 0,
            'templateID' => $templateID,
            'template_name' => $template_data->template_name,
            'template_type' => $template_type,
            'templateData' => $json_templateData,
            'active' => 1
        );

            // %d = int, %f = float, %s = string, eg array('%d', '%f', '%s')
            $format = array('%d','%s','%s','%s','%s','%d');
            // table name, data to insert, format
            $wpdb->insert($table_name, $values, $format);

        // if there is an error
        if ($wpdb->last_error) {

            $errors = $wpdb->last_error;

            $return = array(
                'debug'  => $templateID.' - '.$template_data->template_name.' - '.$template_type.' - '.$json_templateData,
                'message'           => $errors,
                'ID'                => 0,
            );

            wp_send_json($return);
            die();

        }else{// if there was no error

            $return = array(
                'debug'  => $templateID.' - '.$template_data->template_name.' - '.$template_type.' - '.$json_templateData,
                'message'  => __('Success', 'woomulti'),
                'ID'       => 1
            );

            wp_send_json($return);
            die();

        }

    } //end install template function





    /**
     * activate template
     */

    public static function gbwm_activate_template( $template_type, $templateID ){

        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_templates';

        // now lets activate this template
        $wpdb->query( $wpdb->prepare("UPDATE $table_name
                SET active = %d
                WHERE templateID = %s
                AND siteID = %d", 1, $templateID, 0)
        );

        // if there is an error
        if ($wpdb->last_error) {

            $errors = $wpdb->last_error;

            $return = array(
                'debug'  => $templateID.' - '.$template_type,
                'message'           => $errors,
                'ID'                => 0,
            );

            wp_send_json($return);
            die();

        }else{// if there was no error

            $return = array(
                'debug'  => $templateID.' - '.$template_type,
                'message'  => __('Success', 'woomulti'),
                'ID'       => 1
            );

            wp_send_json($return);
            die();

        }

    } //end activate template function





    /**
     * deactivate template
     */

    public static function gbwm_deactivate_template( $template_type, $templateID ){

        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_templates';

        // now lets deactivate this template
        $wpdb->query( $wpdb->prepare("UPDATE $table_name
                SET active = %d
                WHERE templateID = %s
                AND siteID = %d", 0, $templateID, 0)
        );

        // if there is an error
        if ($wpdb->last_error) {

            $errors = $wpdb->last_error;

            $return = array(
                'debug'  => $templateID.' - '.$template_type,
                'message'           => $errors,
                'ID'                => 0,
            );

            wp_send_json($return);
            die();

        }else{// if there was no error

            $return = array(
                'debug'  => $templateID.' - '.$template_type,
                'message'  => __('Success', 'woomulti'),
                'ID'       => 1
            );

            wp_send_json($return);
            die();

        }

    } //end deactivate template function




    /**
     * load site template
     */

    public static function gbwm_site_template($siteID){

        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_templates';

        // check if there is a site template in the database
        $sql = 'SELECT * FROM '. $table_name .' WHERE siteID = '. $siteID;

        // now do the database call
        $SiteTemplate = $wpdb->get_row( $sql );

        // if there is an error
        if ($wpdb->last_error) {

            $error = $wpdb->last_error;

            // show the error
            echo '<div style="font-size: 24px; text-align: center; padding: 40px;">Sorry there was an error, the error was<br><br>

            '. $error .'

            .</div>';

        }else{// if there was no error

            // if there is a template in the database
            if( ! empty( $SiteTemplate ) ){

                $jsonData = json_decode( $SiteTemplate->templateData );

                $templates_path = GBWM_TEMPLATES_PATH;

                $template_type_path = $SiteTemplate->template_type.'/';

                $templateID = $SiteTemplate->templateID;

                $saveType = 'Update';

                // get the templateDate based on $template_type, $templateID
                require_once( $templates_path.$template_type_path.$templateID.'/gbwm-template.php' );

                echo $templateHTML;


            }else{// no template in database so do default

                // no saved template for this site so load the default
                $sql = "SELECT * FROM ". $table_name ." WHERE siteID = 0 AND active = 1 ORDER BY 'id' ASC LIMIT 1";

                // now do the database call
                $DefaultTemplate = $wpdb->get_row( $sql );

                // if there are templates
                if( ! empty( $DefaultTemplate ) ){

                    $jsonData = json_decode( $DefaultTemplate->templateData );

                    $templates_path = GBWM_TEMPLATES_PATH;

                    $template_type_path = $DefaultTemplate->template_type.'/';

                    $templateID = $DefaultTemplate->templateID;

                    $saveType = 'Insert';

                    // get the templateDate based on $template_type, $templateID
                    require_once( $templates_path.$template_type_path.$templateID.'/gbwm-template.php' );

                    echo $templateHTML;

                }else{// no active default template in database

                    // tell them to activate some templates first
                    echo '<div style="font-size: 24px; text-align: center; padding: 20px;">There are no active templates, please activate at least one template to begin.</div>';

                }

            }// end default


        }// end no errors

    } //end gbwm_site_template function





    /**
     * save site template template
     */

    public static function gbwm_change_template($siteID){

        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_templates';

        // check if there is a site template in the database
        $sql = "SELECT * FROM ". $table_name ." WHERE templateID = '".$_REQUEST["templateID"]."' ORDER BY 'id' ASC LIMIT 1";// siteID 0 = default template

        // now do the database call
        $DefaultTemplate = $wpdb->get_row( $sql );

        // if there is an error
        if ($wpdb->last_error) {

            $error = $wpdb->last_error;

            // show the error
            echo '<div style="font-size: 24px; text-align: center; padding: 40px;">Sorry there was an error, the error was<br><br>

            '. $error .'

            .</div>';

        }else{// if there was no error

            $jsonData = json_decode( $DefaultTemplate->templateData );

            $templates_path = GBWM_TEMPLATES_PATH;

            $template_type_path = $DefaultTemplate->template_type.'/';

            $templateID = $DefaultTemplate->templateID;

            $saveType = 'Update';

            // get the templateDate based on $template_type, $templateID
            require_once( $templates_path.$template_type_path.$templateID.'/gbwm-template.php' );

            echo $templateHTML;

        }// end else






    } //end deactivate template function




    /**
     * save site template template
     */

    public static function gbwm_save_site_template(){

        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_templates';

        $saveType = $_REQUEST['saveType'];

        $templateData = json_decode( json_encode( $_REQUEST['templateData'] ) );

        // convert to json
        $json_templateData = json_encode( $_REQUEST['templateData'] );

        if( $saveType == 'Insert' ){

            // insert a new site template

            // insert a site template
            $values = array(
                'siteID' => $_REQUEST['siteID'],
                'templateID' => $templateData->templateID,
                'template_name' => $templateData->template_name,
                'template_type' => $templateData->template_type,
                'templateData' => $json_templateData,
                'active' => 1
            );

            // %d = int, %f = float, %s = string, eg array('%d', '%f', '%s')
            $format = array('%d','%s','%s','%s','%s','%d');
            // table name, data to insert, format
            $wpdb->insert($table_name, $values, $format);

        }else if( $saveType == 'Update' ){

            $wpdb->update(
                $table_name,
                array(
                    'templateID' => $templateData->templateID,
                    'template_name' => $templateData->template_name,
                    'template_type' => $templateData->template_type,
                    'templateData' => $json_templateData,
                    'active' => 1
                ),
                array(
                    'siteID' => (int)$_REQUEST['siteID']
                )
            );

        }

        // if there is an error
        if ($wpdb->last_error) {

            $errors = $wpdb->last_error;

            $return = array(
                'debug'     => $_REQUEST['siteID'].' - '.$templateData->templateID,
                'message'   => $errors,
                'ID'        => 0,
            );

            wp_send_json($return);
            die();

        }else{// if there was no error

            $return = array(
                'debug'     => $_REQUEST['siteID'].' - '.$templateData->templateID,
                'message'   => __('Success', 'woomulti'),
                'ID'        => 1
            );

            wp_send_json($return);
            die();

        }// end else

    }// end gbwm_save_site_template




}// end class