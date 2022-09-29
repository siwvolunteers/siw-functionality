<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\Form as Form_Element;

/**
 * Widget met Formulier
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Form
 * Description: Toont formulier
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Form extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'form';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Formulier', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont formulier', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'text-page';
	}

	/** {@inheritDoc} */
	protected function supports_title(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function get_widget_fields(): array {
		$widget_fields = [
			'form'          => [
				'type'    => 'select',
				'label'   => __( 'Formulier', 'siw' ),
				'prompt'  => __( 'Selecteer een formulier', 'siw' ),
				'options' => \siw_get_forms(),
			],
			'single_column' => [
				'type'    => 'checkbox',
				'label'   => __( 'Enkele kolom', 'siw' ),
				'default' => false,
			],
		];
		return $widget_fields;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {
		return [
			'content' => Form_Element::create()->set_form_id( $instance['form'] )->set_single_column( $instance['single_column'] )->generate(),
		];
	}
}
