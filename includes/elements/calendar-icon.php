<?php declare(strict_types=1);

namespace SIW\Elements;

class Calendar_Icon extends Element {

	protected \DateTime $date_time;

	#[\Override]
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

	#[\Override]
	public function enqueue_styles() {
		self::enqueue_class_style();
	}
}
