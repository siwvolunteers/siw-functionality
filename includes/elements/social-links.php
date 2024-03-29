<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Data\Social_Network;
use SIW\Data\Social_Network_Context;
use SIW\Helpers\Template;

class Social_Links extends Element {

	protected Social_Network_Context $context;

	#[\Override]
	protected function get_template_variables(): array {

		$social_networks = Social_Network::filter( $this->context );

		foreach ( $social_networks as $network ) {
			$networks[] = [
				'url'   => ( Social_Network_Context::SHARE === $this->context ) ? $this->generate_share_url( $network ) : $network->profile_url(),
				'name'  => $network->label(),
				'label' => sprintf(
					// translators: %s is de naam van een sociaal netwerk
					( Social_Network_Context::SHARE === $this->context ) ? __( 'Delen via %s', 'siw' ) : __( 'Volg ons op %s', 'siw' ),
					$network->label()
				),
				'color' => $network->color(),
				'icon'  => [
					'class' => $network->icon_class()->value,
					'size'  => 3,
				],
			];
		}

		return [
			'social_networks' => $networks,
		];
	}

	protected function determine_content_type(): string {
		$content_type = get_post_type();
		if ( str_starts_with( $content_type, 'siw_' ) ) {
			$content_type = substr( $content_type, 4 );
		}
		return $content_type;
	}

	protected function generate_share_url( Social_Network $network ) {
		return Template::create()
		->set_template( $network->share_template() )
		->set_context(
			[
				'url'   => rawurlencode( get_permalink() ),
				'title' => rawurlencode( html_entity_decode( get_the_title() ) ),
			]
		)
		->parse_template();
	}

	public function set_context( Social_Network_Context $context ): self {
		$this->context = $context;
		return $this;
	}

	#[\Override]
	public function enqueue_styles() {
		self::enqueue_class_style();
	}
}
