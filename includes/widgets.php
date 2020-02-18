<?php

namespace SIW;

/**
 * SIW Widgets
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Widgets {
	
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
		if ( ! class_exists( '\SiteOrigin_Widgets_Bundle' ) ) {
			return;
		}
		$self = new self();

		$self->widgets_folder_base = SIW_INCLUDES_DIR . '/widgets';
		$self->widgets = siw_get_data( 'widgets' );

		add_filter( 'siteorigin_widgets_widget_folders', [ $self, 'set_widgets_folders' ] );
		add_filter( 'siteorigin_widgets_active_widgets', [ $self, 'set_active_widgets' ] );
		add_filter( 'siteorigin_panels_data', [ $self, 'handle_renamed_widgets'] );

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
			siteorigin_widget_register( "siw-{$id_base}-widget", $this->widgets_folder_base . "/{$id_base}/{$id_base}.php", "\\SIW\\Widgets\\{$class_base}");
		}
	}

	/**
	 * Hernoemde widgets corrigeren
	 *
	 * @param array $panels_data
	 * @return array
	 */
	public function handle_renamed_widgets( $panels_data ) {

		if ( ! is_array( $panels_data ) ) {
			return $panels_data;
		}
		
		foreach( $panels_data['widgets'] as &$widget ) {
			if ( 0 === strpos( $widget['panels_info']['class'], 'SIW_Widget_' ) ) {
				$widget['panels_info']['class'] = str_replace( 'SIW_Widget_', "\\SIW\\Widgets\\", $widget['panels_info']['class'] );
			}
		}
		return $panels_data;
	}
}