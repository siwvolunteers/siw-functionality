<?php

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
		if ( ! class_exists( 'SiteOrigin_Widgets_Bundle' ) ) {
			return;
		}
		$self = new self();

		$self->widgets_folder_base = SIW_INCLUDES_DIR . '/widgets';
		require_once $self->widgets_folder_base . '/class-siw-widget.php';
		$self->widgets = siw_get_data( 'widgets' );

		add_filter( 'siteorigin_widgets_widget_folders', [ $self, 'set_widgets_folders' ] );
		add_filter( 'siteorigin_widgets_active_widgets', [ $self, 'set_active_widgets' ] );

		$self->register_widgets();
	}

	/**
	 * Overschrijf SiteOrigin Widgets met SIW-widgets
	 *
	 * @param array $folders
	 * @return array
	 */
	public function set_widgets_folders( array $folders ) {
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
	public function set_active_widgets( array $active_widgets ) {
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
