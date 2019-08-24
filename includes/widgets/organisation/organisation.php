<?php

/**
 * Widget met organisatiegegevens
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Properties
 * 
 * @widget_data
 * Widget Name: SIW: Organisatiegegevens
 * Description: Toont organisatiegegevens.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
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
	protected function get_content( array $instance, array $args, array $template_vars, string $css_name ) { 
		ob_start();
		?>
		<p><b><?php esc_html_e( 'Statutaire naam', 'siw' ); ?></b><br><?= SIW_Properties::STATUTORY_NAME ?><br></p>
		<p><b><?php esc_html_e( 'RSIN/fiscaal nummer', 'siw' ); ?></b><br><?= SIW_Properties::RSIN ?><br></p>
		<p><b><?php esc_html_e( 'KVK-nummer', 'siw' ); ?></b><br><?= SIW_Properties::KVK ?><br></p>
		<p><b><?php esc_html_e( 'Rekeningnummer', 'siw' ); ?></b><br><?= SIW_Properties::IBAN ?><br></p>
		<p>
			<b><?php esc_html_e( 'Bestuurssamenstelling', 'siw' ); ?></b><br>
			<?php esc_html_e( 'Het bestuur van SIW bestaat momenteel uit:', 'siw' ); ?>
			<?php echo $this->get_board_members_list(); ?>
		</p>
		<p>
			<b><?php esc_html_e( 'Beloningsbeleid', 'siw' ); ?></b><br>
			<?php echo wp_kses_post( $instance['renumeration_policy'] ); ?><br></p>
		</p>
		<p>
			<b><?php esc_html_e( 'Jaarverslagen', 'siw' ); ?></b><br>
			<?php echo $this->get_annual_reports(); ?>
		</p>
		<?php
		$content = ob_get_clean();
		return $content;
	}

	/**
	 * Geeft lijst met bestuursleden terug
	 * 
	 * @return string
	 */
	protected function get_board_members_list() {
		$board_members = siw_get_option( 'board_members');
		if ( empty( $board_members ) ) {
			return;
		}
	
		$board_members_list = [];
		foreach ( $board_members as $board_member ) {
			$board_members_list[] = sprintf('%s %s<br/><i>%s</i>', $board_member['first_name'], $board_member['last_name'], $board_member['title']);
		}
		return SIW_Formatting::generate_list( $board_members_list );
	}

	/**
	 * Geeft jaarverslagen terug
	 * 
	 * @return string
	 */
	protected function get_annual_reports() {
		$annual_reports = siw_get_option( 'annual_reports' );
		if ( empty( $annual_reports ) ) {
			return;
		}
		$reports = [];
		foreach ( $annual_reports as $report ) {
			$url = wp_get_attachment_url( $report['file'][0] );
			$text = sprintf( esc_html__( 'Jaarverslag %s', 'siw' ), $report['year'] );
			$reports[ $report['year'] ] = SIW_Formatting::generate_link(
				$url,
				$text,
				[
					'target'           => '_blank',
					'rel'              => 'noopener',
					'data-ga-track'    => 1,
					'data-ga-type'     => 'event',
					'data-ga-category' => 'Document',
					'data-ga-action'   => 'Downloaden',
					'data-ga-label'    => $url,
				]
				);
		}
		krsort( $reports );
		return SIW_Formatting::array_to_text( $reports, BR );
	}
}
