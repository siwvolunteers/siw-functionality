<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Icons\Dashicons;

/**
 * Widget Name: SIW: Featured image
 * Description: Toont uitgelichte afbeelding
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Featured_Image extends Widget {

	#[\Override]
	protected function get_name(): string {
		return __( 'Uitgelichte afbeelding', 'siw' );
	}

	#[\Override]
	protected function get_description(): string {
		return __( 'Toont uitgelichte afbeelding', 'siw' );
	}

	#[\Override]
	protected function get_dashicon(): Dashicons {
		return Dashicons::COVER_IMAGE;
	}

	#[\Override]
	protected function supports_title(): bool {
		return true;
	}

	#[\Override]
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

	#[\Override]
	public function get_template_variables( $instance, $args ) {
		return [
			'content' => get_the_post_thumbnail( null, $instance['image_size'] ),
		];
	}
}
