<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Properties;

/**
 * Widget Name: SIW: Organisatiegegevens
 * Description: Toont organisatiegegevens.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Organisation extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'organisation';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Organisatiegegevens', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont organisatiegegevens', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return $this->get_id();
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'building';
	}

	/** {@inheritDoc} */
	protected function supports_title(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function get_widget_fields(): array {
		$widget_form = [
			'renumeration_policy' => [
				'type'           => 'tinymce',
				'label'          => __( 'Beloningsbeleid', 'siw' ),
				'rows'           => 10,
				'default_editor' => 'html',
			],
		];
		return $widget_form;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {
		$parameters = [
			'properties'          => [
				[
					'name'   => __( 'Statutaire naam', 'siw' ),
					'values' => Properties::STATUTORY_NAME,
				],
				[
					'name'   => __( 'RSIN/fiscaal nummer', 'siw' ),
					'values' => Properties::RSIN,
				],
				[
					'name'   => __( 'KVK-nummer', 'siw' ),
					'values' => Properties::KVK,
				],
				[
					'name'   => __( 'Rekeningnummer', 'siw' ),
					'values' => Properties::IBAN,
				],
			],
			'renumeration_policy' => $instance['renumeration_policy'],
			'i18n'                => [
				'renumeration_policy' => __( 'Beloningsbeleid', 'siw' ),
			],
		];

		return $parameters;
	}
}
