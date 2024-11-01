<?php

/**
 * @package WooMaster Download
 *
 * Template Name = Classic
 *
 */

namespace GBWM_Templates\pdf\classic_pdf;

// woocommerce
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

// dompdf
use Dompdf\Dompdf;
use Dompdf\Options;

// TLDExtract
use LayerShifter\TLDExtract\Extract;

class GBWM_Create_Selected
{
    public static function gbwm_create_selected( $siteID, $idsArr, $templateData )
    {
        // get the groups
        $logo   = $templateData->logo;
        $colors = $templateData->colors;
        $optionGroup = $templateData->options;
        $enable = $templateData->options->Enable;

        // load woocommerce and make it global
        global $woocommerce;

        // load wordpress database object and make it global
        global $wpdb;

        $table_name = $wpdb->prefix.'gbwm_sites';

        $date = date("d-m-Y");

        $sql = 'SELECT * FROM '. $table_name .' WHERE id='.$siteID;

        // now do the database call
        $WooSite = $wpdb->get_row( $sql );

        $woocommerceAPI = new Client(
            $WooSite->url,
            $WooSite->Consumer_key,
            $WooSite->Consumer_secret,
            [
                'wp_api' => true,
                'verify_ssl' => false, //$UseSSL
                'version' => 'wc/v2',

                /**
                 * query_string_auth is enabled because on some
                 * sites when trying to download we get the
                 * "Sorry, you cannot list resources"
                 */
                'query_string_auth' => true,
                'validate_url'      => false,
                //'return_as_array' => true,
            ]

        );// end new client

        if( ! isset( $PerPage ) )
        {
            // max for api is 100
            $PerPage = 100;
        }

        if( ! isset( $PageNumber ) )
        {
            $PageNumber = 1;
        }

        try {

            // make the api call and store the results in $Orders
            $Orders = $woocommerceAPI->get( 'orders', [
                'include'   => $idsArr,
                'per_page'  => $PerPage,
                'page'      => $PageNumber,
            ] );

            $lastRequest    = $woocommerceAPI->http->getRequest();
            $lastResponse   = $woocommerceAPI->http->getResponse();
            $headers        = $lastResponse->getHeaders();

        // if there is an api error catch and display
        } catch (HttpClientException $e)
        {

            $return = array(
                'debug'     => $siteID.' - '.$lastResponse,
                'message'   => $e->getMessage(),
                'ID'        => 0,
            );

            wp_send_json($return);
            die();

        }// end catch

        $LSi = 1;
        $lastorder = count($Orders);

        // if there are no orders for this site
        if( $Orders == null || $Orders == '' )
        {
            // do nothing
        }else{

            //reset $html
            unset($html);

            $html = '';

            $html .= '<!DOCTYPE HTML>
            <html>
            <head>
            <title>Invoice</title>
            <style type="text/css" id="pdf_css">

                * {
                    font-size: 13px;
                    color: #'.$colors->body_text->value.';
                }

                .center {
                    text-align: center;
                }

                .bold {
                    font-weight: bold;
                }

                .text-right {
                    text-align: right;
                }

                .table-header {
                    padding: 5px 10px;
                }

                table.invoice_orders,
                table.invoice_totals
                {
                    width: 100%;
                    text-align: left;
                    border-collapse: collapse;
                }

                table.invoice_orders td,
                table.invoice_totals td {
                    border: 1px solid #'.$colors->table_border->value.';
                    padding: 5px 10px;
                }

                table.invoice_orders tbody td,
                table.invoice_totals tbody td {
                }

                .alt {
                    background-color: #'.$colors->alt_row->value.';
                }

                .qty {
                    width: 60px;
                }

                .description {

                }

                .unit-price {
                    width: 70px;
                }

                .total {
                    width: 70px;
                }

                .thanks {
                    font-size: 16px;
                    font-weight: bold;
                    font-style: italic;
                }

                .totals {
                    text-align: right;
                }

                .curency-totals {
                    width: 69px;
                    text-align: right;
                }

                .no-top-border {
                    border-top: 0px solid #'.$colors->table_border->value.' !important;
                }

                .no-bottom-border {
                    border-bottom: 0px solid #'.$colors->table_border->value.' !important;
                }

                .no-left-border {
                    border-left: 0px solid #'.$colors->table_border->value.' !important;
                }

                .no-right-border {
                    border-right: 0px solid #'.$colors->table_border->value.' !important;
                }

                .bottom-border1 {
                    border-bottom: 1px solid #'.$colors->table_border->value.' !important;
                }

                .vtop {
                    vertical-align: top;
                }

                .vbot {
                    vertical-align: bottom;
                }
                </style>

            </head>
            <body>';

            // check to see if a logo has been set
            if( $logo->type == 4 )
            {
                // do not veryfy ssl for images
                $arrContextOptions = array(
                    "ssl" => array(
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                    ),
                );
                stream_context_set_default( $arrContextOptions );

                // give our temp image a name
                $temp_logo = 'temp_logo';

                // set the image url
                $logo_url =  $logo->imagevalue;

                // get past the ssl error by reading the file
                // and putting it in a temp created file.
                file_put_contents(
                    $temp_logo,
                    file_get_contents(
                        $logo_url,
                        false,
                        stream_context_create(
                            $arrContextOptions
                        )
                    )
                );

                // get width and height from image
                list( $image_width, $image_height ) = getimagesize( $temp_logo );
            }

            // we have the order for the current site
            foreach( $Orders as $Order )
            {
                // set the date used in the order date
                $date = strtotime($Order->date_created);

                // get the shipping section from the array
                $billing = $Order->billing;

                // get the shipping section from the array
                $shipping = $Order->shipping;

                $logotype = $templateData->logo->type;

                // if the logo type is site address
                if( $logotype == '' || $logotype == 2 )
                {
                    $arrURL = parse_url( $WooSite->url );

                    $SiteURL = strtoupper( $arrURL['host'] );

                    ($logo->fontsizeurl === '') ? $logo->fontsizeurl = 24 : '';

                    $lineHeight = $logo->fontsizeurl + 4;

                    // set the logo/text
                    $ThisLogo = '<span style="font-weight: bold;
                    color: #'.$colors->header_titles->value.';
                    font-size: '.$logo->fontsizeurl.'px;
                    line-height: normal;
                    padding: 0px;
                    margin: 0px;">'.$SiteURL.'</span>
                    <br />';
                }// end type url




                // if the logo type is text
                if( $logotype == 3 )
                {

                    ($logo->fontsizetext === '') ? $logo->fontsizetext = 24 : '';

                    $lineHeight = $logo->fontsizetext + 4;

                    // set the logo/text
                    $ThisLogo = '<span style="font-weight: bold;
                    color: #'.$colors->header_titles->value.';
                    font-size: '.$logo->fontsizetext.'px;
                    line-height: '.$lineHeight.'px;
                    padding: 0px;
                    margin: 0px;">'.$logo->textvalue.'</span>
                    <br />';
                }// end type text




                // check to see if a logo has been set
                if( $logotype == 4 )
                {
                    // do some math on height and width
                    $max_width = 600;
                    $max_height = 100;

                    // if the image is wider than our max-width
                    // then set image width to max width
                    if( $image_width > $max_width )
                    {
                        $image_width = $max_width;
                    }

                    $ThisLogo = '<img src="'.$logo_url.'" width="'.$image_width.'">
                    <br />';

                }// end type image

                // do the code for the bill to state and country
                $billto_states = WC()->countries->get_states( $billing->country );

                $billto_state  = ! empty( $billto_states[ $billing->state ] ) ? $billto_states[ $billing->state ] : $billing->state;

                // do the code for the ship to state and country
                $shipto_states = WC()->countries->get_states( $shipping->country );

                $shipto_state  = ! empty( $shipto_states[ $shipping->state ] ) ? $shipto_states[ $shipping->state ] : $shipping->state;



                $html .= '<table class="top-section" style="width: 100%;">
                    <tbody>
                        <tr>
                            <td class="company-info vtop" style="width: 480px;
                            max-width: 480px;
                            height: 90px;
                            max-height: 90px;
                            text-align: left;
                            padding: 0px;
                            margin: 0px;
                            line-height: normal;">'.$ThisLogo.'</td>

                            <td class="invoice-info vtop" style="width: 200px;
                            max-width: 200px;
                            text-align: right;
                            vertical-align: top;">

                            <span style="font-size: 24px;
                            font-weight: bold;
                            color: #'.$colors->header_titles->value.';">INVOICE</span><br/>';

                            $orderNumber = '<div><span style="font-weight: bold;
                            color: #'.$colors->body_titles->value.';">Order #:</span> '.$Order->number.'<br/></div>';

                            $orderNumberHidden = '<div style="display: none;"><span style="font-weight: bold;
                            color: #'.$colors->body_titles->value.';">Order #:</span> '.$Order->number.'<br/></div>';

                            $html .= ( $optionGroup->Enable->order_number->value == 'true' ) ? $orderNumber : $orderNumberHidden;

                            $orderDate = '<div><span style="font-weight: bold;
                            color: #'.$colors->body_titles->value.';">Order Date:</span> '.date('M j, Y', $date).'<br/></div>';

                            $orderDateHidden = '<div style="display: none;"><span style="font-weight: bold;
                            color: #'.$colors->body_titles->value.';">Order Date:</span> '.date('M j, Y', $date).'<br/></div>';

                            $html .= ( $optionGroup->Enable->order_date->value == 'true' ) ? $orderDate : $orderDateHidden;

                            $html .= '</td>
                        </tr>
                    </tbody>
                </table>

                <br />

                <table class="mid-section" style="width: 100%;">
                    <tbody>
                        <tr>
                            <td class="bill-to vtop" style="width: 50%; text-align: left;">
                            <span style="font-weight: bold; color: #'.$colors->body_titles->value.';">Bill To:</span><br />
                            '.$billing->first_name.' '.$billing->last_name.'<br />
                            '.( ! empty( $billing->company ) ? $billing->company.'<br />' : '' ).'
                            '.$billing->address_1.'<br />
                            '.( ! empty( $billing->address_2 ) ? $billing->address_2.'<br />' : '' ).'
                            '.$billing->city.'<br />
                            '.$billto_state.'<br />
                            '.$billing->postcode.'<br />
                            '.WC()->countries->countries[ $billing->country ].'<br /><br />';

                            $Phone = '<span style="font-weight: bold; color: #'.$colors->body_titles->value.';">Phone:</span> '.$billing->phone.'<br />';

                            $PhoneHidden = '<div style="display: none;"><span style="font-weight: bold; color: #'.$colors->body_titles->value.';">Phone:</span> '.$billing->phone.'<br /></div>';

                            $html .= ( $optionGroup->Enable->phone->value == 'true' ) ? $Phone : $PhoneHidden;



                            $Email = '<span style="font-weight: bold; color: #'.$colors->body_titles->value.';">Email:</span> '.$billing->email.'';

                            $EmailHidden = '<div style="display: none;"><span style="font-weight: bold; color: #'.$colors->body_titles->value.';">Email:</span> '.$billing->email.'</div>';

                            $html .= ( $optionGroup->Enable->email->value == 'true' ) ? $Email : $EmailHidden;

                            $html .= '</td>

                            <td class="ship-to vtop" style="width: 50%; text-align: left;">
                            <span style="font-weight: bold; color: #'.$colors->body_titles->value.';">Ship To:</span><br />
                            '.$shipping->first_name.' '.$shipping->last_name.'<br />
                            '.( ! empty( $shipping->company ) ? $shipping->company.'<br />' : '' ).'
                            '.$shipping->address_1.'<br />
                            '.( ! empty( $shipping->address_2 ) ? $shipping->address_2.'<br />' : '' ).'
                            '.$shipping->city.'<br />
                            '.$shipto_state.'<br />
                            '.$shipping->postcode.'<br />
                            '.WC()->countries->countries[ $shipping->country ].'<br /><br />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <br />

                <table class="invoice_orders bottom-border1">
                    <thead>
                        <tr style="border: 1px solid #'.$colors->table_header->value.'; background-color: #'.$colors->table_header->value.';">

                            <th class="qty center bold table-header" style="color: #'.$colors->header_text->value.' !important;">Qty</td>
                            <th class="description bold table-header" style="color: #'.$colors->header_text->value.' !important;">Description</td>
                            <th class="unit-price bold table-header" style="color: #'.$colors->header_text->value.' !important;">Unit Price</td>
                            <th class="total bold table-header text-right" style="color: #'.$colors->header_text->value.' !important;">Total</td>

                        </tr>
                        </thead>

                        <tbody>';

                        /**
                        * product row do a foreach
                        *
                        */

                        $currency = html_entity_decode( get_woocommerce_currency_symbol( $Order->currency ) );

                        $subtotal = 0;

                        // used for alternating row background color
                        $ar = true;

                        // work out the quantity
                        foreach ( $Order->line_items as $product )
                        {
                            // do the alt row code
                            $rowcolor = ( ( $ar=!$ar ) ? $colors->alt_row->value : 'ffffff' );

                            $subtotal = $subtotal + number_format($product->total, 2);

                            $sku = '';

                            if( ! empty( $product->sku ) )
                            {
                                $sku = '<br/>
                                SKU: '.$product->sku;
                            }

                            $html .= '<tr style="background-color: #'.$rowcolor.'">
                                <td class="center bold">'.$product->quantity.'</td>
                                <td>'.$product->name.$sku.'</td>
                                <td>'.$currency.number_format((float)$product->price, 2, '.', '').'</td>
                                <td class="text-right">'.$currency.number_format((float)$product->total, 2, '.', '').'</td>
                            </tr>';
                        }

                    $html .= '</tbody>
                </table>

                <table class="invoice_totals">
                    <tbody>

                        <tr>
                            <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>
                            <td class="totals no-top-border no-bottom-border no-left-border">Subtotal:</td>
                            <td class="curency-totals no-top-border bold">'.$currency.number_format((float)$subtotal, 2, '.', '').'</td>
                        </tr>';

                        $totalshipping = 0;

                        foreach($Order->shipping_lines as $shippingprice)
                        {
                            $totalshipping = $totalshipping + $shippingprice->total;
                        }
                        $total_tax = number_format((float)$Order->total_tax, 2, '.', '');

                        $html .= '<tr>
                            <td class="thanks no-top-border no-bottom-border no-left-border no-right-border">Thank you for your business</td>

                            <td class="totals no-top-border no-bottom-border no-left-border">Shipping & Handling:</td>

                            <td class="curency-totals">'.$currency.number_format((float)$totalshipping, 2, '.', '').'</td>
                        </tr>

                        <tr>
                            <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>

                            <td class="totals no-top-border no-bottom-border no-left-border">Tax:</td>

                            <td class="curency-totals" style="border-bottom: 1px solid #'.$colors->table_header->value.';">'.$currency.number_format((float)$total_tax, 2, '.', '').'</td>
                        </tr>

                        <tr>
                            <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>

                            <td class="totals no-top-border no-bottom-border no-left-border no-right-border">Total:</td>

                            <td class="curency-totals bold table-header" style="border: 1px solid #'.$colors->table_header->value.'; background-color: #'.$colors->table_header->value.'; color: #'.$colors->header_text->value.' !important;">'.$currency.$totalPrice = number_format((float) $subtotal + $total_tax + $totalshipping, 2, '.', '').'</td>
                        </tr>

                    </tbody>
                </table>
                ';


                /**
                * if its the last page do not do a pagebreak
                *
                */

                @ ($LSi === $lastorder) ? '' : ($html .= '<div style="page-break-after: always;"></div>');

                // do count for orders
                $LSi++;

            }// end foreach orders

        }// end if orders else

        $html .= '</body></html>';

        // for debug/testing save html
        $htmlDebug = '<div style="font-family: Arial, Helvetica, sans-serif;
        width: 100%;
        max-width: 750px;
        display: flex;
        flex-direction: column;
        flex-wrap: nowrap;
        border: 1px solid #e5e5e5;
        background-color: #ffffff;
        padding: 30px;
        box-shadow: 0px 0px 7px 0px rgba(0,0,0,0.29);">';
        $htmlDebug .= $html;
        $htmlDebug .= '</div>';

        $htmlDebugOut = file_put_contents(GBWM_UPLOADS_PATH.'classic_pdf_select.html', $htmlDebug.PHP_EOL, LOCK_EX);

        // start dompdf
        $options = new Options();

        // the options
        $options->set('isRemoteEnabled', TRUE);
        $options->set('isHtml5ParserEnabled', TRUE);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
            ]
        ]);

        $dompdf->setHttpContext($context);

        // set paper size
        $dompdf->set_paper("A4");

        $dompdf->load_html($html);
        $dompdf->render();

        $output = $dompdf->output();

        // get the pretty domain name
        $result = tld_extract($WooSite->url);

        // extract
        $SiteURL = $result['hostname'];

        $CreateWriterType = 'PDF';//Word2007, PDF, CSV

        $ext = 'pdf';

        $date = date("d-m-Y-H-i-s");

        file_put_contents(GBWM_UPLOADS_PATH.$SiteURL.'-orders-'.$date.'.'.$ext, $output);

    }// end function

}//end class