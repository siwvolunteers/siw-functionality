<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget met interactieve kaart
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      siw_render_map()
 * 
 * Widget Name: SIW: Kaart
 * Description: Toont interactieve kaart
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class SIW_Map_Widget extends SIW_Widget {

	/**
	 * {@inheritDoc}
	 */
	protected $widget_id ='map';

	/**
	 * {@inheritDoc}
	 */
	protected $widget_dashicon = 'location-alt';

	/**
	 * {@inheritDoc}
	 */
	function __construct() {

		$maps = apply_filters( 'siw_maps', [] ); //TODO: get_maps oid

		$this->widget_name = __( 'Kaart', 'siw');
		$this->widget_description = __( 'Toont interactieve kaart', 'siw' );
		$this->widget_fields = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw'),
				'default' => __( 'Blijf op de hoogte', 'siw' ),
			],
			'map' => [
				'type'    => 'select',
				'label'   => __( 'Kaart', 'siw' ),
				'prompt'  => __( 'Kies een kaart', 'siw' ),
				'options' => $maps
			],
		];
		parent::__construct();
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_content( $instance, $args, $template_vars, $css_name ) {
		return siw_render_map( $instance['map'] );
	}
}