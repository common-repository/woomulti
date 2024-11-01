<?php
/**
 * @package GenBuz WooMulti
 */

namespace GBWM_Inc\Pages;

class GBWM_Sites {

    public static function gbwm_sites()
    {

        // current page NOT slug, eg admin.php
        global $pagenow;

        // gets the sites main url without traling slash
        $ThisSiteURL = get_option( 'siteurl' );

        // slug for current page, eg manage-sites
        $slug = $_GET['page'];

        ?>
        <div class="wrap gbwm-sites">

            <h1 class="hideH1"></h1>

            <div class="orders-title bluebg title-font">
                <span><?php _e( 'Manage Woocommerce Sites', 'woomulti' );?></span>
            </div>

            <div class="alert" style="display: none;">
                <span class="closebtn">&times;</span>
                <span class="ajaxMessage"></span>
            </div>

            <button type="submit" name="submit" id="AddFormButton" class="button button-primary button-large title-font" value="<?php _e( 'Add Site', 'woomulti' );?>"><?php _e( 'Add Site', 'woomulti' );?></button>

            <div class="addsite-container" style="display: none;">

                <div class="addsites-message center">
                    <p><?php _e( 'WooCommerce needs to be installed on the site you add here, you must also have already created the Consumer Key and Consumer Secret, if this is your first time setting up a Consumer Key and Consumer Secret you can find out how to do that on our', 'woomulti' );?> <a href="<?php admin_url();?>admin.php?page=gbwm_help" target="_self"><?php _e( 'Help Page', 'woomulti' );?></a></p>
                </div>

                <div class="addsite-header bluebg title-font">
                    <div class="addsite-header-child addsite-url"><?php _e( 'URL', 'woomulti' );?> <span class="SmallText">(<?php _e( 'MUST BE UNIQUE', 'woomulti' );?>)</span></div>
                    <div class="addsite-header-child addsite-ck"><?php _e( 'Consumer key', 'woomulti' );?></div>
                    <div class="addsite-header-child addsite-cs"><?php _e( 'Consumer secret', 'woomulti' );?></div>
                    <div class="addsite-header-child addsite-woosite"><?php _e( 'WooSite Installed', 'woomulti' );?></div>
                    <div class="addsite-header-child addsite-active"><?php _e( 'Active', 'woomulti' );?></div>
                </div>


                <div class="addsite-body blue">

                    <div class="addsite-body-child addsite-url">

                        <input class="SiteAdd" type="text" name="SiteURL" id="SiteURL" placeholder="<?php _e( 'http://www.example.com', 'woomulti' );?>">

                        <span class="SiteURLValidate" style="display: none"><?php _e( 'Website URL is required!', 'woomulti' );?></span>
                    </div>

                    <div class="addsite-body-child addsite-ck">

                        <input class="SiteAdd" type="text" name="SiteCK" id="SiteCK" placeholder="<?php _e( 'Enter Consumer Key', 'woomulti' );?>">

                        <span class="SiteCKValidate" style="display: none"><?php _e( 'Consumer key is required!', 'woomulti' );?></span>

                    </div>

                    <div class="addsite-body-child addsite-cs">

                        <input class="SiteAdd" type="text" name="SiteCS" id="SiteCS"  placeholder="<?php _e( 'Enter Consumer secret', 'woomulti' );?>">

                        <span class="SiteCSValidate" style="display: none"><?php _e( 'Consumer secret is required!', 'woomulti' );?></span>

                    </div>

                    <div class="addsite-body-child addsite-woosite">

                        <select id="SiteWooSite" class="SiteAdd" name="SiteWooSite">
                            <option value="1"><?php _e( 'Yes', 'woomulti' );?></option>
                            <option value="0"><?php _e( 'No', 'woomulti' );?></option>
                        </select>

                    </div>

                    <div class="addsite-body-child addsite-active">

                        <select id="SiteActive" class="SiteAdd" name="SiteActive">
                            <option value="1"><?php _e( 'Yes', 'woomulti' );?></option>
                            <option value="0"><?php _e( 'No', 'woomulti' );?></option>
                        </select>

                    </div>

                </div>

                <div class="addsite-footer title-font">
                    <input type="submit" name="submit" id="AddSite" class="button button-primary button-large" value="<?php _e( 'Add Site', 'woomulti' );?>">

                    <input type="submit" name="cancel" id="CancelSite" class="button button-info button-large" value="<?php _e( 'Close Form', 'woomulti' );?>">
                </div>

            </div>


            <p><?php _e( 'Below are all the WooCommerce sites you currently have connected to this system.', 'woomulti' );?></p>


            <table class="widefat gbwmtable manage-sites">
                <thead>
                <tr class="gbwm-table-header bluebg title-font">
                    <th scope="col" id="url" class="manage-column column-primary SiteURL"><?php _e( 'Site', 'woomulti' );?></th>

                    <th scope="col" id="Consumer_key" class="manage-column SiteCK"><?php _e( 'Consumer key', 'woomulti' );?></th>

                    <th scope="col" id="Consumer_secret" class="manage-column SiteCS"><?php _e( 'Consumer secret', 'woomulti' );?></th>

                    <th scope="col" id="SiteWooSite" class="manage-column SiteWooSite">WooSite</th>

                    <th scope="col" id="SiteActive" class="manage-column SiteActive"><?php _e( 'Active', 'woomulti' );?></th>

                    <th scope="col" id="actions" class="manage-column column-actions gbwm-actions"><?php _e( 'Actions', 'woomulti' );?></th>
                </tr>
                </thead>

                <tbody>
                    <?php

                    /**
                    * Retrieve sites data from the database
                    *
                    * @param int $per_page
                    * @param int $page_number
                    *
                    * @return mixed
                    */

                    $resultCount = 0;

                    $results = self::gbwm_get_sites();

                    // used for alternating row background color
                    $c = true;

                    // if there are no results
                    if($results == null){

                    ?>
                    <tr class="format-standard wpautop">
                        <td class="manage-column NoSites bold center" data-colname="NoSites" colspan="5"><?php _e( 'No Sites Added Yet', 'woomulti' );?></td>
                    </tr>
                    <?php
                    }else{

                        foreach ($results as $site) {

                            $rowbg = (($c=!$c)? ' alt-row' : '' );

                            $WooSiteStatus = $site->woosite;

                            $ActiveStatus = $site->active;

                            // is woosite installed?
                            if($WooSiteStatus == 0){
                                $WooActive = 'No';
                                $WoodataVal = 0;
                                $Wooicon = 'times-circle';
                                $WooButtonType = 'danger';
                            }elseif($WooSiteStatus == 1){
                                $WooActive = 'Yes';
                                $WoodataVal = 1;
                                $Wooicon = 'check-circle';
                                $WooButtonType = 'success';
                            };

                            // is the site active?
                            if($ActiveStatus == 0){
                                $Active = 'No';
                                $dataVal = 0;
                                $icon = 'times-circle';
                                $ButtonType = 'danger';
                            }elseif($ActiveStatus == 1){
                                $Active = 'Yes';
                                $dataVal = 1;
                                $ButtonType = 'success';
                                $icon = 'check-circle';
                            };
                    ?>
                    <tr id="Site<?php echo $site->id; ?>" class="format-standard wpautop<?php echo $rowbg; ?>">
                        <td class="manage-column SiteURL" data-colname="SiteURL"><?php echo $site->url;?></td>

                        <td class="manage-column SiteCK" data-colname="SiteCK"><?php echo $site->Consumer_key; ?></td>

                        <td class="manage-column SiteCS" data-colname="SiteCS"><?php echo $site->Consumer_secret; ?></td>

                        <td class="manage-column SiteWooSite" data-colname="SiteWooSite" data-val="<?php echo $WoodataVal; ?>">

                            <button type="button" class="btn btn-<?php echo $WooButtonType; ?> btn-xs WooSiteStatus <?php echo $site->id;?>" data-siteid="<?php echo $site->id;?>" value="<?php echo $WooSiteStatus; ?>"><i class="icon fas fa-<?php echo $Wooicon;?>"></i></button>

                        </td>

                        <td class="manage-column SiteActive" data-colname="SiteActive" data-val="<?php echo $dataVal; ?>">

                            <button type="button" class="btn btn-<?php echo $ButtonType; ?> btn-xs SiteStatus <?php echo $site->id;?>" data-siteid="<?php echo $site->id;?>" value="<?php echo $ActiveStatus; ?>"><i class="icon fas fa-<?php echo $icon;?>"></i></button>

                        </td>

                        <td class="manage-column gbwm-actions" data-colname="Actions">

                            <button type="button" class="btn btn-dark btn-xs SiteTemplate" data-siteid="<?php echo $site->id;?>">
                                <i class="icon fas fa-print"></i>
                            </button>

                            <button type="button" class="btn btn-info btn-xs SiteEdit" data-siteid="<?php echo $site->id;?>" id="SiteEdit" data-target="#ModalEditSite" data-toggle="modal">
                                <i class="icon fas fa-pen"></i>
                            </button>

                            <button type="button" class="btn btn-danger btn-xs confirmation" data-siteid="<?php echo $site->id;?>" id="SiteDelete">
                                <i class="icon fas fa-trash-alt"></i>
                            </button>

                        </td>
                    </tr>
                    <?php
                        }// end foreach
                    }// end else results
                    ?>
                </tbody>

                <tfoot>
                <tr>
                    <th colspan="6" class="tbfooter title-font"><?php _e( 'Total Sites:', 'woomulti' );?> <span class="totalSites"><?php echo $GLOBALS['resultCount']; ?></span></th>
                </tr>
                </tfoot>

            </table>

        </div>





<!-- modal content -->
<div id="ModalEditSite">

    <h4 class="modal-title center title-font"><?php _e( 'Edit Site Credentials', 'woomulti' );?></h4>

    <div class="closeForm" style="display: none; text-align: center;">

        <p class="ajaxMessage"></p>

        <button type="button" class="btn btn-info btn-xs" data-dismiss="modal" onClick="window.location.reload()"><?php _e( 'Close', 'woomulti' );?></button>

    </div>

    <div class="statusForm">

        <p><?php _e( 'use the form below to edit the details of this site.', 'woomulti' );?></p>

        <form action="" method="post">

            <div class="form-group">

                <input type="hidden" class="form-control" name="siteID" value="">

                <label for="SiteURL"><?php _e( 'Site URL:', 'woomulti' );?></label>
                <input type="text" class="form-control" name="SiteURL" value="">

                <label for="SiteCK"><?php _e( 'Consumer Key:', 'woomulti' );?></label>
                <input type="text" class="form-control" name="SiteCK" value="">

                <label for="SiteCS"><?php _e( 'Consumer Secret:', 'woomulti' );?></label>
                <input type="text" class="form-control" name="SiteCS" value="">

                <div class="ct-new-columns site-modal">

                    <div class="ct-div-block modal-woosite">

                        <label for="SiteWooSite"><?php _e( 'WooSite Installed?', 'woomulti' );?>:</label>
                        <select class="form-control" name="SiteWooSite" id="SiteWooSite">
                            <option value="0"><?php _e( 'No', 'woomulti' );?></option>
                            <option value="1"><?php _e( 'Yes', 'woomulti' );?></option>
                        </select>

                    </div>

                    <div class="ct-div-block modal-active">

                        <label for="SiteActive"><?php _e( 'Active', 'woomulti' );?>:</label>
                        <select class="form-control" name="SiteActive" id="SiteActive">
                            <option value="0"><?php _e( 'No', 'woomulti' );?></option>
                            <option value="1"><?php _e( 'Yes', 'woomulti' );?></option>
                        </select>

                    </div>

                </div>
            </div>

            <div class="modal-footer modal-title">

                <button type="submit" class="btn btn-secondary submit simplemodal-close" name="btn-close"><?php _e( 'Close', 'woomulti' );?></button>

                <button type="submit" class="btn btn-info submit ModalEditSiteSubmit" name="btn-update" data-siteid="0"><?php _e( 'Update', 'woomulti' );?></button>

            </div>

        </form>

    </div>

</div>

<?
    }// end sites function



    public static function gbwm_get_sites( $per_page = 20, $page_number = 1 ) {

        global $wpdb;
        global $resultCount;

        $table_name = $wpdb->prefix.'gbwm_sites';

        $per_page     = 20;
        $page_number = 1;
        $total_items  = 100;

        // sql to call
        $sqlCount = 'SELECT count(*) FROM '. $table_name;

        // now do the database call
        $resultCount = $wpdb->get_var( $sqlCount );

        // sql to call
        $sql = 'SELECT * FROM '. $table_name;

        // do ordering if any
        $sql .= ' LIMIT '. $per_page;
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

        // now do the database call
        $result = $wpdb->get_results( $sql );

        return $result;
    }

}// end class