<?php //add theme corporate
function gfct_corporate_theme()
{
///set initial values
    //$label = gfct_theme_color2('corporate','label');
    //$border = gfct_theme_color2('corporate','border');
   // $button = gfct_theme_color2('corporate','button');
///see if we need to replace default values and replace them
$background = gfct_theme_color2('corporate','background');
$padding = gfct_theme_color2('corporate','padding');
$label = gfct_theme_color2('corporate','label');
$button = gfct_theme_color2('corporate','button');
$facolor = gfct_theme_color2('corporate','facolor');
$rgba = get_rgba('corporate');
$header = gfct_theme_color2('corporate','header');
$output='
/*  
    Name : Corporate Theme rgba-'.$rgba.'
*/
.gform_wrapper.gfct_corporate {
    background: none repeat scroll 0 0 '.$rgba.';
}
.gfct_corporate .gform_body {
    padding: 0 '.$padding.';
}
.gform_wrapper.gfct_corporate ul li, .gform_wrapper.gfct_corporate input[type="text"], .gform_wrapper.gfct_corporate input[type="url"], .gform_wrapper.gfct_corporate input[type="email"], .gform_wrapper.gfct_corporate input[type="tel"], .gform_wrapper.gfct_corporate input[type="number"], .gform_wrapper.gfct_corporate input[type="password"] {
    color: '.$label.';
}
.gform_wrapper.gfct_corporate .gfield_label {
    color: '.$label.';
    font-weight: lighter !important;
}
.gform_wrapper.gfct_corporate .gfield_checkbox li input[type="checkbox"], .gform_wrapper.gfct_corporate .gfield_radio li input[type="radio"], .gform_wrapper.gfct_corporate .gfield_checkbox li input{
  -moz-appearance:none;
-webkit-appearance:none;
-o-appearance:none;
}
.gfct_corporate .button.gform_button{
    background: none repeat scroll 0 0 '.$button.';
    border: 0 none;
    border-radius: 5px;
    color: #fff;
    cursor: pointer;
    font-size: 20px;
    height: 50px;
    margin: 0 12px;
    outline: 0 none;
    padding: 0;
    text-align: center;
    width: 94%;
}
.gfct_corporate select, .gform_wrapper.gfct_corporate input[type="text"], .gform_wrapper.gfct_corporate input[type="url"], .gform_wrapper.gfct_corporate input[type="email"], .gform_wrapper.gfct_corporate input[type="tel"], .gform_wrapper.gfct_corporate input[type="number"], .gform_wrapper.gfct_corporate input[type="password"]{
    background: none repeat scroll 0 0 #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 15px;
    height: 39px;
    margin-bottom: 0;
    padding-left: 10px;
}
.gform_wrapper.gfct_corporate input[type="text"]:focus, .gform_wrapper.gfct_corporate input[type="url"]:focus, .gform_wrapper.gfct_corporate input[type="email"]:focus, .gform_wrapper.gfct_corporate input[type="tel"]:focus, .gform_wrapper.gfct_corporate input[type="number"]:focus, .gform_wrapper.gfct_corporate input[type="password"]:focus{
    border-color:#6e8095;
    outline: none;
}
.gfct_corporate .gform_title{
    background: none repeat scroll 0 0 '.$header.' !important;
    color: '.$label.';
    font-size: 25px !important;
    font-weight: 300 !important;
    line-height: 30px;
    text-align: center;
  padding:15px 10px;
}

.gfct_corporate .left_label .gfield_label {
    margin: 5px 15px 0 0;
}
.gform_wrapper.gfct_corporate .left_label ul.gfield_checkbox, .gform_wrapper.gfct_corporate .right_label ul.gfield_checkbox, .gform_wrapper.gfct_corporate .left_label ul.gfield_radio, .gform_wrapper.gfct_corporate .right_label ul.gfield_radio {
    padding: 8px 0;
}
.gfct_corporate .gfct_fa_span i {
  	color: '.$facolor.';
    margin-left: 10px;
    margin-top: 11px;
    position: absolute;
}
.gform_wrapper.gfct_corporate textarea {
    font-size: 1em;
}';
return $output;
}