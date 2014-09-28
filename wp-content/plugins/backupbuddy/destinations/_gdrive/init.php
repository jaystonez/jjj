<?php

// DO NOT CALL THIS CLASS DIRECTLY. CALL VIA: pb_backupbuddy_destination in bootstrap.php.

// As of BackupBuddy v5.0 by Dustin Bolton.
class pb_backupbuddy_destination_gdrive {
	
	public static $destination_info = array(
		'name'			=>		'Google Drive [BETA]',
		'description'	=>		'Send files to Google Drive. <a href="https://drive.google.com" target="_new">Learn more here.</a>',
	);
	
	// Default settings. Should be public static for auto-merging.
	public static $default_settings = array(
		'type'				=>		'gdrive',	// MUST MATCH your destination slug.
		'title'				=>		'',			// Required destination field.
		'client_id'			=>		'',
		'client_secret'		=>		'',
		'access_token'		=>		'',
		'directory'			=>		'',
		'archive_limit'		=>		0,
	);
	
	
	
	/*	send()
	 *	
	 *	Send one or more files.
	 *	
	 *	@param		array			$files		Array of one or more files to send.
	 *	@return		boolean						True on success, else false.
	 */
	public static function send( $settings = array(), $files = array(), $send_id = '' ) {
				
		$email = $settings['address'];
		
		if ( pb_backupbuddy::$options['email_return'] != '' ) {
			$email_return = pb_backupbuddy::$options['email_return'];
		} else {
			$email_return = get_option('admin_email');
		}
		
		pb_backupbuddy::status( 'details',  'Sending remote email.' );
		$headers = 'From: BackupBuddy <' . $email_return . '>' . "\r\n";
		$wp_mail_result = wp_mail( $email, 'BackupBuddy backup for ' . site_url(), 'BackupBuddy backup for ' . site_url(), $headers, $files );
		pb_backupbuddy::status( 'details',  'Sent remote email.' );
		
		if ( $wp_mail_result === true ) { // WP sent. Hopefully it makes it!
			return true;
		} else { // WP couldn't try to send.
			return false;
		}
		
	} // End send().
	
	
	
	/*	test()
	 *	
	 *	Sends a text email with ImportBuddy.php zipped up and attached to it.
	 *	
	 *	@param		array			$settings	Destination settings.
	 *	@return		bool|string					True on success, string error message on failure.
	 */
	public static function test( $settings ) {
		
		$email = $settings['address'];
		
		pb_backupbuddy::status( 'details', 'Testing email destination. Sending ImportBuddy.php.' );
		pb_backupbuddy::anti_directory_browsing( backupbuddy_core::getTempDirectory(), $die = false );
		$importbuddy_temp = backupbuddy_core::getTempDirectory() . 'importbuddy_' . pb_backupbuddy::random_string( 10 ) . '.php.tmp'; // Full path & filename to temporary importbuddy
		backupbuddy_core::importbuddy( $importbuddy_temp ); // Create temporary importbuddy.
		
		$files = array( $importbuddy_temp );
		
		if ( pb_backupbuddy::$options['email_return'] != '' ) {
			$email_return = pb_backupbuddy::$options['email_return'];
		} else {
			$email_return = get_option('admin_email');
		}
		
		$headers = 'From: BackupBuddy <' . $email_return . '>' . "\r\n";
		$wp_mail_result = wp_mail( $email, 'BackupBuddy Test', 'BackupBuddy destination test for ' . site_url(), $headers, $files );
		pb_backupbuddy::status( 'details',  'Sent test email.' );
		
		@unlink( $importbuddy_temp );
		
		if ( $wp_mail_result === true ) { // WP sent. Hopefully it makes it!
			return true;
		} else { // WP couldn't try to send.
			echo 'WordPress was unable to attempt to send email. Check your WordPress & server settings.';
			return false;
		}
		
	} // End test().
	
	
} // End class.