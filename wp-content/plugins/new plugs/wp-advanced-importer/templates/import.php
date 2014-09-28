<?php
require_once(WP_CONST_ADVANCED_XML_IMP_DIRECTORY.'lib/skinnymvc/core/base/SkinnyBaseActions.php');
require_once(WP_CONST_ADVANCED_XML_IMP_DIRECTORY.'lib/skinnymvc/core/SkinnyActions.php');
$skinnyObj = new CallWPAdvImporterObj();
$checktype['wp_advanced_importer']['common']['type'] = array();
$par=array();
$par_xml=array();
$par_pagexml=array();
$par_customxml=array();
$par_file=array();
$resultArr = array();
$fileExtension = array();
$get_h2 = null;
$checktype=get_option('wp_advanced_importer');
$par=$checktype['wp_advanced_importer']['common']['type'];
if(isset($checktype['wp_advanced_importer']['post_xml'])){$par_xml=$checktype['wp_advanced_importer']['post_xml'];}
if(isset($checktype['wp_advanced_importer']['page_xml'])){ $par_pagexml= $checktype['wp_advanced_importer']['page_xml'];}
if(isset($checktype['wp_advanced_importer']['custom_xml'])){$par_customxml=$checktype['wp_advanced_importer']['custom_xml'];}
$par_file=$checktype['wp_advanced_importer']['common'];
if(isset($par) && (isset($par[0]) && $par[0] == 'post'))  //here  code begins for post
{
	$limit = $_POST['postdata']['limit'];
	$totRecords = $_POST['postdata']['totRecords'];
	$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['importlimit'] = $_POST['postdata']['importlimit'];
	$count = $_POST['postdata']['importlimit'];
	$requested_limit = $_POST['postdata']['importlimit'];
	$tmpCnt = $_POST['postdata']['tmpcount'];
	if($count < $totRecords){
		$count = $tmpCnt+$count;
		if($count > $totRecords){
			$count = $totRecords;
		}
	} else{
		$count = $totRecords;
	}  
	$get_h2 = $par_xml['h2'];
	$module='post';
	$xml_ext = $par_file['upload_csv_realname'];
	$filename = $par_xml['uploadedFile'];//['common']['uploadfilename'];
	$getExtention = explode('.',$xml_ext);
	$ext_cnt = count($getExtention) - 1;
	$fileExtention = $getExtention[$ext_cnt];
	$sessionArr = $par_xml;
	$resultArr = $skinnyObj->xml_file_data($filename,$fileExtention,$module);
	if($_POST['postdata']['dupTitle']){
		$a= $_POST['postdata']['dupTitle'];
		$skinnyObj->titleDupCheck = $_POST['postdata']['dupTitle'];
	}
	if($_POST['postdata']['dupContent']){
		$skinnyObj->conDupCheck = $_POST['postdata']['dupContent'];
	}

	for($i=$limit;$i<$count;$i++){
		$_SESSION['SMACK_SKIPPED_RECORDS'] = $i;
		if (isset($resultArr[$i]) )
		{
			$skinnyObj->processDataInWP($resultArr[$i], $sessionArr, $sessionArr,$module);
		}
		$limit++;
	}
	if($limit >= $totRecords){
		$dir = $skinnyObj->getUploadDirectory();
		$skinnyObj->deletefileafterprocesscomplete($dir);
	}
	if($skinnyObj->insPostCount != 0 || $skinnyObj->dupPostCount != 0 || $skinnyObj->updatedPostCount != 0){
		if(!isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insPostCount']))
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insPostCount']=0;
		if(!isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupPostCount']))
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupPostCount']=0;
		if(!isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']))
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']=0;
		if(!isset($skinnyObj->capturedId))
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['captureId']=0;
		$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insPostCount']=$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insPostCount']+$skinnyObj->insPostCount;
		$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupPostCount']=$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupPostCount']+$skinnyObj->dupPostCount;
		$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']=$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']+$skinnyObj->updatedPostCount;
		if(isset($skinnyObj->capturedId)){
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['captureId']=$skinnyObj->capturedId;
		}
	}
	if($par[0]=='post'){
		echo "<div style='margin-left:7px;'>";
		if(($limit == $requested_limit) && ($limit <= $count))
			echo "<div style='margin-left:3px;'>Chosen server request is " . $count . " .</div><br>";
		echo "[". date('h:m:s') ."] - No of post(s) Skipped - " . $skinnyObj->dupPostCount . ".<br>";
		echo "[". date('h:m:s') ."] - No of post(s) Inserted - " . $skinnyObj->insPostCount . '.<br>';
		if((isset($par[0])&& $par[0] != 'page') || (isset($par[0]) && $par[0] != 'custompost') ){
			if($limit == $totRecords)
				echo "<br><div style='margin-left:3px;'>Import successfully completed!.</div>";
		}
		echo "</div>";

	}
	foreach($_SESSION['SMACK_MAPPING_SETTINGS_VALUES'] as $key => $value){
		for($j=0;$j<$get_h2;$j++){
			if($key == 'mapping'.$j){
				$mapArr[$j] = $value;
			}
		}
	}
}// for type post is selected 
if((isset($par[0]) && $par[0] == 'page') ||  (isset($par[1])  && $par[1] == 'page' )) // page starts here 
{
	$limit = $_POST['postdata']['limit'];
	$totRecords = $_POST['postdata']['totRecords'];
	$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['importlimit'] = $_POST['postdata']['importlimit'];
	$count = $_POST['postdata']['importlimit'];
	$requested_limit = $_POST['postdata']['importlimit'];
	$tmpCnt = $_POST['postdata']['tmpcount'];

	if($count < $totRecords){
		$count = $tmpCnt+$count;
		if($count > $totRecords){
			$count = $totRecords;
		}
	} else{
		$count = $totRecords;
	}  
	$get_h2 = $par_pagexml['h2'];
	$module = 'page';
	$xml_ext = $par_file['upload_csv_realname'];
	$filename = $par_pagexml['uploadedFile'];
	$getExtention = explode('.',$xml_ext);
	$ext_cnt = count($getExtention) - 1;
	$fileExtention = $getExtention[$ext_cnt];
	$sessionArr = $par_pagexml;
	$resultArr = $skinnyObj->xml_file_data($filename,$fileExtention,$module);
	if($_POST['postdata']['dupTitle']){
		$a= $_POST['postdata']['dupTitle'];
		$skinnyObj->titleDupCheck = $_POST['postdata']['dupTitle'];
	}
	if($_POST['postdata']['dupContent']){
		$skinnyObj->conDupCheck = $_POST['postdata']['dupContent'];
	}
	for($i=$limit;$i<$count;$i++){
		$_SESSION['SMACK_SKIPPED_RECORDS'] = $i;
		if (isset($resultArr[$i]) )
		{
			$skinnyObj->processDataInWP($resultArr[$i], $sessionArr, $sessionArr,$module);
		}
		$limit++;
	}
	if($limit >= $totRecords){
		$dir = $skinnyObj->getUploadDirectory();
		$skinnyObj->deletefileafterprocesscomplete($dir);
	}
	if($skinnyObj->insPageCount != 0 || $skinnyObj->dupPageCount != 0 || $skinnyObj->updatedPostCount != 0){
		if(!isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insPageCount']))
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insPageCount']=0;
		if(!isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupPageCount']))
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupPageCount']=0;
		if(!isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']))
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']=0;
		if(!isset($skinnyObj->capturedId))
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['captureId']=0;
		$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insPageCount']=$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insPageCount']+$skinnyObj->insPageCount;
		$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupPageCount']=$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupPageCount']+$skinnyObj->dupPageCount;
		$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']=$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']+$skinnyObj->updatedPostCount;
		if(isset($skinnyObj->capturedId)){
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['captureId']=$skinnyObj->capturedId;
		}
	}
	if($module == 'page'){
		echo "<div style='margin-left:7px;'>";
		if(($limit == $requested_limit) && ($limit <= $count)) {
			if(isset($par[0]) && $par[0] == 'page') {
				echo "<div style='margin-left:3px;'>Choosen server request is " . $count . " .</div><br>";
			}
			echo "[". date('h:m:s') ."] - No of page(s) Skipped - " . $skinnyObj->dupPageCount . ".<br>";
			echo "[". date('h:m:s') ."] - No of page(s) Inserted - " . $skinnyObj->insPageCount . '.<br>';
			if($par[0] == 'page') {
				if($limit == $totRecords)
					echo "<br><div style='margin-left:3px;'>Import successfully completed!.</div>";
			}
			echo "</div>";
		}
	}
	foreach($_SESSION['SMACK_MAPPING_SETTINGS_VALUES'] as $key => $value){
		for($j=0;$j<$get_h2;$j++){
			if($key == 'mapping'.$j){
				$mapArr[$j] = $value;
			}
		}
	}
} // page ends here
if((isset($par[0]) && $par[0] == 'custompost') ||  (isset($par[1])  && $par[1] == 'custompost' ) || (isset($par[2]) && $par[2] == 'custompost')) // custom post  starts here 
{ 
	$limit = $_POST['postdata']['limit'];
	$totRecords = $_POST['postdata']['totRecords'];
	$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['importlimit'] = $_POST['postdata']['importlimit'];
	$count = $_POST['postdata']['importlimit'];
	$requested_limit = $_POST['postdata']['importlimit'];
	$tmpCnt = $_POST['postdata']['tmpcount'];

	if($count < $totRecords){
		$count = $tmpCnt+$count;
		if($count > $totRecords){
			$count = $totRecords;
		}
	} else{
		$count = $totRecords;
	}  
	$get_h2 = $par_customxml['h2'];
	$module = 'custompost';
	$xml_ext = $par_file['upload_csv_realname'];
	$filename = $par_customxml['uploadedFile'];
	$getExtention = explode('.',$xml_ext);
	$ext_cnt = count($getExtention) - 1;
	$fileExtention = $getExtention[$ext_cnt];
	$sessionArr = $par_customxml;
	$resultArr = $skinnyObj->xml_file_data($filename,$fileExtention,$module);
	$ptype = get_post_types();
	if($_POST['postdata']['dupTitle']){
		$a= $_POST['postdata']['dupTitle'];
		$skinnyObj->titleDupCheck = $_POST['postdata']['dupTitle'];
	}
	if($_POST['postdata']['dupContent']){
		$skinnyObj->conDupCheck = $_POST['postdata']['dupContent'];
	}
	for($i=$limit;$i<$count;$i++){
		$_SESSION['SMACK_SKIPPED_RECORDS'] = $i;
		if(isset($resultArr[$i]) )
		{
			$skinnyObj->processDataInWP($resultArr[$i], $sessionArr, $sessionArr,$module);
		}
		$limit++;
	}
	if($limit >= $totRecords){
		$dir = $skinnyObj->getUploadDirectory();
		$skinnyObj->deletefileafterprocesscomplete($dir);
	}
	if($skinnyObj->insCPTCount != 0 || $skinnyObj->dupCPTCount != 0 || $skinnyObj->updatedPostCount != 0){
		if(!isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insCPTCount']))
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insCPTCount']=0;
		if(!isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupCPTCount']))
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupCPTCount']=0;
		if(!isset($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']))
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']=0;
		if(!isset($skinnyObj->capturedId))
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['captureId']=0;
		$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insCPTCount']=$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['insCPTCount']+$skinnyObj->insCPTCount;
		$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupCPTCount']=$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['dupCPTCount']+$skinnyObj->dupCPTCount;
		$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']=$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['updatedPostCount']+$skinnyObj->updatedPostCount;
		if(isset($skinnyObj->capturedId)){
			$_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['captureId']=$skinnyObj->capturedId;
		}
	}
	if($module=='custompost'){
		echo "<div style='margin-left:7px;'>";
		if(($limit == $requested_limit) && ($limit <= $count)) {
			if (isset($par[0]) && $par[0] == 'custompost') {
				echo "<div style='margin-left:3px;'>Chosen server request is " . $count . " .</div><br>";
			}
			echo "[". date('h:m:s') ."] - No of custompost(s) Skipped - " . $skinnyObj->dupCPTCount . ".<br>";
			echo "[". date('h:m:s') ."] - No of custompost(s) Inserted - " . $skinnyObj->insCPTCount . '.<br>';
		}
              if((isset($par[0]) && $par[0] != 'post') && (isset($par[1]) && $par[1] != 'page')) {
		if($limit == $totRecords)
			echo "<br><div style='margin-left:3px;'>Import successfully completed!.</div>";
		echo "</div>";
             }
	}
	foreach($_SESSION['SMACK_MAPPING_SETTINGS_VALUES'] as $key => $value){
		for($j=0;$j<$get_h2;$j++){
			if($key == 'mapping'.$j){
				$mapArr[$j] = $value;
			}
		}
	}
}
?>
