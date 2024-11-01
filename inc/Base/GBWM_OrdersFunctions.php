<?php

 /**
 * @package GenBuz WooMulti
 */

/**
 * this file contains the following functions
 *
 * gbwm_list_orders - lists the orders for the site(s), the following
 * are passed to this function
 *
 * $siteID, $PageNumber, $NewStatus
 * ==================================================================
 *
 * gbwm_order_status - changes the status of an order and also add
 * updates the tracking for an order, the following are passed to
 * this function
 *
 * $siteID, $orderID, $orderStatus, $trackingNumber, $trackingURL
 * ==================================================================
 *
 * gbwm_order_preview - shows the details of an order and also the
 * shows a form to update shipping address, the following are passed
 * to this function.
 *
 * ($siteID, $orderID)
 * ==================================================================
 *
 * gbwm_order_billing_address_update - used to updated the billing
 * address of an order, billing address cannot be updated with the
 * standard woocommerce api, it requires a workaround via custom
 * rest_route, the following are passed to this function
 *
 * $siteID, $orderID, $first_name, $last_name, $company, $address_1,
 * $address_2, $city, $state, $postcode, $country
 * ==================================================================
 *
 * gbwm_order_shipping_address_update - used to updated the shipping
 * address of an order, shipping address cannot be updated with the
 * standard woocommerce api, it requires a workaround via custom
 * rest_route, the following are passed to this function
 *
 * $siteID, $orderID, $first_name, $last_name, $company, $address_1,
 * $address_2, $city, $state, $postcode, $country
 *
 */

// has to be the very first php on the page
namespace GBWM_Inc\Base;

// enable all error reporting for now
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

class GBWM_OrdersFunctions
{

    public static function gbwm_list_orders($siteID)
    {
        if ( isset( $_REQUEST['PageNumber'] ) )
        {
            $PageNumber = $_REQUEST['PageNumber'];
        }

        if ( isset( $_REQUEST['NewStatus'] ) )
        {
            $NewStatus = $_REQUEST['NewStatus'];
        }
        // load woocommerce and make it global
        global $woocommerce;

        // load wordpress database object and make it global
        global $wpdb;

        // load site settings
        $gbwm_settings = get_option( 'gbwm_plugin_settings' );

        // load status setting
        $CurrentStatus = get_option( 'gbwm_orders_listings_status' );

        // table name
        $table_name = $wpdb->prefix.'gbwm_sites';

        // sql
        $WooSitesSQL = 'SELECT * FROM '. $table_name .' WHERE id='.$siteID;

        // now do the database call
        $WooSiteResult = $wpdb->get_row( $WooSitesSQL );

        $WooSite = $WooSiteResult->woosite;

        $woocommerceAPI = new Client(
            $WooSiteResult->url,
            $WooSiteResult->Consumer_key,
            $WooSiteResult->Consumer_secret,
            [
                'wp_api' => true,
                'verify_ssl' => false, //$UseSSL
                'version' => 'wc/v2',
                'query_string_auth' => true,
                'validate_url'      => false,
                'follow_redirects' => true,
                //'return_as_array' => true,
            ]

        );// end new client



        // this should not be empty but just in case
        if( empty( $gbwm_settings ) )
        {
            // create an empty array
            $gbwm_settings = array();
        }



        // this should not be empty but just in case
        if( empty( $CurrentStatus ) )
        {
            // set the default status if empty
            $CurrentStatus = 'any';

            // create the setting
            update_option( 'gbwm_orders_listings_status', 'any', 'no' );
        }

        // if  new status has been sent via ajax
        if(! empty( $NewStatus ))
        {
            $CurrentStatus = $NewStatus;
            update_option( 'gbwm_orders_listings_status', $NewStatus, 'no' );
        }



        // this should not be empty but just in case
        $PerPageOrders = $gbwm_settings['gbwm_pagination'];

        if( empty( $PerPageOrders ) )
        {
            $PerPageOrders = 30;

            // add the pagination to the settings array
            $gbwm_settings['gbwm_pagination'] = $PerPageOrders;

            // create/update the setting
            update_option('gbwm_plugin_settings', $gbwm_settings, 'no');
        }



        if( empty( $PageNumber ) )
        {
            $PageNumber = 1;
        }



        try {

            // make the api call and store the results in $Orders
            $Orders = $woocommerceAPI->get('orders', [
                'status'    => $CurrentStatus,
                'per_page'  => $PerPageOrders,
                'page'      => $PageNumber,
            ]);

            $lastRequest   = $woocommerceAPI->http->getRequest();
            $lastResponse   = $woocommerceAPI->http->getResponse();
            $headers        = $lastResponse->getHeaders();
            $TotalOrders    = $headers['X-WP-Total'];
            $TotalPages     = $headers['X-WP-TotalPages'];

            // create our array
            $MultiArray = array();

        // if there is an api error catch and display
        } catch (HttpClientException $e)
        {
        ?>

        <div class="API_errors">

            <h1 class="title-font"><?php _e( 'Sorry there was an error getting orders from this site', 'woomulti' );?>.</h1>

            <p><?php _e( 'The error message is', 'woomulti' );?>.</p>
            <p class="bold"><?php echo $e->getMessage(); // Error message?></p>

            <p><?php _e( 'Please see the', 'woomulti' );?> <a href="<?php echo admin_url(); ?>admin.php?page=gbwm_help" target="_self"><?php _e( 'Help Section', 'woomulti' );?></a> <?php _e( 'for help with errors', 'woomulti' );?>.</p>

        </div>

        <?
            return;
        }// end catch api errors


            /**
             * start our pagination functions
             */

            $prev_disabled = '';
            $next_disabled = '';

            //function to return the pagination string
            function gbwm_getPagination( $siteID, $PageNumber = 1, $TotalPages, $PerPageOrders = 30, $adjacents = 1 )
            {

                //defaults
                if( !$adjacents ) $adjacents = 1;
                if( !$PerPageOrders ) $PerPageOrders = 30;
                if( !$PageNumber ) $PageNumber = 1;

                //other vars
                $prev = $PageNumber - 1;          //previous page is page - 1
                $next = $PageNumber + 1;          //next page is page + 1
                $lastpage = $TotalPages;    //lastpage
                $lpm1 = $lastpage - 1;      //last page minus 1

                /*
                    Now we apply our rules and draw the pagination object.
                    We're actually saving the code to a variable in case we want to draw it more than once.
                */

                $pagination = '';

                $pagination .= '<nav aria-label="Page navigation">';
                $pagination .= '<ul class="pagination manage-orders" data-siteid="'.$siteID.'">';

                if( $lastpage > 1 )
                {
                    //previous button
                    if ($PageNumber > 1)
                    {
                        $pagination .= '<li class="page-item"><a class="page-link" href="#">'.__( 'Previous', 'woomulti' ).'</a></li>';
                    }else{
                        $pagination .= '';
                    }
                    //pages
                    if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
                    {
                        for ($counter = 1; $counter <= $lastpage; $counter++)
                        {
                            if ($counter == $PageNumber)
                            {
                                $pagination .= '<li class="page-item active current"><a class="page-link" href="#">'.$counter.'</a></li>';
                            }else{
                                $pagination .= '<li class="page-item"><a class="page-link" href="#">'.$counter.'</a></li>';
                            }
                        }
                    }
                    elseif($lastpage >= 7 + ($adjacents * 2))	//enough pages to hide some
                    {
                        //close to beginning; only hide later pages
                        if($PageNumber < 1 + ($adjacents * 3))
                        {
                            for ($counter = 1; $counter < 3 + ($adjacents * 2); $counter++)
                            {
                                if ($counter == $PageNumber)
                                {
                                    $pagination .= '<li class="page-item active current"><a class="page-link" href="#">'.$counter.'</a></li>';
                                }else{
                                    $pagination .= '<li class="page-item"><a class="page-link" href="#">'.$counter.'</a></li>';
                                }
                            }

                            // seperator
                            $pagination .= '<span class="elipses">...</span>';

                            $pagination .= '<li class="page-item"><a class="page-link" href="#">'.$lpm1.'</a></li>';
                            $pagination .= '<li class="page-item"><a class="page-link" href="#">'.$lastpage.'</a></li>';
                        }
                        //in middle; hide some front and some back
                        elseif($lastpage - ($adjacents * 2) > $PageNumber && $PageNumber > ($adjacents * 2))
                        {
                            $pagination .= '<li class="page-item"><a class="page-link" href="#">1</a></li>';
                            $pagination .= '<li class="page-item"><a class="page-link" href="#">2</a></li>';
                            $pagination .= '<span class="elipses">...</span>';
                            for ($counter = $PageNumber - $adjacents; $counter <= $PageNumber + $adjacents; $counter++)
                            {
                                if ($counter == $PageNumber)
                                {
                                    $pagination .= '<li class="page-item active current"><a class="page-link" href="#">'.$counter.'</a></li>';
                                }else{
                                    $pagination .= '<li class="page-item"><a class="page-link" href="#">'.$counter.'</a></li>';
                                }
                            }
                            $pagination .= '<span class="elipses">...</span>';
                            $pagination .= '<li class="page-item"><a class="page-link" href="#">'.$lpm1.'</a></li>';
                            $pagination .= '<li class="page-item"><a class="page-link" href="#">'.$lastpage.'</a></li>';
                        }
                        //close to end; only hide early pages
                        else
                        {
                            $pagination .= '<li class="page-item"><a class="page-link" href="#">1</a></li>';
                            $pagination .= '<li class="page-item"><a class="page-link" href="#">2</a></li>';
                            $pagination .= '<span class="elipses">...</span>';
                            for ($counter = $lastpage - (1 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                            {
                                if ($counter == $PageNumber)
                                {
                                    $pagination .= '<li class="page-item active current"><a class="page-link" href="#">'.$counter.'</a></li>';
                                }else{
                                    $pagination .= '<li class="page-item"><a class="page-link" href="#">'.$counter.'</a></li>';
                                }
                            }
                        }
                    }

                    //next button
                    if ($PageNumber < $counter - 1)
                    {
                        $pagination .= '<li class="page-item"><a class="page-link" href="#">'.__( 'Next', 'woomulti' ).'</a></li>';
                    }else{
                        $pagination .= '';
                    }
                }

                $pagination .= '</ul>';
                $pagination .= '</nav>';

                return $pagination;

            }

            $statuses_array = array('any' => 'All Orders');

            // order status
            foreach(wc_get_order_statuses() as $status => $status_label)
            {
                $status = str_replace( 'wc-', '', $status );

                // add the status to the $statuses_array
                $statuses_array[$status] = $status_label;

                if($status == $CurrentStatus)
                {
                    $CurrentStatusLabel = $status_label;
                }
            }

            foreach($statuses_array as $status => $status_label)
            {
                if($status == $CurrentStatus)
                {
                    $CurrentStatusLabel = $status_label;
                }
            }

            //echo print_r($statuses_array);
        ?>

            <div class="options-row ct-new-columns title-font">

                <div id="orders_toggle_container" class="ct-div-block">

                    <h4 class="download toggle-nav bluebg" value="0"><i class="icon fas fa-chevron-left"></i></h4>

                </div>



                <div id="orders_status_container" class="ct-div-block">

                    <h4 class="status select-title bluebg"><i class="icon fas fa-check-circle"></i> <?php _e( 'Status', 'woomulti' );?>: <span class="green-light" id="SelectStatus" data-status="<?php echo $CurrentStatus; ?>"><?php echo $CurrentStatusLabel; ?></span><span class="dashicons dashicons-arrow-down"></span></h4>

                    <div class="status select-box absolute">
                    <?php
                        // order status
                        foreach($statuses_array as $status => $status_label)
                        {
                            echo '<a href="#" class="status select-select SelectStatus" data-siteid="'.$siteID.'" data-status="'.$status.'">'.$status_label.'</a>';
                        }
                    ?>
                    </div>

                </div>

                <?php

                    $resultCount = 0;

                    global $wpdb;

                    global $templateCount;

                    $table_name = $wpdb->prefix.'gbwm_templates';

                    // sql to call
                    $sqlCount = 'SELECT count(*) FROM '. $table_name .' WHERE active=1';

                    // now do the database call
                    $templateCount = $wpdb->get_var( $sqlCount );

                    // load site settings
                    @ $gbwm_download_settings = get_option( 'gbwm_plugin_settings' );

                    if( $gbwm_download_settings['gbwm_enable_downloads'] === 'Yes' && $templateCount > 0 )
                    {
                ?>
                        <div id="orders_download_container" class="ct-div-block">

                            <h4 class="download select-title bluebg" data-possition="top"><i class="icon fas fa-download"></i> Download <span class="dashicons dashicons-arrow-down"></span></h4>

                            <div class="download select-box top absolute">

                                <a href="#" class="download select-select word-pdf" data-siteid="<?php echo $siteID; ?>" data-type="selected">Download Selected</a>

                                <a href="#" class="download select-select word-pdf" data-siteid="<?php echo $siteID; ?>" data-type="status">Download All Current Status<br/>
                                <span class="downloadSmall">(<?php echo $CurrentStatusLabel;?>)</span></a>

                            </div>

                        </div>
                <?php
                    }
                    do_action('gbwm_orderlist_after_status', $siteID, $allSites=0 );
                ?>



                <div id="orders_pagination_container" class="ct-div-block">

                    <div class="gbwm-Pagination">
                        <?php
                            $adjacents = 1;
                            // pagination function call
                            echo gbwm_getPagination($siteID, $PageNumber, $TotalPages, $PerPageOrders, $adjacents)
                        ?>
                    </div>

                </div>



            </div>

            <div class="clear"></div>


        <table class="widefat gbwmtable" cellspacing="0">
            <thead>
                <tr class="gbwm-table-header bluebg title-font">

                    <th class="manage-column column-cb check-column gbwm-checkbox" scope="col">
                        <input type="checkbox" value="" data-siteid="<?php echo $siteID;?>" id="cb-select-all">
                    </th>

                    <th class="manage-column column-customer gbwm-order-list-customer hide-mobile" scope="col"><?php _e( 'Order', 'woomulti' );?></th>

                    <th class="manage-column column-customer gbwm-order-list-customer show-mobile" scope="col" colspan="4"><?php _e( 'Order', 'woomulti' );?></th>

                    <th id="date" class="manage-column column-date gbwm-order-list-date" scope="col"><?php _e( 'Date', 'woomulti' );?></th>
                    <th id="total" class="manage-column column-total gbwm-order-list-total" scope="col"><?php _e( 'Total', 'woomulti' );?></th>
                    <th id="transaction_id" class="manage-column column-transaction_id gbwm-order-list-transaction_id" scope="col"><?php _e( 'Transaction ID', 'woomulti' );?></th>
                    <th id="status" class="manage-column column-status gbwm-order-list-status" scope="col"><?php _e( 'Status', 'woomulti' );?></th>
                    <th id="actions" class="manage-column column-actions gbwm-order-list-actions" scope="col"><?php _e( 'Actions', 'woomulti' );?></th>

                </tr>
            </thead>

            <tfoot>
                <tr class="gbwm-table-footer bluebg title-font">

                    <th class="manage-column column-cb check-column gbwm-checkbox" scope="col">
                        <input type="checkbox" value="" data-siteid="<?php echo $siteID;?>" id="cb-select-all">
                    </th>

                    <th class="manage-column column-customer gbwm-order-list-customer hide-mobile" scope="col"><?php _e( 'Order', 'woomulti' );?></th>

                    <th class="manage-column column-customer gbwm-order-list-customer show-mobile" scope="col" colspan="4"><?php _e( 'Order', 'woomulti' );?></th>

                    <th class="manage-column column-date gbwm-order-list-date" scope="col"><?php _e( 'Date', 'woomulti' );?></th>
                    <th class="manage-column column-total gbwm-order-list-total" scope="col"><?php _e( 'Total', 'woomulti' );?></th>
                    <th class="manage-column column-transaction_id gbwm-order-list-transaction_id" scope="col"><?php _e( 'Transaction ID', 'woomulti' );?></th>
                    <th class="manage-column column-status gbwm-order-list-status" scope="col"><?php _e( 'Status', 'woomulti' );?></th>
                    <th class="manage-column column-actions gbwm-order-list-actions" scope="col"><?php _e( 'Actions', 'woomulti' );?></th>

                </tr>
            </tfoot>

            <tbody>

                <?php

                $i = 0;

                if($Orders == null || $Orders == 0)
                {
                ?>
                    <tr class="format-standard wpautop">
                        <td class="manage-column NoOrders bold center" data-colname="NoOrders" colspan="7"><?php _e( 'No orders', 'woomulti' );?></td>
                    </tr>
                    <?php
                }else{

                    foreach($Orders as $Order)
                    {
                        // create the array for the orders
                        /**
                         * ID
                         * Customer (first + last)
                         * Date
                         * Currency + Total
                         * Transaction ID
                         * Order Status
                         * Actions
                         */

                        // get the shipping section from the array
                        $shipping = $Order->shipping;

                        // sort the name first + last
                        $shipping->full_name = $shipping->first_name.' '.$shipping->last_name;

                        // start at 0
                        $QTY = 0;

                        // alternating rows css
                        if($i === 0)
                        {
                            $altrow = '';
                            $i = 1;
                        }elseif($i === 1)
                        {
                            $altrow = ' alt-row';
                            $i = 0;
                        }

                        // work out the quantity
                        foreach ($Order->line_items as $product)
                        {
                            $QTY = $QTY + $product->quantity;
                        }
                        $date = date( 'j M, Y', strtotime( $Order->date_created ) );

                        $ThisOrder = array(
                            'id' => $Order->id,
                            'customer' => $shipping->first_name.' '.$shipping->last_name,
                            'date' => $date,
                            'total' => get_woocommerce_currency_symbol($Order->currency).$Order->total,
                            'transaction_id' => $Order->transaction_id,
                            'status' => $Order->status,
                            'actions' => '',
                        );

                        $MultiArray[] = $ThisOrder;

                        // turn this array into an object (my prefference)
                        $ThisOrder = (object) $ThisOrder;

                        ?>
                        <tr class="gbwmtrboddy<?php echo $siteID;?>-<?php echo $ThisOrder->id;?><?php echo $altrow; ?>">

                            <td class="gbwm-checkbox">
                                <input type="checkbox" name="checked[]" value="<?php echo $ThisOrder->id; ?>" id="checkbox_<?php echo $ThisOrder->id; ?>" class="checkbox<?php echo $siteID; ?>">
                            </td>

                            <td class="gbwm-order-list-customer">#<?php echo $ThisOrder->id; ?> <?php echo $ThisOrder->customer; ?></td>
                            <td class="gbwm-order-list-date"><?php echo $ThisOrder->date; ?></td>
                            <td class="gbwm-order-list-total"><?php echo $ThisOrder->total; ?></td>
                            <td class="gbwm-order-list-transactionID"><a href="https://www.paypal.com/activity/payment/<?php echo $ThisOrder->transaction_id; ?>" target="_blank"><?php echo $ThisOrder->transaction_id; ?></a></td>
                            <td class="gbwm-order-list-status">

                                <mark class="order-status status-<?php echo $ThisOrder->status; ?> tips"><span class="gbwmStatus"><?php echo $ThisOrder->status; ?></span></mark>

                            </td>
                            <td class="gbwm-order-list-actions">

                                <?php
                                if( $gbwm_download_settings['gbwm_enable_downloads'] === 'Yes' && $templateCount > 0 )
                                {
                                ?>
                                    <button type="button" class="btn btn-dark btn-xs PrintOrder" data-orderid="<?php echo $ThisOrder->id;?>" data-siteid="<?php echo $siteID;?>" data-type="print">
                                        <i class="icon fas fa-print"></i>
                                    </button>
                                <?php
                                }
                                ?>

                                <button type="button" class="btn btn-primary btn-xs OrderView" data-orderid="<?php echo $ThisOrder->id;?>" data-siteid="<?php echo $siteID;?>">
                                    <i class="icon fas fa-eye"></i>
                                </button>

                                <button type="button" class="btn btn-info btn-xs OrderEdit" data-target='#ModalEditOrder' data-orderid="<?php echo $ThisOrder->id;?>" data-siteid="<?php echo $siteID;?>" data-woosite="<?php echo $WooSite;?>" data-toggle='modal'>
                                    <i class="icon fas fa-pen"></i>
                                </button>

                            </td>
                        </tr>
                    <?php
                    }// end foreach order
                }// end else
                ?>
            </tbody>
        </table>

        <div class="options-row ct-new-columns">

            <div id="orders_toggle_container" class="ct-div-block">

                <h4 class="download toggle-nav bluebg" value="0"><i class="icon fas fa-chevron-left"></i></h4>

            </div>



            <div id="orders_pagination_container" class="ct-div-block">

                <div class="gbwm-Pagination">
                    <?php
                        $adjacents = 1;
                        // pagination function call
                        echo gbwm_getPagination($siteID, $PageNumber, $TotalPages, $PerPageOrders, $adjacents)
                    ?>
                </div>

            </div>



</div>

<div class="clear"></div>

    <script type="text/javascript">
        var PageNumber  = parseInt('<?php echo $PageNumber; ?>'),
            TotalOrders = parseInt('<?php echo $TotalOrders; ?>'),
            TotalPages  = parseInt('<?php echo $TotalPages; ?>'),
            HomeURL  = '<?php echo get_site_url(); ?>'
            ;
    </script>

<?php
    } // end of gbwm_list_orders function







    /**
     * change status and/or add tracking details
     */

    public static function gbwm_order_status( $siteID )
    {
        // load woocommerce and make it global
        global $woocommerce;

        // load wordpress database object and make it global
        global $wpdb;

        // table name for stored sites
        $table_name = $wpdb->prefix.'gbwm_sites';

        // prepare the sql call
        $WooSitesSQL = 'SELECT * FROM '. $table_name .' WHERE id='.$siteID;

        // connect to the database and get the api creddentials for the selected site
        $WooSiteResult = $wpdb->get_row( $WooSitesSQL );

        // start a new wooapi client
        $woocommerceAPI = new Client(
            $WooSiteResult->url,
            $WooSiteResult->Consumer_key,
            $WooSiteResult->Consumer_secret,
            [
                'wp_api'            => true,
                'verify_ssl'        => false, //$UseSSL
                'version'           => 'wc/v2',
                'query_string_auth' => true,
                'debug'             => false,
                'return_as_array'   => false,
                'validate_url'      => false,
                'timeout'           => 15,
            ]

        );// end new client

        // required
        if ( isset( $_REQUEST['orderID'] ) )
        {
            $orderID = $_REQUEST['orderID'];
        }

        // required
        if ( isset( $_REQUEST['orderStatus'] ) )
        {
            $orderStatus = $_REQUEST['orderStatus'];
        }


        // for our custom meta tracking and url
        if ( isset( $_REQUEST['trackingNumber'] ) )
        {
            $trackingNumber = $_REQUEST['trackingNumber'];
        }

        if ( isset( $_REQUEST['trackingURL'] ) )
        {
            $trackingURL = $_REQUEST['trackingURL'];
        }

        if(! empty($trackingNumber) && ! empty($trackingURL))
        {

            $data = array(
                'id' => $orderID,
                'status' => $orderStatus,
                'meta_data' => array(
                    [
                    'key' => 'gbwm_tracking_number',
                    'value' => $trackingNumber,
                    ],
                    [
                    'key' => 'gbwm_tracking_url',
                    'value' => $trackingURL,
                    ],
                ),
            );

        } else {

            $data = array(
                'id' => $orderID,
                'status' => $orderStatus,
            );
        }

        try {

        //pending, on-hold, processing, completed, refunded, failed, cancelled
        $update_status = $woocommerceAPI->put( 'orders/'.$orderID, $data );

        // if there is an api error catch and display
        } catch ( HttpClientException $e )
        {
            $return = array(
                'message'  => $e->getMessage(),
                'id'       => 0
            );

            wp_send_json( $return );
            die();

        }// end catch api errors

            $return = array(
                'message'  => __( 'Order status and/or tracking updated', 'woomulti' ),
                'id'       => 1
            );

            wp_send_json( $return );
            die();

    } // end update order status/tracking







    /**
     * order preview
     */

    public static function gbwm_order_preview($siteID, $orderID)
    {
        // load woocommerce and make it global
        global $woocommerce;

        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_sites';

        $WooSitesSQL = 'SELECT * FROM '. $table_name .' WHERE id='.$siteID;

        // now do the database call
        $WooSiteResult = $wpdb->get_row( $WooSitesSQL );

        $WooSiteStatus = $WooSiteResult->woosite;

        $woocommerceAPI = new Client(
            $WooSiteResult->url,
            $WooSiteResult->Consumer_key,
            $WooSiteResult->Consumer_secret,
            [
                'wp_api' => true,
                'verify_ssl' => false, //$UseSSL
                'version' => 'wc/v2',
                'query_string_auth' => true,
                'validate_url'      => false,
                //'return_as_array' => true,
            ]

        );// end new client

        try {

        // make the api call and store the results in $Orders
        $Order = $woocommerceAPI->get('orders/'.$orderID.'', [
        ]);

            $lastRequest   = $woocommerceAPI->http->getRequest();
            $lastResponse   = $woocommerceAPI->http->getResponse();
            $headers        = $lastResponse->getHeaders();

        // if there is an api error catch and display
        } catch (HttpClientException $e)
        {
        ?>

        <div class="API_errors">

            <h1 class="title-font"><?php _e( 'Sorry there was an error getting orders from this site', 'woomulti' );?>.</h1>

            <p><?php _e( 'The error message is', 'woomulti' );?>.</p>
            <p class="bold"><?php echo $e->getMessage(); // Error message?></p>

            <p><?php _e( 'Please see the', 'woomulti' );?> <a href="<?php echo admin_url(); ?>admin.php?page=gbwm_help" target="_self"><?php _e( 'Help Section', 'woomulti' );?></a> <?php _e( 'for help with errors', 'woomulti' );?>.</p>

        </div>

        <?
            return;
        }// end catch api errors

                // get the billing / shipping sections from the array
                $billing = $Order->billing;
                $shipping = $Order->shipping;
                $products = $Order->line_items;
                $shipping_lines = $Order->shipping_lines;
                $trackingMeta = $Order->meta_data;

                $TrackingNumber = '';
                $TrackingLink = '';

                foreach($trackingMeta as $tracking)
                {
                    if($tracking->key == 'gbwm_tracking_number'){
                        $TrackingNumber = $tracking->value;
                    }

                    if($tracking->key == 'gbwm_tracking_url'){
                        $TrackingLink = $tracking->value;
                    }
                }
        ?>

        <div id="preview-container" class="ct-new-columns">

            <div id="preview-content" class="ct-div-block">

                <div class="preview-top-container ct-new-columns">

                    <div id="order_number_container" class="ct-div-block">

                        <h1 id="headline-order-number" class="ct-headline blue title-font"><?php _e( 'Order #', 'woomulti' );?><?php echo $orderID; ?> <?php _e( 'details', 'woomulti' );?></h1>

                    </div>

                    <div id="close_preview_container" class="ct-div-block">

                        <input type="submit" name="ClosePreview" id="ClosePreview" class="ClosePreview button button-info title-font" value="<?php _e( 'Close Preview', 'woomulti' );?>" data-siteid="<?php echo $siteID; ?>" data-orderid="<?php echo $orderID; ?>">

                    </div>

                </div>



                <div class="ct-text-block">
                    <?php _e( 'Payment via', 'woomulti' );?> <?php echo $Order->payment_method_title; ?> (<a href="https://www.paypal.com/activity/payment/<?php echo $Order->transaction_id; ?>" target="_blank"><?php echo $Order->transaction_id; ?></a>). <?php _e( 'Paid on', 'woomulti' );?> <?php echo date( 'j M, Y, g:i a', strtotime( $Order->date_paid_gmt ) ); ?>
                </div>

                <div class="ct-text-block">
                    <span class="bold"><?php _e( 'Status', 'woomulti' );?></span>: <?php echo $Order->status; ?>
                </div>

                <div class="ct-text-block">
                    <span class="bold"><?php _e( 'Email address', 'woomulti' );?></span>: <?php echo $billing->email; ?>
                </div>

                <div class="ct-text-block">
                    <span class="bold"><?php _e( 'Phone', 'woomulti' );?></span>: <?php echo $billing->phone; ?>
                </div>


                <div class="ct-new-columns products_ordered_header_container title-font">

                    <div class="ct-div-block product_details_header">
                        <?php _e( 'Product', 'woomulti' );?>
                    </div>

                    <div class="ct-div-block product_cost_header">
                        <?php _e( 'Cost', 'woomulti' );?>
                    </div>

                    <div class="ct-div-block product_qty_header">
                        <?php _e( 'QTY', 'woomulti' );?>
                    </div>

                    <div class="ct-div-block product_total_header">
                        <?php _e( 'Total', 'woomulti' );?>
                    </div>

                </div>

                <?php

                foreach($products as $product)
                {
                ?>

                <div class="ct-new-columns products_ordered_container">

                    <div class="ct-div-block product_details">
                        <p><?php echo $product->name; ?></p>
                        <span><strong><?php _e( 'SKU', 'woomulti' );?>:</strong> <?php echo $product->sku; ?></span>
                    </div>

                    <div class="ct-div-block product_cost"><?php echo get_woocommerce_currency_symbol($Order->currency).$product->price; ?></div>

                    <div class="ct-div-block product_qty">x <?php echo $product->quantity; ?></div>

                    <div class="ct-div-block product_total"><?php echo get_woocommerce_currency_symbol($Order->currency).$product->total; ?></div>

                </div>

                <?php
                }
                ?>

                <div class="ct-div-block shipping_lines_header_container title-font"><h4>Shipping Details</h4></div>

                <?php

                foreach($shipping_lines as $shipping_line)
                {
                    $ShippingItems = '';

                    foreach($shipping_line->meta_data as $ShipItems){

                        if($ShipItems->key == 'Items'){
                            $ShippingItems = $ShipItems->value;
                        }

                    }
                ?>

                <div class="ct-new-columns shipping_lines_container">

                    <div class="ct-div-block method_title">
                        <?php echo $shipping_line->method_title; ?>
                        <br>
                        <span style="font-weight: bold;">Items:</span> <?php echo $ShippingItems; ?>
                    </div>

                    <div class="ct-div-block product_total"><?php echo get_woocommerce_currency_symbol($Order->currency).$shipping_line->total; ?></div>

                </div>

                <?
                }
                ?>

                <?php
                if( ! empty( $TrackingNumber ) || ! empty( $TrackingLink )){
                ?>

                <div class="ct-new-columns tracking_header_container">

                    <div class="ct-div-block tracking_header bold">
                        <?php _e( 'Tracking Number', 'woomulti' );?>
                    </div>

                    <div class="ct-div-block tracking_header_value">
                        <?php echo $TrackingNumber;?>
                    </div>

                    <div class="ct-div-block tracking_header bold">
                        <?php _e( 'Tracking Link', 'woomulti' );?>
                    </div>

                    <div class="ct-div-block tracking_header_value">
                        <a href="<?php echo $TrackingLink;?>" target="_blank"><?php echo $TrackingLink;?></a>
                    </div>

                </div>

                <?php
                }
                ?>

                <div class="ct-div-block totals_header_container title-font"><h4>Totals</h4></div>

                <div class="ct-new-columns shipping_totals_container">

                    <div class="ct-div-block shipping_title">Discount:</div>

                    <div class="ct-div-block shipping_line_total"><span style="font-weight: bold;"><?php echo get_woocommerce_currency_symbol($Order->currency).$Order->discount_total; ?></span></div>

                </div>

                <div class="ct-new-columns shipping_totals_container">

                    <div class="ct-div-block shipping_title">Shipping:</div>

                    <div class="ct-div-block shipping_line_total"><span style="font-weight: bold;"><?php echo get_woocommerce_currency_symbol($Order->currency).$Order->shipping_total; ?></span></div>

                </div>

                <div class="ct-new-columns shipping_totals_container">

                    <div class="ct-div-block shipping_title">Total:</div>

                    <div class="ct-div-block shipping_line_total"><span style="font-weight: bold;"><?php echo get_woocommerce_currency_symbol($Order->currency).$Order->total; ?></span></div>

                </div>

            </div>

            <div id="preview-address-container" class="ct-div-block">

                <div id="address-tabs" class="address-tabs">

                    <ul id="AddressTabs">
                        <li class="address-tab title-font"><a href="#billing-address">Billling Address</a></li>
                        <li class="address-tab title-font"><a href="#shipping-address">Shipping Address</a></li>
                    </ul>

                    <div id="billing-address">

                        <h1 class="address-title blue title-font"><?php _e( 'Billling Address', 'woomulti' );?></h1>
<?php
if($WooSiteStatus == 0){// woosite IS NOT installed

    echo $billing->first_name.' '.$billing->last_name.'<br/>';
    if( ! empty( $billing->company ) ){
        echo $billing->company.'<br/>';
    }
    echo $billing->address_1.'<br/>';
    if( ! empty( $billing->address_2 ) ){
        echo $billing->address_2.'<br/>';
    }
    echo $billing->city.'<br/>';

    $states = WC()->countries->get_states( $billing->country );

    $state  = ! empty( $states[ $billing->state ] ) ? $states[ $billing->state ] : $billing->state;

    echo $state.'<br/>';
    echo $billing->postcode.'<br/>';
    echo WC()->countries->countries[ $billing->country ];
    //echo $billing->country.'<br/>';

}else{// woosite IS installed
?>

                        <div id="_billing_form-28-2" class="oxy-billing-form">
                            <form name="billingform" id="billingform<?php echo $siteID; ?>-<?php echo $orderID; ?>" action="" method="post">

                                <div class="ct-new-columns">

                                    <div id="billing_first_name_container" class="ct-div-block">

                                        <label for="billing_first_name" class="title-font"><?php _e( 'First Name', 'woomulti' );?></label>
                                        <input type="text" name="billing_first_name" id="billing_first_name" class="input" value="<?php echo $billing->first_name; ?>" size="20">

                                    </div>

                                    <div id="billing_last_name_container" class="ct-div-block">

                                        <label for="billing_last_name" class="title-font"><?php _e( 'Last Name', 'woomulti' );?></label>
                                        <input type="text" name="billing_last_name" id="billing_last_name" class="input" value="<?php echo $billing->last_name; ?>" size="20">

                                    </div>

                                </div>

                                <div id="billing_company_container" class="ct-div-block">

                                    <label for="billing_company" class="title-font"><?php _e( 'Company', 'woomulti' );?></label>
                                    <input type="text" name="billing_company" id="billing_company" class="input" value="<?php echo $billing->company; ?>" size="20">

                                </div>

                                <div class="ct-new-columns">

                                    <div id="billing_address_1_container" class="ct-div-block">

                                        <label for="billing_address_1" class="title-font"><?php _e( 'Address 1', 'woomulti' );?></label>
                                        <input type="text" name="billing_address_1" id="billing_address_1" class="input" value="<?php echo $billing->address_1; ?>" size="20">

                                    </div>

                                    <div id="billing_address_2_container" class="ct-div-block">

                                        <label for="billing_address_2" class="title-font"><?php _e( 'Address 2', 'woomulti' );?></label>
                                        <input type="text" name="billing_address_2" id="billing_address_2" class="input" value="<?php echo $billing->address_2; ?>" size="20">

                                    </div>

                                </div>

                                <div class="ct-new-columns">

                                    <div id="billing_city_container" class="ct-div-block">

                                        <label for="billing_city" class="title-font"><?php _e( 'City', 'woomulti' );?></label>
                                        <input type="text" name="billing_city" id="billing_city" class="input" value="<?php echo $billing->city; ?>" size="20">

                                    </div>

                                    <div id="billing_state_container" class="ct-div-block">

                                        <label for="billing_state" class="title-font"><?php _e( 'State', 'woomulti' );?></label>
                                        <input type="text" name="billing_state" id="billing_state" class="input" value="<?php echo $billing->state; ?>" size="20">

                                    </div>

                                </div>

                                <div class="ct-new-columns">

                                    <div id="billing_postcode_container" class="ct-div-block">

                                        <label for="billing_postcode" class="title-font"><?php _e( 'Postcode', 'woomulti' );?></label>
                                        <input type="text" name="billing_postcode" id="billing_postcode" class="input" value="<?php echo $billing->postcode; ?>" size="20">

                                    </div>

                                    <div id="billing_country_container" class="ct-div-block">

                                        <label for="billing_country" class="title-font"><?php _e( 'Country', 'woomulti' );?></label>
                                        <input type="text" name="billing_country" id="billing_country" class="input" value="<?php echo $billing->country; ?>" size="20">

                                    </div>

                                </div>

                                <div id="billing_submit_container" class="ct-div-block">

                                    <mark class="alert"></mark>

                                    <input type="submit" name="UpdateBillingAddress" id="UpdateBillingAddress" class="UpdateBillingAddress button button-primary" value="<?php _e( 'Update Address', 'woomulti' );?>" data-siteid="<?php echo $siteID; ?>" data-orderid="<?php echo $orderID; ?>">

                                </div>

                            </form>

                        </div>

<?php
}
?>

                    </div>

                    <div id="shipping-address">

                        <h1 class="address-title blue title-font"><?php _e( 'Shipping Address', 'woomulti' );?></h1>
<?php
if($WooSiteStatus == 0){// woosite IS NOT installed

    echo $shipping->first_name.' '.$shipping->last_name.'<br/>';
    if( ! empty( $shipping->company ) ){
        echo $shipping->company.'<br/>';
    }
    echo $shipping->address_1.'<br/>';
    if( ! empty( $shipping->address_2 ) ){
        echo $shipping->address_2.'<br/>';
    }
    echo $shipping->city.'<br/>';

    $states = WC()->countries->get_states( $shipping->country );

    $state  = ! empty( $states[ $shipping->state ] ) ? $states[ $shipping->state ] : $shipping->state;

    echo $state.'<br/>';
    echo $shipping->postcode.'<br/>';
    echo WC()->countries->countries[ $shipping->country ];

}else{// woosite IS installed
?>
                        <div id="_shipping_form-28-2" class="oxy-shipping-form">

                            <form name="shippingform" id="shippingform<?php echo $siteID; ?>-<?php echo $orderID; ?>" action="" method="post">

                                <div class="ct-new-columns">

                                    <div id="shipping_first_name_container" class="ct-div-block">

                                        <label for="shipping_first_name" class="title-font"><?php _e( 'First Name', 'woomulti' );?></label>
                                        <input type="text" name="shipping_first_name" id="shipping_first_name" class="input" value="<?php echo $shipping->first_name; ?>" size="20">

                                    </div>

                                    <div id="shipping_last_name_container" class="ct-div-block">

                                        <label for="shipping_last_name" class="title-font"><?php _e( 'Last Name', 'woomulti' );?></label>
                                        <input type="text" name="shipping_last_name" id="shipping_last_name" class="input" value="<?php echo $shipping->last_name; ?>" size="20">

                                    </div>

                                </div>

                                <div id="shipping_company_container" class="ct-div-block">

                                    <label for="shipping_company" class="title-font"><?php _e( 'Company', 'woomulti' );?></label>
                                    <input type="text" name="shipping_company" id="shipping_company" class="input" value="<?php echo $shipping->company; ?>" size="20">

                                </div>

                                <div class="ct-new-columns">

                                    <div id="shipping_address_1_container" class="ct-div-block">

                                        <label for="shipping_address_1" class="title-font"><?php _e( 'Address 1', 'woomulti' );?></label>
                                        <input type="text" name="shipping_address_1" id="shipping_address_1" class="input" value="<?php echo $shipping->address_1; ?>" size="20">

                                    </div>

                                    <div id="shipping_address_2_container" class="ct-div-block">

                                        <label for="shipping_address_2" class="title-font"><?php _e( 'Address 2', 'woomulti' );?></label>
                                        <input type="text" name="shipping_address_2" id="shipping_address_2" class="input" value="<?php echo $shipping->address_2; ?>" size="20">

                                    </div>

                                </div>

                                <div class="ct-new-columns">

                                    <div id="shipping_city_container" class="ct-div-block">

                                        <label for="shipping_city" class="title-font"><?php _e( 'City', 'woomulti' );?></label>
                                        <input type="text" name="shipping_city" id="shipping_city" class="input" value="<?php echo $shipping->city; ?>" size="20">

                                    </div>

                                    <div id="shipping_state_container" class="ct-div-block">

                                        <label for="shipping_state" class="title-font"><?php _e( 'State', 'woomulti' );?></label>
                                        <input type="text" name="shipping_state" id="shipping_state" class="input" value="<?php echo $shipping->state; ?>" size="20">

                                    </div>

                                </div>

                                <div class="ct-new-columns">

                                    <div id="shipping_postcode_container" class="ct-div-block">

                                        <label for="shipping_postcode" class="title-font"><?php _e( 'Postcode', 'woomulti' );?></label>
                                        <input type="text" name="shipping_postcode" id="shipping_postcode" class="input" value="<?php echo $shipping->postcode; ?>" size="20">

                                    </div>

                                    <div id="shipping_country_container" class="ct-div-block">

                                        <label for="shipping_country" class="title-font"><?php _e( 'Country', 'woomulti' );?></label>
                                        <input type="text" name="shipping_country" id="shipping_country" class="input" value="<?php echo $shipping->country; ?>" size="20">

                                    </div>

                                </div>

                                <div id="shipping_submit_container" class="ct-div-block">

                                    <mark class="alert"></mark>

                                    <input type="submit" name="UpdateShippingAddress" id="UpdateShippingAddress" class="UpdateShippingAddress button button-primary" value="<?php _e( 'Update Address', 'woomulti' );?>" data-siteid="<?php echo $siteID; ?>" data-orderid="<?php echo $orderID; ?>">

                                </div>

                            </form>

                        </div>

<?php
}
?>

                    </div>

                </div>

            </div>

        </div>

        <?php

    }// end order preview function





    /**
     * change the billing address for a given order $orderID
     */

    public static function gbwm_order_billing_address_update($siteID, $orderID, $first_name, $last_name, $company, $address_1, $address_2, $city, $state, $postcode, $country)
    {
        // load woocommerce and make it global
        global $woocommerce;

        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_sites';

        $WooSitesSQL = 'SELECT * FROM '. $table_name .' WHERE id='.$siteID;

        // now do the database call
        $WooSiteResult = $wpdb->get_row( $WooSitesSQL );

        $woocommerceAPI = new Client(
            $WooSiteResult->url,
            $WooSiteResult->Consumer_key,
            $WooSiteResult->Consumer_secret,
            [
                'wp_api' => true,
                'verify_ssl' => false, //$UseSSL
                'version' => 'gbwm/v1',
                //'version' => 'wc/v2',
                'query_string_auth' => true,
                'validate_url'      => false,
                //'return_as_array' => true,
            ]

        );// end new client

        try {

        // make the api call and send $data

        $data = array(
            'siteID'        => $siteID,
            'orderID'       => $orderID,
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'company'       => $company,
            'address_1'     => $address_1,
            'address_2'     => $address_2,
            'city'          => $city,
            'state'         => $state,
            'postcode'      => $postcode,
            'country'       => $country,
        );

        $UpdateBillingAddress = $woocommerceAPI->post('UpdateBillingAddress', $data);

        $lastRequest   = $woocommerceAPI->http->getRequest();
        $lastResponse   = $woocommerceAPI->http->getResponse();
        $headers        = $lastResponse->getHeaders();

    // if there is an api error catch and display
    } catch (HttpClientException $e)
    {
        $return = array(
            'message'  => $e->getMessage(),
            'id'       => 0
        );

        wp_send_json($return);
        die();

    }

        $return = array(
            'message'  => __('Billing address updated', 'woomulti'),
            'id'       => 1
        );

        wp_send_json($return);
        die();

    }// end change the billing address for a given order $orderID





    /**
     * change the shipping address for a given order $orderID
     */

    public static function gbwm_order_shipping_address_update($siteID, $orderID, $first_name, $last_name, $company, $address_1, $address_2, $city, $state, $postcode, $country)
    {
        // load woocommerce and make it global
        global $woocommerce;

        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_sites';

        $WooSitesSQL = 'SELECT * FROM '. $table_name .' WHERE id='.$siteID;

        // now do the database call
        $WooSiteResult = $wpdb->get_row( $WooSitesSQL );

        $woocommerceAPI = new Client(
            $WooSiteResult->url,
            $WooSiteResult->Consumer_key,
            $WooSiteResult->Consumer_secret,
            [
                'wp_api' => true,
                'verify_ssl' => false, //$UseSSL
                'version' => 'gbwm/v1',
                //'version' => 'wc/v2',
                'query_string_auth' => true,
                'validate_url'      => false,
                //'return_as_array' => true,
            ]

        );// end new client

        try {

        // make the api call and send $data

        $data = array(
            'siteID'        => $siteID,
            'orderID'       => $orderID,
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'company'       => $company,
            'address_1'     => $address_1,
            'address_2'     => $address_2,
            'city'          => $city,
            'state'         => $state,
            'postcode'      => $postcode,
            'country'       => $country,
        );

        $UpdateShippingAddress = $woocommerceAPI->post('UpdateShippingAddress', $data);

        $lastRequest   = $woocommerceAPI->http->getRequest();
        $lastResponse   = $woocommerceAPI->http->getResponse();
        $headers        = $lastResponse->getHeaders();

    // if there is an api error catch and display
    } catch (HttpClientException $e)
    {
        $return = array(
            'message'  => $e->getMessage(),
            'id'       => 0
        );

        wp_send_json($return);
        die();

    }

        $return = array(
            'message'  => __('Shipping address updated', 'woomulti'),
            'id'       => 1
        );

        wp_send_json($return);
        die();

    }// end change the shipping address for a given order $orderID


} // end of OrdersFunctions class