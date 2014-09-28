<?php
$post = $page = $custompost = $categories = $users = $customtaxonomy = $comments = $eshop = $wpcommerce = $woocommerce = $settings = $support = $dashboard = $export = $mappingtemplate = $filemanager = $schedulemapping = $importtype = '';
$impCEM = CallWPAdvImporterObj::getInstance();
$settings = $impCEM->getSettings();
$get_pluginData = get_plugin_data(plugin_dir_path( __FILE__ ).'../index.php'); 
$mod = isset($_REQUEST['__module']) ? $_REQUEST['__module'] : '';
if(is_array($settings)){
	foreach($settings as $key){
		$$key = true;
	}
}
if(isset($_POST['post_csv']) && $_POST['post_csv'] == 'Import')
{
	$dashboard = 'selected';
}
else if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	$$action = 'selected';
}
else if(isset($mod) && !empty($mod))
{
	$$mod = 'selected';
}
$menuHTML = "<div class='csv-top-navigation-wrapper' id='header' name='mainNavigation'><ul id='topNavigation'>";
#if($post)
	$menuHTML .="<li class=\"navigationMenu $importtype\"><a href = 'admin.php?page=".WP_CONST_ADVANCED_XML_IMP_SLUG."/index.php&__module=importtype&step=uploadfile' class = 'navigationMenu-link' id='module4'>Post / Page / CustomPost</a></li>";
#$menuHTML .= "<li class=\"navigationMenu $settings\"><a href = 'admin.php?page=".WP_CONST_ADVANCED_XML_IMP_SLUG."/index.php&__module=settings' class='navigationMenu-link' id='module15'>Settings</a></li>";
$menuHTML .= "<li class=\"navigationMenu $support\"><a href = 'admin.php?page=".WP_CONST_ADVANCED_XML_IMP_SLUG."/index.php&__module=support' class='navigationMenu-link' id='module16'>Support</a></li>";
$tabcount = count(get_option('wpadvxmlimpfreesettings')); 
$menuHTML .= "</ul>";
/*$menuHTML .= "<div style='margin-right:10px;width: 250px;float: right;'>";
$menuHTML .= "<span class='prolinks'><a class='label label-info' href='http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html' target='_blank'>GO PRO NOW</a></span>";
$menuHTML .= "<span class='prolinks'><a class='label label-info' href='http://demo.smackcoders.com/demowpthree/wp-admin/admin.php?page=wp-ultimate-csv-importer-pro/index.php&__module=dashboard' target='_blank'>TRY PRO LIVE DEMO NOW</a></span>";
$menuHTML .= "</div>";*/
$menuHTML .= "</div>";
$menuHTML .= "<div style='width:100%;padding-bottom:30px;'>";
$menuHTML .= '<div class="">
<label class="plugintags"><a href="http://wiki.smackcoders.com/WP_Ultimate_CSV_Importer" target="_blank">Wiki</a></label>
<label class="plugintags"><a href="http://wiki.smackcoders.com/WP_Ultimate_CSV_Importer_FAQ" target="_blank">Faq</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/wordpress-ultimate-csv-importer-plugin/" target="_blank">Tutorials</a></label>
<label class="plugintags"><a href="http://wiki.smackcoders.com/WP_Ultimate_CSV_Importer_Videos" target="_blank">Videos</a></label>
<label class="plugintags"><a href="http://forum.smackcoders.com/" target="_blank">Forum</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/wordpress-ultimate-csv-importer-csv-sample-files-and-updates.html" target="_blank">Sample Files</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/how-to-make-one-click-easy-csv-import-in-wordpress-free-cheat-sheet-downloads.html" target="_blank">Cheat Sheets</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/wordpress-ultimate-csv-importer-plugin/" target="_blank">Related Downloads</a></label>
<label class="plugintags"><a href="http://wiki.smackcoders.com/WP_Ultimate_CSV_Importer_Change_Log" target="_blank">Change Log</a></label>
<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/wordpress-ultimate-csv-importer-plugin/" target="_blank">Current Version News</a></label>
</div>';
if(isset ($_REQUEST['__module']) && $_REQUEST['__module'] != 'settings')
	$menuHTML .= "<div style='float:left;'><a class='label label-info' href='".get_admin_url()."admin.php?page=".WP_CONST_ADVANCED_XML_IMP_SLUG."/index.php&__module=settings'>Click here to Enable any disabled module</a></div>";

if(isset ($_REQUEST['__module']) && $_REQUEST['__module'] == 'settings') {
        $menuHTML .= "<div style='float:left;margin-right:15px;'><a class='label label-info' href='".get_admin_url()."admin.php?page=".WP_CONST_ADVANCED_XML_IMP_SLUG."/index.php&__module=support'>Click here to Get some useful links</a></div>"; 
	$menuHTML .= "<div style='float:right;margin-right:15px;'>Current Version: ".$get_pluginData['Version']." <a class='label label-info' href='http://wordpress.org/plugins/wp-ultimate-csv-importer/developers/'>Get Old Versions</a></div>";
}
if(isset ($_REQUEST['__module']) && $_REQUEST['__module'] != 'support' && $_REQUEST['__module'] != 'settings') {
        $menuHTML .= "<div style='float:right;margin-right:15px;'><a class='label label-info' href='".get_admin_url()."admin.php?page=".WP_CONST_ADVANCED_XML_IMP_SLUG."/index.php&__module=support'>Click here to Get some useful links</a></div>"; 
        $menuHTML .= "<div style='float:right;margin-right:15px;'>Current Version: ".$get_pluginData['Version']." <a class='label label-info' href='http://wordpress.org/plugins/wp-ultimate-csv-importer/developers/'>Get Old Versions</a></div>";
}
if(isset ($_REQUEST['__module']) && $_REQUEST['__module'] == 'support'){
        $menuHTML .= "<div style='float:right;margin-right:15px;'>Current Version: ".$get_pluginData['Version']." <a class='label label-info' href='http://wordpress.org/plugins/wp-ultimate-csv-importer/developers/'>Get Old Versions</a></div>";
}
$menuHTML .= "</div>";
$menuHTML .= "<div class='msg' id = 'showMsg' style = 'display:none;'></div>";
$menuHTML .= "<input type='hidden' id='current_url' name='current_url' value='".get_admin_url()."admin.php?page=".WP_CONST_ADVANCED_XML_IMP_SLUG."/index.php&__module=".$_REQUEST['__module']."&step=uploadfile'/>";
echo $menuHTML;

