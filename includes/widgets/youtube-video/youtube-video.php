<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\YouTube_Video as YouTube_Video_Element;

/**
 * @widget_data
 * Widget Name: SIW: Youtube video
 * Description: Toont youtube video
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class YouTube_Video extends Widget {

	#[\Override]
	protected function get_id(): string {
		return 'youtube_video';
	}

	#[\Override]
	protected function get_name(): string {
		return __( 'YouTube video', 'siw' );
	}

	#[\Override]
	protected function get_description(): string {
		return __( 'Toont YouTube video', 'siw' );
	}

	#[\Override]
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	#[\Override]
	protected function get_dashicon(): string {
		return 'youtube';
	}

	#[\Override]
	protected function supports_title(): bool {
		return true;
	}

	#[\Override]
	protected function supports_intro(): bool {
		return true;
	}

	#[\Override]
	public function get_widget_fields(): array {
		$widget_fields = [
			'video_id' => [
				'type'  => 'text',
				'label' => __( 'Video ID', 'siw' ),
			],
			'autoplay' => [
				'type'    => 'checkbox',
				'label'   => __( 'Autoplay', 'siw' ),
				'default' => false,
			],
			'mute'     => [
				'type'    => 'checkbox',
				'label'   => __( 'Mute', 'siw' ),
				'default' => false,
			],
		];
		return $widget_fields;
	}

	#[\Override]
	public function get_template_variables( $instance, $args ) {

		if ( ! isset( $instance['video_id'] ) || empty( $instance['video_id'] ) ) {
			return [];
		}

		return [
			'content' => YouTube_Video_Element::create()
				->set_video_id( $instance['video_id'] )
				->set_autoplay( $instance['autoplay'] )
				->set_mute( $instance['mute'] )
				->generate(),
		];
	}
}
