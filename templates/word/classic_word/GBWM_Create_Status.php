<?php

/**
 * @package WooMaster Download
 *
 * Template Name = Classic
 *
 */

namespace GBWM_Templates\word\classic_word;

// woocommerce
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

// phpWord
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\TablePosition;
use PhpOffice\PhpWord\Settings;

// TLDExtract
use LayerShifter\TLDExtract\Extract;

class GBWM_Create_Status
{
    public static function gbwm_create_status( $siteID, $status, $templateData )
    {
        // get the groups
        $logo   = $templateData->logo;
        $colors = $templateData->colors;
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

        // escape special chars
        Settings::setOutputEscapingEnabled( true );

        // Creating the new document...
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

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


        if( ! isset( $status ) )
        {
            $status = 'processing';
        }

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
                'status'    => $status,
                'per_page'  => $PerPage,
                'page'      => $PageNumber,
            ] );

            $lastRequest   = $woocommerceAPI->http->getRequest();
            $lastResponse   = $woocommerceAPI->http->getResponse();
            $headers        = $lastResponse->getHeaders();

        // if there is an api error catch and display
        } catch (HttpClientException $e)
        {

            $return = array(
                'debug'  => $siteID.' - '.$template_type,
                'message'           => $e->getMessage(),
                'ID'                => 0,
            );

            wp_send_json($return);
            die();

        }// end catch

        $LSi = 1;
        $lastorder = count($Orders);

        // if there are no orders for this site
        if( $Orders == null || $Orders == 0 )
        {
            // do nothing
        }else{

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

                $section = $phpWord->addSection(
                    array(
                        'marginLeft'    => 550,
                        'marginRight'   => 550,
                        'marginTop'     => 750,
                        'marginBottom'  => 750,
                    )
                );


                /**
                * start by creating the top table
                *
                * columns = 2
                * left column = company info
                * right column = title, order number and order date
                *
                */

                $table = $section->addTable(
                    array(
                        'width' => 100 * 50,
                        'unit' => 'pct',
                        'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
                    )
                );

                // add a single row for the top table
                $table->addRow();

                // set the cells to 30 and 70 %

                // left table = none, site, title, logo
                $cell_text = $table->addCell( Converter::cmToTwip( 14.50 ) );

                $logotype = $templateData->logo->type;

                // if the logo type is site address
                if( $logotype == '' || $logotype == 2 )
                {
                    $arrURL = parse_url( $WooSite->url );

                    $SiteURL = strtoupper( $arrURL['host'] );

                    ($logo->fontsizeurl === '') ? $logo->fontsizeurl = 24 : '';

                    $cell_text->addText(
                        $SiteURL,
                        array(
                            'bold' => true,
                            'size' => Converter::pixelToPoint( $logo->fontsizeurl ),
                            'color' => $colors->header_titles->value,
                            ),
                        array(
                            'spaceAfter' => Converter::pointToTwip( 0 ),
                            )
                    );
                }

                // if the logo type is site address
                if( $logotype == 3 )
                {

                    ($logo->fontsizetext === '') ? $logo->fontsizetext = 24 : '';

                    $cell_text->addText(
                        $logo->textvalue,
                        array(
                            'bold' => true,
                            'size' => Converter::pixelToPoint( $logo->fontsizetext ),
                            'color' => $colors->header_titles->value,
                            ),
                        array(
                            'spaceAfter' => Converter::pointToTwip( 0 ),
                            )
                    );
                }

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

                    // now add the image
                    $cell_text->addImage(
                        $logo_url,
                        array(
                            'width' => Converter::pixelToPoint( $image_width ),
                            //'height' => 210,
                            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT,
                        )
                    );
                }


                // right table = invoice/packing slip, order #, order date
                $cell_text = $table->addCell( Converter::cmToTwip( 4.55 ) );

                $cell_text->addText(
                    'INVOICE',
                    array(
                        'bold' => true,
                        'size' => 20,
                        'color' => $colors->header_titles->value,
                    ),
                    array(
                        'align' => 'right',
                        'spaceAfter' => Converter::pointToTwip( 10 ),
                    )
                );


                if($enable->order_number->value == 'true'){

                    $orderrun = $cell_text->addTextRun(
                        array(
                            'align' => 'right',
                            'spaceAfter' => Converter::pointToTwip( 3 ),
                            )
                    );

                    // order number
                    $orderrun->addText(
                        'Order #: ',
                            array(
                                'bold' => true,
                                'color' => $colors->body_titles->value,
                                ),
                            array(
                                )
                    );

                    $orderrun->addText(
                        $Order->number,
                            array(
                                'color' => $colors->body_text->value,
                                ),
                            array(
                                )
                    );
                }


                if($enable->order_date->value == 'true'){
                    // order date
                    $date = strtotime($Order->date_created);

                    $daterun = $cell_text->addTextRun(
                        array(
                            'align' => 'right',
                            'spaceAfter' => Converter::pointToTwip( 20 ),
                            )
                    );

                    // order number
                    $daterun->addText(
                        'Order Date: ',
                            array(
                                'bold' => true,
                                'color' => $colors->body_titles->value,
                                ),
                            array(
                                )
                    );

                    $daterun->addText(
                        date('M j, Y', $date),
                            array(
                                'color' => $colors->body_text->value,
                                ),
                            array(
                                )
                    );
                }

                // blank line
                $section->addTextBreak(
                    1,
                    array(
                        'size' => 1,
                    ),
                    array(
                        'spaceBefore' => Converter::pointToTwip( 0 ),
                        'spaceAfter' => Converter::pointToTwip( 0 ),
                ));


                /**
                * creating the addresses table
                *
                * columns = 2
                * left column = bill to
                * right column = ship to
                *
                */

                // get the shipping section from the array
                $billing = $Order->billing;

                // get the shipping section from the array
                $shipping = $Order->shipping;

                $table = $section->addTable(
                    array(
                        'width' => 100 * 50,
                        'unit' => 'pct',
                        'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
                    )
                );

                // add a single row for the top table
                $table->addRow();

                // set the cells to 50 and 50 %
                // left table = company name / address
                $cell_text = $table->addCell(50 * 50);

                $cell_text->addText(
                    'Bill To:',
                    array(
                        'bold' => true,
                        'size' => 12,
                        'color' => $colors->body_titles->value,
                        ),
                    array(
                        'spaceAfter' => Converter::pointToTwip( 3 ),
                        )
                );

                $cell_text->addText(
                    $billing->first_name.' '.$billing->last_name,
                    array(
                        'color' => $colors->body_text->value,
                        ),
                    array(
                        'spaceAfter' => Converter::pointToTwip( 0 ),
                        )
                );

                if( ! empty( $billing->company ) )
                {
                    $cell_text->addText(
                        $billing->company,
                        array(
                            'color' => $colors->body_text->value,
                            ),
                        array(
                            'spaceAfter' => Converter::pointToTwip( 0 ),
                            )
                    );
                }

                $cell_text->addText(
                    $billing->address_1,
                    array(
                        'color' => $colors->body_text->value,
                        ),
                    array(
                        'spaceAfter' => Converter::pointToTwip( 0 ),
                            )
                );

                if( ! empty( $billing->company ) )
                {
                    $cell_text->addText(
                        $billing->address_2,
                        array(
                            'color' => $colors->body_text->value,
                            ),
                        array(
                            'spaceAfter' => Converter::pointToTwip( 0 ),
                                )
                    );
                }

                $cell_text->addText(
                    $billing->city,
                    array(
                        'color' => $colors->body_text->value,
                        ),
                    array(
                        'spaceAfter' => Converter::pointToTwip( 0 ),
                            )
                );

                $states = WC()->countries->get_states( $billing->country );

                $state  = ! empty( $states[ $billing->state ] ) ? $states[ $billing->state ] : $billing->state;

                $cell_text->addText(
                    $state,
                    array(
                        'color' => $colors->body_text->value,
                        ),
                    array(
                        'spaceAfter' => Converter::pointToTwip( 0 ),
                        )
                );

                $cell_text->addText(
                    $billing->postcode,
                    array(
                        'color' => $colors->body_text->value,
                        ),
                    array(
                        'spaceAfter' => Converter::pointToTwip( 0 ),
                        )
                );

                $cell_text->addText(
                    WC()->countries->countries[ $billing->country ]
                    ,
                    array(
                        'color' => $colors->body_text->value,
                        ),
                    array(
                        'spaceAfter' => Converter::pointToTwip( 0 ),
                            )
                );

                // right = ship to

                // set the cells to 50 and 50 %
                // left table = company name / address
                $cell_text = $table->addCell(50 * 50);

                $cell_text->addText(
                    'Ship To:',
                    array(
                        'bold' => true,
                        'size' => 12,
                        'color' => $colors->body_titles->value,
                        ),
                    array(
                        'spaceAfter' => Converter::pointToTwip( 3 ),
                        )
                );

                $cell_text->addText(
                    $shipping->first_name.' '.$shipping->last_name,
                    array(
                        'color' => $colors->body_text->value,
                        ),
                    array(
                        'spaceAfter' => Converter::pointToTwip( 0 ),
                        )
                );

                if( ! empty( $shipping->company ) )
                {
                    $cell_text->addText(
                        $shipping->company,
                        array(
                            'color' => $colors->body_text->value,
                            ),
                        array(
                            'spaceAfter' => Converter::pointToTwip( 0 ),
                            )
                    );
                }

                $cell_text->addText(
                    $shipping->address_1,
                    array(
                        'color' => $colors->body_text->value,
                        ),
                    array(
                        'spaceAfter' => Converter::pointToTwip( 0 ),
                        )
                );

                if( ! empty( $shipping->company ) )
                {
                    $cell_text->addText(
                        $shipping->address_2,
                        array(
                            'color' => $colors->body_text->value,
                            ),
                        array(
                            'spaceAfter' => Converter::pointToTwip( 0 ),
                            )
                    );
                }

                $cell_text->addText(
                    $shipping->city,
                    array(
                        'color' => $colors->body_text->value,
                        ),
                    array(
                        'spaceAfter' => Converter::pointToTwip( 0 ),
                        )
                );

                $states = WC()->countries->get_states( $shipping->country );

                $state  = ! empty( $states[ $shipping->state ] ) ? $states[ $shipping->state ] : $shipping->state;

                $cell_text->addText(
                    $state,
                    array(
                        'color' => $colors->body_text->value,
                        ),
                    array(
                        'spaceAfter' => Converter::pointToTwip( 0 ),
                        )
                );

                $cell_text->addText(
                    $shipping->postcode,
                    array(
                        'color' => $colors->body_text->value,
                        ),
                    array(
                        'spaceAfter' => Converter::pointToTwip( 0 ),
                        )
                );

                $cell_text->addText(
                    WC()->countries->countries[ $shipping->country ]
                    ,
                    array(
                        'color' => $colors->body_text->value,
                        ),
                    array(
                        'spaceAfter' => Converter::pointToTwip( 8 ),
                        )
                );


                /**
                * phone email paragraphs
                *
                */

                if($enable->phone->value == 'true'){
                    $phonetextrun = $section->addTextRun(
                        array(
                            'spaceBefore' => Converter::pointToTwip( 1 ),
                            'spaceAfter' => Converter::pointToTwip( 3 ),
                            )
                    );

                    $phonetextrun->addText(
                        'Phone: ',
                            array(
                                'bold' => true,
                                'color' => $colors->body_titles->value,
                                ),
                            array(
                                )
                    );

                    $phonetextrun->addText(
                        $billing->phone,
                            array(
                                'color' => $colors->body_text->value,
                                ),
                            array(
                                )
                    );
                }// end if phone

                if($enable->email->value == 'true'){
                    $emailtextrun = $section->addTextRun(
                        array(
                            'spaceBefore' => Converter::pointToTwip( 1 ),
                            'spaceAfter' => Converter::pointToTwip( 3 ),
                            )
                    );

                    $emailtextrun->addText(
                        'Email: ',
                            array(
                                'bold' => true,
                                'color' => $colors->body_titles->value,
                                ),
                            array(
                                )
                    );

                    $emailtextrun->addText(
                        $billing->email,
                            array(
                                'color' => $colors->body_text->value,
                                ),
                            array(
                                )
                    );
                }



                // blank line
                $section->addTextBreak(
                    1,
                    array(
                        'size' => 1,
                    ),
                    array(
                        'spaceBefore' => Converter::pointToTwip( 4 ),
                        'spaceAfter' => Converter::pointToTwip( 4 ),
                ));






                /**
                 * creating the orders table
                 *
                 * columns = 4 some merged
                 *
                 * Qty
                 * Description
                 * Unit Price
                 * Total
                 *
                 */

                $table = $section->addTable(
                    array(
                        'width' => 97.5 * 50,
                        'unit' => 'pct',
                        'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
                        'cellMargin'=> 50,
                        'cellMarginLeft'=> 100,
                        'cellMarginRight'=> 100,
                        'borderSize' => 8,
                        'borderColor' => $colors->table_border->value,
                    )
                );




                /**
                * header row
                *
                */

                $table->addRow();

                $headerstyle = array(
                    'borderSize' => 8,
                    'borderColor' => $colors->table_header->value,
                    'valign' => 'center',
                    'bgColor' => $colors->table_header->value,
                );

                // quantity column
                $table->addCell( Converter::cmToTwip( 1.96 ), $headerstyle )->addText(
                    'QTY',
                    array(
                        'bold' => true,
                        'color' => $colors->header_text->value,
                            ),
                    array(
                        'align' => 'center',
                        'spaceBefore' => Converter::pointToTwip( 2 ),
                        'spaceAfter' => Converter::pointToTwip( 1 ),
                            )
                );

                // Description column
                $table->addCell( Converter::cmToTwip( 11.96 ), $headerstyle)->addText(
                    'Description',
                    array(
                        'bold' => true,
                        'color' => $colors->header_text->value,
                            ),
                    array(
                        'spaceBefore' => Converter::pointToTwip( 2 ),
                        'spaceAfter' => Converter::pointToTwip( 1 ),
                            )
                );

                // Unit Price column
                $table->addCell( Converter::cmToTwip( 2.67 ), $headerstyle)->addText(
                    'Unit Price',
                    array(
                        'bold' => true,
                        'color' => $colors->header_text->value,
                            ),
                    array(
                        'spaceBefore' => Converter::pointToTwip( 2 ),
                        'spaceAfter' => Converter::pointToTwip( 1 ),
                            )
                );

                // Total column
                $table->addCell( Converter::cmToTwip( 2.81 ), $headerstyle)->addText(
                    'Total',
                    array(
                        'bold' => true,
                        'color' => $colors->header_text->value,
                            ),
                    array(
                        'align' => 'right',
                        'spaceBefore' => Converter::pointToTwip( 2 ),
                        'spaceAfter' => Converter::pointToTwip( 1 ),
                            )
                );




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

                    $table->addRow();

                    /**
                     * here is how this code works
                     * $ar = starts true (Boolean) (set above loop)
                     *
                     * $ar=!$ar MEANS IT WAS TRUE BUT NOW FALSE (TOGGLE TRUE/FALSE)
                     *
                     * ( ( $ar=TOGGLE TRUE OR FALSE )? 'IF TRUE' : 'IF FALSE' )
                     */
                    $rowcolor = ( ( $ar=!$ar )? $colors->alt_row->value : 'ffffff' );

                    $ordersrowstyle = array(
                        'borderSize'=> 8,
                        'borderColor'=> $colors->table_border->value,
                        'valign' => 'center',
                        'bgColor'=> $rowcolor,
                    );

                    // quantity column
                    $table->addCell(Converter::cmToTwip( 1.96 ), $ordersrowstyle)->addText(
                        $product->quantity,
                        array(
                            'size' => 9,
                            'bold' => true,
                            'color' => $colors->body_text->value,
                                ),
                        array(
                            'align' => 'center',
                            'spaceBefore' => Converter::pointToTwip( 2 ),
                            'spaceAfter' => Converter::pointToTwip( 1 ),
                                )
                    );

                    // Description column
                    $Description = $table->addCell(Converter::cmToTwip( 11.96 ), $ordersrowstyle);

                    $Description->addText(
                        $product->name,
                        array(
                            'color' => $colors->body_text->value,
                            'size' => 9,
                                ),
                        array(
                            'spaceBefore' => Converter::pointToTwip( 2 ),
                            'spaceAfter' => Converter::pointToTwip( 1 ),
                                )
                    );
                    if( ! empty( $product->sku ) && $enable->sku->value == 'true' )
                    {
                        $Description->addText(
                            'SKU: '.$product->sku,
                            array(
                                'color' => $colors->body_text->value,
                                'size' => 9,
                                ),
                            array(
                                'spaceBefore' => Converter::pointToTwip( 1 ),
                                'spaceAfter' => Converter::pointToTwip( 1 ),
                                )
                        );
                    }

                    // Unit Price column
                    $table->addCell(Converter::cmToTwip( 2.67 ), $ordersrowstyle)->addText(
                        $currency.number_format($product->price, 2),
                        array(
                            'color' => $colors->body_text->value,
                            'size' => 9,
                                ),
                        array(
                            'spaceBefore' => Converter::pointToTwip( 2 ),
                            'spaceAfter' => Converter::pointToTwip( 1 ),
                                )
                    );

                    // Total column, we need to add
                    // each one up for subtotal

                    $subtotal = $subtotal + number_format($product->total, 2);

                    $table->addCell(Converter::cmToTwip( 2.81 ), $ordersrowstyle)->addText(
                        $currency.number_format((float)$product->total, 2, '.', ''),
                        array(
                            'color' => $colors->body_text->value,
                            'size' => 9,
                                ),
                        array(
                            'align' => 'right',
                            'spaceBefore' => Converter::pointToTwip( 2 ),
                            'spaceAfter' => Converter::pointToTwip( 1 ),
                                )
                    );

                }



                /**
                * Subtotal row (some columns merged)
                *
                */

                // Subtotal column
                $thankyou = array(
                    'gridSpan' => 2,
                    'borderSize'=> 8,
                    'borderColor'=> 'ffffff',
                    'valign' => 'center',
                );

                $table->addRow();

                // Subtotal column
                $subtotalstyle1 = array(
                    'borderSize'=> 8,
                    'borderColor'=> 'ffffff',
                    'valign' => 'center',
                    'bgColor'=> 'ffffff'
                );

                $table->addCell(Converter::cmToTwip( 6 ), $thankyou)->addText(
                    'Thank you for your business',
                    array(
                        'bold' => true,
                        'size' => 10,
                        'color' => $colors->body_text->value,
                            ),
                    array(
                        'align' => 'left',
                        'spaceBefore' => Converter::pointToTwip( 2 ),
                        'spaceAfter' => Converter::pointToTwip( 1 ),
                            )
                );

                $table->addCell(Converter::cmToTwip( 2.67 ), $subtotalstyle1)->addText(
                    'Subtotal:',
                    array(
                        'color' => $colors->body_text->value,
                            ),
                    array(
                        'align' => 'right',
                        'spaceBefore' => Converter::pointToTwip( 2 ),
                        'spaceAfter' => Converter::pointToTwip( 1 ),
                            )
                );

                $subtotalstyle2 = array(
                    'borderSize'=> 8,
                    'borderColor'=> $colors->table_border->value,
                    'valign' => 'center',
                    'bgColor'=> 'ffffff'
                );

                $table->addCell(Converter::cmToTwip( 2.81 ), $subtotalstyle2)->addText(
                    $currency.number_format($subtotal, 2),
                    array(
                        'color' => $colors->body_text->value,
                        'bold' => true,
                            ),
                    array(
                        'align' => 'right',
                        'spaceBefore' => Converter::pointToTwip( 2 ),
                        'spaceAfter' => Converter::pointToTwip( 1 ),
                            )
                );





                /**
                * shipping & handling row (some columns merged)
                *
                */


                $table->addRow();

                // Subtotal column
                $subtotalstyle1 = array(
                    'gridSpan' => 3,
                    'borderSize'=> 8,
                    'borderColor'=> 'ffffff',
                    'valign' => 'center',
                    'bgColor'=> 'ffffff',
                );

                // Subtotal column
                $subtotalstyleShip = array(
                    'gridSpan' => 3,
                    'borderSize'=> 8,
                    'borderColor'=> 'ffffff',
                    'valign' => 'center',
                    'bgColor'=> 'ffffff',
                    'cellMargin'=> 0,
                );

                // shipping column
                $ShippingCell = $table->addCell(Converter::cmToTwip( 2.67 ), $subtotalstyleShip);

                $ShippingCell->addText(
                    'Shipping &',
                    array(
                        'color' => $colors->body_text->value,
                            ),
                    array(
                        'align' => 'right',
                        'spaceBefore' => Converter::pointToTwip( 0 ),
                        'spaceAfter' => Converter::pointToTwip( 1 ),
                            )
                );

                $ShippingCell->addText(
                    'Handling:',
                    array(
                        'color' => $colors->body_text->value,
                        ),
                    array(
                        'align' => 'right',
                        'spaceBefore' => Converter::pointToTwip( 1 ),
                        'spaceAfter' => Converter::pointToTwip( 0 ),
                        )
                );

                $subtotalstyle2 = array(
                    'borderSize'=> 8,
                    'borderColor'=> $colors->table_border->value,
                    'valign' => 'center',
                    'bgColor'=> 'ffffff'
                );

                $totalshipping = 0;

                foreach($Order->shipping_lines as $shippingprice)
                {
                    $totalshipping = $totalshipping + $shippingprice->total;
                }

                $table->addCell(Converter::cmToTwip( 2.81 ), $subtotalstyle2)->addText(
                    $currency.number_format($totalshipping, 2),
                    array(
                        'color' => $colors->body_text->value,
                            ),
                    array(
                        'align' => 'right',
                        'spaceBefore' => Converter::pointToTwip( 2 ),
                        'spaceAfter' => Converter::pointToTwip( 1 ),
                            )
                );





                /**
                * tax row (some columns merged)
                *
                */



                $table->addRow();

                $total_tax = number_format($Order->total_tax, 2);

                // Subtotal column
                $subtotalstyle1 = array(
                    'gridSpan' => 3,
                    'borderSize'=> 8,
                    'borderColor'=> 'ffffff',
                    'valign' => 'center',
                    'bgColor'=>'ffffff'
                );

                $table->addCell(Converter::cmToTwip( 2.67 ), $subtotalstyle1)->addText(
                    'Tax:',
                    array(
                        'color' => $colors->body_text->value,
                            ),
                    array(
                        'align' => 'right',
                        'spaceBefore' => Converter::pointToTwip( 2 ),
                        'spaceAfter' => Converter::pointToTwip( 1 ),
                            )
                );

                $subtotalstyle2 = array(
                    'borderSize'=> 8,
                    'borderColor'=> $colors->table_border->value,
                    'valign' => 'center',
                    'bgColor'=>'ffffff'
                );

                $table->addCell(Converter::cmToTwip( 2.81 ), $subtotalstyle2)->addText(
                    $currency.number_format($total_tax, 2),
                    array(
                        'color' => $colors->body_text->value,
                            ),
                    array(
                        'align' => 'right',
                        'spaceBefore' => Converter::pointToTwip( 2 ),
                        'spaceAfter' => Converter::pointToTwip( 1 ),
                            )
                );





                /**
                * total row (some columns merged)
                *
                */

                $table->addRow();

                // Subtotal column
                $subtotalstyle1 = array(
                    'gridSpan' => 3,
                    'borderSize'=> 8,
                    'borderColor'=>'ffffff',
                    'valign' => 'center',
                    'bgColor'=>'ffffff'
                );

                $table->addCell(Converter::cmToTwip( 2.67 ), $subtotalstyle1)->addText(
                    'Total:',
                    array(
                        'color' => $colors->body_text->value,
                            ),
                    array(
                        'align' => 'right',
                        'spaceBefore' => Converter::pointToTwip( 2 ),
                        'spaceAfter' => Converter::pointToTwip( 1 ),
                            )
                );

                $subtotalstyle3 = array(
                    'borderSize'=> 8,
                    'borderColor'=> $colors->table_header->value,
                    'valign' => 'center',
                    'bgColor'=> $colors->table_header->value,
                );

                $table->addCell(Converter::cmToTwip( 2.81 ), $subtotalstyle3)->addText(
                    $currency.$totalPrice = number_format( $subtotal + $total_tax + $totalshipping, 2 ),
                    array(
                        'color' => $colors->header_text->value,
                            ),
                    array(
                        'align' => 'right',
                        'spaceBefore' => Converter::pointToTwip( 2 ),
                        'spaceAfter' => Converter::pointToTwip( 1 ),
                            )
                );





                /**
                * if its the last page do not do a pagebreak
                *
                */

                @ ($LSi === $lastorder) ? '' : $section->addPageBreak();

                // do count for orders
                $LSi++;

            }// end foreach orders

        }// end if orders else

        // get the pretty domain name
        $result = tld_extract($WooSite->url);

        // extract
        $SiteURL = $result['hostname'];

        $CreateWriterType = 'Word2007';//Word2007, PDF, CSV

        $ext = 'docx';

        // Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter( $phpWord, $CreateWriterType );

        $date = date("d-m-Y-H-i-s");

        $objWriter->save( GBWM_UPLOADS_PATH.$SiteURL.'-orders-'.$date.'.'.$ext );

    }// end function

}//end class