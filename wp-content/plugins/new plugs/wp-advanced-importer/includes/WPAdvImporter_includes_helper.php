<?php
/******************************
 * Filename	: includes/WPAdvImporter_includes_helper.php
 * Description	: Helper class for WP Advanced Importer
 * Author 	: Fredrick
 * Owner  	: smackcoders.com
 * Date   	: Mar24,2014
 */
require_once("WXR_importer.php");
class WPAdvImporter_includes_helper {

	public function __construct()
	{
		$this->getKeyVals();
	}

	// @var string CSV upload directory name
	public $uploadDir = 'advanced_importer';

	// @var boolean post title check
	public $titleDupCheck = false;

	// @var boolean content title check
	public $conDupCheck = false;

	// @var boolean for post flag
	public $postFlag = true;

	// @var int duplicate post count
	public $dupPostCount = 0;

	public $dupPageCount = 0;

	public $dupCPTCount = 0;

	// @var int inserted post count
	public $insPostCount = 0;
	
        // @var int inserted post count
	public $insPageCount = 0;
	
        // @var int inserted post count
	public $insCPTCount = 0;

	// @var int no post author count
	public $noPostAuthCount = 0;

	// @var int updated post count
	public $updatedPostCount=0;

	// @var string delimiter
	public $delim = ",";

	// @var array delilimters supported by CSV importer
	public $delim_avail = array(
			',',
			';'
			);

	// @var array wp field keys
	public $keys = array();

	// @var array for default columns
	public $defCols = array(
			'post_title' => null,
			'post_content' => null,
			'post_excerpt' => null,
			'post_date' => null,
			'post_name' => null,
			'post_tag' => null,
			'post_category' => null,
			'post_author' => null,
			'featured_image' => null,
			'post_parent' => 0,
			'post_status' => 0
			);

	// @var array CSV headers
	public $headers = array();

	public $capturedId=0;

	/* getImportDataConfiguration */
	public function getImportDataConfiguration(){
		$importDataConfig = "<div class='importstatus'id='importallwithps_div'>
                        <table><tr><td>
                        <label>Import with post status</label><span class='mandatory'> *</span></td><td>
			<div style='float:left;'>
                        <select name='importallwithps' id='importallwithps' onChange='selectpoststatus();' >
                        <option value='0'>Status as in CSV</option>
                        <option value='1'>Publish</option>
                        <option value='2'>Sticky</option>
                        <option value='4'>Private</option>
                        <option value='3'>Protected</option>
                        <option value='5'>Draft</option>
                        <option value='6'>Pending</option>
                        </select></div>
			<div style='float:right;'>
                        <a href='#' class='tooltip'>
                        <img src='".WP_CONST_ADVANCED_XML_IMP_DIR."images/help.png' />
                        <span class='tooltipPostStatus'>
                        <img class='callout' src='".WP_CONST_ADVANCED_XML_IMP_DIR."images/callout.gif' />
                        Select the status for the post  imported, if not defined within your csv .E.g.publish
                        <img src='". WP_CONST_ADVANCED_XML_IMP_DIR."images/help.png' style='margin-top: 6px;float:right;' />
                        </span></a> </div>
                        </td></tr><tr><td>
                        <div id='globalpassword_label' class='globalpassword' style='display:none;'><label>Password</label><span class='mandatory'> *</span></div></td><td>
                        <div id='globalpassword_text' class='globalpassword' style='display:none;'><input type = 'text' id='globalpassword_txt' name='globalpassword_txt' placeholder='Password for all post'></div></td></tr></table>
                        </div>";
			return $importDataConfig;
	}

	/**
	 * Get upload directory
	 */
	public function getUploadDirectory($check = 'plugin_uploads')
	{
		$upload_dir = wp_upload_dir();
		if($check == 'plugin_uploads'){
			return $upload_dir ['basedir'] . "/" . $this->uploadDir;
		}else{
			return $upload_dir ['basedir'];
		}
	}

	/**
	 *	generate help tooltip
	 *	@param string $content ** content to show on tooltip **
	 *	@return string $html ** generated HTML **
	 **/
	public function generatehelp($content, $mapping_style = NULL)
	{
		$html = '<div style = "'.$mapping_style.'"> <a href="#" class="tooltip">
			<img src="'.WP_CONST_ADVANCED_XML_IMP_DIR.'images/help.png" />
			<span class="tooltipPostStatus">
			<img class="callout" src="'.WP_CONST_ADVANCED_XML_IMP_DIR.'images/callout.gif" />
			'.$content.'
			<img src="'.WP_CONST_ADVANCED_XML_IMP_DIR.'images/help.png" style="margin-top: 6px;float:right;" />
			</span> </a> </div>';
		return $html;
	}


	public static function output_front_xml_page()
	{
		if(!isset($_REQUEST['__module']))
		{
			wp_redirect( get_admin_url() . 'admin.php?page='.WP_CONST_ADVANCED_XML_IMP_SLUG.'/index.php&__module=importtype&step=uploadfile');
		}
		require_once(WP_CONST_ADVANCED_XML_IMP_DIRECTORY.'config/settings.php');
		require_once(WP_CONST_ADVANCED_XML_IMP_DIRECTORY.'lib/skinnymvc/controller/SkinnyController.php');

		$c = new SkinnyControllerWPAdvXMLFree;
                $c->main();
	}

	public function getSettings(){
		return get_option('wpadvxmlimpfreesettings');
	}

	public function renderMenu()
	{
		include(plugin_dir_path(__FILE__) . '../templates/menu.php');
	}

	public function requestedAction($action,$step){
		$actions = array('dashboard','settings','help','users','comments','eshop','wpcommerce','woocommerce','categories','customtaxonomy','export', 'mappingtemplate');
		if(!in_array($action,$actions)){
			include(plugin_dir_path(__FILE__) . '../templates/view.php');
		}else{
			include(plugin_dir_path(__FILE__) . '../modules/'.$action.'/actions/actions.php');
			include(plugin_dir_path(__FILE__) . '../modules/'.$action.'/templates/view.php');
		}
	}

	/**
	 * Move CSV to the upload directory
	 */
	public function move_file()
	{
		if ($_FILES ["csv_import"] ["error"] == 0) {
			$tmp_name = $_FILES ["csv_import"] ["tmp_name"];
			$this->csvFileName = $_FILES ["csv_import"] ["name"];
			move_uploaded_file($tmp_name, $this->getUploadDirectory() . "/$this->csvFileName");
		}
	}

	/**
	 * Check upload dirctory permission
	 */
	function checkUploadDirPermission()
	{
		$this->getUploadDirectory();
		$upload_dir = wp_upload_dir();
		if (!is_dir($upload_dir ['basedir'])) {
			print " <div style='font-size:16px;margin-left:20px;margin-top:25px;'>" . $this->t("UPLOAD_PERMISSION_ERROR") . "
				</div><br/>
				<div style='margin-left:20px;'>
				<form class='add:the-list: validate' method='post' action=''>
				<input type='submit' class='button-primary' name='Import Again' value='" . $this->t("IMPORT_AGAIN") . "'/>
				</form>
				</div>";
			$this->freeze();
		} else {
			if (!is_dir($this->getUploadDirectory())) {
				wp_mkdir_p($this->getUploadDirectory());
			}
		}
	}


	/**
	 * Get field colum keys
	 */
	function getKeyVals()
	{
		$cust_fields='';
		$acf_field=array();
		global $wpdb;
		$active_plugins = get_option('active_plugins');
		$limit = ( int )apply_filters('postmeta_form_limit', 150);
		$this->keys = $wpdb->get_col("SELECT meta_key FROM $wpdb->postmeta
				GROUP BY meta_key
				HAVING meta_key NOT LIKE '\_%' and meta_key NOT LIKE 'field_%'
				ORDER BY meta_key
				LIMIT $limit");

		foreach ($this->keys as $val) {
			$this->defCols ["CF: " . $val] = $val;
		}

		
	
	}

	/**
	 * Function converts XML data to formatted array.
	 *
	 * @param $file XML
	 *            input filename
	 * @param $delim delimiter
	 *            for the XML
	 * @return array formatted XML output as array
	 */
	function xml_file_data($file,$fileExtension, $module='post')
	{
	//	print('am from xml_file_data');die;
		#print('$file: '. $file.'</br>');
		#print('$fileExtension: '.$fileExtension.'<br>');
		#print('$module: '. $module.'<br>');
		$file = $this->getUploadDirectory() .'/'. $file;
               //  echo '<pre>'; print_r($file); die('it works');
		ini_set("auto_detect_line_endings", true);

		$data_rows = array();
		$commentsArr = array();
                $postmetaArr= array();
                $my_titles = array();
                $my_content = array();
                $terms_val = array();
                $domain_name = array();
		$all_domain=array();
		$my_title=array();
//		$this->delim = $delim;
		//print($this->delim);die;
		$fileexists = file_exists($file);
	    if ($fileexists) {
		    if($fileExtension=='xml')
		    {
			    $mycls = new Knol_WXR_Parser();
			    //call parse() function from WXR_importer.php file that returns xml contents as array
			    $all_arr=$mycls->parse($file); 
                           
//echo '<pre>';print_r($all_arr);die('it works');
                          if(is_array($all_arr))
			    {
				    foreach($all_arr as $key => $value)
				    {
					    //Under the posts key all Posts are available in all WXR file. 
					    if($key=='posts')
					    {
						    $i=0;
						    foreach($value as  $post_value)
						    {
							if(array_key_exists('post_type',$post_value) && $post_value['post_type'] == $module){
							    $my_post[$i]=$post_value;
							    $i++;
							}
						    }
					    }
				    }

                                  if(isset($module) && $module == 'custompost')  // custompost start here
                                     {
                                      foreach($all_arr as $key => $value)
                                    {
                                            //Under the posts key all Posts are available in all WXR file. 
                                            if($key=='posts')
                                            {
                                                    $i=0;
                                                    foreach($value as  $post_value)
                                                    {
                                                   // echo '<pre>'; print_r(''); 
                                                       $eliminate = array('post','page','attachment','revision','nav_menu_item','comments');
                                                  // echo '<pre>'; print_r($eliminate); die('it works');
                                                                                                            
                                                //     echo '<pre>'; print_r($eli);  
		                                  if(array_key_exists('post_type',$post_value) )
                                                    {
  if(isset($post_value['post_type'] ) && $post_value['post_type'] != 'post' && $post_value['post_type'] != 'page' && $post_value['post_type'] != 'attachment'  && $post_value['post_type'] != 'revision'  && $post_value['post_type'] != 'comments'  && $post_value['post_type'] != 'nav_menu_item')
                                                     { 
                                                       $custom_array= $post_value;
                                                          
                                                        $my_post[$i]=$custom_array;
                                                            $i++;
                                                      
                                                        }
                                                       }
                                                   
                                            }
                                    }
                                 }
                            //  echo '<pre>'; print_r($custom_array);
                                 }//custom post end here
                   //   die('it custom post');

				    for($j=0;$j<$i;$j++)
				    {       
					    $n=0;
					    // Check here the post_type
						$post_types=get_post_types();
                                               
					    foreach($my_post[$j] as $my_key => $my_val)
					    {
                                         
						    //Key of my_posts are post headers
						    array_push($my_title,$my_key);
						    //Value of my_posts are post contents
						    if(is_array($my_val)&& $my_key!='postmeta' && $my_key!='comments' && $my_key!= 'category' && $my_key!= 'post_tag' && $my_key!= 'post_category' && $my_key!='terms')
							    $my_content[$j][$n]="";
						    elseif($my_key!='postmeta' && $my_key!='comments' && $my_key!= 'category' && $my_key!= 'post_tag' && $my_key!= 'post_category' && $my_key!='terms')
							    $my_content[$j][$n]=$my_val;
						    //In WXR file, post_categories and post_tags will be in terms key
						    if($my_key=='terms')
						    { 
							    $x=0;$z=0;
							    foreach($my_val as $my_category)
							    {	$y=0;
								    foreach($my_category as $categ_key => $categ_val)
								    {      
									    //domain refered as post_categories or post_tags
									    if($categ_key=='domain')
									    {	
										    //Some headers are in domain
										    if($categ_val){
											if(!in_array($categ_val, $all_domain))
											    $all_domain[$z]=$categ_val;
										    } else {
										//	    $all_domain[$z]=null;
										    }
										    $z++;
									    }
									    if(isset($categ_key))
										    $terms_key[$j][$x][$y]=$categ_key;
									    else
										    $terms_key[$j][$x][$y]=null;
									    if(isset($categ_val))
										    $terms_val[$j][$x][$y]=$categ_val;
									    else
										    $terms_val[$j][$x][$y]=null;

									    $y++;
								    }
								    $x++;
							    }

						    } 
						    if($my_key=='postmeta')
						    {
							foreach($my_val as $pmArr){
								$postmetaArr[$j]['postmeta'][$pmArr['key']] = $pmArr['value'];
							} 
						    } 
						    if($my_key == 'comments')
						    {
							$commentsArr[$j]['comments']= $my_val;
						    } 
						if($my_key!='postmeta' && $my_key!='comments' && $my_key!= 'category' && $my_key!= 'post_tag' && $my_key!= 'post_category')
						    $n++;
					    }
                                   
					$my_content[$j][$n]="";
				    } 
				    //Remove duplicates from my_title
				    $my_title=array_unique($my_title); 
				    //Remove duplicates from all_domain
				    $all_domain=array_unique($all_domain);
                                //   echo '<pre>'; print_r($all_domain);die('it exist');
				    foreach($all_domain as $key => $value)
				    {
					    array_push($my_title,$value);
				    }
				    $l=0;
				    foreach($my_title as $value)
				    {	
					    //Make a serial index	
					    $my_titles[$l]=$value;$l++;
				    }	
				    // collect post_categories and post_tags values
                            //echo '<pre>'; print_r($terms_val);//die('it exist');
                             if(isset($module) && $module != 'page' && $module != 'custompost')
                               { 
				for($n=0;$n<$i;$n++){
                                     //    print_r($all_domain); 
					for($m=0;$m<count($terms_val[$n]);$m++){ 
						if(in_array($terms_val[$n][$m][2],$all_domain)){
                                               //   echo '<pre>'; print_r($terms_val[$n][$m][2]); die('it exists');
							$domain_name[$n][$terms_val[$n][$m][2]][$m] = $terms_val[$n][$m][0];

						}
					}
				}
                               
				    //Insert post_categories and post_tags values
		//	echo '<pre>'; print_r($domain_name);//die('it exists'); 
                           for($l=0;$l<count($my_content);$l++)
				    {   
					    if(is_array($domain_name[$l])){
						    if(array_key_exists('post_tag', $domain_name[$l]) && array_key_exists('category', $domain_name[$l])){
							    array_push($my_content[$l],$domain_name[$l]);
						    } else {
							    if(!array_key_exists('post_tag', $domain_name[$l])){
								    $domain_name[$l]['post_tag'] = array();
							    }
							    if(!array_key_exists('category', $domain_name[$l])){
								    $domain_name[$l]['category'] = array();
							    }
							    array_push($my_content[$l],$domain_name[$l]);
						    }
					    }
				    }
                             
#print('<pre>'); #print_r($my_content); 
				    for($m=0;$m<count($my_content);$m++){
					$get_last_index = count($my_content[$m]); 
					if(array_key_exists($m,$postmetaArr)){
						foreach($my_content[$m][$get_last_index] as $newMetaKey => $newMetaVal){
							$my_content[$m][$get_last_index][$newMetaKey] = $newMetaVal;
						}
						foreach($postmetaArr[$m] as $postmetaKey => $postmetaVal){
							$my_content[$m][$get_last_index][$postmetaKey] = $postmetaVal;
						}
					}
				    }
#print_r($my_content); print_r($postmetaArr);
#die;
                                    for($m=0;$m<count($my_content);$m++){
                                        $get_last_index = count($my_content[$m]); 
                                        if(array_key_exists($m,$commentsArr)){
                                                foreach($my_content[$m][$get_last_index] as $newMetaKey => $newMetaVal){
                                                        $my_content[$m][$get_last_index][$newMetaKey] = $newMetaVal;
                                                }       
                                                foreach($commentsArr[$m] as $commentsKey => $commentsVal){
                                                        $my_content[$m][$get_last_index][$commentsKey] = $commentsVal;
                                                }       
                                        }       
                                    }
                              }
					$data_rows=$my_content;
					$this->headers=$my_titles;
				//echo '<pre>'; print_r($data_rows); die('coming');
}
		}
                  
                   } else {

		
         }		return $data_rows;
       
	
 }
      


	/**
	 * Manage duplicates
	 *
	 * @param string type = (title|content), string content
	 * @return boolean
	 */
	function duplicateChecks($type = 'title', $text, $gettype)
	{
		global $wpdb;
		//$this->dupPostCount = 0;
		if ($type == 'content') {
			$htmlDecode = html_entity_decode($text);
			$strippedText = strip_tags($htmlDecode);
			$contentLength = strlen($strippedText);
			$allPosts_count = $wpdb->get_results("SELECT COUNT(ID) as count FROM $wpdb->posts WHERE post_type = \"{$gettype}\" and post_status IN('publish','future','draft','pending','private')");
			$allPosts_count = $allPosts_count[0]->count;
			$allPosts = $wpdb->get_results("SELECT ID,post_title,post_date,post_content FROM $wpdb->posts WHERE post_type = \"{$gettype}\" and post_status IN('publish','future','draft','pending','private')");
			foreach ($allPosts as $allPost) {
				$htmlDecodePCont = html_entity_decode($allPost->post_content);
				$strippedTextPCont = strip_tags($htmlDecodePCont);
				similar_text($strippedTextPCont, $strippedText, $p);
				if ($p == 100) {
					if($gettype == 'post'){
						$this->dupPostCount++;
					}
					if($gettype == 'page'){
						$this->dupPageCount++;
					}
					if($gettype != 'post' && $gettype != 'page'){
						$this->dupCPTCount++;
					}
					#$this->dupPostCount++;
					return false;
				}
			}
			return true;
		} else if ($type == 'title') {
			$post_exist = $wpdb->get_results("select ID from " . $wpdb->posts . " where post_title = \"{$text}\" and post_type = \"{$gettype}\" and post_status in('publish','future','draft','pending','private')");
			if (count($post_exist) == 0 && ($text != null || $text != ''))
				return true;
		}
		if($gettype == 'post'){
			$this->dupPostCount++;
		}
		if($gettype == 'page'){
			$this->dupPageCount++;
		}
		if($gettype != 'post' && $gettype != 'page'){
			$this->dupCPTCount++;
		}
		#$this->dupPostCount++;
		return false;
	}


	/**
	 * function to map the csv file and process it
	 *
	 * @return boolean
	 */
	function processDataInWP($data_rows,$ret_array,$session_arr,$module)
	{
		global $wpdb;
                $new_post = array();
               
                $checktype=array();
                $qar_type = array();
                $checktype['wp_advanced_importer']['common']['type'] = array(); 
		$post_id = '';
                $cpt = '';
		$smack_taxo = array();
		$custom_array = array();
		$seo_custom_array= array();
                $cpt = $data_rows[15];		
		$headr_count = $ret_array['h2'];
		for ($i = 0; $i < count($data_rows); $i++) {
			if (array_key_exists('mapping' . $i, $ret_array)) {
            
				if($ret_array ['mapping' . $i] != '-- Select --'){
					if ($ret_array ['mapping' . $i] != 'add_custom' . $i) {
						$strip_CF = strpos($ret_array['mapping' . $i], 'CF: ');
						if ($strip_CF === 0) {
							$custom_key = substr($ret_array['mapping' . $i], 4);
							$custom_array[$custom_key] = $data_rows[$i];
						} 
						else {
							$new_post[$ret_array['mapping' . $i]] = $data_rows[$i];
						}
					} else {
						$new_post [$ret_array ['textbox' . $i]] = $data_rows [$i];
						$custom_array [$ret_array ['textbox' . $i]] = $data_rows [$i];
					}
				}
			}
		}
             
		for ($inc = 0; $inc < count($data_rows); $inc++) {
			foreach ($this->keys as $k => $v) {
				if (array_key_exists($v, $new_post)) {
					$custom_array [$v] = $new_post [$v];
				}
			}
		}
		if(is_array( $new_post )){
		foreach ($new_post as $ckey => $cval) {
			$this->postFlag = true;
			$taxo = get_taxonomies();
			foreach ($taxo as $taxokey => $taxovalue) {
				if ($taxokey != 'category' && $taxokey != 'link_category' && $taxokey != 'post_tag' && $taxokey != 'nav_menu' && $taxokey != 'post_format') {
					if ($taxokey == $ckey) {
						$smack_taxo [$ckey] = $new_post [$ckey];
					}
				}
			}

			$taxo_check = 0;
			if (!isset($smack_taxo[$ckey])) {
				$smack_taxo [$ckey] = null;
				$taxo_check = 1;
			}
			if ($ckey != 'post_category' && $ckey != 'post_tag' && $ckey != 'featured_image' && $ckey != $smack_taxo [$ckey]) {
				if ($taxo_check == 1) {
					unset($smack_taxo[$ckey]);
					$taxo_check = 0;
				}
				if (array_key_exists($ckey, $custom_array)) {
					$darray [$ckey] = $new_post [$ckey];
				} else {
					if (array_key_exists($ckey, $smack_taxo)) {
						$data_array[$ckey] = null;
					} else {
						$data_array[$ckey] = $new_post [$ckey];
					}
				}
			} else {
				switch ($ckey) {
					case 'post_tag' :
						$tags [$ckey] = $new_post [$ckey];
						break;
					case 'post_category' :
						$categories [$ckey] = $new_post [$ckey];
						break;
					case 'featured_image' :
						/*
						 * TODO: Cleanup required
						 */
						$split_filename = explode('/', htmlentities($new_post [$ckey]));
						$arr_filename = count($split_filename);
						$plain_filename = $split_filename [$arr_filename - 1];
						$new_post [$ckey] = str_replace(' ', '%20', $new_post [$ckey]);
						$file_url = $filetype [$ckey] = $new_post [$ckey];
						$file_type = explode('.', $filetype [$ckey]);
						$count = count($file_type);
						$type = $file_type [$count - 1];

						if ($type == 'png') {
							$file ['post_mime_type'] = 'image/png';
						} else if ($type == 'jpg' || $type == 'jpeg') {
							$file ['post_mime_type'] = 'image/jpeg';
						} else if ($type == 'gif') {
							$file ['post_mime_type'] = 'image/gif';
						}
						$img_name = explode('/', $file_url);
						$imgurl_split = count($img_name);
						$img_name = explode('.', $img_name [$imgurl_split - 1]);
						if (count($img_name) > 2) {
							for ($r = 0; $r < (count($img_name) - 1); $r++) {
								if ($r == 0)
									$img_title = $img_name[$r];
								else
									$img_title .= '.' . $img_name[$r];
							}
						} else {
							$img_title = $img_name = $img_name [0];
						}
						$attachmentName = urldecode($img_title) . '.' . $type;
						$dir = wp_upload_dir();
						$get_media_settings = get_option('uploads_use_yearmonth_folders');
						if($get_media_settings == 1){
							$dirname = date('Y') . '/' . date('m');
							$full_path = $dir ['basedir'] . '/' . $dirname;
							$baseurl = $dir ['baseurl'] . '/' . $dirname;
						}else{
							$full_path = $dir ['basedir'];
							$baseurl = $dir ['baseurl'];
						}
						$filename = explode('/', $file_url);
						$file_split = count($filename);
						$filepath = $full_path . '/' . urldecode($plain_filename);
						$fileurl = $baseurl . '/' . $filename [$file_split - 1];
						if (is_dir($full_path)) {
							$smack_fileCopy = @copy($file_url, $filepath);
						} else {
							wp_mkdir_p($full_path);
							$smack_fileCopy = @copy($file_url, $filepath);
						}

						if(!function_exists('wp_get_current_user')) {
						    include(ABSPATH . "wp-includes/pluggable.php"); 
						}
						$img = wp_get_image_editor($filepath);
						if (!is_wp_error($img)) {

							$sizes_array = array(
									// #1 - resizes to 1024x768 pixel, square-cropped image
									array('width' => 1024, 'height' => 768, 'crop' => true),
									// #2 - resizes to 100px max width/height, non-cropped image
									array('width' => 100, 'height' => 100, 'crop' => false),
									// #3 - resizes to 100 pixel max height, non-cropped image
									array('width' => 300, 'height' => 100, 'crop' => false),
									// #3 - resizes to 624x468 pixel max width, non-cropped image
									array('width' => 624, 'height' => 468, 'crop' => false)
									);
							$resize = $img->multi_resize($sizes_array);
						}
						if ($smack_fileCopy) {
							$file ['guid'] = $fileurl;
							$file ['post_title'] = $img_title;
							$file ['post_content'] = '';
							$file ['post_status'] = 'attachment';
						} else {
							$file = false;
						}
						break;
				}
			}
		}
		}
//get_option
                      
            
               
              
             		if(isset($module) && ($module == 'post' || $module == 'page')) {
			$data_array['post_type'] = $module;
	                }
                        else
                           {
                           $data_array['post_type'] = $cpt;   
                           }
                      
               if ($this->titleDupCheck == 'true')
			$this->postFlag = $this->duplicateChecks('title', $data_array ['post_title'], $data_array ['post_type']);

		if ($this->conDupCheck == 'true' && $this->postFlag)
			$this->postFlag = $this->duplicateChecks('content', $data_array ['post_content'], $data_array ['post_type']);

		if ($this->postFlag) {
			unset ($sticky);
			if (empty($data_array['post_status']))
				$data_array['post_status'] = null;
			if (isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['importallwithps']) && $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['importallwithps'] != 0)
				$data_array['post_status'] = $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['importallwithps'];

			switch ($data_array ['post_status']) {
				case 1 :
					$data_array['post_status'] = 'publish';
					break;
				case 2 :
					$data_array['post_status'] = 'publish';
					$sticky = true;
					break;
				case 3 :
					$data_array['post_status'] = 'publish';
					$data_array ['post_password'] = $_POST ['postsPassword'];
					break;
				case 4 :
					$data_array ['post_status'] = 'private';
					break;
				case 5 :
					$data_array ['post_status'] = 'draft';
					break;
				case 6 :
					$data_array ['post_status'] = 'pending';
					break;
				default :
					$poststatus = $data_array['post_status'] = strtolower($data_array['post_status']);
					if ($data_array['post_status'] != 'publish' && $data_array['post_status'] != 'private' && $data_array['post_status'] != 'draft' && $data_array['post_status'] != 'pending' && $data_array['post_status'] != 'sticky') {
						$stripPSF = strpos($data_array['post_status'], '{');
						if ($stripPSF === 0) {
							$poststatus = substr($data_array['post_status'], 1);
							$stripPSL = substr($poststatus, -1);
							if ($stripPSL == '}') {
								$postpwd = substr($poststatus, 0, -1);
								$data_array['post_status'] = 'publish';
								$data_array ['post_password'] = $postpwd;
							} else {
								$data_array['post_status'] = 'publish';
								$data_array ['post_password'] = $poststatus;
							}
						} else {
							$data_array['post_status'] = 'publish';
						}
					}
					if ($data_array['post_status'] == 'sticky') {
						$data_array['post_status'] = 'publish';
						$sticky = true;
					}

			}
			// Author name/id update
			if(isset($data_array ['post_author'])){
				$authorLen = strlen($data_array ['post_author']);
				$postuserid = $data_array ['post_author'];
				$checkpostuserid = intval($data_array ['post_author']);
				$postAuthorLen = strlen($checkpostuserid);
				$postauthor = array();

				if ($authorLen == $postAuthorLen) {
					$postauthor = $wpdb->get_results("select ID from $wpdb->users where ID = \"{$postuserid}\"");
				} else {
					$postauthor = $wpdb->get_results("select ID from $wpdb->users where user_login = \"{$postuserid}\"");
				}

				if (empty($postauthor) || !$postauthor[0]->ID) {
					$data_array ['post_author'] = 1;
					$this->noPostAuthCount++;
				} else {
					$data_array ['post_author'] = $postauthor [0]->ID;
				}
			}
			else{
				$data_array ['post_author'] = 1;
				$this->noPostAuthCount++;
			}
		
			// Date format post
			if (!isset($data_array ['post_date'])){
				$data_array ['post_date'] = date('Y-m-d H:i:s');
			}else{
				$data_array ['post_date'] = date('Y-m-d H:i:s', strtotime($data_array ['post_date']));
			}
			if(isset($data_array ['post_slug'])){
				$data_array ['post_name'] = $data_array ['post_slug'];
			}

			//add global password
			if($data_array){
				if($ret_array['importallwithps'] == 3){
					$data_array['post_password'] = $ret_array['globalpassword_txt'];
					
				}
			}
	//	print('<pre>');print_r($data_array);die('it exist');
			if (isset($data_array))
                            {
				$post_id = wp_insert_post($data_array);
                            }
			unset($postauthor);
			if ($post_id) {
				$uploaded_file_name=$session_arr['uploadedFile'];
                                $real_file_name = $session_arr['uploaded_csv_name'];
				$action = $data_array['post_type'];
				$created_records[$action][] = $post_id;
				if($action == 'post'){
					$imported_as = 'Post';
				        $this->insPostCount++;
				}
				if($action == 'page'){
					$imported_as = 'Page';
			           	$this->insPageCount++;
				}
				if($action != 'post' && $action != 'page'){
					$imported_as = 'Custom Post';
				        $this->insCPTCount++;
				}
				$keyword = $action;
				if (isset($sticky) && $sticky)
					stick_post($post_id);

				if (!empty ($custom_array)) {
					foreach ($custom_array as $custom_key => $custom_value) {
						add_post_meta($post_id, $custom_key, $custom_value);
					}
				}

				
				// Create custom taxonomy to post
				if (!empty ($smack_taxo)) {
					foreach ($smack_taxo as $taxo_key => $taxo_value) {
						if (!empty($taxo_value)) {
							$split_line = explode('|', $taxo_value);
							wp_set_object_terms($post_id, $split_line, $taxo_key);
						}
					}
				}

				// Create/Add tags to post
				if (!empty ($tags)) {
					foreach ($tags as $tag_key => $tag_value) {
						wp_set_post_tags($post_id, $tag_value);
					}
				}

				// Create/Add category to post
				if (!empty ($categories)) {
					$split_cate = explode('|', $categories ['post_category']);
					foreach ($split_cate as $key => $val) 
                                         {
					if (is_numeric($val))
					$split_cate[$key] = 'uncategorized';
					}
					wp_set_object_terms($post_id, $split_cate, 'category');
				}
				// Add featured image
				if (!empty ($file)) {
					$wp_filetype = wp_check_filetype(basename($attachmentName), null);
					$wp_upload_dir = wp_upload_dir();
					$attachment = array(
							'guid' => $wp_upload_dir['url'] . '/' . basename($attachmentName),
							'post_mime_type' => $wp_filetype['type'],
							'post_title' => preg_replace('/\.[^.]+$/', '', basename($attachmentName)),
							'post_content' => '',
							'post_status' => 'inherit'
							);
					if($get_media_settings == 1){
						$generate_attachment = $dirname . '/' . $attachmentName;
					}else{
						$generate_attachment = $attachmentName;
					}
					$uploadedImage = $wp_upload_dir['path'] . '/' . $attachmentName;
					$attach_id = wp_insert_attachment($attachment, $generate_attachment, $post_id);
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					$attach_data = wp_generate_attachment_metadata($attach_id, $uploadedImage);
					wp_update_attachment_metadata($attach_id, $attach_data);
					set_post_thumbnail($post_id, $attach_id);
				}
			}
			else{
				$skippedRecords[] = $_SESSION['SMACK_SKIPPED_RECORDS'];
			}
		}
				unset($data_array);
	}
        
        /**
         * Delete uploaded file after import process
         */
        function deletefileafterprocesscomplete($uploadDir) {
                //array_map('unlink', glob("$uploadDir/*"));
		$files = array_diff(scandir($uploadDir), array('.','..')); 
		foreach ($files as $file) { 
			(is_dir("$uploadDir/$file")) ? rmdir("$uploadDir/$file") : unlink("$uploadDir/$file"); 
		} 
        }

        // Function convert string to hash_key
        public function convert_string2hash_key($value) {
                $file_name = hash_hmac('md5', "$value", 'secret');
                return $file_name;
        }

	// Function for common footer
	public function common_footer_for_other_plugin_promotions(){
		$content = '<div class="accordion-inner">
			<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/" target="_blank">Social All in One Bot</a></label>
			<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/google-seo-author-snippet-plugin/" target="_blank">Google SEO Author Snippet</a></label>
			<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/" target="_blank">WP Advanced Importer</a></label>
			<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/wp-vtiger/" target="_blank">WP Tiger</a></label>
			<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/" target="_blank">WP Sugar</a></label>
			<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/" target="_blank">WP Zoho crm Sync</a></label>
			<label class="plugintags"><a href="http://blog.smackcoders.com/category/free-wordpress-plugins/" target="_blank">CRM Ecommerce Integration</a></label>

			<label class="plugintags"><a href="http://www.smackcoders.com/wp-ultimate-csv-importer-pro.html" target="_blank">WP Ultimate CSV Importer Pro</a></label>
			<label class="plugintags"><a href="http://www.smackcoders.com/pro-wordpress-vtiger-webforms-module.html" target="_blank">WP Tiger Pro</a></label>
			<label class="plugintags"><a href="http://www.smackcoders.com/wordpress-sugar-integration-automated-multi-web-forms-generator-pro.html" target="_blank">WordPress Sugar Pro</a></label>
			<label class="plugintags"><a href="http://www.smackcoders.com/vtigercrm6-magento-connector.html" target="_blank">VTiger 6 Magento Sync</a></label>
			<label class="plugintags"><a href="http://www.smackcoders.com/vtigercrm-mailchimp-integration.html" target="_blank">VTiger 6 Mailchimp</a></label>
			<label class="plugintags"><a href="http://www.smackcoders.com/vtiger-quickbooks-integration-module.html" target="_blank">Vtiger QuickBooks</a></label>
			<label class="plugintags"><a href="http://www.smackcoders.com/xero-vtiger-integration.html" target="_blank">Vtiger Xero Sync</a></label>
			<label class="plugintags"><a href="http://www.smackcoders.com/vtiger-crm-hrm-payroll-modules.html" target="_blank">Vtiger HR and Payroll</a></label>
                        <label class="plugintags"><a href="http://www.smackcoders.com/hr-payroll.html" target="_blank">HR Payroll</a></label>
			<div style="position:relative;float:right;"><a href="http://www.smackcoders.com/"><img width=80 src="http://www.smackcoders.com/skin/frontend/default/megashop/images/logo.png" /></a></div>
			</div>';
		echo $content;
	}

	// Function for social sharing
	public function importer_social_profile_share() {
		$urlCurrentPage = "http://www.smackcoders.com/wp-ultimate-csv-importer.html";
		$fbimgsrc = WP_CONTENT_URL . "/plugins/" . WP_CONST_ADVANCED_XML_IMP_SLUG . "/images/facebook.png";
		$googleimgsrc = WP_CONTENT_URL . "/plugins/" . WP_CONST_ADVANCED_XML_IMP_SLUG . "/images/googleplus.png";
		$linkedimgsrc = WP_CONTENT_URL . "/plugins/" . WP_CONST_ADVANCED_XML_IMP_SLUG . "/images/linkedin.png";
		$twitimgsrc = WP_CONTENT_URL . "/plugins/" . WP_CONST_ADVANCED_XML_IMP_SLUG . "/images/twitter.png";
		$strPageTitle = 'WP Ultimate CSV Importer';
		$linked_in_username = 'smackcoders';

		//Facebook
		$htmlShareButtons = '<span class="sociallink">';
		$htmlShareButtons .= '<a id="wpcsv_facebook_share" href="http://www.facebook.com/sharer.php?u=' . $urlCurrentPage  . '" target="_blank">';
		$htmlShareButtons .= '<img title="Facebook" class="wpcsv" src="' . $fbimgsrc . '" alt="Facebook" />';
		$htmlShareButtons .= '</a>';
		$htmlShareButtons .= '</span>';

		//Google Plus
		$htmlShareButtons .= '<span class="sociallink">';
		$htmlShareButtons .= '<a id="wpcsv_google_share" href="https://plus.google.com/share?url=' . $urlCurrentPage  . '" target="_blank" >';
		$htmlShareButtons .= '<img title="Google+" class="wpcsv" src="' . $googleimgsrc . '" alt="Google+" />';
		$htmlShareButtons .= '</a>';
		$htmlShareButtons .= '</span>';

		//Linked in
		$htmlShareButtons .= '<span class="sociallink">';
		$htmlShareButtons .= '<a id="wpcsv_linkedin_share" class="wpcsv_share_link" href="http://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($urlCurrentPage)  . '&title='.urlencode($strPageTitle).'&source='.$linked_in_username.'" target="_blank" >';
		$htmlShareButtons .= '<img title="LinkedIn" class="wpcsv" src="' . $linkedimgsrc . '" alt="LinkedIn" />';
		$htmlShareButtons .= '</a>';
		$htmlShareButtons .= '</span>';

		//Twitter
		$username = "smackcoders";
		// format the URL into friendly code
		$twitterShareText = urlencode(html_entity_decode($strPageTitle . ' ', ENT_COMPAT, 'UTF-8'));
		// twitter share link
		$htmlShareButtons .= '<span class="sociallink">';
		$htmlShareButtons .= '<a id="wpcsv_twitter_share" href="http://twitter.com/share?url=' . $urlCurrentPage .'&via='.$username.'&related='.$username.'&text=' . $twitterShareText . '" target="_blank">';
		$htmlShareButtons .= '<img title="Twitter" class="wpcsv" src="' . $twitimgsrc . '" alt="Twitter" />';
		$htmlShareButtons .= '</a>';
		$htmlShareButtons .= '</span>';
		echo $htmlShareButtons;
	}

}
class CallWPAdvImporterObj extends WPAdvImporter_includes_helper
{
	private static $_instance = null;
	public static function getInstance()
	{
		if( !is_object(self::$_instance) )  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
			self::$_instance = new WPAdvImporter_includes_helper();
		return self::$_instance;
	}
}// CallSkinnyObj Class Ends
?>
