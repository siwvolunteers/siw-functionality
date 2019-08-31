<?php

/**
 * Widget met omschrijving Nederlandse projecten
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Formatting
 * @uses      SIW_i18n
 * 
 * @widget_data
 * Widget Name: SIW: Nederlandse projecten
 * Description: Toont omschrijving van Nederlandse projecten
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class SIW_Widget_Dutch_Projects extends SIW_Widget {

	/**
	 * {@inheritDoc}
	 */
	protected $widget_id ='dutch_projects';

	/**
	 * {@inheritDoc}
	 */
	protected $widget_dashicon = 'list-view';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Nederlandse projecten', 'siw' );
		$this->widget_description = __( 'Toont omschrijving van Nederlandse projecten', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'  => 'text',
				'label' => __( 'Titel', 'siw'),
			],
		];
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_content( array $instance, array $args, array $template_vars, string $css_name ) {
		$language = SIW_i18n::get_current_language();
		$projects = siw_get_option( 'dutch_projects');
		$accordion_panes = [];
		$tabs_panes = [];
		foreach ( $projects as $project ) {
			$work_type = siw_get_work_type( $project['work_type'] ); 
			$icon = SIW_Formatting::generate_icon( $work_type ? $work_type->get_icon_class() : '', 2, 'circle' );
			$summary = wpautop( sprintf( __( 'Thema: %s', 'siw' ), $work_type ? $work_type->get_name() : '' ) );
			$summary .= wpautop( sprintf( __( 'Projectcode: %s', 'siw' ), $project['code'] ) );

			$content = SIW_Formatting::generate_columns([
				[ 'width' => 2, 'content' => $icon . $summary ],
				[ 'width' => 10, 'content' => $project["description_{$language}"] ],
			]);

			$tabs_panes[] = [
				'title'   => $project["name_{$language}"],
				'content' => $content,
			];
			$accordion_panes[] = [
				'title'   => $project["name_{$language}"],
				'content' => $project["description_{$language}"],
			];
		}
		$content = SIW_Formatting::generate_tabs( $tabs_panes );
		$mobile_content = SIW_Formatting::generate_accordion( $accordion_panes );


		//TODO: functie in SIW_Formatting voor mobile content
		$content = '<div class="hidden-xs">' . $content . '</div>';
		$content .= '<div class="hidden-sm hidden-md hidden-lg">' . $mobile_content . '</div>';

		$booklet_link = $this->get_booklet_link();
		if ( false !== $booklet_link ) {
			$content .= $booklet_link;
		}

		return $content;
	}

	/**
	 * Haal link naar programmaboekje NP op
	 * 
	 * @return string
	 * 
	 * @todo check of programmaboekje van huidige jaar is
	 * @todo function generate_document_link maken
	 */
	protected function get_booklet_link() {
		$booklets = siw_get_option( 'dutch_projects_booklet');
		$booklet = reset( $booklets );
		$booklet_year = siw_get_option( 'dutch_projects_booklet_year');

		if ( ! empty( $booklet ) ) {
			$booklet_link = SIW_Formatting::generate_link(
				$booklet['url'],
				sprintf( __( 'Engelstalige programmaboekje %d (PDF)', 'siw' ), $booklet_year ),
				[
					'target'           => '_blank',
					'rel'              => 'noopener',
					'data-ga-track'    => 1,
					'data-ga-type'     => 'event',
					'data-ga-category' => 'Document',
					'data-ga-action'   => 'Downloaden',
					'data-ga-label'    => $booklet['url'],
					]
			);
			$booklet_link = sprintf( __( 'Wil jij meer lezen over onze Nederlandse vrijwilligersprojecten, download dan ons %s.', 'siw' ),  $booklet_link );
		}
		else {
			$booklet_link = false;
		}

		return $booklet_link;
	}
}
