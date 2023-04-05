<?php declare(strict_types=1);

namespace SIW\Widgets;

/**
 * Widget met sponsors
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Social links
 * Description: Toont links naar sociale netwerken
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Sponsors extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'sponsors';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Sponsors', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont links naar sponsors', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return 'sponsors';
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'money';
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
	public function get_template_variables( $instance, $args ) {
		$sponsors = siw_get_option( 'sponsors' );
		if ( empty( $sponsors ) ) {
			return [];
		}

		$sponsors = array_map(
			fn( array $sponsor ) : array => [
				'url'  => $sponsor['site'],
				'name' => $sponsor['name'],
				'logo' => wp_get_attachment_image( $sponsor['logo'][0], 'medium' ),
			],
			$sponsors
		);

		return [
			'sponsors' => $sponsors,
		];
	}
}
