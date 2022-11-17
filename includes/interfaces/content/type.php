<?php declare(strict_types=1);

namespace SIW\Interfaces\Content;

/**
 * Interface content type
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Type {

	/** Geeft post type terug (inclusief prefix `siw_`)*/
	public function get_post_type(): string;

	/** Geeft slug terug */
	public function get_slug(): string;

	/** Geeft labels terug */
	public function get_labels(): array;

	/** Geeft array met metabox velden terug */
	public function get_meta_box_fields(): array;

	/** Geeft (dash-)icon terug */
	public function get_icon(): string;

	/** Geeft template variable voor single post terug */
	public function get_single_template_variables( int $post_id ): array;

	/** Geeft template variable voor archive post terug */
	public function get_archive_template_variables( int $post_id ): array;

}
