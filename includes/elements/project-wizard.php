<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Data\Project_Type;
use SIW\Facades\WooCommerce;
use SIW\Forms\Form;
use SIW\WooCommerce\Taxonomy_Attribute;

class Project_Wizard extends Element {

	protected \RW_Meta_Box $meta_box;

	#[\Override]
	protected function get_template_variables(): array {
		ob_start();
		$this->meta_box->show();
		$content = ob_get_clean();
		return [
			'meta_box' => $content,
		];
	}

	#[\Override]
	protected function initialize() {
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
						$this->generate_link( get_term_link( 'europa', Taxonomy_Attribute::CONTINENT->value ) )
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
						$this->generate_link( WooCommerce::get_page_permalink( 'shop' ) )
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
						'Voor jou zijn <em>Wereld-basis-projecten</em> een mooi startpunt.',
						$this->generate_link( get_permalink( Project_Type::WORLD_BASIC->get_page() ) )
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
						$this->generate_link( WooCommerce::get_page_permalink( 'shop' ) )
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
						$this->generate_link( get_permalink( Project_Type::ESC->get_page() ) )
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

	protected function generate_link( string $url ): string {
		return Link::create()
			->set_url( $url )
			->set_text( __( 'Lees meer', 'siw' ) )
			->add_class( 'page-link' )
			->generate();
	}


	#[\Override]
	public function enqueue_styles() {
		$this->meta_box->enqueue();
		self::enqueue_class_style();
	}
}
