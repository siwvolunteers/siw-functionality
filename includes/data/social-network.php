<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Data\Icons\Social_Icons;
use SIW\Interfaces\Enums\Colors;
use SIW\Interfaces\Enums\Labels;
use SIW\Traits\Enum_List;

enum Social_Network: string implements Labels, Colors {

	use Enum_List;

	case FACEBOOK = 'facebook';
	case TWITTER = 'twitter';
	case INSTAGRAM = 'instagram';
	case LINKEDIN = 'linkedin';
	case YOUTUBE = 'youtube';
	case WHATSAPP = 'whatsapp';

	public function label(): string {
		return match ( $this ) {
			self::FACEBOOK  => __( 'Facebook', 'siw' ),
			self::TWITTER   => __( 'Twitter', 'siw' ),
			self::INSTAGRAM => __( 'Instagram', 'siw' ),
			self::LINKEDIN  => __( 'LinkedIn', 'siw' ),
			self::YOUTUBE   => __( 'YouTube', 'siw' ),
			self::WHATSAPP  => __( 'WhatsApp', 'siw' ),
		};
	}

	public function color(): string {
		return match ( $this ) {
			self::FACEBOOK  => '#3b5998',
			self::TWITTER   => '#00aced',
			self::INSTAGRAM => '#dd2a7b',
			self::LINKEDIN  => '#007bb6',
			self::YOUTUBE   => '#ff3333',
			self::WHATSAPP  => '#25D366',
		};
	}

	public function profile_url(): ?string {
		return match ( $this ) {
			self::FACEBOOK  => 'https://www.facebook.com/SIWvolunteers/',
			self::TWITTER   => 'https://twitter.com/SIWvolunteers',
			self::INSTAGRAM => 'https://www.instagram.com/siwvrijwilligersprojecten/',
			self::LINKEDIN  => 'https://www.linkedin.com/company/siw',
			self::YOUTUBE   => 'https://www.youtube.com/user/SIWvolunteerprojects',
			self::WHATSAPP  => null,
		};
	}

	public function share_template(): ?string {
		return match ( $this ) {
			self::FACEBOOK  => 'https://www.facebook.com/sharer/sharer.php?u={{ url }}',
			self::TWITTER   => 'https://twitter.com/intent/tweet?text={{ title }}&amp;url={{ url }}&amp;via=siwvolunteers',
			self::INSTAGRAM => null,
			self::LINKEDIN  => 'https://www.linkedin.com/sharing/share-offsite/?url={{ url }}',
			self::YOUTUBE   => null,
			self::WHATSAPP  => 'https://api.whatsapp.com/send?text={{ url }}',
		};
	}

	public function icon_class(): Social_Icons {
		return match ( $this ) {
			self::FACEBOOK  => Social_Icons::FACEBOOK,
			self::TWITTER   => Social_Icons::TWITTER,
			self::INSTAGRAM => Social_Icons::INSTAGRAM,
			self::LINKEDIN  => Social_Icons::LINKEDIN,
			self::YOUTUBE   => Social_Icons::YOUTUBE,
			self::WHATSAPP  => Social_Icons::WHATSAPP,
		};
	}

	public static function filter( Social_Network_Context $context ) {
		return array_filter(
			Social_Network::cases(),
			fn ( Social_Network $network ): bool => $network->is_valid_for_context( $context )
		);
	}

	protected function is_valid_for_context( Social_Network_Context $context ): bool {
		return (
			( Social_Network_Context::SHARE === $context && null !== $this->share_template() )
			||
			( Social_Network_Context::FOLLOW === $context && null !== $this->profile_url() )
		);
	}
}
