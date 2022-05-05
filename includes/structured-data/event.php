<?php declare(strict_types=1);

namespace SIW\Structured_Data;

/**
 * Evenement
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://schema.org/Event
 */
class Event extends Thing {

	/** {@inheritDoc} */
	protected function get_type() : string {
		return 'Event';
	}

	/** Zet organisator */
	public function set_organizer( Organization $organizer ) {
		return $this->set_property( 'organizer', $organizer );
	}

	/** Zet startdatum */
	public function set_start_date( \DateTime $start_date ) {
		return $this->set_property( 'startDate', $start_date );
	}

	/** Zet einddatum */
	public function set_end_date( \DateTime $end_date ) {
		return $this->set_property( 'endDate', $end_date );
	}

	/** Zet soort evenement */
	public function set_event_attendance_mode( Event_Attendance_Mode $event_attendance_mode ) {
		return $this->set_property( 'eventAttendanceMode', $event_attendance_mode );
	}

	/** Zet status van evenement */
	public function set_event_status( Event_Status_Type $event_status ) {
		return $this->set_property( 'eventStatus', $event_status );
	}

	/** Voeg status van evenement toe */
	public function add_event_status( Event_Status_Type $event_status ) {
		return $this->add_property( 'eventStatus', $event_status );
	}

	/** Zet locatie TODO: check, of union type  VirtualLocation|Place */
	public function set_location( Thing $location ) {
		return $this->set_property( 'location', $location );
	}
}
