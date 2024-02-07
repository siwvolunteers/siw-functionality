<?php declare(strict_types=1);

namespace SIW\Structured_Data;

/**
 * @see https://schema.org/Event
 */
class Job_Posting extends Thing {

	#[\Override]
	protected function get_type(): string {
		return 'JobPosting';
	}

	public function set_title( string $title ): static {
		return $this->set_property( 'title', $title );
	}

	public function set_date_posted( \DateTime $date_posted ): static {
		return $this->set_property( 'datePosted', $date_posted );
	}

	public function set_valid_through( \DateTime $valid_through ): static {
		return $this->set_property( 'validThrough', $valid_through );
	}

	public function set_employment_type( Employment_Type $employment_type ): static {
		return $this->set_property( 'employmentType', $employment_type );
	}

	public function add_employment_type( Employment_Type $employment_type ): static {
		return $this->add_property( 'employmentType', $employment_type );
	}

	public function set_hiring_organization( Organization $hiring_organization ): static {
		return $this->set_property( 'hiringOrganization', $hiring_organization );
	}

	public function set_job_location( Place $job_location ): static {
		return $this->set_property( 'jobLocation', $job_location );
	}

	public function set_qualifications( string $qualifications ): static {
		return $this->set_property( 'qualifications', $qualifications );
	}

	public function set_responsibilities( string $responsibilities ): static {
		return $this->set_property( 'responsibilities', $responsibilities );
	}

	public function set_employer_overview( string $employer_overview ): static {
		return $this->set_property( 'employerOverview', $employer_overview );
	}

	public function set_job_benefits( string $job_benefits ): static {
		return $this->set_property( 'jobBenefits', $job_benefits );
	}
}
