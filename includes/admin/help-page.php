<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Elements\Accordion_Tabs;
use SIW\Facades\Meta_Box;

class Help_Page extends Base {

	#[Add_Action( 'admin_menu' )]
	public function add_help_page() {

		if ( ! Meta_Box::get_option( 'faq.show_page' ) ) {
			return;
		}
		add_menu_page(
			__( 'Help', 'siw' ),
			__( 'Help', 'siw' ),
			'edit_posts',
			'siw-help-page',
			[ $this, 'render_page' ],
			'dashicons-editor-help'
		);
	}

	public function render_page() {

		$faq = Meta_Box::get_option( 'faq' );

		foreach ( $faq['questions'] as $question ) {
			$panes[] = [
				'title'   => $question['question'],
				'content' => $question['answer'],
			];
		}

		?>
		<h2><?php echo esc_html__( 'Q&A', 'siw' ); ?></h2>
		<?php
			Accordion_Tabs::create()
				->add_items( $panes )
				->render();
	}
}
