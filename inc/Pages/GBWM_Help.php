<?php
/**
 * @package GenBuz WooMulti
 */

namespace GBWM_Inc\Pages;

class GBWM_Help {

    public static function gbwm_help()
    {

        // current page NOT slug, eg admin.php
        global $pagenow;

        // gets the sites main url without traling slash
        $ThisSiteURL = get_option( 'siteurl' );

        // slug for current page, eg manage-sites
        $slug = $_GET['page'];

        ?>
        <div class="wrap gbwm-help">

            <h1 class="hideH1"></h1>

            <div class="orders-title bluebg title-font">
                <span><?php _e( 'WooMulti Help', 'woomulti' );?></span>
            </div>

            <p><?php _e( 'If you need help getting started then check out the help guides listed below.', 'woomulti' );?></p>

            <div id="help-tabs" class="help-tabs">

                <ul id="HelpTabs">
                    <li class="help-tab title-font"><a href="#add-site"><?php _e( 'Add A Site', 'woomulti' );?></a></li>
                    <li class="help-tab title-font"><a href="#add-courier"><?php _e( 'Add A Courier', 'woomulti' );?></a></li>
                    <li class="help-tab title-font"><a href="#error-messages"><?php _e( 'Error Messages', 'woomulti' );?></a></li>
                </ul>

                <div class="tab-content">

                    <div id="add-site">

                        <h1 class="ct-headline blue title-font"><?php _e( 'How to add a site', 'woomulti' );?></h1>

                        <p><?php _e( 'Before you can start managing orders from sites you connect to, you first must activate the WooCommerce API and generate a Consumer Key and a Consumer Secret on those sites. This guide will take you through the steps needed to do just that.', 'woomulti' );?></p>

                        <p><?php _e( 'As mentioned in the WooMulti description, the plugin uses a second plugin (WooSite) on the connected sites for some features such as updating shipping and billing address and also adding tracking information to orders and displaying them in customer emails and the customer account section, you do not require the WooSite plugin for updating status and viewing orders but for this guide I will be adding all the features so I will also be installing the WooSite plugin on the connected sites.', 'woomulti' );?></p>

                        <p><?php _e( 'so Lets imagine your sites are like the following.', 'woomulti' );?></p>

                        <ul>
                            <li>www.main-site.com (WooMulti plugin installed, all sites connect to this site.)</li>
                            <li>www.site-1.com (WooSite plugin installed)</li>
                            <li>www.site-2.com (WooSite plugin installed)</li>
                            <li>www.site-3.com (WooSite plugin installed)</li>
                            <li>www.site-4.com (WooSite plugin installed)</li>
                            <li>www.site-5.com (WooSite plugin installed)</li>
                        </ul>

                        <p><?php _e( 'Our setup is quite simple', 'woomulti' );?></p>

                        <ul>
                            <li><?php _e( 'Step 1, Install WooSite on the site(s) we want to connect to.', 'woomulti' );?></li>
                            <li><?php _e( 'Step 2, Activate and generate Consumer Key and Consumer Secret.', 'woomulti' );?></li>
                            <li><?php _e( 'Step 3, Add the site to WooMulti (www.main-site.com)', 'woomulti' );?></li>
                        </ul>

                        <p><?php _e( '3 easy steps and your done, so lets get started.', 'woomulti' );?></p>

                        <p>&nbsp;</p>


                        <h3 class="title-font bold500"><?php _e( 'Step 1 - Install The WooSite Plugin.', 'woomulti' );?></h3>

                        <p><?php _e( 'Go to the WooCommerce site you want to connect to (example: www.site-1.com) and login to wordpress, then goto "Plugins > Add New"., now type in the search bar "woosite" and you should see the woosite plugin as shown in the image below, click "Install Now" on the "WooSite" Plugin.', 'woomulti' );?></p>

                        <img alt="" src="<?php echo GBWM_PLUGIN_URL ;?>assets/images/help/add-site-1.jpg" class="ct-image help-image">




                        <h3 class="title-font bold500"><?php _e( 'Step 2 - Generate Consumer Key And Consumer Secret.', 'woomulti' );?></h3>

                        <p><?php _e( 'After you install the WooSite Plugin and while still on the site you want to connect the next thing you need to do is Generate a Consumer Key and Consumer Secret, to do this goto WooCommerce > Settings and click on the "Advanced tab" and then on the sub menu under the tabs click on "REST API" you will see a screen like the image below, on that screen click on the "Add Key" button.', 'woomulti' );?></p>

                        <img alt="" src="<?php echo GBWM_PLUGIN_URL ;?>assets/images/help/add-site-2.jpg" class="ct-image help-image">

                        <p><?php _e( 'On the next screen you will be asked to choose 3 options, "Description" is the name you want to give this API key, I name mine "WooMulti API" so I know what it is for but you can name it anything you like, next you need to select the "User" this API key is assigned to (The API needs a user who is capable of read/write access) so for best results select the admin, next you choose the "Permissions" again read/write access it needed.', 'woomulti' );?></p>

                        <p><?php _e( 'When you have done this you should have a screen similar to the image below, if you have click "Generate API key".', 'woomulti' );?></p>

                        <img alt="" src="<?php echo GBWM_PLUGIN_URL ;?>assets/images/help/add-site-3.jpg" class="ct-image help-image">

                        <p><?php _e( 'On the next screen you will be shown you Consumer Key and Consumer Secret, you need to copy both of these keys because you will not be able to see them again if you leave the page, you would have to create new keys and we are going to need these keys in a moment, the image below is what you should see.', 'woomulti' );?></p>

                        <img alt="" src="<?php echo GBWM_PLUGIN_URL ;?>assets/images/help/add-site-4.jpg" class="ct-image help-image">




                        <h3 class="title-font bold500"><?php _e( 'Step 3 - Add The Site To The WooMulti Plugin.', 'woomulti' );?></h3>

                        <p><?php _e( 'Now goto the main-site.com/wp-admin and login (the site with WooMulti installed) now goto "WooMulti > Sites" and you will see the following screen, once there click on the "Add Site" button as seen in the image below.', 'woomulti' );?></p>

                        <img alt="" src="<?php echo GBWM_PLUGIN_URL ;?>assets/images/help/add-site-5.jpg" class="ct-image help-image">

                        <p><?php _e( 'On the next screen you need to enter the site URL, the Consumer Key, the Consumer Secret and last choose if the site is active (ready to connect and manage orders) and then click on "Add Site" button as seen in the image below.', 'woomulti' );?></p>

                        <img alt="" src="<?php echo GBWM_PLUGIN_URL ;?>assets/images/help/add-site-6.jpg" class="ct-image help-image">




                        <h3 class="title-font bold500"><?php _e( 'All Done!', 'woomulti' );?></h3>

                        <p><?php _e( 'Congratualtions you have just added your first site to WooMulti, now when you goto "WooMulti > Orders" you will see your added site on the left menu under the dashboard, you need to do this for all sites that you add to the plugin.', 'woomulti' );?></p>

                        <p><?php _e( 'You do not need to install the "WooSite" plugin on the main site with WooMulti installed as WooMulti already has the features installed but you still need to generate a Consumer Key and Consumer Secret.', 'woomulti' );?></p>

                    </div>

                    <div id="add-courier">

                        <h1 class="ct-headline blue title-font"><?php _e( 'How to add a courier', 'woomulti' );?></h1>

                        <p><?php _e( 'Adding a courier is really easy.', 'woomulti' );?></p>

                        <ul>
                            <li><?php _e( 'Goto WooMulti > Couriers.', 'woomulti' );?></li>
                            <li><?php _e( 'Click on Add Courier.', 'woomulti' );?></li>
                            <li><?php _e( 'Enter a name for the courier example: (Royal Mail).', 'woomulti' );?></li>
                            <li><?php _e( 'Enter the courier URL example: (https://www.royalmail.com) or a direct link to the tracking page example: (https://www.royalmail.com/track-your-item#/).', 'woomulti' );?></li>
                            <li><?php _e( 'Select Active or Inactive.', 'woomulti' );?></li>
                            <li><?php _e( 'Click on Add Courier.', 'woomulti' );?></li>
                        </ul>

                        <h3 class="title-font bold500"><?php _e( 'All Done!', 'woomulti' );?></h3>

                    </div>

                    <div id="error-messages">

                        <h1 class="ct-headline blue title-font"><?php _e( 'Help with error messages', 'woomulti' );?></h1>


                        <h3 class="title-font bold500">Syntax error</h3>

                        <p><?php _e( 'This error is usually caused the site you are trying to connect to has permalinks set to "Plain" example: (https://www.yoursite.com/?p=123) in order for the WooCommerce API to work it requires permalinks set to somthing other than "Plain".', 'woomulti' );?></p>

                        <p>&nbsp;</p>



                        <h3 class="title-font bold500">cURL Error: Connection timed out after XXXXX milliseconds</h3>

                        <p><?php _e( 'This message means the plugin was unable to reach the site it was trying to connect to, the site you are trying to connect to could be temporally or permanently down, try connecting again later.', 'woomulti' );?></p>

                        <p>&nbsp;</p>



                        <h3 class="title-font bold500">Error: Sorry, you cannot list resources. [woocommerce_rest_cannot_view]</h3>

                        <p><?php _e( 'This message normally means that the connection credentials for the site you tried to connnect to (Consumer Key and/or Consumer Secret) did not work (login failed).', 'woomulti' );?></p>

                        <p><?php _e( 'On some occasions this error happens after an update of woocommerce on either the main site (WooMulti) or the connected site (WooSite), this is a common problem with woocommerce and a quick fix is to goto "Wordpress Admin > Settings > Permalinks" and re-save the settings, remember permalinks can NOT be set to "Plain".', 'woomulti' );?></p>

                        <p>&nbsp;</p>



                        <h3 class="title-font bold500">Error: Invalid signature - provided signature does not match. [woocommerce_rest_authentication_error]</h3>

                        <p><?php _e( 'The most common reason for this error is that you are trying to connect to example: http://www.somesite.com and the site in question only accepts secure connections (http<span style="color:red;font-weight:bold;">s</span>://) and what happens is the site will "redirect" you to the secure version of the site, this can break things.', 'woomulti' );?></p>

                        <p><?php _e( 'Make sure you connect to the site using the correct method, if the site uses a secure connection (https) then make sure the site is added or updated to use (https) and not (http).', 'woomulti' );?></p>

                        <p>&nbsp;</p>



                        <h3 class="title-font bold500">SSL Problems</h3>

                        <p><?php _e( 'A possible solution is to edit the .htaccess, ONLY DO THE FOLLOWING IF YOU ARE CONFIDENT IN WHAT YOU ARE DOING AS THIS CAN BREAK YOUR SITE.', 'woomulti' );?></p>

                        <p><?php _e( 'Edit your .htaccess and include the following lines', 'woomulti' );?></p>

                        <p><code>RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]</code></p>

                        <p><code>&lt;IfModule mod_fastcgi.c><br/>
                        FastCgiConfig -pass-header Authorization<br/>
                        &lt;/IfModule></code></p>

                        <p><?php _e( 'When finished your .htaccess file should look similar to the example below (Your .htaccess could have more than the example below) and the red text indicates what has been added and where.', 'woomulti' );?></p>

                        <p><code># BEGIN WordPress<br/>
                        &lt;IfModule mod_rewrite.c><br/>
                        RewriteEngine On<br/>
                        RewriteBase /<br/>
                        <span style="color:red;">RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]</span><br/>
                        RewriteRule ^index\.php$ - [L]<br/>
                        RewriteCond %{REQUEST_FILENAME} !-f<br/>
                        RewriteCond %{REQUEST_FILENAME} !-d<br/>
                        RewriteRule . /index.php [L]<br/>
                        &lt;/IfModule><br/>
                        <br/>
                        # END WordPress<br/>
                        <br/>
                        <span style="color:red;">&lt;IfModule mod_fastcgi.c><br/>
                        FastCgiConfig -pass-header Authorization<br/>
                        &lt;/IfModule></span></code></p>

                        <p>&nbsp;</p>


                    </div>

                </div><?php // end tab-content ?>

            </div><?php // end tabs ?>

        </div><?php // end wrap ?>

<?php
    }// end help function

}// end class