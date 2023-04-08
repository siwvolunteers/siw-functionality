<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Project_Type;
use SIW\Interfaces\Forms\Form;
use SIW\Util\Links;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Widget met keuzehulp
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Keuzehulp
 * Description: Toont keuzehulp
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Project_Wizard extends Widget {

	protected \RW_Meta_Box $meta_box;

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'project_wizard';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Keuzehulp', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont keuzehulp', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'lightbulb';
	}

	/** {@inheritDoc} */
	protected function supports_title(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return true;
	}

	protected function create_wizard() {
		$meta_box = [
			'title'       => __( 'Keuzehulp', 'siw' ),
			'post_types'  => [],
			'class'       => 'project-wizard',
			'toggle_type' => 'slide',
			'fields'      => [
				[
					'id'      => 'age',
					'name'    => 'Hoe oud ben je?',
					'columns' => Form::THIRD_WIDTH,
					'type'    => 'radio',
					'inline'  => false,
					'options' => [
						'18minus' => 'Onder de 18 jaar',
						'18to30'  => '18 jaar t/m 30 jaar',
						'30plus'  => '30 jaar of ouder',
					],
				],
				[
					'id'      => 'duration',
					'name'    => 'Hoe lang wil je weg?',
					'columns' => Form::THIRD_WIDTH,
					'type'    => 'radio',
					'inline'  => false,
					'options' => [
						'stv' => __( 'Korter dan 3 weken', 'siw' ),
						'mtv' => __( 'Van 3 weken tot 2 maanden', 'siw' ),
						'ltv' => __( 'Langer dan 2 maanden', 'siw' ),
					],
					'visible' => [
						'age',
						'in',
						[ '18to30', '30plus' ],
					],
				],
				[
					'id'      => 'destination',
					'name'    => 'Waar wil je heen?',
					'columns' => Form::THIRD_WIDTH,
					'type'    => 'radio',
					'inline'  => false,
					'options' => [
						'europe'        => __( 'Europa (inclusief Aruba en Curaçao)', 'siw' ),
						'asia'          => __( 'Azië', 'siw' ),
						'africa'        => __( 'Afrika', 'siw' ),
						'north_america' => __( 'Noord-Amerika', 'siw' ),
						'latin_america' => __( 'Latijns-Amerika', 'siw' ),
					],
					'visible' => [
						[ 'age', 'in', [ '18to30', '30plus' ] ],
						[ 'duration', 'in', [ 'mtv', 'ltv' ] ],
					],
				],
				[
					'type'    => 'custom_html',
					'std'     => sprintf(
						'%s %s',
						'Begin je zoektocht eens bij de <em>groepsprojecten binnen Europa</em>.',
						Links::generate_link(
							get_term_link( 'europa', Taxonomy_Attribute::CONTINENT->value ),
							__( 'Lees meer', 'siw' ),
							[ 'class' => 'page-link' ]
						)
					),
					'columns' => Form::FULL_WIDTH,
					'class'   => 'wizard-result',
					'visible' => [
						'when' => [
							[ 'age', '=', '18minus' ],
						],
					],
				],
				[
					'type'    => 'custom_html',
					'std'     => sprintf(
						'%s %s',
						'Begin je zoektocht eens bij de <em>groepsprojecten</em>.',
						Links::generate_link(
							wc_get_page_permalink( 'shop' ),
							__( 'Lees meer', 'siw' ),
							[ 'class' => 'page-link' ]
						)
					),
					'columns' => Form::FULL_WIDTH,
					'class'   => 'wizard-result',
					'visible' => [
						'when' => [
							[ 'age', '!=', '18minus' ],
							[ 'duration', '=', 'stv' ],
						],
					],
				],
				[
					'type'    => 'custom_html',
					'std'     => sprintf(
						'%s %s',
						'Voor jou zijn <em>Wereld-basis-projecten</em> een mooi startpunt</em>.',
						Links::generate_link(
							get_permalink( siw_get_project_type_page( Project_Type::WORLD_BASIC ) ),
							__( 'Lees meer', 'siw' ),
							[ 'class' => 'page-link' ]
						)
					),
					'columns' => Form::FULL_WIDTH,
					'class'   => 'wizard-result',
					'visible' => [
						[ 'age', 'in', [ '18to30', '30plus' ] ],
						[ 'duration', 'in', [ 'mtv', 'ltv' ] ],
						[ 'destination', 'in', [ 'asia', 'africa', 'north_america', 'latin_america' ] ],
					],
				],
				[
					'type'    => 'custom_html',
					'std'     => sprintf(
						'%s %s',
						'Begin je zoektocht eens bij de <em>groepsprojecten</em>.',
						Links::generate_link(
							wc_get_page_permalink( 'shop' ),
							__( 'Lees meer', 'siw' ),
							[ 'class' => 'page-link' ]
						)
					),
					'columns' => Form::FULL_WIDTH,
					'class'   => 'wizard-result',
					'visible' => [
						[ 'age', '=', '30plus' ],
						[ 'duration', '=', 'mtv' ],
						[ 'destination', '=', 'europe' ],
					],
				],
				[
					'type'    => 'custom_html',
					'std'     => 'Hiervoor bieden we momenteel geen projecten aan.',
					'columns' => Form::FULL_WIDTH,
					'class'   => 'wizard-result',
					'visible' => [
						[ 'age', '=', '30plus' ],
						[ 'duration', '=', 'ltv' ],
						[ 'destination', '=', 'europe' ],
					],
				],

				[
					'type'    => 'custom_html',
					'std'     => sprintf(
						'%s %s',
						'Jij komt in aanmerking voor het <em>European Solidarity Fund</em> en kan bijna kosteloos een vrijwilligersproject gaan doen.',
						Links::generate_link(
							get_permalink( siw_get_project_type_page( Project_Type::ESC ) ),
							__( 'Lees meer', 'siw' ),
							[ 'class' => 'page-link' ]
						)
					),
					'columns' => Form::FULL_WIDTH,
					'class'   => 'wizard-result',
					'visible' => [
						[ 'age', '=', '18to30' ],
						[ 'duration', 'in', [ 'mtv', 'ltv' ] ],
						[ 'destination', '=', 'europe' ],
					],
				],
			],
		];
		$this->meta_box = new \RW_Meta_Box( $meta_box );
	}

	/** {@inheritDoc} */
	public function initialize() {
		$this->register_frontend_styles(
			[
				[
					'siw-widget-project-wizard',
					SIW_ASSETS_URL . 'css/widgets/project-wizard.css',
					[],
					SIW_PLUGIN_VERSION,
				],
			]
		);
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {
		$this->create_wizard();
		ob_start();
		$this->meta_box->show();
		$content = ob_get_clean();

		$this->meta_box->enqueue();

		return [
			'content' => $content,
		];
	}
}
