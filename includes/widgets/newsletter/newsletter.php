<?php declare(strict_types=1);

namespace SIW\Widgets;

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
	 */
	protected string $widget_id ='newsletter';

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_dashicon = 'email';

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
	protected function get_template_parameters( array $instance, array $args, array $template_vars, string $css_name ): array {

		$subscriber_count = siw_newsletter_get_subscriber_count( siw_get_option( 'newsletter_list' ) );
		return [
			'id' => uniqid(),
			'first_name' => [
				'label' => __( 'Voornaam', 'siw' ),
				'id'    => 'newsletter_name',
				'name'  => 'name',
				],
			'email' => [
				'label' => __( 'E-mail', 'siw' ),
				'id'    => 'newsletter_email',
				'name'  => 'email',
			],
			'i18n' => [
				'cta'       => sprintf( __( 'Meld je aan voor onze nieuwsbrief en voeg je bij de %d abonnees.', 'siw' ), $subscriber_count ),
				'subscribe' => __( 'Aanmelden', 'siw' )
			]
		];
	}
}
