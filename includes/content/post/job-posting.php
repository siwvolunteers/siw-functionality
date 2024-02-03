<?php declare(strict_types=1);

namespace SIW\Content\Post;

use SIW\Data\Job_Type;

class Job_Posting extends Post {

	private const MAX_AGE_JOB_POSTING = 12;

	#[\Override]
	public function get_thumbnail_id(): int {
		$images = $this->get_meta( 'image', [ 'limit' => 1 ] );
		$image = reset( $images );

		return $image ? (int) $image['ID'] : 0;
	}

	#[\Override]
	public function is_active(): bool {
		return $this->get_meta( 'deadline' ) > gmdate( 'Y-m-d' );
	}

	#[\Override]
	public function should_delete(): bool {
		return $this->get_meta( 'deadline' ) < gmdate( 'Y-m-d', time() - ( static::MAX_AGE_JOB_POSTING * MONTH_IN_SECONDS ) );
	}

	public function get_deadline(): \DateTime {
		return \DateTime::createFromFormat( 'Y-m-d', $this->get_meta( 'deadline' ), wp_timezone() );
	}

	public function get_introduction(): string {
		return $this->get_meta( 'introduction' );
	}

	public function get_job_type(): Job_Type {
		return Job_Type::tryFrom( $this->get_meta( 'job_type' ) );
	}

	public function get_hours(): string {
		return $this->get_meta( 'hours' );
	}

	public function get_application_manager(): array {

		if ( $this->get_meta( 'different_application_manager' ) ) {
			return $this->get_meta( 'application_manager' );
		}
		return siw_get_option( 'job_posting.hr_manager' );
	}

	public function get_work(): string {
		return $this->get_meta( 'description.work' );
	}

	public function get_qualifications(): string {
		return $this->get_meta( 'description.qualifications' );
	}

	public function get_perks(): string {
		return $this->get_meta( 'description.perks' );
	}
}
