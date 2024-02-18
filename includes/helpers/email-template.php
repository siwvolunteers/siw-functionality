<?php declare(strict_types=1);

namespace SIW\Helpers;

use SIW\Data\Color;
use SIW\Data\Social_Network;
use SIW\Data\Social_Network_Context;
use SIW\Properties;
class Email_Template {

	// TODO: integreren in email helper?

	protected string $template;

	protected array $context = [];

	protected function __construct() {}

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
				'accent'         => Color::ACCENT->color(),
				'contrast'       => Color::CONTRAST->color(),
				'contrast_light' => Color::CONTRAST_LIGHT->color(),
				'base'           => Color::BASE->color(),
			],
			'social_networks' => array_values(
				array_map(
					fn( Social_Network $network ): array => [
						'follow_url' => $network->profile_url(),
						'image_url'  => SIW_ASSETS_URL . 'images/mail/' . $network->value . '.png',
						'slug'       => $network->value,
					],
					Social_Network::filter( Social_Network_Context::FOLLOW )
				)
			),
		];
		return $self;
	}

	public function set_signature( string $name ): self {
		$this->context['signature'] = [
			'name' => $name,
		];
		return $this;
	}

	public function set_subject( string $subject ): self {
		$this->context['subject'] = $subject;
		return $this;
	}

	public function set_template( string $template ): self {
		$this->template = str_replace( '_', '-', $template );
		return $this;
	}

	public function add_context( array $context ): self {
		$this->context += $context;
		return $this;
	}

	public function add_table_data( array $data, string $heading = null ): self {
		if ( ! wp_is_numeric_array( $data ) ) {
			$data = array_map(
				fn( string $label, string $value ): array => [
					'label' => $label,
					'value' => $value,
				],
				array_keys( $data ),
				$data
			);
			$data = array_values( $data );
		}

		$data = array_filter(
			$data,
			fn( $item ): bool=> ! empty( $item['label'] && ! empty( $item['value'] ) )
		);

		$this->context['table_data'][] = [
			'heading' => $heading,
			'data'    => $data,
		];
		$this->context['has_table_data'] = true;
		return $this;
	}

	public function generate(): string {
		return Template::create()
			->set_template( 'emails/' . $this->template )
			->set_context( $this->context )
			->parse_template();
	}
}
