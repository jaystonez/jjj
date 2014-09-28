<?php
/*
	Pre-populated variables coming into this script:
		$destination_settings
		$mode
*/

global $pb_hide_test, $pb_hide_save;
$pb_hide_test = false;
$default_name = NULL;

set_include_path( pb_backupbuddy::plugin_path() . '/destinations/gdrive/' . PATH_SEPARATOR . get_include_path());

if ( 'add' == $mode ) {
	$default_name = 'My Email';
	
	require_once( pb_backupbuddy::plugin_path() . '/destinations/gdrive/Google/Client.php' );
	require_once( pb_backupbuddy::plugin_path() . '/destinations/gdrive/Google/Http/MediaFileUpload.php' );
	require_once( pb_backupbuddy::plugin_path() . '/destinations/gdrive/Google/Service/Drive.php' );
	
	$client_id = '';
	$client_secret = '';
	$redirect_uri = '';

	$client = new Google_Client();
	$client->setClientId($client_id);
	$client->setClientSecret($client_secret);
	$client->setRedirectUri($redirect_uri);
	$client->addScope("https://www.googleapis.com/auth/drive");
	$service = new Google_Service_Drive($client);
	
	echo 'url: ' . $client->createAuthUrl();
	
	
	return;
}
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'title',
	'title'		=>		__( 'Destination name', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( 'Name of the new destination to create. This is for your convenience only.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|string[1-45]',
	'default'	=>		$default_name,
) );

$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'address',
	'title'		=>		__( 'Email address', 'it-l10n-backupbuddy' ),
	'tip'		=>		__( '[Example: your@email.com] - Email address for this destination.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|email',
) );
