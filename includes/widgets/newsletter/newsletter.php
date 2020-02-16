<?php

namespace SIW\Widgets;

use SIW\HTML;

/**
 * Widget met aanmeldformulier nieuwsbrief
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data
 * Widget Name: SIW: Nieuwsbrief
 * Description: Toont aanmeldformulier voor nieuwsbrief
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Newsletter extends Widget {

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_id ='newsletter';

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_dashicon = 'email';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Nieuwsbrief', 'siw');
		$this->widget_description = __( 'Toont aanmeldformulier voor nieuwsbrief', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'     => 'text',
				'label'    => __( 'Titel', 'siw' ),
				'default'  => __( 'Blijf op de hoogte', 'siw' ),
				'required' => true,
			],
		];
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_content( array $instance, array $args, array $template_vars, string $css_name ) { 

		$selectors = [
			'form'    => "#{$args['widget_id']} form",
			'name'    => "#{$args['widget_id']} #newsletter_name",
			'email'   => "#{$args['widget_id']} #newsletter_email",
			'loading' => "#{$args['widget_id']} .loading",
			'message' => "#{$args['widget_id']} .message",

		];

		ob_start();
		?>
		<div data-siw-newsletter-selectors="<?php echo esc_attr( json_encode( $selectors ) );?>">
			<div class="text-center loading hidden"></div>
			<div class="text-center message hidden"></div>
			<form method="post" autocomplete="on" id="newsletter_form">
				<p>
				<?= sprintf( esc_html__( 'Meld je aan voor onze nieuwsbrief en voeg je bij de %d abonnees.', 'siw' ), siw_newsletter_get_subscriber_count( $instance['list'] ) );?>
				</p>
				<?php
				echo HTML::generate_field( 'text', [ 'label' => __( 'Voornaam', 'siw' ), 'id' => 'newsletter_name', 'name' => 'name', 'required' => true ], [ 'tag' => 'p' ] );
				echo HTML::generate_field( 'email', [ 'label' => __( 'E-mail', 'siw' ), 'id' => 'newsletter_email', 'name' => 'email', 'required' => true ], [ 'tag' => 'p' ] );
				echo HTML::generate_field( 'submit', [ 'value' => __( 'Aanmelden', 'siw' ) ], [ 'tag' => 'p'] );
				?>
			</form>
		</div>
		<?php
		$content = ob_get_clean();
		return $content;
	}
}
