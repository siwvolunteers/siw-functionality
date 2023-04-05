<?php declare(strict_types=1);

namespace SIW\Widgets;

/**
 * Widget met uitgelichte afbeelding
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Featured image
 * Description: Toont uitgelichte afbeelding
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Featured_Image extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'featured-image';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Uitgelichte afbeelding', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont uitgelichte afbeelding', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'cover-image';
	}

	/** {@inheritDoc} */
	protected function supports_title(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return false;
	}

	/** {@inheritDoc} */
	protected function get_widget_fields(): array {
		$widget_fields = [
			'image_size' => [
				'type'    => 'image-size',
				'label'   => __( 'Image size', 'siw' ),
				'default' => 'post-thumbnail',
			],
		];
		return $widget_fields;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {
		return [
			'content' => get_the_post_thumbnail( null, $instance['image_size'] ),
		];
	}
}
