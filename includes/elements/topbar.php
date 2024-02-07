<?php declare(strict_types=1);

namespace SIW\Elements;

class Topbar extends Element {

	protected string $url;
	protected string $text;

	#[\Override]
	protected function get_template_variables(): array {
		return [
			'url'  => $this->url,
			'text' => $this->text,
		];
	}

	public function set_url( string $url ): self {
		$this->url = $url;
		return $this;
	}

	public function set_text( string $text ): self {
		$this->text = $text;
		return $this;
	}

	#[\Override]
	public function enqueue_styles() {
		self::enqueue_class_style();
	}
}
