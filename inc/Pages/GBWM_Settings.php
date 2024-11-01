<?php
/**
 * @package GenBuz WooMulti
 */

namespace GBWM_Inc\Pages;

class GBWM_Settings {

    public static function gbwm_settings() {

    ?>
        <div class="wrap gbwm-settings">

            <h1 class="hideH1"></h1>

            <div class="orders-title bluebg">
                <span>GenBuz WooMulti <?php _e( 'Settings', 'woomulti' );?></span>
            </div>

            <?php settings_errors(); ?>

            <form method="POST" action="options.php">
                <?php
                    settings_fields( 'gbwm_settings_group' );

                    $gbwm_settings = get_option( 'gbwm_plugin_settings' );

                    $pagination_options = array( 10, 30, 50, 100 );

                    $yes_no_options = array( __( 'Yes', 'woomulti' ), __( 'No', 'woomulti' ) );
                ?>
                <table class="widefat gbwmtable settings" cellspacing="0" style="">

                <thead>
                    <tr class="gbwm-table-header bluebg">

                        <th scope="col" id="gbwm-settings-setting" class="manage-column column-primary"><?php _e( 'Setting', 'woomulti' );?></th>

                        <th scope="col" id="gbwm-settings-description" class="manage-column column-primary"><?php _e( 'Description', 'woomulti' );?></th>

                    </tr>
                </thead>

                <thead>
                    <tr class="gbwm-table-header-grey">

                        <th scope="col" class="manage-column column-primary" colspan="2"><?php _e( 'Orders Settings', 'woomulti' );?></th>

                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td class="settings-setting">

                            <select name="gbwm_plugin_settings[gbwm_pagination]" id="gbwm_pagination">

                            <?php
                            foreach( $pagination_options as $po ){

                                if( empty( $gbwm_settings['gbwm_pagination'] ) )
                                {

                                    echo '<option value="'.$po.'">'.$po.'</option>';

                                }else{

                                    echo '<option value="'.$po.'" '.selected( $gbwm_settings['gbwm_pagination'], $po, false ).'>'.$po.'</option>';

                                }
                            }
                            ?>
                            </select>

                        </td>

                        <td class="settings-description">

                            <h3><?php _e( 'Pagination', 'woomulti' );?></h3>
                            <p><?php _e( 'How many results per page should the plugin get, for example should it show', 'woomulti' );?> <strong>10, 30, 50</strong> <?php _e( 'etc orders per page.', 'woomulti' );?></p>

                        </td>
                    </tr>

                <thead>
                    <tr class="gbwm-table-header-grey">

                        <th scope="col" class="manage-column column-primary" colspan="2"><?php _e( 'Couriers Settings', 'woomulti' );?></th>

                    </tr>
                </thead>
                    <tr>
                        <td class="settings-setting border-bottom-settings">
                            <select name="gbwm_plugin_settings[gbwm_enable_tracking]" id="gbwm_enable_tracking">

                                <?php
                                foreach( $yes_no_options as $et ){

                                    if( empty( $gbwm_settings['gbwm_enable_tracking'] ) )
                                    {

                                        echo '<option value="'.$et.'">'.$et.'</option>';

                                    }else{

                                        echo '<option value="'.$et.'" '.selected( $gbwm_settings['gbwm_enable_tracking'], $et, false ).'>'.$et.'</option>';

                                    }
                                }
                                ?>

                            </select>

                        </td>

                        <td class="settings-description border-bottom-settings">

                            <h3><?php _e( 'Enable Tracking', 'woomulti' );?></h3>
                            <p><?php _e( 'If you would like to add tracking information to emails sent to your customers set this option to', 'woomulti' );?> "<strong><?php _e( 'Yes', 'woomulti' );?></strong>", <?php _e( 'to send tracking information to connected sites the connected sites must have the', 'woomulti' );?> "<strong>WooSite</strong>" <?php _e( 'plugin installed.', 'woomulti' );?></p>

                        </td>
                    </tr>



                    <?php
                        // if there are couriers added

                        // load wordpress database object and make it global
                        global $wpdb;

                        // table name
                        $table_name = $wpdb->prefix.'gbwm_couriers';

                        // sql
                        $SQL = 'SELECT * FROM '. $table_name;

                        // now do the database call
                        $couriers = $wpdb->get_results( $SQL );
                    ?>

                    <tr>
                        <td class="settings-setting">
                            <select name="gbwm_plugin_settings[gbwm_default_courier]" id="gbwm_default_courier">

                                <?php

                                    // no tracking
                                    echo '<option value="0">'. __('No Tracking', 'woomulti') .'</option>';

                                foreach( $couriers as $courier ){

                                    if( empty( $gbwm_settings['gbwm_default_courier'] ) )
                                    {
                                        echo '<option value="'.$courier->id.'">'.$courier->title.'</option>';

                                    }else{

                                        echo '<option value="'.$courier->id.'" '.selected( $gbwm_settings['gbwm_default_courier'], $courier->id, false ).'>'.$courier->title.'</option>';

                                    }
                                }
                                ?>

                            </select>

                        </td>

                        <td class="settings-description">

                            <h3><?php _e( 'Default Courier', 'woomulti' );?></h3>
                            <p><?php _e( 'If you wish you can add a default courier, when selected the default courier will be the "Selected" option in the courier drop down option when adding tracking.', 'woomulti' );?></p>

                        </td>
                    </tr>
                    <thead>
                        <tr class="gbwm-table-header-grey">

                            <th scope="col" class="manage-column column-primary" colspan="2">Downloads Settings</th>

                        </tr>
                    </thead>
                    <tr class="">
                        <td class="settings-setting border-bottom-settings">
                            <select name="gbwm_plugin_settings[gbwm_enable_downloads]" id="gbwm_enable_downloads">

                                <?php

                                $gbwm_settings = get_option( 'gbwm_plugin_settings' );

                                $yes_no_options = array('Yes', 'No');

                                foreach($yes_no_options as $et)
                                {
                                    if(empty($gbwm_settings['gbwm_enable_downloads'])){

                                        echo '<option value="'.$et.'">'.$et.'</option>';

                                    }else{

                                        echo '<option value="'.$et.'" '.selected( $gbwm_settings['gbwm_enable_downloads'], $et, false ).'>'.$et.'</option>';

                                    }
                                }
                                ?>

                            </select>

                        </td>

                        <td class="settings-description border-bottom-settings">

                            <h3>Enable Downloads</h3>
                            <p>If you would like to download orders then set this option to "<strong>Yes</strong>".</p>

                        </td>
                    </tr>
                    <tr class="">
                        <td class="settings-setting">
                            <select name="gbwm_plugin_settings[gbwm_downloads_retention]" id="gbwm_downloads_retention">

                                <?php

                                $retention_options = array(
                                    '1 Week'    => 7,
                                    '2 Weeks'   => 14,
                                    '1 Month'   => 30,
                                    '2 Months'  => 60,
                                    '3 Months'  => 90,
                                    '6 Months'  => 180,
                                    '1 Year'    => 365
                                );

                                foreach($retention_options as $label => $et){

                                    if(empty($gbwm_settings['gbwm_downloads_retention'])){

                                        echo '<option value="'.$et.'">'.$label.'</option>';

                                    }else{

                                        echo '<option value="'.$et.'" '.selected( $gbwm_settings['gbwm_downloads_retention'], $et, false ).'>'.$label.'</option>';

                                    }
                                }
                                ?>

                            </select>

                        </td>

                        <td class="settings-description">

                            <h3>Retention</h3>
                            <p>How long should the order download files be be available on this system, it is recomeneded to "<strong>Clean Up</strong>" your files as you can run out of space on shared hosting accounts, files older than this will be automatically deleted from your system.</p>

                        </td>
                    </tr>


                    <?php
                        // hook for other extensions to add settings
                        do_action( 'gbwm_add_settings' );
                    ?>
                    </tbody>

                    <tfoot id="major-publishing-actions">
                        <tr>
                            <th colspan="2">
                                <?php
                                    submit_button();
                                ?>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </form>

        </div>
<?php
    }
}