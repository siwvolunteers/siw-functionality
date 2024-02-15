<?php declare(strict_types=1);

namespace SIW\Forms;

use SIW\Util\Logger;

class Submission_Rate_Limit {

	private const SUBMISSION_RATE_LIMIT = 99;
	private const SUBMISSION_RATE_LIMIT_INTERVAL = HOUR_IN_SECONDS;

	public static function check( \WP_REST_Request $request, string $form_id ): bool|\WP_Error {
		$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) );
		$transient_name = "siw_form_{$form_id}_{$ip}";
		$submission_count = (int) get_transient( $transient_name );
		++$submission_count;
		set_transient( $transient_name, $submission_count, self::SUBMISSION_RATE_LIMIT_INTERVAL );
		if ( $submission_count > self::SUBMISSION_RATE_LIMIT ) {
			Logger::info( "Meerdere aanmeldingen ({$submission_count}) in korte tijd voor formulier '{$form_id}' vanaf IP {$ip}", __METHOD__ );
			return new \WP_Error(
				'duplicate_submission',
				__( 'Je hebt dit formulier al ingevuld.', 'siw' ),
				[ 'status' => \WP_Http::TOO_MANY_REQUESTS ]
			);
		}

		return true;
	}
}
