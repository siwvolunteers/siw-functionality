<?php

namespace SIW\Widgets;

use SIW\Formatting;
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
			$content = wpautop( esc_html__( 'Er zijn momenteel geen geplande activiteiten.', 'siw' ) );
			return $content;
		}

		foreach ( $events as $event_id ) {

			ob_start();
			?>
			<h3 class="title">
				<?= HTML::generate_link( get_permalink( $event_id ), get_the_title( $event_id ), [ 'class' => 'link' ] ) ?>
			</h3>
			<span class="duration" >
				<?php
					printf(
						'%s %s-%s',
						Formatting::format_date( siw_meta( 'event_date', [], $event_id ), false ),
						siw_meta( 'start_time', [], $event_id ),
						siw_meta( 'end_time', [], $event_id )
					)
				 ?>
			</span>
			<br/>
			<span class="location">
				<?php 
					if ( siw_meta( 'online', [], $event_id ) ) {
						esc_html_e( 'Online', 'siw' );
					}
					else {
						$location = siw_meta( 'location', [], $event_id );
						printf( '%s, %s', $location['name'], $location['city'] );
					}
				?>
			</span>
			<?php
			$event_list[] = ob_get_clean();
			$json_ld[] = siw_generate_event_json_ld( $event_id );
		}
		$content = HTML::generate_list( $event_list );
		$content .= implode( SPACE, $json_ld );
		$content .= '<p class="page-link">' . HTML::generate_link( get_post_type_archive_link( 'siw_event' ) , __( 'Bekijk de volledige agenda.', 'siw' ), ['class' => 'link'] ) . '</p>';

		return $content;
	}

	/**
	 * Haalt toekomstige evenementen op
	 * 
	 * @return array
	 */
	protected function get_upcoming_events() {
		return siw_get_upcoming_events([
			'number' => self::NUMBER_OF_EVENTS,
		]);
	}
}
