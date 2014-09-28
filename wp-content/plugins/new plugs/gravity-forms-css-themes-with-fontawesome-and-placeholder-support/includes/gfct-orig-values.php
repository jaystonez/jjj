<?php
//these are the initial values for the GFCT Plugin
function create_gfct_settings() {
    $values = array(
        'stylish_orig' => array(
            'button' => '#008fd5',
            'facolor' => '#008fd5',
            'background' => '#4DB2E0',
            'heading' => '#ffffff',
            'label' => '#ffffff',
            'buttonhover' => '#004fed',
            'opacity' => '.7',
        ),
        'stylish_user' => array(
            'buttonhover' => '',
            'heading' => '',
            'facolor' => '',
            'label' => '',
            'button' => '',
            'background' => '',
            'opacity' => '',
        ),
        'corporate_orig' => array(
            'background' => '#f9f9f9',
            'opacity' => '1',
            'padding' => '15px',
            'label' => '#666',
            'button' => '#f0776c',
            'facolor'=> 'grey',
            'header'=> '#f1f1f1',
        ),
        'corporate_user' => array(
            'background' => '',
            'opacity' => '',
            'padding' => '',
            'label' => '',
            'button' => '',
            'facolor'=> '',
            'header'=> '',
        )
    );
    add_option('gfct', $values);
    //update_option ( 'gfct', $values );
}
//create_gfct_settings();
function create_gfct_global_settings() {
    $values = array(
        'disable_css' => '0',
        'fontawesome' => '1',
        'enqueue_fontawesome' => '1',
        'themes'=>array(
    'no_theme' => array(
            'name' => 'no theme',
            'slug' => 'no_theme',
            'cssclass' => '',
            'active' => '1',
        ),
    'stylish' => array(
            'name' => 'stylish',
            'slug' => 'stylish',
            'cssclass' => 'gfct_stylish',
            'active' => '1',
        ),
    'corporate' => array(
            'name' => 'corporate',
            'slug' => 'corporate',
            'cssclass' => 'gfct_corporate',
            'active' => '1',
        )
    )
    );
    add_option('gfct_global_settings', $values);
//update_option('gfct_global_settings', $values);
};
//create_gfct_global_settings();