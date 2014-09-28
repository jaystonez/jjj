<?php
// add the admin options page
add_action('admin_menu', 'gfct_admin_global_add_page');

function gfct_admin_global_add_page() {
add_options_page('gfct Global Page', 'GF CSS  Global Options', 'manage_options', 'gfct_global_settings', 'gfct_admin_global_add_the_page');
// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
}
// display the admin options page

function gfct_admin_global_add_the_page() {
?>
<div>
    <form action="options.php" method="post">
        <?php
        settings_fields('gfct_global_settings');
        //Output nonce, action, and option_page fields for a settings page. Please note that this function must be called inside of the form tag for the options page. ;
        //  settings_fields( $option_group )
        do_settings_sections('gfct_global_settings');
        ?>

        <input name="Submit" type="submit" class="gfct_submit" value="<?php esc_attr_e('Save Changes'); ?>" />
    </form>
    <?php gfct_themes_display(); ?>
</div>
<?php }
// add the admin settings and such
add_action('admin_init', 'gfct_global_admin_init');

function gfct_global_admin_init() {
register_setting('gfct_global_settings', 'gfct_global_settings', 'gfct_global_settings_validate');
//register_setting( $option_group, $option_name, $sanitize_callback );
add_settings_section('gfct_global_main', '<i class="fa fa-cogs"></i>Global Settings</div>', 'gfct_global_section_text', 'gfct_global_settings');
//add_settings_section( $id, $title, $callback, $page );
//add_settings_field('rg_gforms_disable_css', 'Disable Gravity Forms Css', 'rg_gforms_disable_css', 'gfct_global_settings', 'gfct_global_main');
add_settings_field('use_fontawesome', 'FontAwesome Icons', 'use_fontawesome', 'gfct_global_settings', 'gfct_global_main');
add_settings_field('enqueue_fontawesome', 'Enqueue FontAwesome icons', 'enqueue_fontawesome', 'gfct_global_settings', 'gfct_global_main');
add_settings_field('activate_themes', 'Activate these themes', 'activate_themes', 'gfct_global_settings', 'gfct_global_main');
//add_settings_field( $id, $title, $callback, $page, $section, $args );
}

function gfct_global_section_text() {
    $options = get_option('gfct_global_settings');
//display links to active themes options
echo '<p>This is the option page for the Gravity Forms CSS Themes. You can edit global options or go to theme options of your specific theme. The theme needs to be activated to access it\'s options:</br>';
    
    foreach ($options['themes'] as $theme){
        if (1 ==$theme['active'] AND 'no_theme' != $theme['slug'] ){
            echo '<a href="' . get_site_url() . '/wp-admin/options-general.php?page=gfct-'.$theme['slug'].'">'.ucfirst($theme['name']).' Theme</a></br>';
        }
    }
    echo    '<a href="http://gravity-forms-css-themes-fontawesome-placeholders.com/">Dark Theme - Full  Version</a></br>
             <a href="http://gravity-forms-css-themes-fontawesome-placeholders.com/">Modern Theme - Full  Version</a></br>
             <a href="http://gravity-forms-css-themes-fontawesome-placeholders.com/">Orange Theme - Full  Version</a></br>
             <a href="http://gravity-forms-css-themes-fontawesome-placeholders.com/">Rainbow Theme - Full  Version</a></br>
             <a href="http://gravity-forms-css-themes-fontawesome-placeholders.com/">Social Theme - Full  Version</a></br>';
    echo '</br>Assigning specific FontAwesome icons, assigning theme to specific form and selecting the placeholder is done seamlessly within the Gravity Forms plugin. Edit your <b>form settings/form fields</b> to see the options.';
    echo'</p>';
//var_dump($options);
//echo '$options["fontawesome"]'.$options["fontawesome"];
}

function rg_gforms_disable_css() {
//var_dump(get_option('rg_gforms_disable_css'));
$offcss = get_option('gfct_global_settings');
//echo $offcss['disable_css'];
if ('1' == $offcss['disable_css']) {
echo '<input type="radio" name="gfct_global_settings[disable_css]" value="0">Enable Gravity Forms Default css output</br>
             <input type="radio" name="gfct_global_settings[disable_css]" value="1" checked="checked" >Disable Css (not recommended)';
} elseif ('0' == $offcss['disable_css'] or!isset($options['disable_css'])) {
echo '<input type="radio" name="gfct_global_settings[disable_css]" value="0" checked="checked">Enable Gravity Forms Default css output</br>
             <input type="radio" name="gfct_global_settings[disable_css]" value="1" >Disable Css (not recommended)';
}
}
function enqueue_fontawesome() {
    $options = get_option('gfct_global_settings');
    //echo $options['fontawesome'];
    if ('0' == $options['enqueue_fontawesome']) {
    echo '<input type="radio" name="gfct_global_settings[enqueue_fontawesome]" value="1">GFCT enqueues FontAwesome- makes sense if you are using the option</br>
                 <input type="radio" name="gfct_global_settings[enqueue_fontawesome]" value="0" checked="checked" >FontAwesome already enqueued by theme or other plugin';
    } elseif ('1' == $options['enqueue_fontawesome'] or!isset($options['enqueue_fontawesome'])) {
    echo '<input type="radio" name="gfct_global_settings[enqueue_fontawesome]" value="1" checked="checked">GFCT enqueues FontAwesome- makes sense if you are using the option</br>
                 <input type="radio" name="gfct_global_settings[enqueue_fontawesome]" value="0" >FontAwesome already enqueued by theme or other plugin';
    }
}
function use_fontawesome() {
    $options = get_option('gfct_global_settings');
    //echo $options['fontawesome'];
    if ('0' == $options['fontawesome']) {
    echo '<input type="radio" name="gfct_global_settings[fontawesome]" value="1">Enable FontAwesome icons for Gravity Forms</br>
                 <input type="radio" name="gfct_global_settings[fontawesome]" value="0" checked="checked" >Disable this';
    } elseif ('1' == $options['fontawesome'] or!isset($options['fontawesome'])) {
    echo '<input type="radio" name="gfct_global_settings[fontawesome]" value="1" checked="checked">Enable FontAwesome icons for Gforms</br>
                 <input type="radio" name="gfct_global_settings[fontawesome]" value="0" >Disable this (not recommended)';
    }
}
function activate_themes(){
    $options = get_option('gfct_global_settings');
    $code = '';
    $code .= '<input type="hidden" name="gfct_global_settings[themes][no_theme][active]" value="1" />';
    foreach($options['themes'] as $theme ){
        if ('no_theme' != $theme['slug']){
                $code .='<input type="checkbox" name="gfct_global_settings[themes]['.$theme["slug"].'][active]" value="1"' . checked( 1, $theme["active"], false ) . '/>'.$theme["name"].'</br>';
        }
    }
    echo $code;
}

// validate our options
function gfct_global_settings_validate($input) {
    //var_dump($input);
    $options = get_option('gfct_global_settings');
    $options['disable_css'] = sanitize_text_field($input['disable_css']);
    $options['fontawesome'] = sanitize_text_field($input['fontawesome']);
    $options['enqueue_fontawesome'] = sanitize_text_field($input['enqueue_fontawesome']);
    foreach($options['themes'] as $theme ){
    $options['themes'][$theme["slug"]]['active'] = sanitize_text_field($input['themes'][$theme["slug"]]['active']);
    };
    return $options;
}
//update_option ( 'rg_gforms_disable_css', '1' );