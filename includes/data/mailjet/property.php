<?php declare(strict_types=1);

namespace SIW\Data\Mailjet;

enum Property: string {
	case FIRST_NAME = 'firstname';
	case LAST_NAME = 'lastname';
	case INTEREST_PROJECT_TYPE = 'interest_project_type';
	case INTEREST_DESTINATION = 'interest_destination';
	case REFERRAL = 'referral';
	case AGE_RANGE = 'age_range';

	public function get_data_type(): Data_Type {
		return match ( $this ) {
			self::FIRST_NAME            => Data_Type::STRING,
			self::LAST_NAME             => Data_Type::STRING,
			self::INTEREST_PROJECT_TYPE => Data_Type::STRING,
			self::INTEREST_DESTINATION  => Data_Type::STRING,
			self::REFERRAL              => Data_Type::STRING,
			self::AGE_RANGE             => Data_Type::STRING,
		};
	}

	public function get_namespace(): Property_Namespace {
		return match ( $this ) {
			default => Property_Namespace::STATIC
		};
	}
}
