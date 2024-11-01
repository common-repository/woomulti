<?php
/**********************
* Template Name: Classic - PDF
* Author: GenBuz
* Version: 1.1
**********************/

// default data
$templateData = array(
    // template_name/templateID needs to be unique
    'template_name' => 'Classic',
    'template_type' => 'pdf',// needs to be the same name as template type folder (case sensitive)
    'templateID' => 'classic_pdf',// needs to be the same name as template folder (case sensitive)
    'template_description' => '<strong>Classic</strong> is a clean proffesional PDF template with various colour options available and can be customised for each individual site.',

    'logo' => array (
        // 1=no logo,2=site url,3=text,4=image
        'type' => 2,
        'fontsizeurl' => 24,// for type 2
        'textvalue' => '',// for type 3
        'fontsizetext' => 24,// for type 3
        'imagevalue' => '',// for type 4
    ),

    'colors' => array(
        'header_titles' => array(
            'title' => 'Header Titles',
            'value' => '026b96',
        ),
        'body_titles' => array(
            'title' => 'Body Titles',
            'value' => '026b96',
        ),
        'body_text' => array(
            'title' => 'Body Text',
            'value' => '333333',
        ),
        'table_header' => array(
            'title' => 'Table Header',
            'value' => '026b96',
        ),
        'header_text' => array(
            'title' => 'Header Text',
            'value' => 'ffffff',
        ),
        'table_border' => array(
            'title' => 'Table Border',
            'value' => 'e5e5e5',
        ),
        'alt_row' => array(
            'title' => 'Alternate Row',
            'value' => 'f6f6f6',
        ),
    ),

    'options' => array(
        'Enable' => array(
            'order_number' => array(
                'title' => 'Order Number',
                'value' => 'true',
            ),
            'order_date' => array(
                'title' => 'Order Date',
                'value' => 'true',
            ),
            'phone' => array(
                'title' => 'Phone',
                'value' => 'true',
            ),
            'email' => array(
                'title' => 'Email',
                'value' => 'true',
            ),
            'sku' => array(
                'title' => 'SKU',
                'value' => 'true',
            ),
        )
    ),

    'screenshot_arr' => array(
        'main' => 'screenshot-main.jpg',
        'main_thumb' => 'screenshot-main-thumb.jpg',

        'screenshots' => array(
            // do a foreach here
            array(
                'screenshot' => 'screenshot-1.jpg',
                'screenshot_thumb' => 'screenshot-thumb-1.jpg',
            ),
            array(
                'screenshot' => 'screenshot-2.jpg',
                'screenshot_thumb' => 'screenshot-thumb-2.jpg',
            ),
            array(
                'screenshot' => 'screenshot-3.jpg',
                'screenshot_thumb' => 'screenshot-thumb-3.jpg',
            ),
            array(
                'screenshot' => 'screenshot-4.jpg',
                'screenshot_thumb' => 'screenshot-thumb-4.jpg',
            ),
            array(
                'screenshot' => 'screenshot-5.jpg',
                'screenshot_thumb' => 'screenshot-thumb-5.jpg',
            ),
            array(
                'screenshot' => 'screenshot-6.jpg',
                'screenshot_thumb' => 'screenshot-thumb-6.jpg',
            ),

        ),

    ),

);// end templateData array

$GBWM_THIS_URL = trailingslashit( plugin_dir_url( __FILE__ ) );

if( ! empty($saveType) ){

    // load wordpress database object and make it global
    global $wpdb;

    // get details for this site
    $table_name = $wpdb->prefix.'gbwm_sites';

    $sql = "SELECT * FROM ". $table_name ." WHERE id = ".$siteID." LIMIT 1";

    // now do the database call
    $thisSite = $wpdb->get_row( $sql );

    $logoType = $jsonData->logo->type;
    $textvalue = $jsonData->logo->textvalue;
    $imagevalue = $jsonData->logo->imagevalue;
    $fontsizeurl = $jsonData->logo->fontsizeurl;
    $fontsizetext = $jsonData->logo->fontsizetext;

    $templateHTML = '
    <input type="hidden" id="ThisSite" class="form-control radio-extra" name="ThisSite" data-siteid="'.$siteID.'" data-savetype="'.$saveType.'" value="'.$thisSite->url.'">

    <div id="template-container" class="ct-new-columns">

        <div id="template-sidebar" class="ct-div-block">

            <div id="site-template-accordion" class="ct-div-block">

                <div class="ct-div-block sidebar-header first title-font">
                    <h1 class="ct-headline">Intro</h1>
                </div>

                <div class="ct-div-block sidebar-options">

                    <h3 class="ct-headline">Site Template</h3>

                    <p class="">Use this section to configure a download template for this site.</p>

                    <p class="">The changes you make in this panel will be updated in real time in the template preview on the right.</p>

                    <p class="">Once you are happy with your template dont foget to click the "Save" button, you can create a unique template for each site.</p>

                    <p class=""><span class="bold">Note:</span> The preview on the right is a "close" representation of the actual template.</p>

                </div>

                <div class="ct-div-block sidebar-header title-font">
                    <h1 class="ct-headline">Template</h1>
                </div>

                <div class="ct-div-block sidebar-options">

                    <h3 class="ct-headline">Select A Template</h3>

                    <select id="Template" class="form-control" name="Template">
                    ';

                    // get list of current active templates
                    $table_name = $wpdb->prefix.'gbwm_templates';

                    $sql = "SELECT * FROM ". $table_name ." WHERE siteID = 0 AND active = 1 ORDER BY 'id' ASC LIMIT 100";

                    $templates = $wpdb->get_results( $sql );

                    foreach($templates as $template){

                        if( $template->template_type == 'word' ){

                            $templateType = 'Word';

                        }elseif( $template->template_type == 'pdf' ){

                            $templateType = 'PDF';

                        }

                        $templateHTML .= '<option data-templatename="'.$template->template_name.'" data-templatetype="'.$template->template_type.'" value="'.$template->templateID.'"'.(($template->templateID == $jsonData->templateID ) ? ' selected="selected"' : '').'>'.$template->template_name.' - '.$templateType.'</option>';

                    }

                    $templateHTML .= '</select>

                </div>

                <div class="ct-div-block sidebar-header title-font">
                    <h1 class="ct-headline">Logo</h1>
                </div>

                <div class="ct-div-block sidebar-options">

                    <h3 class="ct-headline">Logo Type</h3>

                    <div id="logo-radio" class="ct-div-block">';

                        //1=no logo,2=site url,3=text,4=image

                        $templateHTML .= '
                        <div class="template-radio ct-new-columns">
                            <input type="radio" id="nologo" class="form-control logo" name="logo" value="1"'.(( $logoType == 1 ) ? ' checked=""' : '').'>
                            <label for="nologo">No Logo</label>
                        </div>

                        <div class="template-radio fontsize-container ct-new-columns">

                            <div class="input-box">
                                <input type="radio" id="siteurl" class="form-control logo" name="logo" value="2"'.(( $logoType == 2 ) ? ' checked=""' : '').'>
                                <label for="siteurl">Site URL</label>
                            </div>

                            <div class="fontsize-box url"'.(( $logoType == 2 ) ? '' : ' style="display:none;"').'>

                                <div class="fontsize-minus"><i class="fas fa-minus-square"></i></div>

                                <input type="text" id="fontsize-url" class="form-control size-extra" name="fontsize-url" value="'.$fontsizeurl.'">

                                <div class="fontsize-plus"><i class="fas fa-plus-square"></i></div>

                                <label for="fontsize-url" class="size-extra-label">Size</label>

                            </div>

                        </div>

                        <div class="template-radio fontsize-container ct-new-columns">

                            <div class="input-box">
                                <input type="radio" id="text" class="form-control logo" name="logo" value="3"'.(( $logoType == 3 ) ? ' checked=""' : '').'>
                                <label for="text">Text</label>
                            </div>

                            <div class="fontsize-box text"'.(( $logoType == 3 ) ? '' : ' style="display:none;"').'>

                                <div class="fontsize-minus"><i class="fas fa-minus-square"></i></div>

                                <input type="text" id="fontsize-text" class="form-control size-extra" name="fontsize-text" value="'.$fontsizetext.'">

                                <div class="fontsize-plus"><i class="fas fa-plus-square"></i></div>

                                <label for="fontsize-text" class="size-extra-label">Size</label>

                            </div>

                        </div>

                        <div class="template-radio-text ct-new-columns"'.(( $logoType == 3 ) ? '' : ' style="display:none;"').'>

                            <input type="text" id="radio-text" class="form-control radio-extra" name="radio-text" value="'.$textvalue.'">

                            <label for="radio-text" class="radio-extra-label">Your Text</label>

                        </div>

                        <div class="template-radio ct-new-columns">
                            <input type="radio" id="image" class="form-control logo" name="logo" value="4"'.(( $logoType == 4 ) ? ' checked=""' : '').'>
                            <label for="image">Image</label>
                        </div>

                        <div class="template-radio-url ct-new-columns"'.(( $logoType == 4 ) ? '' : ' style="display:none;"').'>

                            <input type="text" id="radio-url" class="form-control radio-extra" name="radio-url" value="'.$imagevalue.'">

                            <label for="radio-url" class="radio-extra-label">Image URL</label>

                        </div>



                    </div>

                </div>

                <div class="ct-div-block sidebar-header title-font">
                    <h1 class="ct-headline">Colors</h1>
                </div>

                <div class="ct-div-block sidebar-options">
                    <h3 class="ct-headline">Choose Your Colors</h3>
                    ';

                    $colors = $jsonData->colors;

                    $colorCSS = json_decode(json_encode($colors), true);

                    $colorCSS = array_keys($colorCSS);

                    //print_r($colorCSS);

                    $i = 0;

                    foreach($colors as $color){

                        $templateHTML .= '<div class="ct-new-columns color-select-container">';

                        $templateHTML .= '<input type="text" name="'.$colorCSS[$i].'" id="'.$colorCSS[$i].'" data-color="'.$color->value.'" value="#'.$color->value.'">';

                        $templateHTML .= '<div class="color-title">'.$color->title.'</div>';

                        $templateHTML .= '</div>';

                        $i++;

                    }
                    ?>

                <?php

                $templateHTML .= '</div>

                <div class="ct-div-block sidebar-header title-font">
                    <h1 class="ct-headline">Options</h1>
                </div>

                <div class="ct-div-block sidebar-options last">
                    ';

                    $optionGroup = $jsonData->options;

                    $optionGroupKey = json_decode(json_encode($optionGroup), true);

                    $optionGroupKey = array_keys($optionGroupKey);

                    $i = 0;

                    foreach($optionGroup as $options){

                        $templateHTML .= '<h3 class="ct-headline">'.$optionGroupKey[$i].'</h3>';

                        $optionKey = json_decode(json_encode($options), true);

                        $optionKey = array_keys($optionKey);

                        $i2 = 0;

                        foreach($options as $option){

                            //'.(( $value == 'true' ) ? ' checked=""' : '').'

                            $templateHTML .= '
                            <div class="template-radio ct-new-columns">

                                <input type="checkbox" id="'.$optionKey[$i2].'" class="form-control enable" name="'.$optionKey[$i2].'" value="'.$option->value.'"'.(( $option->value == 'true' ) ? ' checked=""' : '').'>

                                <label for="'.$optionKey[$i2].'">'.$option->title.'</label>

                            </div>
                            ';

                            $i2++;// group name counter

                        }

                        $i++;// group name counter
                    }
                    ?>

                <?php

                $templateHTML .= '</div>

            </div>

            <div class="ct-div-block sidebar-save-container last title-font">
                <h1 class="ct-headline sidebar-save">Save</h1>
            </div>

        </div>

        <div id="template-preview-container" class="ct-div-block">

            <div id="ajaxTemplateContainer" class="ct-div-block">

                <table class="header">
                    <tr>
                        <td class="logo">';

                            if( $logoType == 2 ){

                                $arrURL = parse_url( $thisSite->url );
                                $SiteURL = strtoupper( $arrURL['host'] );

                                $templateHTML .= '<span class="header_titles statlogo siteurl" style="font-size: '.$fontsizeurl.'px">'.$SiteURL.'</span>';

                            }else if( $logoType == 3 ){

                                $templateHTML .= '<span class="header_titles statlogo text" style="font-size: '.$fontsizetext.'px" style="">'.$textvalue.'</span>';

                            }else if( $logoType == 4 ){

                                $templateHTML .= '<img src="'.$imagevalue.'" class="statlogo image"/>';
                            }


                        $templateHTML .= '
                        </td>

                        <td class="details">
                            <h1 class="header_titles invoice" style="">INVOICE</h1>';

                                $orderNumber = '<div class="body_text order_number"><span class="header-order-number header_titles bold">Order #:</span> 61<br/></div>';

                                $orderNumberHidden = '<div class="body_text order_number" style="display:none;"><span class="header-order-number header_titles bold">Order #:</span> 61<br/></div>';

                                $templateHTML .= ( $optionGroup->Enable->order_number->value == 'true' ) ? $orderNumber : $orderNumberHidden;

                                $orderDate = '<div class="body_text order_date"><span class="header-order-date header_titles bold">Order Date:</span> Dec 30, 2018</div>';

                                $orderDateHidden = '<div class="body_text order_date" style="display:none;"><span class="header-order-date header_titles bold">Order Date:</span> Dec 30, 2018</div>';

                                $templateHTML .= ( $optionGroup->Enable->order_date->value == 'true' ) ? $orderDate : $orderDateHidden;

                        $templateHTML .= '</td>
                    </tr>
                </table>

                <br/>

                <table class="addresses">
                    <tr>
                        <td class="bill-to-container">
                            <span class="bill-to-address body_titles">Bill To:</span><br/>
                            <span class="body_text">Customer Name</span><br/>
                            <span class="body_text">123 Something Street</span><br/>
                            <span class="body_text">This City</span><br/>
                            <span class="body_text">Some State</span><br/>
                            <span class="body_text">SR1 1AB</span><br/>
                            <span class="body_text">United States (US)</span><br/><br/>';

                            $phone = '<div class="phone"><span class="bill-to-phone body_titles">Phone:</span> <span class="body_text">0123 4567 8965</span><br/></div>';

                            $phoneHidden = '<div class="phone" style="display:none;"><span class="bill-to-phone body_titles">Phone:</span> <span class="body_text">0123 4567 8965</span><br/></div>';

                            $templateHTML .= ( $optionGroup->Enable->phone->value == 'true' ) ? $phone : $phoneHidden;

                            $email = '<div class="email"><span class="bill-to-email body_titles">Email:</span> <span class="body_text">test@customer.com</span><br/></div>';

                            $emailHidden = '<div class="email" style="display:none;"><span class="bill-to-email body_titles">Email:</span> <span class="body_text">test@customer.com</span><br/></div>';

                            $templateHTML .= ( $optionGroup->Enable->email->value == 'true' ) ? $email : $emailHidden;

                        $templateHTML .= '</td>

                        <td class="ship-to-container">
                            <span class="ship-to-address body_titles">Ship To:</span><br/>
                            <span class="body_text">Customer Name</span><br/>
                            <span class="body_text">123 Something Street</span><br/>
                            <span class="body_text">This City</span><br/>
                            <span class="body_text">Some State</span><br/>
                            <span class="body_text">SR1 1AB</span><br/>
                            <span class="body_text">United States (US)</span>
                        </td>
                    </tr>
                </table>

                <br/>

                <table class="widefat orders-table">
                    <tr class="table_header">
                        <th class="qty center header-cell">QTY</th>
                        <th class="description header-cell">Description</th>
                        <th class="price header-cell">Unit Price</th>
                        <th class="total text-right header-cell">Total</th>
                    </tr>
                    <tr class="order-row body_text">
                        <td class="order-cell center body_text">1</td>
                        <td class="order-cell body_text">Mens long sleeve jumper - Gray, Medium<br/>';

                        $sku = '<div class="sku"><span class="skuspan body_text">SKU:</span> MLSJG-44523</div>';

                        $skuHidden = '<div class="sku" style="display:none;"><span class="skuspan body_text">SKU:</span> MLSJG-44523</div>';

                        $templateHTML .= ( $optionGroup->Enable->sku->value == 'true' ) ? $sku : $skuHidden;

                    $templateHTML .= '</td>
                        <td class="order-cell body_text">$29.99</td>
                        <td class="order-cell text-right body_text">$29.99</td>
                    </tr>
                    <tr class="order-row alt_row body_text">
                        <td class="order-cell center body_text">1</td>
                        <td class="order-cell body_text">V-neck T-shirt - Blue, Medium<br/>
                        ';

                        $sku = '<div class="sku"><span class="skuspan body_text">SKU:</span> VNTSB-98547</div>';

                        $skuHidden = '<div class="sku" style="display:none;"><span class="skuspan body_text">SKU:</span> VNTSB-98547</div>';

                        $templateHTML .= ( $optionGroup->Enable->sku->value == 'true' ) ? $sku : $skuHidden;

                    $templateHTML .= '</td>
                        <td class="order-cell body_text">$19.99</td>
                        <td class="order-cell text-right body_text">$19.99</td>
                    </tr>
                    <tr class="order-row body_text">
                        <td class="order-cell center body_text">1</td>
                        <td class="order-cell body_text">Womens long sleeve jumper - White, small<br/>';

                        $sku = '<div class="sku"><span class="skuspan body_text">SKU:</span> WLSJW-45874</div>';

                        $skuHidden = '<div class="sku" style="display:none;"><span class="skuspan body_text">SKU:</span> WLSJW-45874</div>';

                        $templateHTML .= ( $optionGroup->Enable->sku->value == 'true' ) ? $sku : $skuHidden;

                    $templateHTML .= '</td>
                        <td class="order-cell body_text">$29.99</td>
                        <td class="order-cell text-right body_text">$29.99</td>
                    </tr>
                    <tr>
                        <td class="0pky"></td>
                        <td class="0pky"></td>
                        <td class="subtitle body_text">Subtotal:</td>
                        <td class="amount bold body_text">$79.97</td>
                    </tr>
                    <tr>
                        <td class="thank-you body_text" colspan="2">Thank you for your business</td>
                        <td class="subtitle body_text">Shipping &<br/> Handling:</td>
                        <td class="amount body_text">$6.99</td>
                    </tr>
                    <tr>
                        <td class="0pky"></td>
                        <td class="0pky"></td>
                        <td class="subtitle body_text">Tax:</td>
                        <td class="amount body_text">$0.00</td>
                    </tr>
                    <tr>
                        <td class="0pky"></td>
                        <td class="0pky"></td>
                        <td class="subtitle body_text">Total:</td>
                        <td class="totalamount header-cell">$86.96</td>
                    </tr>
                </table>

                <br/><br/>

            </div>

        </div>

    </div>';

    $templateHTML .= '<link rel="stylesheet" id="gbwm-template-css"  href="'.$GBWM_THIS_URL.'gbwm-template.css?ver='.time().'" type="text/css" media="all" />

    <script type="text/javascript" src="'.GBWM_TEMPLATES_URL.'global/jquery.textfill.js?ver='.time().'"></script>

    <script type="text/javascript" src="'.$GBWM_THIS_URL.'gbwm-template.js?ver='.time().'"></script>
    ';

    }// end if
        ?>