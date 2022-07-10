<?php declare(strict_types=1);

namespace SIW\Content\Types;

use SIW\Content\Type;
use SIW\Data\Country;
use SIW\Data\Work_Type;
use SIW\Elements\Features;
use SIW\Elements\Icon;
use SIW\Elements\Quote;
use SIW\Elements\World_Map;
use SIW\Helpers\Template;
use SIW\I18n;
use SIW\Util\CSS;
use SIW\Util\Links;


/**
 * Op Maat landen
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
class TM_Country extends Type {

	/** {@inheritDoc} */
	protected string $post_type = 'tm_country';

	/** {@inheritDoc} */
	protected string $menu_icon = 'dashicons-location-alt';

	/** {@inheritDoc} */
	protected string $slug = 'vrijwilligerswerk-op-maat';

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
				'desc'     => __( 'Beschrijf de Op Maat projecten in dit land', 'siw' ),
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
			'slug'   => 'vrijwilligerswerk-op-maat-in',
			'filter' => true,
		];
		return $taxonomies;
	}

	/** {@inheritDoc} */
	protected function get_labels(): array {
		$labels = [
			'name'               => __( 'Op Maat landen', 'siw' ),
			'singular_name'      => __( 'Op Maat land', 'siw' ),
			'add_new'            => __( 'Nieuw Op Maat land', 'siw' ),
			'add_new_item'       => __( 'Voeg Op Maat land toe', 'siw' ),
			'edit_item'          => __( 'Bewerk Op Maat land', 'siw' ),
			'new_item'           => __( 'Nieuw Op Maat land', 'siw' ),
			'all_items'          => __( 'Alle Op Maat landen', 'siw' ),
			'view_item'          => __( 'Bekijk Op Maat land', 'siw' ),
			'search_items'       => __( 'Zoek Op Maat land', 'siw' ),
			'not_found'          => __( 'Geen Op Maat landen gevonden', 'siw' ),
			'not_found_in_trash' => __( 'Geen Op Maat landen gevonden in de prullenbak', 'siw' ),
			'archives'           => __( 'Alle Op Maat landen', 'siw' ),
		];
		return $labels;
	}

	/** {@inheritDoc} */
	protected function get_archive_title( string $archive_title ): string {
		return __( 'Vrijwilligerswerk op Maat', 'siw' );
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

		$continent = siw_meta( 'siw_tm_country_continent' );
		$template_vars = [
			'image'     => wp_get_attachment_image( $image['ID'], 'large' ),
			'quote'     => \rwmb_get_value( 'quote' ),
			'link'      => Links::generate_button_link( get_permalink(), __( 'Lees meer', 'siw' ) ),
			'continent' => $continent->name,
		];
		Template::create()->set_template( 'types/tm_country_archive' )->set_context( $template_vars )->render_template();
	}

	/** {@inheritDoc} */
	public function add_single_content() {

		$images = siw_meta( 'image', [ 'limit' => 1 ] );
		$image = reset( $images );

		$country = siw_get_country( siw_meta( 'country' ) );
		$tailor_made_page_link = I18n::get_translated_page_url( (int) siw_get_option( 'pages.explanation.tailor_made' ) );

		$template_vars = [
			'image'             => wp_get_attachment_image( $image['ID'], 'large' ),
			'mapcss'            => CSS::HIDE_ON_MOBILE_CLASS,
			'worldmap'          => World_Map::create()->set_country( $country )->set_zoom( 2 )->generate(),
			'country'           => $country->get_name(),
			'introduction'      => rwmb_get_value( 'introduction' ),
			'description'       => rwmb_get_value( 'description' ),
			'quote'             => Quote::create()->set_quote( rwmb_get_value( 'quote' ) )->generate(),
			'sign_up_link'      => Links::generate_button_link( $tailor_made_page_link, __( 'Meld je aan', 'siw' ) ),
			'child_policy_link' => do_shortcode( '[siw_pagina_lightbox link_tekst="Lees meer over ons beleid." pagina="kinderbeleid"]' ),
			'features'          => $this->country_features(),
			'worktypes'         => [],
		];
		// welke type projecten zijn er
		$work_types = siw_meta( 'work_type' );
		foreach ( $work_types as $work_type ) {
			$worktype = siw_get_work_type( $work_type );
			$name = sprintf( '%s %s', Icon::create()->set_icon_class( $worktype->get_icon_class() )->set_has_background( true )->generate(), $worktype->get_name() );
			array_push( $template_vars['worktypes'], [ 'name' => $name ] );
		}
		// plaats opmerking als er kinderprojecten zijn
		if ( in_array( 'kinderen', $work_types, true ) ) {
			$template_vars += [ 'has_child_projects' => true ];
		}
		Template::create()->set_template( 'types/tm_country_single' )->set_context( $template_vars )->render_template();
	}

	/**Maak features voor te kijken hoe het werkt */
	public function country_features(): string {
		$features = Features::create()
			->set_columns( 4 )
			->add_items(
				[
					[
						'icon'    => 'siw-icon-file-signature',
						'title'   => '1. Aanmelding',
						'content' => 'Ben je geïnteresseerd in een Project Op Maat? Meld je dan direct aan via de website.',
					],
					[
						'icon'    => 'siw-icon-handshake',
						'title'   => '2. Kennismaking',
						'content' => 'Na het kennismakingsgesprek stelt de regiospecialist een selectie van drie Projecten Op Maat voor je samen.',
					],
					[
						'icon'    => 'siw-icon-clipboard-check',
						'title'   => '3. Bevestiging',
						'content' => 'Als je een passend Project Op Maat hebt gekozen, volgt de betaling. Vervolgens gaat de regiospecialist voor je aan de slag.',
					],
					[
						'icon'    => 'siw-icon-tasks',
						'title'   => '4. Voorbereiding',
						'content' => 'Kom naar de Infodag zodat je goed voorbereid aan jouw avontuur kan beginnen.',
					],
				]
			)->generate();
		return $features;
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
