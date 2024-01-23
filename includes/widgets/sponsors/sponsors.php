<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\List_Columns;
use SIW\Elements\List_Style_Type;

/**
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
		return Widget::DEFAULT_TEMPLATE_ID;
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

		$links = array_map(
			fn( array $sponsor ): string => sprintf(
				'<a href="%s" target="_blank" rel="noopener external noreferrer">%s</a>',
				esc_url( $sponsor['site'] ),
				wp_get_attachment_image( $sponsor['logo'][0], 'medium' ),
			),
			$sponsors
		);

		return [
			'content' => List_Columns::create()->set_list_style_type( List_Style_Type::NONE )->add_items( $links )->generate(),
		];
	}
}
