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

class GBWM_Create_Test
{
    public static function gbwm_create_selected( $siteID, $idsArr, $templateData )
    {

        // Creating the new document...
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $phpWord->setDefaultParagraphStyle(
            array(
                //'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT,
                'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(1),
                'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(1),
                //'lineHeight' => 1,
            )
        );

        $section = $phpWord->addSection();

        $html = '';

        $html .= '<table style="width: 100%; font-size: 13px; border-collapse: collapse;">
        <tbody>
            <tr>
                <td class="company-info vtop" style="width: 480px;
                max-width: 480px;
                height: 90px;
                max-height: 90px;
                text-align: left;
                padding: 0px;
                margin: 0px;">

                <span style="font-weight: bold;
                color: #008d21;
                font-size: 68px;
                line-height: 72px;
                padding: 0px;
                margin: 0px;">WooMulti</span>
                <br />

            </td>

                <td class="invoice-info vtop" style="width: 200px;
                max-width: 200px;
                text-align: right;
                vertical-align: top;">

                <span style="font-size: 24px;
                font-weight: bold;
                color: #008d21;">INVOICE</span><br/><div><span style="font-weight: bold;
                color: #008d21;">Order #:</span> 46<br/></div><div><span style="font-weight: bold;
                color: #008d21;">Order Date:</span> Sep 1, 2018<br/></div></td>
            </tr>
        </tbody>
    </table>

    <br />

    <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
        <tbody>
            <tr>
                <td class="bill-to vtop" style="width: 50%; text-align: left;">
                <span style="font-weight: bold; color: #008d21;">Bill To:</span><br />
                <span>Customer Four</span><br />

                <span>4 Something Street</span><br />
                <span>City Centre</span><br />
                <span>Sunderland</span><br />
                <span>Tyne And Wear</span><br />
                <span>SR1 1AB</span><br />
                <span>United Kingdom (UK)</span><br /><br />

                <span style="font-weight: bold; color: #008d21;">Email:</span> test@test.com</td>

                <td class="ship-to vtop" style="width: 50%; text-align: left;">
                <span style="font-weight: bold; color: #008d21;">Ship To:</span><br />
                <span>Customer Four</span><br />

                <span>4 Something Street</span><br />
                <span>City Centre</span><br />
                <span>Sunderland</span><br />
                <span>Tyne And Wear</span><br />
                <span>SR1 1AB</span><br />
                <span>United Kingdom (UK)</span><br /><br />
                </td>
            </tr>
        </tbody>
    </table>

    <br />

    <table style="width: 100%; font-size: 13px; border-collapse: collapse;">

        <tbody>

            <tr style="background-color: #008d21;">

                <td style="color: #ffffff;
                width: 60px;
                padding: 5px;
                font-weight: bold;
                text-align: center;
                vertical-align: middle;">Qty</td>

                <td style="color: #ffffff;
                padding: 5px;
                font-weight: bold;
                text-align: left;
                vertical-align: middle;">Description</td>

                <td style="color: #ffffff;
                width: 70px;
                padding: 5px;
                font-weight: bold;
                text-align: left;
                vertical-align: middle;">Unit Price</td>

                <td style="color: #ffffff;
                width: 70px;
                padding: 5px;
                font-weight: bold;
                text-align: right;
                vertical-align: middle;">Total</td>

            </tr>

            <tr style="background-color: #ffffff">
                    <td class="center bold">1</td>
                    <td>Sunglasses</td>
                    <td>£90.00</td>
                    <td class="text-right">£90.00</td>
            </tr>

            <tr style="background-color: #f6f6f6">
                    <td class="center bold">1</td>
                    <td>T-shirt</td>
                    <td>£18.00</td>
                    <td class="text-right">£18.00</td>
            </tr>

            <tr style="background-color: #ffffff">
                <td class="center bold">1</td>
                <td>V-neck T-shirt</td>
                <td>£18.00</td>
                <td class="text-right">£18.00</td>
            </tr>

        </tbody>
    </table>

    <br />

    <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
        <tbody>

            <tr>
                <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>
                <td class="totals no-top-border no-bottom-border no-left-border">Subtotal:</td>
                <td class="curency-totals no-top-border bold">£126.00</td>
            </tr><tr>
                <td class="thanks no-top-border no-bottom-border no-left-border no-right-border" style="font-weight: bold;">Thank you for your business</td>

                <td class="totals no-top-border no-bottom-border no-left-border">Shipping & Handling:</td>

                <td class="curency-totals">£3.99</td>
            </tr>

            <tr>
                <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>

                <td class="totals no-top-border no-bottom-border no-left-border">Tax:</td>

                <td class="curency-totals" style="border-bottom: 1px solid #008d21;">£0.00</td>
            </tr>

            <tr>
                <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>

                <td class="totals no-top-border no-bottom-border no-left-border no-right-border">Total:</td>

                <td class="curency-totals bold table-header" style="border: 1px solid #008d21; background-color: #008d21; color: #ffffff;">£129.99</td>
            </tr>

        </tbody>
    </table>';



        // escape special chars
        Settings::setOutputEscapingEnabled( true );

        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);



        $CreateWriterType = 'Word2007';//Word2007, PDF, CSV

        $ext = 'docx';

        // Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter( $phpWord, $CreateWriterType );

        // current date and time for filename
        $date = date("d-m-Y-H-i-s");

        $objWriter->save( GBWM_UPLOADS_PATH.'TEST-orders-'.$date.'.'.$ext );

    }// end function

}//end class