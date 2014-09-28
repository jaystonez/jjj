<?php

/*
  Plugin Name: Gravity Forms CSS Themes Free
  Plugin URI: http://gravity-forms-css-themes-fontawesome-placeholders.com
  Description: a plugin to add css themes with color options to Gravity Forms. Supports FontAwesome and placeholders. 
  Version: 7
  Author: Mo Pristas
  Author URI: http://pristas.cz
 */
//let's do this - first, let's define the paths.... using multiple path options because of localhost support and debugging results(makes no sense, but worked)
define('gfct__PLUGIN_URL', plugin_dir_url(__FILE__));
define('gfct__PLUGIN_DIR', plugin_dir_path(__FILE__));
if (is_admin()) {
    //functions used in plugin backend
    require_once( plugin_dir_path(__FILE__) . 'includes/gfct-backend-functions.php'); // admin section functions
    //let's have access to the original values
    require_once( gfct__PLUGIN_DIR . 'includes/gfct-orig-values.php' );
    //option validation function for the themes is rather massive, think of ways to make it more efficient
    require_once( plugin_dir_path(__FILE__) . 'includes/gfct_option_validation.php');
    //lets load global options settings page
    require_once( gfct__PLUGIN_DIR . 'includes/gfct-global-options.php' );
    //this file contains functions messing with the gravity forms plugin, like theme dropdown or Font Awesome
    require_once( gfct__PLUGIN_DIR . 'includes/gfct-fields-gravity.php' );

    //our activation function-load all settings with add_option   
//    function gfct_activate() {
//        create_gfct_global_settings();
//        create_gfct_settings();
//    }
//    register_activation_hook( __FILE__, 'gfct_activate' );
    /////activation
    function gfct_plugin_activate() {
        create_gfct_global_settings();
        create_gfct_settings();
    }

    register_activation_hook(__FILE__, 'gfct_plugin_activate');

    function load_plugin() {

        if (is_admin() && get_option('activated_plugin') == 'plugin-gfct') {

            delete_option('activated_gfct');
        }
    }

    add_action('admin_init', 'load_plugin');
    ///end activation
    //
    //
    //
    ///include themes options
    //let's get plugin and theme options
    $options = get_option('gfct_global_settings');
//let's define what we want to load in the backend
    foreach ($options['themes'] as $theme) {
        if (1 == $theme['active']) {
            include_once( gfct__PLUGIN_DIR . 'options/gfct-' . $theme["slug"] . '-options.php' );
        };
    };
    ///themes options included now
}
//end admin, begin frontend
$options = get_option('gfct_global_settings');
//now, let's include theme parsing...
foreach ($options['themes'] as $theme) {
    if (1 == $theme['active']) {
        include_once( gfct__PLUGIN_DIR . 'themes/gfct-' . $theme["slug"] . '.php' );
    };
};
//this is here to remember what the foreach above does
//require_once( plugin_dir_path( __FILE__ ) . 'themes/gfct-stylish.php');
//
//lets load the frontend functions    
require_once( plugin_dir_path(__FILE__) . 'includes/gfct-frontend-functions.php'); //basic fron-end related functions
//lets parse the css outputs
add_action('wp_head', 'hook_gfct_css');
//load css as required by settings
$options = get_option('gfct_global_settings');
//let's include font awesome
if (1 == $options['fontawesome']) {
    //adds fontawesome, if not turned off by user in global settings
    if (1 == $options['enqueue_fontawesome']) {
        add_action('wp_enqueue_scripts', 'gfct_fontawesome_enqueue_script');
    }
    // css for FontAwesome loaded in hook_gfct_css
    //loads js for fronted
    add_action('wp_enqueue_scripts', 'gfctmagic_enqueue_script');
}

//this fction will be moved to backend functions, once the fate of dequeing gravity forms styling is decided                                                                                           
function gfct_remove_gravityforms_style() {
    wp_dequeue_style('gforms_css');
}

//let's parse our css to output in header
function hook_gfct_css() {
    $options = get_option('gfct_global_settings');
    //start with declaring stylesheet
    $mycss = '<style title="GFCT" type="text/css">';
    //first, let's see if we need to enqueue our default fontawesome
    if (1 == $options['fontawesome']) {
        $mycss .= hook_gfct_fontawesome_basic_css(); //default css for fontawesome support
    }
    //include themes if they are active
    foreach ($options['themes'] as $theme) {
        if (1 == $theme['active']) {
            $themefunction = 'gfct_' . $theme['slug'] . '_theme';
            $mycss .= call_user_func($themefunction);
        };
    };
    //$mycss .= gfct_stylish_theme(); //example of the foreach above
    //
    //close the style tag
    $mycss .='</style>';
    echo $mycss;
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'gfct_settings_link' );
