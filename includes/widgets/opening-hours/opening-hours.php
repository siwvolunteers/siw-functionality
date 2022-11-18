<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\List_Columns;
use SIW\Elements\Table;

/**
 * Widget met openingstijden
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Openingstijden
 * Description: Toont openingstijden
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Opening_Hours extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'opening_hours';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Openingstijden', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont openingstijden', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'clock';
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
		$widget_fields = [
			'display_mode' => [
				'type'    => 'radio',
				'label'   => __( 'Weergave', 'siw' ),
				'options' => [
					'list'  => __( 'Lijst', 'siw' ),
					'table' => __( 'Tabel', 'siw' ),
				],
			],
		];
		return $widget_fields;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {

		$opening_hours = siw_get_opening_hours();
		switch ( $instance['display_mode'] ) {
			case 'list':
				$opening_hours = array_map(
					fn( array $value ): string => implode( ': ', $value ),
					$opening_hours
				);
				$content = List_Columns::create()->add_items( $opening_hours )->generate();
				break;
			case 'table':
			default:
				$content = Table::create()->add_items( $opening_hours )->generate();
				break;
		}

		return [
			'intro'   => $instance['intro'],
			'content' => $content,
		];
	}
}
