<?php
/**
 * @package GenBuz WooMulti
 */

namespace GBWM_Inc\Pages;

class GBWM_Couriers {

    public static function gbwm_couriers()
    {

        /**
         * display the current couriers urls and also add the option to add more couriers
         * */

        ?>
        <div class="wrap gbwm-couriers">

            <h1 class="hideH1"></h1>

            <div class="orders-title bluebg title-font">
                <span><?php _e( 'Manage Couriers', 'woomulti' );?></span>
            </div>

            <?php
                $results = self::gbwm_get_sites();

                //echo WMD_PLUGIN_PATH;
            ?>

            <div class="alert" style="display: none;">
                <span class="closebtn">&times;</span>
                <span class="ajaxMessage"></span>
            </div>

            <button type="submit" name="submit" id="AddFormButton" class="button button-primary button-large title-font" value="Add Site"><?php _e( 'Add Courier', 'woomulti' );?></button>

            <div class="addcourier-container" style="display:none;">

                <div class="addcourier-header bluebg title-font">
                    <div class="addcourier-header-child addcourier-title"><?php _e( 'Courier Name', 'woomulti' );?></div>
                    <div class="addcourier-header-child addcourier-url"><?php _e( 'Courier Website', 'woomulti' );?></div>
                    <div class="addcourier-header-child addcourier-active"><?php _e( 'Active', 'woomulti' );?></div>
                </div>

                <div class="addcourier-body blue">

                    <div class="addcourier-body-child addcourier-title">

                        <input class="CourierTitle" type="text" name="CourierTitle" id ="CourierTitle" placeholder="<?php _e( 'Courier Name', 'woomulti' );?>">

                        <span class="CourierTitleValidate" style="display: none"><?php _e( 'Courier Name is required!', 'woomulti' );?></span>

                    </div>

                    <div class="addcourier-body-child addcourier-url">

                        <input class="CourierURL" type="text" name="CourierURL" id="CourierURL" placeholder="<?php _e( 'Courier Website', 'woomulti' );?>">

                        <span class="CourierURLValidate" style="display: none"><?php _e( 'Courier Website is required!', 'woomulti' );?></span>


                    </div>

                    <div class="addcourier-body-child addcourier-active">

                        <select id="CourierActive" class="SiteAdd" name="CourierActive">
                            <option value="1"><?php _e( 'Yes', 'woomulti' );?></option>
                            <option value="0"><?php _e( 'No', 'woomulti' );?></option>
                        </select>

                    </div>

                </div>

                <div class="addcourier-footer title-font">
                    <input type="submit" name="submit" id="AddCourier" class="button button-primary button-large" value="Add Courier">

                    <input type="submit" name="cancel" id="CancelCourier" class="button button-info button-large" value="Close Form">
                </div>

            </div>

            <p><?php _e( 'Below are all the couriers you currently have connected to this system.', 'woomulti' );?></p>

            <table class="widefat gbwmtable manage-couriers">
                <thead>
                <tr class="gbwm-table-header bluebg title-font">
                    <th scope="col" id="title" class="manage-column column-primary CourierTitle"><?php _e( 'Courier Name', 'woomulti' );?></th>

                    <th scope="col" id="url" class="manage-column CourierURL"><?php _e( 'Courier Website', 'woomulti' );?></th>

                    <th scope="col" id="active" class="manage-column CourierActive"><?php _e( 'Active', 'woomulti' );?></th>

                    <th scope="col" id="actions" class="manage-column column-actions gbwm-order-list-actions"><?php _e( 'Actions', 'woomulti' );?></th>
                </tr>
                </thead>

                <tbody>
                    <?php

                        // used for alternating row background color
                        $c = true;

                        // if there are no results
                        if($results == null){
                            ?>
                            <tr class="format-standard wpautop">
                                <td class="manage-column NoSites bold center" data-colname="NoSites" colspan="4"><?php _e( 'No couriers Added Yet', 'woomulti' );?></td>
                            </tr>
                        <?php
                        }else{

                        foreach ($results as $courier) {

                            /**
                             * here is how this code works
                             * $c = starts true (Boolean) (set above loop)
                             *
                             * $c=!$c MEANS IT WAS TRUE BUT NOW FALSE (TOGGLE TRUE/FALSE)
                             *
                             * ( ( $c=TOGGLE TRUE OR FALSE )? 'IF TRUE' : 'IF FALSE' )
                             */
                            $altrow = ( ( $c=!$c )? ' alt-row' : '' );

                            $ActiveStatus = $courier->active;

                            if($ActiveStatus == 0){
                                $ButtonType = 'danger';
                                $dataVal = 0;
                                $icon = 'times-circle';
                            }elseif($ActiveStatus == 1){
                                $ButtonType = 'success';
                                $dataVal = 1;
                                $icon = 'check-circle';
                            };
                    ?>
                    <tr id="Courier<?php echo $courier->id; ?>" class="format-standard wpautop<?php echo $altrow; ?>">

                        <td class="manage-column CourierTitle" data-colname="CourierTitle"><?php echo $courier->title;?></td>

                        <td class="manage-column CourierURL" data-colname="CourierURL"><?php echo $courier->url;?></td>

                        <td class="manage-column CourierActive" data-colname="CourierActive" data-val="<?php echo $dataVal; ?>"><button type="button" class="btn btn-<?php echo $ButtonType; ?> btn-xs CourierStatus <?php echo $courier->id;?>" data-siteid="<?php echo $courier->id;?>" value="<?php echo $ActiveStatus; ?>"><i class="icon fas fa-<?php echo $icon;?>"></i></button></td>

                        <td class="manage-column gbwm-order-list-actions" data-colname="Actions">

                            <button type="button" class="btn btn-info btn-xs CourierEdit" data-siteid="<?php echo $courier->id;?>" id="CourierEdit" data-target="#ModalEditCourier" data-toggle="modal"><i class="icon fas fa-pen"></i></button>

                            <button type="button" class="btn btn-danger btn-xs confirmation" data-siteid="<?php echo $courier->id;?>" id="CourierDelete"><i class="icon fas fa-trash-alt"></i></button>

                        </td>
                    </tr>
                    <?php
                        }// end foreach
                    }// end else results
                    ?>
                </tbody>

                <tfoot>
                <tr>
                    <th colspan="5" class="tbfooter title-font"><?php _e( 'Total Couriers', 'woomulti' );?>: <span class="totalSites"><?php echo $GLOBALS['resultCount']; ?></span></th>
                </tr>
                </tfoot>

            </table>

            <div class="clear"></div>

        </div><?php // end wrap ?>




<!-- modal content -->
<div id="ModalEditCourier">

    <h4 class="modal-title center title-font"><?php _e( 'Edit Courier Details', 'woomulti' );?></h4>

    <div class="closeForm" style="display: none; text-align: center;">

        <p class="ajaxMessage"></p>

        <button type="button" class="btn btn-info btn-xs" onClick="window.location.reload()"><?php _e( 'Close', 'woomulti' );?></button>

    </div>

    <div class="statusForm">

        <p><?php _e( 'use the form below to edit the details of this courier', 'woomulti' );?>.</p>

        <form action="" method="post">

            <div class="form-group">

                <input type="hidden" class="form-control" name="siteID" value="">

                <label for="CourierTitle"><?php _e( 'Courier Title', 'woomulti' );?>:</label>
                <input type="text" class="form-control" name="CourierTitle" value="">

                <label for="CourierURL"><?php _e( 'Courier Website', 'woomulti' );?>:</label>
                <input type="text" class="form-control" name="CourierURL" value="">

                <label for="CourierActive"><?php _e( 'Active', 'woomulti' );?>:</label>
                <select class="form-control" name="CourierActive" id="CourierActive">
                    <option value="0"><?php _e( 'No', 'woomulti' );?></option>
                    <option value="1"><?php _e( 'Yes', 'woomulti' );?></option>
                </select>

            </div>

            <div class="modal-footer title-font">

                <button type="submit" class="btn btn-secondary submit simplemodal-close" name="btn-close"><?php _e( 'Close', 'woomulti' );?></button>

                <button type="submit" class="btn btn-info submit ModalEditCourierSubmit" name="btn-update" data-siteid="0"><?php _e( 'Update', 'woomulti' );?></button>

            </div>

        </form>

    </div>

        <div class="clear"></div>

</div>
<?
    }




    /**
    * Retrieve sites data from the database
    *
    * @param int $per_page
    * @param int $page_number
    *
    * @return mixed
    */

    public static function gbwm_get_sites( $per_page = 100, $page_number = 1 ) {

        $resultCount = 0;

        global $wpdb;
        global $resultCount;

        $table_name = $wpdb->prefix.'gbwm_couriers';

        $per_page     = 100;
        $page_number = 1;
        //$total_items  = 100;

        $active = 0;

        // sql to call
        $sqlCount = 'SELECT count(*) FROM '. $table_name;

        // now do the database call
        $resultCount = $wpdb->get_var( $sqlCount );

        if($active === 1){

            $SQLFilter = ' WHERE active='.$active;

        }else{

            $SQLFilter = '';

        }

        // sql to call
        $sql = 'SELECT * FROM '. $table_name.$SQLFilter;

        // do ordering if any
        $sql .= ' LIMIT '. $per_page;
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

        // now do the database call
        $result = $wpdb->get_results( $sql );

        return $result;
    }


}