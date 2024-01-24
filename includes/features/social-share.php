<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Data\Post_Type_Support;
use SIW\Data\Social_Network_Context;
use SIW\Elements\Social_Links;
use SIW\Helpers\Template;
use SIW\Traits\Class_Assets;

class Social_Share extends Base {

	use Class_Assets;

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_styles() {
		self::enqueue_class_style();
	}

	#[Add_Action( 'generate_after_content' )]
	public function render() {

		if ( ! is_single() || ! $this->is_supported_post_type() ) {
			return;
		}

		Template::create()
			->set_template( 'features/social-share' )
			->set_context(
				[
					'header'       => __( 'Delen', 'siw' ),
					'social_links' => Social_Links::create()
						->set_context( Social_Network_Context::SHARE )
						->generate(),
				]
			)
			->render_template();
	}

	protected function is_supported_post_type(): bool {
		return post_type_supports( get_post_type(), Post_Type_Support::SOCIAL_SHARE->value );
	}
}
