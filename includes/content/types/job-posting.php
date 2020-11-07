<?php declare(strict_types=1);
namespace SIW\Content\Types;

use SIW\Content\Type;
use SIW\Elements;
use SIW\Formatting;
use SIW\Util\Links;

/**
 * Vacatures
 * 
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.0
 */
class Job_Posting extends Type {

	/**
	 * {@inheritDoc}
	 */
	protected string $post_type = 'job_posting';

	/**
	 * {@inheritDoc}
	 */
	protected string $menu_icon = 'dashicons-nametag';

	/**
	 * {@inheritDoc}
	 */
	protected string $slug = 'vacatures';

	/**
	 * {@inheritDoc}
	 */
	protected string $single_width = 'mobile';

	/**
	 * {@inheritDoc}
	 */
	protected string $orderby = 'meta_value';

	/**
	 * {@inheritDoc}
	 */
	protected string $orderby_meta_key = 'deadline';

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
	protected int $archive_column_width = 33;

	/**
	 * {@inheritDoc}
	 */
	public function get_meta_box_fields() : array {
		$hr_manager = siw_get_option( 'hr_manager');
		//TODO: verplaatsen naar options?
		$hr_manager = wp_parse_args(
			$hr_manager,
			[
				'name' => '',
				'title' => '',
				'email' => ''
			]
		);

		$metabox_fields = [
			[
				'type' => 'heading',
				'name' => __( 'Gegevens', 'siw' ),
			],
			[
				'id'       => 'hours',
				'name'     => __( 'Aantal uur per week', 'siw' ),
				'type'     => 'text',
				'required' => true,
				'size'     => 10,
				'append'   => __( 'uur/week', 'siw' ),
			],
			[
				'id'            => 'deadline',
				'name'          => __( 'Deadline', 'siw' ),
				'type'          => 'date',
				'required'      => true,
				'admin_columns' => 'after title',
			],
			[
				'id'        => 'paid',
				'name'      => __( 'Betaalde vacature', 'siw' ),
				'type'      => 'switch',
				'on_label'  => __( 'Ja', 'siw' ),
				'off_label' => __( 'Nee', 'siw'),
			],
			[
				'id'            => 'featured',
				'name'          => __( 'Uitgelichte vacature', 'siw' ),
				'type'          => 'switch',
				'on_label'      => __( 'Ja', 'siw' ),
				'off_label'     => __( 'Nee', 'siw'),
				'admin_columns' => 'after deadline',
			],
			[
				'name'     => __( 'Contactpersoon voor sollicitaties', 'siw' ),
				'type'     => 'heading',
				'desc'     => sprintf(
					__( 'Standaard: %s (%s), %s', 'siw' ),
					$hr_manager['name'],
					$hr_manager['title'],
					$hr_manager['email']
				),
			],
			[
				'id'        => 'different_application_manager',
				'name'      => __( 'Anders dan standaard contactpersoon', 'siw' ),
				'type'      => 'switch',
				'on_label'  => __( 'Ja', 'siw' ),
				'off_label' => __( 'Nee', 'siw' ),
			],
			[
				'id'        => 'application_manager',
				'type'      => 'group',
				'visible'   => [ 'different_application_manager', true ],
				'fields'    => [
					[
						'id'       => 'name',
						'name'     => __( 'Naam', 'siw' ),
						'type'     => 'text',
						'required' => true,
					],
					[
						'id'       => 'title',
						'name'     => __( 'Functie', 'siw' ),
						'type'     => 'text',
						'required' => true,
					],
					[
						'id'       => 'email',
						'name'     => __( 'E-mail', 'siw' ),
						'type'     => 'email',
						'required' => true,
					],
				],
			],
			[
				'name'     => __( 'Contactpersoon voor meer informatie', 'siw' ),
				'type'     => 'heading',
			],
			[
				'id'        => 'different_contact_person',
				'name'      => __( 'Anders dan contactpersoon voor sollicitaties', 'siw' ),
				'type'      => 'switch',
				'on_label'  => __( 'Ja', 'siw' ),
				'off_label' => __( 'Nee', 'siw' ),
			],
			[
				'id'        => 'contact_person',
				'type'      => 'group',
				'visible'   => [ 'different_contact_person', true ],
				'fields'    => [
					[
						'id'       => 'name',
						'name'     => __( 'Naam', 'siw' ),
						'type'     => 'text',
						'required' => true,
					],
					[
						'id'       => 'title',
						'name'     => __( 'Functie', 'siw' ),
						'type'     => 'text',
						'required' => true,
					],
					[
						'id'       => 'email',
						'name'     => __( 'E-mail', 'siw' ),
						'type'     => 'email',
						'required' => true,
					],
				],
			],
			[
				'name'     => __( 'Beschrijving vacature', 'siw' ),
				'type'     => 'heading',
			],
			[
				'id'       => 'introduction',
				'name'     => __( 'Inleiding', 'siw' ),
				'type'     => 'wysiwyg',
				'required' => true,
			],
			[
				'id'        => 'description',
				'type'      => 'group',
				'fields'    => [
					[
						'id'       => 'work',
						'name'     => __( 'Wat ga je doen?', 'siw' ),
						'type'     => 'wysiwyg',
						'required' => true,
					],
					[
						'id'       => 'qualifications',
						'name'     => __( 'Wie ben jij?', 'siw' ),
						'type'     => 'wysiwyg',
						'required' => true,
					],
					[
						'id'       => 'perks',
						'name'     => __( 'Wat bieden wij?', 'siw' ),
						'type'     => 'wysiwyg',
						'required' => true,
					],
				]
			],
		];
		return $metabox_fields;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_labels() : array {
		$labels = [
			'name'               => __( 'Vacatures', 'siw' ),
			'singular_name'      => __( 'Vacature', 'siw' ),
			'add_new'            => __( 'Nieuwe vacature', 'siw' ),
			'add_new_item'       => __( 'Nieuwe vacature toevoegen', 'siw' ),
			'edit_item'          => __( 'Vacature bewerken', 'siw' ),
			'all_items'          => __( 'Alle vacatures', 'siw' ),
			'search_items'       => __( 'Vacatures zoeken', 'siw' ),
			'not_found'          => __( 'Geen vacatures gevonden', 'siw' ),
		];
		return $labels;
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function get_taxonomies() : array {
		$taxonomies['type'] = [
			'labels' => [
				'name'                       => _x( 'Soort vacature', 'Taxonomy General Name', 'siw' ),
				'singular_name'              => _x( 'Soort vacature', 'Taxonomy Singular Name', 'siw' ),
				'menu_name'                  => __( 'Soort vacature', 'siw' ),
				'all_items'                  => __( 'Alle vacaturesoorten', 'siw' ),
				'add_new_item'               => __( 'Soort vacature toevoegen', 'siw' ),
				'update_item'                => __( 'Soort vacatures bijwerken', 'siw' ),
				'view_item'                  => __( 'Bekijk soort vacature', 'siw' ),
				'search_items'               => __( 'Zoek vacaturesoorten', 'siw' ),
				'not_found'                  => __( 'Geen vacaturesoorten gevonden', 'siw' ),
			],
			'args' => [
				'public' => true,
			],
			'slug'   => 'vacatures-voor',
			'filter' => true,
		];
		return $taxonomies;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_social_share_cta() : string {
		return __( 'Deel deze vacature', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	function get_seo_noindex( int $post_id ) : bool {
		return siw_meta( 'deadline', [], $post_id ) < date( 'Y-m-d' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_active_posts_meta_query() : array {
		return [
			'key'     => 'deadline',
			'value'   => date('Y-m-d'),
			'compare' => '>'
		];
	}

	/**
	 * {@inheritDoc}
	 * 
	 * @todo refactor enzo
	 */
	public function add_single_content() {
		
		//Eigenschappen TODO: subtitle oid
		echo '<h5>';
		if ( siw_meta( 'paid' ) ) {
			echo sprintf( esc_html__( 'Betaalde functie (%s uur/week)', 'siw' ), siw_meta( 'hours' ) );
		}
		else {
			echo sprintf( esc_html__( 'Vrijwillige functie (%s uur/week)', 'siw' ), siw_meta( 'hours' ) );
		}
		echo '</h5>';
		echo '<hr>';
		//Inleiding
		echo wpautop( wp_kses_post( siw_meta( 'introduction' ) ) );

		//Inhoud
		$description = siw_meta( 'description' );
		echo '<h2>' . esc_html__( 'Wat houdt deze vacature in?', 'siw' ) . '</h2>';
		echo '<p>';
		echo Elements::generate_accordion([
			[
				'title'   => __( 'Wat ga je doen?', 'siw' ),
				'content' => $description['work'],
			],
			[
				'title'   => __( 'Wie ben jij?', 'siw' ),
				'content' => $description['qualifications'],
			],
			[
				'title'   => __( 'Wat bieden wij jou?', 'siw' ),
				'content' => $description['perks'],
			],
			[
				'title'   => __( 'Wie zijn wij?', 'siw' ),
				'content' => siw_get_option( 'job_postings_organization_profile' ),
			],
		]);
		echo '</p>';

		//Meer informatie
		if ( siw_meta( 'different_contact_person' ) ) {
			$contact_person = $this->get_contact_person();

			echo '<h2>' . esc_html__('Meer weten?', 'siw') . '</h2>';
			echo wpautop(
				wp_kses_post(
					sprintf(
						__( 'Voor meer informatie kun je contact opnemen met: %s (%s), %s', 'siw' ),
						BR . $contact_person['name'],
						$contact_person['title'],
						Links::generate_mailto_link( $contact_person['email'] )
					)
				)
			);
		}
		
		//Soliciteren
		$application_manager = $this->get_application_manager();

		echo '<h2>' . esc_html__( 'Solliciteren?', 'siw') . '</h2>';
		echo wpautop(
			wp_kses_post(
				sprintf(
					__( 'Je motivatie met cv kun je uiterlijk %s sturen naar: %s (%s), %s', 'siw' ),
					Formatting::format_date( siw_meta( 'deadline' ), true ),
					BR . $application_manager['name'],
					$application_manager['title'],
					Links::generate_mailto_link( $application_manager['email'] )
				)
			)
		);

		//JSON_LD toevoegen
		echo siw_generate_job_posting_json_ld( get_the_ID() );
	}

	/**
	 * Haal gegevens van 
	 *
	 * @return array
	 */
	protected function get_application_manager() : array {
		if ( siw_meta( 'different_application_manager' ) ) {
			return siw_meta( 'application_manager' );
		}
		return siw_get_option( 'hr_manager');
	}

	/**
	 * Haal gegevens van contactpersoon op
	 *
	 * @return array
	 */
	protected function get_contact_person() : array {
		if ( siw_meta( 'different_contact_person' ) ) {
			return siw_meta( 'contact_person' );
		}
		return $this->get_application_manager();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_archive_intro() : array {
		$hr_manager = siw_get_option( 'hr_manager' );

		$intro = [
			__( 'SIW ontvangt al ruim zestig jaar buitenlandse vrijwilligers op diverse projecten in Nederland en zendt Nederlandse vrijwilligers uit naar projecten over de hele wereld.', 'siw' ),
			__( 'Ruim 70 vrijwilligere medewerkers zetten zich hier vol overgave voor in.', 'siw' ),
			__( "Regelmatig zijn we op zoek naar nieuwe collega's.", 'siw' ),
			__( 'Ben jij op zoek naar een functie bij een organisatie met een internationaal speelveld en kom jij graag in aanraking met andere culturen?', 'siw' ),
			__( 'Wellicht heeft SIW Internationale Vrijwilligersprojecten dan een vacature voor jou.', 'siw' ),
			BR2 . __( 'Is er op dit moment geen geschikte vacature voor jou bij SIW?', 'siw' ),
			__( 'Je kunt ons ook een open sollicitatie sturen.', 'siw' ),
			__( 'Wij zijn altijd op zoek naar vrijwillige medewerkers die ons kunnen helpen met diverse kantoorwerkzaamheden.', 'siw' ),
			sprintf( __( "Stuur jouw motivatie en curriculum vitae onder vermelding van 'Open sollicitatie' naar %s", 'siw' ),  $hr_manager['email'] ),

		];
		return $intro;
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_archive_content() {
		$type = siw_meta( 'siw_job_posting_type' );
		$subtitle = siw_meta( 'paid' ) ? sprintf( __( 'Betaalde functie (%s uur/week)', 'siw' ), siw_meta( 'hours' ) ) : sprintf( __( 'Vrijwillige functie (%s uur/week)', 'siw' ), siw_meta( 'hours' ) );
		?>
		<div class="grid-100">
			<h5>
			<?php echo esc_html( $subtitle ); ?>
			<h5>
		<?php
		?>
		</div>
		<div class="grid-100">
			<?php the_excerpt(); ?>
		</div>
		<div class="grid-100">
			<?php echo Links::generate_button_link( get_permalink() , __( 'Lees meer', 'siw' ) );?>
		</div>
		<hr>
		<div class="grid-100">
			<?php printf( '%s', esc_html( $type->name ) ); ?>
		</div>
		<?php
	}

	/**
	 * {@inheritDoc}
	 */
	protected function generate_slug( array $data, array $postarr ): string {
		return $data['post_title'];
	}

}
