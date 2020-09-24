<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Admin\Notices as Admin_Notices;

/**
 * Aanpassingen aan Admin Bar
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Admin_Bar {

	/**
	 * Nodes
	 *
	 * @var array
	 */
	protected static $nodes = [];

	/**
	 * Acties
	 *
	 * @var array
	 */
	protected static $actions = [];

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'admin_bar_menu', [ $self, 'remove_nodes'], PHP_INT_MAX );
		add_action( 'admin_bar_menu', [ $self, 'add_logo'], 1 );
		add_action( 'admin_bar_menu', [ $self, 'add_environment'], 2 );
		add_action( 'admin_bar_menu', [ $self, 'add_action_triggers'], 3 );
		add_action( 'init', [ $self, 'process_action_triggers' ] ); //TODO: misschien admin_init?
	}
	
	/**
	 * Verwijdert standaardnodes
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar
	 */
	public function remove_nodes( \WP_Admin_Bar $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'wp-logo' );
		$wp_admin_bar->remove_node( 'site-name' );
		$wp_admin_bar->remove_node( 'comments' );
		$wp_admin_bar->remove_node( 'new-content' );
	}

	/**
	 * Voegt logo toe aan adminbar
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar
	 */
	public function add_logo( \WP_Admin_Bar $wp_admin_bar ) {
		$logo_args = [
			'id'   => 'siw-logo',
			'meta' => [
				'class' => 'siw-logo',
				'title' => 'SIW',
			],
		];
		$wp_admin_bar->add_node( $logo_args );
	}

	/**
	 * Voegt omgeving toe adminbar
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar
	 */
	public function add_environment( \WP_Admin_Bar $wp_admin_bar ) {
		$env_type = \wp_get_environment_type();
		$env_args = [
			'id'    => 'siw-env',
			'parent'=> 'top-secondary',
			'title' => '<span class="ab-icon"></span><span class="ab-label">' . esc_html( ucfirst( $env_type ) ) . '</span>',
			'meta'  => [
				'title' => __( 'Omgeving', 'siw' ),
				'class' => 'env-' . sanitize_title( $env_type ),
			],
		];
		$wp_admin_bar->add_node( $env_args );
	}

	/**
	 * Voegt acties toe aan adminbar
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar
	 */
	public function add_action_triggers( \WP_Admin_Bar $wp_admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) || empty( self::$actions ) ) {
			return;
		}

		/* Voeg hoofdnode toe*/
		$args = [
			'id'    => 'siw-actions',
			'title' => __( 'Start actie', 'siw' ),
			'href'  => '#',
		];
		$wp_admin_bar->add_node( $args );
	
		/* Voeg nodes toe */
		$nodes = wp_list_sort( self::$nodes, 'title', 'asc', true );
		foreach ( $nodes as $node => $properties ) {
			$args = [
				'parent' => ( isset( $properties['parent'] ) ) ? 'siw-' . $properties['parent'] . '-actions' : 'siw-actions',
				'id'     => 'siw-' .$node . '-actions',
				'title'  => $properties['title'],
			];
			$wp_admin_bar->add_node( $args );
		}

		$referer = '&_wp_http_referer=' . rawurlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		
		/* Voeg acties toe */
		$actions = wp_list_sort( self::$actions, 'title', 'asc', true );
		foreach ( $actions as $action => $properties ) {
			$args = [
				'parent' => ( isset( $properties['parent'] ) ) ? 'siw-' . $properties['parent'] . '-actions' : 'siw-actions',
				'id'     => "siw-action-{$action}",
				'title'  => $properties['title'],
				'href'   => wp_nonce_url( admin_url( "admin-post.php?siw-action={$action}{$referer}" ), 'siw-action' ),
			];
			$wp_admin_bar->add_node( $args );
		}
	}

	/**
	 * Verwerkt actie
	 */
	public function process_action_triggers() {
		if ( ! isset( $_GET['siw-action'] ) || ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'siw-action') ) {
			return;
		}

		$action = esc_attr( trim( $_REQUEST['siw-action']) );
		
		if ( ! in_array( $action, array_keys( self::$actions ) ) ) {
			return;
		}
		do_action( "siw_{$action}" );

		$notices = new Admin_Notices;
		$notices->add_notice( 'success', sprintf( __( 'Proces gestart: %s', 'siw' ), self::$actions[ $action ]['title'] ), true );
		wp_redirect( wp_get_referer() );
	}

	/**
	 * Voegt node toe
	 *
	 * @param string $node
	 * @param array $properties
	 */
	public static function add_node( $node, $properties ) {
		self::$nodes[ $node ] = $properties;
	}

	/**
	 * Voegt actie toe
	 *
	 * @param string $action
	 * @param array $properties
	 */
	public static function add_action( $action, $properties ) {
		self::$actions[ $action ] = $properties;
	}
}
