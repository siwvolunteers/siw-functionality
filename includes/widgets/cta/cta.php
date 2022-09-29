<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\I18n;
use SIW\Util;

/**
 * Widget met Call to Action
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: CTA
 * Description: Toont call to action
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class CTA extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'cta';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'CTA', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont call to action', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return $this->get_id();
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'megaphone';
	}

	/** {@inheritDoc} */
	protected function supports_title(): bool {
		return false;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return false;
	}

	/** {@inheritDoc} */
	protected function get_widget_fields(): array {
		$widget_fields = [
			'headline'    => [
				'type'  => 'text',
				'label' => __( 'Headline', 'siw' ),
			],
			'button_text' => [
				'type'  => 'text',
				'label' => __( 'Tekst voor knop', 'siw' ),
			],
			'button_page' => [
				'type'    => 'select',
				'label'   => __( 'Pagina voor knop', 'siw' ),
				'prompt'  => __( 'Selecteer een pagina', 'siw' ),
				'options' => Util::get_pages(),
			],
			'align'       => [
				'type'    => 'select',
				'label'   => __( 'Uitlijning', 'siw' ),
				'options' => [
					'left'   => __( 'Links', 'siw' ),
					'center' => __( 'Midden', 'siw' ),
					'right'  => __( 'Rechts', 'siw' ),
				],
				'default' => 'center',
			],
		];
		return $widget_fields;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {
		return [
			'headline' => $instance['headline'],
			'align'    => $instance['align'],
			'button'   => [
				'url'  => I18n::get_translated_page_url( intval( $instance['button_page'] ) ),
				'text' => $instance['button_text'],
			],
		];
	}

	/** {@inheritDoc} */
	public function initialize() {
		$this->register_frontend_styles(
			[
				[
					'siw-widget-cta',
					SIW_ASSETS_URL . 'css/widgets/cta.css',
					[],
					SIW_PLUGIN_VERSION,
				],
			]
		);
	}
}
