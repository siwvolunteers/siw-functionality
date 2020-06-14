<?php

namespace SIW\Compatibility;

/**
 * Aanpassingen voor UpdraftPlus
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @see       https://updraftplus.com/
 * @since     3.0.0
 */
class UpdraftPlus {

	/**
	 * Tijdstip backup database
	 *
	 * @var string
	 */
	const TS_BACKUP_DB = '04:00';

	/**
	 * Tijdstip backup bestanden
	 *
	 * @var string
	 */
	const TS_BACKUP_FILES = '04:30';

	/**
	 * Init
	 */
	public static function init() {

		if ( ! class_exists( '\UpdraftPlus' ) ) {
			return;
		}
		$self = new self();
		add_filter( 'updraftplus_schedule_firsttime_db', [ $self, 'set_time_db_backup'] ) ;
		add_filter( 'updraftplus_schedule_firsttime_files', [ $self, 'set_time_files_backup'] ) ;
		add_filter( 'updraftplus_blog_name', [ $self, 'set_backup_name' ] );
		add_action( 'admin_init', [ $self, 'hide_woocommerce_in_plugin_update_message'], PHP_INT_MAX );

		//Verbergt diverse UpdraftPlus notificaties
		define( 'UPDRAFTPLUS_NOADS_B', true );
		define( 'UPDRAFTPLUS_NONEWSFEED', true );
		define( 'UPDRAFTPLUS_ADMINBAR_DISABLE', true );
		define( 'UPDRAFTPLUS_DISABLE_WP_CRON_NOTICE', true );
	}

	/**
	 * Zet de tijd voor de database backup
	 *
	 * @param int $scheduled_time
	 * @return int
	 */
	public function set_time_db_backup( int $scheduled_time ) : int {
		$tomorrow = strtotime( 'tomorrow' );
		$backup_db_day = date( 'Y-m-d', max( $scheduled_time, $tomorrow ) );
	
		return strtotime( $backup_db_day . ' ' . self::TS_BACKUP_DB . wp_timezone_string() );
	}

	/**
	 * Zet de tijd voor de bestandsbackup
	 *
	 * @param int $scheduled_time
	 * @return int
	 */
	public function set_time_files_backup( int $scheduled_time ) : int {
		$tomorrow = strtotime( 'tomorrow' );
		$backup_files_day = date( 'Y-m-d', max( $scheduled_time, $tomorrow ) );
		return strtotime( $backup_files_day . ' ' . self::TS_BACKUP_FILES . wp_timezone_string() );
	}

	/**
	 * Voegt site-url toe aan backupnaam
	 *
	 * @return string
	 */
	public function set_backup_name() : string {
		return sanitize_title( SIW_SITE_NAME );
	}

	/**
	 * Verbergt melding bij WooCommerce plugin updates
	 */
	public function hide_woocommerce_in_plugin_update_message() {
		global $updraftplus_admin;
		remove_filter('woocommerce_in_plugin_update_message', [ $updraftplus_admin, 'woocommerce_in_plugin_update_message' ] );
	}
}
