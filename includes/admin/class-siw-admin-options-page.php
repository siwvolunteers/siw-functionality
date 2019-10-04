<?php

/**
 * Optie-pagina's
 * 
 * @package   SIW\Admin
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Admin_Options_Page {

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

		// Tijdelijke fix, kan weg als we van MailPoet af zijn.
		if ( defined( 'WYSIJA_ITF' ) && WYSIJA_ITF ) {
			return;
		}

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
		$first_page = true;
		foreach ( $pages as $page ) {
			$page['option_name'] = SIW_Options::OPTION_NAME;
			$page['id'] = SIW_Options::PAGE_PREFIX . $page['id'];
			$page['submit_button'] = __( 'Opslaan', 'siw' );
			$page['message'] = __( 'Instellingen opgeslagen', 'siw' );

			if ( true == $first_page ) {
				$page['submenu_title'] = $page['menu_title'];
				$page['page_title'] = $page['menu_title'];
				$page['menu_title'] = __( 'SIW Instellingen', 'siw' );
				$page['position'] = self::PRIORITY;
				$parent_page_id = $page['id'];
				$first_page = false;
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

		usort( $pages, function( $a, $b ) {
			return strnatcmp( $a['menu_title'], $b['menu_title'] );
		});
		return $pages;
	}

}