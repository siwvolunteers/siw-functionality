<?php declare(strict_types=1);

namespace SIW\Modules;

use SIW\Core\Template;
use SIW\i18n;
use SIW\Properties;

/**
 * Topbar
 * 
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Topbar {

	/** Toon het evenement x aantal dagen van te voren */
	const EVENT_SHOW_DAYS_BEFORE = 14;

	/** Verberg het evenement y aantal dagen van te voren */
	const EVENT_HIDE_DAYS_BEFORE = 1;

	/** Instellingen */
	protected array $settings;

	/** Inhoud van de topbar */
	protected ?array $content;

	/** Init */
	public static function init() {
		$self = new self();
		if ( ! i18n::is_default_language() ) {
			return;
		}

		//Alleen in front-end tonen
		if ( is_admin() ) {
			return;
		}

		//Instellingen ophalen
		$self->settings = siw_get_option( 'topbar' );

		//Content zetten
		$self->content = $self->get_content();
		if ( is_null( $self->content ) ) {
			return;
		}
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_styles' ] );
		add_action( 'generate_before_header', [ $self, 'render' ] );
	}

	/** Rendert de topbar */
	public function render() {
		Template::render_template(
			'modules/topbar',
			[
				'target'               => $this->content['link_target'] ?? '_self',
				'link_url'             => $this->content['link_url'],
				'link_text'            => $this->content['link_text'],

			]
		);
	}

	/** Voegt stylesheet toe */
	public function enqueue_styles() {
		wp_register_style( 'siw-topbar', SIW_ASSETS_URL . 'css/modules/siw-topbar.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-topbar' );
	}

	/** Haalt de inhoud op */
	protected function get_content() : ?array {
	
		$content =
			$this->get_page_content() ??
			$this->get_event_content() ??
			$this->get_sale_content() ??
			null;

		return $content;
	}

	/** Haalt de evenementen-inhoud op */
	protected function get_event_content() : ?array {
		if ( ! $this->settings['show_event_content'] ) {
			return null;
		}
		
		$upcoming_events = siw_get_upcoming_events(
			[
				'number'      => 1,
				'date_before' => date( 'Y-m-d', strtotime( '+' . self::EVENT_SHOW_DAYS_BEFORE . ' days') ),
				'date_after'  => date( 'Y-m-d', strtotime( '+' . self::EVENT_HIDE_DAYS_BEFORE . ' days') ),
			]
		);

		if ( empty ( $upcoming_events ) ) {
			return null;
		}
		$event_id = $upcoming_events[0];

		$link_text = sprintf(
			__( 'Kom naar de %s op %s.', 'siw' ),
			get_the_title( $event_id ),
			siw_format_date( siw_meta( 'event_date', [], $event_id ), false )
		);

		return [
			'link_url'  => get_the_permalink( $event_id ),
			'link_text' => $link_text,
		];
	}

	/** Haalt de kortingsactie-inhoud op */
	protected function get_sale_content() : ?array {
		if ( ! $this->settings['show_sale_content'] ) {
			return null;
		}

		if ( ! siw_is_workcamp_sale_active() ) {
			return null;
		}

		$sale_price = siw_format_amount( Properties::WORKCAMP_FEE_REGULAR_SALE );
		$end_date = siw_format_date( siw_get_option( 'workcamp_sale.end_date' ), false );
	
		return [
			'link_url'  => wc_get_page_permalink( 'shop' ),
			'link_text' => sprintf( __( 'Meld je uiterlijk %s aan voor een project en betaal slechts %s.' , 'siw' ), $end_date, $sale_price ) ,
		];
	}

	/** Undocumented function */
	protected function get_page_content() {
		if ( ! $this->settings['show_page_content'] ) {
			return null;
		}

		if ( date( 'Y-m-d' ) < $this->settings['page_content']['start_date'] || date( 'Y-m-d' ) > $this->settings['page_content']['end_date'] ) {
			return null;
		}

		return [
			'link_url'  => $this->settings['page_content']['link_url'],
			'link_text' => $this->settings['page_content']['link_text'],
		];
	}
}
