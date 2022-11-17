<?php declare(strict_types=1);

namespace SIW\Content\Features;

use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Interfaces\Content\Options as I_Options;
use SIW\Interfaces\Content\Type as I_Type;

/**
 * Voegt extra opties voor content type toe
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Options extends Base {

	/** Extra opties */
	protected I_Options $options;

	/** Init */
	public function __construct( protected I_Type $type ) {}

	/** Voegt opties toe */
	public function set_extra_options( I_Options $options ) {
		$this->options = $options;
	}

	#[Filter( 'siw_option_settings_tabs' )]
	/** Voegt tab voor content type toe */
	public function add_tabs( array $tabs ): array {
		$tabs[] = [
			'id'    => $this->type->get_post_type(),
			'label' => $this->type->get_labels()['singular_name'],
			'icon'  => $this->type->get_icon(),
		];

		return $tabs;
	}

	#[Filter( 'siw_option_settings_fields' )]
	/** Voegt velden toe aan opties voor post type */
	public function add_fields( array $fields ): array {

		$type_fields = [
			[
				'id'       => 'archive_intro',
				'name'     => __( 'Introtekst archief', 'siw' ),
				'type'     => 'wysiwyg',
				'required' => true,
			],
		];

		// Eventueel extra opties tovoegen
		if ( isset( $this->options ) ) {
			$type_fields = array_merge( $type_fields, $this->options->get_options() );
		}

		$fields[] = [
			'id'     => $this->type->get_post_type(),
			'type'   => 'group',
			'tab'    => $this->type->get_post_type(),
			'fields' => $type_fields,
		];

		return $fields;
	}

}
