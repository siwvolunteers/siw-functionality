<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\CTA_Hero;

/**
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
		return Widget::DEFAULT_TEMPLATE_ID;
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
			'headline'          => [
				'type'     => 'text',
				'label'    => __( 'Headline', 'siw' ),
				'required' => true,
			],
			'subheadline'       => [
				'type'  => 'text',
				'label' => __( 'Subheadline', 'siw' ),
			],
			'button_text'       => [
				'type'     => 'text',
				'label'    => __( 'Tekst voor knop', 'siw' ),
				'required' => true,
			],
			'button_url'        => [
				'type'        => 'text',
				'label'       => __( 'URL', 'siw' ),
				'sanitize'    => 'wp_make_link_relative',
				'description' => __( 'Relatief', 'siw' ),
				'required'    => true,
			],
			'background_images' => [
				'type'                 => 'multiple_media',
				'label'                => __( 'Achtergrondafbeeldingen', 'siw' ),
				'library'              => 'image',
				'thumbnail_dimensions' => [ 64, 64 ],
			],
		];
		return $widget_fields;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {

		if ( ! isset( $instance['background_images'] ) || empty( $instance['background_images'] ) ) {
			return [];
		}

		return [
			'content' => CTA_Hero::create()
				->set_headline( $instance['headline'] )
				->set_subheadline( $instance['subheadline'] ?? '' )
				->set_button_text( $instance['button_text'] )
				->set_button_url( $instance['button_url'] )
				->set_background_images_ids( $instance['background_images'] ?? [] )
				->generate(),
		];
	}
}
