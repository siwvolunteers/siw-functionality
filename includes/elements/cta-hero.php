<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Util\CSS;

/**
 * Class om CTA Hero te genereren
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class CTA_Hero extends Element {

	const ASSETS_HANDLE = 'siw-cta-hero';

	private const DEFAULT_DISPLAY_TIME = 3;
	private const DEFAULT_TRANSITION_TIME = 2;

	protected string $headline;
	protected string $subheadline;
	protected string $button_text;
	protected string $button_url;
	protected int $display_time = self::DEFAULT_DISPLAY_TIME;
	protected int $transition_time = self::DEFAULT_TRANSITION_TIME;
	protected array $background_image_ids;

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'cta-hero';
	}

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

	/** {@inheritDoc} */
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
			'background_images' => $background_images,
		];
	}

	/** {@inheritDoc} */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/elements/cta-hero.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/elements/cta-hero.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );

		$keyframe_2 = $this->display_time / $this->determine_animation_duration() * 100;
		$keyframe_3 = 1 / $this->get_background_images_count() * 100;
		$keyframe_4 = 100 - ( $this->display_time / $this->determine_animation_duration() * 100 );

		$css = CSS::get_css_generator();
		$css->open_block( "keyframes siwHeroFade{$this->get_background_images_count()}" );

		$css->add_rule(
			'0%',
			[
				'opacity'                   => 1,
				'animation-timing-function' => 'ease-in',
			]
		);
		$css->add_rule(
			"{$keyframe_2}%",
			[
				'opacity'                   => 1,
				'animation-timing-function' => 'ease-out',
			]
		);
		$css->add_rule(
			"{$keyframe_3}%",
			[
				'opacity' => 0,
			]
		);
		$css->add_rule(
			"{$keyframe_4}%",
			[
				'opacity' => 0,
			]
		);
		$css->add_rule(
			'100%',
			[
				'opacity' => 1,
			]
		);
		$css->close_block();

		wp_add_inline_style( self::ASSETS_HANDLE, $css->get_output() );
	}

}
