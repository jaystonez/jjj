<?php
function gfct_oldornew($uservalue , $originalvalue){
    if(0 < strlen($uservalue)){
        $value = $uservalue;}
    else { $value = $originalvalue;}
    return $value;
}
//used to populate backend fields in theme options
function gfct_theme_color($theme, $elementid) {
    $options = get_option('gfct');
    $uservalue = $options[$theme."_user"][$elementid];
    $originalvalue = $options[$theme."_orig"][$elementid] ;
    $value = gfct_oldornew($uservalue , $originalvalue);
    
       echo "<input id='". $theme."_".$elementid ."' name='gfct[". $theme."_user][$elementid]' size='40' type='text' value='{$value }' class='gfct-color-field' />";
} 
function gfct_iris_admin_enqueue_scripts($hook) {
        // bail early if this is not the plugin option screen
       // if( 'settings_page_gfct' !== $hook ) {
        if( strpos($hook, 'gfct' ) == FALSE ) {
            return;
        }
        wp_enqueue_script( 'wp-color-picker' );
        // load the minified version of custom script
        wp_enqueue_script( 'gfct-iris',  gfct__PLUGIN_URL .'js/gfct-iris.js', array( 'jquery', 'wp-color-picker' ), '1.1', true );
        wp_enqueue_style( 'wp-color-picker' );
        //wp_enqueue_script('jquery');
        //wp_enqueue_script('jquery-ui-core');
    }
add_action( 'admin_enqueue_scripts', 'gfct_iris_admin_enqueue_scripts' );

//add custom admin css
function gfct_admin_css($hook) {
        if( strpos($hook, 'gfct' ) == FALSE ) {
            return;
        }
    wp_enqueue_style( 'gfct_admin',  gfct__PLUGIN_URL .'/css/gfct_admin.css' );
    wp_enqueue_style( 'font-awesome-css', '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css', array(), '4.0.3' );
}
add_action( 'admin_enqueue_scripts', 'gfct_admin_css' );
function gfct_border_select($theme){
     $options = get_option('gfct');
     $user = $theme.'_user';
       echo "<select id='". $theme ."_border' name='gfct[". $user ."][border]'/>";
           $desc = 0;
       while (17 > strval($desc)){
           if (strval($desc) == gfct_theme_color2($theme,'border')){
               $selected = 'selected="selected"';
           }
           else {
               $selected = '';
           }
           echo '<option value='.$desc.' '. $selected .'>'.$desc.'</option>';
           $desc++;
       }
            echo '</select>';   
}
function gfct_opacity_dropdown($themename){
    $options = get_option('gfct');
       echo "<select id='". $themename ."_opacity' name='gfct[". $themename ."_user][opacity]'/>";
           $desc = 1;
       while (strval('-.01') <= strval($desc)){
           if (strval($desc) == gfct_theme_color2($themename,'opacity')){
               $selected = 'selected="selected"';
           }
           else {
               $selected = '';
           }
           echo '<option value='.$desc.' '. $selected .'>'.$desc.'</option>';
           $desc = round($desc - .05,2);
       }
            echo '</select>';
};
function gfct_themes_display(){
     $options = get_option('gfct_global_settings');
//display images of the themes
      echo '<div class="gfct_themes">';
    foreach ($options['themes'] as $theme){
        if ('no_theme' != $theme['slug'] AND 1 == $theme['active']){?>
            <div class="gfct_themebox">
                <a href="<?php echo get_site_url() . '/wp-admin/options-general.php?page=gfct-'.$theme['slug'] ?>">
                    <img class="gfct_theme" src="<?php echo gfct__PLUGIN_URL .'images/'. $theme['slug']; ?>.jpg">
                </a>
                <h2><?php echo ucfirst($theme['name']) ?></h2>
            </div>
          <?php  
        }
    }
    echo '  <div class="gfct_themebox">
                <a href="http://gravity-forms-css-themes-fontawesome-placeholders.com/">
                    <img src=" '. gfct__PLUGIN_URL .'images/dark.jpg" class="gfct_theme">
                </a>
                <h2>Dark</h2>
            </div>
            <div class="gfct_themebox">
                <a href="http://gravity-forms-css-themes-fontawesome-placeholders.com/">
                    <img src=" '. gfct__PLUGIN_URL .'images/modern.jpg" class="gfct_theme">
                </a>
                <h2>Modern</h2>
            </div>
            <div class="gfct_themebox">
                <a href="http://gravity-forms-css-themes-fontawesome-placeholders.com/">
                    <img src=" '. gfct__PLUGIN_URL .'images/orange.jpg" class="gfct_theme">
                </a>
                <h2>Orange</h2>
            </div>
            <div class="gfct_themebox">
                <a href="http://gravity-forms-css-themes-fontawesome-placeholders.com/">
                    <img src=" '. gfct__PLUGIN_URL .'images/rainbow.jpg" class="gfct_theme">
                </a>
                <h2>Rainbow</h2>
            </div>
            <div class="gfct_themebox">
                <a href="http://gravity-forms-css-themes-fontawesome-placeholders.com/">
                    <img src=" '. gfct__PLUGIN_URL .'images/social.jpg" class="gfct_theme">
                </a>
                <h2>Social</h2>
            </div>';
    echo '</div>'; 
}
function gfct_theme_display($theme){
     $options = get_option('gfct_global_settings');?>
        <div class="gfct_themes">
            <div class="gfct_themebox">
                    <img class="gfct_theme" src="<?php echo  gfct__PLUGIN_URL . 'images/'. $options['themes'][$theme]['slug']; ?>.jpg">
                <h2><?php echo ucfirst($options['themes'][$theme]['name']) ?></h2>
            </div>'
          <?php  
    echo '</div>'; 
}