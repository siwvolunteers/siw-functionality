<?php declare(strict_types=1);

namespace SIW\Forms\Forms;

use SIW\Config;
use SIW\Data\Special_Page;
use SIW\Elements\Link;
use SIW\Forms\Form;
use SIW\Widgets\Newsletter_Confirmation;
use WP_REST_Request;

class Newsletter extends Form {

	#[\Override]
	public function get_name(): string {
		return __( 'Aanmelding nieuwsbrief', 'siw' );
	}

	#[\Override]
	protected function should_send_notification_mail(): bool {
		return false;
	}

	#[\Override]
	public function get_fields(): array {
		$fields = [
			[
				'id'   => 'first_name',
				'name' => __( 'Voornaam', 'siw' ),
				'type' => 'text',
			],
			[
				'id'   => 'email',
				'name' => __( 'E-mailadres', 'siw' ),
				'type' => 'email',
			],
			[
				'id'      => 'list_id',
				'type'    => 'hidden',
				'std'     => Config::get_mailjet_newsletter_list_id(),
				'columns' => Form::FULL_WIDTH,
			],
		];
		return $fields;
	}

	#[\Override]
	protected function get_template_context( WP_REST_Request $request ): array {
		$confirmation_page = Special_Page::NEWSLETTER_CONFIRMATION->get_page();
		$context = parent::get_template_context( $request );
		// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		$context['confirmation_url'] = add_query_arg(
			[
				Newsletter_Confirmation::QUERY_ARG_EMAIL   => rawurlencode( base64_encode( $request->get_param( 'email' ) ) ),
				Newsletter_Confirmation::QUERY_ARG_EMAIL_HASH => rawurlencode( siw_hash( $request->get_param( 'email' ) ) ),
				Newsletter_Confirmation::QUERY_ARG_FIRST_NAME => rawurlencode( base64_encode( $request->get_param( 'first_name' ) ) ),
				Newsletter_Confirmation::QUERY_ARG_FIRST_NAME_HASH => rawurlencode( siw_hash( $request->get_param( 'first_name' ) ) ),
				Newsletter_Confirmation::QUERY_ARG_LIST_ID => rawurlencode( base64_encode( $request->get_param( 'list_id' ) ) ),
				Newsletter_Confirmation::QUERY_ARG_LIST_ID_HASH => rawurlencode( siw_hash( $request->get_param( 'list_id' ) ) ),
			],
			untrailingslashit( get_permalink( $confirmation_page ) )
		);
			// phpcs:enable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		return $context;
	}
}
