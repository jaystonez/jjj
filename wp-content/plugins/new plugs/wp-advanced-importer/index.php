<?php
/******************************
Plugin Name: WP Advanced Importer
Description: A plugin that helps to import the data's from a XML file.
Version: 1.2.1
Author: smackcoders.com
Plugin URI: http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html
Author URI: http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html
 * filename: index.php
 */

ini_set('display_errors', 'Off');
ob_start();
define('WP_CONST_ADVANCED_XML_IMP_URL', 'http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html');
define('WP_CONST_ADVANCED_XML_IMP_NAME', 'WP Advanced Importer');
define('WP_CONST_ADVANCED_XML_IMP_SLUG', 'wp-advanced-importer');
define('WP_CONST_ADVANCED_XML_IMP_SETTINGS', 'WP Advanced Importer');
define('WP_CONST_ADVANCED_XML_IMP_VERSION', '1.2.1');
define('WP_CONST_ADVANCED_XML_IMP_DIR', WP_PLUGIN_URL . '/' . WP_CONST_ADVANCED_XML_IMP_SLUG . '/');
define('WP_CONST_ADVANCED_XML_IMP_DIRECTORY', plugin_dir_path( __FILE__ ));
define('WP_XMLIMP_PLUGIN_BASE', WP_CONST_ADVANCED_XML_IMP_DIRECTORY);

//require_once('config/settings.php');

if(!class_exists('SkinnyControllerWPAdvImp')){
//    require_once('lib/skinnymvc/controller/SkinnyController.php');
}

require_once('includes/WPAdvImporter_includes_helper.php');
require_once('includes/WXR_importer.php');


function action_xml_imp_admin_menu()
{
	add_menu_page(WP_CONST_ADVANCED_XML_IMP_SETTINGS, WP_CONST_ADVANCED_XML_IMP_NAME, 'manage_options',  __FILE__, array('WPAdvImporter_includes_helper','output_front_xml_page'), WP_CONST_ADVANCED_XML_IMP_DIR . "/images/icon.png");
}
add_action ( "admin_menu", "action_xml_imp_admin_menu" );

function action_xml_imp_admin_init()
{
	if(isset($_REQUEST['page']) && ($_REQUEST['page'] == 'wp-advanced-importer/index.php' || $_REQUEST['page'] == 'page')) {
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('jquery-style', plugins_url('css/jquery-ui.css', __FILE__));
		wp_register_script('advanced-importer-js', plugins_url('js/advanced-importer.js', __FILE__));
		wp_enqueue_script('advanced-importer-js');
		wp_register_script('advanced-importer-button', plugins_url('js/buttons.js', __FILE__));
		wp_enqueue_script('advanced-importer-button');
		wp_enqueue_style('advanced_importer_font_awesome', plugins_url('css/font-awesome.css', __FILE__));
		wp_register_script('jquery-min', plugins_url('js/jquery.js', __FILE__));
		wp_enqueue_script('jquery-min');
		wp_register_script('jquery-widget', plugins_url('js/jquery.ui.widget.js', __FILE__));
		wp_enqueue_script('jquery-widget');
		wp_register_script('jquery-fileupload', plugins_url('js/jquery.fileupload.js', __FILE__));
		wp_enqueue_script('jquery-fileupload');
		wp_register_script('bootstrap-collapse', plugins_url('js/bootstrap-collapse.js', __FILE__));
		wp_enqueue_script('bootstrap-collapse');
		wp_enqueue_style('style', plugins_url('css/style.css', __FILE__));
		wp_enqueue_style('jquery-fileupload', plugins_url('css/jquery.fileupload.css', __FILE__));
		wp_enqueue_style('bootstrap-css', plugins_url('css/bootstrap.css', __FILE__));
		wp_enqueue_style('advanced-importer-css', plugins_url('css/main.css', __FILE__));
	}
}
add_action('admin_init', 'action_xml_imp_admin_init');

add_action('init', 'WPAdvImpStartSession', 1);
add_action('wp_logout', 'WPAdvImpEndSession');
add_action('wp_login', 'WPAdvImpEndSession');
function importXmlRequest(){
      require_once('templates/import.php');
      die;
}
add_action('wp_ajax_importXmlRequest', 'importXmlRequest');
function WPAdvImpStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function WPAdvImpEndSession() {
    session_destroy ();
}
?>
