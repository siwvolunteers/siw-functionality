<?php

/**
 * Aanpassingen voor UpdraftPlus
 * 
 * @package     SIW\Compatibility
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 * 
 * @uses        SIW_Util
 * @uses        SIW_Properties
 */

class SIW_Compat_UpdraftPlus {

	/**
	 * Init
	 */
	public static function init() {

		if ( ! class_exists( 'UpdraftPlus' ) ) {
			return;
		}
		$self = new self();
		add_filter( 'updraftplus_schedule_firsttime_db', [ $self, 'set_time_db_backup'] ) ;
		add_filter( 'updraftplus_schedule_firsttime_files', [ $self, 'set_time_files_backup'] ) ;
		add_filter( 'updraftplus_blog_name', [ $self, 'set_backup_name' ] );
		add_action( 'admin_init', [ $self, 'hide_woocommerce_in_plugin_update_message'], PHP_INT_MAX );

		$self->hide_notifications();
	}

	/**
	 * Zet de tijd voor de database backup
	 *
	 * @param int $scheduled_time
	 * @return int
	 */
	public function set_time_db_backup( int $scheduled_time ) {
		$tomorrow = strtotime( 'tomorrow' );
		$backup_db_day = date( 'Y-m-d', max( $scheduled_time, $tomorrow ) );
	
		$backup_db_ts = strtotime( $backup_db_day . ' ' . SIW_Properties::TS_BACKUP_DB );
		$backup_db_ts_gmt = SIW_Util::convert_timestamp_to_gmt( $backup_db_ts );
	
		return $backup_db_ts_gmt;
	}

	/**
	 * Zet de tijd voor de bestandsbackup
	 *
	 * @param int $scheduled_time
	 * @return int
	 */
	public function set_time_files_backup( int $scheduled_time ) {
		$tomorrow = strtotime( 'tomorrow');
		$backup_files_day = date( 'Y-m-d', max( $scheduled_time, $tomorrow ) );
	
		$backup_files_ts = strtotime( $backup_files_day . ' ' . SIW_Properties::TS_BACKUP_FILES );
		$backup_files_ts_gmt = SIW_Util::convert_timestamp_to_gmt( $backup_files_ts );
	
		return $backup_files_ts_gmt;
	}

	/**
	 * Voegt site-url toe aan backupnaam
	 *
	 * @param string $blog_name
	 * @return string
	 */
	public function set_backup_name( string $blog_name ) {
		$blog_name = sanitize_title( SIW_SITE_NAME );
		return $blog_name;
	}

	/**
	 * Verbergt diverse UpdraftPlus notificaties
	 */
	public function hide_notifications() {
		define( 'UPDRAFTPLUS_NOADS_B', true );
		define( 'UPDRAFTPLUS_NONEWSFEED', true );
		define( 'UPDRAFTPLUS_ADMINBAR_DISABLE', true );
		define( 'UPDRAFTPLUS_DISABLE_WP_CRON_NOTICE', true );
	}

	/**
	 * Verbergt melding bij WooCommerce plugin updates
	 */
	public function hide_woocommerce_in_plugin_update_message() {
		global $updraftplus_admin;
		remove_filter('woocommerce_in_plugin_update_message', [ $updraftplus_admin, 'woocommerce_in_plugin_update_message' ] );
	}
}