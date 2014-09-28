<?php
//uninstall function of the plugin
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

delete_option( 'gfct' );
delete_option( 'gfct_themes' );
delete_option( 'gfct_global_settings' );

// For site options in multisite
delete_site_option( 'gfct' );  
delete_site_option( 'gfct_themes' );
delete_site_option( 'gfct_global_settings' );

