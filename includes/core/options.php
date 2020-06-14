<?php

namespace SIW\Core;

/**
 * Class om alle opties te registeren
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Options {

	/**
	 * Optienaam
	 * 
	 * @var string
	 */
	const OPTION_NAME = 'siw_options';

	/**
	 * Prefix voor paginaslug
	 * 
	 * @var string
	 */
	const PAGE_PREFIX = 'siw-';

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'rwmb_meta_boxes', [ $self, 'add_settings_meta_boxes'] );
		add_filter( 'siw_option_value', [ $self, 'format_option_value' ], 10, 2 );
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

		foreach ( $settings_boxes as $box ) {
			$validation = [];
			
			$fields = $box['fields'];
			foreach ( $fields as $field ) {
				//Validatie kopieëren naar metabox
				if ( isset( $field['validation'] ) ) {
					$validation['rules'][ $field['id'] ] = $field['validation'];
				}

			}
			if ( ! empty( $validation ) ) {
				$box['validation'] = $validation;
			}
			$box['toggle_type'] = 'slide';
			$box['settings_pages'] = self::PAGE_PREFIX . $box['settings_pages'];
			$meta_boxes[] = $box;
		}

		return $meta_boxes;
	}

	/**
	 * Haalt alle instellingen-metaboxes op
	 * 
	 * @return array
	 */
	protected function get_meta_boxes() {
		$files = siw_get_data_file_ids( 'options' );
		
		if ( empty( $files ) ) {
			return [];
		}
		
		foreach ( $files as $file ) {
			$meta_boxes[] = siw_get_data( "options/{$file}" );
		}
		return $meta_boxes;
	}

	/**
	 * Undocumented function
	 *
	 * @param mixed $value
	 * @param string $option
	 *
	 * @return mixed
	 */
	public function format_option_value( $value, $option ) {
		switch ( $option ) {
			case 'board_members':
				$titles = siw_get_board_titles();
				$callback = function( &$value, $key ) use ( $titles ) {
					$value['title'] = ( isset( $value['title'] ) && isset( $titles[ $value['title'] ] ) ) ? $titles[ $value['title'] ] : '';
				};
				array_walk( $value, $callback, $titles );
				break;

			case 'special_opening_hours':
				$value = array_column( $value , null, 'date' );
				$callback = function( &$value, $key ) {
					$value = $value['opened'] ? sprintf( '%s-%s', $value['opening_time'], $value['closing_time'] ) : __( 'gesloten', 'siw' );
				};
				array_walk( $value, $callback );
				break;

			case 'opening_hours':
				$callback = function( &$value, $key ) {
					$value = $value['open'] ? sprintf( '%s-%s', $value['opening_time'], $value['closing_time'] ) : __( 'gesloten', 'siw' );
				};
				array_walk( $value, $callback );
			break;
		}

		return $value;
	}
}
