<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget met agenda
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Formatting
 * 
 * @widget_data 
 * Widget Name: SIW: Agenda
 * Description: Toont eerstvolgende evenementen
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class SIW_Calendar_Widget extends SIW_Widget {

	/**
	 * {@inheritDoc}
	 */
	protected $widget_id ='calendar';

	/**
	 * {@inheritDoc}
	 */
	protected $widget_dashicon = 'calendar';

	/**
	 * {@inheritDoc}
	 */
	function __construct() {
		$this->widget_name = __( 'Agenda', 'siw');
		$this->widget_description = __( 'Toont eerstvolgende evenementen', 'siw' );
		$this->widget_fields = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw'),
				'default' => __( 'Agenda', 'siw' ),
			],
		];
		parent::__construct();
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_content( $instance, $args, $template_vars, $css_name ) {

		$events = $this->get_upcoming_events();

		if ( empty( $events ) ) {
			$content = '<p>' . esc_html__( 'Er zijn momenteel geen geplande activiteiten.', 'siw' ) . '</p>';
			return $content;
		}
		foreach ( $events as $event ) {
			ob_start();
			?>
			<h5 class="title">
				<?= SIW_Formatting::generate_link( $event['permalink'], $event['title'], [ 'class' => 'link' ] ) ?>
			</h5>
			<span class="duration" >
				<?= esc_html( $event['date_range'] );?> <br/>
				<?= esc_html( $event['start_time'] . '&nbsp;-&nbsp;' . $event['end_time'] );?><br/>
			</span>
			<span class="location">
				<?= esc_html( $event['location'] . ',&nbsp;' . $event['city'] );?>
			</span>
			<?= $event['json_ld'];?>
			<?php
			$event_list[] = ob_get_clean();
		}
		$content = SIW_Formatting::generate_list( $event_list );
		$content .= '<p class="page-link">' . SIW_Formatting::generate_link( get_page_link( siw_get_setting( 'agenda_parent_page' ) ), __( 'Bekijk de volledige agenda.', 'siw' ) ) . '</p>';

		return $content;
	}

	/**
	 * Undocumented function
	 * 
	 * @return array
	 */
	protected function get_upcoming_events() {
		$events = false; // get_transient( 'siw_upcoming_events' );
		if ( false === $events ) {
			$events = siw_get_upcoming_events( 2 );
			set_transient( 'siw_upcoming_events', $events, HOUR_IN_SECONDS );
		}
		return $events;
	}
}