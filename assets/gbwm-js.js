(function ($) {
    $(document).ready(function ($) {

        /**
         * some resets
         */

        $('.alert').hide();


        /**
         * activate the tabs
         */
        // menu tabs
        $('#tabbed-tabs').tabs( { active: 0 } );

        // help tabs
        $('#help-tabs').tabs( { active: 0 } );



        /**
         * check if download cron is running
         */
        if ($('body .check-cron').length > 0)
        {
            /* magic ... */
        }





        /**
         * Check All Checkboxes on orders page
         */
        $(this).on('click', '#cb-select-all', function(e) {

            var siteID = $(this).data('siteid');

            $(':checkbox.checkbox'+siteID).prop('checked', this.checked);

        }); // end check all boxes on orders page





        /**
         * Check All Checkboxes on downloads page
         */
        $(this).on('click', '#cb-select-all', function(e) {

            $(':checkbox.checkbox').prop('checked', this.checked);

        }); // end check all boxes on downloads page








        /**
         * Manage Orders Section
         */



        /**
         * if the tab menu is clicked do the following
         */

        // cache
        var tabbedNav = $('.tabbed-nav');


        tabbedNav.find('li').click( function(e) {

            e.preventDefault();

            if ($(this).hasClass("active")) {
                // do nothing
            } else {
                tabbedNav.find('li').not(this).removeClass('active');
                $(this).toggleClass("active");
            }
            return false;
        });

        // end tab menu function




        /**
         * This is called when the TAB content is first loaded
         */
        tabbedNav.find('.wooAPI').click( function() {

            var siteID = $(this).attr('id');

            // load the overlay
            $('#WooAPIContainer'+siteID+' .LoadingSitesAjax').LoadingOverlay('show', {
                background  : 'rgba(69, 136, 205, 0.5)',
                text        : 'Loading Please Wait...',
            });

            var PageNumber;

            if(PageNumber == undefined || PageNumber == null || PageNumber == ''){
                PageNumber = 1;
            }

            GetOrders(siteID, PageNumber);


        });// end on click wooAPI function




        /**
         * pagination for the manage orders table
         */

        // first lets just diable the default action on any and all clicks
        $(this).on('click', 'ul.pagination li.page-item', function(e) {
            e.preventDefault();
        });

        // if it was an actual pagination
        $(this).on('click', 'ul.pagination.manage-orders li.page-item:not(.disabled, .active)', function(e) {

            e.preventDefault();

            var siteID = $(this).parent('.pagination').data('siteid');

            // get the current page number (default is 1)
            var CurrentPage = parseInt($(this).siblings('.active').text());

            // was it next, previous or a page number clicked?
            var PageToGet = $(this).children('.page-link').text();

            // if it was the Next page link clicked
            if(PageToGet == 'Previous'){
                var PageNumber = parseInt(CurrentPage - 1);
            }

            if(PageToGet == 'Next'){
                var PageNumber = parseInt(CurrentPage + 1);
            }

            if(PageToGet != 'Previous' && PageToGet != 'Next'){
                var PageNumber = parseInt(PageToGet);
            }

            GetOrders(siteID, PageNumber);

        }); // end orders pagination




        /**
         * toggle sites menu
         */
        $(this).on('click', '.toggle-nav', function() {

            $('.gbwm-css .tabbed-nav').toggle('slide');

            // get the status value for the clicked button
            var ActiveStatus = $(this).val();


            if( ActiveStatus == 0 ){ // nav shown
                var OldIcon = 'chevron-left';
                var NewIcon = 'chevron-right';
                var TopOld = 'chevron-up';
                var TopNew = 'chevron-down';
                var NewValue = 1;
            } else if ( ActiveStatus == 1 ){ // nav hidden
                var OldIcon = 'chevron-right';
                var NewIcon = 'chevron-left';
                var TopOld = 'chevron-down';
                var TopNew = 'chevron-up';
                var NewValue = 0;
            }
            console.log(ActiveStatus);

            // remove old icon
            $('#orders_toggle_container .toggle-nav .icon.fas').removeClass('fa-'+OldIcon+'');
            $('.tab-menu .toggle-nav .icon.fas').removeClass('fa-'+TopOld+'');

            // add new icon
            $('#orders_toggle_container .toggle-nav .icon.fas').addClass('fa-'+NewIcon+'');
            $('.tab-menu .toggle-nav .icon.fas').addClass('fa-'+TopNew+'');

            // update value
            $('#orders_toggle_container .toggle-nav').val(NewValue);
            $('.tab-menu .toggle-nav').val(NewValue);

        }); // end check all boxes





        /**
         * status dropdown
         */

        $(this).on('click', '.status.select-title', function(e) {
            e.stopPropagation();
            // toggle status dropdown
            $('.status.select-box').toggle();

            $(document).click(function () {
                if($('.status.select-box').is(':visible')){
                   $('.status.select-box').hide();
                }
            });

        });





        /**
        * change orders listing status
        */

        $(this).on('click', 'a.SelectStatus', function(e) {

            e.preventDefault();

            // get the selected value

            var siteID = $(this).data('siteid');
            var NewStatus = $(this).data('status');

            GetOrders (siteID, PageNumber = 1, NewStatus);

        });// end PerPage function






        /**
         * close preview
         */
        $(this).on('click', '#ClosePreview', function(e) {

            // close all previews
            $('.OrderViewTR').remove();

        }); // end close preview






        /**
         * close preview
         */
        $(this).on('click', '.OrderView.active', function(e) {

            // remove active css
            $('.OrderView.active').removeClass('active');

            // close all other previews
            $('.OrderViewTR').remove();

        }); // end close preview






        /**
         * preview order
         */

        $(this).on('click', '.OrderView:not(.active)', function() {

            // get the siteID
            var siteID = $(this).data('siteid');

            // get the orderID
            var orderID = $(this).data('orderid');

            // remove active css from all
            $('.OrderView.active').removeClass('active');

            $(this).addClass('active');

            // close all other previews
            $('.OrderViewTR').remove();

            // now insert the new tr and td
            $('<tr class="OrderViewTR"><td class="OrderViewTD" colspan="7" style="text-align: center;"><h3>Loading Please Wait...</h3></td></tr>').insertAfter($(this).closest('tr'));

            // now get the data for the new tr
            $.ajax({
                url: ajaxurl,
                type: 'post',
                dataType: 'html',
                data: {'action': 'gbwm_ajax', 'siteID': siteID, 'orderID': orderID, 'ajaxFunction': 'PreviewOrder'
                },

                success: function(result) {

                    $('.OrderViewTD').css('text-align', 'unset');
                    $('.OrderViewTD').html(result);

                    // preview addresses tabs
                    $('#address-tabs').tabs( { active: 0 } );

                    // hide ajax messages
                    $('.alert').hide();

                },

                error: function(xhr, desc, err) {

                    $('.LoadingSitesAjax').hide();

                    $('#WooAPIContainer'+siteID+'ReplaceRows').html('Details: ' + desc + '\nError:' + err);

                    // simply set the "force" parameter to true:
                    $('.WooAPIContainer'+siteID+' .widefat.gbwmtable').LoadingOverlay('hide', true);

                }

            }); // end ajax call

        }); // end preview order







        /**
         * update billing address
         */

      $(this).on('click', '#UpdateBillingAddress.UpdateBillingAddress', function(e) {

          e.preventDefault();

          // reset
          var siteID = null;
          var orderID = null;

          // get the siteID
          var siteID = $(this).data('siteid');

          // get the orderID
          var orderID = $(this).data('orderid');

          // do the overlay
          $('.oxy-billing-form').LoadingOverlay('show', {
              background  : 'rgba(69, 136, 205, 0.5)',
          });

          //console.log('data- site/order id = '+siteID+'/'+orderID);

          var first_name  = $('#billingform'+siteID+'-'+orderID+' input[name=billing_first_name]').val();
          var last_name   = $('#billingform'+siteID+'-'+orderID+' input[name=billing_last_name]').val();
          var company     = $('#billingform'+siteID+'-'+orderID+' input[name=billing_company]').val();
          var address_1   = $('#billingform'+siteID+'-'+orderID+' input[name=billing_address_1]').val();
          var address_2   = $('#billingform'+siteID+'-'+orderID+' input[name=billing_address_2]').val();
          var city        = $('#billingform'+siteID+'-'+orderID+' input[name=billing_city]').val();
          var state       = $('#billingform'+siteID+'-'+orderID+' input[name=billing_state]').val();
          var postcode    = $('#billingform'+siteID+'-'+orderID+' input[name=billing_postcode]').val();
          var country     = $('#billingform'+siteID+'-'+orderID+' input[name=billing_country]').val();

              // now get the data for the new tr
              $.ajax({

                  url: ajaxurl,
                  type: 'post',
                  data: {'action': 'gbwm_ajax', 'siteID': siteID, 'orderID': orderID, 'first_name': first_name, 'last_name': last_name, 'company': company, 'address_1': address_1, 'address_2': address_2, 'city': city, 'state': state, 'postcode': postcode, 'country': country, 'AddressType': 'Billing', 'ajaxFunction': 'UpdateAddress'
                  },

                  success: function(result) {

                      // if the id is 0 then its an error
                      if(result.id == 0){

                          $('.alert').addClass('MessageError');

                          $('.alert').html('<span class="gbwmStatus">Sorry there was an error updating the billing addres.</span>');

                          // show ajax messages
                          $('.alert').show();
                          $('.alert').css('margin-bottom','20px');
                          $('.alert').delay(10000).fadeOut(400);

                          $('.oxy-billing-form').LoadingOverlay('hide', true);

                      }

                      // if the id is 1 then its a success
                      if(result.id == 1){

                          $('.alert').addClass('MessageSuccess');

                          $('.alert').html('<span class="gbwmStatus">The billing address has been updated.</span>');

                          // show ajax messages
                          $('.alert').show();
                          $('.alert').css('margin-bottom','20px');
                          $('.alert').delay(5000).fadeOut(400);

                          $('.oxy-billing-form').LoadingOverlay('hide', true);

                      }
                  },

                  error: function(xhr, desc, err) {

                      $('.alert').addClass('MessageError');

                      $('.alert').html('Details: ' + desc + '\nError:' + err);

                      $('.alert').show();
                  }

              }); // end ajax call

          }); // end update billing address







          /**
           * update shipping address
           */

        $(this).on('click', '#UpdateShippingAddress.UpdateShippingAddress', function(e) {

            e.preventDefault();

            // reset
            var siteID = null;
            var orderID = null;

            // get the siteID
            var siteID = $(this).data('siteid');

            // get the orderID
            var orderID = $(this).data('orderid');

            // do the overlay
            $('.oxy-shipping-form').LoadingOverlay('show', {
                background  : 'rgba(69, 136, 205, 0.5)',
            });

            //console.log('data- site/order id = '+siteID+'/'+orderID);

            var first_name  = $('#shippingform'+siteID+'-'+orderID+' input[name=shipping_first_name]').val();
            var last_name   = $('#shippingform'+siteID+'-'+orderID+' input[name=shipping_last_name]').val();
            var company     = $('#shippingform'+siteID+'-'+orderID+' input[name=shipping_company]').val();
            var address_1   = $('#shippingform'+siteID+'-'+orderID+' input[name=shipping_address_1]').val();
            var address_2   = $('#shippingform'+siteID+'-'+orderID+' input[name=shipping_address_2]').val();
            var city        = $('#shippingform'+siteID+'-'+orderID+' input[name=shipping_city]').val();
            var state       = $('#shippingform'+siteID+'-'+orderID+' input[name=shipping_state]').val();
            var postcode    = $('#shippingform'+siteID+'-'+orderID+' input[name=shipping_postcode]').val();
            var country     = $('#shippingform'+siteID+'-'+orderID+' input[name=shipping_country]').val();

                // now get the data for the new tr
                $.ajax({

                    url: ajaxurl,
                    type: 'post',
                    data: {'action': 'gbwm_ajax', 'siteID': siteID, 'orderID': orderID, 'first_name': first_name, 'last_name': last_name, 'company': company, 'address_1': address_1, 'address_2': address_2, 'city': city, 'state': state, 'postcode': postcode, 'country': country, 'AddressType': 'Shipping', 'ajaxFunction': 'UpdateAddress'
                    },

                    success: function(result) {

                        // if the id is 0 then its an error
                        if(result.id == 0){

                            $('.alert').addClass('MessageError');

                            $('.alert').html('<span class="gbwmStatus">Sorry there was an error updating the shipping addres.</span>');

                            // show ajax messages
                            $('.alert').show();
                            $('.alert').css('margin-bottom','20px');
                            $('.alert').delay(10000).fadeOut(400);

                            $('.oxy-shipping-form').LoadingOverlay('hide', true);

                        }

                        // if the id is 1 then its a success
                        if(result.id == 1){

                            $('.alert').addClass('MessageSuccess');

                            $('.alert').html('<span class="gbwmStatus">The shipping address has been updated.</span>');

                            // show ajax messages
                            $('.alert').show();
                            $('.alert').css('margin-bottom','20px');
                            $('.alert').delay(5000).fadeOut(400);

                            $('.oxy-shipping-form').LoadingOverlay('hide', true);

                        }
                    },

                    error: function(xhr, desc, err) {

                        $('.alert').addClass('MessageError');

                        $('.alert').html('Details: ' + desc + '\nError:' + err);

                        $('.alert').show();
                    }

                }); // end ajax call

            }); // end update shipping address






        /**
         * .OrderEdit modal open
         */

        $(this).on('click', 'button.OrderEdit', function(e) {

            e.preventDefault();

            $('#ModalEditOrder').modal({
                overlayId: 'modal-overlay',
                containerId: 'modal-container',
                closeHTML: null,
                opacity: 65,
                position: [35,],
                overlayClose: true
            });

            $('.closeForm').hide();
            $('.statusForm').show();

            //$('#trackingDiv').hide();

            // get the siteID
            var siteID = $(this).data('siteid');

            // get the orderID
            var orderID = $(this).data('orderid');

            // get the orderID
            var woosite = $(this).data('woosite');

            $('#ModalEditOrder input[name=siteID]').val(siteID);

            $('#ModalEditOrder input[name=orderID]').val(orderID);

            // do the woosite status
            if( woosite == 0 ){

                $('#trackingDiv').hide();

            }

            $('#ModalEditOrder select[name=trackingURL]').val();

            $('#ModalEditOrder input[name=trackingNumber]').val();

            // get the current status
            var CurrentStatus = $('.gbwmtrboddy'+siteID+'-'+orderID+' .gbwm-order-list-status  .gbwmStatus').text();

            // make the current status selected
            $('#ModalEditOrder option[value$='+CurrentStatus+']').prop('selected', true);

        }); // end .OrderEdit modal open





        /**
         * if a tracking courier is selected show the tracking number input
         */
        $(this).on('click', '#trackingURL', function(e) {

            var trackingStatus = $('#ModalEditOrder select[name=trackingURL]').val();

            if(trackingStatus == 0){

                $('#trackingNumberDiv').hide();

            }else{

                $('#trackingNumberDiv').show();
            }
        }); // end show tracking input




        /**
         * .OrderEdit Modal Submit, update the order
         */

        $(this).on('click', '#ModalEditOrder .submit', function(e) {

            e.preventDefault();

            $('.alert').hide();

            $('.closeForm').hide();

            $('.statusForm').show();

            // get the details
            var siteID          = $('#ModalEditOrder input[name=siteID]').val();

            var orderID         = $('#ModalEditOrder input[name=orderID]').val();

            var pageStatus      = $('#SelectStatus').data('status');

            var currentStatus   = $('tr.gbwmtrboddy'+siteID+'-'+orderID+' mark.order-status .gbwmStatus').text();

            var orderStatus     = $('#ModalEditOrder select[name=orderStatus]').val();

            var trackingURL = $('#ModalEditOrder select[name=trackingURL]').val();

            var trackingNumber = $('#ModalEditOrder input[name=trackingNumber]').val();

            // if a courier is selected make sure the traking number isnt empty
            if( trackingURL != 0 && trackingNumber == '' ){

                $('#ModalEditOrder input[name=trackingNumber]').css('border', '1px solid red');

                $('#ModalEditOrder .trackingNumberValidate').show();

                return;
            }

            // Let's call it 2 times just for fun...
            $('#modal-container').LoadingOverlay('show', {
                background  : 'rgba(69, 136, 205, 0.5)',
            });

            // start the ajax call
            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {'action': 'gbwm_ajax', 'siteID': siteID, 'orderID': orderID, 'orderStatus': orderStatus, 'trackingNumber': trackingNumber, 'trackingURL': trackingURL, 'ajaxFunction': 'OrderStatus'
            },

            success: function(result) {

                // if the id is 0 then its an error
                if(result.id == 0){

                    $('.alert').addClass('MessageError');

                    $('.alert').html('Sorry there was an error updating the order.');

                    $('.alert').show();
                    $('.alert').delay(3000).fadeOut(400);

                    $('.statusForm').hide();

                    $('.closeForm').show();

                }

                // if the id is 1 then its a success
                if(result.id == 1){

                    $('.alert').addClass('MessageSuccess');

                    $('.alert').html('The order status was updated, if you entered tracking details they have also been updated.');

                    $('.alert').show();
                    $('.alert').delay(3000).fadeOut(400);


                    // if the status has changed from original
                    if(currentStatus != orderStatus){

                        // change title to new staus
                        $('tr.gbwmtrboddy'+siteID+'-'+orderID+' mark.order-status.status-'+currentStatus+' .gbwmStatus').text(orderStatus);

                        // add new class
                        $('tr.gbwmtrboddy'+siteID+'-'+orderID+' mark.order-status.status-'+currentStatus).addClass('status-'+orderStatus);

                        // remove old class
                        $('tr.gbwmtrboddy'+siteID+'-'+orderID+' mark.order-status.status-'+currentStatus).removeClass('status-'+currentStatus);

                    }



                    // if the pageStatus is 'all' and new and old status dont match
                    if(pageStatus != 'all' && currentStatus != orderStatus){

                        $('.gbwmtrboddy'+siteID+'-'+orderID).hide();

                    }

                    $('.statusForm').hide();

                    // Let's call it 2 times just for fun...
                    $('#modal-container').LoadingOverlay('hide', true);

                    $.modal.close();

                    //$('.closeForm').show();

                }
            },

            error: function(xhr, desc, err) {

                $('.alert').addClass('MessageError');

                $('.alert').html('Details: ' + desc + '\nError:' + err);
            }

            }); // end ajax call

        }); // end .OrderEdit Modal Submit, update the order










        /**
         * Manage Sites Section
         */

        /**
         * add site and add courier forms and buttons
        */

        $(this).on('click', '#AddFormButton', function() {

            $('#AddFormButton').hide();
            $('.gbwm-css .addsite-container, .gbwm-css .addcourier-container').show();

            //$('table.gbwmtable.addcourier, table.gbwmtable.addsite').show();

        });// end on click AddFormButton





        $(this).on('click', '.gbwm-css .addsite-container #CancelSite, .gbwm-css .addcourier-container #CancelCourier', function() {

            $('.gbwm-css .addsite-container, .gbwm-css .addcourier-container').hide();

            $('#AddFormButton').show();

        });// end on click add site form






        /**
         * add site on click
        */

        $(this).on('click', '.gbwm-css .addsite-container #AddSite', function() {

            $('.alert').hide();

            var url = $('.gbwm-css .addsite-container input[name=SiteURL]').val();
            var SiteCK = $('.gbwm-css .addsite-container input[name=SiteCK]').val();
            var SiteCS = $('.gbwm-css .addsite-container input[name=SiteCS]').val();
            var WooSite = $('.gbwm-css .addsite-container select[name=SiteWooSite]').val();
            var active = $('.gbwm-css .addsite-container select[name=SiteActive]').val();

            if(url == ''){

                $('.gbwm-css .addsite-container #SiteURL').css({'border': '1px solid #df0202', 'box-shadow': '0 0 1px 3px rgba(166, 4, 4, 0.07)'});

                $('.gbwm-css .addsite-container .SiteURLValidate').show();

                $('.gbwm-css .addsite-container .SiteURLValidate').css({'color': '#df0202','margin-top': '5px'});

            }else{

                $('.gbwm-css .addsite-container #SiteURL').css({'border': '1px solid #ddd', 'box-shadow': 'inset 0 1px 2px rgba(0,0,0,.07)'});

                $('.gbwm-css .addsite-container .SiteURLValidate').hide();

            }

            if(SiteCK == ''){

                $('.gbwm-css .addsite-container #SiteCK').css({'border': '1px solid #df0202', 'box-shadow': '0 0 1px 3px rgba(166, 4, 4, 0.07)'});

                $('.gbwm-css .addsite-container .SiteCKValidate').show();

                $('.gbwm-css .addsite-container .SiteCKValidate').css({'color': '#df0202','margin-top': '5px'});

            }else{

                $('.gbwm-css .addsite-container #SiteCK').css({'border': '1px solid #ddd', 'box-shadow': 'inset 0 1px 2px rgba(0,0,0,.07)'});

                $('.gbwm-css .addsite-container .SiteCKValidate').hide();

            }

            if(SiteCS == ''){

                $('.gbwm-css .addsite-container #SiteCS').css({'border': '1px solid #df0202', 'box-shadow': '0 0 1px 3px rgba(166, 4, 4, 0.07)'});

                $('.gbwm-css .addsite-container .SiteCSValidate').show();

                $('.gbwm-css .addsite-container .SiteCSValidate').css({'color': '#df0202','margin-top': '5px'});

            }else{

                $('.gbwm-css .addsite-container #SiteCS').css({'border': '1px solid #ddd', 'box-shadow': 'inset 0 1px 2px rgba(0,0,0,.07)'});

                $('.gbwm-css .addsite-container .SiteCSValidate').hide();

            }

            if(!(url == '' || SiteCK == '' || SiteCS == '')){

                $.ajax({
                    url: ajaxurl,
                    type: 'post',
                    data: {'action': 'gbwm_ajax', 'url': url, 'SiteCK': SiteCK, 'SiteCS': SiteCS, 'WooSite': WooSite, 'active': active, 'ajaxFunction': 'AddSite'},

                    success: function(result) {

                        // if the ID is 0 then its an error
                        if(result.ID == 0){

                            $('.alert').addClass('MessageError');

                            // if

                            $('.alert').html('Sorry there was an error adding the site.<br>Error:<br><strong>'+result.message+'</strong>');

                            $('.alert').show();
                            $('.alert').delay(5000).fadeOut(400);

                        }

                        // if the ID is 1 then its a success
                        if(result.ID == 1){

                            //console.log(result.debug);

                            $('.alert').addClass('MessageSuccess');

                            $('.alert').html('Success... The site was successfully added - This page will automatically re-load in 2 seconds.');

                            $('.alert').show();

                            setTimeout(function() {
                                location.reload();
                            }, 2000);

                        }
                    },

                    error: function(xhr, desc, err) {
                        $('.alert').addClass('MessageError');

                        $('.ajaxMessage').html('Details: ' + desc + '\nError:' + err);

                        $('.MessageError').show();
                    }

                    }); // end ajax call

            } // end if not empty

        });// end on click add site







        /**
         * on edit site model open
         */
        $(this).on('click', 'table.gbwmtable.manage-sites .btn.SiteEdit', function(e) {

            e.preventDefault();

            $('#ModalEditSite').modal({
      					overlayId: 'modal-overlay',
      					containerId: 'modal-container',
      					closeHTML: null,
      					opacity: 65,
      					position: ['0',],
      					overlayClose: true
    				});

            // get and set the siteID
            var siteID = $(this).data('siteid');

            // get and set the SiteURL
            var url = $('tr#Site'+siteID+' td.SiteURL').text();
            $('#ModalEditSite input[name=SiteURL]').val(url);

            // get and set the SiteCK
            var Consumer_key = $('tr#Site'+siteID+' td.SiteCK').text();
            $('#ModalEditSite input[name=SiteCK]').val(Consumer_key);

            // get and set the SiteCS
            var Consumer_secret = $('tr#Site'+siteID+' td.SiteCS').text();
            $('#ModalEditSite input[name=SiteCS]').val(Consumer_secret);

            // get and set the SiteWooSite
            var SiteWooSite = $('tr#Site'+siteID+' td.SiteWooSite').data('val');
            $('#ModalEditSite select[name=SiteWooSite]#SiteWooSite').val(SiteWooSite);

            // make the current SiteWooSite status selected
            $('#ModalEditSite #SiteWooSite option[value$='+SiteWooSite+']').prop('selected', true);

            // get and set the SiteActive
            var SiteActive = $('tr#Site'+siteID+' td.SiteActive').data('val');
            $('#ModalEditSite select[name=SiteActive]#SiteActive').val(SiteActive);

            // make the current status selected
            $('#ModalEditSite #SiteActive option[value$='+SiteActive+']').prop('selected', true);

            // add the correct siteID to the submit button
            $('.ModalEditSiteSubmit').data('siteid', siteID);

            // show and hide
            $('.closeForm').hide();

            $('.statusForm').show();

        }); // end on edit site model open







        /**
         * do the ajax on site edit modal submit
         */

        $(this).on('click', '#ModalEditSite .ModalEditSiteSubmit', function(e) {
            e.preventDefault();

            $('.alert').hide();

            // get the site id for the clicked button
            var siteID = $(this).data('siteid');

            var url = $('#ModalEditSite input[name=SiteURL]').val();

            var Consumer_key = $('#ModalEditSite input[name=SiteCK]').val();

            var Consumer_secret = $('#ModalEditSite input[name=SiteCS]').val();

            var WooSite = $('#ModalEditSite select[name=SiteWooSite]').val();

            var active = $('#ModalEditSite select[name=SiteActive]').val();

            // do the overlay
            $('#modal-container').LoadingOverlay('show', {
                background  : 'rgba(69, 136, 205, 0.5)',
            });

            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {'action': 'gbwm_ajax', 'siteID': siteID, 'url': url, 'Consumer_key': Consumer_key, 'Consumer_secret': Consumer_secret, 'WooSite': WooSite, 'active': active, 'ajaxFunction': 'EditSite'
            },

            success: function(result) {

                // if the ID is 0 then its an error
                if(result.ID == 0){

                    $('.alert').addClass('MessageError');

                    $('.ajaxMessage').html('Sorry there was an error updating the site.<br>Error:<br><strong>'+result.message+'</strong>'
                );

                    $('.statusForm').hide();

                    // do the overlay
                    $('#modal-container').LoadingOverlay('hide');

                    $('.closeForm').show();

                }

                // if the ID is 1 then its a success
                if(result.ID == 1){

                    $('.alert').addClass('MessageSuccess');

                    $('.ajaxMessage').html('The site was updated.');

                    $('.statusForm').hide();

                    // do the overlay
                    $('#modal-container').LoadingOverlay('hide');

                    $('.closeForm').show();

                }
            },

            error: function(xhr, desc, err) {

                $('.alert').addClass('MessageError');

                $('.alert').html('Details: ' + desc + '\nError:' + err);

                $('.alert').show();

            }

            }); // end ajax call

        }); // end edit site function







        /**
         * if the request was "SiteDelete" then lets delete the site
        */

        $(this).on('click', 'table.gbwmtable.manage-sites .btn.confirmation', function() {

            $('.alert').hide();

            if (confirm('Are you sure you want to remove this site?')) {

                // get the site id for the clicked button
                var siteID = $(this).data('siteid');

                $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {'action': 'gbwm_ajax', 'siteID': siteID, 'ajaxFunction': 'DeleteSite'},

                success: function(result) {

                    // if the ID is 0 then its an error
                    if(result.ID == 0){

                        $('.alert').addClass('MessageError');

                        $('.alert').html('Sorry there was an error deleting this site.<br>Error:<br><strong>'+result.message+'</strong>');

                        $('.alert').show();

                    }

                    // if the ID is 1 then its a success
                    if(result.ID == 1){

                        $('.alert').addClass('MessageSuccess');

                        $('.alert').html('Success... The site was successfully removed.');

                        $('.alert').show();
                        $('.alert').delay(5000).fadeOut(400);

                        $('tr#Site'+siteID).hide();

                        var totalSites = parseInt($('.totalSites').text()) - 1;

                        $('.totalSites').text(totalSites);

                    }
                },

                error: function(xhr, desc, err) {
                    $('.alert').addClass('MessageError');

                    $('.alert').html('Details: ' + desc + '\nError:' + err);

                    $('.alert').show();
                }

                }); // end ajax call

            }// end confirm

        });// end on click SiteDelete







        /**
         * Change woosite status on click
        */

        $(this).on('click', 'table.gbwmtable.manage-sites .btn.WooSiteStatus', function() {

            // get the site id for the clicked button
            var siteID = $(this).data('siteid');

            // get the status value for the clicked button
            var WooSiteStatus = $(this).val();

            // change icon and start spinnin
            $('table.gbwmtable.manage-sites .btn.WooSiteStatus.'+siteID+' .icon.fas').addClass('fa-spin');

            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {'action': 'gbwm_ajax', 'siteID': siteID, 'WooSiteStatus': WooSiteStatus, 'ajaxFunction': 'WooSiteStatus'},

                success: function(result) {

                    // if the ID is 0 then its an error
                    if(result.ID == 0){

                        $('.alert').addClass('MessageError');

                        $('.ajaxMessage').html('Sorry there was an error changing the status of woosite installed this site.<br>Error:<br><strong>'+result.message+'</strong>');

                        $('.alert').show();
                        $('.alert').delay(5000).fadeOut(400);

                    }

                    // if the ID is 1 then its a success
                    if(result.ID == 1){

                        if( WooSiteStatus == 0 ){ // inactive
                            var OldStatus = 'danger';
                            var NewStatus = 'success';
                            var OldButton = 'times-circle';
                            var NewButton = 'check-circle';
                            var NewValue = 1;
                        } else if ( WooSiteStatus == 1 ){ // active
                            var OldStatus = 'success';
                            var NewStatus = 'danger';
                            var OldButton = 'check-circle';
                            var NewButton = 'times-circle';
                            var NewValue = 0;
                        }


                        // button class (toggle)
                        $('table.gbwmtable.manage-sites .btn.WooSiteStatus.'+siteID).removeClass('btn-'+OldStatus+'');

                        // button class (toggle)
                        $('table.gbwmtable.manage-sites .btn.WooSiteStatus.'+siteID).addClass('btn-'+NewStatus+'');

                        $('.btn-danger:focus').css('box-shadow','0 0 0 0rem rgba(220,53,69,.5)');

                        $('.btn-success:focus').css('box-shadow','0 0 0 0rem rgba(220,53,69,.5)');


                        // remove old icon
                        $('table.gbwmtable.manage-sites .btn.WooSiteStatus.'+siteID+' .icon.fas').removeClass('fa-'+OldButton+'');

                        // add new icon
                        $('table.gbwmtable.manage-sites .btn.WooSiteStatus.'+siteID+' .icon.fas').addClass('fa-'+NewButton+'');

                        // remove the icon spinning class
                        $('table.gbwmtable.manage-sites .btn.WooSiteStatus.'+siteID+' .icon.fas').removeClass('fa-spin');

                        $('table.gbwmtable.manage-sites .btn.WooSiteStatus.'+siteID).val(NewValue);

                    }
                },

                error: function(xhr, desc, err) {
                    $('.alert').addClass('MessageError');

                    $('.ajaxMessage').html('Details: ' + desc + '\nError:' + err);

                    $('.MessageError').show();
                }

            }); // end ajax call

        });// end Change WooSite status on click







        /**
         * Change site active status on click
        */

        $(this).on('click', 'table.gbwmtable.manage-sites .btn.SiteStatus', function() {

            // get the site id for the clicked button
            var siteID = $(this).data('siteid');

            // get the status value for the clicked button
            var ActiveStatus = $(this).val();

            // change icon and start spinnin
            $('table.gbwmtable.manage-sites .btn.SiteStatus.'+siteID+' .icon.fas').addClass('fa-spin');

            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {'action': 'gbwm_ajax', 'siteID': siteID, 'ActiveStatus': ActiveStatus, 'ajaxFunction': 'SiteStatus'},

                success: function(result) {

                    // if the ID is 0 then its an error
                    if(result.ID == 0){

                        $('.alert').addClass('MessageError');

                        $('.ajaxMessage').html('Sorry there was an error changing the status of this site.<br>Error:<br><strong>'+result.message+'</strong>');

                        $('.alert').show();
                        $('.alert').delay(5000).fadeOut(400);

                    }

                    // if the ID is 1 then its a success
                    if(result.ID == 1){

                        if( ActiveStatus == 0 ){ // inactive
                            var OldStatus = 'danger';
                            var NewStatus = 'success';
                            var OldButton = 'times-circle';
                            var NewButton = 'check-circle';
                            var NewValue = 1;
                        } else if ( ActiveStatus == 1 ){ // active
                            var OldStatus = 'success';
                            var NewStatus = 'danger';
                            var OldButton = 'check-circle';
                            var NewButton = 'times-circle';
                            var NewValue = 0;
                        }


                        // button class (toggle)
                        $('table.gbwmtable.manage-sites .btn.SiteStatus.'+siteID).removeClass('btn-'+OldStatus+'');

                        // button class (toggle)
                        $('table.gbwmtable.manage-sites .btn.SiteStatus.'+siteID).addClass('btn-'+NewStatus+'');

                        $('.btn-danger:focus').css('box-shadow','0 0 0 0rem rgba(220,53,69,.5)');

                        $('.btn-success:focus').css('box-shadow','0 0 0 0rem rgba(220,53,69,.5)');


                        // remove old icon
                        $('table.gbwmtable.manage-sites .btn.SiteStatus.'+siteID+' .icon.fas').removeClass('fa-'+OldButton+'');

                        // add new icon
                        $('table.gbwmtable.manage-sites .btn.SiteStatus.'+siteID+' .icon.fas').addClass('fa-'+NewButton+'');

                        // remove the icon spinning class
                        $('table.gbwmtable.manage-sites .btn.SiteStatus.'+siteID+' .icon.fas').removeClass('fa-spin');

                        $('table.gbwmtable.manage-sites .btn.SiteStatus.'+siteID).val(NewValue);

                    }
                },

                error: function(xhr, desc, err) {
                    $('.alert').addClass('MessageError');

                    $('.ajaxMessage').html('Details: ' + desc + '\nError:' + err);

                    $('.MessageError').show();
                }

            }); // end ajax call

        });// end Change site active status on click









        /**
        * start courier sections
        */

        /**
         * Change courier active status on click
        */

        $(this).on('click', 'table.gbwmtable.manage-couriers .btn.CourierStatus', function() {

            // get the site id for the clicked button
            var siteID = $(this).data('siteid');

            // get the site id for the clicked button
            var ActiveStatus = $(this).val();

            // start the icon spinning class
            $('table.gbwmtable.manage-couriers .btn.CourierStatus.'+siteID+' .icon.fas').addClass('fa-spin');

                $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {'action': 'gbwm_ajax', 'siteID': siteID, 'ActiveStatus': ActiveStatus, 'ajaxFunction': 'CourierStatus'},

                success: function(result) {

                    // if the ID is 0 then its an error
                    if(result.ID == 0){

                        $('.alert').addClass('MessageError');

                        $('.ajaxMessage').html('Sorry there was an error updating the courier.<br>Error:<br><strong>'+result.message+'</strong>');

                        $('.alert').show();
                        $('.alert').delay(5000).fadeOut(400);

                    }

                    // if the ID is 1 then its a success
                    if(result.ID == 1){

                        if( ActiveStatus == 0 ){ // inactive
                            var OldStatus = 'danger';
                            var NewStatus = 'success';
                            var OldButton = 'times-circle';
                            var NewButton = 'check-circle';
                            var NewValue = 1;
                        } else if ( ActiveStatus == 1 ){ // active
                            var OldStatus = 'success';
                            var NewStatus = 'danger';
                            var OldButton = 'check-circle';
                            var NewButton = 'times-circle';
                            var NewValue = 0;
                        }


                        // button class (toggle)
                        $('table.gbwmtable.manage-couriers .btn.CourierStatus.'+siteID).removeClass('btn-'+OldStatus+'');

                        // button class (toggle)
                        $('table.gbwmtable.manage-couriers .btn.CourierStatus.'+siteID).addClass('btn-'+NewStatus+'');

                        $('.btn-danger:focus').css('box-shadow','0 0 0 0rem rgba(220,53,69,.5)');

                        $('.btn-success:focus').css('box-shadow','0 0 0 0rem rgba(220,53,69,.5)');


                        // icons class (toggle)
                        $('table.gbwmtable.manage-couriers .btn.CourierStatus.'+siteID+' .icon.fas').removeClass('fa-'+OldButton+'');

                        // now change the css and icon
                        $('table.gbwmtable.manage-couriers .btn.CourierStatus.'+siteID+' .icon.fas').addClass('fa-'+NewButton+'');

                        // remove the icon spinning class
                        $('table.gbwmtable.manage-couriers .btn.CourierStatus.'+siteID+' .icon.fas').removeClass('fa-spin');

                        $('table.gbwmtable.manage-couriers .btn.CourierStatus.'+siteID).val(NewValue);

                    }
                },

                error: function(xhr, desc, err) {
                    $('.alert').addClass('MessageError');

                    $('.ajaxMessage').html('Details: ' + desc + '\nError:' + err);

                    $('.MessageError').show();
                }

                }); // end ajax call

        });// end Change courier active status on click







        /**
         * add courier on click
        */

        $(this).on('click', '.gbwm-css .addcourier-container #AddCourier', function() {

            var title = $('.gbwm-css .addcourier-container input[name=CourierTitle]').val();
            var url = $('.gbwm-css .addcourier-container input[name=CourierURL]').val();
            var active = $('.gbwm-css .addcourier-container select[name=CourierActive]').val();

            if(title == ''){

                $('.gbwm-css .addcourier-container #CourierTitle').css({'border': '1px solid #df0202', 'box-shadow': '0 0 1px 3px rgba(166, 4, 4, 0.07)'});

                $('.gbwm-css .addcourier-container .CourierTitleValidate').show();

                $('.gbwm-css .addcourier-container .CourierTitleValidate').css({'color': '#df0202','padding-left': '10px'});

            }else{

                $('.gbwm-css .addcourier-container #CourierTitle').css({'border': '1px solid #ddd', 'box-shadow': 'inset 0 1px 2px rgba(0,0,0,.07)'});

                $('.gbwm-css .addcourier-container .CourierTitleValidate').hide();

            }

            if(url == ''){

                $('.gbwm-css .addcourier-container #CourierURL').css({'border': '1px solid #df0202', 'box-shadow': '0 0 1px 3px rgba(166, 4, 4, 0.07)'});

                $('.gbwm-css .addcourier-container .CourierURLValidate').show();

                $('.gbwm-css .addcourier-container .CourierURLValidate').css({'color': '#df0202','padding-left': '10px'});

            }else{

                $('.gbwm-css .addcourier-container #CourierURL').css({'border': '1px solid #ddd', 'box-shadow': 'inset 0 1px 2px rgba(0,0,0,.07)'});

                $('.gbwm-css .addcourier-container .CourierURLValidate').hide();

            }

            if(!(title == '' || url == '')){

                $.ajax({
                    url: ajaxurl,
                    type: 'post',
                    data: {'action': 'gbwm_ajax', 'title': title, 'url': url, 'active': active, 'ajaxFunction': 'AddCourier'},

                    success: function(result) {

                        // if the ID is 0 then its an error
                        if(result.ID == 0){

                            $('.alert').addClass('MessageError');

                            $('.ajaxMessage').html('Sorry there was an error adding the courier.<br>Error:<br><strong>'+result.message+'</strong>');

                            $('.alert').show();
                            $('.alert').delay(5000).fadeOut(400);

                        }

                        // if the ID is 1 then its a success
                        if(result.ID == 1){

                            //console.log(result.debug);

                            $('.alert').addClass('MessageSuccess');

                            $('.ajaxMessage').html('Success... The courier was successfully added - This page will automatically re-load in 2 seconds.');

                            $('.alert').show();

                            setTimeout(function() {
                                location.reload();
                            }, 2000);

                        }
                    },

                    error: function(xhr, desc, err) {
                        $('.alert').addClass('MessageError');

                        $('.ajaxMessage').html('Details: ' + desc + '\nError:' + err);

                        $('.MessageError').show();
                    }

                }); // end ajax call

            } // end if not empty

        });// end on click add courier







        /**
         * on edit courier model open
         */

        $(this).on('click', 'table.gbwmtable.manage-couriers .btn.CourierEdit', function(e) {

            e.preventDefault();

            $('#ModalEditCourier').modal({
                overlayId: 'modal-overlay',
                containerId: 'modal-container',
                closeHTML: null,
                opacity: 65,
                position: ['0',],
                overlayClose: true
            });

            // get and set the siteID
            var siteID = $(this).data('siteid');

            // get and set the name
            var CourierTitle = $('tr#Courier'+siteID+' td.CourierTitle').text();
            $('#ModalEditCourier input[name=CourierTitle]').val(CourierTitle);

            // get and set the siteID
            var url = $('tr#Courier'+siteID+' td.CourierURL').text();
            $('#ModalEditCourier input[name=CourierURL]').val(url);

            // get and set the siteID
            var CourierActive = $('tr#Courier'+siteID+' td.CourierActive').data('val');
            $('#ModalEditCourier select[name=CourierActive]#CourierActive').val(CourierActive);

            // make the current status selected
            $('#ModalEditCourier #CourierActive option[value$='+CourierActive+']').prop('selected', true);

            // add the correct siteID to the submit button
            $('.ModalEditCourierSubmit').data('siteid', siteID);

            // show and hide
            $('.closeForm').hide();

            $('.statusForm').show();

        }); // end on edit Courier model open







        /**
         * do the ajax on Courier edit modal submit
         */

        $(this).on('click', '#ModalEditCourier .ModalEditCourierSubmit', function(e) {
            e.preventDefault();

            // get the site id for the clicked button
            var siteID = $(this).data('siteid');

            var title = $('#ModalEditCourier input[name=CourierTitle]').val();

            var url = $('#ModalEditCourier input[name=CourierURL]').val();

            var active = $('#ModalEditCourier select[name=CourierActive]').val();

            // do the overlay
            $('#modal-container').LoadingOverlay('show', {
                background  : 'rgba(69, 136, 205, 0.5)',
            });

            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {'action': 'gbwm_ajax', 'siteID': siteID, 'title': title, 'url': url, 'active': active, 'ajaxFunction': 'EditCourier'
            },

            success: function(result) {

                // if the ID is 0 then its an error
                if(result.ID == 0){

                    $('.ajaxMessage').html('Sorry there was an error updating the courier.<br>Error:<br><strong>'+result.message+'</strong>');

                    $('.statusForm').hide();

                    // do the overlay
                    $('#modal-container').LoadingOverlay('hide');

                    $('.closeForm').show();

                }

                // if the ID is 1 then its a success
                if(result.ID == 1){

                    $('.ajaxMessage').html('The courier was updated.');

                    $('.statusForm').hide();

                    // do the overlay
                    $('#modal-container').LoadingOverlay('hide');

                    $('.closeForm').show();

                }
            },

            error: function(xhr, desc, err) {

                $('.alert').addClass('MessageSuccess');

                $('.ajaxMessage').html('Details: ' + desc + '\nError:' + err);

                $('.MessageError').show();

            }

            }); // end ajax call

        }); // end edit Courier function







        /**
         * if the request was "CourierDelete" then lets delete the Courier
        */

        $(this).on('click', 'table.gbwmtable.manage-couriers .btn.confirmation', function() {

            if (confirm('Are you sure you want to remove this courier?')) {

                // get the site id for the clicked button
                var siteID = $(this).data('siteid');

                $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {'action': 'gbwm_ajax', 'siteID': siteID, 'ajaxFunction': 'DeleteCourier'},

                success: function(result) {

                    // if the ID is 0 then its an error
                    if(result.ID == 0){

                        $('.alert').addClass('MessageError');

                        $('.ajaxMessage').html('Sorry there was an error deleting this site.<br>Error:<br><strong>'+result.message+'</strong>');

                        $('.alert').show();

                    }

                    // if the ID is 1 then its a success
                    if(result.ID == 1){

                        $('.alert').addClass('MessageSuccess');

                        $('.ajaxMessage').html('Success... The courier was successfully removed.');

                        $('.alert').show();
                        $('.alert').delay(2500).fadeOut(400);

                        $('tr#Courier'+siteID).hide();

                        var totalSites = parseInt($('.totalSites').text()) - 1;

                        $('.totalSites').text(totalSites);

                    }
                },

                error: function(xhr, desc, err) {
                    $('.alert').addClass('MessageError');

                    $('.ajaxMessage').html('Details: ' + desc + '\nError:' + err);

                    $('.MessageError').show();
                }

                }); // end ajax call

            }// end confirm

        });// end on click CourierDelete







        /**
         * close alert box on click
        */

        $(this).on('click', '.alert .closebtn', function() {

            $('.alert').hide();

        });// end on click close alert box









        /**
        * start downloads sections
        */

        /**
         * download orders show dropdown
        */

        $(this).on('click', '.download.select-title', function(e) {
            e.stopPropagation();

            // get the possition
            var possition = $(this).data('possition');

            // toggle download dropdown
            $('.download.select-box.'+possition).toggle();

            $(document).click(function () {
                if($('.download.select-box').is(':visible')){
                $('.download.select-box').hide();
                }
            });

        });





        /**
         * download orders (word/pdf) status, selected, print
        */
        $(this).on('click', '.download .word-pdf, .gbwm-order-list-actions button.PrintOrder', function(e) {

            // get the site id for the clicked button
            var siteID = $(this).data('siteid');

            // get the download type (selected, status)
            var downloadtype  = $(this).data('type');

            if(downloadtype == 'selected'){

                // get the order ids for the selected orders
                var idsArr = [];

                $.each($("input[name='checked[]']:checked"), function(){
                    idsArr.push($(this).val());
                });

                // if no boxes checked send alert
                if( idsArr == '' || idsArr == undefined ){

                    e.preventDefault();

                    alert('You need to select some orders first.');

                    return;

                }
            }

            if(downloadtype == 'print'){
                // get the status for the page
                var idsArr  = $(this).data('orderid');
                var downloadtype = 'selected';
            }

            if(downloadtype == 'status'){
                // get the status for the page
                var status  = $('#SelectStatus').data('status');
            }

            $('<div id="downloadOrdersModal" class="center"><h1 id="downloadOrdersModalH1" class="title-font"><i class="fas fa-spinner fa-spin"></i>&nbsp;&nbsp;&nbsp;Preparing orders for download.... Please wait</h1></div>').modal({
                onClose: function(dialog) {
                    dialog.data.delay(1100).fadeOut(500, function() {
                        $.modal.close();
                    });
                },
                overlayId: 'modal-overlay',
                containerId: 'modal-container',
                opacity: 65,
                position: [100,],
                overlayClose: true,
            });

            console.log('siteID: '+siteID+' - downloadtype: '+downloadtype+' - status: '+status+' - idsArr: '+idsArr)

            // now get the data for the new tr
            $.ajax({
                url: ajaxurl,
                type: 'post',
                data:
                {
                    'action': 'gbwm_ajax', 'siteID': siteID, 'downloadtype': downloadtype, 'status': status, 'idsArr': idsArr, 'ajaxFunction': 'DownloadOrders'
                },

                success: function(result) {

                    // if there was an error
                    if( result.ID == 0 ){

                        $('.faf.fa-spinner.fa-spin').removeClass('fa-spinner, fa-spin');

                        // success message
                        $('#downloadOrdersModal').html('<h1 class="title-font">Sorry there was an error downloading orders from this site.</h1><p>The error message is.</p><p class="bold">'+result.message+'</p><p>Please see the <a href="admin.php?page=gbwm_help" target="_self"> Help Section</a> for help with errors.</p><br><br><button type="button" class="btn btn-dark btn-xs simplemodal-close center">Close</button>');


                    }else{

                        // success message
                        $('.alert .ajaxMessage').html('The download has begun, you can see the download when it is ready in the <a href="admin.php?page=gbwm_downloads">Downloads</a> section');

                        // add success class
                        $('.alert').addClass('MessageSuccess');

                        $('.alert').show();

                        $('.faf.fa-spinner.fa-spin').removeClass('fa-spinner, fa-spin');

                        $('#downloadOrdersModalH1').text('Download Started');

                        // close the modal
                        $.modal.close();

                        console.log(HomeURL);

                        // now load the cron in the background to start the downloads
                        $.get(HomeURL+'/wp-cron.php?doing_cron', function(data, status){
                            //alert('Data: ' + data + '\nStatus: ' + status);
                          });

                    }

                },

                error: function(xhr, desc, err) {

                    $('#WooAPIContainer'+siteID+'ReplaceRows').html('Details: ' + desc + '\nError:' + err);

                }

            }); // end ajax call

        });// end download word/pdf





        /**
         * delete selected downloads
         */

        $(this).on('click', '.gbwm-css .gbwm-downloads #DeleteSelectedFiles', function(e) {

            // get the order ids for the selected orders
            var idsArr = [];

            $.each($("input[name='checked[]']:checked"), function(){
                idsArr.push($(this).val());
            });

            // if no boxes checked send alert
            if( idsArr == '' || idsArr == undefined ){

                e.preventDefault();

                alert('You need to select some files first.');

                return;

            }

            if (confirm('Are you sure you want to delete these file(s)?')) {

                // do the ajax call
                $.ajax({
                    url: ajaxurl,
                    type: 'post',
                    data: {'action': 'gbwm_ajax', 'idsArr': idsArr, 'ajaxFunction': 'DeleteSelectedFiles'},

                    success: function(result) {

                        // if the ID is 0 then its an error
                        if(result.ID == 0){

                            $('.alert').addClass('MessageError');

                            $('.ajaxMessage').html('Sorry there was an error deleting some file(s).<br>Error:<br><strong>'+result.message+'</strong>');

                            $('.alert').show();

                        }

                        // if the ID is 1 then its a success
                        if(result.ID == 1){

                            $('.alert').addClass('MessageSuccess');

                            $('.ajaxMessage').html('Success... The file(s) were successfully deleted.');

                            $('.alert').show();

                            setTimeout(function() {
                                location.reload();
                            }, 1500);

                        }
                    },

                    error: function(xhr, desc, err) {
                        $('.alert').addClass('MessageError');

                        $('.ajaxMessage').html('Details: ' + desc + '\nError: ' + err);

                        $('.MessageError').show();
                    }

                    }); // end ajax call

            }// end confirm

        });// end delete selected downloads





        /**
         * delete file
         */

        $(this).on('click', '.gbwm-css .gbwm-downloads .DeleteFile', function() {

            if (confirm('Are you sure you want to delete this file?')) {

                // get the filename
                var filename = $(this).data('file');

                var thisTR = $(this);

                $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {'action': 'gbwm_ajax', 'filename': filename, 'ajaxFunction': 'DeleteFile'},

                success: function(result) {

                    // if the ID is 0 then its an error
                    if(result.ID == 0){

                        $('.alert').addClass('MessageError');

                        $('.ajaxMessage').html('Sorry there was an error deleting this file.<br>Error:<br><strong>'+result.message+'</strong>');

                        $('.alert').show();

                    }

                    // if the ID is 1 then its a success
                    if(result.ID == 1){

                        $('.alert').addClass('MessageSuccess');

                        $('.ajaxMessage').html('Success... The file was successfully deleted.');

                        $('.alert').show();
                        $('.alert').delay(3500).fadeOut(400);

                        // close all other previews
                        $(thisTR).closest('tr').remove();

                        var totalSites = parseInt($('.totalFiles').text()) - 1;

                        $('.totalFiles').text(totalSites);

                    }
                },

                error: function(xhr, desc, err) {
                    $('.alert').addClass('MessageError');

                    $('.ajaxMessage').html('Details: ' + desc + '\nError: ' + err);

                    $('.MessageError').show();
                }

                }); // end ajax call

            }// end confirm

        });// end delete file





        /**
         * download file
         */

        $(this).on('click', '.gbwm-css .gbwm-downloads .DownloadFile', function() {

            //wp-cron.php?doing_cron

            // get the filename
            var filename = $(this).data('file');

            // now download
            var downloadsURL = trans.downloadsURL;

            window.open(
                downloadsURL+'/'+filename,
                '_blank' // <- This is what makes it open in a new window.
            );

        });// end on click download file









        /**
        * start template sections
        */

        // start jquery tabs
        $( "#tabs" ).tabs();

        // lightbox for template screenshots
        $('.venobox').venobox(
            {
                framewidth: '600px',        // default: ''
                frameheight: '600px',       // default: ''
                border: '5px',              // default: '0'
                bgcolor: '#fff',            // default: '#fff'
                numeratio: true,            // default: false
                infinigall: true,           // default: false
                numerationPosition: 'top',  // default: top
                closeBackground: '#fff',     // default: #161617
                closeColor: '#000',          // default: #d2d2d2
                numerationBackground: '#fff',     // default: #161617
                numerationColor: '#000',          // default: #d2d2d2
            }
        );





        /**
         * display site template
         */

        $(this).on('click', '.SiteTemplate:not(.active)', function() {

            // get the siteID
            var siteID = $(this).data('siteid');

            // remove active css from all
            $('.SiteTemplate.active').removeClass('active');

            $(this).addClass('active');

            // close all other previews
            $('.SetTemplateTR').remove();

            // now insert the new tr and td
            $('<tr class="SetTemplateTR"><td class="SetTemplateTD" colspan="7" style="text-align: center;"><h3>Loading Please Wait...</h3></td></tr>').insertAfter($(this).closest('tr'));

            // now get the data for the new tr
            $.ajax({
                url: ajaxurl,
                type: 'post',
                dataType: 'html',
                data: {'action': 'gbwm_ajax', 'siteID': siteID, 'ajaxFunction': 'SiteTemplate'
                },

                success: function(result) {

                    $('.SetTemplateTD').css('text-align', 'unset');
                    $('.SetTemplateTD').html(result);

                    // hide ajax messages
                    $('.alert').hide();

                },

                error: function(xhr, desc, err) {

                    $('.LoadingSitesAjax').hide();

                    $('#WooAPIContainer'+siteID+'ReplaceRows').html('Details: ' + desc + '\nError:' + err);

                    // simply set the "force" parameter to true:
                    $('.WooAPIContainer'+siteID+' .widefat.gbwmtable').LoadingOverlay('hide', true);

                }

            }); // end ajax call

        }); // end display site template





        /**
         * close template editor
         */

        $(this).on('click', '.SiteTemplate.active', function() {

            // remove active css from all
            $('.SiteTemplate.active').removeClass('active');

            // close all previews
            $('.SetTemplateTR').remove();



        }); // end close template editor





        /**
         * load new template
         */

        $(this).on('change', '.gbwm-css #template-sidebar #Template', function() {

            // get the siteID
            var siteID = $('#ThisSite').data('siteid');

            // get the siteID
            var templateID = $(this).val();

            // get the siteID
            var templatesiteID = $(".gbwm-css #template-sidebar #Template option:selected").data('templatesiteid');

            console.log( 'templatesiteID: '+templatesiteID );

            //return;

            // close all other previews
            $('.SetTemplateTR').remove();

            // now insert the new tr and td
            $('<tr class="SetTemplateTR"><td class="SetTemplateTD" colspan="7" style="text-align: center;"><h3>Loading Please Wait...</h3></td></tr>').insertAfter($('#Site'+siteID).closest('tr'));

            // now get the data for the new tr
            $.ajax({
                url: ajaxurl,
                type: 'post',
                dataType: 'html',
                data: {'action': 'gbwm_ajax', 'siteID': siteID, 'templatesiteID': templatesiteID, 'templateID': templateID, 'ajaxFunction': 'ChangeTemplate'
                },

                success: function(result) {

                    $('.SetTemplateTD').css('text-align', 'unset');
                    $('.SetTemplateTD').html(result);

                    // hide ajax messages
                    $('.alert').hide();

                },

                error: function(xhr, desc, err) {

                    $('.LoadingSitesAjax').hide();

                    $('#WooAPIContainer'+siteID+'ReplaceRows').html('Details: ' + desc + '\nError:' + err);

                    // simply set the "force" parameter to true:
                    $('.WooAPIContainer'+siteID+' .widefat.gbwmtable').LoadingOverlay('hide', true);

                }

            }); // end ajax call

        }); // end close template editor





        /**
         * install template
         */

        $(this).on('click', '.InstallTemplate', function() {

            // get the siteID
            var template_type = $(this).data('templatetype');

            // get the siteID
            var templateID = $(this).data('templateid');

            var ThisButton = $(this);

            // make the ajax call

            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {'action': 'gbwm_ajax', 'template_type': template_type, 'templateID': templateID, 'ajaxFunction': 'InstallTemplate'
                },

                success: function(result) {

                    // if the ID is 0 then its an error
                    if(result.ID == 0){

                        $('.alert').addClass('MessageError');

                        $('.ajaxMessage').html('Sorry there was an error activating this template.<br>Error:<br><strong>'+result.message+'</strong>');

                        $('.alert').show();

                    }

                    // if the ID is 1 then its a success
                    if(result.ID == 1){

                        // removeClass
                        $(ThisButton).removeClass( 'btn-dark InstallTemplate' );

                        // addClass
                        $(ThisButton).addClass( 'btn-success DeactivateTemplate' );

                        // change button text
                        $(ThisButton).text( 'Deactivate' );

                    }

                },

                error: function(xhr, desc, err) {

                    $('.LoadingSitesAjax').hide();

                    $('#WooAPIContainer'+templateID+'ReplaceRows').html('Details: ' + desc + '\nError:' + err);

                    // simply set the "force" parameter to true:
                    $('.WooAPIContainer'+templateID+' .widefat.gbwmtable').LoadingOverlay('hide', true);

                }

            }); // end ajax call

        }); // end install template





        /**
         * activate template
         */

        $(this).on('click', '.ActivateTemplate', function() {

            // get the siteID
            var template_type = $(this).data('templatetype');

            // get the siteID
            var templateID = $(this).data('templateid');

            var ThisButton = $(this);

            // make the ajax call

            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {'action': 'gbwm_ajax', 'template_type': template_type, 'templateID': templateID, 'ajaxFunction': 'ActivateTemplate'
                },

                success: function(result) {

                    // if the ID is 0 then its an error
                    if(result.ID == 0){

                        $('.alert').addClass('MessageError');

                        $('.ajaxMessage').html('Sorry there was an error activating this template.<br>Error:<br><strong>'+result.message+'</strong>');

                        $('.alert').show();

                    }

                    // if the ID is 1 then its a success
                    if(result.ID == 1){

                        // removeClass
                        $(ThisButton).removeClass( 'btn-dark ActivateTemplate' );

                        // addClass
                        $(ThisButton).addClass( 'btn-success DeactivateTemplate' );

                        // change button text
                        $(ThisButton).text( 'Deactivate' );

                    }

                },

                error: function(xhr, desc, err) {

                    $('.LoadingSitesAjax').hide();

                    $('#WooAPIContainer'+templateID+'ReplaceRows').html('Details: ' + desc + '\nError:' + err);

                    // simply set the "force" parameter to true:
                    $('.WooAPIContainer'+templateID+' .widefat.gbwmtable').LoadingOverlay('hide', true);

                }

            }); // end ajax call

        }); // end activate template





        /**
         * deactivate template
         */

        $(this).on('click', '.DeactivateTemplate', function() {

            // get the siteID
            var template_type = $(this).data('templatetype');

            // get the siteID
            var templateID = $(this).data('templateid');

            var ThisButton = $(this);

            // make the ajax call

            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {'action': 'gbwm_ajax', 'template_type': template_type, 'templateID': templateID, 'ajaxFunction': 'DeactivateTemplate'
                },

                success: function(result) {

                    // if the ID is 0 then its an error
                    if(result.ID == 0){

                        $('.alert').addClass('MessageError');

                        $('.ajaxMessage').html('Sorry there was an error activating this template.<br>Error:<br><strong>'+result.message+'</strong>');

                        $('.alert').show();

                    }

                    // if the ID is 1 then its a success
                    if(result.ID == 1){

                        // removeClass
                        $(ThisButton).removeClass( 'btn-success DeactivateTemplate' );

                        // addClass
                        $(ThisButton).addClass( 'btn-dark ActivateTemplate' );

                        // change button text
                        $(ThisButton).text( 'Activate' );

                    }

                },

                error: function(xhr, desc, err) {

                    $('.LoadingSitesAjax').hide();

                    $('#WooAPIContainer'+templateID+'ReplaceRows').html('Details: ' + desc + '\nError:' + err);

                    // simply set the "force" parameter to true:
                    $('.WooAPIContainer'+templateID+' .widefat.gbwmtable').LoadingOverlay('hide', true);

                }

            }); // end ajax call

        }); // end deactivate template






        /**
         * functions for the plugin are below
         */



        /**
         * get orders = site, ammount to get, what page to get
        */
        function GetOrders (siteID, PageNumber = 1, NewStatus) {

            // Let's call it 2 times just for fun...
            $('#WooAPIContainer'+siteID+' .gbwmtable').LoadingOverlay('show', {
                background  : 'rgba(69, 136, 205, 0.5)',
                text        : 'Loading Please Wait...',
            });

            $.ajax({
                url: ajaxurl,
                type: 'post',
                dataType: 'html',
                data: {'action': 'gbwm_ajax', 'siteID': siteID, 'PageNumber': PageNumber, 'NewStatus': NewStatus, 'ajaxFunction': 'GetOrders'
            },

                success: function(result) {

                    $('#WooAPIContainer'+siteID).html(result);

                    // hide the overlay
                    $('.WooAPIContainer'+siteID+' .LoadingSitesAjax').LoadingOverlay('hide', true);

                    // simply set the "force" parameter to true:
                    $('.WooAPIContainer'+siteID+' .widefat.gbwmtable').LoadingOverlay('hide', true);

                },

                error: function(xhr, desc, err) {

                    $('#WooAPIContainer'+siteID+'ReplaceRows').html('Details: ' + desc + '\nError:' + err);

                    // simply set the "force" parameter to true:
                    $('.WooAPIContainer'+siteID+' .widefat.gbwmtable').LoadingOverlay('hide', true);

                }

            }); // end ajax call

        };



    });// end document ready

})(jQuery);