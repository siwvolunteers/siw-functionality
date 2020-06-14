<?php

namespace SIW\Admin;

use SIW\Core\Options;

/**
 * Optie-pagina's
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Options_Page {

	/**
	 * Priority van pagina's
	 * 
	 * @var int
	 */
	const PRIORITY = 68;

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'mb_settings_pages', [ $self, 'add_settings_pages'] );
	}

	/**
	 * Voegt optiepagina toe
	 *
	 * @param array $settings_pages
	 * @return array
	 */
	public function add_settings_pages( $settings_pages ) {

		$pages = $this->get_settings_pages();
		$is_first_page = true;
		foreach ( $pages as $page ) {
			$page['option_name'] = Options::OPTION_NAME;
			$page['id'] = Options::PAGE_PREFIX . $page['id'];
			$page['submit_button'] = __( 'Opslaan', 'siw' );
			$page['message'] = __( 'Instellingen opgeslagen', 'siw' );
			$page['columns'] = 1;
			$page['tab_style'] = 'left';

			if ( $is_first_page ) {
				$page['submenu_title'] = $page['menu_title'];
				$page['page_title'] = $page['menu_title'];
				$page['menu_title'] = __( 'SIW', 'siw' );
				$page['position'] = self::PRIORITY;
				$parent_page_id = $page['id'];
				$is_first_page = false;
			}
			else {
				$page['parent'] = $parent_page_id;
			}
			$settings_pages[] = $page;
		}
		return $settings_pages;
	}

	/**
	 * Haalt alle optiepagina's op
	 * 
	 * @return array
	 */
	protected function get_settings_pages() {
		$pages = siw_get_data( 'option-pages' );
		return wp_list_sort( $pages, 'menu_title' );
	}
}
