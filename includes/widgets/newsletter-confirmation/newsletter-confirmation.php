<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Icons\Dashicons;
use SIW\Jobs\Async\Export_To_Mailjet;

/**
 * Widget Name: SIW: Nieuwsbrief - bevestiging aanmelding
 * Description: TODO
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Newsletter_Confirmation extends Widget {

	// Constantes voor query args
	public const QUERY_ARG_EMAIL = 'nl_email';
	public const QUERY_ARG_EMAIL_HASH = 'nl_email_hash';
	public const QUERY_ARG_FIRST_NAME = 'nl_first_name';
	public const QUERY_ARG_FIRST_NAME_HASH = 'nl_first_name_hash';
	public const QUERY_ARG_LIST_ID = 'nl_list_id';
	public const QUERY_ARG_LIST_ID_HASH = 'nl_list_id_hash';

	#[\Override]
	protected function get_name(): string {
		return __( 'Nieuwsbrief - bevestiging', 'siw' );
	}

	#[\Override]
	protected function get_description(): string {
		return __( 'Toont bevestiging van nieuwsbrief', 'siw' );
	}

	#[\Override]
	protected function get_dashicon(): Dashicons {
		return Dashicons::RSS;
	}

	#[\Override]
	public function get_template_variables( $instance, $args ) {
		return [
			'content' => $this->process_confirmation(),
		];
	}

	/** Verwerk aanmelding voor de nieuwsbrief */
	protected function process_confirmation(): string {

		$email = $this->process_parameter( self::QUERY_ARG_EMAIL, self::QUERY_ARG_EMAIL_HASH );
		$first_name = $this->process_parameter( self::QUERY_ARG_FIRST_NAME, self::QUERY_ARG_FIRST_NAME_HASH );
		$list_id = $this->process_parameter( self::QUERY_ARG_LIST_ID, self::QUERY_ARG_LIST_ID_HASH );

		if ( null === $email || null === $first_name || null === $list_id ) {
			return __( 'Helaas is er iets misgegaan met de aanmelding.', 'siw' );
		}

		$data = [
			'email'      => $email,
			'list_id'    => (int) $list_id,
			'properties' => [
				'firstname' => $first_name,
			],
		];
		as_enqueue_async_action( Export_To_Mailjet::class, $data );

		return __( 'Gefeliciteerd! Je bent nu aangemeld voor de SIW-nieuwsbrief.', 'siw' );
	}

	protected function process_parameter( string $parameter_query_arg, string $parameter_hash_query_arg ): ?string {
		// Haal parameters van request op
		$parameter_raw = get_query_arg( $parameter_query_arg ) ?? '';
		$parameter_hash_raw = get_query_arg( $parameter_hash_query_arg ) ?? '';

		// Check of de parameter en de hash gevuld zijn
		if ( empty( $parameter_raw ) || empty( $parameter_hash_raw ) ) {
			return null;
		}

		// Decode de parameter en de hash
		$parameter = base64_decode( urldecode( $parameter_raw ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
		$parameter_hash = urldecode( $parameter_hash_raw );

		// Check of hash correct is
		if ( ! hash_equals( siw_hash( $parameter ), $parameter_hash ) ) {
			return null;
		}

		return $parameter;
	}
}
