<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Content\Post\Event;
use SIW\Content\Posts\Events;
use SIW\Elements\Calendar_Icon;
use SIW\Helpers\Template;

/**
 * @widget_data
 * Widget Name: SIW: Agenda
 * Description: Toont eerstvolgende evenementen
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Calendar extends Widget {

	/** Maximaal aantal events wat in widget getoond wordt */
	private const MAX_NUMBER_OF_EVENTS = 5;

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
			$events = Events::get_future_info_days();
		} else {
			$events = Events::get_future_events();
		}

		if ( count( $events ) > (int) $instance['number'] ) {
			$events = array_slice( $events, 0, (int) $instance['number'] );
		}

		$event_list = array_map(
			fn( Event $event ): array => [
				'event'    => $event,
				'calendar' => Calendar_Icon::create()->set_datetime( $event->get_event_date() )->generate(),
			],
			$events
		);
		return [
			'event_list'  => $event_list,
			'has_events'  => ! empty( $events ),
			'archive_url' => get_post_type_archive_link( 'siw_event' ),
		];
	}

	/** {@inheritDoc} */
	public function initialize() {
		$this->register_frontend_styles(
			[
				[
					self::get_asset_handle(),
					self::get_style_asset_url(),
					[],
					SIW_PLUGIN_VERSION,
				],
			]
		);
	}

	protected function parse_event( Event $event ): string {
		return Template::create()
			->set_template( 'widgets/calendar-event' )
			->set_context(
				[
					'calendar_icon' => Calendar_Icon::create()->set_datetime( $event->get_event_date() )->generate(),
					'event'         => $event,
					'location'      => $event->is_online() ?
						__( 'Online', 'siw' ) : sprintf( '%s %s', $event->get_location()['name'], $event->get_location()['city'] ),
				]
			)
			->parse_template();
	}
}
