<?php declare(strict_types=1);

namespace SIW\Content\Features;

use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Interfaces\Content\Type as I_Type;
use SIW\Interfaces\Content\Upload_Subdir as I_Upload_Subdir;

/**
 * Aparte upload dir voor content type
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Upload_Subdir extends Base {

	/** Init */
	private function __construct( protected I_Type $type, protected I_Upload_Subdir $upload_subdir ) {}

	#[Filter( 'siw/upload_dir/content_type' )]
	/** Voegt structured data toe */
	public function set_upload_subir( $subdirs ): array {

		$subdirs[ $this->type->get_post_type() ] = $this->upload_subdir->get_upload_subdir();
		return $subdirs;
	}
}
