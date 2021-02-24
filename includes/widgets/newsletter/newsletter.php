<?php declare(strict_types=1);

namespace SIW\Widgets;

/**
 * Widget met aanmeldformulier nieuwsbrief
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @widget_data
 * Widget Name: SIW: Nieuwsbrief
 * Description: Toont aanmeldformulier voor nieuwsbrief
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Newsletter extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'newsletter';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Nieuwsbrief', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont aanmeldformulier voor nieuwsbrief', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return $this->get_id();
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'email';
	}

	/** {@inheritDoc} */
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

	/** {@inheritDoc} */
	function get_template_variables( $instance, $args ) {

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
