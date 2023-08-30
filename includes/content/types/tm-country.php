<?php declare(strict_types=1);

namespace SIW\Content\Types;

use SIW\Content\Type;
use SIW\Data\Country;
use SIW\Data\Project_Type;
use SIW\Data\Special_Page;
use SIW\Data\Work_Type;
use SIW\Elements\Interactive_SVG_Map;
use SIW\Elements\Quote;
use SIW\Helpers\Template;
use SIW\Util\CSS;
use SIW\Util\Links;


/**
 * Wereld (basis) projecten
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
class TM_Country extends Type {

	/** {@inheritDoc} */
	protected string $post_type = 'tm_country';

	/** {@inheritDoc} */
	protected string $menu_icon = 'dashicons-location-alt';

	/** {@inheritDoc} */
	protected string $slug = 'wereld-basis-projecten';

	/** {@inheritDoc} */
	protected bool $archive_taxonomy_filter = true;

	/** {@inheritDoc} */
	protected bool $archive_masonry = true;

	/** {@inheritDoc} */
	protected int $archive_column_width = 25;

	/** {@inheritDoc} */
	protected string $orderby = 'title';

	/** {@inheritDoc} */
	protected string $archive_order = 'ASC';

	/** {@inheritDoc} */
	protected string $admin_order = 'ASC';

	/** {@inheritDoc} */
	protected bool $has_carousel_support = true;

	/** {@inheritDoc} */
	protected string $upload_subdir = 'op-maat';

	/** {@inheritDoc} */
	public function get_meta_box_fields(): array {
		$meta_box_fields = [
			[
				'id'          => 'country',
				'name'        => __( 'Land', 'siw' ),
				'type'        => 'select_advanced',
				'required'    => true,
				'options'     => siw_get_countries_list( Country::TAILOR_MADE ),
				'placeholder' => __( 'Selecteer een land', 'siw' ),
			],
			[
				'id'       => 'work_type',
				'name'     => __( 'Soort werk', 'siw' ),
				'type'     => 'checkbox_list',
				'required' => true,
				'options'  => siw_get_work_types_list( Work_Type::TAILOR_MADE, Work_Type::SLUG ),
			],
			[
				'id'       => 'quote',
				'name'     => __( 'Quote', 'siw' ),
				'type'     => 'text',
				'required' => true,
				'size'     => 100,
			],
			[
				'id'       => 'introduction',
				'name'     => __( 'Introductie', 'siw' ),
				'desc'     => __( 'Inclusief beste reistijd', 'siw' ),
				'type'     => 'wysiwyg',
				'required' => true,
			],
			[
				'id'       => 'description',
				'name'     => __( 'Beschrijving', 'siw' ),
				'desc'     => __( 'Beschrijf de Wereld Basis projecten in dit land', 'siw' ),
				'type'     => 'wysiwyg',
				'required' => true,
			],
			[
				'id'               => 'image',
				'name'             => __( 'Afbeelding', 'siw' ),
				'type'             => 'image_advanced',
				'required'         => true,
				'force_delete'     => true,
				'max_file_uploads' => 1,
				'max_status'       => false,
				'image_size'       => 'thumbnail',
			],
		];
		return $meta_box_fields;
	}

	/** {@inheritDoc} */
	protected function get_taxonomies(): array {
		$taxonomies['continent'] = [
			'labels' => [
				'name'          => _x( 'Continent', 'Taxonomy General Name', 'siw' ),
				'singular_name' => _x( 'Continent', 'Taxonomy Singular Name', 'siw' ),
				'menu_name'     => __( 'Continenten', 'siw' ),
				'all_items'     => __( 'Alle continenten', 'siw' ),
				'add_new_item'  => __( 'Continent toevoegen', 'siw' ),
				'update_item'   => __( 'Continent bijwerken', 'siw' ),
				'view_item'     => __( 'View Item', 'siw' ),
				'search_items'  => __( 'Zoek continenten', 'siw' ),
				'not_found'     => __( 'Geen continenten gevonden', 'siw' ),
			],
			'args'   => [
				'public' => true,
			],
			'slug'   => 'wereld-basis-projecten-in',
			'filter' => true,
		];
		return $taxonomies;
	}

	/** {@inheritDoc} */
	protected function get_labels(): array {
		$labels = [
			'name'               => __( 'Wereld Basis landen', 'siw' ),
			'singular_name'      => __( 'Wereld Basis land', 'siw' ),
			'add_new'            => __( 'Nieuw Wereld Basis land', 'siw' ),
			'add_new_item'       => __( 'Voeg Wereld Basis land toe', 'siw' ),
			'edit_item'          => __( 'Bewerk Wereld Basis land', 'siw' ),
			'new_item'           => __( 'Nieuw Wereld Basis land', 'siw' ),
			'all_items'          => __( 'Alle Wereld Basis landen', 'siw' ),
			'view_item'          => __( 'Bekijk Wereld Basis land', 'siw' ),
			'search_items'       => __( 'Zoek Wereld Basis land', 'siw' ),
			'not_found'          => __( 'Geen Wereld Basis landen gevonden', 'siw' ),
			'not_found_in_trash' => __( 'Geen Wereld Basis landen gevonden in de prullenbak', 'siw' ),
			'archives'           => __( 'Alle Wereld Basis landen', 'siw' ),
		];
		return $labels;
	}

	/** {@inheritDoc} */
	protected function get_archive_title( string $archive_title ): string {
		return __( 'Wereld Basis', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_archive_intro(): array {
		$intro = siw_get_option( 'tm_country.archive_intro' );
		return [ $intro ];
	}

	/** {@inheritDoc} */
	public function add_archive_content() {
		$images = siw_meta( 'image', [ 'limit' => 1 ] );
		$image = reset( $images );

		$template_vars = [
			'image' => wp_get_attachment_image( $image['ID'], 'large' ),
			'quote' => \rwmb_get_value( 'quote' ),
			'link'  => Links::generate_link( get_permalink(), __( 'Lees meer', 'siw' ), [ 'class' => 'page-link' ] ),
		];
		Template::create()->set_template( 'types/tm_country_archive' )->set_context( $template_vars )->render_template();
	}

	/** {@inheritDoc} */
	public function add_single_content() {

		$images = siw_meta( 'image', [ 'limit' => 1 ] );
		$image = reset( $images );

		$country = siw_get_country( siw_meta( 'country' ) );
		$tailor_made_page = siw_get_project_type_page( Project_Type::WORLD_BASIC() );
		$child_policy_link = siw_get_special_page( Special_Page::CHILD_POLICY() );

		$template_vars = [
			'image'             => wp_get_attachment_image( $image['ID'], 'large' ),
			'mapcss'            => CSS::HIDE_ON_MOBILE_CLASS,
			'worldmap'          => Interactive_SVG_Map::create()
				->set_map( Interactive_SVG_Map::MAP_WORLD )
				->select_region( $country->get_iso_code() )
				->set_focus_region( $country->get_iso_code() )
				->set_zoom_max( 2 )
				->generate(),
			'country'           => $country->get_name(),
			'introduction'      => rwmb_get_value( 'introduction' ),
			'description'       => rwmb_get_value( 'description' ),
			'quote'             => Quote::create()->set_quote( rwmb_get_value( 'quote' ) )->generate(),
			'sign_up_link'      => Links::generate_button_link( get_permalink( $tailor_made_page ), __( 'Meld je aan', 'siw' ) ),
			'child_policy_link' => Links::generate_link( get_permalink( $child_policy_link ), __( 'Lees meer over ons beleid.', 'siw' ) ),
		];
		// welke type projecten zijn er
		$work_types = siw_meta( 'work_type' );

		$template_vars['work_types'] = array_map(
			fn( string $work_type ): string => siw_get_work_type( $work_type )?->get_name(),
			$work_types
		);

		$has_child_projects = ! empty(
			array_filter(
				$work_types,
				fn( string $work_type ): bool => siw_get_work_type( $work_type )?->needs_review(),
			)
		);

		// plaats opmerking als er kinderprojecten zijn
		if ( $has_child_projects ) {
			$template_vars += [ 'has_child_projects' => true ];
		}
		Template::create()->set_template( 'types/tm_country_single' )->set_context( $template_vars )->render_template();
	}

	/** {@inheritDoc} */
	protected function get_social_share_cta(): string {
		return __( 'Deel deze landenpagina', 'siw' );
	}

	/** {@inheritDoc} */
	protected function generate_title( array $data, array $postarr ): string {
		return siw_get_country( $postarr['country'] )->get_name();
	}
}
