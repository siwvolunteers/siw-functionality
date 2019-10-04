<?php

/**
 * Class om alle opties te registeren
 * 
 * @package   SIW
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

class SIW_Options {

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
	const PAGE_PREFIX = 'siw-options-';

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'rwmb_meta_boxes', [ $self, 'add_settings_meta_boxes'] );
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
			foreach( $fields as $field ) {
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

		foreach ( $files as $file ) {
			$meta_boxes[] = siw_get_data( $file, 'options' );
		}
		return $meta_boxes;
	}
}