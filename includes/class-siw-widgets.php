<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SIW Widgets
 *
 * @package   SIW
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */
class SIW_Widgets {
	
	/**
	 * SIW-widgets
	 *
	 * @var array
	 */
	protected $widgets;

	/**
	 * Basismap voor widgets
	 *
	 * @var string
	 */
	protected $widgets_folder_base;

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();

		$self->set_widgets_folder_base();
		$self->include_files();
		$self->get_widgets();

		add_filter( 'siteorigin_widgets_widget_folders', [ $self, 'set_widgets_folders' ] );
		add_filter( 'siteorigin_widgets_active_widgets', [ $self, 'set_active_widgets' ] );

		$self->register_widgets();
	}

	/**
	 * Zet basis-map voor widgets
	 */
	protected function set_widgets_folder_base() {
		$this->widgets_folder_base = SIW_INCLUDES_DIR . '/widgets';
	}

	/**
	 * Include bestanden
	 * 
	 * - Abstracte basis-klasse
	 * - Data
	 */
	protected function include_files() {
		require_once( $this->widgets_folder_base . '/abstract-siw-widget.php' );
		require_once( $this->widgets_folder_base . '/data.php' );
	}

	/**
	 * Haalt widgets op
	 */
	protected function get_widgets() {
		$widgets = [];
		/**
		 * Array met SIW-widgets
		 *
		 * @param array $widgets Gegevens van widget { id_base => class_base }
		 */
		$this->widgets = apply_filters( 'siw_widgets', $widgets );
	}

	/**
	 * Overschrijf SiteOrigin Widgets met SIW-widgets
	 *
	 * @param array $folders
	 * @return array
	 */
	public function set_widgets_folders( $folders ) {
		$folders = [];
		$folders[] = $this->widgets_folder_base . '/';
		return $folders;
	}

	/**
	 * Activeert alle SIW-widgets
	 *
	 * @param array $active_widgets
	 * @return array
	 */
	public function set_active_widgets( $active_widgets ) {
		foreach ( $this->widgets as $id_base => $class_base ) {
			$active_widgets[ $id_base ] = true;
		}
		return $active_widgets;
	}

	/**
	 * Registreert alle SIW-widgets
	 */
	protected function register_widgets() {
		foreach ( $this->widgets as $id_base => $class_base ) {
			siteorigin_widget_register( "siw-{$id_base}-widget", $this->widgets_folder_base . "/{$id_base}/{$id_base}.php", "SIW_Widget_{$class_base}");
		}
	}
}
