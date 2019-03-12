<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Opties
 * 
 * @package     SIW\Options
 * @copyright   2019 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */

class SIW_Options_Page {

	/**
	 * Optienaam in database
	 *
	 * @var string
	 */
	protected $option_name = 'siw_options';

	/**
	 * Inits
	 */
	public static function init() {
		$self = new self();
		add_filter( 'mb_settings_pages', [ $self, 'add_settings_pages'] );
		add_filter( 'rwmb_meta_boxes', [ $self, 'add_settings_meta_boxes'] );
	}

	/**
	 * Haalt alle optie-pagina's op
	 * 
	 * @return array
	 */
	protected function get_settings_pages() {
		$pages = [];
		/**
		 * TODO: verplaatsen naar losse functie
		 */
		$pages = apply_filters( 'siw_settings_pages', $pages );

		return $pages;
	}

	/**
	 * Haalt alle optiepagina-metaboxes op
	 * 
	 * @return array
	 */
	protected function get_meta_boxes() {
		$settings_boxes = [];

		/**
		 * TODO: verplaatsen naar losse functie
		 */
		$settings_boxes = apply_filters( 'siw_settings_meta_boxes', $settings_boxes );

		return $settings_boxes;
	}

	/**
	 * Voegt optie-pagina toe
	 *
	 * @param array $settings_pages
	 * @return array
	 */
	public function add_settings_pages( $settings_pages ) {

		$pages = $this->get_settings_pages();
		$first_page = true;
		foreach ( $pages as $page ) {
			$page['option_name'] = $this->option_name;

			if ( true == $first_page ) {
				$page['submenu_title'] = $page['menu_title'];
				$page['page_title'] = $page['menu_title'];
				$page['menu_title'] = __( 'SIW Instellingen', 'siw' );
				$page['position'] = 68;
				$parent_page_id = $page['id'];
			}
			else {
				$page['parent'] = $parent_page_id;
			}
			$settings_pages[] = $page;
			$first_page = false;
		}

		return $settings_pages;
	}

	/**
	 * Voegt instellingen-metaboxes toe
	 *
	 * @param array $meta_boxes
	 * 
	 * @return array
	 */
	public function add_settings_meta_boxes( $meta_boxes ) {

		$settings_boxes = $this->get_meta_boxes();

		foreach( $settings_boxes as $box ) {
			$validation = [];
			
			$fields = $box['fields'];
			foreach( $fields as $field ) {
				if ( isset( $field['validation'] ) ) {
					$validation['rules'][ $field['id'] ] = $field['validation'];
				}
			}
			if ( ! empty( $validation ) ) {
				$box['validation'] = $validation;
			}
			$box['toggle_type'] = 'slide';
			$meta_boxes[] = $box;
		}
		//TODO: validation verplaatsen

		return $meta_boxes;
	}
}