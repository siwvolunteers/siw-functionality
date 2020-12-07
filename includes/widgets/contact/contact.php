<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements;
use SIW\Properties;
use SIW\Util\Links;

/**
 * Widget met contactinformatie
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data
 * Widget Name: SIW: Contactinformatie
 * Description: Toont contactinformatie.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Contact extends Widget {

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_id = 'contact';

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_dashicon = 'phone';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Contactinformatie', 'siw');
		$this->widget_description = __( 'Toont contactinformatie', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {
		$widget_forms = [
			'title' => [
				'type'      => 'text',
				'label'     => __( 'Titel', 'siw'),
				'default'   => __( 'Contact', 'siw' ),
			],
		];
		return $widget_forms;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_content( array $instance, array $args, array $template_vars, string $css_name ) : string {
		ob_start();
		?>
		<div class="siw-contact">
			<?php
			echo wpautop(
				implode(
					BR,
					[
						Properties::NAME,
						sprintf( '%s | %s %s', Properties::ADDRESS, Properties::POSTCODE, Properties::CITY ),
						sprintf( '%s | %s',
							Links::generate_tel_link( Properties::PHONE_INTERNATIONAL, Properties::PHONE ),
							Links::generate_mailto_link( Properties::EMAIL )
						),
						Links::generate_link(
							'https://api.whatsapp.com/send?phone='. Properties::WHATSAPP_FULL,
							Elements::generate_icon( 'siw-icon-whatsapp' ) . SPACE . Properties::WHATSAPP,
							[ 'class' => 'siw-contact-link'],
						),
						Elements::generate_opening_hours('table'),
					]
				)
			);
			?>
		</div>
		<div class="siw-social-links clearfix">
			<?php
			$social_networks = siw_get_social_networks( 'follow' );
			foreach ( $social_networks as $network ) {
				echo Links::generate_icon_link(
					$network->get_follow_url(),
					[
						'class'      => $network->get_icon_class(),
						'background' => 'circle'
					],
					[
						'class'               => $network->get_slug(),
						'title'               => $network->get_name(),
						'target'              => '_blank',
						'rel'                 => 'noopener external',
						'aria-label'          => sprintf( esc_attr__( 'Volg ons op %s', 'siw' ), $network->get_name() ),
						'data-balloon-pos'    => 'up',
						'data-original-title' => $network->get_name(),
						'style'               => '--hover-color: ' . $network->get_color(),
					],
				);
			}
			?>
		</div>
		<?php
		return ob_get_clean();
	}
}
