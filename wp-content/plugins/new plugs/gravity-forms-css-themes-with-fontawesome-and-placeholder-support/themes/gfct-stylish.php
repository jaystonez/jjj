<?php //add theme stylish
function gfct_stylish_theme()
{
///set initial values
    $options = get_option('gfct');
    gfct_theme_color2('stylish','buttonhover');

gfct_theme_color2('stylish','facolor');
           $button   = gfct_theme_color2('stylish','button');
           $rgbac    = gfct_hex2RGB($options['stylish_orig']['background']);
           $buttonhover = gfct_theme_color2('stylish','buttonhover');
           $label = gfct_theme_color2('stylish','label');
           $heading = gfct_theme_color2('stylish','heading');
           $facolor = gfct_theme_color2('stylish','facolor');
///see if we need to replace default values and replace them
//can't do background because it is in rgb, function called
    $rgba =  get_rgba('stylish');
$output='
/*  
    Name : Stylish Theme
*/
.gfct_stylish .ginput_complex label {
    color: '.$label.';
}
.gfct_stylish .button.gform_button:hover {
    background: none repeat scroll 0 0 '. $buttonhover.';
}
.gfct_stylish .gfield_radio, .gfct_stylish .gfield_checkbox {
    width: 56%;
  margin-top:10px !important;
}
.gfct_stylish .gform_body {
    width: 97%;
}
.gform_wrapper.gfct_stylish h3.gform_title {
    border-bottom: 1px solid '.$heading.';
    line-height: 22px;
    padding-bottom: 6px;
    color:'.$heading.';
}
.gform_wrapper.gfct_stylish li, .gform_wrapper.gfct_stylish form li{
  position:relative;
  visibility:visible;
}
.gfct_stylish .button.gform_previous_button {
    line-height: 22px !important;
    margin-bottom: 5px !important;
    width: 40% !important;
}
.gform_wrapper.gfct_stylish h3.gf_progressbar_title {
    color: white;
    opacity: 1 !important;
}
.gform_wrapper.gfct_stylish .button {
    background: none repeat scroll 0 0 '.$button.';
  border: 1px solid white;
    color: #fff;
    display: block;
    line-height: 38px;
    margin: 0 auto !important;
    text-transform: uppercase;
    width: 72%;
}
.gform_wrapper.gfct_stylish .gf_progressbar_wrapper {
    border-bottom: 1px solid #fff;
}
.gform_wrapper.gfct_stylish .gfield_checkbox li label, .gform_wrapper.gfct_stylish .gfield_radio li label, .gform_wrapper.gfct_stylish ul.gfield_radio li input[type="radio"]:checked + label, .gform_wrapper.gfct_stylish ul.gfield_checkbox li input[type="checkbox"]:checked + label {
    color: '. $label .';
}
.gfct_stylish .gform_page_fields {
    margin: 0 auto;
    width: 70%;
}
.gform_wrapper.gfct_stylish select, .gform_wrapper.gfct_stylish select input[type="text"], .gform_wrapper.gfct_stylish input[type="url"], .gform_wrapper.gfct_stylish input[type="email"], .gform_wrapper.gfct_stylish input[type="tel"], .gform_wrapper.gfct_stylish input[type="number"], .gform_wrapper.gfct_stylish input[type="password"] {
    display: block;
    height: 31px;
    line-height: 22px;
    margin-bottom: 12px;
    width: 56% !important;
    padding-left:9%;
}
.gfct_stylish .gfield_label {
    color:'. $label .';
    display: block;
    margin: 0 auto;
}
.gform_wrapper.gfct_stylish {
    background: none repeat scroll 0 0 '. $rgba .';
    border-radius: 10px;
    margin: 40px auto;
    padding: 10px 10%;
    position: relative;
}
.gfct_stylish .gfct_fa_span i {
    margin-top: 0.4em;
    position: absolute;
    color: '.$facolor.';
}
.gfct_stylish textarea {
    margin: 1px 0;
    padding-top: 6px;
}';
return $output;
}