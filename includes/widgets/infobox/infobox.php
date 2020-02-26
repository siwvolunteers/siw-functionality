<?php

namespace SIW\Widgets;

use SIW\Elements;

/**
 * Widget met infoboxes
 *
 * @copyright 2019-2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data
 * Widget Name: SIW: Infobox
 * Description: Toont infobox met tekst in icon
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Infobox extends Widget {

	/**
	 * {@inheritDoc}
	 */
	protected $widget_id ='infobox';

	/**
	 * {@inheritDoc}
	 */
	protected $widget_dashicon = 'align-right';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Infobox', 'siw');
		$this->widget_description = __( 'Toont infoboxes met icon', 'siw' );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'  => 'text',
				'label' => __( 'Titel', 'siw'),
			],
			'intro' => [
				'type'           => 'tinymce',
				'label'          => __( 'Intro', 'siw' ),
				'rows'           => 10,
				'default_editor' => 'html',
			],
			'infoboxes' => [
				'type'       => 'repeater',
				'label'      => __( 'Infoboxes' , 'siw' ),
				'item_name'  => __( 'Infobox', 'siw' ),
				'item_label' => [
					'selector'     => "[id*='title']",
					'update_event' => 'change',
					'value_method' => 'val'
				],
				'fields' => [
					'icon' => [
						'type'  => 'icon',
						'label' => __( 'Icoon', 'siw' ),
					],
					'title' => [
						'type'  => 'text',
						'label' => __( 'Titel', 'siw' )
					],
					'content' => [
						'type'           => 'tinymce',
						'label'          => __( 'Inhoud', 'siw' ),
						'rows'           => 10,
						'default_editor' => 'html',
					],
				],
			],

		];
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_content( array $instance, array $args, array $template_vars, string $css_name ) {
		ob_start();
		?>
		<?php
		if ( isset( $instance['intro'] ) ) {
			echo wp_kses_post( $instance['intro'] );
		}
		foreach ( $instance['infoboxes'] as $infobox ) : ?>
			<div class="row header">
				<div class="icon ">
					<?php echo Elements::generate_icon( $infobox['icon'], 3, 'circle');?>
				</div>
				<div class="title">
				<h4><?php echo esc_html( $infobox['title'] );?></h4>
				</div>
			</div>
			<div class="row">
				<div class="content col-md-12">
					<?php echo wpautop( wp_kses_post( $infobox['content'] ) );?>
				</div>
			</div>
		<?php endforeach;
		$content = ob_get_clean();
		return $content;
	}
}
