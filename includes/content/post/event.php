<?php declare(strict_types=1);

namespace SIW\Content\Post;

class Event extends Post {

	private const MAX_AGE_EVENT = 6;

	public function get_thumbnail_id(): int {
		$images = $this->get_meta( 'image', [ 'limit' => 1 ] );
		$image = reset( $images );

		return $image ? (int) $image['ID'] : 0;
	}

	public function get_event_date(): \DateTime {
		return \DateTime::createFromFormat( 'Y-m-d', $this->get_meta( 'event_date' ) );
	}

	public function get_description(): string {
		return $this->get_meta( 'description' );
	}

	public function get_start_datetime(): \DateTime {
		return new \DateTime( $this->get_meta( 'event_date' ) . $this->get_meta( 'start_time' ) );
	}

	public function get_end_datetime(): \DateTime {
		return new \DateTime( $this->get_meta( 'event_date' ) . $this->get_meta( 'end_time' ) );
	}

	public function get_start_time(): string {
		return $this->get_meta( 'start_time' );
	}

	public function get_end_time(): string {
		return $this->get_meta( 'end_time' );
	}

	public function is_active(): bool {
		return $this->get_meta( 'event_date' ) > gmdate( 'Y-m-d' );
	}

	public function should_delete(): bool {
		return $this->get_meta( 'event_date' ) < gmdate( 'Y-m-d', time() - ( static::MAX_AGE_EVENT * MONTH_IN_SECONDS ) );
	}

	public function is_info_day(): bool {
		return (bool) $this->get_meta( 'info_day' );
	}

	public function is_online(): bool {
		return (bool) $this->get_meta( 'online' );
	}

	public function get_location(): array {
		return $this->is_online() ? [] : $this->get_meta( 'location' );
	}

	public function get_application(): array {
		return $this->is_info_day() ? [] : $this->get_meta( 'application' );
	}

	public function has_different_organizer(): bool {
		return ! $this->is_info_day() && $this->get_meta( 'different_organizer' );
	}

	public function get_organizer(): array {
		return ! $this->is_info_day() && $this->get_meta( 'different_organizer' ) ? $this->get_meta( 'organizer' ) : [];
	}

	public function get_mailjet_list_id(): ?string {
		return $this->get_meta( 'mailjet_list_id' );
	}

	public function set_mailjet_list_id( string $list_id ) {
		$this->set_meta( 'mailjet_list_id', $list_id );
	}
}
