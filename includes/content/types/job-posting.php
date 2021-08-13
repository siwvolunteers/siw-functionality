<?php declare(strict_types=1);
namespace SIW\Content\Types;

use SIW\Content\Type;
use SIW\Elements\Accordion;
use SIW\Util\Links;
use SIW\Core\Template;

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
				'id'       => 'job_type',
				'name'     => __( 'Soort functie', 'siw' ),
				'type'     => 'button_group',
				'required' => true,
				'options' => [
					'volunteer' => __( 'Vrijwillig', 'siw' ),
					'paid'      => __( 'Betaald', 'siw' ),
					'internship' => __( 'Stage', 'siw' ),
				],
				'std' => 'volunteer',
				'admin_columns' => 'after title',
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
				'admin_columns' => 'after job_type',
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
	
	/** {@inheritDoc} */
	protected function get_taxonomies() : array {
		return [];
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
	 */
	protected function get_content_labels() : array {
		$labels = [
			'paid'					=> __( 'Betaalde functie', 'siw'),
			'internship'			=> __( 'Stage', 'siw' ),
			'volunteer'				=> __( 'Vrijwillige functie', 'siw' ),
			'label_work'   			=> __( 'Wat ga je doen?', 'siw' ),
			'label_qualifications'	=> __( 'Wie ben jij?', 'siw' ),
			'label_perks'   		=> __( 'Wat bieden wij jou?', 'siw' ),
			'label_profile'   		=> __( 'Wie zijn wij?', 'siw' ),
			'label_function'		=> __( 'Wat houdt deze vacature in?', 'siw' ),
		];
		return $labels;
	}
	/**
	 * regel voor type baan
	 */
	protected function get_job() : string {	
		$jobtype = siw_meta('job_type') ? siw_meta('job_type') : "volunteer";
		$job = $this->get_content_labels()[$jobtype];
		return($job);
	}

	/**
	 * {@inheritDoc}
	 * 
	 * @todo refactor enzo
	 */
	public function add_single_content() {
		$template_vars = [
							"job" => $this->get_job(),
							"hours" => siw_meta('hours'),
							"intro"=>wpautop( wp_kses_post( siw_meta( 'introduction' ) ) ),
							"profile"=>siw_get_option( 'job_postings_organization_profile' ),
							];
		$items = $this->accordeon_items();
		$template_vars += ['content'=> Accordion::create()->add_items($items)->generate()];
		// contactpersoon voor informatie
		if ( siw_meta( 'different_contact_person' ) ) {
			$template_vars += 	[
									"contact" =>  $this->get_contact_person()['name'],
									"contacttitle" => $this->get_contact_person()['title'],
									"contactemail" => Links::generate_mailto_link( $this->get_contact_person()['email'])
								];
		}
		// sollicitatie sturen naar
		$applicationmanager = $this->get_application_manager();
			$template_vars += 	[
									"application" =>  $this->get_application_manager()['name'],
									"applicationtitle" => $this->get_application_manager()['title'],
									"applicationemail" => Links::generate_mailto_link( $this->get_application_manager()['email']),
									"applicationdate" => siw_format_date( siw_meta( 'deadline' ))
								];
		echo Template::parse_template( "types/job_posting_single", $template_vars );
		//JSON_LD toevoegen
		echo siw_generate_job_posting_json_ld( get_the_ID() );
	}
	/**
	 * beschrijving vacature als accordeon
	 */
	protected function accordeon_items() : array{
		$labels = $this->get_content_labels();
		$description = siw_meta( 'description' );
		$items = [
				[
					'title'   => $labels['label_work'],
					'content' => $description['work'],
				],
				[
					'title'   => $labels['label_qualifications'],
					'content' => $description['qualifications'],
				],
				[
					'title'   => $labels['label_perks'],
					'content' => $description['perks'],
				],
				[
					'title'   => $labels['label_profile'],
					'content' => siw_get_option( 'job_postings_organization_profile' ),
				],
			];
		return($items);
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
			__( 'SIW ontvangt al ruim vijfenzestig jaar buitenlandse vrijwilligers op diverse projecten in Nederland en zendt Nederlandse vrijwilligers uit naar projecten over de hele wereld.', 'siw' ),
			__( 'Ruim 70 vrijwilligere medewerkers zetten zich hier vol overgave voor in.', 'siw' ),
			__( "Regelmatig zijn we op zoek naar nieuwe collega's.", 'siw' ),
			__( "Ook stagiaires en afstudeerders kunnen bij ons terecht.", "siw"),
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
		$vars = [
			"job" => $this->get_job(),
			"hours" => siw_meta('hours'),
			'excerpt' => apply_filters( 'the_excerpt', get_the_excerpt() ),
			'link' => Links::generate_button_link( get_permalink() , __( 'Lees meer', 'siw' ) )
			];
		echo Template::parse_template( "types/job_posting_archive", $vars );
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function generate_slug( array $data, array $postarr ): string {
		return $data['post_title'];
	}

}