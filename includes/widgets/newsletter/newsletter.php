<?php
/*
 * Widget Name: SIW: Nieuwsbrief
 * Description: Toont aanmeldformulier voor nieuwsbrief
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget met aanmeldformulier nieuwsbrief
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Formatting
 */
class SIW_Widget_Newsletter extends SIW_Widget {

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
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw' ),
				'default' => __( 'Blijf op de hoogte', 'siw' ),
			],
		];
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', function() {
		$inline_script = "
			$( '.so-widget-siw_newsletter_widget form' ).submit(function( event ) {
				event.preventDefault();
				siwNewsletterSubscribeFromForm( '.so-widget-siw_newsletter_widget' );
				return false;
			});";
		wp_add_inline_script( 'siw-newsletter', "(function( $ ) {" . $inline_script . "})( jQuery );" );
		});
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_content( $instance, $args, $template_vars, $css_name ) { 
		ob_start();
		?>
		<div>
			<div class="text-center loading hidden"></div>
			<div class="text-center message hidden"></div>
			<form method="post" autocomplete="on">
				<p>
				<?= sprintf( esc_html__( 'Meld je aan voor onze nieuwsbrief en voeg je bij de %d abonnees.', 'siw' ), $this->get_subscriber_count( siw_get_setting( 'newsletter_list' ) ) );?>
				</p>
				<?= SIW_Formatting::generate_field( 'text', [ 'label' => __( 'Voornaam', 'siw' ), 'name' => 'name', 'required' => true ], [ 'tag' => 'p' ] ) ;?>
				<?= SIW_Formatting::generate_field( 'email', [ 'label' => __( 'E-mail', 'siw' ), 'name' => 'email', 'required' => true ], [ 'tag' => 'p' ] ) ;?>
				<?= SIW_Formatting::generate_field( 'submit', [ 'value' => __( 'Aanmelden', 'siw') ], [ 'tag' => 'p'] ); ?>
			</form>
		</div>
		<?php
		$content = ob_get_clean();
		return $content;
	}

	/**
	 * Geeft aantal abonnees van lijst
	 * 
	 * @param int $list
	 * @return int
	 */
	protected function get_subscriber_count( $list ) {
		$subscriber_count = do_shortcode( '[wysija_subscribers_count list_id="' . esc_attr( $list ) . '" ]' );
		return $subscriber_count;
	}
}
