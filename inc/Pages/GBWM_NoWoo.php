<?php
/**
 * @package GenBuz WooMulti
 */

namespace GBWM_Inc\Pages;

class GBWM_NoWoo {

    public static function gbwm_nowoo()
    {
        ?>
        <div class="wrap gbwm-nowoo">

            <h1 class="hideH1"></h1>

            <div class="orders-title bluebg title-font">
                <span><?php _e( 'WooCommerce Required', 'woomulti' );?></span>
            </div>

            <div style="text-align:center;font-size:18px;"><?php _e( 'Im sorry but we did not detect WooCommerce Installed and Activated.', 'woomulti' );?></div>

            <br/><br/>

            <div style="text-align:center;font-size:18px;"><?php _e( 'WooCommerce needs to be Installed and Activated for WooMulti to function, please install and activate WooCommerce.', 'woomulti' );?></div>

        </div><?php // end wrap ?>

<?php
    }// end gbwm_nowoo function

}// end class