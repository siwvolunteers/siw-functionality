<?php declare(strict_types=1);

namespace SIW\Elements;

use luizbills\CSS_Generator\Generator;

class CTA_Hero extends Element {

	private const DEFAULT_DISPLAY_TIME = 3;
	private const DEFAULT_TRANSITION_TIME = 2;

	protected string $headline;
	protected string $subheadline;
	protected string $button_text;
	protected string $button_url;
	protected int $display_time = self::DEFAULT_DISPLAY_TIME;
	protected int $transition_time = self::DEFAULT_TRANSITION_TIME;
	protected array $background_image_ids;

	public function set_headline( string $headline ): self {
		$this->headline = $headline;
		return $this;
	}

	public function set_subheadline( string $subheadline ): self {
		$this->subheadline = $subheadline;
		return $this;
	}

	public function set_button_url( string $button_url ): self {
		$this->button_url = $button_url;
		return $this;
	}

	public function set_button_text( string $button_text ): self {
		$this->button_text = $button_text;
		return $this;
	}

	public function set_display_time( int $display_time ): self {
		$this->display_time = $display_time;
		return $this;
	}

	public function set_transition_time( int $transition_time ): self {
		$this->transition_time = $transition_time;
		return $this;
	}

	public function set_background_images_ids( array $background_image_ids ): self {
		$this->background_image_ids = $background_image_ids;
		return $this;
	}

	protected function get_background_images_count(): int {
		return count( $this->background_image_ids );
	}

	protected function determine_animation_duration(): int {
		return ( $this->display_time + $this->transition_time ) * $this->get_background_images_count();
	}

	#[\Override]
	protected function get_template_variables(): array {

		$animation_delay = 0;
		$background_images = [];
		foreach ( $this->background_image_ids as $background_image_id ) {
			$background_images[] = [
				'animation_delay'      => $animation_delay,
				'animation_duration'   => $this->determine_animation_duration(),
				'animation_name'       => "siwHeroFade{$this->get_background_images_count()}",
				'background_image_url' => wp_get_attachment_url( $background_image_id ),
			];
			$animation_delay += ( $this->display_time + $this->transition_time );
		}

		return [
			'headline'          => $this->headline,
			'subheadline'       => $this->subheadline,
			'button'            => [
				'url'  => $this->button_url,
				'text' => $this->button_text,
			],
			'background_images' => array_reverse( $background_images ),
		];
	}

	#[\Override]
	public function enqueue_styles() {
		self::enqueue_class_style();

		$keyframe_2 = $this->display_time / $this->determine_animation_duration() * 100;
		$keyframe_3 = 1 / $this->get_background_images_count() * 100;
		$keyframe_4 = 100 - ( $this->display_time / $this->determine_animation_duration() * 100 );

		$css_rules = [];
		$css_rules[] = [
			'selector'     => '0%',
			'declarations' => [
				'opacity'                   => 1,
				'animation-timing-function' => 'ease-in',
			],
		];

		$css_rules[] = [
			'selector'     => "{$keyframe_2}%",
			'declarations' => [
				'opacity'                   => 1,
				'animation-timing-function' => 'ease-out',
			],
		];

		$css_rules[] = [
			'selector'     => "{$keyframe_3}%",
			'declarations' => [
				'opacity' => 0,
			],
		];

		$css_rules[] = [
			'selector'     => "{$keyframe_4}%",
			'declarations' => [
				'opacity' => 0,
			],
		];
		$css_rules[] = [
			'selector'     => '100%',
			'declarations' => [
				'opacity' => 1,
			],
		];

		// Nodig tot https://github.com/WordPress/gutenberg/pull/58918 in WP zit
		$inline_css = sprintf(
			"@keyframes siwHeroFade{$this->get_background_images_count()}{%s}",
			wp_style_engine_get_stylesheet_from_css_rules( $css_rules )
		);

		wp_add_inline_style( self::get_asset_handle(), $inline_css );
	}
}
