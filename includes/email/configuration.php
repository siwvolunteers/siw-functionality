<?php
/**
 * Configuratie van e-mail
 * 
 * - SMTP
 * - DKIM
 * 
 * @package    SIW\Email
 * @author     Maarten Bruna
 * @copyright  2017-2018 SIW Internationale Vrijwilligersprojecten
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
//TODO: namespace + functies of class
add_action( 'phpmailer_init', function ( $phpmailer ) {

	/*SMTP-configuratie*/
	if ( siw_get_setting( 'smtp_enabled' ) ) {
		$phpmailer->isSMTP();
		$phpmailer->Host = siw_get_setting( 'smtp_host' );
		$phpmailer->Port = siw_get_setting( 'smtp_port');
		$phpmailer->SMTPAuth = (bool) siw_get_setting( 'smtp_authentication' );
		$phpmailer->Username = siw_get_setting( 'smtp_credentials' )['username'];
		$phpmailer->Password = siw_get_setting( 'smtp_credentials' )['password'];
		$phpmailer->SMTPSecure = siw_get_setting( 'smtp_encryption');
		$phpmailer->Sender = $phpmailer->From;
	}

	/*DKIM*/
	if ( ! defined( 'SIW_DKIM_KEY' ) ) {
		define( 'SIW_DKIM_KEY', false );
	}

	if ( siw_get_setting( 'dkim_enabled' ) && SIW_DKIM_KEY ) {
		$phpmailer->DKIM_selector = siw_get_setting( 'dkim_selector' );
		$phpmailer->DKIM_domain = siw_get_setting( 'dkim_domain' );
		$phpmailer->DKIM_identity = $phpmailer->From;
		$phpmailer->DKIM_passphrase = siw_get_setting( 'dkim_passphrase' );
		$phpmailer->DKIM_private_string = SIW_DKIM_KEY;
	}
}, 999 );


/* Standaard afzender-adres aanpassen */
add_filter( 'wp_mail_from', function( $from ) {
	$sitename = strtolower( $_SERVER['SERVER_NAME'] );
	if ( substr( $sitename, 0, 4 ) == 'www.' ) {
		$sitename = substr( $sitename, 4 );
	}
	$default_from = 'wordpress@' . $sitename;

	if ( $from != $default_from ) {
		return $from;
	}

	return SIW_EMAIL;
});


/* Standaard afzender-naam aanpassen */
add_filter( 'wp_mail_from_name', function( $from_name ) {

	$default_from_name = 'WordPress';
	if ( $from_name != $default_from_name ) {
		return $from_name;
	}

	return SIW_NAME;
});
