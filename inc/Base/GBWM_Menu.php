<?php
/**
 * @package GenBuz WooMulti
 */

namespace GBWM_Inc\Base;

class GBWM_Menu
{
    public function gbwm_register()
    {
        // check to see if woocommerce is installed and active
        add_action('plugins_loaded', array( $this, 'gbwm_check_for_woocommerce' ) );

        // setup the admin menu and enqueue scripts
        add_action( 'admin_menu', array( $this, 'gbwm_create_admin_menu' ) );

        // add our custom css class to the body
        add_filter( 'admin_body_class', function( $classes ) {

            $classes .= 'gbwm-css';

            return $classes;

        } );// end admin_body_class filter

    }// end gbwm_register

    // this plugin needs woocommerce so checif its installed and activated
    public function gbwm_check_for_woocommerce() {

        // if woocommerce is not found
        if (!defined('WC_VERSION')) {

            // 0 = not installed or inactive
            $this->WooActive = 0;

        } else {// if woocommerce is found

            // 1 = is installed and active
            $this->WooActive = 1;

        }// end else

    }// end check_for_woocommerce



    // create the admin menu
    public function gbwm_create_admin_menu()
    {

        if($this->WooActive == 0){// woocommerce not installed or active

            $gbwm_nowoo = add_menu_page(
                'Genbuz WooMulti '.__( 'WooCommerce not found', 'woomulti' ), // page title
                'WooMulti', // Menu Title
                'manage_options', // manage_options means admin only
                'gbwm_nowoo', // the page slug for this menu item
                array( $this, 'gbwm_render_nowoo' ), // fucntion for page content if passing to page
                'dashicons-cart', // icon for the menu (top level only)
                25 // priority where on the menu it is placed, I want it before woocommerce products
            );

            $slugs = array(
                $gbwm_nowoo,
            );

        }else{// woocommerce is installed and active

                // load site settings
                @ $gbwm_load_settings = get_option( 'gbwm_plugin_settings' );

                $gbwm_main = add_menu_page(
                    'Genbuz WooMulti '.__( 'Manage Orders', 'woomulti' ), // page title
                    'WooMulti', // Menu Title
                    'manage_options', // manage_options means admin only
                    'gbwm_orders', // the page slug for this menu item
                    array( $this, 'gbwm_render_orders' ), // fucntion for page content if passing to page
                    'dashicons-cart', // icon for the menu (top level only)
                    25 // priority where on the menu it is placed, I want it before woocommerce products
                );

                // renamed first submenu from default Top level to new name
                $gbwm_orders = add_submenu_page(
                    'gbwm_orders', //$parent_slug
                    'Genbuz WooMulti '.__( 'Manage Orders', 'woomulti' ), //$page_title
                    __( 'Orders', 'woomulti' ), //$menu_title
                    'manage_options', //$capability
                    'gbwm_orders', //$menu_slug
                    array( $this, 'gbwm_render_orders' ) //$function
                );

                $gbwm_sites = add_submenu_page(
                    'gbwm_orders', //$parent_slug
                    __( 'Sites', 'woomulti' ), //$page_title
                    __( 'Sites', 'woomulti' ), //$menu_title
                    'manage_options', //$capability
                    'gbwm_sites', //$menu_slug
                    array( $this, 'gbwm_render_sites' )//$function
                );

                if( $gbwm_load_settings['gbwm_enable_tracking'] == 'Yes' ){

                    $gbwm_couriers = add_submenu_page(
                        'gbwm_orders', //$parent_slug
                        __( 'Couriers', 'woomulti' ), //$page_title
                        __( 'Couriers', 'woomulti' ), //$menu_title
                        'manage_options', //$capability
                        'gbwm_couriers', //$menu_slug
                        array( $this, 'gbwm_render_couriers' )//$function
                    );

                }else{

                    $gbwm_couriers = '';

                }

                if( $gbwm_load_settings['gbwm_enable_downloads'] == 'Yes' ){

                $gbwm_downloads = add_submenu_page(
                    'gbwm_orders', // Third party parent plugin Slug
                    __( 'Downloads', 'woomulti' ), //$page_title
                    __( 'Downloads', 'woomulti' ), //$menu_title
                    'manage_options', // permisions
                    'gbwm_downloads', // this page slug
                    array( $this, 'gbwm_render_downloads' ) // callback
                );

                $gbwm_templates = add_submenu_page(
                    'gbwm_orders', //$parent_slug
                    __( 'Templates', 'woomulti' ), //$page_title
                    __( 'Templates', 'woomulti' ), //$menu_title
                    'manage_options', //$capability
                    'gbwm_templates', //$menu_slug
                    array( $this, 'gbwm_render_templates' ) //$function
                );

                }else{

                    $gbwm_downloads = '';
                    $gbwm_templates = '';

                }

                $gbwm_settings = add_submenu_page(
                    'gbwm_orders', //$parent_slug
                    __( 'Settings', 'woomulti' ), //$page_title
                    __( 'Settings', 'woomulti' ), //$menu_title
                    'manage_options', //$capability
                    'gbwm_settings', //$menu_slug
                    array( $this, 'gbwm_render_settings' ) //$function
                );

                $gbwm_help = add_submenu_page(
                    'gbwm_orders', //$parent_slug
                    __( 'Help', 'woomulti' ), //$page_title
                    __( 'Help', 'woomulti' ), //$menu_title
                    'manage_options', //$capability
                    'gbwm_help', //$menu_slug
                    array( $this, 'gbwm_render_Help' ) //$function
                );

                $slugs = array(
                    $gbwm_main,
                    $gbwm_orders,
                    $gbwm_sites,
                    $gbwm_couriers,
                    $gbwm_downloads,
                    $gbwm_templates,
                    $gbwm_settings,
                    $gbwm_help,
                );
            }


        // set the css and scripts
        foreach ( $slugs as $slug )
        {
            /**
             * THE DOUBLE QUOTES ARE NEEDED
             */
            // make sure the style callback is used on our page only
            add_action("admin_print_styles-$slug", array( $this, 'gbwm_load_styles' ), 999 );

            // make sure the script callback is used on our page only
            add_action("admin_print_scripts-$slug", array( $this, 'gbwm_load_scripts' ), 999 );
        }
    }



    // render the orders page
    public function gbwm_render_nowoo()
    {
        // ClassName::function_name
        \GBWM_Inc\Pages\GBWM_NoWoo::gbwm_nowoo();
    }



    // render the orders page
    public function gbwm_render_orders()
    {
        // ClassName::function_name
        \GBWM_Inc\Pages\GBWM_Orders::gbwm_orders();
    }


    // render the sites page
    public function gbwm_render_sites()
    {
        // ClassName::function_name
        \GBWM_Inc\Pages\GBWM_Sites::gbwm_sites();
    }

    // render the couriers page
    function gbwm_render_couriers()
    {
        // ClassName::function_name
        \GBWM_Inc\Pages\GBWM_Couriers::gbwm_couriers();
    }

    // render the downloads page
    function gbwm_render_downloads()
    {
        // ClassName::function_name
        \GBWM_Inc\Pages\GBWM_Downloads::gbwm_downloads();
    }

    // render the templates page
    public function gbwm_render_templates()
    {
        // ClassName::function_name
        \GBWM_Inc\Pages\GBWM_Templates::gbwm_templates();
    }


    // render the settings page
    public function gbwm_render_settings()
    {
        // ClassName::function_name
        \GBWM_Inc\Pages\GBWM_Settings::gbwm_settings();
    }


    // render the help page
    public function gbwm_render_Help()
    {
        // ClassName::function_name
        \GBWM_Inc\Pages\GBWM_Help::gbwm_help();
    }






    // load our css
    public function gbwm_load_styles()
    {

        // google font
        wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Oswald:300,400,500', false );

        // font awesome
        wp_enqueue_style( 'gbwm-font-awesome', '//use.fontawesome.com/releases/v5.2.0/css/all.css', false );

        //this is the color picker css
        wp_register_style( 'gbwm-spectrum', GBWM_PLUGIN_URL.'assets/spectrum.css', array(), time(), 'all' );
        wp_enqueue_style('gbwm-spectrum');

        //venobox lightbox
        wp_register_style( 'gbwm-venobox', GBWM_PLUGIN_URL.'assets/venobox.css', array() );
        wp_enqueue_style('gbwm-venobox');

        //custom jquery ui structure
        wp_register_style( 'gbwm-jquery-ui-structure', GBWM_PLUGIN_URL.'assets/jquery-ui.structure.css', array(), time(), 'all' );
        wp_enqueue_style('gbwm-jquery-ui-structure');

        //custom jquery ui theme
        wp_register_style( 'gbwm-jquery-ui-theme', GBWM_PLUGIN_URL.'assets/jquery-ui.theme.css', array(), time(), 'all' );
        wp_enqueue_style('gbwm-jquery-ui-theme');

        //this is the plugins css
        wp_register_style( 'gbwm-style', GBWM_PLUGIN_URL.'assets/gbwm-style.css', array(), time(), 'all' );
        wp_enqueue_style('gbwm-style');

    }



    // load our js
    public function gbwm_load_scripts()
    {

        // load the jquery-ui scripts as needed
        //wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-widget' );
        wp_enqueue_script( 'jquery-ui-accordion' );
        wp_enqueue_script( 'jquery-ui-tabs' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'jquery-ui-draggable' );
        wp_enqueue_script( 'jquery-ui-droppable' );
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_enqueue_script( 'jquery-ui-position' );
        wp_enqueue_script( 'jquery-ui-button' );

        // js overlay
        wp_register_script('gbwm_loadingoverlay-js', GBWM_PLUGIN_URL.'assets/loadingoverlay.js', array('jquery'),false, true);
        wp_enqueue_script('gbwm_loadingoverlay-js');

        // js simplemodal
        wp_register_script('gbwm_simplemodal-js', GBWM_PLUGIN_URL.'assets/jquery.simplemodal.js', array('jquery'),false, true);
        wp_enqueue_script('gbwm_simplemodal-js');

        // js spectrum color picker
        wp_register_script('gbwm_spectrum-js', GBWM_PLUGIN_URL.'assets/spectrum.js', array('jquery'),false, true);
        wp_enqueue_script('gbwm_spectrum-js');

        // venobox lightbox
        wp_register_script('gbwm-venobox-js', GBWM_PLUGIN_URL.'assets/venobox.js', array('jquery'),time(), true);
        wp_enqueue_script('gbwm-venobox-js');

        // this is the plugins js
        wp_enqueue_script( 'gbwm-js', GBWM_PLUGIN_URL.'assets/gbwm-js.js', array(), time(), true );

        // used to pass php info and other things to jquery
        $translation_array = array( 'downloadsURL' => GBWM_UPLOADS_URL, 'siteURL' => GBWM_UPLOADS_URL );
        //after wp_enqueue_script
        wp_localize_script( 'gbwm-js', 'trans', $translation_array );

    }




}