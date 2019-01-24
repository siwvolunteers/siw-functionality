<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Topbar
 * 
 * @package SIW\Modules
 * @author Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses SIW_Formatting
 * @uses SIW_Properties
 */
class SIW_Topbar {

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
	const EVENT_HIDE_DAYS_BEFORE = 2;

	/**
	 * Inhoud van de topbar
	 *
	 * @var string
	 */
	protected $content;

	public static function init() {
		$self = new self();
		if ( ! SIW_i18n::is_default_language() ) {
			return;
		}
		$self->content = $self->get_content();
		if ( false == $self->content ) {
			return;
		}
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_styles' ] );
		add_action( 'kt_before_header_content', [ $self, 'render' ] );
	}

	/**
	 * Rendert de topbar
	 *
	 * @return void
	 */
	public function render() {
		$target = isset( $this->content['link_target'] ) ? $this->content['link_target'] : '_self';
	
		?>
		<div id="topbar" class="topclass">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div id="topbar-content">
							<span class="hidden-xs hidden-sm"><?php echo esc_html( $this->content['intro'] );?>&nbsp;</span>
							<?= SIW_Formatting::generate_link( $this->content['link_url'], $this->content['link_text'], [ 'id' => 'topbar_link', 'target' => $target ] ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	}

	/**
	 * Voegt stylesheet toe
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		wp_register_style( 'siw-topbar', SIW_ASSETS_URL . 'css/siw-topbar.css', null, SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-topbar' );
	}

	/**
	 * Haalt de inhoud op
	 *
	 * @return array
	 */
	protected function get_content() {
		$social_content = $this->get_social_content();
		if ( ! empty( $social_content ) ) {
			return $social_content;
		}
	
		$event_content = $this->get_event_content();
		if ( ! empty( $event_content ) ) {
			return $event_content;
		}
	
		$sale_content = $this->get_sale_content();
		if ( ! empty( $sale_content ) ) {
			return $sale_content;
		}
	
		$job_content = $this->get_job_content();
		if ( ! empty ( $job_content ) ) {
			return $job_content;
		}
	
		return false;
	}

	/**
	 * Haalt de social media-inhoud op
	 *
	 * @return array
	 */
	protected function get_social_content() {

		if ( ! siw_get_setting( 'topbar_social_link_enabled' ) ||
			empty( siw_get_setting( 'topbar_social_link_date_end' ) ) ||
			date( 'Y-m-d' ) > siw_get_setting( 'topbar_social_link_date_end' ) ||
			empty( siw_get_setting( 'topbar_social_link_intro' ) ) ||
			empty( siw_get_setting( 'topbar_social_link_text' ) ) ||
			empty( siw_get_setting( 'topbar_social_link_network' ) )
			) {
			return false;
		}

		$social_networks = siw_get_social_networks('follow');

		$social_content = [
			'intro'       => siw_get_setting( 'topbar_social_link_intro' ),
			'link_url'    => $social_networks[ siw_get_setting( 'topbar_social_link_network') ]->get_follow_url(),
			'link_text'   => siw_get_setting( 'topbar_social_link_text' ),
			'link_target' => '_blank',
		];
		return $social_content;

	}

	/**
	 * Haalt de evenementen-inhoud op
	 *
	 * @return array
	 */
	protected function get_event_content() {

		$date_hide = strtotime( date( 'Y-m-d' ) ) + ( self::EVENT_SHOW_DAYS_BEFORE * DAY_IN_SECONDS );
		$date_show = strtotime( date( 'Y-m-d' ) ) + ( self::EVENT_HIDE_DAYS_BEFORE * DAY_IN_SECONDS );

		$upcoming_events = siw_get_upcoming_events( 1, $date_show, $date_hide ); //TODO: andere functie

		if ( empty ( $upcoming_events ) ) {
			return false;
		}
		$event = $upcoming_events[0];

		if ( $event['start_date'] == $event['end_date'] ) {
			$link_text = sprintf( __( 'Kom naar de %s op %s.', 'siw' ), $event['title'], $event['date_range'] );
		}
		else {
			$link_text = sprintf( __( 'Kom naar de %s van %s.', 'siw' ), $event['title'], $event['date_range'] );
		}

		$event_content = [
			'intro'     => __( 'Maak kennis met SIW.', 'siw' ),
			'link_url'  => $event['permalink'],
			'link_text' => $link_text,
		];

		return $event_content;
	}

	/**
	 * Haalt de vacature-inhoud op
	 *
	 * @return array
	 */
	protected function get_job_content() {
		$job = siw_get_featured_job(); //TODO:setting van maken
		if ( false == $job ) {
			return false;
		}
		$job_title = lcfirst( $job['title'] );
		$job_content = [
			'intro'     => __( 'Word actief voor SIW.', 'siw' ),
			'link_url'  => $job['permalink'],
			'link_text' => sprintf( __( 'Wij zoeken een %s.', 'siw' ), $job_title ),
		];
	
		return $job_content;
	}

	/**
	 * Haalt de kortingsactie-inhoud op
	 *
	 * @return void
	 */
	protected function get_sale_content() {
		if ( ! siw_is_sale_active() ) {
			return false;
		}

		$sale_tariff = SIW_Formatting::format_amount( SIW_Properties::get('workcamp_fee_regular_sale') );
		$end_date = SIW_Formatting::format_date( siw_get_setting( 'workcamp_sale_end_date' ), false );
	
		$sale_content = [
			'intro'     => __( 'Grijp je kans en ontvang korting!',  'siw' ),
			'link_url'  => wc_get_page_permalink( 'shop' ),
			'link_text' => sprintf( __( 'Meld je uiterlijk %s aan voor een project en betaal slechts %s.' , 'siw' ), $end_date, $sale_tariff ) ,
		];
	
		return $sale_content;
	}
}