<?php declare(strict_types=1);

namespace SIW\Content\Types;

use SIW\Content\Type;
use SIW\Data\Country;
use SIW\Data\Work_Type;
use SIW\Elements;
use SIW\Elements\World_Map;
use SIW\i18n;
use SIW\Util\CSS;
use SIW\Util\Links;

/**
 * Op Maat landen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.?
 */
class TM_Country extends Type {

	/**
	 * {@inheritDoc}
	 */
	protected string $post_type = 'tm_country';

	/**
	 * {@inheritDoc}
	 */
	protected string $menu_icon = 'dashicons-location-alt';

	/**
	 * {@inheritDoc}
	 */
	protected string $slug = 'vrijwilligerswerk-op-maat';
	
	/**
	 * {@inheritDoc}
	 */
	protected bool $archive_taxonomy_filter = true;

	/**
	 * {@inheritDoc}
	 */
	protected bool $archive_masonry = true;

	/**
	 * {@inheritDoc}
	 */
	protected int $archive_column_width = 25;

	/**
	 * {@inheritDoc}
	 */
	protected string $orderby = 'title';

	/**
	 * {@inheritDoc}
	 */
	protected string $archive_order = 'ASC';

	/**
	 * {@inheritDoc}
	 */
	protected string $admin_order = 'ASC';

	/**
	 * {@inheritDoc}
	 */
	protected bool $has_carousel_support = true;

	/**
	 * {@inheritDoc}
	 */
	protected string $upload_subdir = 'op-maat';
	
	/**
	 * {@inheritDoc}
	 */
	public function get_meta_box_fields() : array {
		$meta_box_fields = [
			[
				'id'          => 'country',
				'name'        => __( 'Land', 'siw' ),
				'type'        => 'select_advanced',
				'required'    => true,
				'options'     => siw_get_countries_list( Country::TAILOR_MADE, 'slug' ),
				'placeholder' => __( 'Selecteer een land', 'siw' ),
			],
			[
				'id'          => 'work_type',
				'name'        => __( 'Soort werk', 'siw' ),
				'type'        => 'checkbox_list',
				'required'    => true,
				'options'     => siw_get_work_types_list( Work_Type::TAILOR_MADE, 'slug' ),
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
				'desc'     => __( 'Inclusief beste reistijd', 'siw'),
				'type'     => 'wysiwyg',
				'required' => true,
			],
			[
				'id'       => 'description',
				'name'     => __( 'Beschrijving', 'siw' ),
				'desc'     => __( 'Beschrijf de Op Maat projecten in dit land', 'siw'),
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

	/**
	 * {@inheritDoc}
	 */
	protected function get_taxonomies() : array {
		$taxonomies['continent'] = [
			'labels' => [
				'name'                       => _x( 'Continent', 'Taxonomy General Name', 'siw' ),
				'singular_name'              => _x( 'Continent', 'Taxonomy Singular Name', 'siw' ),
				'menu_name'                  => __( 'Continenten', 'siw' ),
				'all_items'                  => __( 'Alle continenten', 'siw' ),
				'add_new_item'               => __( 'Continent toevoegen', 'siw' ),
				'update_item'                => __( 'Continent bijwerken', 'siw' ),
				'view_item'                  => __( 'View Item', 'siw' ),
				'search_items'               => __( 'Zoek continenten', 'siw' ),
				'not_found'                  => __( 'Geen continenten gevonden', 'siw' ),
			],
			'args' => [
				'public' => true,
			],
			'slug'   => 'vrijwilligerswerk-op-maat-in',
			'filter' => true,
		];
		return $taxonomies;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_labels() : array {
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

	/**
	 * {@inheritDoc}
	 */
	protected function get_archive_title( string $archive_title ) : string {
		return __( 'Vrijwilligerswerk op Maat', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_archive_intro() : array {

		$url = i18n::get_translated_page_url( (int) siw_get_option( 'pages.explanation.tailor_made' ) );
		$link = Links::generate_link( $url, __( 'Projecten Op Maat', 'siw' ) );

		$intro = [
			__( 'Hieronder zie je de landenpagina’s van de Projecten op Maat.', 'siw' ),
			__( 'Per land leggen we uit welke type projecten wij aanbieden.', 'siw' ),
			__( 'Tijdens onze Projecten Op Maat bepaal je samen met een regiospecialist wat je gaat doen en hoe lang jouw project duurt.', 'siw' ),
			sprintf( __( 'We vertellen je meer over de werkwijze van deze projecten op onze pagina %s.', 'siw' ), $link ),
		];
		return $intro;
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_archive_content() {
		//TODO: verplaatsen naar init?
		$images = siw_meta( 'image', ['limit' => 1 ] );
		$image = reset( $images );

		$continent = siw_meta( 'siw_tm_country_continent');

		?>
		<div class="grid-100">
			<?php echo wp_get_attachment_image( $image['ID'], 'large'); ?>
		</div>
		<div class="grid-100">
			<?php echo wpautop( esc_html( rwmb_get_value( 'quote' ) ) ); ?>
		</div>
		<div class="grid-100">
			<?php echo Links::generate_button_link( get_permalink() , __( 'Lees meer', 'siw' ) );?>
		</div>
		<hr>
		<div class="grid-100">
			<?php echo esc_html( $continent->name ); ?>
		</div>
		<?php
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_single_content() {
		//TODO: verplaatsen naar init?
		$country = siw_get_country( siw_meta('country') );
		$continent = $country->get_continent();
		?>
		<div class="grid-50 <?php echo CSS::HIDE_ON_MOBILE_CLASS; ?>" data-sal="slide-right" data-sal-duration="1800" data-sal-easing="ease-out-sine">
			
			<?php 
				$world_map = new World_Map();
				echo $world_map->generate( $country, 2 );
			?>
		</div>
		<div class="grid-50" data-sal="slide-left" data-sal-duration="1800" data-sal-easing="ease-out-sine">
			<h2><?php printf( esc_html__( 'Projecten Op Maat in %s', 'siw' ), $country->get_name() );  ?></h2>
			<p><?php echo wp_kses_post( rwmb_get_value( 'introduction' ) );?></p>
			<b><?php esc_html_e( 'Dit is het type projecten dat we hier aanbieden:', 'siw' );?></b>
			<p>
				<?php
				$work_types = siw_meta( 'work_type' );
				$has_child_projects = false;
				foreach ( $work_types as $work_type ) {
					$work_type = siw_get_work_type( $work_type );
					if ( 'kinderen' == $work_type->get_slug() ) {
						$has_child_projects = true; //TODO: misschien array_key_exists gebruiken?
					}

					printf( '%s %s<br>', Elements::generate_icon( $work_type->get_icon_class(), 2, 'circle' ), $work_type->get_name() );
				}
				?>
			</p>
		</div>
		<div class="grid-100" data-sal="fade" data-sal-duration="1850" data-sal-easing="ease-out-sine">
			<?php echo Elements::generate_quote( rwmb_get_value( 'quote' ) ); ?>
		</div>
		<div class="grid-50 push-50" data-sal="slide-left" data-sal-duration="1800" data-sal-easing="ease-out-sine">
			<?php
				$images = siw_meta( 'image', ['limit' => 1 ] );
				$image = reset( $images );
				echo wp_get_attachment_image( $image['ID'], 'large'); ?>
		</div>

		<div class="grid-50 pull-50" data-sal="slide-right" data-sal-duration="1800" data-sal-easing="ease-out-sine">
			<p><?php echo wp_kses_post( rwmb_get_value( 'description' ) );?></p>
			<?php if ( $has_child_projects ) : ?>
			<p>
				<?php
					esc_html_e( 'Goed om te weten: SIW beoordeelt projecten met kinderen volgens de richtlijnen van het Better Care Network.', 'siw' );
					echo do_shortcode(' [siw_pagina_lightbox link_tekst="Lees meer over ons beleid." pagina="kinderbeleid"]');
				?>
			</p>
			<?php endif ?>
			<p>
			<?php 
				echo sprintf( esc_html__( 'Samen met de regiospecialist %s ga je aan de slag om van jouw idee werkelijkheid te maken.', 'siw' ), $continent->get_name() ) . SPACE; 
				echo esc_html__( 'Word jij hiervan enthousiast, ga dan naar onze pagina over Op Maat projecten.', 'siw' ) . BR2;
				
				//TODO: verplaatsen naar init/constructor
				$tailor_made_page_link = i18n::get_translated_page_url( (int) siw_get_option( 'pages.explanation.tailor_made' ) );
				echo Links::generate_button_link( $tailor_made_page_link, __( 'Meld je aan', 'siw' ) );
				?>
			</p>
		</div>

		<!-- Start stappenplan TODO: naar instellingenmenu verplaatsen -->
		<div class="grid-100">
			<h2><?php esc_html_e( 'Zo werkt het', 'siw' );?></h2>
		</div>
		<?php
		echo Elements::generate_features(
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
			],
			4 //TODO:
		);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_social_share_cta() : string {
		return __( 'Deel deze landenpagina', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function generate_title( array $data, array $postarr ) : string {
		return siw_get_country( $postarr['country'] )->get_name();
	}

}