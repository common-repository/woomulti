<?php

 /**
 * @package GenBuz WooMulti
 */

namespace GBWM_Inc\Base;

class GBWM_SettingsLink
{
    public function gbwm_register()
    {
        // display settings link on the plugins page

        // setup the plugin page "settings" link
        add_filter( 'plugin_action_links_' . GBWM_PLUGIN_NAME, array( $this, 'gbwm_settingslink' ) );
    }


    // no output allowed in this function
    // add settings page link to the plugins page
    public function gbwm_settingslink( array $links )
    {
        $settings_link = '<a href="admin.php?page=gbwm_settings">'.__('Settings', 'woomulti').'</a>';
        array_push( $links, $settings_link );

        // now return the link
        return $links;
    }

}// end class