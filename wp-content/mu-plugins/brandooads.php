<?php
/**
* @package BrandooAds
* @version 1.0
*/
/*
Plugin Name: Brandoo Ads
Plugin URI: http://wordpress.brandoo.pl
Description: Advertisement plugin for Brandoo WordPress commercial products.
Author: Michał Gibas
Version: 1.0
Author URI: http://brandoo.pl
*/

/* Copyright 2013 Grupa Brandoo (email: info@brandoo.pl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

/**
* Add a widget to the dashboard.
*
* This function is hooked into the 'wp_dashboard_setup' action below.
*/

function support_add_dashboard_widgets() {

wp_add_dashboard_widget(
                 'support_dashboard_widget', // Widget slug.
                 'Commercial support', // Title.
                 'support_dashboard_widget_function' // Display function.
        );	
}
add_action( 'wp_dashboard_setup', 'support_add_dashboard_widgets' );

/**
* Create the function to output the contents of our Dashboard Widget.
*/
function support_dashboard_widget_function() {

// Display whatever it is you want to show.
echo '
<div style="width:100%;background:#f65974;padding:0;color:white;font-family:Tahoma;height:auto;padding:0;overflow:hidden;">
<h2 style="color:white;padding:20px;font-size:1.4em;background:#d1334e;display:block;height:auto;font-style:italic;text-align:center;text-shadow:#630a85 2px 2px 2px;border-bottom:3px solid #801124;">Any problems? Choose proffessional support.<br />We are here to help you 24/7.</h2>
<img src="http://wordpress.brandoo.pl/wp-content/uploads/2013/10/support.png" style="float:left;width:160px; height:162px;margin:-20px 0 0 0px;" />
<span style="color:white;font-size:1.2em;margin:10px 20px;display:block;text-align:justify;">Assuring your peace of mind, our experts are here to help you solve any issues. We can solve any difficulties you might have with your website immediately. With Brandoo WordPress Support Team and Windows Azure helpdesk you get professional help 24/7.</span>
<a href="http://wordpress.brandoo.pl/products/#commercial-support" style="float:right;background:#ffae00;padding:10px 15px;margin:10px;color:black;text-align:center;border:5px white solid;-moz-box-shadow: 0px 0px 10px #858585;-webkit-box-shadow: 0px 0px 10px #858585;box-shadow: 0px 0px 10px #858585;-moz-border-radius-topleft: 10px;-moz-border-radius-topright:10px;-moz-border-radius-bottomleft:10px;-moz-border-radius-bottomright:10px;-webkit-border-top-left-radius:10px;-webkit-border-top-right-radius:10px;-webkit-border-bottom-left-radius:10px;-webkit-border-bottom-right-radius:10px;border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:10px;border-bottom-right-radius:10px;" target="_blank">MORE...</a>
</div>
';
}

function marketing_add_dashboard_widgets() {

wp_add_dashboard_widget(
                 'marketing_dashboard_widget', // Widget slug.
                 'Search marketing solutions', // Title.
                 'marketing_dashboard_widget_function' // Display function.
        );	
}
add_action( 'wp_dashboard_setup', 'marketing_add_dashboard_widgets' );

/**
* Create the function to output the contents of our Dashboard Widget.
*/
function marketing_dashboard_widget_function() {

// Display whatever it is you want to show.
echo '
<div style="width:100%;background:#0dd0a8;padding:0;color:white;font-family:Tahoma;height:auto;padding:0;overflow:hidden;">
<h2 style="color:white;padding:20px;font-size:1.4em;background:#08b692;display:block;height:auto;font-style:italic;text-align:center;text-shadow:#03896e 2px 2px 2px;border-bottom:3px solid #03896e;">Be visible and sell more.</h2>
<img src="http://wordpress.brandoo.pl/wp-content/uploads/2013/10/marketing.png" style="float:left;width:160px; height:190px;margin:-30px 0 0 0px;" />
<span style="color:white;font-size:1.2em;margin:10px 20px;display:block;text-align:justify;">Have a website? Need an audience? Our dedicated search engine marketing and optimization team will work closely with you to promote your project and company effectively on the Web.</span>
<a href="http://wordpress.brandoo.pl/products/#search-marketing" style="float:right;background:#ffae00;padding:10px 15px;margin:10px;color:black;text-align:center;border:5px white solid;-moz-box-shadow: 0px 0px 10px #858585;-webkit-box-shadow: 0px 0px 10px #858585;box-shadow: 0px 0px 10px #858585;-moz-border-radius-topleft: 10px;-moz-border-radius-topright:10px;-moz-border-radius-bottomleft:10px;-moz-border-radius-bottomright:10px;-webkit-border-top-left-radius:10px;-webkit-border-top-right-radius:10px;-webkit-border-bottom-left-radius:10px;-webkit-border-bottom-right-radius:10px;border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:10px;border-bottom-right-radius:10px;" target="_blank">MORE...</a>
</div>
';
}

function design_add_dashboard_widgets() {

wp_add_dashboard_widget(
                 'design_dashboard_widget', // Widget slug.
                 'Visual identity & web page design', // Title.
                 'design_dashboard_widget_function' // Display function.
        );	
}
add_action( 'wp_dashboard_setup', 'design_add_dashboard_widgets' );

/**
* Create the function to output the contents of our Dashboard Widget.
*/
function design_dashboard_widget_function() {

// Display whatever it is you want to show.
echo '
<div style="width:100%;background:#9b58b5;padding:0;color:white;font-family:Tahoma;height:auto;padding:0;overflow:hidden;">
<h2 style="color:white;padding:20px;font-size:1.4em;background:#7c229f;display:block;height:20px;font-style:italic;text-align:center;text-shadow:#630a85 2px 2px 2px;border-bottom:3px solid #630a85;">Let us design your website!</h2>
<img src="http://wordpress.brandoo.pl/wp-content/uploads/2013/10/pencil.png" style="float:left;width:160px; height:160px;margin:-20px 0 0 0px;" />
<span style="color:white;font-size:1.2em;margin:10px 20px;display:block;text-align:justify;">We will use our experience to build a stunning website for you! Based on latest technologies, simple to manage, functional and useful - your new website will generate more traffic and improve your sales.</span>
<a href="http://wordpress.brandoo.pl/products/#cloud-website" style="float:right;background:#ffae00;padding:10px 15px;margin:10px;color:black;text-align:center;border:5px white solid;-moz-box-shadow: 0px 0px 10px #858585;-webkit-box-shadow: 0px 0px 10px #858585;box-shadow: 0px 0px 10px #858585;-moz-border-radius-topleft: 10px;-moz-border-radius-topright:10px;-moz-border-radius-bottomleft:10px;-moz-border-radius-bottomright:10px;-webkit-border-top-left-radius:10px;-webkit-border-top-right-radius:10px;-webkit-border-bottom-left-radius:10px;-webkit-border-bottom-right-radius:10px;border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:10px;border-bottom-right-radius:10px;" target="_blank">MORE...</a>
</div>
';
}

?>