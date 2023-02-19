<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Util\CSS;

/**
 * Widget met agenda
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Agenda
 * Description: Toont eerstvolgende evenementen
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Calendar extends Widget {

	/** Maximaal aantal events wat in widget getoond wordt */
	const MAX_NUMBER_OF_EVENTS = 5;

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
	protected function supports_title(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function get_widget_fields(): array {
		$widget_fields = [
			'number'        => [
				'type'    => 'slider',
				'label'   => __( 'Aantal evenementen', 'siw' ),
				'default' => 2,
				'min'     => 1,
				'max'     => self::MAX_NUMBER_OF_EVENTS,
			],
			'only_infodays' => [
				'type'    => 'checkbox',
				'label'   => __( 'Toon alleen SIW infodagen', 'siw' ),
				'default' => false,
			],
			'columns'       => [
				'type'    => 'slider',
				'label'   => __( 'Aantal kolommen', 'siw' ),
				'default' => 1,
				'min'     => 1,
				'max'     => 4,
			],
		];
		return $widget_fields;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {

		if ( $instance['only_infodays'] ) {
			$events = siw_get_upcoming_info_days( (int) $instance['number'] );
		} else {
			$events = siw_get_upcoming_events( [ 'number' => $instance['number'] ] );
		}

		$parameters = [
			'responsive_classes' => CSS::generate_responsive_classes( (int) $instance['columns'] ),
			'active_events'      => ! empty( $events ),
			'events'             => array_map( [ $this, 'parse_event' ], $events ),
			'json_ld'            => array_map( 'siw_generate_event_json_ld', $events ),
			'agenda_url'         => get_post_type_archive_link( 'siw_event' ),
			'agenda_text'        => __( 'Bekijk de volledige agenda.', 'siw' ),
			'no_events_text'     => __( 'Er zijn momenteel geen geplande activiteiten.', 'siw' ),
		];
		return $parameters;
	}

	/** {@inheritDoc} */
	public function initialize() {
		$this->register_frontend_styles(
			[
				[
					'siw-widget-calendar',
					SIW_ASSETS_URL . 'css/widgets/calendar.css',
					[],
					SIW_PLUGIN_VERSION,
				],
			]
		);
	}

	/** Parset event data */
	protected function parse_event( int $event_id ) : array {
		return [
			'title'    => get_the_title( $event_id ),
			'url'      => get_permalink( $event_id ),
			'day'      => wp_date( 'd', strtotime( siw_meta( 'event_date', [], $event_id ) ) ),
			'month'    => wp_date( 'M', strtotime( siw_meta( 'event_date', [], $event_id ) ) ),
			'duration' => sprintf(
				'%s-%s',
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
}
