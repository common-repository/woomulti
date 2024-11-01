<?php
/**
 * @package GenBuz WooMulti
 */

namespace GBWM_Inc\Base;

class GBWM_Ajax
{
    public function gbwm_register()
    {
        // ajax section
        add_action( 'wp_ajax_gbwm_ajax', array( $this, 'gbwm_ajax' ) );
    }


    // afax function callback
    public function gbwm_ajax()
    {

        /**
         * ajaxFunction list so far
         *
         * These are for the manage orders section
         * =======================================
         * GetOrders = get orders
         * OrderStatus = track and status
         * PreviewOrder = preview order / shipping address
         * UpdateAddress = edit shipping address
         *
         * These are for the manage sites section
         * ======================================
         * AddSite = add new site
         * WooSiteStatus = change the woosite status of a site
         * SiteStatus = change the active status of a site
         * EditSite = edit site
         * DeleteSite = delete site
         *
         * These are for the manage couries section
         * ========================================
         * CourierStatus = change Courier Status
         * AddCourier = add a new courier
         * EditCourier = edit courier
         *
         * These are for the manage templates section
         * ==========================================
         *
         * InstallTemplate = add a template to the database
         * ActivateTemplate = activate an installed template
         * DeactivateTemplate = deactivate an installed template
         * SiteTemplate get template for site
         * ChangeTemplate change the template
         * SaveSiteTemplate = save custom values site template
         *
         * These are for the manage downloads section
         * ==========================================
         *
         * DownloadOrders = download orders for a given type
         * DeleteSelectedFiles = delete selected file(s) from the downloads folder
         * DeleteFile = delete a file from the downloads folder
         * DownloadFile = download a created file
         *
         */

        // make sure this is ajax
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            // required common vars
            $ajaxFunction = $_REQUEST['ajaxFunction'];

            if ( isset( $_REQUEST['siteID'] ) )
            {
                $siteID = $_REQUEST['siteID'];
            }

            if(!empty($ajaxFunction)){

                // ajaxFunction GetOrders = get orders for selected site
                if($ajaxFunction == 'GetOrders'){

                    // function to call
                    GBWM_OrdersFunctions::gbwm_list_orders( $siteID );

                    // now die
                    die();

                }




                // ajaxFunction OrderStatus = track and status
                if($ajaxFunction == 'OrderStatus'){

                    // function to call
                    GBWM_OrdersFunctions::gbwm_order_status( $siteID );

                    // now die
                    die();

                }




                // ajaxFunction PreviewOrder = preview order / shipping address
                if($ajaxFunction == 'PreviewOrder'){

                    // required
                    if ( isset( $_REQUEST['orderID'] ) )
                    {
                        $orderID = $_REQUEST['orderID'];
                    }

                    // function to call
                    GBWM_OrdersFunctions::gbwm_order_preview($siteID, $orderID);

                    // now die
                    die();

                }




                // ajaxFunction UpdateAddress = edit shipping address
                if($ajaxFunction == 'UpdateAddress'){

                    // required
                    if ( isset( $_REQUEST['orderID'] ) )
                    {
                        $orderID = $_REQUEST['orderID'];
                    }

                    if ( isset( $_REQUEST['first_name'] ) )
                    {
                        $first_name = $_REQUEST['first_name'];
                    }

                    if ( isset( $_REQUEST['last_name'] ) )
                    {
                        $last_name = $_REQUEST['last_name'];
                    }

                    if ( isset( $_REQUEST['company'] ) )
                    {
                        $company = $_REQUEST['company'];
                    }

                    if ( isset( $_REQUEST['address_1'] ) )
                    {
                        $address_1 = $_REQUEST['address_1'];
                    }

                    if ( isset( $_REQUEST['address_2'] ) )
                    {
                        $address_2 = $_REQUEST['address_2'];
                    }

                    if ( isset( $_REQUEST['city'] ) )
                    {
                        $city = $_REQUEST['city'];
                    }

                    if ( isset( $_REQUEST['state'] ) )
                    {
                        $state = $_REQUEST['state'];
                    }

                    if ( isset( $_REQUEST['postcode'] ) )
                    {
                        $postcode = $_REQUEST['postcode'];
                    }

                    if ( isset( $_REQUEST['country'] ) )
                    {
                        $country = $_REQUEST['country'];
                    }

                    if ( isset( $_REQUEST['AddressType'] ) )
                    {
                        $AddressType = $_REQUEST['AddressType'];
                    }

                    if($AddressType == 'Billing'){

                        // function to call
                        GBWM_OrdersFunctions::gbwm_order_billing_address_update($siteID, $orderID, $first_name, $last_name, $company, $address_1, $address_2, $city, $state, $postcode, $country);

                    }elseif($AddressType == 'Shipping'){

                        // function to call
                        GBWM_OrdersFunctions::gbwm_order_shipping_address_update($siteID, $orderID, $first_name, $last_name, $company, $address_1, $address_2, $city, $state, $postcode, $country);

                    }

                    // now die
                    die();

                }






                /**
                 * start of site functions
                 */

                // ajaxFunction AddSite
                if($ajaxFunction == 'AddSite'){

                    if ( isset( $_REQUEST['url'] ) )
                    {
                        $url = $_REQUEST['url'];
                    }

                    if ( isset( $_REQUEST['SiteCK'] ) )
                    {
                        $SiteCK = $_REQUEST['SiteCK'];
                    }

                    if ( isset( $_REQUEST['SiteCS'] ) )
                    {
                        $SiteCS = $_REQUEST['SiteCS'];
                    }

                    if ( isset( $_REQUEST['WooSite'] ) )
                    {
                        $WooSite = $_REQUEST['WooSite'];
                    }

                    if ( isset( $_REQUEST['active'] ) )
                    {
                        $active = $_REQUEST['active'];
                    }

                    // function to call
                    GBWM_SitesFunctions::gbwm_add_site($url, $SiteCK, $SiteCS, $WooSite, $active);

                    // now die
                    die();

                }





                // ajaxFunction WooSiteStatus = woosite status
                if($ajaxFunction == 'WooSiteStatus'){

                    if ( isset( $_REQUEST['WooSiteStatus'] ) )
                    {
                        $WooSiteStatus = $_REQUEST['WooSiteStatus'];
                    }

                    // function to call
                    GBWM_SitesFunctions::gbwm_change_woosite_status($siteID, $WooSiteStatus);

                    // now die
                    die();

                }





                // ajaxFunction SiteStatus = site active status
                if($ajaxFunction == 'SiteStatus'){

                    if ( isset( $_REQUEST['ActiveStatus'] ) )
                    {
                        $ActiveStatus = $_REQUEST['ActiveStatus'];
                    }

                    // function to call
                    GBWM_SitesFunctions::gbwm_change_site_status($siteID, $ActiveStatus);

                    // now die
                    die();

                }





                // ajaxFunction EditSite = edit site
                if($ajaxFunction == 'EditSite'){

                    if ( isset( $_REQUEST['url'] ) )
                    {
                        $url = $_REQUEST['url'];
                    }

                    if ( isset( $_REQUEST['Consumer_key'] ) )
                    {
                        $Consumer_key = $_REQUEST['Consumer_key'];
                    }

                    if ( isset( $_REQUEST['Consumer_secret'] ) )
                    {
                        $Consumer_secret = $_REQUEST['Consumer_secret'];
                    }

                    if ( isset( $_REQUEST['WooSite'] ) )
                    {
                        $WooSite = $_REQUEST['WooSite'];
                    }

                    if ( isset( $_REQUEST['active'] ) )
                    {
                        $active = $_REQUEST['active'];
                    }

                    // function to call
                    GBWM_SitesFunctions::gbwm_edit_site($siteID, $url, $Consumer_key, $Consumer_secret, $WooSite, $active);

                    // now die
                    die();

                }




                // ajaxFunction DeleteSite = delete site
                if($ajaxFunction == 'DeleteSite'){

                    // function to call
                    GBWM_SitesFunctions::gbwm_delete_site($siteID);

                    // now die
                    die();

                }






                /**
                 * couriers section
                 */

                // change status
                if($ajaxFunction == 'CourierStatus'){

                    if ( isset( $_REQUEST['ActiveStatus'] ) )
                    {
                        $ActiveStatus = $_REQUEST['ActiveStatus'];
                    }

                    // function to call
                    GBWM_CouriersFunctions::gbwm_change_courier_status($siteID, $ActiveStatus);

                    // now die
                    die();

                }




                // add new courier
                if($ajaxFunction == 'AddCourier'){

                    if ( isset( $_REQUEST['title'] ) )
                    {
                        $title = $_REQUEST['title'];
                    }

                    if ( isset( $_REQUEST['url'] ) )
                    {
                        $url = $_REQUEST['url'];
                    }

                    if ( isset( $_REQUEST['active'] ) )
                    {
                        $active = $_REQUEST['active'];
                    }

                    // function to call
                    GBWM_CouriersFunctions::gbwm_add_courier($title, $url, $active);

                    // now die
                    die();

                }




                // ajaxFunction EditCourier = edit courier
                if($ajaxFunction == 'EditCourier'){

                    if ( isset( $_REQUEST['title'] ) )
                    {
                        $title = $_REQUEST['title'];
                    }

                    if ( isset( $_REQUEST['url'] ) )
                    {
                        $url = $_REQUEST['url'];
                    }

                    if ( isset( $_REQUEST['active'] ) )
                    {
                        $active = $_REQUEST['active'];
                    }

                    // function to call
                    GBWM_CouriersFunctions::gbwm_edit_courier($siteID, $title, $url, $active);

                    // now die
                    die();

                }




                // ajaxFunction DeleteCourier = delete courier
                if($ajaxFunction == 'DeleteCourier'){

                    // function to call
                    GBWM_CouriersFunctions::gbwm_delete_courier($siteID);

                    // now die
                    die();

                }






                /**
                 * start of template functions
                 */

                // ajaxFunction InstallTemplate
                if($ajaxFunction == 'InstallTemplate'){

                    if ( isset( $_REQUEST['template_type'] ) )
                    {
                        $template_type = $_REQUEST['template_type'];
                    }

                    if ( isset( $_REQUEST['templateID'] ) )
                    {
                        $templateID = $_REQUEST['templateID'];
                    }

                    // function to call
                    GBWM_TemplatesFunctions::gbwm_install_template( $template_type, $templateID );

                    // now die
                    die();

                }







                // ajaxFunction ActivateTemplate
                if($ajaxFunction == 'ActivateTemplate'){

                    if ( isset( $_REQUEST['template_type'] ) )
                    {
                        $template_type = $_REQUEST['template_type'];
                    }

                    if ( isset( $_REQUEST['templateID'] ) )
                    {
                        $templateID = $_REQUEST['templateID'];
                    }

                    // function to call
                    GBWM_TemplatesFunctions::gbwm_activate_template( $template_type, $templateID );

                    // now die
                    die();

                }







                // ajaxFunction DeactivateTemplate
                if($ajaxFunction == 'DeactivateTemplate'){

                    if ( isset( $_REQUEST['template_type'] ) )
                    {
                        $template_type = $_REQUEST['template_type'];
                    }

                    if ( isset( $_REQUEST['templateID'] ) )
                    {
                        $templateID = $_REQUEST['templateID'];
                    }

                    // function to call
                    GBWM_TemplatesFunctions::gbwm_deactivate_template( $template_type, $templateID );

                    // now die
                    die();

                }







                // ajaxFunction SiteTemplate = get saved template
                if($ajaxFunction == 'SiteTemplate'){

                    // function to call
                    GBWM_TemplatesFunctions::gbwm_site_template($siteID);

                    // now die
                    die();

                }







                // ajaxFunction ChangeTemplate = change current template
                if($ajaxFunction == 'ChangeTemplate'){

                    // function to call
                    GBWM_TemplatesFunctions::gbwm_change_template( $siteID );

                    // now die
                    die();

                }







                // ajaxFunction SaveSiteTemplate
                if($ajaxFunction == 'SaveSiteTemplate'){

                    // function to call
                    GBWM_TemplatesFunctions::gbwm_save_site_template();

                    // now die
                    die();

                }






                /**
                 * start of download functions
                 */

                // ajaxFunction DownloadOrders
                if($ajaxFunction == 'DownloadOrders'){

                    if ( isset( $_REQUEST['status'] ) )
                    {
                        $status = $_REQUEST['status'];
                    }else{
                        $status = null;
                    }

                    if ( isset( $_REQUEST['downloadtype'] ) )
                    {
                        $type = $_REQUEST['downloadtype'];
                    }

                    if ( isset( $_REQUEST['idsArr'] ) )
                    {
                        $idsArr = $_REQUEST['idsArr'];
                    }else{
                        $idsArr = null;
                    }

                    //GBWM_DownloadsFunctions::gbwm_download_orders( $siteID );
                    GBWM_Cron::gbwm_create_files_cron( $siteID, $status, $type, $idsArr );

                    // now die
                    die();

                }






                // ajaxFunction DeleteSelectedFiles
                if($ajaxFunction == 'DeleteSelectedFiles'){

                    GBWM_DownloadsFunctions::gbwm_delete_selected_files();

                    // now die
                    die();

                }






                // ajaxFunction DeleteFile
                if($ajaxFunction == 'DeleteFile'){

                    GBWM_DownloadsFunctions::gbwm_delete_file();

                    // now die
                    die();

                }






                // ajaxFunction DeleteFile
                if($ajaxFunction == 'DownloadFile'){

                    GBWM_DownloadsFunctions::gbwm_download_file();

                    // now die
                    die();

                }






                // end of if !empty

            } else {

                $reponse = array();
                $response['response'] = __( "You didn't send the siteID", 'woomulti' );

                header( "Content-Type: application/json" );
                echo json_encode($response);

                //Don't forget to always exit in the ajax function.
                die();
            }
        }
    }

}