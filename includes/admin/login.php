<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Aanpassen login-scherm
 * - Styling
 * - Link en title van logo
 * - Welkomstboodschap
 * - Verwijderen shake-animatie
 */
add_action( 'login_enqueue_scripts', function() {
	wp_enqueue_style( 'siw-login-css', SIW_ASSETS_URL . 'css/siw-login.css', array(), SIW_PLUGIN_VERSION );
} );

add_filter( 'login_headerurl', function() {
	return get_home_url( '', '', 'http' );
} );

add_filter( 'login_headertitle', function() {
	return esc_html__( SIW_NAME, 'siw' );
} );

add_filter( 'login_message', function( $message ) {
	if ( empty( $message ) ) {
		return '<p class="message">' . esc_html__( 'Welkom bij SIW. Log in om verder te gaan.', 'siw' ) . '</p>';
	} else {
		return $message;
	}
} );

add_action( 'login_head', function() {
	remove_action( 'login_head', 'wp_shake_js', 12 );
} );

/* IP whitelist voor plugin 'Limit Login Attempts' */
add_filter( 'limit_login_whitelist_ip', function( $allow, $ip ) {
	$ip_whitelist = siw_get_ip_whitelist();
	if ( in_array( $ip, $ip_whitelist ) ) {
		$allow = true;
	}
	return $allow;
}, 99, 2 );


/*
 * Meeste recente login van gebruiker:
 * - Bijhouden
 * - Tonen op adminscherm
 */
add_action( 'wp_login', function( $user_login, $user ) {
	update_user_meta( $user->ID, 'last_login', current_time( 'mysql' ) );
}, 10, 2 );

add_filter( 'manage_users_columns', function( $columns ) {
	$columns['lastlogin'] = __( 'Laatste login', 'siw' );
	return $columns;
} );

add_action( 'manage_users_custom_column', function( $value, $column_name, $user_id ) {
	if ( 'lastlogin' == $column_name ) {
		$last_login = get_user_meta( $user_id, 'last_login', true );
		if ( ! empty( $last_login ) ) {
			$time = mysql2date( 'H:i', $last_login, false );
			$date = siw_get_date_in_text( mysql2date( 'Y-m-d', $last_login, false ), true );
			$value = $date . ' ' . $time;
		}
		else {
			$value = __( 'Nog nooit ingelogd', 'siw' );
		}
	}
	return $value;
}, 10, 3 );


/*
 * Functies voor "Password protected" plugin
 * - Welkomstboodschap
 * - Verwijderen shake-animatie
 * - IP whitelist voor directe toegang tot site
 */
add_action( 'password_protected_before_login_form', function() {
	$site_url = '//www.siw.nl';
	?>
	<p class='message'>
	<b><?php esc_html_e( 'Welkom op de testsite van SIW.','siw' )?></b><br />
	<?php esc_html_e( 'Voer het wachtwoord in om toegang te krijgen.', 'siw' )?><br /><br />
	<?php printf( wp_kses_post( __( 'Klik <a href="%s">hier</a> om naar de echte website van SIW te gaan.', 'siw' ) ), esc_url($site_url) );?>
	</p>
<?php
} );

add_action( 'password_protected_login_head', function() {
	remove_action( 'password_protected_login_head', 'wp_shake_js', 12);
} );

add_filter( 'password_protected_is_active', function( $is_active ) {
	$ip_whitelist = siw_get_ip_whitelist();
	if ( in_array( $_SERVER['REMOTE_ADDR'], $ip_whitelist ) ) {
		$is_active = false;
	}
	return $is_active;
} );
