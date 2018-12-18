


<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Aanpassingen voor UpdraftPlus
 * 
 * @package     SIW\Compatibility
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 * 
 * @uses        siw_get_timestamp_in_gmt()
 */

class SIW_UpdraftPlus {

	/**
	 * Init
	 *
	 * @return void
	 */
	public static function init() {

		if ( ! class_exists( 'UpdraftPlus' ) ) {
			return;
		}
		$self = new self();
		add_filter( 'updraftplus_schedule_firsttime_db', [ $self, 'set_time_db_backup'] ) ;
		add_filter( 'updraftplus_schedule_firsttime_files', [ $self, 'set_time_files_backup'] ) ;
		add_filter( 'updraftplus_blog_name', [ $self, 'set_backup_name' ] );
		$self->hide_notifications();

	}

	/**
	 * Zet de tijd voor de database backup
	 *
	 * @param int $scheduled_time
	 * @return int
	 */
	public function set_time_db_backup( $scheduled_time ) {
		/*
		* Instellen starttijd Updraft Plus backup
		* - Database
		* - Bestanden
		*/
		$tomorrow = strtotime( 'tomorrow' );
		$backup_db_day = date( 'Y-m-d', max( $scheduled_time, $tomorrow ) );
	
		$backup_db_ts = strtotime( $backup_db_day . ' ' . SIW_CRON_TS_BACKUP_DB );
		$backup_db_ts_gmt = siw_get_timestamp_in_gmt( $backup_db_ts );
	
		return $backup_db_ts_gmt;
	}

	/**
	 * Zet de tijd voor de bestandsbackup
	 *
	 * @param int $scheduled_time
	 * @return int
	 */
	public function set_time_files_backup( $scheduled_time ) {
		$tomorrow = strtotime( 'tomorrow');
		$backup_files_day = date( 'Y-m-d', max( $scheduled_time, $tomorrow ) );
	
		$backup_files_ts = strtotime( $backup_files_day . ' ' . SIW_CRON_TS_BACKUP_FILES );
		$backup_files_ts_gmt = siw_get_timestamp_in_gmt( $backup_files_ts );
	
		return $backup_files_ts_gmt;
	}

	/**
	 * Voegt site-url toe aan backupnaam
	 *
	 * @param string $blog_name
	 * @return string
	 */
	public function set_backup_name( $blog_name ) {
		$blog_name = sanitize_title( SIW_SITE_NAME );
		return $blog_name;
	}

	/**
	 * Verbergt diverse UpdraftPlus notificaties
	 *
	 * @return void
	 */
	public function hide_notifications() {
		define( 'UPDRAFTPLUS_NOADS_B', true );
		define( 'UPDRAFTPLUS_NONEWSFEED', true );
		define( 'UPDRAFTPLUS_ADMINBAR_DISABLE', true );
		define( 'UPDRAFTPLUS_DISABLE_WP_CRON_NOTICE', true );
	}

}