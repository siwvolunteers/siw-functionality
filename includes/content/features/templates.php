<?php declare(strict_types=1);

namespace SIW\Content\Features;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Helpers\Template;
use SIW\Interfaces\Content\Taxonomies as I_Taxonomies;
use SIW\Interfaces\Content\Type as I_Type;


/**
 * TODO:
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Templates extends Base {


	/** Taxonomieën */
	protected I_Taxonomies $taxonomies;

	/** {@inheritDoc} */
	protected function __construct( protected I_Type $type ) {}

	/** Voegt taxonomies toe */
	public function add_taxonomies( I_Taxonomies $taxonomies ) {
		$this->taxonomies = $taxonomies;
		return $this;
	}

	#[Filter( 'generate_do_template_part' )]
	/** Geen template part uit bestand laden voor content types */
	public function disable_do_template_part( bool $do_template_part ): bool {
		if ( is_post_type_archive( $this->type->get_post_type() ) ) {
			return false;
		}

		if ( isset( $this->taxonomies ) ) {
			foreach ( array_keys( $this->taxonomies->get_taxonomies() ) as $taxonomy ) {
				if ( is_tax( "{$this->type->get_post_type()}_{$taxonomy}" ) ) {
					return false;
				}
			}
		}

		if ( is_singular( $this->type->get_post_type() ) ) {
			return false;
		}

		return $do_template_part;
	}

	#[Action( 'generate_before_do_template_part' )]
	/** TODO: */
	public function do_template_part() {

		$template_type = null;
		if ( is_singular( $this->type->get_post_type() ) ) {
			$template_type = 'single';
		} elseif ( is_post_type_archive( $this->type->get_post_type() ) ) {
			$template_type = 'archive';
		} elseif ( isset( $this->taxonomies ) ) {
			foreach ( array_keys( $this->taxonomies->get_taxonomies() ) as $taxonomy ) {
				if ( is_tax( "{$this->type->get_post_type()}_{$taxonomy}" ) ) {
					$template_type = 'archive';
				}
			}
		}

		if ( null === $template_type ) {
			return;
		}

		Template::create()
			->set_template( "types/{$this->type->get_post_type()}/{$template_type}" )
			->set_context( $this->get_template_variables( $template_type, get_the_ID() ) )
			->render_template();
	}

	/** Geeft template variabelen terug */
	protected function get_template_variables( string $template_type, int $post_id ): array {

		$template_variables = match ( $template_type ) {
			'single' => $this->type->get_single_template_variables( $post_id ),
			'archive' => $this->type->get_archive_template_variables( $post_id ),
		};

		$title_parameters = generate_get_the_title_parameters();

		$template_variables += [
			'post'       => [
				'id'        => $post_id,
				'title'     => the_title( $title_parameters['before'], $title_parameters['after'], false ),
				'class'     => implode( ' ', get_post_class( 'siw-type', $post_id ) ),
				'microdata' => generate_get_microdata( 'article' ), // TODO: juiste schema type gebruiken (event, job_posting, review?)
			],
			'show_title' => generate_show_title(),
		];

		return $template_variables;
	}
}
