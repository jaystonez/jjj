<?php
add_filter('gform_form_settings', 'gfct_theme', 10, 2);
function gfct_theme($settings, $form) {
    $settings['Form Layout']['gfct_theme'] = '
        <tr>
            <th><label for="gfct_theme">Gravity Forms Css Themes plugin theme</label></th>
            <td>'. gfct_get_themes_list(rgar($form, 'gfct_theme')).'
            </td>
        </tr>';

    return $settings;
}
// save your custom form setting
add_filter('gform_pre_form_settings_save', 'save_gfct_theme');
function save_gfct_theme($form) {
    $form['gfct_theme'] = rgpost('gfct_theme');
    return $form;
};     
function gfct_get_themes_list($currenttheme) {
    $options = get_option('gfct_global_settings');
    $mythemes = $options['themes'];
    //var_dump($themes);
    $selecttheme = '<select name=gfct_theme>';
    foreach($mythemes as $theme) {
        if (1 == $theme['active']){
    //foreach($mythemes as $name => $cssclass) {
            if ($currenttheme == $theme['cssclass']) {
            $selected = 'selected="selected"';;
             }
            else {
            $selected = '';
             }
	$selecttheme .= '<option value="'.$theme['cssclass'].'" '.$selected.'>'.$theme['name'].'</option>';
        }
    };
   $selecttheme .=  '</select>';
   return $selecttheme;
};
//add custom field to advanced settings
add_action("gform_field_advanced_settings", "gfct_standard_settings", 10, 2);
function gfct_standard_settings($position, $form_id){

    //create settings on position 25 (right after Field Label)
    if($position == 50){
        ?>
        <li class="gfctfa_setting field_setting">
            <label for="field_admin_label">
                <?php _e("GFCT FontAwesome", "gravityforms"); ?>
                <?php gform_tooltip("form_field_gfctfa_value") ?>
            </label>
            <input type="text" id="field_gfctfa_value" onkeyup="SetFieldProperty('gfctfaField' , this.value)" /> fa-user
        </li>
        <?php
    }
}
//adding field to editor
add_action("gform_editor_js", "gfct_editor_script");
function gfct_editor_script(){
    ?>
    <script type='text/javascript'>
        //adding setting to fields of type "text" and others
        fieldSettings["text"] += ", .gfctfa_setting";
        fieldSettings["address"] += ", .gfctfa_setting";
        fieldSettings["website"] += ", .gfctfa_setting";
        fieldSettings["phone"] += ", .gfctfa_setting";
        fieldSettings["number"] += ", .gfctfa_setting";
        fieldSettings["date"] += ", .gfctfa_setting";
        fieldSettings["time"] += ", .gfctfa_setting";
        fieldSettings["textarea"] += ", .gfctfa_setting";
        fieldSettings["name"] += ", .gfctfa_setting";
        fieldSettings["email"] += ", .gfctfa_setting";
        fieldSettings["post_title"] += ", .gfctfa_setting";
        fieldSettings["post_content"] += ", .gfctfa_setting";
        fieldSettings["post_tags"] += ", .gfctfa_setting";
        fieldSettings["post_category"] += ", .gfctfa_setting";
        fieldSettings["post_image"] += ", .gfctfa_setting";
        fieldSettings["post_custom_field"] += ", .gfctfa_setting";
        fieldSettings["post_tags"] += ", .gfctfa_setting";
         

        //binding to the load field settings event to initialize the checkbox
        jQuery(document).bind("gform_load_field_settings", function(event, field, form){
            jQuery("#field_gfctfa_value").val(field["gfctfaField"]);
        });
    </script>
    <?php
}

add_filter('gform_tooltips', 'addgfct_encryption_tooltips');
function addgfct_encryption_tooltips($tooltips){
   $tooltips["form_field_gfctfa_value"] = '<h6>Fontawesome icon</h6>Add your fontawesome icons, for example: </br>fa-envelope</br>fa-map-marker. Full list here: <a href="//fontawesome.io/">FontAwesome</a>';
   return $tooltips;
}
//add placeholder functionality to GF backend
add_filter('gform_form_settings', 'gfct_placeholder_setting', 10, 2);
function gfct_placeholder_setting($settings, $form) {
    $settings['Form Layout']['gfct_placeholder'] = '
        <tr>
            <th><label for="gfct_placeholder">GFCT - use placeholders instead of labels</label></th>
            <td><input type="checkbox" value="1"  '. checked( 1, rgar($form, 'gfct_placeholder'), false ) .'name="gfct_placeholder"> use placeholders?</td>
        </tr>';

    return $settings;
}

// save your custom form setting
add_filter('gform_pre_form_settings_save', 'save_gfct_placeholder_setting');
function save_gfct_placeholder_setting($form) {
    $form['gfct_placeholder'] = rgpost('gfct_placeholder');
    return $form;
}
//add placeholder to wrapper css