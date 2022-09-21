<?php declare(strict_types=1);

namespace SIW\Modules;

use SIW\Data\Social_Network;
use SIW\Elements\Social_Links;
use SIW\Helpers\Template;

/**
 * Voegt share-links toe voor social netwerken
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Social_Share {

	/** Post type van huidige post */
	protected string $post_type;

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'generate_after_content', [ $self, 'render' ] );
	}

	/** Toont de share links */
	public function render() {

		if ( ! is_single() || ! $this->is_supported_post_type() ) {
			return;
		}

		$title = get_the_title();
		$url = get_permalink();

		Template::create()
			->set_template( 'modules/social-share' )
			->set_context(
				[
					'content' => Social_Links::create()
						->set_context( Social_Network::SHARE )
						->set_header( $this->get_title() )
						->set_title( $title )
						->set_url( $url )
						->generate(),
				]
			)
			->render_template();
	}

	/** Genereert de titel */
	protected function get_title() {
		return $this->get_post_type_settings()[ $this->post_type ] ?? '';
	}

	/** Geeft aan of dit een ondersteunde post type is */
	protected function is_supported_post_type(): bool {
		$this->post_type = get_post_type();
		return in_array( $this->post_type, array_keys( $this->get_post_type_settings() ), true );
	}

	/** Haal instelling van post type op */
	protected function get_post_type_settings(): array {
		return apply_filters( 'siw_social_share_post_types', [] );
	}

}
