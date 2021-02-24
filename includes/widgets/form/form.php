<?php declare(strict_types=1);

namespace SIW\Widgets;

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
		return 'default';
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'text-page';
	}

	/** {@inheritDoc} */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Formulier', 'siw' );
		$this->widget_description = __( 'Toont formulier', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'  => 'text',
				'label' => __( 'Titel', 'siw' ),
			],
			'intro' => [
				'type'           => 'tinymce',
				'label'          => __( 'Intro', 'siw' ),
				'rows'           => 5,
				'default_editor' => 'html',
			],
			'form' => [
				'type'    => 'select',
				'label'   => __( 'Formulier', 'siw' ),
				'prompt'  => __( 'Selecteer een formulier', 'siw' ),
				'options' => \siw_get_forms(),
			],
		];
		return $widget_form;
	}

	/** {@inheritDoc} */
	function get_template_variables( $instance, $args ) {
		return [
			'intro'   => $instance['intro'],
			'content' => sprintf( '[caldera_form id="%s"]', $instance['form'] )
		];
	}
}
