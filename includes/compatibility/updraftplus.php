<?php declare(strict_types=1);

namespace SIW\Compatibility;

/**
 * Aanpassingen voor UpdraftPlus
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://updraftplus.com/
 */
class UpdraftPlus {

	/** Tijdstip backup database */
	const TS_BACKUP_DB = '04:00';

	/** Tijdstip backup bestanden */
	const TS_BACKUP_FILES = '04:30';

	/** Init */
	public static function init() {

		if ( ! is_plugin_active( 'updraftplus/updraftplus.php' ) ) {
			return;
		}
		$self = new self();
		add_filter( 'updraftplus_schedule_firsttime_db', [ $self, 'set_time_db_backup' ] );
		add_filter( 'updraftplus_schedule_firsttime_files', [ $self, 'set_time_files_backup' ] );
		add_filter( 'updraftplus_blog_name', fn() : string => sanitize_title( SIW_SITE_NAME ) );
		add_action( 'admin_init', [ $self, 'hide_woocommerce_in_plugin_update_message' ], PHP_INT_MAX );

		// Verbergt diverse UpdraftPlus notificaties
		define( 'UPDRAFTPLUS_NOADS_B', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
		define( 'UPDRAFTPLUS_NONEWSFEED', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
		define( 'UPDRAFTPLUS_ADMINBAR_DISABLE', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
		define( 'UPDRAFTPLUS_DISABLE_WP_CRON_NOTICE', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
	}

	/** Zet de tijd voor de database backup */
	public function set_time_db_backup( int $scheduled_time ): int {
		$tomorrow = strtotime( 'tomorrow' );
		$backup_db_day = gmdate( 'Y-m-d', max( $scheduled_time, $tomorrow ) );
		return strtotime( $backup_db_day . ' ' . self::TS_BACKUP_DB . wp_timezone_string() );
	}

	/** Zet de tijd voor de bestandsbackup */
	public function set_time_files_backup( int $scheduled_time ): int {
		$tomorrow = strtotime( 'tomorrow' );
		$backup_files_day = gmdate( 'Y-m-d', max( $scheduled_time, $tomorrow ) );
		return strtotime( $backup_files_day . ' ' . self::TS_BACKUP_FILES . wp_timezone_string() );
	}

	/** Verbergt melding bij WooCommerce plugin updates */
	public function hide_woocommerce_in_plugin_update_message() {
		global $updraftplus_admin;
		remove_filter( 'woocommerce_in_plugin_update_message', [ $updraftplus_admin, 'woocommerce_in_plugin_update_message' ] );
	}
}
