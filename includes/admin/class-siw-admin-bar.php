<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Aanpassingen aan Admin Bar
 * 
 * @package   SIW\Admin
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Admin_Notices
 */
class SIW_Admin_Bar {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'admin_bar_menu', [ $self, 'remove_nodes'], PHP_INT_MAX );
		add_action( 'admin_bar_menu', [ $self, 'add_logo'], 1 );
		add_action( 'admin_bar_menu', [ $self, 'add_environment'], 2 );
		add_action( 'admin_bar_menu', [ $self, 'add_action_triggers'], 3 );
		add_action( 'init', [ $self, 'process_action_triggers' ] );
	}
	
	/**
	 * Verwijdert standaardnodes
	 *
	 * @param WP_Admin_Bar $wp_admin_bar
	 */
	public function remove_nodes( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'wp-logo' );
		$wp_admin_bar->remove_node( 'site-name' );
		$wp_admin_bar->remove_node( 'comments' );
		$wp_admin_bar->remove_node( 'new-content' );
	}

	/**
	 * Voegt logo toe aan adminbar
	 *
	 * @param WP_Admin_Bar $wp_admin_bar
	 */
	public function add_logo( $wp_admin_bar ) {
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
	 * @param WP_Admin_Bar $wp_admin_bar
	 */
	public function add_environment( $wp_admin_bar ) {
		$url_args = [
			'id'    => 'siw-url',
			'title' => sprintf( __( 'Je bent ingelogd op: %s', 'siw' ), site_url() ),
		];
		$wp_admin_bar->add_node( $url_args );
	}

	/**
	 * Voegt acties toe aan adminbar
	 *
	 * @param WP_Admin_Bar $wp_admin_bar
	 */
	public function add_action_triggers( $wp_admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$actions = $this->get_actions();
		if ( empty( $actions ) ) {
			return;
		}

		$nodes = $this->get_nodes();

		/* Voeg hoofdnode toe*/
		$args = [
			'id'    => 'siw-actions',
			'title' => __( 'Start actie', 'siw' ),
			'href'  => '#',
		];
		$wp_admin_bar->add_node( $args );
	
		/* Voeg nodes toe */
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
		if ( ! isset( $_GET['siw-action'] ) || ! isset( $_GET['_wpnonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'siw-action') ) {
			return;
		}
		$action = esc_attr( trim( $_REQUEST['siw-action']) );

		$actions = $this->get_actions();
		
		if ( ! in_array( $action, array_keys( $actions ) ) ) {
			return;
		}
		do_action( "siw_{$action}" );
		do_action( 'siw_action', $action ); //TODO: 1 methode kiezen

		$notices = new SIW_Admin_Notices;
	
		$notices->add_notice( 'success', sprintf( __( 'Proces gestart: %s', 'siw' ), $actions[ $action ]['title'] ), true );
		wp_redirect( wp_get_referer() );
	}

	/**
	 * Haalt alle acties op
	 * 
	 * @return array
	 */
	protected function get_actions() {

		$actions = [];
		/**
		 * Admin bar acties
		 *
		 * @param array $actions
		 */
		$actions = apply_filters( 'siw_admin_bar_actions', $actions );
		return $actions;
	}

	/**
	 * Haalt alle nodes op
	 * 
	 * @return array
	 */
	protected function get_nodes() {
		$nodes = [];
		/**
		 * Admin bar nodes
		 *
		 * @param array $actions
		 */
		$nodes = apply_filters( 'siw_admin_bar_nodes', $nodes );
		return $nodes;
	}

	/**
	 * Voegt node toe
	 *
	 * @param string $node
	 * @param array $properties
	 */
	public static function add_node( $node, $properties ) {
		add_filter( 'siw_admin_bar_nodes', function( $nodes ) use( $node, $properties ) {
			$nodes[ $node ] = $properties;
			return $nodes;
		});
	}

	/**
	 * Voegt actie toe
	 *
	 * @param string $action
	 * @param array $properties
	 */
	public static function add_action( $action, $properties ) {
		add_filter( 'siw_admin_bar_actions', function( $actions ) use( $action, $properties ) {
			$actions[ $action ] = $properties;
			return $actions;
		});
	}
}