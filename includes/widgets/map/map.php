<?php
/*
(c)2018 SIW Internationale Vrijwilligersprojecten
*/

/*
Widget Name: SIW: Kaart
Description: Toont een kaart.
Author: SIW Internationale Vrijwilligersprojecten
Author URI: http://github.com/siwvolunteers
*/

/**
 * Widget met Mapplic kaart
 */
class SIW_Map_Widget extends SiteOrigin_Widget {
	function __construct() {

		$maps = apply_filters( 'siw_maps', [] );

		parent::__construct(
			'siw-map-widget',
			__( 'SIW: Kaart', 'siw'),
			[
				'description'	=> __( 'Toont een Mapplic-kaart.', 'siw'),
				'panels_groups'	=> [ 'siw' ],
				'panels_icon'	=> 'dashicons dashicons-location-alt',
			],
			[],
			[
				'title' => [
					'type' => 'text',
					'label' => __( 'Titel', 'siw'),
				],
				'map' => [
					'type'		=> 'select',
					'label'		=> __( 'Kaart', 'siw' ),
					'prompt'	=> __( 'Kies een kaart', 'siw' ),
					'options'	=> $maps
				],
			],
			plugin_dir_path(__FILE__)
		);
	}

	/**
	 * Undocumented function
	 *
	 * @param array $instance
	 * @param array $args
	 * @param array $template_vars
	 * @param string $css_name
	 * @return void
	 */
	public function get_html_content( $instance, $args, $template_vars, $css_name ) {

		$title = apply_filters( 'widget_title', $instance['title'] );
		$html_content =  $args['before_widget'];
		if ( $title ) {
			$html_content .= $args['before_title'] . $title . $args['after_title'];
		}
		$html_content .= siw_render_map( $instance['map'] );
		$html_content .= $args['after_widget'];

		return $html_content;
	}


}

siteorigin_widget_register( 'siw-map-widget', __FILE__, 'SIW_Map_Widget');
