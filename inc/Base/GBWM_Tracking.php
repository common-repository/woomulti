<?php
/**
 * @package GenBuz WooMulti
 */

namespace GBWM_Inc\Base;

class GBWM_Tracking
{
    public function gbwm_register()
    {
        /**
         * show tracking details if set in the my-account > orders > view site section.
         */
        add_action( 'woocommerce_order_details_after_order_table', array($this, 'gbwm_tracking_field_order' ), 10, 1 );


        /**
         * show tracking details in the email if set.
         */
        add_action( 'woocommerce_email_after_order_table', array($this, 'gbwm_tracking_field_email'), 15, 4 );
    }
    

    public function gbwm_tracking_field_order($order){

        @ $tracking_number = get_post_meta($order->id , 'gbwm_tracking_number' , true );

        @ $tracking_url = get_post_meta($order->id , 'gbwm_tracking_url' , true );

        if(! empty($tracking_number) && ! empty($tracking_url)) {

        ?>

        <h2 class="woocommerce-order-details__title"><?php _e( 'Tracking Information', 'woomulti' );?></h2>

        <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
            <tbody>
                <tr class="woocommerce-table__line-item order_item">

                    <td class="woocommerce-table__tracking-number tracking-number" width="50%">
                        <strong><?php _e( 'Tracking Number', 'woomulti' );?></strong>
                    </td>
                    
                    <td class="woocommerce-table__tracking-number tracking-number" width="50%">
                        <strong><?php echo $tracking_number;?></strong>
                    </td>
                    
                </tr>
                
                <tr class="woocommerce-table__line-item order_item">

                    <td class="woocommerce-table__tracking-url tracking-url" width="50%">
                        <strong><?php _e( 'Tracking Link', 'woomulti' );?></strong>
                    </td>
                    
                    <td class="woocommerce-table__tracking-url tracking-url" width="50%">
                        <strong><a href="<?php echo $tracking_url;?>" target="_blank"><?php echo $tracking_url;?></a></strong>
                    </td>
                    
                </tr>
            </tbody>
        </table>
    <?php
        }
    }// end account function

    

    public function gbwm_tracking_field_email( $order, $sent_to_admin, $plain_text, $email ) {

        @ $tracking_number = get_post_meta($order->id , 'gbwm_tracking_number' , true );
        @ $tracking_url = get_post_meta($order->id , 'gbwm_tracking_url' , true );

        if(! empty($tracking_number) && ! empty($tracking_url)) {
        ?>

        <h2 style="color: #96588a; display: block; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;"><?php _e( 'Tracking Information', 'woomulti' );?></h2>

        <table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding: 0;" border="0">
            <tbody>
                <tr>
                    <td class="td" style="text-align: left; border-top-width: 4px; color: #636363; border: 1px solid #e5e5e5; padding: 12px;" width="50%"><?php _e( 'Tracking Number', 'woomulti' );?></td>
                    
                    <td class="td" style="text-align: left; border-top-width: 4px; color: #636363; border: 1px solid #e5e5e5; padding: 12px;" width="50%"><?php echo $tracking_number;?></td>
                </tr>
                <tr>
                    <td class="td" style="text-align: left; border-top-width: 4px; color: #636363; border: 1px solid #e5e5e5; padding: 12px;" width="50%"><?php _e( 'Tracking Link', 'woomulti' );?></td>
                    
                    <td class="td" style="text-align: left; border-top-width: 4px; color: #636363; border: 1px solid #e5e5e5; padding: 12px;" width="50%"><a href="<?php echo $tracking_url;?>" target="_blank"><?php echo $tracking_url;?></a></td>
                </tr>
            </tbody>
        </table>

        <?php

        }

    }// end email function

}