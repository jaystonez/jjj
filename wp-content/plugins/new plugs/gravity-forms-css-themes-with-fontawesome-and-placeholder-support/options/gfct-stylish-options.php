<?php // add the admin options page
add_action('admin_menu', 'gfct_admin_add_page');
function gfct_admin_add_page() {
//add_options_page('gfct Plugin Page', 'GF CSS Themes Plugin Menu', 'manage_options', 'gfct', 'gfct_options_page');
add_submenu_page( null, 'gfct Plugin Page', 'GF CSS Themes Plugin Menu', 'manage_options', 'gfct-stylish', 'gfct_options_page' );
// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
}
?>
<?php // display the admin options page
function gfct_options_page() {
        ?>
        <div>
<!--        <h2>Stylish theme menu</h2>-->
        <?php 
        $options = get_option('gfct');
       // var_dump($options['stylish_orig']['button']); ?>
<!--        Edit how the theme is displayed-->
        <form action="options.php" method="post">
        <?php   settings_fields('gfct'); 
                 // settings_fields( $option_group )
                 //    A settings group name. This should match the group name used in register_setting(). 
                do_settings_sections('gfct-stylish'); 
                //the slug name of the page whose settings sections you want to output. This should match the page name used in add_settings_section(). ?>
        <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form>
            <?php gfct_theme_display('stylish') ?>
        </div>
        <?php
    }?>
<?php 
// add the admin settings and such
add_action('admin_init', 'gfct_admin_init');
function gfct_admin_init(){
        register_setting( 'gfct', 'gfct', 'gfct_options_validate' );
        //register_setting( $option_group, $option_name, $sanitize_callback );
        add_settings_section('gfct_main', 'Main Settings', 'gfct_section_text', 'gfct-stylish');
        //add_settings_section( $id, $title, $callback, $page )
        //add_settings_field( $id, $title, $callback, $page, $section, $args );
        add_settings_field('gfct_stylish_heading', 'Heading Color', 'gfct_stylish_heading', 'gfct-stylish', 'gfct_main');
        add_settings_field('gfct_stylish_label', 'Label Color', 'gfct_stylish_label', 'gfct-stylish', 'gfct_main');
        add_settings_field('gfct_stylish_button', 'Button Color', 'gfct_stylish_button', 'gfct-stylish', 'gfct_main');
        add_settings_field('gfct_stylish_facolor', 'Font Awesome Color', 'gfct_stylish_facolor', 'gfct-stylish', 'gfct_main');
        add_settings_field('gfct_stylish_buttonhover', 'Button Hover Color', 'gfct_stylish_buttonhover', 'gfct-stylish', 'gfct_main');
        add_settings_field('gfct_stylish_background', 'Background Color', 'gfct_stylish_background', 'gfct-stylish', 'gfct_main');
        add_settings_field('gfct_stylish_opacity', 'Background Opacity', 'gfct_stylish_opacity', 'gfct-stylish', 'gfct_main');
        add_settings_field('gfct_stylish_reset', 'Reset theme to default value', 'gfct_stylish_reset', 'gfct-stylish', 'gfct_main');
    }
function gfct_section_text() {
    echo '<p><a href="' . get_site_url() . '/wp-admin/options-general.php?page=gfct_global_settings">Back to Global Settings</a></br>You can change or reset theme colors for your theme</p>';
} 
function gfct_stylish_heading() {
    gfct_theme_color('stylish','heading');
} 
function gfct_stylish_label() {
    gfct_theme_color('stylish','label');
}  
function gfct_stylish_facolor() {
    gfct_theme_color('stylish','facolor');
}  
function gfct_stylish_button() {
    gfct_theme_color('stylish','button');
} 
function gfct_stylish_buttonhover() {
    gfct_theme_color('stylish','buttonhover');
}  
function gfct_stylish_background() {
    gfct_theme_color('stylish','background');
}  
function gfct_stylish_reset() {
    $options = get_option('gfct');
     //isset( $options['stylish_background'] ) ?  $options['stylish_background'] : '';
       echo "<input type='checkbox' name='gfct[stylish_user][reset]' value='1'>";
}
function gfct_stylish_opacity() {
    gfct_opacity_dropdown('stylish');
} 