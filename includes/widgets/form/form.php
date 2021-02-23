<?php declare(strict_types=1);

namespace SIW\Widgets;

use Caldera_Forms_Forms;
use SIW\i18n;
use SIW\Util;

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
	protected string $widget_id ='form';

	/** {@inheritDoc} */
	protected string $widget_dashicon = 'text-page';

	/** {@inheritDoc} */
	protected bool $use_default_template = true;

	/** {@inheritDoc} */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Formulier', 'siw');
		$this->widget_description = __( 'Toont formulier', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'  => 'text',
				'label' => __( 'Titel', 'siw'),
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
