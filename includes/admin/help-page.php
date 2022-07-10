<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Elements\Accordion;

/**
 * Voegt help-pagina toe
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Help_Page {

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'admin_menu', [ $self, 'add_properties_page' ] );
	}

	/** Voegt adminpagina toe */
	public function add_properties_page() {

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
			Accordion::create()
				->add_items( $panes )
				->render();

	}
}
