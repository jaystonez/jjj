<?php // add the admin options page
add_action('admin_menu', 'gfct_corporate_admin_add_page');
function gfct_corporate_admin_add_page() {
//add_options_page('gfct Plugin Page', 'GF CSS Themes Plugin Menu', 'manage_options', 'gfct-corporate', 'gfct_corporate_options_page');
add_submenu_page( null, 'gfct corporate Page', 'GF CSS corporate Plugin Menu', 'manage_options', 'gfct-corporate', 'gfct_corporate_options_page' );
// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
}
?>
<?php // display the admin options page
function gfct_corporate_options_page() {
        ?>
        <div>
<!--        <h2>Corporate theme menu</h2>
        Edit how the theme is displayed-->
        <form action="options.php" method="post">
        <?php   settings_fields('gfct'); 
                 // settings_fields( $option_group )
                 //    A settings group name. This should match the group name used in register_setting(). 
                do_settings_sections('gfct-corporate'); 
                //the slug name of the page whose settings sections you want to output. This should match the page name used in add_settings_section(). ?>
        <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form>
        <?php gfct_theme_display('corporate') ?>
        </div>
        <?php
    }?>
<?php 
//            'background' => '#f9f9f9',
//            'opacity' => '1',
//            'padding' => '15px',
//            'label' => '#666',
//            'button' => '#f0776c',
//            'facolor'=> 'grey',
// add the admin settings and such
add_action('admin_init', 'gfct_corporate_admin_init');
function gfct_corporate_admin_init(){
        register_setting( 'gfct', 'gfct', 'gfct_options_validate' );
        //register_setting( $option_group, $option_name, $sanitize_callback );
        add_settings_section('gfct-corporate', 'Corporate Settings', 'gfct_corporate_section_text', 'gfct-corporate');
        //add_settings_section( $id, $title, $callback, $page )
        //add_settings_field( $id, $title, $callback, $page, $section, $args );
        add_settings_field('gfct_corporate_background', 'Background color', 'gfct_corporate_background', 'gfct-corporate', 'gfct-corporate');
        add_settings_field('gfct_corporate_opacity', 'Background opacity', 'gfct_corporate_opacity', 'gfct-corporate', 'gfct-corporate');
        add_settings_field('gfct_corporate_header', 'Header background color', 'gfct_corporate_header', 'gfct-corporate', 'gfct-corporate');
        add_settings_field('gfct_corporate_label', 'Label text color', 'gfct_corporate_label', 'gfct-corporate', 'gfct-corporate');
        add_settings_field('gfct_corporate_label', 'Label text color', 'gfct_corporate_label', 'gfct-corporate', 'gfct-corporate');
        add_settings_field('gfct_corporate_button', 'Button background', 'gfct_corporate_button', 'gfct-corporate', 'gfct-corporate');
        add_settings_field('gfct_corporate_facolor', 'FontAwesome color', 'gfct_corporate_facolor', 'gfct-corporate', 'gfct-corporate');
        add_settings_field('gfct_corporate_reset', 'Reset theme to default value', 'gfct_corporate_reset', 'gfct-corporate', 'gfct-corporate');
    }
function gfct_corporate_section_text() {
    echo '<p><a href="' . get_site_url() . '/wp-admin/options-general.php?page=gfct_global_settings">Back to Global Settings</a></br>You can change or reset theme colors for your theme</p>';
} 
function gfct_corporate_background() {
    gfct_theme_color('corporate','background');
}
function gfct_corporate_header() {
    gfct_theme_color('corporate','header');
}
function gfct_corporate_opacity() {
    gfct_opacity_dropdown('corporate');
}
function gfct_corporate_padding() {
    $options = get_option('gfct');
    //echo "<text id='corporate_padding' name='gfct[corporate_user][padding]' value=". (0 < strlen($options['corporate_user']['padding'])) ? $options['corporate_user']['padding'] : '' ."'/>";
    ?>
    <input type='text' id='corporate_padding' name='gfct[corporate_user][padding]' value='<?php if(0 < strlen($options['corporate_user']['padding'])){ echo $options['corporate_user']['padding'];}else{echo $options['corporate_orig']['padding'];};?>'/>
    <p>Use px,% or anything else (15px or 2%)</p>
        <?php
} 
function gfct_corporate_label() {
    gfct_theme_color('corporate','label');
}  
function gfct_corporate_button() {
    gfct_theme_color('corporate','button');
}  
function gfct_corporate_facolor() {
    gfct_theme_color('corporate','facolor');
}  
function gfct_corporate_reset() {
    $options = get_option('gfct');
     //isset( $options['corporate_background'] ) ?  $options['corporate_background'] : '';
       echo "<input type='checkbox' name='gfct[corporate_user][reset]' value='1'>";
} 
//options validated elsewhere