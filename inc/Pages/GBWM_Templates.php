<?php
/**
 * @package GenBuz WooMulti
 */

namespace GBWM_Inc\Pages;

class GBWM_Templates {

    public static function gbwm_templates()
    {

        $templates_path_word = GBWM_TEMPLATES_PATH.'word/';
        $templates_url_word = GBWM_TEMPLATES_URL.'word/';

        $templates_path_pdf = GBWM_TEMPLATES_PATH.'pdf/';
        $templates_url_pdf = GBWM_TEMPLATES_URL.'pdf/';

    ?>
    <div class="wrap gbwm-templates">

        <h1 class="hideH1"></h1>

        <div class="orders-title bluebg title-font">
            <span><?php _e( 'Manage Templates', 'woomulti' );?></span>
        </div>

        <h1>Available Templates</h1>

        <p>Below are a list of current supported templates, click the Word or PDF to see the templates for each type, click on screenshots to see screenshots of the templates.</p>

        <div id="help-tabs" class="help-tabs">

            <ul id="HelpTabs">
                    <li class="help-tab title-font"><a href="#word-templates">Word Templates</a></li>
                    <li class="help-tab title-font"><a href="#pdf-templates">PDF Templates</a></li>
            </ul>

            <div id="word-templates" class="ct-new-columns">

                <?php

                if ( $templates_folder = opendir( $templates_path_word ) )
                {
                    // scan the dir for files/folders
                    $template_folders = scandir( $templates_path_word );

                    // remove the "." and ".."
                    unset( $template_folders[array_search( '.', $template_folders, true)] );
                    unset( $template_folders[array_search( '..', $template_folders, true)] );

                    // if the scaned folder was empty
                    if ( count( $template_folders ) < 1 )
                    {
                        return;
                    }

                    $galid = 1;

                    $i = 1;

                    // foreach folder in the template_folders array
                    foreach( $template_folders as $template_folder )
                    {
                        // if its a dir open it
                        if( is_dir( $templates_path_word.$template_folder ) )
                        {

                            if( file_exists( $templates_path_word.$template_folder.'/gbwm-template.php' ) )
                            {
                                require_once( $templates_path_word.$template_folder.'/gbwm-template.php' );

                                $template_data = json_decode( json_encode( $templateData ) );

                                $i++;

                                ?>

                                <div class="ct-new-columns template-wrapper">

                                    <div class="ct-div-block template-image">
                                        <img alt="" src="<?php echo $templates_url_word;?><?php echo $template_folder;?>/screenshots/<?php echo $template_data->screenshot_arr->main_thumb;?>" class="ct-image">
                                    </div>

                                    <div class="ct-div-block template-info">
                                        <h2 class="ct-headline template-title"><?php echo $template_data->template_name;?></h2>

                                        <div class="ct-text-block template-description">
                                            <p><?php echo $template_data->template_description;?></p>
                                        </div>

                                        <div class="ct-div-block template-options">

                                            <div class="ct-div-block template-screenshots">

                                                <a href="<?php echo $templates_url_word . $template_folder;?>/screenshots/<?php echo $template_data->screenshot_arr->main;?>" data-gall="word-screenshot-preview-<?php echo $galid;?>" class="ct-link-text venobox"><button type="button" class="btn btn-info btn-xs ActivateTemplate" data-templateid="<?php echo $template_data->templateID; ?>">Screenshots</button></a>

                                                <div style="display: none;">

                                                    <?php
                                                        foreach($template_data->screenshot_arr->screenshots as $screenshot)
                                                        {
                                                            echo '
                                                            <a href="'.$templates_url_word.$template_folder.'/screenshots/'.$screenshot->screenshot.'" data-gall="word-screenshot-preview-'.$galid.'" class="venobox"></a>';

                                                        }

                                                        $galid++;
                                                    ?>

                                                </div><?php // end display: none ?>
                                            </div><?php // end screenshots ?>

                                            <div class="ct-div-block template-activate">

                                            <?php

                                            // check if template is installed and active
                                            // load wordpress database object and make it global
                                            global $wpdb;

                                            $table_name = $wpdb->prefix.'gbwm_templates';

                                            // sql to call
                                            $sql = 'SELECT * FROM '. $table_name .' WHERE siteID = 0 AND templateID = \''. $template_data->templateID.'\'';

                                            // now do the database call
                                            $Template = $wpdb->get_row( $sql );

                                            // if the template is there
                                            $debug = 1;
                                            if( ! empty( $Template ) ){

                                                if( $Template->active == 0){// inactive

                                                    // show activate button
                                                ?>

                                                <button type="button" class="btn btn-dark btn-xs ActivateTemplate" data-templatetype="<?php echo $template_data->template_type; ?>" data-templateid="<?php echo $template_data->templateID; ?>">Activate</button>

                                                <?php

                                                }else{// active

                                                    // show deactivate button
                                                ?>

                                                <button type="button" class="btn btn-success btn-xs DeactivateTemplate" data-templatetype="<?php echo $template_data->template_type; ?>" data-templateid="<?php echo $template_data->templateID; ?>">Deactivate</button>

                                                <?php

                                                }

                                            }else{// if the template is not installed

                                                // show activate button
                                                ?>

                                                <button type="button" class="btn btn-dark btn-xs InstallTemplate" data-templatetype="<?php echo $template_data->template_type; ?>" data-templateid="<?php echo $template_data->templateID; ?>">Activate</button>

                                                <?php

                                            }

                                            ?>

                                            </div><?php // end activate ?>

                                        </div><?php // end template-options ?>
                                    </div><?php // end template-info ?>
                                </div><?php // end template-wrapper ?>

                                <?php
                                //echo '<pre>';
                                    //echo print_r( $template_data );
                                //echo '</pre><br/><br/>';

                            }// end if file_exists

                        }// end if is_dir

                    }// end foreach

                }// end open dir

                    //sort($templates);
                    closedir( $templates_folder );

                ?>

            </div><?php // end word-templates ?>

            <div id="pdf-templates">

                <?php

                if ( $templates_folder = opendir( $templates_path_pdf ) )
                {
                    // scan the dir for files/folders
                    $template_folders = scandir( $templates_path_pdf );

                    // remove the "." and ".."
                    unset( $template_folders[array_search( '.', $template_folders, true)] );
                    unset( $template_folders[array_search( '..', $template_folders, true)] );

                    // if the scaned folder was empty
                    if ( count( $template_folders ) < 1 )
                    {
                        return;
                    }

                    $galid = 1;

                    $i = 1;

                    // foreach folder in the template_folders array
                    foreach( $template_folders as $template_folder )
                    {
                        // if its a dir open it
                        if( is_dir( $templates_path_pdf.$template_folder ) )
                        {

                            if( file_exists( $templates_path_pdf.$template_folder.'/gbwm-template.php' ) )
                            {
                                require_once( $templates_path_pdf.$template_folder.'/gbwm-template.php' );

                                $template_data = json_decode( json_encode( $templateData ) );

                                $i++;

                                ?>

                                <div class="ct-new-columns template-wrapper">

                                    <div class="ct-div-block template-image">
                                        <img alt="" src="<?php echo $templates_url_pdf;?><?php echo $template_folder;?>/screenshots/<?php echo $template_data->screenshot_arr->main_thumb;?>" class="ct-image">
                                    </div>

                                    <div class="ct-div-block template-info">
                                        <h2 class="ct-headline template-title"><?php echo $template_data->template_name;?></h2>

                                        <div class="ct-text-block template-description">
                                            <p><?php echo $template_data->template_description;?></p>
                                        </div>

                                        <div class="ct-div-block template-options">

                                            <div class="ct-div-block template-screenshots">

                                                <a href="<?php echo $templates_url_pdf . $template_folder;?>/screenshots/<?php echo $template_data->screenshot_arr->main;?>" data-gall="word-screenshot-preview-<?php echo $galid;?>" class="ct-link-text venobox"><button type="button" class="btn btn-info btn-xs ActivateTemplate" data-templateid="<?php echo $template_data->templateID; ?>">Screenshots</button></a>

                                                <div style="display: none;">

                                                    <?php
                                                        foreach($template_data->screenshot_arr->screenshots as $screenshot)
                                                        {
                                                            echo '
                                                            <a href="'.$templates_url_pdf.$template_folder.'/screenshots/'.$screenshot->screenshot.'" data-gall="word-screenshot-preview-'.$galid.'" class="venobox"></a>';

                                                        }

                                                        $galid++;
                                                    ?>

                                                </div><?php // end display: none ?>
                                            </div><?php // end screenshots ?>

                                            <div class="ct-div-block template-activate">

                                            <?php

                                            // check if template is installed and active
                                            // load wordpress database object and make it global
                                            global $wpdb;

                                            $table_name = $wpdb->prefix.'gbwm_templates';

                                            // sql to call
                                            $sql = 'SELECT * FROM '. $table_name .' WHERE siteID = 0 AND templateID = \''. $template_data->templateID.'\'';

                                            // now do the database call
                                            $Template = $wpdb->get_row( $sql );

                                            // if the template is there
                                            $debug = 1;
                                            if( ! empty( $Template ) ){

                                                if( $Template->active == 0){// inactive

                                                    // show activate button
                                                ?>

                                                <button type="button" class="btn btn-dark btn-xs ActivateTemplate" data-templatetype="<?php echo $template_data->template_type; ?>" data-templateid="<?php echo $template_data->templateID; ?>">Activate</button>

                                                <?php

                                                }else{// active

                                                    // show deactivate button
                                                ?>

                                                <button type="button" class="btn btn-success btn-xs DeactivateTemplate" data-templatetype="<?php echo $template_data->template_type; ?>" data-templateid="<?php echo $template_data->templateID; ?>">Deactivate</button>

                                                <?php

                                                }

                                            }else{// if the template is not installed

                                                // show activate button
                                                ?>

                                                <button type="button" class="btn btn-dark btn-xs InstallTemplate" data-templatetype="<?php echo $template_data->template_type; ?>" data-templateid="<?php echo $template_data->templateID; ?>">Activate</button>

                                                <?php

                                            }

                                            ?>

                                            </div><?php // end activate ?>

                                        </div><?php // end template-options ?>
                                    </div><?php // end template-info ?>
                                </div><?php // end template-wrapper ?>

                                <?php
                            }// end if file_exists

                        }// end if is_dir

                    }// end foreach

                }// end open dir

                //sort($templates);
                closedir( $templates_folder );

                ?>
            </div><?php // end pdf-templates ?>

        </div><?php // end tabs wrapper ?>



    </div>

    <?php

    }// end gbwm_templates function

}// end GBWM_Templates class