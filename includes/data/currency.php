<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Labels;
use SIW\Traits\Enum_List;

enum Currency: string implements Labels {

	use Enum_List;

	case CAD = 'CAD';
	case CHF = 'CHF';
	case DKK = 'DKK';
	case EUR = 'EUR';
	case GBP = 'GBP';
	case IDR = 'IDR';
	case INR = 'INR';
	case JPY = 'JPY';
	case KES = 'KES';
	case MXN = 'MXN';
	case MYR = 'MYR';
	case RUB = 'RUB';
	case THB = 'THB';
	case USD = 'USD';
	case VND = 'VND';

	/** {@inheritDoc} */
	public function label(): string {
		return match ( $this ) {
			// phpcs:disable WordPress.WP.I18n.TextDomainMismatch
			self::CAD => __( 'Canadian dollar', 'woocommerce' ),
			self::CHF => __( 'Swiss franc', 'woocommerce' ),
			self::DKK => __( 'Danish krone', 'woocommerce' ),
			self::EUR => __( 'Euro', 'woocommerce' ),
			self::GBP => __( 'Pound sterling', 'woocommerce' ),
			self::IDR => __( 'Indonesian rupiah', 'woocommerce' ),
			self::INR => __( 'Indian rupee', 'woocommerce' ),
			self::JPY => __( 'Japanese yen', 'woocommerce' ),
			self::KES => __( 'Kenyan shilling', 'woocommerce' ),
			self::MXN => __( 'Mexican peso', 'woocommerce' ),
			self::MYR => __( 'Malaysian ringgit', 'woocommerce' ),
			self::RUB => __( 'Russian ruble', 'woocommerce' ),
			self::THB => __( 'Thai baht', 'woocommerce' ),
			self::USD => __( 'United States (US) dollar', 'woocommerce' ),
			self::VND => __( 'Vietnamese &#x111;&#x1ed3;ng', 'woocommerce' ),
			// phpcs:enable WordPress.WP.I18n.TextDomainMismatch
		};
	}

	public function symbol(): string {
		return match ( $this ) {
			self::CAD => 'C$',
			self::CHF => 'CHF',
			self::DKK => 'kr.',
			self::EUR => '&euro;',
			self::GBP => '&pound;',
			self::IDR => 'Rp',
			self::INR => '&#x20B9;',
			self::JPY => '&yen;',
			self::KES => 'Ksh',
			self::MXN => '$',
			self::MYR => 'RM',
			self::RUB => '&#8381;',
			self::THB => '&#x0E3F;',
			self::USD => '$',
			self::VND => '&#x20ab;',
		};
	}
}
