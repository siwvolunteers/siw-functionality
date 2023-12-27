<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een calendar icoon te genereren
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Calendar_Icon extends Element {

	protected \DateTime $date_time;

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'day'   => [
				'name'   => wp_date( 'l', $this->date_time->getTimestamp() ),
				'number' => wp_date( 'd', $this->date_time->getTimestamp() ),
			],
			'month' => [
				'name'   => wp_date( 'F', $this->date_time->getTimestamp() ),
				'number' => wp_date( 'm', $this->date_time->getTimestamp() ),
			],
		];
	}

	public function set_datetime( \DateTime $date_time ): self {
		$this->date_time = $date_time;
		return $this;
	}

	public function enqueue_styles() {
		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/elements/calendar-icon.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::get_assets_handle(), 'path', SIW_ASSETS_DIR . 'css/elements/calendar-icon.css' );
		wp_enqueue_style( self::get_assets_handle() );
	}
}
