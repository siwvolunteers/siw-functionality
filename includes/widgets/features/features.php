<?php
/*
 * Widget Name: SIW: Features
 * Description: Toont features met toelichting en link
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget met features
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Widget_Features extends SIW_Widget {

	/**
	 * {@inheritDoc}
	 */
	protected $widget_id ='features';

	/**
	 * {@inheritDoc}
	 */
	protected $widget_dashicon = 'yes';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Features', 'siw');
		$this->widget_description = __( 'Toont features met toelichting en link', 'siw' );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {

		$icons = kad_icon_list();

		$icons = array_combine( $icons, $icons );
		ksort( $icons );
		$widget_form = [
			'title' => [
				'type'  => 'text',
				'label' => __( 'Titel', 'siw'),
			],
			'columns' => [
				'type'   => 'radio',
				'label'   => __( 'Aantal kolommen', 'siw' ),
				'options' => [
					1 => __( 'Eén', 'siw'),
					2 => __( 'Twee', 'siw' ),
					3 => __( 'Drie', 'siw' ),
					4 => __( 'Vier', 'siw' ),
				],
			],
			'features' => [
				'type'       => 'repeater',
				'label'      => __( 'Features' , 'siw' ),
				'item_name'  => __( 'Feature', 'siw' ),
				'item_label' => [
					'selector'     => "[id*='title']",
					'update_event' => 'change',
					'value_method' => 'val'
				],
				'fields' => [
					'icon' => [
						'type'  => 'select',
						'label' => __( 'Icoon', 'siw' ),
						'options' => $icons,
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
					'add_link' => [
						'type'          => 'checkbox',
						'label'         => __( 'Voeg link toe', 'siw' ),
						'default'       => false,
						'state_emitter' => [
							'callback'    => 'conditional',
							'args'        => [
								'link_{$repeater}[show]: val',
								'link_{$repeater}[hide]: ! val'
							],
						],
					],
					'link_url' => [
						'type'          => 'text',
						'label'         => __( 'URL', 'siw' ),
						'state_handler' => [
							'link_{$repeater}[show]' => [ 'show' ],
							'link_{$repeater}[hide]' => [ 'hide' ],
						],
					],
				],
			],

		];
		return $widget_form;
	}


	/**
	 * {@inheritDoc}
	 */
	public function get_content( $instance, $args, $template_vars, $css_name ) {

		$columns = $instance['columns'];
		$rows = array_chunk( $instance['features'], $columns );

		switch ( $columns ) {
			case 4:
				$class = 'col-md-3';
				break;
			case 3:
				$class = 'col-md-4';
				break;
			case 2:
				$class = 'col-md-6';
				break;
			case 1:
			default:
				$class = 'col-md-12';
		}

		ob_start();
		?>
		<div class="container">
		<?php foreach ( $rows as $row ) : ?>
			<div class = "row">
				<?php foreach ( $row as $feature ) : ?>
				<div class="<?= esc_attr( $class ); ?>">
					<?php 
						$output = sprintf(
							'[iconbox icon="%s" link="%s" btn="%s" btn_txt="Lees meer" color="#fff" "hbackground="%s" background="%s" tcolor="%s"]',
							esc_attr( $feature['icon'] ),
							$feature['add_link'] ? esc_url( $feature['link_url'] ) : '',
							$feature['add_link'] ? 'true' : 'false',
							esc_attr( SIW_Properties::PRIMARY_COLOR ),
							esc_attr( SIW_Properties::FONT_COLOR ),
							esc_attr( SIW_Properties::FONT_COLOR )
						);
						$output .= '<h4>' . esc_html( $feature['title'] ) . '</h4>';
						$output .= wp_kses_post( $feature['content'] ) . '<br/>';
						$output .= '[/iconbox]';
						echo do_shortcode( $output );
					?>
				</div>
				<?php endforeach ?>
			</div>
			<?php endforeach ?>
		</div>

		<?php
		$content = ob_get_clean();
		return $content;
	}
}
