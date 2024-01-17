<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Content\Posts\Events;
use SIW\Elements\Topbar as Topbar_Element;

class Topbar extends Base {

	private const EVENT_SHOW_DAYS_BEFORE = 14;

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

	protected function get_event_content(): ?array {

		$upcoming_events = Events::get_future_events( [ 'limit' => 1 ] );

		if ( empty( $upcoming_events ) ) {
			return null;
		}
		$event = reset( $upcoming_events );

		if ( $event->get_event_date()->diff( new \DateTime() )->days > static::EVENT_SHOW_DAYS_BEFORE ) {
			return null;
		}

		$link_text = sprintf(
		// translators: %1$s is de naam van het evenement, %2$s is de datum
			__( 'Kom naar de %1$s op %2$s', 'siw' ),
			$event->get_title(),
			$event->get_event_date()->format( 'j F' )
		);

		return [
			'url'  => $event->get_permalink(),
			'text' => $link_text,
		];
	}
}
