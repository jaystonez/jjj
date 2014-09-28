<?php
  function gfct_fontawesome_enqueue_script() {
	 wp_enqueue_style( 'font-awesome-css', '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css', array(), '4.0.3' );
}
  function gfctmagic_enqueue_script() {
	 wp_enqueue_script( 'gfctmagic',  gfct__PLUGIN_URL .'js/gfctmagic.js', array('jquery'), '1.0' );
}
//add theme class to gform wrapper
//used also to inject placeholder class
add_filter("gform_pre_render", "gfct_theme_class");	
  function gfct_theme_class ($form){
      if (isset($form['gfct_placeholder']) AND '1' == $form['gfct_placeholder']){
                $form["cssClass"] .= ' gfct_placeholder';
      }
      if (isset($form['gfct_theme'])){
      //var_dump($form);
      $class = $form["cssClass"];
      $addclass = $form['gfct_theme'];
      $form["cssClass"] .= ' '.$addclass;
      }
      if ((isset($form['gfct_placeholder']) AND '1' == $form['gfct_placeholder']) OR (isset($form['gfct_theme'])) ){
          $form["cssClass"] .= ' gfct_noconflict';
      }
      return $form;
  }
  add_filter('gform_pre_render', 'populate_gfct_fields');
function populate_gfct_fields($form){
//   var_dump(print_r($form['fields'][1])); 
    foreach($form['fields'] as &$field){
       if(isset($field['gfctfaField'])) {
        $field["cssClass"] .= " gfct_add ".$field['gfctfaField']." gfct_end";
     //   $field["cssClass"] = 'xxx';
        }
    }
    return $form;
}
//used to populate colors and stuff in theme css rendering
function gfct_theme_color2($theme , $elementid) {
    $options = get_option('gfct');
    if(isset($options[$theme.'_user'][$elementid]) AND 0 < strlen($options[$theme.'_user'][$elementid])){
    $value = $options[$theme.'_user'][$elementid];}
    else {$value = $options[$theme.'_orig'][$elementid];}
    return $value;
} 
/**
* Convert a hexa decimal color code to its RGB equivalent
*/     
    function gfct_hex2RGB($hexStr, $returnAsString = true, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return 'Invalid hex color code'; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}
//get background rgba color
function get_rgba($themeslug){
    $rgb = gfct_hex2RGB(gfct_theme_color2($themeslug,'background'));
    $opacity = gfct_theme_color2($themeslug,'opacity');
    $rgba = 'rgba('.$rgb.','.$opacity.')';
    return $rgba;
}
function gfct_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=gfct_global_settings">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
function hook_gfct_fontawesome_basic_css(){
    $code ='
/*  
    Name : GFCT css
    Description:    Start with basic fontawesome and placeholder support
    Author     : Mo Pristas
    Comment    : This css is generated by the Gravity Forms CSS Themes plugin
*/    
.gfct_fa_span i {
    margin-left: 10px;
    margin-top: 6px;
  position:absolute;
}
.gfct-uses-fa {
    padding-left: 30px !important;
}
.gfct_placeholder_active, .gform_wrapper .gfct_placeholder_active.datepicker, .gform_wrapper .gfct_placeholder_active.ginput_complex input[type="text"], .gform_wrapper .gfct_placeholder_active.ginput_complex input[type="url"], .gform_wrapper .gfct_placeholder_active.ginput_complex input[type="email"], .gform_wrapper .gfct_placeholder_active.ginput_complex input[type="tel"], .gform_wrapper .gfct_placeholder_active.ginput_complex input[type="number"], .gform_wrapper .gfct_placeholder_active.ginput_complex input[type="password"], .gform_wrapper .gfct_placeholder_active.ginput_complex select {
    width: 94% !important;
}
.gfct_placeholder_active.ginput_complex span {
    padding: 7px 0;
}';
    return $code;
}