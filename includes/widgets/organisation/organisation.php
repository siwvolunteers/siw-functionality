<?php
/*
 * 
 * Widget Name: SIW: Organisatiegegevens
 * Description: Toont organisatiegegevens.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget met organisatiegegevens
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Properties
 */
class SIW_Widget_Organisation extends SIW_Widget {

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_id = 'organisation';

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_dashicon = 'building';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Organisatiegegevens', 'siw');
		$this->widget_description = __( 'Toont organisatiegegevens', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'      => 'text',
				'label'     => __( 'Titel', 'siw'),
				'default'   => __( 'Gegevens', 'siw' ),
			],
			'renumeration_policy' => [
				'type'           => 'tinymce',
				'label'          => __( 'Beloningsbeleid', 'siw' ),
				'rows'           => 10,
				'default_editor' => 'html',
			],
		];
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_content( $instance, $args, $template_vars, $css_name ) { 
		ob_start();
		?>
		<p><b><?php esc_html_e( 'Statutaire naam', 'siw' ); ?></b><br><?= SIW_Properties::STATUTORY_NAME ?><br></p>
		<p><b><?php esc_html_e( 'RSIN/fiscaal nummer', 'siw' ); ?></b><br><?= SIW_Properties::RSIN ?><br></p>
		<p><b><?php esc_html_e( 'KVK-nummer', 'siw' ); ?></b><br><?= SIW_Properties::KVK ?><br></p>
		<p><b><?php esc_html_e( 'Rekeningnummer', 'siw' ); ?></b><br><?= SIW_Properties::IBAN ?><br></p>
		<p>
			<b><?php esc_html_e( 'Bestuurssamenstelling', 'siw' ); ?></b><br>
			<?php esc_html_e( 'Het bestuur van SIW bestaat momenteel uit:', 'siw' ); ?>
			<?php echo do_shortcode( '[siw_bestuursleden]'); ?>
		</p>
		<p>
			<b><?php esc_html_e( 'Beloningsbeleid', 'siw' ); ?></b><br>
			<?php echo wp_kses_post( $instance['renumeration_policy'] ); ?><br></p>
		</p>
		<p>
			<b><?php esc_html_e( 'Jaarverslagen', 'siw' ); ?></b><br>
			<?php echo do_shortcode( '[siw_jaarverslagen]'); ?>
		</p>
		<?php
		$content = ob_get_clean();
		return $content;
	}
}