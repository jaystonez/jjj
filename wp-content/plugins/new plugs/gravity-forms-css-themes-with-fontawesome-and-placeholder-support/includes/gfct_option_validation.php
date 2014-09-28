<?php
// validate our options
function gfct_options_validate($input) {
    $options = get_option('gfct');
    //stylish
    //$themes = array('stylish_user','glass_user','rainbow_user','corporate_user');
        foreach($options as $name => $themearray){
            if (0 == strpos($name, 'user')){}else{  

    //foreach ($themes as $theme) {
        //$themeobject = $options[$theme];
                
                    $themeobject = $themearray;
                    $user = $name;
                    $slug = str_replace('_user', '', $theme);
                    $orig = $slug."_orig";
                    foreach($themeobject as $key => $value){
                        if (isset($input[$user][$key])) {
                            $options[$user][$key] = sanitize_text_field($input[$user][$key]);
                        }    
                    }
                    if (isset($input[$user]['reset']) AND 1 == sanitize_text_field($input[$user]['reset'])) {
                        foreach($themeobject as $key => $value){
                            $options[$user][$key] = $options[$orig][$key];
                        }
                    }
                }
        }     
//    if (isset($input['stylish_user']['label'])) {
//        $options['stylish_user']['label'] = sanitize_text_field($input['stylish_user']['label']);
//    }
//    if (isset($input['stylish_user']['buttonhover'])) {
//        $options['stylish_user']['buttonhover'] = sanitize_text_field($input['stylish_user']['buttonhover']);
//    }
//    if (isset($input['stylish_user']['button'])) {
//        $options['stylish_user']['button'] = sanitize_text_field($input['stylish_user']['button']);
//    }
//    if (isset($input['stylish_user']['facolor'])) {
//        $options['stylish_user']['facolor'] = sanitize_text_field($input['stylish_user']['facolor']);
//    }
//    if (isset($input['stylish_user']['background'])) {
//        $options['stylish_user']['background'] = sanitize_text_field($input['stylish_user']['background']);
//    }
//    if (isset($input['stylish_user']['opacity'])) {
//        $options['stylish_user']['opacity'] = sanitize_text_field($input['stylish_user']['opacity']);
//    }
//    if (isset($input['stylish_user']['reset']) AND 1 == sanitize_text_field($input['stylish_user']['reset'])) {
//        $options['stylish_user']['heading'] = $options['stylish_orig']['heading'];
//        $options['stylish_user']['label'] = $options['stylish_orig']['label'];
//        $options['stylish_user']['facolor'] = $options['stylish_orig']['facolor'];
//        $options['stylish_user']['buttonhover'] = $options['stylish_orig']['buttonhover'];
//        $options['stylish_user']['button'] = $options['stylish_orig']['button'];
//        $options['stylish_user']['background'] = $options['stylish_orig']['background'];
//        $options['stylish_user']['opacity'] = $options['stylish_orig']['opacity'];
//    }
//    //rainbow theme
//    if (isset($input['rainbow_user']['border'])) {
//        $options['rainbow_user']['border'] = sanitize_text_field($input['rainbow_user']['border']);
//    }
//    if (isset($input['rainbow_user']['label'])) {
//        $options['rainbow_user']['label'] = sanitize_text_field($input['rainbow_user']['label']);
//    }
//    if (isset($input['rainbow_user']['button'])) {
//        $options['rainbow_user']['button'] = sanitize_text_field($input['rainbow_user']['button']);
//    }
//    if (isset($input['rainbow_user']['reset']) AND 1 == sanitize_text_field($input['rainbow_user']['reset'])) {
//        //$options['rainbow_user']['rainbow_label'] = $options['rainbow_orig']['text_label'];
//        $options['rainbow_user']['label'] = $options['rainbow_orig']['label'];
//        $options['rainbow_user']['opacity'] = $options['rainbow_orig']['opacity'];
//    }
//    //glass theme
//    if (isset($input['glass_user']['opacity'])) {
//        $options['glass_user']['opacity'] = sanitize_text_field($input['glass_user']['opacity']);
//    }
//    if (isset($input['glass_user']['label'])) {
//        $options['glass_user']['label'] = sanitize_text_field($input['glass_user']['label']);
//    }
//    if (isset($input['glass_user']['reset']) AND 1 == sanitize_text_field($input['glass_user']['reset'])) {
//        //$options['glass_user']['glass_label'] = $options['glass_orig']['text_label'];
//        $options['glass_user']['label'] = $options['glass_orig']['label'];
//        $options['glass_user']['opacity'] = $options['glass_orig']['opacity'];
//    }
//    //corporate theme
//    //        'background' => '#f9f9f9',
////            'opacity' => '1',
////            'padding' => '15px',
////            'label' => '#666',
////            'button' => '#f0776c',
////            'facolor'=> 'grey',
////            'header'=> '#f1f1f1',
//    if (isset($input['corporate_user']['background'])) {
//        $options['corporate_user']['background'] = sanitize_text_field($input['corporate_user']['background']);
//    }
//    if (isset($input['corporate_user']['opacity'])) {
//        $options['corporate_user']['opacity'] = sanitize_text_field($input['corporate_user']['opacity']);
//    }
//    if (isset($input['corporate_user']['padding'])) {
//        $options['corporate_user']['padding'] = sanitize_text_field($input['corporate_user']['padding']);
//    }
//    if (isset($input['corporate_user']['label'])) {
//        $options['corporate_user']['label'] = sanitize_text_field($input['corporate_user']['label']);
//    }
//    if (isset($input['corporate_user']['button'])) {
//        $options['corporate_user']['button'] = sanitize_text_field($input['corporate_user']['button']);
//    }
//    if (isset($input['corporate_user']['facolor'])) {
//        $options['corporate_user']['facolor'] = sanitize_text_field($input['corporate_user']['facolor']);
//    }
//    if (isset($input['corporate_user']['header'])) {
//        $options['corporate_user']['header'] = sanitize_text_field($input['corporate_user']['header']);
//    }
//    if (isset($input['corporate_user']['reset']) AND 1 == sanitize_text_field($input['corporate_user']['reset'])) {
//        //$options['corporate_user']['corporate_label'] = $options['corporate_orig']['text_label'];
//        $options['corporate_user']['background'] = $options['corporate_orig']['background'];
//        $options['corporate_user']['header'] = $options['corporate_orig']['header'];
//        $options['corporate_user']['opacity'] = $options['corporate_orig']['opacity'];
//        $options['corporate_user']['label'] = $options['corporate_orig']['label'];
//        $options['corporate_user']['button'] = $options['corporate_orig']['button'];
//        $options['corporate_user']['padding'] = $options['corporate_orig']['padding'];
//        $options['corporate_user']['facolor'] = $options['corporate_orig']['facolor'];
//    }
    return $options;
};