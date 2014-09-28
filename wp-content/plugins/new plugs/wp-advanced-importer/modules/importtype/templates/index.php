<?php
/* Module : Post
   Author : Fredrick
   Owner  : smackcoders.com
   Date	  : Feb11,2014
 */ 
$impCE = new WPAdvImporter_includes_helper(); 
?>
        <div style="width:100%;">
	<div id="accordion">
	<table class="table-importer">
	<tr>
	<td>
	<h3>Import XML  File</h3>
	<div id='sec-one' <?php if(isset($_REQUEST['step']) && $_REQUEST['step'] != 'uploadfile') {?> style='display:none' <?php } ?>>
	<?php if(is_dir($impCE->getUploadDirectory('default'))){ ?>
		<input type='hidden' id='is_uploadfound' name='is_uploadfound' value='found' />
	<?php } else { ?>
		<input type='hidden' id='is_uploadfound' name='is_uploadfound' value='notfound' />
        <div class="warning" id="warning" name="warning" style="display:none;margin: 4% 0 4% 10%;"></div>
	<?php } ?>
	<form action='<?php echo admin_url().'admin.php?page='.WP_CONST_ADVANCED_XML_IMP_SLUG.'/index.php&__module='.$_REQUEST['__module'].'&step=mapping_settings'?>' id='browsefile' method='post' name='browsefile' onsubmit="return import_csv();">
	<div class="importfile" align='center'>
	<div id='filenamedisplay'></div><!--<form class="add:the-list: validate" style="clear:both;" method="post" enctype="multipart/form-data" onsubmit="return file_exist();">-->
	<div class="container">
	<!-- The fileinput-button span is used to style the file input field as button -->
	<span class="btn btn-success fileinput-button">
	<span>Browse</span>
	<!-- The file input field used as target for the file upload widget -->
	<input type ='hidden' id="pluginurl"value="<?php echo  WP_CONTENT_URL ;?>">
	<?php $uploadDir = wp_upload_dir(); ?>
	<input type="hidden" id="uploaddir" value="<?php echo isset($uploadDir['basedir']) ? $uploadDir['basedir'] : ''; ?>">
	<input type="hidden" id="uploadFileName" name="uploadfilename" value="">
	<input type = 'hidden' id = 'uploadedfilename' name = 'uploadedfilename' value = ''>
        <input type = 'hidden' id = 'upload_csv_realname' name = 'upload_csv_realname' value =''>
        <input type = 'hidden' id = 'current_file_version' name = 'current_file_version' value = ''>
        <input type = 'hidden' id = 'current_module' name = 'current_module' value = '<?php echo isset($_REQUEST['__module']) ? $_REQUEST['__module'] : ''; ?>' >
         <input type ='hidden' id = 'changetype' name = 'changetype' value =''>
	<input id="fileupload" type="file" name="files[]" multiple>
	</span>
	<!-- The global progress bar -->
	<div id="progress" class="progress">
	<div class="progress-bar progress-bar-success"></div>
	</div>
        <div class="form-group">
        <ul class = "type">
        <li> <label> <input type='checkbox' name='type[]' value='post' id='typepost' onclick='selecttype(this.id)'>Post</label> </li>
        <li> <label> <input type='checkbox' name='type[]' value='page' id='typepage' onclick='selecttype(this.id)'>Page</label> </li>
        <li> <label><input type='checkbox' name='type[]' value='custompost' id='typecustom' onclick='selecttype(this.id)'>Custompost</label>  </li>      
        </ul>
        </div>
	<div class = "form-group" style="margin-top: 27px;margin-left:440px;">
	<input type = 'button' name='clearform' id='clearform' value='Clear' onclick="Reload();" class = 'btn btn-warning' /> 
        <input type = 'submit' name='importfile' id='importtype' value='Next>>' onclick = "choosetype(this.form);" class = 'btn btn-primary' disabled/>

	</div>
	<div class="warning" id="warning" name="warning" style="display:none"></div>
	<!-- The container for the uploaded files -->
	<div id="files" class="files"></div>
	<div id="defaultpanel" align='center' style='width:100%;'> <p class='msgborder' style='color:green;'> You can also drag and drop files here</div>
	<!--<div class="panel panel-default" id="defaultpanel">
	<h4 class="panel-title">Notes</h4>
	<ul>
	<li>1. The maximum file size for uploads is unlimited.</li>
	<li>2. You can drag and drop files from your desktop on this webpage.</li>
	</ul>
	</div>-->
	</div>
	<script language="javascript" type="text/javascript">
	var check_upload_dir = document.getElementById('is_uploadfound').value;  
	if(check_upload_dir == 'notfound'){
		$('#defaultpanel').css('visibility','hidden');
		$('<p/>').text("").appendTo('#warning');
		$( "#warning" ).empty();
		$('#warning').css('display','inline');
		$('<p/>').text("Warning:   Sorry. There is no uploads directory Please create it with write permission.").appendTo('#warning');
		$('#warning').css('color','red');
		$('#warning').css('font-weight','bold');
		$('#progress .progress-bar').css('visibility','hidden');
	}
	else{
	$(function () {
		'use strict';
		var uploadPath = document.getElementById('uploaddir').value;
		var url = (document.getElementById('pluginurl').value+'/plugins/<?php echo WP_CONST_ADVANCED_XML_IMP_SLUG;?>/templates/uploader.php')+'?uploadPath='+uploadPath+'&curr_action=<?php echo isset($_REQUEST['__module']) ? $_REQUEST['__module']  : ''; ?>';
		$('#fileupload').fileupload({
		url: url,
		dataType: 'json',
		done: function (e, data) {
		$.each(data.result.files, function (index, file) {
		document.getElementById('uploadFileName').value=file.name;
                var filewithmodule = file.uploadedname.split(".");
                var check_file = filewithmodule[filewithmodule.length - 1];

               if(check_file != "xml") { alert("Unsupported File Format"); }
                file.uploadedname = filewithmodule[0]+"-<?php echo isset($_REQUEST['__module']) ? $_REQUEST['__module'] : ''; ?>"+".xml";
                document.getElementById('upload_csv_realname').value = file.uploadedname; 
                var get_version1 = file.name.split("-<?php echo isset($_REQUEST['__module']) ? $_REQUEST['__module'] : ''; ?>"); 
                var get_version2 = get_version1[1].split(".xml");
                var get_version3 = get_version2[0].split("-");
                document.getElementById('current_file_version').value = get_version3[1];
		$('#uploadedfilename').val(file.uploadedname);
		$( "#filenamedisplay" ).empty();
		if(file.size>1024 && file.size<(1024*1024))
		{
			var fileSize =(file.size/1024).toFixed(2)+' kb';
		}
		else if(file.size>(1024*1024))
		{
			var fileSize =(file.size/(1024*1024)).toFixed(2)+' mb';
		}
		else
		{
			var fileSize= (file.size)+' byte';
		}
		$('<p/>').text((file.name)+' - '+fileSize).appendTo('#filenamedisplay');
		$('#importfile').attr('disabled', false);
		});
		},progressall: function (e, data) {
		var progress = parseInt(data.loaded / data.total * 100, 10);
		$('#progress .progress-bar').css('width', progress + '%' );
		}
		}).prop('disabled', !$.support.fileInput)
		.parent().addClass($.support.fileInput ? undefined : 'disabled');
		});
	}
	</script>
	<input type = 'hidden' name = 'importid' id = 'importid' >
		</form>
	</div>
	</div>
	</td>
	</tr>
	<tr>
	<td>
  <!-- Here Mapping Configuration for POST starts  -->
       <div id="sec-two-post" >
     <?
       require_once(WP_CONST_ADVANCED_XML_IMP_DIRECTORY.'includes/WXR_Handler.php');
       $handlerObj = new WXR_Handler();
       $next = $handlerObj->postHandler($_POST);
       if(isset($next)) {  ?>     
<form name='mappingConfig' action="<?php echo admin_url(); ?>admin.php?page=<?php echo WP_CONST_ADVANCED_XML_IMP_SLUG;?>/index.php&__module=<?php echo $_REQUEST['__module']?>&step=<?php echo$next ?>"  method="post" onsubmit="return import_csv();">
        <div class='msg' id = 'showMsg' style = 'display:none'></div>
	<?php $_SESSION['SMACK_MAPPING_SETTINGS_VALUES'] = $_POST; 
        if(isset($_POST['type']) && $_POST['type'] == '') {
                $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['type'] = $_POST['type']; } ?>
        <div id='sec-two' <?php if(isset($_REQUEST['step']) && $_REQUEST['step'] == 'mapping_settings'   ){ ?> style='display:block' <?php } else {?> style = 'display:none'<?php } ?> >
        <h3 > Post Mapping Configuration </h3>
	<div class='mappingsection'>
	<h2><div class="secondformheader"> Import Data Configuration </div></h2>
	<?php if(isset($_REQUEST['__module']) && $_REQUEST['__module'] == 'custompost'){ ?>
		<div class='importstatus' style='display:block'>
			<input type="hidden" id="customposts" name="customposts" value="">
			<div style = 'float:left'> <label> Select Post Type </label> <span class="mandatory"> * </span> </div>
			<div style = 'float:left'> 
			<select name='custompostlist' id='custompostlist'>
			<option value='select'>---Select---</option>
			<?php
                        $customlist = get_post_types();
                        foreach ($customlist as $key => $value) {
                              if(isset($value))
                               {
				if (($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item') && ($value != 'post') && ($value != 'page') && ($value != 'wp-types-group') && ($value != 'wp-types-user-group')) {?>
					<option id="<?php echo isset($value) ? $value : ''; ?>"> <?php echo isset($value) ? $value : '';?> </option>
						<?php }
		   }	} ?>
			</select>
			</div>
			<div style = 'float:left'> 
			<a href="#" class="tooltip">
			<img src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/help.png" />
			<span class="tooltipCustompost">
			<img class="callout" src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/callout.gif" />
			<strong>Select your custompost type</strong>
			<img src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/help.png" style="margin-top: 6px;float:right;" />
			</span>
			</a>
			</div>
			</div>
			<?php } ?>
			<?php echo $impCE->getImportDataConfiguration(); ?>
			</div>
			<div id='mappingheader' class='mappingheader' >
			<?php  
	   $allcustomposts=''; $mFieldsArr=''; $delimeter=''; $type= ''; $mappingFields_arr =array(); $filename='';$module = 'post';
			if(isset($_POST['uploadfilename']) && $_POST['uploadfilename'] != ''){
				$file_name = $_POST['uploadfilename'];
				$filename = $impCE->convert_string2hash_key($file_name);
			}
                        if(isset($_POST['type']) && $_POST['type'] == '') {
                               $type=$_POST['type'];
                               }
                        
                        if(isset($_POST['upload_csv_realname']) && $_POST['upload_csv_realname'] != '') {
                                $uploaded_csv_name = $_POST['upload_csv_realname'];
                        }
                        $getrecords=array();
			$getrecords = $impCE->xml_file_data($filename,'xml',$module); 
			$getcustomposts=get_post_types();
			foreach($getcustomposts as $keys => $value)
			{
				if(($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item') && ($value != 'post') && ($value != 'page') && ($value != 'wp-types-group') && ($value != 'wp-types-user-group')) {
					$allcustomposts.=$value.',';
				}
			}
			?>
		        <table style="font-size: 12px;" class = "table table-striped"> 
			<tr>
		<!--	<td colspan='4'> -->
			<div align='center' style='float:right;'>
			<?php $cnt = count($impCE->defCols) + 2;
			$cnt1 = count($impCE->headers);
                        $imploaded_array=array();
			$imploded_array = implode(',', $impCE->headers); ?>
			<input type = 'hidden' id = 'imploded_header' name = 'imploded_array' value = '<?php echo is_array($imploded_array) ? $imploaded_array : ''; ?>'>
			<input type="hidden" id="h1" name="h1" value="<?php echo isset($cnt) ? $cnt : ''; ?>"/>
			<input type="hidden" id="h2" name="h2" value="<?php echo isset($cnt1) ? $cnt1 : ''; ?>"/>
			<input type='hidden' name='selectedImporter' id='selectedImporter' value="<?php echo isset($_REQUEST['__module']) ? $_REQUEST['__module'] : ''; ?>">
			<input type="hidden" id="prevoptionindex" name="prevoptionindex" value=""/>
			<input type="hidden" id="prevoptionvalue" name="prevoptionvalue" value=""/>
			<input type='hidden' id='current_record' name='current_record' value='0' />
			<input type='hidden' id='totRecords' name='totRecords' value='<?php echo count($getrecords); ?>' />
			<input type='hidden' id='tmpLoc' name='tmpLoc' value='<?php echo WP_CONST_ADVANCED_XML_IMP_DIR ; ?>' />
			<input type='hidden' id='uploadedFile' name='uploadedFile' value="<?php echo  isset($filename) ? $filename : ''; ?>" />
                        <!-- real uploaded filename -->
                        <input type='hidden' id='uploaded_csv_name' name='uploaded_csv_name' value="<?php echo isset($uploaded_csv_name) ? $uploaded_csv_name : ''; ?>" />
			<input type='hidden' id='select_delimeter' name='select_delimeter' value="<?php echo  isset($delimeter) ? $delimeter : ''; ?>" />
			<input type='hidden' id='check_post' name ='check_post' value="<? echo isset($post) ? $post : '' ; ?> " />
                         <input type='hidden' id='check_page' name='check_page' value="<? echo isset($page) ? $page : '' ; ?> "/>
                        <input type='hidden' id='check_custom' name='check_custom' value="<? echo isset($custompost) ? $custompost : '' ; ?>"/>
                        <input type='hidden' id='stepstatus' name='stepstatus' value='<?php echo isset($_REQUEST['step']) ? $_REQUEST['step'] : ''; ?>' />
	<!--		<input type='hidden' id='mappingArr' name='mappingArr' value='' />
			<input type='button' id='prev_record' name='prev_record' class="btn btn-primary" value='<<' onclick='gotoelement(this.id);' />
			<label style="padding-right:10px;">Change the csv sample record value by rows</label>
			<input type='button' id='next_record' name='next_record' class="btn btn-primary" value='>>' onclick='gotoelement(this.id);' />
			Go To: <input type='text' id='goto_element' name='goto_element' />
			<input type='button' id='apply_element' name='apply_element' class="btn btn-success" value='Get Record' onclick='gotoelement(this.id);' />  -->
			</div>
	<!--		</td> -->
			</tr> 
			<?php
			$count = 0;
			if (isset($_REQUEST['__module']) && $_REQUEST['__module'] == 'page') {
				unset($impCE->defCols['post_category']);
				unset($impCE->defCols['post_tag']);
			}
			?>
			<tr><td class="left_align"> <b>XML HEADER</b> </td><td> <b>WP FIELDS</b> </td><td></td></tr>
			<?php
			foreach ($impCE->headers as $key => $value) 
			{ ?>
				<tr>
                                 
					<td class="left_align"> <label> <?php print($value);?> </label> </td>
					<td> <select name="mapping<?php print($count); ?>" id="mapping<?php print($count); ?>" class="uiButton" onchange="addcustomfield(this.value,<?php echo $count; ?>);">
					<option id = "select"> -- Select -- </option>
					<?php
					foreach ($impCE->defCols as $key1 => $value1) 
					{
                                                if (isset($key1) && $key1  == 'post_name')
							$key1 = 'post_slug';
						if (isset($value) && $value == 'post_name')
							$value = 'post_slug';?> 
			                      <option value = "<?php print($key1); ?>"  <?php if($key1 == $value){ ?>selected<?php }?>>  <?php
                                                if ($key1 != 'post_name') {
							print ($key1);
							$mappingFields_arr[$key1] = $key1;
						}
						else {
							print 'post_slug';
							$mappingFields_arr['post_slug'] = 'post_slug';
						} ?>
						</option><?php
					}

					foreach (get_taxonomies() as $taxokey => $taxovalue)  {
						if ($taxokey != 'category' && $taxokey != 'link_category' && $taxokey != 'post_tag' && $taxokey != 'nav_menu' && $taxokey != 'post_format') { ?>
					<option value="<?php print($taxokey); ?>"> <?php print($taxovalue);?> </option>
					<?php $mappingFields_arr[$taxovalue] = $taxovalue;
						}
					}
					?>
					<option value="add_custom<?php print($count); ?>">Add Custom Field</option>
					</select> 
					</td>
					<!--<td>
					<?php
                                        //echo '<pre>'; print_r($getrecords); die('comeing'); 
					//$getrecords[0][$key] = htmlspecialchars($getrecords[0][$key], ENT_COMPAT, "UTF-8");
					/*if(strlen($getrecords[0][$key])>32)
					{
						$getrecords[0][$key] = substr($getrecords[0][$key], 0, 28).'...';
					}*/ ?>
					<span id='elementVal_<?php //echo isset($key) ? $key : ''; ?>' > <?php //echo ($getrecords[0][$key]); ?> </span>
					</td>
					<td width = "180px;">
					<input class="customfieldtext" type="text" id="textbox<?php //print($count); ?>" name="textbox<?php //print($count); ?>" TITLE="Replace the default value" style="display: none;float:left;width:160px;" value="<?php //echo isset($value) ? $value : ''  ?>"/>
					<span style="display: none;float:left" id="customspan<?php echo $count ?>">
					<a href="#" class="tooltip">
					<img src="../wp-content/plugins/<?php echo WP_CONST_ADVANCED_XML_IMP_SLUG;?>/images/help.png" />
					<span class="tooltipPostStatus">
					<img class="callout" src="../wp-content/plugins/<?php echo WP_CONST_ADVANCED_XML_IMP_SLUG;?>/images/callout.gif" />
					<strong>Give a name for your new custom field</strong>
					<img src="../wp-content/plugins/<?php echo WP_CONST_ADVANCED_XML_IMP_SLUG;?>/images/help.png" style="margin-top: 6px;float:right;" />
					</span>
					</a> 
					</span>
					<span style="display: none; color: red; margin-left: 5px;" id="customspan<?php echo $count ?>">Replace the custom value</span>
					</td>-->
					</tr>
					<?php
					$count++;
			}
                 
			foreach($mappingFields_arr as $mkey => $mval){
				$mFieldsArr .= $mkey.',';
			}
			$mFieldsArr = substr($mFieldsArr, 0, -1);
			?>
		</table>
		<input type="hidden" id="mapping_fields_array" name="mapping_fields_array" value="<?php print_r($mFieldsArr); ?>"/>
		<div>
			<div class="goto_import_options" align=center>
		<div class="mappingactions" >
		<input type='button' id='clear_mapping' class='clear_mapping btn btn-warning' name='clear_mapping' value='Clear Mapping' onclick='clearMapping();' style = 'float:left'/>
		<span >
		<a href="#" class="tooltip tooltip_smack"  >
		<img src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/help.png" />
		<span class="tooltipClearMapping">
		<img class="callout" src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/callout.gif" />
		<strong>Refresh to re-map fields</strong>
		<img src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/help.png" style="margin-top: 6px;float:right;" />
		</span>
		</a>
		</span>
		</div>
		<div class="mappingactions" >
		<input type='submit' id='goto_importer_setting' class='goto_importer_setting btn btn-info' name='goto_importer_setting' value='Next >>' /> 
	                 	</div>
	             	    </div> 
		         </div>
		      </div>
		  </div>
	       </form>
	     </td>
	   </tr>
	 <tr>
       <td>
     <?php } ?>
      </div>
 
  <!-- Here ends -->

  <!-- Here Mappoing configuration starts for Page -->
     <div id="sec-two-page" >
     <?   if ((isset($_POST['type'][0]) && $_POST['type'][0] == 'page')  || (isset($_REQUEST['step']) && $_REQUEST['step'] == 'pageoption')) {
       require_once(WP_CONST_ADVANCED_XML_IMP_DIRECTORY.'includes/WXR_Handler.php');
       $handlerObj = new WXR_Handler();
       $next = $handlerObj->pageHandler($_POST); ?> 
      <form name='mappingConfig' action="<?php echo admin_url(); ?>admin.php?page=<?php echo WP_CONST_ADVANCED_XML_IMP_SLUG;?>/index.php&__module=<?php echo $_REQUEST['__module']?>&step=<?echo$next ?>"  method="post" onsubmit="return import_csv();" >
	<div class='msg' id = 'showMsg' style = 'display:none'></div>
	<?php $_SESSION['SMACK_MAPPING_SETTINGS_VALUES'] = $_POST; 
        if(isset($_POST['type']) && $_POST['type'] == '') {
                $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['type'] = $_POST['type'];
           } ?>
        <div id='sec-two' <?php if(isset($_REQUEST['step']) && $_REQUEST['step'] == 'mapping_settings' || isset($_REQUEST['step']) && $_REQUEST['step'] == 'pageoption'){ ?> style='display:block' <?php } else { ?> style = 'display:none' <?php } ?> >
        <h3> Page Mapping Configuration </h3>    
      	<div class='mappingsection'>
	<h2><div class="secondformheader">Import Data Configuration</div></h2>
	<?php if(isset($_REQUEST['__module']) && $_REQUEST['__module'] == 'custompost'){ ?>
		<div class='importstatus' style='display:block'>
			<input type="hidden" id="customposts" name="customposts" value="">
			<div style = 'float:left'> <label> Select Post Type </label> <span class="mandatory"> * </span> </div>
			<div style = 'float:left'> 
			<select name='custompostlist' id='custompostlist'>
			<option value='select'>---Select---</option>
			<?php
                        $customlist = get_post_types();
                        foreach ($customlist as $key => $value) {
                              if(isset($value))
                               {
				if (($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item') && ($value != 'post') && ($value != 'page') && ($value != 'wp-types-group') && ($value != 'wp-types-user-group')) {?>
					<option id="<?php echo isset($value) ? $value : ''; ?>"> <?php echo isset($value) ? $value : '';?> </option>
						<?php }
		   }	} ?>
			</select>
			</div>
			<div style = 'float:left'> 
			<a href="#" class="tooltip">
			<img src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/help.png" />
			<span class="tooltipCustompost">
			<img class="callout" src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/callout.gif" />
			<strong>Select your custompost type</strong>
		<img src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/help.png" style="margin-top: 6px;float:right;" />
			</span>
			</a>
			</div>
			</div>
			<?php } ?>
			<?php echo $impCE->getImportDataConfiguration(); ?>
			</div>
			<div id='mappingheader' class='mappingheader' >
			<?php  
			$allcustomposts='';$mFieldsArr=''; $type= '';$module = 'page'; $mappingFields_arr =array(); $filename='';
                        $checktype=get_option('wp_advanced_importer');
                        if(isset($_POST))
                          {
			if(isset($_POST['uploadfilename']) && $_POST['uploadfilename'] != ''){
				$file_name = $_POST['uploadfilename'];
				$filename = $impCE->convert_string2hash_key($file_name);
			}
                        if(isset($_POST['type']) && $_POST['type'] == '') {
                               $type=$_POST['type'];
                               }
                        if(isset($_POST['upload_csv_realname']) && $_POST['upload_csv_realname'] != '') {
                                $uploaded_csv_name = $_POST['upload_csv_realname'];
                        }
                     }
                     
       // if  data comes from either page or post 
                     if(isset($checktype['wp_advanced_importer']['common']))
                        {
                          if(isset($checktype['wp_advanced_importer']['common']['uploadfilename']) && $checktype['wp_advanced_importer']['common']['uploadfilename'] != '')
                         {
                          $file_name=$checktype['wp_advanced_importer']['common']['uploadfilename'];
                          $filename = $impCE->convert_string2hash_key($file_name);
                         }
                      
                          if(isset($checktype['wp_advanced_importer']['common']['upload_csv_realname']) && $checktype['wp_advanced_importer']['common']['upload_csv_realname'] != '')
                         {
                          $upload_csv_name=$checktype['wp_advanced_importer']['common']['upload_csv_realname'];
                         
                         }
                   }
                        $getrecords=array();
			$getrecords = $impCE->xml_file_data($filename,'xml',$module); 
			$getcustomposts=get_post_types();
			foreach($getcustomposts as $keys => $value)
			{
				if(($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item') && ($value != 'post') && ($value != 'page') && ($value != 'wp-types-group') && ($value != 'wp-types-user-group')) {
					$allcustomposts.=$value.',';
				}
			}
			?>
			<table style="font-size: 12px;" class = "table table-striped"> 
			<tr>
		   <!-- <td colspan='4'>  -->
			<div align='center' style='float:right;'>
			<?php $cnt = count($impCE->defCols) + 2;
			$cnt1 = count($impCE->headers);
                        $imploaded_array=array();
			$imploded_array = implode(',', $impCE->headers); ?>
			<input type = 'hidden' id = 'imploded_header' name = 'imploded_array' value = '<?php echo is_array($imploded_array) ? $imploaded_array : ''; ?>'>
			<input type="hidden" id="h1" name="h1" value="<?php echo isset($cnt) ? $cnt : ''; ?>"/>
			<input type="hidden" id="h2" name="h2" value="<?php echo isset($cnt1) ? $cnt1 : ''; ?>"/>
			<input type='hidden' name='selectedImporter' id='selectedImporter' value="<?php echo isset($_REQUEST['__module']) ? $_REQUEST['__module'] : ''; ?>"/>
			<input type="hidden" id="prevoptionindex" name="prevoptionindex" value=""/>
			<input type="hidden" id="prevoptionvalue" name="prevoptionvalue" value=""/>
			<input type='hidden' id='current_record' name='current_record' value='0' />
			<input type='hidden' id='totRecords' name='totRecords' value='<?php echo count($getrecords); ?>' />
			<input type='hidden' id='tmpLoc' name='tmpLoc' value='<?php echo WP_CONST_ADVANCED_XML_IMP_DIR ; ?>' />
			<input type='hidden' id='uploadedFile' name='uploadedFile' value="<?php echo  isset($filename) ? $filename : ''; ?>" />
                        <!-- real uploaded filename -->
                        <input type='hidden' id='uploaded_csv_name' name='uploaded_csv_name' value="<?php echo isset($uploaded_csv_name) ? $uploaded_csv_name : ''; ?>" />
			<input type='hidden' id='check_post' name ='check_post' value="<? echo isset($post) ? $post : '' ; ?> " />
                         <input type='hidden' id='check_page' name='check_page' value="<? echo isset($page) ? $page : '' ; ?> "/>
                        <input type='hidden' id='check_custom' name='check_custom' value="<? echo isset($custompost) ? $custompost : '' ; ?>"/>


                        <input type='hidden' id='stepstatus' name='stepstatus' value='<?php echo isset($_REQUEST['step']) ? $_REQUEST['step'] : ''; ?>' />
		<!--	<input type='hidden' id='mappingArr' name='mappingArr' value='' />
			<input type='button' id='prev_record' name='prev_record' class="btn btn-primary" value='<<' onclick='gotoelement(this.id);' />
			<label style="padding-right:10px;">Change the csv sample record value by rows</label>
			<input type='button' id='next_record' name='next_record' class="btn btn-primary" value='>>' onclick='gotoelement(this.id);' />
			Go To: <input type='text' id='goto_element' name='goto_element' />
			<input type='button' id='apply_element' name='apply_element' class="btn btn-success" value='Get Record' onclick='gotoelement(this.id);' />  -->
			</div>
		<!--	</td> -->
			</tr> 
			<?php
			$count = 0;
		                
				unset($impCE->defCols['post_category']);
				unset($impCE->defCols['post_tag']);
                                unset($impCE->headers['category']);
                                unset($impCE->headers['post_tag']);
			
			?>
			<tr><td class="left_align"> <b>XML HEADER</b> </td><td> <b>WP FIELDS</b> </td><td></td></tr>
			<?php
			foreach ($impCE->headers as $key => $value) 
			{ ?>
				<tr>
					<td class="left_align"> <label> <?php print($value);?> </label> </td>
					<td> <select name="mapping<?php print($count); ?>" id="mapping<?php print($count); ?>" class="uiButton" onchange="addcustomfield(this.value,<?php echo $count; ?>);">
					<option id = "select"> -- Select -- </option>
					<?php
					foreach ($impCE->defCols as $key1 => $value1) 
					{
						if (isset($key1) && $key1  == 'post_name')
							$key1 = 'post_slug';
						if (isset($value) && $value == 'post_name')
							$value = 'post_slug';?> 
			                      <option value = "<?php print($key1); ?>" <?php if($key1 == $value){ ?>selected<?php }?>>  <?php
                                                if ($key1 != 'post_name') {
							print ($key1);
							$mappingFields_arr[$key1] = $key1;
						}
						else {
							print 'post_slug';
							$mappingFields_arr['post_slug'] = 'post_slug';
						}
						?>
							</option>
							<?php
					}
                                        foreach (get_taxonomies() as $taxokey => $taxovalue) 
					{
						if ($taxokey != 'category' && $taxokey != 'link_category' && $taxokey != 'post_tag' && $taxokey != 'nav_menu' && $taxokey != 'post_format') 
						{ ?>
					<option value="<?php print($taxokey); ?>"> <?php print($taxovalue);?> </option>
								<?php $mappingFields_arr[$taxovalue] = $taxovalue;
						}
					}
					?>
					<option value="add_custom<?php print($count); ?>">Add Custom Field</option>
					</select> 
					</td>
					<!--<td>
					<?php
                                        //echo '<pre>'; print_r($getrecords); die('comeing'); 
					//$getrecords[0][$key] = htmlspecialchars($getrecords[0][$key], ENT_COMPAT, "UTF-8");
					/*if(strlen($getrecords[0][$key])>32)
					{
						$getrecords[0][$key] = substr($getrecords[0][$key], 0, 28).'...';
					}*/ ?>
					<span id='elementVal_<?php //echo isset($key) ? $key : ''; ?>' > <?php //echo ($getrecords[0][$key]); ?> </span>
					</td>
					<td width = "180px;">
					<input class="customfieldtext" type="text" id="textbox<?php //print($count); ?>" name="textbox<?php //print($count); ?>" TITLE="Replace the default value" style="display: none;float:left;width:160px;" value="<?php //echo isset($value) ? $value : ''  ?>"/>
					<span style="display: none;float:left" id="customspan<?php echo $count ?>">
					<a href="#" class="tooltip">
					<img src="../wp-content/plugins/<?php echo WP_CONST_ADVANCED_XML_IMP_SLUG;?>/images/help.png" />
					<span class="tooltipPostStatus">
					<img class="callout" src="../wp-content/plugins/<?php echo WP_CONST_ADVANCED_XML_IMP_SLUG;?>/images/callout.gif" />
					<strong>Give a name for your new custom field</strong>
					<img src="../wp-content/plugins/<?php echo WP_CONST_ADVANCED_XML_IMP_SLUG;?>/images/help.png" style="margin-top: 6px;float:right;" />
					</span>
					</a> 
					</span>
					<span style="display: none; color: red; margin-left: 5px;" id="customspan<?php echo $count ?>">Replace the custom value</span>
					</td>-->
					</tr>
					<?php
					$count++;
			}
			foreach($mappingFields_arr as $mkey => $mval){
				$mFieldsArr .= $mkey.',';
			}
			$mFieldsArr = substr($mFieldsArr, 0, -1);
			?>
		</table>
		<input type="hidden" id="mapping_fields_array" name="mapping_fields_array" value="<?php print_r($mFieldsArr); ?>"/>
		<div>
			<div class="goto_import_options" align=center>
		<div class="mappingactions" >
		<input type='button' id='clear_mapping' class='clear_mapping btn btn-warning' name='clear_mapping' value='Clear Mapping' onclick='clearMapping();' style = 'float:left'/>
		<span >
		<a href="#" class="tooltip tooltip_smack"  >
		<img src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/help.png" />
		<span class="tooltipClearMapping">
		<img class="callout" src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/callout.gif" />
		<strong>Refresh to re-map fields</strong>
		<img src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/help.png" style="margin-top: 6px;float:right;" />
		</span>
		</a>
			</span>
			</div>
			<div class="mappingactions" >
			<input type='submit' id='goto_importer_setting' class='goto_importer_setting btn btn-info' name='goto_importer_setting' value='Next >>' /> 
			</div>
			</div> 
			</div>
			</div>
			</div>
			</form>
			</td>
			</tr>
			<tr>
			<td>
		   <?php } ?>
		    </div>
	    
	  <!-- here ends -->
	  
	  <!-- Here Mapping configuartion for custom post -->
	    <div id='sec-two-custompost' >
	       <?
		       
		$mapping_section['wp_advance_importer']['common'] = array();  
		$checktype['wp_advance_importer']['common']['type'] = array();
		$c = array(); ?>
	     <?   if (isset ($_POST['type'][0] ) && $_POST['type'][0] == 'custompost'  || isset($_REQUEST['step'] ) && $_REQUEST['step'] == 'customoption') {
	       require_once(WP_CONST_ADVANCED_XML_IMP_DIRECTORY.'includes/WXR_Handler.php');
	       $handlerObj = new WXR_Handler();
	       $next = $handlerObj->custompostHandler($_POST);
		?> 
	      <form name='mappingConfig' action="<?php echo admin_url(); ?>admin.php?page=<?php echo WP_CONST_ADVANCED_XML_IMP_SLUG;?>/index.php&__module=<?php echo $_REQUEST['__module']?>&step=<? echo $next ?>"  method="post" onsubmit="return import_csv();" >
		<div class='msg' id = 'showMsg' style = 'display:none'></div>
		<?php $_SESSION['SMACK_MAPPING_SETTINGS_VALUES'] = $_POST; //print('<pre>'); print_r($_POST);die;
		if(isset($_POST['type']) && $_POST['type'] == '') {
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['type'] = $_POST['type'];
		   } ?>
		    
	      
		<div id='sec-two' <?php if(isset($_REQUEST['step']) && $_REQUEST['step'] == 'mapping_settings' || isset($_REQUEST['step']) && $_REQUEST['step'] == 'customoption'){ ?> style='display:block' <?php } else { ?> style = 'display:none' <?php } ?> >
	       <h3> Custom post Mapping Configuration </h3>
	<div class='mappingsection'>
	<h2><div class="secondformheader">Import Data Configuration</div></h2>
	<?php if(isset($_REQUEST['__module']) && $_REQUEST['__module'] == 'custompost'){ ?>
		<div class='importstatus' style='display:block'>
			<input type="hidden" id="customposts" name="customposts" value="">
			<div style = 'float:left'> <label> Select Post Type </label> <span class="mandatory"> * </span> </div>
			<div style = 'float:left'> 
			<select name='custompostlist' id='custompostlist'>
			<option value='select'>---Select---</option>
			<?php
                        $customlist = get_post_types();
                        foreach ($customlist as $key => $value) {
                              if(isset($value))
                               {
				if (($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item') && ($value != 'post') && ($value != 'page') && ($value != 'wp-types-group') && ($value != 'wp-types-user-group')) {?>
					<option id="<?php echo isset($value) ? $value : ''; ?>"> <?php echo isset($value) ? $value : '';?> </option>
						<?php }
		   }	} ?>
			</select>
			</div>
			<div style = 'float:left'> 
			<a href="#" class="tooltip">
			<img src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/help.png" />
			<span class="tooltipCustompost">
			<img class="callout" src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/callout.gif" />
			<strong>Select your custompost type</strong>
			<img src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/help.png" style="margin-top: 6px;float:right;" />
			</span>
			</a>
			</div>
			</div>
			<?php } ?>
			<?php echo $impCE->getImportDataConfiguration(); ?>
			</div>
			<div id='mappingheader' class='mappingheader' >
			<?php  
			$allcustomposts=''; $mFieldsArr=''; $delimeter=''; $type= ''; $mappingFields_arr =array(); $filename='';
                        $module = 'custompost';
       // if  data comes from either page or post 
                    $checktype = get_option('wp_advanced_importer');
                     if(isset($checktype['wp_advanced_importer']['common']))
                        {
                          if(isset($checktype['wp_advanced_importer']['common']['uploadfilename']) && $checktype['wp_advanced_importer']['common']['uploadfilename'] != '')
                         {
                          $file_name=$checktype['wp_advanced_importer']['common']['uploadfilename'];
                          $filename = $impCE->convert_string2hash_key($file_name);
                         }
                      
                          if(isset($checktype['wp_advanced_importer']['common']['upload_csv_realname']) && $checktype['wp_advanced_importer']['common']['upload_csv_realname'] != '')
                         {
                          $upload_csv_name=$checktype['wp_advanced_importer']['common']['upload_csv_realname'];
                         
                         }
                   }
                        $getrecords=array();
                        $getrecords = $impCE->xml_file_data($filename,'xml',$module); 
			$getcustomposts=get_post_types();
			foreach($getcustomposts as $keys => $value)
			{
		        if(($value != 'featured_image') && ($value != 'attachment') && ($value != 'wpsc-product') && ($value != 'wpsc-product-file') && ($value != 'revision') && ($value != 'nav_menu_item') && ($value != 'post') && ($value != 'page') && ($value != 'wp-types-group') && ($value != 'wp-types-user-group')) {
					$allcustomposts.=$value.',';
				}
			}
			?>
			<table style="font-size: 12px;" class = "table table-striped"> 
			<tr>
		<!--	<td colspan='4'> -->
			<div align='center' style='float:right;'>
			<?php $cnt = count($impCE->defCols) + 2;
			$cnt1 = count($impCE->headers);
                        $imploaded_array=array();
			$imploded_array = implode(',', $impCE->headers); ?>
			<input type = 'hidden' id = 'imploded_header' name = 'imploded_array' value = '<?php echo is_array($imploded_array) ? $imploaded_array : ''; ?>'>
			<input type="hidden" id="h1" name="h1" value="<?php echo isset($cnt) ? $cnt : ''; ?>"/>
			<input type="hidden" id="h2" name="h2" value="<?php echo isset($cnt1) ? $cnt1 : ''; ?>"/>
			<input type='hidden' name='selectedImporter' id='selectedImporter' value="<?php echo isset($_REQUEST['__module']) ? $_REQUEST['__module'] : ''; ?>"/>
			<input type="hidden" id="prevoptionindex" name="prevoptionindex" value=""/>
			<input type="hidden" id="prevoptionvalue" name="prevoptionvalue" value=""/>
			<input type='hidden' id='current_record' name='current_record' value='0' />
			<input type='hidden' id='totRecords' name='totRecords' value='<?php echo count($getrecords); ?>' />
			<input type='hidden' id='tmpLoc' name='tmpLoc' value='<?php echo WP_CONST_ADVANCED_XML_IMP_DIR ; ?>' />
			<input type='hidden' id='uploadedFile' name='uploadedFile' value="<?php echo  isset($filename) ? $filename : ''; ?>" />
                        <!-- real uploaded filename -->
                        <input type='hidden' id='uploaded_csv_name' name='uploaded_csv_name' value="<?php echo isset($uploaded_csv_name) ? $uploaded_csv_name : ''; ?>" />
			<input type='hidden' id='check_post' name ='check_post' value="<? echo isset($post) ? $post : '' ; ?> " />
                         <input type='hidden' id='check_page' name='check_page' value="<? echo isset($page) ? $page : '' ; ?> "/>
                        <input type='hidden' id='check_custom' name='check_custom' value="<? echo isset($custompost) ? $custompost : '' ; ?>"/>


                        <input type='hidden' id='stepstatus' name='stepstatus' value='<?php echo isset($_REQUEST['step']) ? $_REQUEST['step'] : ''; ?>' />
			<input type='hidden' id='mappingArr' name='mappingArr' value='' />
		<!--	<input type='button' id='prev_record' name='prev_record' class="btn btn-primary" value='<<' onclick='gotoelement(this.id);' />
			<label style="padding-right:10px;">Change the csv sample record value by rows</label>
			<input type='button' id='next_record' name='next_record' class="btn btn-primary" value='>>' onclick='gotoelement(this.id);' />
			Go To: <input type='text' id='goto_element' name='goto_element' />
			<input type='button' id='apply_element' name='apply_element' class="btn btn-success" value='Get Record' onclick='gotoelement(this.id);' />  -->
			</div>
		<!--	</td> -->
			</tr> 
			<?php
			$count = 0;
			if (isset($_REQUEST['__module']) && $_REQUEST['__module'] == 'page') {
				unset($impCE->defCols['post_category']);
				unset($impCE->defCols['post_tag']);
			}
			?>
			<tr><td class="left_align"> <b>XML HEADER</b> </td><td> <b>WP FIELDS</b> </td><td></td></tr>
			<?php
			foreach ($impCE->headers as $key => $value) 
			{ ?>
				<tr>
					<td class="left_align"> <label> <?php print($value);?> </label> </td>
					<td> <select name="mapping<?php print($count); ?>" id="mapping<?php print($count); ?>" class="uiButton" onchange="addcustomfield(this.value,<?php echo $count; ?>);">
					<option id = "select"> -- Select -- </option>
					<?php
					foreach ($impCE->defCols as $key1 => $value1) 
					{
						if (isset($key1) && $key1  == 'post_name')
							$key1 = 'post_slug';
						if (isset($value) && $value == 'post_name')
							$value = 'post_slug';?> 
			                      <option value = "<?php print($key1); ?>"<?php if($key1 == $value){ ?>selected<?php }?>>  <?php
                                                if ($key1 != 'post_name')
						{
							print ($key1);
							$mappingFields_arr[$key1] = $key1;
						}
						else
						{
							print 'post_slug';
							$mappingFields_arr['post_slug'] = 'post_slug';
						}
						?>
							</option>
							<?php
					}

					foreach (get_taxonomies() as $taxokey => $taxovalue) 
					{
						if ($taxokey != 'category' && $taxokey != 'link_category' && $taxokey != 'post_tag' && $taxokey != 'nav_menu' && $taxokey != 'post_format') 
						{ ?>
							<option value="<?php print($taxokey); ?>"> <?php print($taxovalue);?> </option>
								<?php $mappingFields_arr[$taxovalue] = $taxovalue;
						}
					}
					?>
					<option value="add_custom<?php print($count); ?>">Add Custom Field</option>
					</select> 
					</td>
					<!--<td>
					<?php
                                        //echo '<pre>'; print_r($getrecords); die('comeing'); 
					//$getrecords[0][$key] = htmlspecialchars($getrecords[0][$key], ENT_COMPAT, "UTF-8");
					/*if(strlen($getrecords[0][$key])>32)
					{
						$getrecords[0][$key] = substr($getrecords[0][$key], 0, 28).'...';
					}*/ ?>
					<span id='elementVal_<?php //echo isset($key) ? $key : ''; ?>' > <?php //echo ($getrecords[0][$key]); ?> </span>
					</td>
					<td width = "180px;">
					<input class="customfieldtext" type="text" id="textbox<?php //print($count); ?>" name="textbox<?php //print($count); ?>" TITLE="Replace the default value" style="display: none;float:left;width:160px;" value="<?php //echo isset($value) ? $value : ''  ?>"/>
					<span style="display: none;float:left" id="customspan<?php echo $count ?>">
					<a href="#" class="tooltip">
					<img src="../wp-content/plugins/<?php echo WP_CONST_ADVANCED_XML_IMP_SLUG;?>/images/help.png" />
					<span class="tooltipPostStatus">
					<img class="callout" src="../wp-content/plugins/<?php echo WP_CONST_ADVANCED_XML_IMP_SLUG;?>/images/callout.gif" />
					<strong>Give a name for your new custom field</strong>
					<img src="../wp-content/plugins/<?php echo WP_CONST_ADVANCED_XML_IMP_SLUG;?>/images/help.png" style="margin-top: 6px;float:right;" />
					</span>
					</a> 
					</span>
					<span style="display: none; color: red; margin-left: 5px;" id="customspan<?php echo $count ?>">Replace the custom value</span>
					</td>-->
					</tr>
					<?php
					$count++;
			}
			foreach($mappingFields_arr as $mkey => $mval){
				$mFieldsArr .= $mkey.',';
			}
			$mFieldsArr = substr($mFieldsArr, 0, -1);
			?>
		</table>
		<input type="hidden" id="mapping_fields_array" name="mapping_fields_array" value="<?php print_r($mFieldsArr); ?>"/>
		<div>
			<div class="goto_import_options" align=center>
		<div class="mappingactions" >
		<input type='button' id='clear_mapping' class='clear_mapping btn btn-warning' name='clear_mapping' value='Clear Mapping' onclick='clearMapping();' style = 'float:left'/>
		<span >
		<a href="#" class="tooltip tooltip_smack"  >
		<img src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/help.png" />
		<span class="tooltipClearMapping">
		<img class="callout" src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/callout.gif" />
		<strong>Refresh to re-map fields</strong>
		<img src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/help.png" style="margin-top: 6px;float:right;" />
		</span>
		</a>
		</span>
		</div>
		<div class="mappingactions" >
		<input type='submit' id='goto_importer_setting' class='goto_importer_setting btn btn-info' name='goto_importer_setting' value='Next >>' /> 
		</div>
		</div> 
		</div>
		</div>
		</div>
		</form>
		</td>
		</tr>
		<tr>
		<td>
        <?php } ?>
           </div>
  
  <!-- here ends --> 
      <h3>Import Option Settings</h3>
		<div id='sec-three' <?php if(isset($_REQUEST['step']) && $_REQUEST['step'] != 'importoptions'){ ?> style='display:none' <?php } ?> ><?
           $filename = ''; $stepstatus = ''; $tmpLoc = ''; $selectedImporter = '';
            if(isset($_REQUEST['step']) && $_REQUEST['step'] == 'importoptions') {
             require_once(WP_CONST_ADVANCED_XML_IMP_DIRECTORY.'includes/WXR_Handler.php');
             $handlerObj = new WXR_Handler();
             $checktype = $handlerObj->importOption($_POST);
	     if(isset($checktype['wp_advanced_importer']['custom_xml']))
               {                  
             if(isset($checktype['wp_advanced_importer']['custom_xml']['uploadedFile']) && $checktype['wp_advanced_importer']['custom_xml']['uploadedFile'] != '') {
                          $file_name=$checktype['wp_advanced_importer']['custom_xml']['uploadedFile'];
                          $filename = $impCE->convert_string2hash_key($file_name);
                         }
        if(isset($checktype['wp_advanced_importer']['custom_xml']['uploaded_csv_name']) && $checktype['wp_advanced_importer']['custom_xml']['uploaded_csv_name'] != '')
                         {
                          $upload_csv_name=$checktype['wp_advanced_importer']['custom_xml']['uploaded_csv_name'];
                         }
        if(isset($checktype['wp_advanced_importer']['custom_xml']['selectedImporter']) && $checktype['wp_advanced_importer']['custom_xml']['selectedImporter'] != '')
                        {
                          $selectedImporter=$checktype['wp_advanced_importer']['custom_xml']['selectedImporter'];
                        }
        if(isset($checktype['wp_advanced_importer']['custom_xml']['totRecords']) && $checktype['wp_advanced_importer']['custom_xml']['totRecords'] != '')
                        {
                          $totRecords=$checktype['wp_advanced_importer']['custom_xml']['totRecords'];
                        }
        if(isset($checktype['wp_advanced_importer']['custom_xml']['tmpLoc']) && $checktype['wp_advanced_importer']['custom_xml']['tmpLoc'] != '')
                        {
                          $tmpLoc=$checktype['wp_advanced_importer']['custom_xml']['tmpLoc'];
                        }
                        if(isset($checktype['wp_advanced_importer']['custom_xml']['h2']) && $checktype['wp_advanced_importer']['custom_xml']['h2'] != '')
                        {
                          $h2=$checktype['wp_advanced_importer']['custom_xml']['h2'];
                        }
                        if(isset($checktype['wp_advanced_importer']['custom_xml']['stepstatus']) && $checktype['wp_advanced_importer']['custom_xml']['stepstatus'] != '')
                        {
                          $stepstatus=$checktype['wp_advanced_importer']['custom_xml']['stepstatus'];
                        }

  }
     else if(isset($checktype['wp_advanced_importer']['page_xml']))
          {                  
        if(isset($checktype['wp_advanced_importer']['page_xml']['uploadedFile']) && $checktype['wp_advanced_importer']['page_xml']['uploadedFile'] != '')
                         {
                          $file_name=$checktype['wp_advanced_importer']['page_xml']['uploadedFile'];
                          $filename = $impCE->convert_string2hash_key($file_name);
                         }
        if(isset($checktype['wp_advanced_importer']['page_xml']['mydelimeter']) && $checktype['wp_advanced_importer']['page_xml']['mydelimeter'] != '')
                         {
                          $delimeter=$checktype['wp_advanced_importer']['page_xml']['mydelimeter'];
                                                  }
        if(isset($checktype['wp_advanced_importer']['page_xml']['uploaded_csv_name']) && $checktype['wp_advanced_importer']['page_xml']['uploaded_csv_name'] != '')
                         {
                          $upload_csv_name=$checktype['wp_advanced_importer']['page_xml']['uploaded_csv_name'];
                         }
        if(isset($checktype['wp_advanced_importer']['page_xml']['selectedImporter']) && $checktype['wp_advanced_importer']['page_xml']['selectedImporter'] != '')
                        {
                          $selectedImporter=$checktype['wp_advanced_importer']['page_xml']['selectedImporter'];
                        }
        if(isset($checktype['wp_advanced_importer']['page_xml']['totRecords']) && $checktype['wp_advanced_importer']['page_xml']['totRecords'] != '')
                        {
                          $totRecords=$checktype['wp_advanced_importer']['page_xml']['totRecords'];
                        }
        if(isset($checktype['wp_advanced_importer']['page_xml']['tmpLoc']) && $checktype['wp_advanced_importer']['page_xml']['tmpLoc'] != '')
                        {
                          $tmpLoc=$checktype['wp_advanced_importer']['page_xml']['tmpLoc'];
                        }
                        if(isset($checktype['wp_advanced_importer']['page_xml']['h2']) && $checktype['wp_advanced_importer']['page_xml']['h2'] != '')
                        {
                          $h2=$checktype['wp_advanced_importer']['page_xml']['h2'];
                        }
                        if(isset($checktype['wp_advanced_importer']['page_xml']['stepstatus']) && $checktype['wp_advanced_importer']['page_xml']['stepstatus'] != '')
                        {
                          $stepstatus=$checktype['wp_advanced_importer']['page_xml']['stepstatus'];
                        }
           }
     else if(isset($checktype['wp_advanced_importer']['post_xml']))
          {                  
        if(isset($checktype['wp_advanced_importer']['post_xml']['uploadedFile']) && $checktype['wp_advanced_importer']['post_xml']['uploadedFile'] != '')
                         {
                          $file_name=$checktype['wp_advanced_importer']['post_xml']['uploadedFile'];
                          $filename = $impCE->convert_string2hash_key($file_name);
                         }
        if(isset($checktype['wp_advanced_importer']['post_xml']['mydelimeter']) && $checktype['wp_advanced_importer']['post_xml']['mydelimeter'] != '')
                         {
                          $delimeter=$checktype['wp_advanced_importer']['post_xml']['mydelimeter'];
                                                  }
        if(isset($checktype['wp_advanced_importer']['post_xml']['uploaded_csv_name']) && $checktype['wp_advanced_importer']['post_xml']['uploaded_csv_name'] != '')
                         {
                          $upload_csv_name=$checktype['wp_advanced_importer']['post_xml']['uploaded_csv_name'];
                         }
        if(isset($checktype['wp_advanced_importer']['post_xml']['selectedImporter']) && $checktype['wp_advanced_importer']['post_xml']['selectedImporter'] != '')
                        {
                          $selectedImporter=$checktype['wp_advanced_importer']['post_xml']['selectedImporter'];
                        }
        if(isset($checktype['wp_advanced_importer']['post_xml']['totRecords']) && $checktype['wp_advanced_importer']['post_xml']['totRecords'] != '')
                        {
                          $totRecords=$checktype['wp_advanced_importer']['post_xml']['totRecords'];
                        }
        if(isset($checktype['wp_advanced_importer']['post_xml']['tmpLoc']) && $checktype['wp_advanced_importer']['post_xml']['tmpLoc'] != '')
                        {
                          $tmpLoc=$checktype['wp_advanced_importer']['post_xml']['tmpLoc'];
                        }
                        if(isset($checktype['wp_advanced_importer']['post_xml']['h2']) && $checktype['wp_advanced_importer']['post_xml']['h2'] != '')
                        {
                          $h2=$checktype['wp_advanced_importer']['post_xml']['h2'];
                        }
                        if(isset($checktype['wp_advanced_importer']['post_xml']['stepstatus']) && $checktype['wp_advanced_importer']['post_xml']['stepstatus'] != '')
                        {
                          $stepstatus=$checktype['wp_advanced_importer']['post_xml']['stepstatus'];
                        }
          }     ?>     
		<input type="hidden" id="prevoptionindex" name="prevoptionindex" value="<?php echo  isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['prevoptionindex']) ?  $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['prevoptionindex'] : ''; ?>"/>
		<input type="hidden" id="prevoptionvalue" name="prevoptionvalue" value="<?php echo isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['prevoptionvalue']) ? $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['prevoptionvalue'] : ''; ?>"/>
		<input type='hidden' id='current_record' name='current_record' value='<?php echo isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['current_record']) ? $_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['current_record'] : ''; ?>' />
		<input type='hidden' id='tot_records' name='tot_records' value='<?php echo isset($totRecords) ? $totRecords : ''; ?>' />
      <input type='hidden' id='selectedImporter' name='selectedImporter' value='<?php echo 'importtype'; ?>' >
		<input type='hidden' id='tmpLoc' name='tmpLoc' value='<?php echo isset($tmpLoc) ? $tmpLoc : '' ;?>' />
		<input type='hidden' id='checkfile' name='checkfile' value='<?php echo isset($filename) ? $filename : ''; ?>' />
		<input type='hidden' id='uploadedFile1' name='uploadedFile1' value='<?php echo isset($upload_csv_name) ? $upload_csv_name : ''; ?>' />
		<input type='hidden' id='stepstatus' name='stepstatus' value='<?php echo isset($stepstatus) ? $stepstatus : ''; ?>' />
		<input type='hidden' id='mappingArr' name='mappingArr' value='' />
                <input type='hidden' id='siteurl' name='siteurl' value = '<?php echo site_url(); ?>'>
                <input type='hidden' id='h2' name='h2' value='<?php echo isset($h2) ? $h2 : '';?>' >
		<!-- Import settings options -->
		<!--        <h4 class="hndle">Search settings</h4>-->
		
		<form method="POST" >
                <div class="logstyle">
		<ul id="settings">
		<li>
		<label><input name='duplicatecontent' id='duplicatecontent' type="checkbox" value=""> Detect Duplicate Post Content</label> <br>
		<label><input name='duplicatetitle' id='duplicatetitle' type="checkbox" value="" > Detect Duplicate Post Title</label> <br>
		How much comparisons per Server-Request? <span class="mandatory">*</span> <input name="importlimit" id="importlimit" type="text" value="" onblur="check_allnumeric(this.value);">
		<span class='msg' id='server_request_warning' style="display:none;color:red;margin-left:-10px;">You can set upto <?php echo isset($totRecords) ? $totRecords : '' ; ?> per request.</span>
		<input type="hidden" id="currentlimit" name="currentlimit" value="0"/>
		<input type="hidden" id="tmpcount" name="tmpcount" value="0" />
                <input type='hidden' id="siteurl" name="siteurl" value='<?php echo get_site_url(); ?>o'>
		</li>
		</ul>
               
		<input id="startbutton" class="btn btn-primary" type="button" value="Import Now" style="color: #ffffff;background:#2E9AFE;" onclick="importRecordsbySettings();"  disabled/>
		<input class="btn btn-warning" type="button" value="Import Again" id="importagain" style="display:none" onclick="import_again();"  />
		<!--<input id="continuebutton" class="button" type="button" value="Continue old search" style="color: #ffffff;background:#2E9AFE;">-->
		<div id="ajaxloader" style="display:none"><img src="<?php echo WP_CONST_ADVANCED_XML_IMP_DIR; ?>images/ajax-loader.gif"> Processing...</div>
		<div class="clear"></div>
		</div>
		<div class="clear"></div>
                 </form>
        <?php } ?>
		
		<!-- Code Ends Here-->
		</div>
		</td>
		</tr>
		</table>
		</div>
		<div id='reportLog' class='reportLog'>
		<h3>Logs :</h3>
		<div id="logtabs" class="logcontainer">
		<div id="log" class='log'>	
		</div>
		</div>
		</div>
		<!-- Promotion footer for other useful plugins -->
		<!--<div class= "promobox" id="pluginpromo" style="width:99%;">
		<div class="accordion-group" >
		<div class="accordion-heading">
		<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> OTHER USEFUL PLUGINS BY SMACKCODERS </a>
		</div>
		<div class="accordion-body in collapse">
		<div>
		<?php //$impCE->common_footer_for_other_plugin_promotions(); ?>
		</div>
		</div>
		</div>
		</div>-->
	</div>

