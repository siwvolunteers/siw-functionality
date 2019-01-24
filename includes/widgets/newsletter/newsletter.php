<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget met formulier voor Snel Zoeken
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Formatting
 * 
 * Widget Name: SIW: Nieuwsbrief
 * Description: Toont aanmeldformulier voor nieuwsbrief
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class SIW_Newsletter_Widget extends SIW_Widget {

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
	function __construct() {
		$this->widget_name = __( 'Nieuwsbrief', 'siw');
		$this->widget_description = __( 'Toont aanmeldformulier voor nieuwsbrief', 'siw' );
		$this->widget_fields = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw' ),
				'default' => __( 'Blijf op de hoogte', 'siw' ),
			],
			'list' => [
				'type'    => 'select',
				'label'   => __( 'Lijst', 'siw' ),
				'options' => siw_get_mailpoet_lists(),
			]
		];
		parent::__construct();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_content( $instance, $args, $template_vars, $css_name ) { 
		ob_start();
		?>
		<div>
			<div id="newsletter_message" class="text-center hidden"></div>
			<div id="newsletter_loading" class="text-center hidden"></div>
			<form id="siw_newsletter_subscription" method="post" autocomplete="on">
				<p>
				<?= sprintf( esc_html__( 'Meld je aan voor onze nieuwsbrief en voeg je bij de %d abonnees.', 'siw' ), $this->get_subscriber_count( $instance['list'] ) );?>
				</p>
				<?= SIW_Formatting::generate_field( 'text', [ 'label' => __( 'Voornaam', 'siw' ), 'name' => 'name', 'id' => 'newsletter_name', 'required' => true ], [ 'tag' => 'p' ] ) ;?>
				<?= SIW_Formatting::generate_field( 'email', [ 'label' => __( 'E-mail', 'siw' ), 'name' => 'email', 'id' => 'newsletter_email', 'required' => true ], [ 'tag' => 'p' ] ) ;?>
				<?= SIW_Formatting::generate_field( 'submit', [ 'value' => __( 'Aanmelden', 'siw') ], [ 'tag' => 'p'] ); ?>
				<?= SIW_Formatting::generate_field( 'hidden', [ 'value' => $instance['list'], 'name' => 'list_id', 'id' => 'newsletter_list_id' ] ); ?>
				<?php wp_nonce_field( 'siw_newsletter_nonce', 'newsletter_nonce', false );?>
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