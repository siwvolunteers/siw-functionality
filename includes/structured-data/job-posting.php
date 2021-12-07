<?php declare(strict_types=1);

namespace SIW\Structured_Data;

/**
 * Vacature
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://schema.org/JobPosting
 */
class Job_Posting extends Thing {

	/** {@inheritDoc} */
	protected function get_type(): string {
		return 'JobPosting';
	}

	/** Zet functietitel */
	public function set_title( string $title ): static {
		return $this->set_property( 'title', $title );
	}

	/** Zet publicatiedatum */
	public function set_date_posted( \DateTime $date_posted ): static {
		return $this->set_property( 'datePosted', $date_posted );
	}
	
	/** Zet deadline */
	public function set_valid_through( \DateTime $valid_through ): static {
		return $this->set_property( 'validThrough', $valid_through );
	}
	
	/** Zet type baan */
	public function set_employment_type( Employment_Type $employment_type ): static {
		return $this->set_property( 'employmentType', $employment_type );
	}

	/** Voeg type baan toe */
	public function add_employment_type( Employment_Type $employment_type ): static {
		return $this->add_property( 'employmentType', $employment_type );
	}

	/**	Zet werkgever */
	public function set_hiring_organization( Organization $hiring_organization ): static {
		return $this->set_property( 'hiringOrganization', $hiring_organization );
	}

	/** Zet locatie */
	public function set_job_location( Place $job_location ): static {
		return $this->set_property( 'jobLocation', $job_location );
	}

	/** Zet functie-eisen */
	public function set_qualifications( string $qualifications ): static {
		return $this->set_property( 'qualifications', $qualifications );
	}

	/** Zet verantwoordelijkheden */
	public function set_responsibilities( string $responsibilities ): static {
		return $this->set_property( 'responsibilities', $responsibilities );
	}

	/** Zet beschrijving werkgever */
	public function set_employer_overview( string $employer_overview ): static {
		return $this->set_property( 'employerOverview', $employer_overview );
	}

	public function set_job_benefits( string $job_benefits ): static {
		return $this->set_property( 'jobBenefits', $job_benefits );
	}
}
