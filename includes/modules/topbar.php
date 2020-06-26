<?php

namespace SIW\Modules;

use SIW\i18n;
use SIW\Formatting;
use SIW\Util;
use SIW\Properties;
use SIW\Util\Links;

/**
 * Topbar
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Topbar {

	/**
	 * Toon het evenement x aantal dagen van te voren
	 * 
	 * @var int
	 */
	const EVENT_SHOW_DAYS_BEFORE = 14;

	/**
	 * Verberg het evenement y aantal dagen van te voren
	 * 
	 * @var int
	 */
	const EVENT_HIDE_DAYS_BEFORE = 1;

	/**
	 * Instellingen
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Inhoud van de topbar
	 *
	 * @var string
	 */
	protected $content;

	/**
	 * Init
	 */
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
		if ( false == $self->content ) {
			return;
		}
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_styles' ] );
		add_action( 'generate_before_header', [ $self, 'render' ] );
	}

	/**
	 * Rendert de topbar
	 */
	public function render() {
		$target = isset( $this->content['link_target'] ) ? $this->content['link_target'] : '_self';
	
		?>
		<div class="topbar">
			<div class="topbar-content grid-container">
				<span class="hide-on-mobile hide-on-tablet"><?php echo esc_html( $this->content['intro'] );?>&nbsp;</span>
					<?php
					echo Links::generate_link(
						$this->content['link_url'],
						$this->content['link_text'],
						[
							'id'               => 'topbar_link',
							'class'            => 'button ghost',
							'target'           => $target,
							'data-ga-track'    => 1,
							'data-ga-type'     => 'event',
							'data-ga-category' => 'Topbar',
							'data-ga-action'   => 'Klikken',
							'data-ga-label'    => $this->content['link_url'],
						]
					);
				?>
				
			</div>
		</div>
	<?php
	}

	/**
	 * Voegt stylesheet toe
	 */
	public function enqueue_styles() {
		wp_register_style( 'siw-topbar', SIW_ASSETS_URL . 'css/modules/siw-topbar.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-topbar' );
	}

	/**
	 * Haalt de inhoud op
	 *
	 * @return array
	 */
	protected function get_content() {
	
		$content =
			$this->get_custom_content() ??
			$this->get_event_content() ??
			$this->get_sale_content() ??
			$this->get_job_posting_content() ??
			false;

		return $content;
	}

	/**
	 * Haalt de evenementen-inhoud op
	 *
	 * @return array
	 */
	protected function get_event_content() {
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
			Formatting::format_date( siw_meta( 'event_date', [], $event_id ), false )
		);

		return [
			'intro'     => __( 'Maak kennis met SIW.', 'siw' ),
			'link_url'  => get_the_permalink( $event_id ),
			'link_text' => $link_text,
		];
	}

	/**
	 * Haalt de vacature-inhoud op
	 *
	 * @return array
	 */
	protected function get_job_posting_content() {
		if ( ! $this->settings['show_job_posting_content'] ) {
			return null;
		}

		$jobs = siw_get_featured_job_postings();
		if ( empty ( $jobs ) ) {
			return null;
		}
		$job_id = $jobs[0];

		$job_title = lcfirst( get_the_title( $job_id) );
		return [
			'intro'     => __( 'Word actief voor SIW.', 'siw' ),
			'link_url'  => get_the_permalink( $job_id ),
			'link_text' => sprintf( __( 'Wij zoeken een %s.', 'siw' ), $job_title ),
		];
	}

	/**
	 * Haalt de kortingsactie-inhoud op
	 * 
	 * @return array
	 * 
	 * @todo kortingsactie Op Maat toevoegen
	 */
	protected function get_sale_content() {
		if ( ! $this->settings['show_sale_content'] ) {
			return null;
		}

		if ( ! Util::is_workcamp_sale_active() ) {
			return null;
		}

		$sale_price = Formatting::format_amount( Properties::WORKCAMP_FEE_REGULAR_SALE );
		$end_date = Formatting::format_date( siw_get_option( 'workcamp_sale' )['end_date'], false );
	
		return [
			'intro'     => __( 'Grijp je kans en ontvang korting!', 'siw' ),
			'link_url'  => wc_get_page_permalink( 'shop' ),
			'link_text' => sprintf( __( 'Meld je uiterlijk %s aan voor een project en betaal slechts %s.' , 'siw' ), $end_date, $sale_price ) ,
		];
	}

	/**
	 * Undocumented function
	 *
	 * @return array
	 */
	protected function get_custom_content() {
		if ( ! $this->settings['show_custom_content'] ) {
			return null;
		}

		if ( date( 'Y-m-d' ) < $this->settings['custom_content']['start_date'] || date( 'Y-m-d' ) > $this->settings['custom_content']['end_date'] ) {
			return null;
		}

		return [
			'intro'     => $this->settings['custom_content']['intro'],
			'link_url'  => $this->settings['custom_content']['link_url'],
			'link_text' => $this->settings['custom_content']['link_text'],
		];
	}
}
