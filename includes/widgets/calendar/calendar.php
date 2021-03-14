<?php declare(strict_types=1);

namespace SIW\Widgets;

/**
 * Widget met agenda
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @widget_data 
 * Widget Name: SIW: Agenda
 * Description: Toont eerstvolgende evenementen
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Calendar extends Widget {

	/** Aantal events wat in widget getoond wordt */
	const NUMBER_OF_EVENTS = 2;

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'calendar';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Agenda', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont eerstvolgende evenementen', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return $this->get_id();
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'calendar';
	}

	/** {@inheritDoc} */
	public function get_widget_form() {

		$widget_form = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw' ),
				'default' => __( 'Agenda', 'siw' ),
			],
		];
		return $widget_form;
	}

	/** {@inheritDoc} */
	function get_template_variables( $instance, $args ) {
		$events = $this->get_upcoming_events();

		$parameters = [
			'active_events'  => ! empty( $events ),
			'events'         => array_map( [ $this, 'parse_event'], $events ),
			'json_ld'        => array_map( 'siw_generate_event_json_ld', $events ),
			'agenda_url'     => get_post_type_archive_link( 'siw_event' ),
			'agenda_text'    => __( 'Bekijk de volledige agenda.', 'siw' ),
			'no_events_text' => __( 'Er zijn momenteel geen geplande activiteiten.', 'siw' ),
		];
		return $parameters;
	}

	/** Parset event data */
	protected function parse_event( int $event_id ) : array {
		return [
			'title'    => get_the_title( $event_id ),
			'url'      => get_permalink( $event_id ),
			'duration' => sprintf(
				'%s %s-%s',
				siw_format_date( siw_meta( 'event_date', [], $event_id ), false ),
				siw_meta( 'start_time', [], $event_id ),
				siw_meta( 'end_time', [], $event_id )
			),
			'location' =>
				siw_meta( 'online', [], $event_id )
				?
				esc_html__( 'Online', 'siw' )
				:
				sprintf( '%s, %s', siw_meta( 'location.name', [], $event_id ), siw_meta( 'location.city', [], $event_id ) ),
			
		];
	}

	/** Haalt toekomstige evenementen op */
	protected function get_upcoming_events() : array {
		return siw_get_upcoming_events([
			'number' => self::NUMBER_OF_EVENTS,
		]);
	}
}
