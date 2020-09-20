<?php declare(strict_types=1);

namespace SIW\Newsletter;

use SIW\Util;
use SIW\Newsletter\Hash;

/**
 * Bevestigingspagina
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 */
class Confirmation_Page {

	/**
	 * Boodschap voor gebruiker
	 *
	 * @var string
	 */
	protected $message;

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();

		add_filter( 'body_class', [ $self, 'maybe_add_body_class' ] );
		add_action( 'wp', [ $self, 'maybe_process_confirmation'] );
		add_filter( 'template_include', [ $self, 'load_template' ] );
		add_action( 'siw_newsletter_confirmation', [ $self, 'show_message'] );
	}

	/**
	 * Verwerk aanmelding voor nieuwsbrief
	 */
	public function maybe_process_confirmation() {
		if ( ! $this->is_newsletter_confirmation() ) {
			return;
		}

		//Haal parameters van request op
		$hash = urldecode( Util::get_request_parameter( 'nl_hash' ) );
		$data = base64_decode( urldecode( Util::get_request_parameter( 'nl_data' ) ) );

		if ( ! Hash::data_is_valid( $data, $hash ) ) {
			$this->message = __( 'Helaas is er iets misgegaan met de aanmelding.', 'siw' );
			return;
		}

		//Afbreken als aanmelding al gedaan is TODO:: transient verplaatsen naar Mailjet-class?
		if ( get_transient( "siw_newsletter_confirm_{$hash}" ) ) {
			$this->message = __( 'Je bent al aangemeld voor de SIW-nieuwsbrief.', 'siw' );
			return;
		}

		$data = json_decode( $data, true );

		if ( siw_newsletter_subscribe( $data['email'], $data['list_id'], $data['properties'] ) ) {
			$this->message = __( 'Gefeliciteerd! Je bent nu aangemeld voor de SIW-nieuwsbrief.', 'siw' );
			//Transient zetten zodat aanmelding niet nog een keer verwerkt wordt bij opnieuw bezoeken pagina
			set_transient( "siw_newsletter_confirm_{$hash}", true, DAY_IN_SECONDS );
		}
		else {
			$this->message = __( 'Helaas is er iets misgegaan met de aanmelding.', 'siw' );
		}
	}

	/**
	 * Laadt template voor bevestigingspagina
	 *
	 * @param string $template
	 *
	 * @return string
	 */
	public function load_template( $template ) : string {
		if ( $this->is_newsletter_confirmation() ) {
			$template = SIW_TEMPLATES_DIR . '/newsletter-confirmation.php';
		}
		return $template;
	}

	/**
	 * Toont bevestingsmelding
	 */
	public function show_message() {
		echo wp_kses_post( $this->message );
	}

	/**
	 * Geeft aan of dit een bevestiging van de nieuwsbrief is
	 *
	 * @return bool
	 */
	protected function is_newsletter_confirmation() : bool {
		return is_front_page() && (bool) Util::get_request_parameter( 'nl_confirmation');
	}

	/**
	 * Voegt body class toe bij bevestigingspagina
	 *
	 * @param array $classes
	 *
	 * @return array
	 */
	public function maybe_add_body_class( array $classes ) : array {
		if ( $this->is_newsletter_confirmation() ) {
			$classes[] = 'siw-newsletter-confirmation';
		}
		return $classes;
	}
}
