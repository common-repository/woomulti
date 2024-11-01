/**********************
* Template Name: Classic - Word
* Author: GenBuz
* Version: 1.1
**********************/

(function ($) {
    $(document).ready(function ($) {

        // set no idons for the accordion
        var icons = {
            header: 'no-icon',
            activeHeader: 'no-icon'
        };

        // start the accordion
        $( '#site-template-accordion' ).accordion({
            heightStyle: 'content',
            icons: icons,
        });




        /**
         * load the template data on page load
        */

        // now set the line-height
        $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.siteurl, .gbwm-css #ajaxTemplateContainer .logo .statlogo.text').css( {'line-height': 'normal'} );

        // header_titles
        var header_titles = $('.gbwm-css #template-sidebar #header_titles').attr('data-color');

        $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.siteurl, .gbwm-css #ajaxTemplateContainer .logo .statlogo.text, .gbwm-css #ajaxTemplateContainer .header_titles').css( {'color': '#'+header_titles} );

        // body_titles
        var body_titles = $('.gbwm-css #template-sidebar #body_titles').attr('data-color');

        $('.gbwm-css #ajaxTemplateContainer .body_titles').css({'color': '#'+body_titles});

        // body_text
        var body_text = $('.gbwm-css #template-sidebar #body_text').attr('data-color');

        $( '.gbwm-css #ajaxTemplateContainer .body_text' ).each(function () {
            this.style.setProperty( 'color', '#'+body_text, 'important' );
        });

        // table_header
        var table_header =  $('.gbwm-css #template-sidebar #table_header').attr('data-color');

        $('.gbwm-css #ajaxTemplateContainer .header-cell').css({'background-color': '#'+table_header});

        $('.gbwm-css #ajaxTemplateContainer .orders-table .table_header .header-cell, .gbwm-css #ajaxTemplateContainer td.totalamount').css({'border': '1px solid #'+table_header});

        // header_text
        var header_text = $('.gbwm-css #template-sidebar #header_text').attr('data-color');

        $( '.gbwm-css #ajaxTemplateContainer .header-cell' ).each(function () {
            this.style.setProperty( 'color', '#'+header_text, 'important' );
        });

        // table_border
        var table_border = $('.gbwm-css #template-sidebar #table_border').attr('data-color');

        $('.gbwm-css #ajaxTemplateContainer .order-cell, .gbwm-css #ajaxTemplateContainer td.amount').css({'border-color': '#'+table_border});

        // alt_row
        var alt_row = $('.gbwm-css #template-sidebar #alt_row').attr('data-color');

        $('.gbwm-css #ajaxTemplateContainer .alt_row').css({'background-color': '#'+alt_row});







        /**
         * load the color picker
        */

        $('.gbwm-css #template-sidebar #header_titles').spectrum({
            preferredFormat: 'hex',
            showInitial: true,
            showInput: true,
            replacerClassName: 'colorPicker',
            move: function (color) {

                $('.gbwm-css #ajaxTemplateContainer .header_titles').css({'color': '#'+color.toHex()});
            },
            change: function(color) {

                $('.gbwm-css #template-sidebar #header_titles').attr('data-color', color.toHex());

                $('.gbwm-css #ajaxTemplateContainer .header_titles').css({'color': '#'+color.toHex()});

            }
        });

        $('.gbwm-css #template-sidebar #body_titles').spectrum({
            preferredFormat: 'hex',
            showInitial: true,
            showInput: true,
            move: function (color) {
                $('.gbwm-css #ajaxTemplateContainer .body_titles').css({'color': '#'+color.toHex()});
            },
            change: function(color) {

                $('.gbwm-css #template-sidebar #body_titles').attr('data-color', color.toHex());

                $('.gbwm-css #ajaxTemplateContainer .body_titles').css({'color': '#'+color.toHex()});

            }
        });

        $('.gbwm-css #template-sidebar #body_text').spectrum({
            preferredFormat: 'hex',
            showInitial: true,
            showInput: true,
            move: function (color) {

                $( '.gbwm-css #ajaxTemplateContainer .body_text' ).each(function () {
                    this.style.setProperty( 'color', '#'+color.toHex(), 'important' );
                });
            },
            change: function(color) {

                $('.gbwm-css #template-sidebar #body_text').attr('data-color', color.toHex());

                $( '.gbwm-css #ajaxTemplateContainer .body_text' ).each(function () {
                    this.style.setProperty( 'color', '#'+color.toHex(), 'important' );
                });

            }
        });

        $('.gbwm-css #template-sidebar #table_header').spectrum({
            preferredFormat: 'hex',
            showInitial: true,
            showInput: true,
            move: function (color) {

                $('.gbwm-css #ajaxTemplateContainer .header-cell').css({'background-color': '#'+color.toHex()});

                $('.gbwm-css #ajaxTemplateContainer .orders-table .table_header .header-cell, .gbwm-css #ajaxTemplateContainer td.totalamount').css({'border': '1px solid #'+color.toHex()});
            },
            change: function(color) {

                $('.gbwm-css #template-sidebar #table_header').attr('data-color', color.toHex());

                $('.gbwm-css #ajaxTemplateContainer .header-cell').css({'background-color': '#'+color.toHex()});

                $('.gbwm-css #ajaxTemplateContainer .orders-table .table_header .header-cell, .gbwm-css #ajaxTemplateContainer td.totalamount').css({'border': '1px solid #'+color.toHex()});

            }
        });

        $('.gbwm-css #template-sidebar #header_text').spectrum({
            preferredFormat: 'hex',
            showInitial: true,
            showInput: true,
            move: function (color) {

                $( '.gbwm-css #ajaxTemplateContainer .header-cell' ).each(function () {
                    this.style.setProperty( 'color', '#'+color.toHex(), 'important' );
                });
            },
            change: function(color) {

                $('.gbwm-css #template-sidebar #header_text').attr('data-color', color.toHex());

                $( '.gbwm-css #ajaxTemplateContainer .header-cell' ).each(function () {
                    this.style.setProperty( 'color', '#'+color.toHex(), 'important' );
                });

            }
        });

        $('.gbwm-css #template-sidebar #table_border').spectrum({
            preferredFormat: 'hex',
            showInitial: true,
            showInput: true,
            move: function (color) {

                $('.gbwm-css #ajaxTemplateContainer .order-cell, .gbwm-css #ajaxTemplateContainer td.amount').css({'border-color': '#'+color.toHex()});
            },
            change: function(color) {

                $('.gbwm-css #template-sidebar #table_border').attr('data-color', color.toHex());

                $('.gbwm-css #ajaxTemplateContainer .order-cell, .gbwm-css #ajaxTemplateContainer td.amount').css({'border-color': '#'+color.toHex()});

            }
        });

        $('.gbwm-css #template-sidebar #alt_row').spectrum({
            preferredFormat: 'hex',
            showInitial: true,
            showInput: true,
            move: function (color) {

                $('.gbwm-css #ajaxTemplateContainer .alt_row').css({'background-color': '#'+color.toHex()});
            },
            change: function(color) {

                $('.gbwm-css #template-sidebar #alt_row').attr('data-color', color.toHex());

                $('.gbwm-css #ajaxTemplateContainer .alt_row').css({'background-color': '#'+color.toHex()});

            }
        });





        /**
         * logo options
         */

        $(this).off('click', '.gbwm-css .template-radio .logo').on('click', '.gbwm-css .template-radio .logo', function() {

            // get the checked value
            var radioValue = $("input[name='logo']:checked"). val();

            if(radioValue == 1 ){
                // hide the text and url inputs
                $('.template-radio-text, .template-radio-url').hide();
                $('.fontsize-container .fontsize-box.text').hide();
                $('.fontsize-container .fontsize-box.url').hide();

                // hide the logo
                $('.gbwm-css #ajaxTemplateContainer .logo').html( '<span class="header_titles statlogo nologo"></span>' );

            }

            if( radioValue == 2 ){
                // hide the text and url inputs
                $('.template-radio-text, .template-radio-url').hide();
                // show/hide the font size input
                $('.fontsize-container .fontsize-box.url').show();
                $('.fontsize-container .fontsize-box.text').hide();

                // set the fontsize
                var fontsizeurl = $('.gbwm-css #template-sidebar #fontsize-url').val();

                console.log('radio-url: '+fontsizeurl);

                if(fontsizeurl == ''){
                    $('.gbwm-css #template-sidebar #fontsize-url').val(24);
                }

            }

            if( radioValue == 3 ){
                // show the text input if it isnt already visible
                $('.template-radio-text').show();
                $('.template-radio-url').hide();
                // show/hide the font size input
                $('.fontsize-container .fontsize-box.text').show();
                $('.fontsize-container .fontsize-box.url').hide();

                // set the fontsize
                var fontsizetext = $('.gbwm-css #template-sidebar #fontsize-text').val();

                if(fontsizetext == ''){
                    $('.gbwm-css #template-sidebar #fontsize-text').val(24);
                }

            }

            if( radioValue == 4 ){
                // show the text input if it isnt already visible
                $('.template-radio-url').show();
                $('.template-radio-text').hide();
                $('.fontsize-container .fontsize-box.text').hide();
                $('.fontsize-container .fontsize-box.url').hide();
            }

        }); // end logo options






        /**
         * update siteurl on change
         */
        $( this ).off('click keyup change focus', '.gbwm-css #logo-radio #siteurl').on( 'click keyup change focus', '.gbwm-css #logo-radio #siteurl', function() {

            // the site url
            var thissite = $( '.gbwm-css .SetTemplateTD #ThisSite' ).val();

            // lets get just the name (no http(s)://)
            var a = document.createElement("A");
            a.href = thissite;

            // update the logo section
            $('.gbwm-css #ajaxTemplateContainer .logo').html( '<span class="header_titles statlogo siteurl">'+a.hostname.toUpperCase()+'</span>' );

            // set the fontsize
            var fontsizeurl = $('.gbwm-css #template-sidebar #fontsize-url').val();

            if(fontsizeurl == ''){
                $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.siteurl').css({'font-size': '24px'});
            }else{
                $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.siteurl').css({'font-size': fontsizeurl+'px'});
            }

            // now set the line-height
            $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.siteurl').css( {'line-height': 'normal'} );

            // now get the color
            var header_titles_color = rgb2hex($('.gbwm-css #template-sidebar #header_titles').attr('data-color'));

            // now set the color
            $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.siteurl').css( {'color': '#'+header_titles_color} );

        });






        /**
         * update fontsizeurl on minus click
         */
        $( this ).off('click', '.gbwm-css #template-sidebar .fontsize-box.url .fa-minus-square').on( 'click', '.gbwm-css #template-sidebar .fontsize-box.url .fa-minus-square', function() {

            var fontsizeurl = $('.gbwm-css #template-sidebar #fontsize-url').val();

            console.log(fontsizeurl);

            if(fontsizeurl < 14 ){
                fontsizeurl = 14;
                $('.gbwm-css #template-sidebar #fontsize-url').val(fontsizeurl);
            }else{
                fontsizeurl--;
                $('.gbwm-css #template-sidebar #fontsize-url').val(fontsizeurl);

                console.log('- '+fontsizeurl);
            }

            // now set the font-size
            $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.siteurl').css( {'font-size': fontsizeurl+'px'} );

        });






        /**
         * update fontsizeurl on plus click
         */
        $( this ).off('click', '.gbwm-css #template-sidebar .fontsize-box.url .fa-plus-squaree').on( 'click', '.gbwm-css #template-sidebar .fontsize-box.url .fa-plus-square', function() {

            var fontsizeurl = $('.gbwm-css #template-sidebar #fontsize-url').val();

            console.log(fontsizeurl);

            if(fontsizeurl <= 14){
                fontsizeurl = 14;
            }

            if(fontsizeurl >= 72 ){
                fontsizeurl = 72;
                $('.gbwm-css #template-sidebar #fontsize-url').val(fontsizeurl);
            }else{
                fontsizeurl++;
                $('.gbwm-css #template-sidebar #fontsize-url').val(fontsizeurl);

                console.log('+ '+fontsizeurl);
            }

            // now set the line-height
            $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.siteurl').css( {'font-size': fontsizeurl+'px'} );

        });






        /**
         * update fontsizeurl on change
         */
        $( this ).off('click keyup change focus', '.gbwm-css #template-sidebar #fontsize-url').on( 'click keyup change focus', '.gbwm-css #template-sidebar #fontsize-url', function() {

            var fontsizeurl = $('.gbwm-css #template-sidebar #fontsize-url').val();

            console.log(fontsizeurl)

            // now set the line-height
            $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.siteurl').css( {'font-size': fontsizeurl+'px'} );

        });






        /**
         * update text on change
         */
        $( this ).off('click keyup change focus', '.gbwm-css #logo-radio .template-radio-text input[name="radio-text"], .gbwm-css #logo-radio .template-radio #text').on( 'click keyup change focus', '.gbwm-css #logo-radio .template-radio-text input[name="radio-text"], .gbwm-css #logo-radio .template-radio #text', function() {

            // the key
            var textvalue = $( '.gbwm-css #logo-radio .template-radio-text input[name="radio-text"]' ).val();

            $('.gbwm-css #ajaxTemplateContainer .logo').html( '<span class="header_titles statlogo text">'+textvalue+'</span>' );

            $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.text').css( {'line-height': 'normal'} );

            // set the fontsize
            var fontsizetext = $('.gbwm-css #template-sidebar #fontsize-text').val();

            if(fontsizetext == ''){
                $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.text').css({'font-size': '24px'});
            }else{
                $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.text').css({'font-size': fontsizetext+'px'});
            }

            // now get the color
            var header_titles_color = rgb2hex($('.gbwm-css #template-sidebar #header_titles').attr('data-color'));

            // now set the color
            $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.text').css( {'color': '#'+header_titles_color} );


        });






        /**
         * update fontsizetext on minus click
         */
        $( this ).off('click', '.gbwm-css #template-sidebar .fontsize-box.text .fa-minus-square').on( 'click', '.gbwm-css #template-sidebar .fontsize-box.text .fa-minus-square', function() {

            var fontsizetext = $('.gbwm-css #template-sidebar #fontsize-text').val();

            console.log(fontsizetext);

            if(fontsizetext <= 14 ){
                fontsizetext = 14;
            }else{
                fontsizetext--;

                console.log('- '+fontsizetext);
            }

            // update the input
            $('.gbwm-css #template-sidebar #fontsize-text').val(fontsizetext);

            // now set the line-height
            $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.text').css( {'font-size': fontsizetext+'px'} );

        });






        /**
         * update fontsizetext on plus click
         */
        $( this ).off('click', '.gbwm-css #template-sidebar .fontsize-box.text .fa-plus-square').on( 'click', '.gbwm-css #template-sidebar .fontsize-box.text .fa-plus-square', function() {

            var fontsizetext = $('.gbwm-css #template-sidebar #fontsize-text').val();

            console.log(fontsizetext);

            if(fontsizetext <= 14){
                fontsizetext = 14;
            }

            if(fontsizetext > 72 ){
                fontsizetext = 72;
                $('.gbwm-css #template-sidebar #fontsize-text').val(fontsizetext);
            }else{
                fontsizetext++;
                $('.gbwm-css #template-sidebar #fontsize-text').val(fontsizetext);

                console.log('- '+fontsizetext);
            }

            // now set the line-height
            $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.text').css( {'font-size': fontsizetext+'px'} );

        });






        /**
         * update fontsizetext on change
         */
        $( this ).off( 'click keyup change focus', '.gbwm-css #template-sidebar #fontsize-text' ).on( 'click keyup change focus', '.gbwm-css #template-sidebar #fontsize-text', function() {

            var fontsizetext = $('.gbwm-css #template-sidebar #fontsize-text').val();

            console.log(fontsizetext)

            // now set the line-height
            $('.gbwm-css #ajaxTemplateContainer .logo .statlogo.text').css( {'font-size': fontsizetext+'px'} );

        });






        /**
         * update image on change
         */
        $( this ).off( 'click keyup change focus', '.gbwm-css #logo-radio .template-radio-url input[name="radio-url"], .gbwm-css #logo-radio .template-radio #image' ).on( 'click keyup change focus', '.gbwm-css #logo-radio .template-radio-url input[name="radio-url"], .gbwm-css #logo-radio .template-radio #image', function() {

            // the url
            var urlvalue = $( '.gbwm-css #logo-radio .template-radio-url input[name="radio-url"]' ).val();

            $('.gbwm-css #ajaxTemplateContainer .logo').html( '<img src="'+urlvalue+'" class="statlogo image"/>' );
        });






        /**
         * enable / disable section
         */
        $(this).off( 'click', '.gbwm-css .template-radio .enable' ).on('click', '.gbwm-css .template-radio .enable', function() {

            // the key
            var key = $(this).attr('id');

            if($(this).prop("checked") == true){

                // set new value
                $(this).val('true');

                // show item
                $('.gbwm-css #ajaxTemplateContainer .'+key).show();

            }else{

                // set new value
                $(this).val('false');

                // hide item
                $('.gbwm-css #ajaxTemplateContainer .'+key).hide();

            }

        });






        /**
         * save section
         */

        $(this).off('click', '.gbwm-css #template-sidebar .sidebar-save').on('click', '.gbwm-css #template-sidebar .sidebar-save', function() {

            console.log('One click word')

            /**
             * template values
             */

            // siteID
            var siteID = $( '.gbwm-css .SetTemplateTD #ThisSite' ).attr( 'data-siteid' );
            console.log('siteID - '+siteID);

            var templateID = $(".gbwm-css #template-sidebar #Template option:selected").val();
            console.log('templateID - '+templateID);

            // template_type
            var template_type = $(".gbwm-css #template-sidebar #Template option:selected").attr( 'data-templatetype' );
            console.log('template_type - '+template_type);

            // template_name
            var template_name = $(".gbwm-css #template-sidebar #Template option:selected").attr( 'data-templatename' );
            console.log('template_name - '+template_name);

            // saveType
            var saveType = $( '.gbwm-css .SetTemplateTD #ThisSite' ).attr( 'data-savetype' );
            console.log('saveType - '+saveType);




            /**
             * Logo values
             */

            // get the logo value
            var logoType = $(".gbwm-css #template-sidebar input[name='logo']:checked").val();
            console.log('logoType - '+logoType);

            var fontsizeurl = $('.gbwm-css #template-sidebar #fontsize-url').val();
            console.log('fontsizeurl - '+fontsizeurl);

            var textvalue = $( '.gbwm-css #template-sidebar #radio-text.radio-extra' ).val();
            console.log('textvalue - '+textvalue);

            var fontsizetext = $('.gbwm-css #template-sidebar #fontsize-text').val();
            console.log('fontsizetext - '+fontsizetext);

            var imagevalue = $( '.gbwm-css #template-sidebar #radio-url.radio-extra' ).val();
            console.log('imagevalue - '+imagevalue);





            /**
             * color values
             */

            // header_titles value
            var header_titles = rgb2hex($('.gbwm-css #template-sidebar #header_titles').attr('data-color'));

            console.log('header_titles - '+header_titles);

            // body_titles value
            var body_titles = rgb2hex($('.gbwm-css #template-sidebar #body_titles').attr('data-color'));

            console.log('body_titles - '+body_titles);

            // body_text value
            var body_text = rgb2hex($('.gbwm-css #template-sidebar #body_text').attr('data-color'));

            console.log('body_text - '+body_text);

            // table_header value
            var table_header = rgb2hex($('.gbwm-css #template-sidebar #table_header').attr('data-color'));

            console.log('table_header - '+table_header);

            // header_text value
            var header_text = rgb2hex($('.gbwm-css #template-sidebar #header_text').attr('data-color'));

            console.log('header_text - '+header_text);

            // table_border value
            var table_border = rgb2hex($('.gbwm-css #template-sidebar #table_border').attr('data-color'));

            console.log('table_border - '+table_border);

            // alt_row value
            var alt_row = rgb2hex($('.gbwm-css #template-sidebar #alt_row').attr('data-color'));

            console.log('alt_row - '+alt_row);





            /**
             * Enable values
             */

            // order_number value
            var order_number = $( '.gbwm-css #template-sidebar .sidebar-options #order_number' ).val();
            console.log('order_number - '+order_number);

            // order_number value
            var order_date = $( '.gbwm-css #template-sidebar .sidebar-options #order_date' ).val();
            console.log('order_date - '+order_date);

            // phone value
            var phone = $( '.gbwm-css #template-sidebar .sidebar-options #phone' ).val();
            console.log('phone - '+phone);

            // email value
            var email = $( '.gbwm-css #template-sidebar .sidebar-options #email' ).val();
            console.log('email - '+email);

            // sku value
            var sku = $( '.gbwm-css #template-sidebar .sidebar-options #sku' ).val();
            console.log('sku - '+sku);

            if(typeof textvalue === 'undefined'){
                var textvalue = '';
            }

            if(typeof fontsizeurl === 'undefined'){
                var fontsizeurl = '';
            }

            if(typeof fontsizetext === 'undefined'){
                var fontsizetext = '';
            }

            if(typeof imagevalue === 'undefined'){
                var imagevalue = '';
            }


            var templateData = {

                template_name:  template_name,
                template_type:  template_type,
                templateID:     templateID,

                logo: {
                    // 1=no logo,2=site url,3=text,4=image
                    type:           logoType,
                    fontsizeurl:    fontsizeurl,// for type 2
                    textvalue:      textvalue,// for type 3
                    fontsizetext:   fontsizetext,// for type 3
                    imagevalue:       imagevalue,// for type 4
                },

                colors: {
                    header_titles: {
                        title: 'Header Titles',
                        value: header_titles,
                    },
                    body_titles: {
                        title: 'Body Titles',
                        value: body_titles,
                    },
                    body_text: {
                        title: 'Body Text',
                        value: body_text,
                    },
                    table_header: {
                        title: 'Table Header',
                        value: table_header,
                    },
                    header_text: {
                        title: 'Header Text',
                        value: header_text,
                    },
                    table_border: {
                        title: 'Table Border',
                        value: table_border,
                    },
                    alt_row: {
                        title: 'Alternate Row',
                        value: alt_row,
                    },
                },

                options: {
                    Enable: {
                        order_number: {
                            title: 'Order Number',
                            value: order_number,
                        },
                        order_date: {
                            title: 'Order Date',
                            value: order_date,
                        },
                        phone: {
                            title: 'Phone',
                            value: phone,
                        },
                        email: {
                            title: 'Email',
                            value: email,
                        },
                        sku: {
                            title: 'SKU',
                            value: sku,
                        },
                    }
                },
            };

            $('<div class="center"><h1 id="savingModal" class="title-font"><i class="fas fa-spinner fa-spin"></i>&nbsp;&nbsp;&nbsp;Saving Template</h1></div>').modal({
                onClose: function(dialog) {
                    dialog.data.delay(1100).fadeOut(500, function() {
                        $.modal.close();
                    });
                },
                overlayClose:true
            });


            // now send the values to the ajax
            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {'action': 'gbwm_ajax', 'siteID': siteID, 'templateData' : templateData, 'saveType' : saveType, 'ajaxFunction': 'SaveSiteTemplate'},

                success: function(result) {

                    // if the id is 0 then its an error
                    if(result.ID == 0){

                        $('.faf.fa-spinner.fa-spin').removeClass('fa-spinner, fa-spin');

                        $('.savingModal').text('Sorry there was an error saving the template.<br>Error:<br><strong>'+result.message+'</strong><br><br><button type="button" class="btn btn-dark btn-xs simplemodal-close">Close</button>');

                        //

                    }

                    // if the id is 1 then its a success
                    if(result.ID == 1){

                        // update saveType
                        var saveType = $( '.gbwm-css .SetTemplateTD #ThisSite' ).attr( 'data-savetype' );

                        if(saveType == 'Insert'){
                            $( '.gbwm-css .SetTemplateTD #ThisSite' ).attr( 'data-savetype', 'Update' );
                        }

                        $('.faf.fa-spinner.fa-spin').removeClass('fa-spinner, fa-spin');

                        $('#savingModal').text('Template Saved');

                        // close the modal
                        $.modal.close();
                    }

                },

                error: function(xhr, desc, err) {

                    // simply set the "force" parameter to true:
                    $('.WooAPIContainer'+siteID+' .widefat.gbwmtable').LoadingOverlay('hide', true);

                }

            }); // end ajax call

        });// end save section



        /**
         * functions
         */

        // convert rgb(a) to hex
        function rgb2hex(rgb) {
            if (  rgb.search("rgb") == -1 ) {
                 return rgb;
            } else {
                 rgb = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
                 function hex(x) {
                      return ("0" + parseInt(x).toString(16)).slice(-2);
                 }
                 return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
            }
       }



    });// end document ready

})(jQuery);