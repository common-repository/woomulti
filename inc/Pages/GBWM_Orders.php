<?php
/**
 * @package GenBuz WooMulti
 */

namespace GBWM_Inc\Pages;

class GBWM_Orders {

    public static function gbwm_orders() {

        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_sites';

        $per_page     = 100;
        $page_number = 1;

        $WooSitesSQL = 'SELECT * FROM '. $table_name .' WHERE active=1';

        // do ordering if any
        $WooSitesSQL .= ' LIMIT '. $per_page;
        $WooSitesSQL .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

        // now do the database call
        $WooSitesResults = $wpdb->get_results( $WooSitesSQL );

        ?>

        <div class="wrap gbwm-orders">

        <h1 class="hideH1"></h1>

            <div class="tab-menu"><h4 class="download toggle-nav bluebg" value="0"><i class="icon fas fa-chevron-up"></i> <?php _e( 'Menu', 'woomulti' );?></h4></div>

            <div id="tabbed-tabs" class="tabbed clearfix">

                <ul class="tabbed-nav title-font">

                    <li class="active"><a href="#tabbed-tab-default"><?php _e( 'Dashboard', 'woomulti' );?></a></li>

                    <?php
                    // only got one site at the min
                    foreach ( $WooSitesResults as $WooSite ) {
                        $siteID = $WooSite->id;
                        $WooSiteURL = $WooSite->url;
                    ?>

                    <li class=""><a href="#tabbed-tab<?php echo $siteID; ?>" class="wooAPI" id="<?php echo $siteID; ?>"><?php echo $WooSiteURL; ?></a></li>

                    <?php
                    }
                    ?>

                </ul>

                <div class="tabbed-content-container">

                    <div id="tabbed-tab-default" class="tabbed-results">

                        <div class="orders-title bluebg title-font">

                            <span><?php _e( 'Welcome to GenBuz WooMulti', 'woomulti' );?></span>

                            <h2><?php _e( 'Multi site WooCommerce Api order managment', 'woomulti' );?></h2>
                        </div>

                        <div class="dashboard-subheading">
                            <h1 class="blue title-font"><?php _e( 'Getting Started', 'woomulti' );?></h1>
                            <p><?php _e( 'A great way to get started are with the main options below', 'woomulti' );?>.</p>
                        </div>

                        <div class="ct-new-columns dashboard-options">

                            <div id="dashboard-sites" class="ct-div-block dashbox">

                                <div class="ct-div-block shadow-box-wrapper">

                                    <h4 class="ct-headline blue shadow-box-title title-font"><?php _e( 'Sites', 'woomulti' );?></h4>

                                    <div class="ct-text-block shadow-box-text">
                                        <p><?php _e( "Add a new site to manage that site's orders", 'woomulti' );?>.</p>
                                    </div>

                                    <a class="ct-link-text atomic-small-button-outline" href="<?php echo admin_url(); ?>admin.php?page=gbwm_sites" target="_self"><?php _e( 'ADD SITE', 'woomulti' );?></a>

                                </div>

                            </div>

                            <div id="dashboard-couriers" class="ct-div-block dashbox">

                                <div class="ct-div-block shadow-box-wrapper">

                                    <h4 class="ct-headline blue shadow-box-title title-font"><?php _e( 'Couriers', 'woomulti' );?></h4>

                                    <div class="ct-text-block shadow-box-text">
                                        <p><?php _e( "Add couriers to start using tracking", 'woomulti' );?>.</p>
                                    </div>

                                    <a class="ct-link-text atomic-small-button-outline" href="<?php echo admin_url(); ?>admin.php?page=gbwm_couriers" target="_self"><?php _e( 'ADD COURIER', 'woomulti' );?></a>

                                </div>

                            </div>

                            <div id="dashboard-settings" class="ct-div-block dashbox">

                                <div class="ct-div-block shadow-box-wrapper">

                                    <h4 class="ct-headline blue shadow-box-title title-font"><?php _e( 'Settings', 'woomulti' );?></h4>

                                    <div class="ct-text-block shadow-box-text">
                                        <p><?php _e( 'Remember to choose the settings you want', 'woomulti' );?>.</p>
                                    </div>

                                    <a class="ct-link-text atomic-small-button-outline" href="<?php echo admin_url(); ?>admin.php?page=gbwm_settings" target="_self"><?php _e( 'UPDATE SETTINGS', 'woomulti' );?></a>

                                </div>

                            </div>

                        </div>

                    </div>

                    <?php
                    // only got one site at the min
                    foreach ($WooSitesResults as $WooSite) {
                        $siteID     = $WooSite->id;
                        $WooSiteURL = $WooSite->url;
                    ?>

                    <div id="tabbed-tab<?php echo $siteID; ?>" class="tabbed-results" style="display: none;">

                        <div class="orders-title bluebg">
                            <h1 class="title-font"><?php _e( 'Orders for', 'woomulti' );?> <?php echo $WooSiteURL; ?></h1>
                        </div>

                        <div class="alert" style="display: none;">
                            <span class="closebtn">&times;</span>
                            <span class="ajaxMessage"></span>
                        </div>

                        <div id="WooAPIContainer<?php echo $siteID; ?>">
                            <div class="LoadingSitesAjax"></div>
                        </div><?php //end WooAPIContainer ?>

                    </div><?php //end bhoechie-tab-content ?>

                    <?php
                    }// end foreach site
                    ?>

                </div><?php // end tabbed-content-container ?>

            </div><?php // end tabbed-tabs ?>

        </div><?php //end wrap gbwm-css ?>





        <!-- modal content -->
        <div id="ModalEditOrder">

            <h4 class="modal-title center title-font"><?php _e( 'Update Order', 'woomulti' );?></h4>

            <div class="closeForm" style="display: none; text-align: center;">

                <p class="alert"></p>

                <button type="button" class="btn btn-info btn-xs simplemodal-close"><?php _e( 'Close', 'woomulti' );?></button>

            </div>

            <div class="statusForm">

                <p><?php _e( 'Use the form below to update the details of this order or add tracking number', 'woomulti' );?>.</p>

                <form action="" method="post">

                <input type="hidden" class="form-control" name="siteID" value="">
                <input type="hidden" class="form-control" name="orderID" value="">

                <div class="form-group">

                    <label for="orderStatus"><?php _e( 'Change Order Status', 'woomulti' );?>:</label>
                    <select id="orderStatus" class="form-control" name="orderStatus">

                    <?php
                    // order status
                    foreach(wc_get_order_statuses() as $status => $status_label){

                        $status = str_replace( 'wc-', '', $status );

                        echo '<option class="orderStatusOption" value="'.$status.'">'.$status_label.'</option>';

                        //echo $status_label.' - '.$status.'<br>';
                    }
                    ?>
                    </select>

                    <?php
                        // load settings
                        $gbwm_settings = get_option( 'gbwm_plugin_settings' );

                        //if tracking is enabled
                        if( $gbwm_settings['gbwm_enable_tracking'] == 'Yes' )
                        {
                    ?>

                    <div id="trackingDiv">

                        <p><?php _e( 'You can add a tracking number and tracking link to completed orders (optional)', 'woomulti' );?>.</p>

                        <label for="trackingURL"><?php _e( 'Tracking', 'woomulti' );?>:</label>
                        <select id="trackingURL" class="form-control" name="trackingURL">

                            <?php

                            if( empty( $gbwm_settings['gbwm_default_courier'] ) )
                            {
                                // empty option
                                echo '<option value="0">'. __('No Tracking', 'woomulti') .'</option>';
                            }else{

                                echo '<option class="trackingURLOption" value="0" '. selected( $gbwm_settings['gbwm_default_courier'], 0, false ) .'>'. __('No Tracking', 'woomulti') .'</option>';

                            }

                            // get the active tracking urls from the database
                            // table name for stored sites
                            $table_name = $wpdb->prefix.'gbwm_couriers';

                            // prepare the sql call
                            $couriers_SQL = 'SELECT * FROM '. $table_name .' WHERE active=1';

                            // now do the database call
                            $couriers = $wpdb->get_results( $couriers_SQL );

                            foreach( $couriers as $courier ){

                                if( empty( $gbwm_settings['gbwm_default_courier'] ) )
                                {
                                    echo '<option class="trackingURLOption" value="'.$courier->url.'">'.$courier->title.'</option>';

                                }else{

                                    echo '<option class="trackingURLOption" value="'.$courier->url.'" '.selected( $gbwm_settings['gbwm_default_courier'], $courier->id, false ).'>'.$courier->title.'</option>';

                                }
                            }

                            ?>

                        </select>
                        <?php
                        if( $gbwm_settings['gbwm_default_courier'] == 0 || $gbwm_settings['gbwm_default_courier'] == '' ){
                            $showhide = 'none';
                        }else{
                            $showhide = 'block';
                        }
                        ?>
                        <div id="trackingNumberDiv" style="display: <?php echo $showhide;?>">

                            <label for="trackingNumber"><?php _e( 'Enter Tracking Number', 'woomulti' );?>:</label>

                            <input type="text" class="form-control" name="trackingNumber" value="">

                            <span class="trackingNumberValidate" style="color:red;display: none"><?php _e( 'Tracking number is required when a courier is chosen!', 'woomulti' );?></span>
                        </div>

                    </div>

                    <?php
                        } //end ! empty
                    ?>

                    <div class="modal-footer modal-title">

                        <button type="submit" class="btn btn-secondary close simplemodal-close" name="btn-close"><?php _e( 'Close', 'woomulti' );?></button>

                        <button type="submit" class="btn btn-info submit" name="btn-update"><?php _e( 'Update', 'woomulti' );?></button>

                    </div>

                </form>

            </div>

        </div>

    <?php

    /**
     * after modal hook
     */
    do_action('gbwm_orderlist_last', 10);

    } // end orders function

} // end Orders class