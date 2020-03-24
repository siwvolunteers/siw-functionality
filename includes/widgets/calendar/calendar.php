<?php

namespace SIW\Widgets;

use SIW\HTML;

/**
 * Widget met agenda
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data 
 * Widget Name: SIW: Agenda
 * Description: Toont eerstvolgende evenementen
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Calendar extends Widget {

	/**
	 * Aantal events wat in widget getoond wordt
	 * 
	 * @var int
	 */
	const NUMBER_OF_EVENTS = 2;

	/**
	 * {@inheritDoc}
	 */
	protected $widget_id ='calendar';

	/**
	 * {@inheritDoc}
	 */
	protected $widget_dashicon = 'calendar';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Agenda', 'siw');
		$this->widget_description = __( 'Toont eerstvolgende evenementen', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {

		$widget_form = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw'),
				'default' => __( 'Agenda', 'siw' ),
			],
		];
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_content( array $instance, array $args, array $template_vars, string $css_name ) {

		$events = $this->get_upcoming_events();

		if ( empty( $events ) ) {
			$content = '<p>' . esc_html__( 'Er zijn momenteel geen geplande activiteiten.', 'siw' ) . '</p>';
			return $content;
		}
		foreach ( $events as $event ) {
			ob_start();
			?>
			<h5 class="title">
				<?= HTML::generate_link( $event['permalink'], $event['title'], [ 'class' => 'link' ] ) ?>
			</h5>
			<span class="duration" >
				<?= esc_html( $event['date_range'] );?> <br/>
				<?= esc_html( $event['start_time'] . '&nbsp;-&nbsp;' . $event['end_time'] );?><br/>
			</span>
			<span class="location">
				<?php 
					if ( isset( $event['type_evenement'] ) && 'online' == $event['type_evenement'] ) {
						esc_html_e( 'Online', 'siw' );
					}
					else {
						echo esc_html( $event['location'] . ',&nbsp;' . $event['city'] );
					}
				?>
			</span>
			<?php
			$event_list[] = ob_get_clean();
			$json_ld[] = $event['json_ld'];
		}
		$content = HTML::generate_list( $event_list );
		$content .= implode( SPACE, $json_ld );
		$content .= '<p class="page-link">' . HTML::generate_link( get_page_link( siw_get_option( 'events_archive_page' ) ), __( 'Bekijk de volledige agenda.', 'siw' ) ) . '</p>';

		return $content;
	}

	/**
	 * Haalt toekomstige evenementen op
	 * 
	 * @return array
	 */
	protected function get_upcoming_events() {
		return siw_get_upcoming_events( self::NUMBER_OF_EVENTS );
	}
}
