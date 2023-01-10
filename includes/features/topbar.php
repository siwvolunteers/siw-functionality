<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Action;
use SIW\Base;
use SIW\Helpers\Template;
use SIW\I18n;

/**
 * Topbar
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Topbar extends Base {

	const ASSETS_HANDLE = 'siw-topbar';

	/** Toon het evenement x aantal dagen van te voren */
	const EVENT_SHOW_DAYS_BEFORE = 14;

	/** Verberg het evenement y aantal dagen van te voren */
	const EVENT_HIDE_DAYS_BEFORE = 1;

	/** Inhoud van de topbar */
	protected ?array $content;

	/** Bepaal of topbar getoond moet worden */
	protected function show_topbar(): bool {

		$show_topbar = wp_cache_get( key: 'siw_show_topbar', found: $found );
		if ( true === $found ) {
			return $show_topbar;
		}

		$show_topbar = true;

		if ( ! I18n::is_default_language() || is_admin() ) {
			$show_topbar = false;
		}

		// Content zetten
		$this->content = $this->get_content();
		if ( is_null( $this->content ) ) {
			$show_topbar = false;
		}

		wp_cache_set( 'siw_show_topbar', $show_topbar );

		return $show_topbar;
	}

	#[Action( 'generate_before_header' )]
	/** Rendert de topbar */
	public function render() {
		if ( ! $this->show_topbar() ) {
			return;
		}
		Template::create()
			->set_template( 'features/topbar' )
			->set_context(
				[
					'target'    => $this->content['link_target'] ?? '_self',
					'link_url'  => $this->content['link_url'],
					'link_text' => $this->content['link_text'],
				]
			)
			->render_template();
	}

	#[Action( 'wp_enqueue_scripts' )]
	/** Voegt stylesheet toe */
	public function enqueue_styles() {
		if ( ! $this->show_topbar() ) {
			return;
		}
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/features/topbar.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/features/topbar.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}

	/** Haalt de inhoud op */
	protected function get_content(): ?array {
		$content =
			$this->get_event_content() ??
			null;

		return $content;
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
			__( 'Kom naar de %1$s op %2$s.', 'siw' ),
			get_the_title( $event_id ),
			siw_format_date( siw_meta( 'event_date', [], $event_id ), false )
		);

		return [
			'link_url'  => get_the_permalink( $event_id ),
			'link_text' => $link_text,
		];
	}
}
