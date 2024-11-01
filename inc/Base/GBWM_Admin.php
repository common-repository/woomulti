<?php

 /**
 * @package GenBuz WooMulti
 */

namespace GBWM_Inc\Base;

class GBWM_Admin
{
    public $plugin;

    public function gbwm_register()
    {
        //Activate Custom Settings on admin_init ready for use on the settings page.
        add_action( 'admin_init', array( $this, 'gbwm_admin_init' ) );        
    }


    /**
     * admin_init function.
     */

    public function gbwm_admin_init()
    {
        // name_for_form, name_for_database
        register_setting( 'gbwm_settings_group', 'gbwm_plugin_settings' );
    }

}