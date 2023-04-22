<?php declare(strict_types=1);

namespace SIW\Helpers;

use SIW\Data\Social_Network;
use SIW\Data\Social_Network_Context;
use SIW\Properties;
use SIW\Util\CSS;

/**
 * Class om een e-mail template te genereren
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Email_Template {

	// TODO: integreren in email helper?

	/** Context voor Mustache template */
	protected array $context = [];

	/** Constructor */
	protected function __construct() {}

	/** Creëer email */
	public static function create(): self {
		$self = new self();

		$self->context = [
			'properties'      => [
				'name'                => Properties::NAME,
				'phone'               => Properties::PHONE,
				'phone_international' => Properties::PHONE_INTERNATIONAL,
				'email'               => Properties::EMAIL,
				'site_url'            => SIW_SITE_URL,
				'site_name'           => SIW_SITE_NAME,
				'logo_url'            => wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ),
			],
			'colors'          => [
				'accent'         => CSS::ACCENT_COLOR,
				'contrast'       => CSS::CONTRAST_COLOR,
				'contrast_light' => CSS::CONTRAST_COLOR_LIGHT,
				'base'           => CSS::BASE_COLOR,
			],
			'i18n'            => [
				'with_kind_regards' => __( 'Met vriendelijke groet', 'siw' ),
				'entered_data'      => __( 'Ingevulde gegevens', 'siw' ),
				'visit_our_website' => __( 'Bezoek onze website', 'siw' ),
			],
			'social_networks' => array_values(
				array_map(
					fn( Social_Network $network ): array => [
						'follow_url' => $network->get_follow_url(),
						'image_url'  => SIW_ASSETS_URL . 'images/mail/' . $network->get_slug() . '.png',
						'slug'       => $network->get_slug(),
					],
					siw_get_social_networks( Social_Network_Context::FOLLOW )
				)
			),

		];
		return $self;
	}

	/** Zet ondertekening */
	public function set_signature( string $name ): self {
		$this->context['signature'] = [
			'name' => $name,
		];
		return $this;
	}

	/** Zet onderwerp */
	public function set_subject( string $subject ): self {
		$this->context['subject'] = $subject;
		return $this;
	}

	/** Zet boodschap */
	public function set_message( string $message ): self {
		$this->context['message'] = $message;
		return $this;
	}

	/** Zet gegevens voor samenvatting */
	public function set_summary_data( array $summary_data ): self {
		if ( ! wp_is_numeric_array( $summary_data ) ) {
			$summary_data = array_map(
				fn( string $label, string $value ): array => [
					'label' => $label,
					'value' => $value,
				],
				array_keys( $summary_data ),
				$summary_data
			);
			$summary_data = array_values( $summary_data );
		}

		$this->context['summary'] = [
			'show' => true,
			'data' => $summary_data,
		];
		return $this;
	}

	/** Genereert email template */
	public function generate(): string {
		return Template::create()
			->set_template( 'email' )
			->set_context( $this->context )
			->parse_template();
	}
}
