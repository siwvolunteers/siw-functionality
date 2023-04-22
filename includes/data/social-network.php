<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Bevat informatie over een sociaal netwerk
 *
 * @copyright   2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Social_Network extends Data {

	/** Slug van het netwerk */
	protected string $slug;

	/** Naam van het netwerk */
	protected string $name;

	/** CSS-class van icoon */
	protected string $icon_class;

	/** Kleurcode */
	protected string $color;

	/** URL van netwerk om te volgen */
	protected ?string $follow_url;

	/** URL-template voor delen */
	protected ?string $share_url_template;

	/** Is netwerk om te delen? */
	protected bool $share;

	/** Is netwerk om te volgen? */
	protected bool $follow;

	/** Geeft slug van netwerk terug */
	public function get_slug(): string {
		return $this->slug;
	}

	/** Geeft de naam van het netwerk terug */
	public function get_name(): string {
		return $this->name;
	}

	/** Geeft icon class voor voor netwerk terug */
	public function get_icon_class(): string {
		return $this->icon_class;
	}

	/** Geeft kleurcode van netwerk terug */
	public function get_color(): string {
		return $this->color;
	}

	/** Geeft aan of via dit netwerk gedeeld kan worden */
	public function is_for_sharing(): bool {
		return $this->share;
	}

	/** Geeft aan of dit netwerk gevolgd kan worden */
	public function is_for_following(): bool {
		return $this->follow;
	}

	/** Geeft URL van network om te volgen terug */
	public function get_follow_url(): string {
		return $this->follow_url;
	}

	/** Geeft template voor url om te delen terug */
	public function get_share_url_template(): string {
		return $this->share_url_template;
	}

	/** Geeft aan of Sociaal netwerk geldig is voor een gegeven context */
	public function is_valid_for_context( Social_Network_Context $context ): bool {
		return (
			Social_Network_Context::ALL === $context
			|| ( Social_Network_Context::SHARE === $context && $this->is_for_sharing() )
			|| ( Social_Network_Context::FOLLOW === $context && $this->is_for_following() )
		);
	}
}
