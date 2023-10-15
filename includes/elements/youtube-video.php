<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * YouTube video
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class YouTube_Video extends Element {

	protected string $video_id;

	protected bool $autoplay = false;

	protected bool $mute = false;

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'video_id'     => $this->video_id,
			'video_params' => build_query(
				[
					'autoplay' => $this->autoplay,
					'mute'     => $this->mute,
				]
			),
		];
	}

	public function set_video_id( string $video_id ): static {
		$this->video_id = $video_id;
		return $this;
	}

	public function set_autoplay( bool $autoplay ): static {
		$this->autoplay = $autoplay;
		return $this;
	}

	public function set_mute( bool $mute ): static {
		$this->mute = $mute;
		return $this;
	}
}
