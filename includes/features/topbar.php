<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Elements\Topbar as Topbar_Element;

/**
 * Topbar
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Topbar extends Base {

	/** Toon het evenement x aantal dagen van te voren */
	private const EVENT_SHOW_DAYS_BEFORE = 14;

	/** Verberg het evenement y aantal dagen van te voren */
	private const EVENT_HIDE_DAYS_BEFORE = 1;

	#[Add_Action( 'generate_before_header', 1 )]
	public function show_topbar(): void {

		$event_content = $this->get_event_content();
		if ( null === $event_content ) {
			return;
		}

		Topbar_Element::create()
			->set_text( $event_content['text'] )
			->set_url( $event_content['url'] )
			->render();
	}

	/** Haalt de evenementen-inhoud op */
	protected function get_event_content(): ?array {

		$upcoming_events = siw_get_upcoming_events(
			[
				'number'      => 1,
				'date_before' => gmdate( 'Y-m-d', strtotime( '+' . self::EVENT_SHOW_DAYS_BEFORE . ' days' ) ),
				'date_after'  => gmdate( 'Y-m-d', strtotime( '+' . self::EVENT_HIDE_DAYS_BEFORE . ' days' ) ),
			]
		);

		if ( empty( $upcoming_events ) ) {
			return null;
		}
		$event_id = $upcoming_events[0];

		$link_text = sprintf(
		// translators: %1$s is de naam van het evenement, %2$s is de datum
			__( 'Kom naar de %1$s op %2$s', 'siw' ),
			get_the_title( $event_id ),
			siw_format_date( siw_meta( 'event_date', [], $event_id ), false )
		);

		return [
			'url'  => get_the_permalink( $event_id ),
			'text' => $link_text,
		];
	}
}
