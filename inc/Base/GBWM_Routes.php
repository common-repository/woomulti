<?php
/**
 * @package GenBuz WooMulti
 */

namespace GBWM_Inc\Base;

class GBWM_Routes
{
    public function gbwm_register()
    {
        // custom rest route
        add_action( 'rest_api_init', array( $this, 'gbwm_routes' ) );
    }

    
    // afax function callback
    public function gbwm_routes()
    {
        /**
         * register_rest_route syntax
         * 
         * uniquename/v?, /whatever name you want (singleword)/id(code)
         * 
         * Methods
         * 
         * READABLE = 'GET'
         * CREATABLE = 'POST'
         * EDITABLE = 'POST, PUT, PATCH'
         * DELETABLE = 'DELETE'
         * ALLMETHODS = 'GET, POST, PUT, PATCH, DELETE'
         */
    
        register_rest_route( 'gbwm/v1', '/UpdateBillingAddress', array(
            'methods' => 'POST',
            'callback' => array( $this, 'gbwm_update_billing_address')
        ) );
    
        register_rest_route( 'gbwm/v1', '/UpdateShippingAddress', array(
            'methods' => 'POST',
            'callback' => array( $this, 'gbwm_update_shipping_address')
        ) );

    }





    /**
     * this is the UpdateBillingAddress custom endpoint
     */
    public function gbwm_update_billing_address( \WP_REST_Request $request )
    {
        // get data
        $siteID     = $request['siteID'];
        $orderID    = $request['orderID'];
    
        // if there is no siteID or orderID
        if ( empty( $siteID ) || empty( $orderID ) ) {
    
            // now return
            return rest_ensure_response( __( 'Failed','woomulti' ) );
    
        }else{// if siteID and orderID exist
    
            // update the billing address for this order
            update_post_meta( $orderID, '_billing_first_name', $request['first_name'] );
            update_post_meta( $orderID, '_billing_last_name', $request['last_name'] );
            update_post_meta( $orderID, '_billing_company', $request['company'] );
            update_post_meta( $orderID, '_billing_address_1', $request['address_1'] );
            update_post_meta( $orderID, '_billing_address_2', $request['address_2'] );
            update_post_meta( $orderID, '_billing_city', $request['city'] );
            update_post_meta( $orderID, '_billing_state', $request['state'] );
            update_post_meta( $orderID, '_billing_postcode', $request['postcode'] );
            update_post_meta( $orderID, '_billing_country', $request['country'] );
    
            // now return
            return rest_ensure_response( __( 'Success','woomulti' ) );
        }
    }// end gbwm_update_billing_address funtion





    /**
     * this is the UpdateShippingAddress custom endpoint
     */
    public function gbwm_update_shipping_address( \WP_REST_Request $request )
    {
        // get data
        $siteID     = $request['siteID'];
        $orderID    = $request['orderID'];
    
        // if there is no siteID or orderID
        if ( empty( $siteID ) || empty( $orderID ) ) {
    
            // now return
            return rest_ensure_response( __( 'Failed','woomulti' ) );
    
        }else{// if siteID and orderID exist
    
            // update the shipping address for this order
            update_post_meta( $orderID, '_shipping_first_name', $request['first_name'] );
            update_post_meta( $orderID, '_shipping_last_name', $request['last_name'] );
            update_post_meta( $orderID, '_shipping_company', $request['company'] );
            update_post_meta( $orderID, '_shipping_address_1', $request['address_1'] );
            update_post_meta( $orderID, '_shipping_address_2', $request['address_2'] );
            update_post_meta( $orderID, '_shipping_city', $request['city'] );
            update_post_meta( $orderID, '_shipping_state', $request['state'] );
            update_post_meta( $orderID, '_shipping_postcode', $request['postcode'] );
            update_post_meta( $orderID, '_shipping_country', $request['country'] );
    
            // now return
            return rest_ensure_response( __( 'Success','woomulti' ) );
        }
    }// end gbwm_update_shipping_address funtion

}// end class