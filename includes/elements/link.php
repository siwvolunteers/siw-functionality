<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Data\Elements\Link\Rel;
use SIW\Data\Elements\Link\Target;

class Link extends Element {

	protected string $url;
	protected ?string $text = null;
	protected Target $target = Target::SELF;

	/** @var Rel[] */
	protected array $rel = [];
	protected array $attributes = [];

	public function set_url( string $url ): static {
		$this->url = $url;
		return $this;
	}

	public function set_text( string $text ): static {
		$this->text = $text;
		return $this;
	}

	public function set_target( Target $target ): static {
		$this->target = $target;
		return $this;
	}

	public function add_rel( Rel $rel ): static {
		$this->rel[] = $rel;
		return $this;
	}

	public function set_is_external(): static {
		$this->add_rel( Rel::EXTERNAL );
		$this->add_rel( Rel::NOOPENER );
		$this->set_target( Target::BLANK );
		return $this;
	}


	public function add_attribute( string $attribute, array|string|bool $value ): static {
		$this->attributes[ $attribute ] = $value;
		return $this;
	}

	#[\Override]
	protected function get_template_variables(): array {
		return [
			'url'        => $this->url,
			'text'       => $this->text ?? $this->url,
			'target'     => $this->target->value,
			'rel'        => implode( ' ', array_map( fn( Rel $rel ): string => $rel->value, $this->rel ) ),
			'attributes' => \build_html_attributes( $this->attributes ),
		];
	}
}
