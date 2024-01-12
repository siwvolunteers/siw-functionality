<?php declare(strict_types=1);

namespace SIW\Structured_Data;

/**
 * @see https://schema.org/Event
 */
class Event extends Thing {

	/** {@inheritDoc} */
	protected function get_type(): string {
		return 'Event';
	}

	public function set_organizer( Organization $organizer ): static {
		return $this->set_property( 'organizer', $organizer );
	}

	public function set_start_date( \DateTime $start_date ): static {
		return $this->set_property( 'startDate', $start_date );
	}

	public function set_end_date( \DateTime $end_date ): static {
		return $this->set_property( 'endDate', $end_date );
	}

	public function set_event_attendance_mode( Event_Attendance_Mode $event_attendance_mode ): static {
		return $this->set_property( 'eventAttendanceMode', $event_attendance_mode );
	}

	public function set_event_status( Event_Status_Type $event_status ): static {
		return $this->set_property( 'eventStatus', $event_status );
	}

	public function add_event_status( Event_Status_Type $event_status ): static {
		return $this->add_property( 'eventStatus', $event_status );
	}

	public function set_location( Place|Virtual_Location $location ): static {
		return $this->set_property( 'location', $location );
	}
}
