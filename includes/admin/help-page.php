<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Attributes\Action;
use SIW\Base;
use SIW\Elements\Accordion_Tabs;

/**
 * Voegt help-pagina toe
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Help_Page extends Base {

	#[Action( 'admin_menu' )]
	/** Voegt adminpagina toe */
	public function add_help_page() {

		if ( ! siw_get_option( 'faq.show_page' ) ) {
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

	/** Rendert de adminpagina */
	public function render_page() {

		$faq = siw_get_option( 'faq' );

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
