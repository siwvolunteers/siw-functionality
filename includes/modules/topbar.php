<?php declare(strict_types=1);

namespace SIW\Modules;

use SIW\Helpers\Template;
use SIW\I18n;
use SIW\Properties;

/**
 * Topbar
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Topbar {

	const STYLE_HANDLE = 'siw-topbar';

	/** Toon het evenement x aantal dagen van te voren */
	const EVENT_SHOW_DAYS_BEFORE = 14;

	/** Verberg het evenement y aantal dagen van te voren */
	const EVENT_HIDE_DAYS_BEFORE = 1;

	/** Inhoud van de topbar */
	protected ?array $content;

	/** Init */
	public static function init() {
		$self = new self();
		if ( ! I18n::is_default_language() ) {
			return;
		}

		// Alleen in front-end tonen
		if ( is_admin() ) {
			return;
		}

		// Content zetten
		$self->content = $self->get_content();
		if ( is_null( $self->content ) ) {
			return;
		}
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_styles' ] );
		add_action( 'generate_before_header', [ $self, 'render' ] );
	}

	/** Rendert de topbar */
	public function render() {
		Template::create()
			->set_template( 'modules/topbar' )
			->set_context(
				[
					'target'    => $this->content['link_target'] ?? '_self',
					'link_url'  => $this->content['link_url'],
					'link_text' => $this->content['link_text'],
				]
			)
			->render_template();
	}

	/** Voegt stylesheet toe */
	public function enqueue_styles() {
		wp_register_style( self::STYLE_HANDLE, SIW_ASSETS_URL . 'css/modules/siw-topbar.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( self::STYLE_HANDLE );
	}

	/** Haalt de inhoud op */
	protected function get_content() : ?array {
		$content =
			$this->get_event_content() ??
			null;

		return $content;
	}

	/** Haalt de evenementen-inhoud op */
	protected function get_event_content() : ?array {

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
