<?php declare(strict_types=1);
namespace SIW\Content\Types;

use SIW\Content\Type;
use SIW\Elements\Accordion_Tabs;
use SIW\Helpers\Template;
use SIW\Util\Links;

/**
 * Vacatures
 *
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 */
class Job_Posting extends Type {

	/** {@inheritDoc} */
	protected string $post_type = 'job_posting';

	/** {@inheritDoc} */
	protected string $menu_icon = 'dashicons-nametag';

	/** {@inheritDoc} */
	protected string $slug = 'vacatures';

	/** {@inheritDoc} */
	protected string $single_width = 'mobile';

	/** {@inheritDoc} */
	protected string $orderby = 'meta_value';

	/** {@inheritDoc} */
	protected string $orderby_meta_key = 'deadline';

	/** {@inheritDoc} */
	protected bool $archive_masonry = true;

	/** {@inheritDoc} */
	protected int $archive_column_width = 33;

	/** {@inheritDoc} */
	public function get_meta_box_fields(): array {
		$hr_manager = siw_get_option( 'hr_manager' );
		// TODO: verplaatsen naar options?
		$hr_manager = wp_parse_args(
			$hr_manager,
			[
				'name'  => '',
				'title' => '',
				'email' => '',
			]
		);

		$metabox_fields = [
			[
				'type' => 'heading',
				'name' => __( 'Gegevens', 'siw' ),
			],
			[
				'id'                => 'abstract',
				'name'              => __( 'Korte samenvatting', 'siw' ),
				'label_description' => __( 'Wordt getoond op overzichtspagina', 'siw' ),
				'type'              => 'textarea',
				'required'          => true,
			],
			[
				'id'            => 'job_type',
				'name'          => __( 'Soort functie', 'siw' ),
				'type'          => 'button_group',
				'required'      => true,
				'options'       => [
					'volunteer'  => __( 'Vrijwillig', 'siw' ),
					'paid'       => __( 'Betaald', 'siw' ),
					'internship' => __( 'Stage', 'siw' ),
				],
				'std'           => 'volunteer',
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
				'name' => __( 'Contactpersoon voor sollicitaties', 'siw' ),
				'type' => 'heading',
				'desc' => sprintf(
					// translators: %1$s is de naam van de HR contactpersoon, %2$s is de functie en %3$s is het emailadres
					__( 'Standaard: %1$s (%2$s), %3$s', 'siw' ),
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
				'id'      => 'application_manager',
				'type'    => 'group',
				'visible' => [ 'different_application_manager', true ],
				'fields'  => [
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
				'name' => __( 'Contactpersoon voor meer informatie', 'siw' ),
				'type' => 'heading',
			],
			[
				'id'        => 'different_contact_person',
				'name'      => __( 'Anders dan contactpersoon voor sollicitaties', 'siw' ),
				'type'      => 'switch',
				'on_label'  => __( 'Ja', 'siw' ),
				'off_label' => __( 'Nee', 'siw' ),
			],
			[
				'id'      => 'contact_person',
				'type'    => 'group',
				'visible' => [ 'different_contact_person', true ],
				'fields'  => [
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
				'name' => __( 'Beschrijving vacature', 'siw' ),
				'type' => 'heading',
			],
			[
				'id'       => 'introduction',
				'name'     => __( 'Inleiding', 'siw' ),
				'type'     => 'wysiwyg',
				'required' => true,
			],
			[
				'id'     => 'description',
				'type'   => 'group',
				'fields' => [
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
					[
						'id'   => 'organization_profile',
						'name' => __( 'Wie zijn wij?', 'siw' ),
						'type' => 'custom_html',
						'std'  => siw_get_option( 'job_postings_organization_profile' ),
					],
				],
			],
		];
		return $metabox_fields;
	}

	/** {@inheritDoc} */
	protected function get_labels(): array {
		$labels = [
			'name'          => __( 'Vacatures', 'siw' ),
			'singular_name' => __( 'Vacature', 'siw' ),
			'add_new'       => __( 'Nieuwe vacature', 'siw' ),
			'add_new_item'  => __( 'Nieuwe vacature toevoegen', 'siw' ),
			'edit_item'     => __( 'Vacature bewerken', 'siw' ),
			'all_items'     => __( 'Alle vacatures', 'siw' ),
			'search_items'  => __( 'Vacatures zoeken', 'siw' ),
			'not_found'     => __( 'Geen vacatures gevonden', 'siw' ),
		];
		return $labels;
	}

	/** {@inheritDoc} */
	protected function get_taxonomies(): array {
		return [];
	}

	/** {@inheritDoc} */
	protected function get_social_share_cta(): string {
		return __( 'Deel deze vacature', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_seo_noindex( int $post_id ): bool {
		return siw_meta( 'deadline', [], $post_id ) < gmdate( 'Y-m-d' );
	}

	/** {@inheritDoc} */
	protected function get_active_posts_meta_query(): array {
		return [
			'key'     => 'deadline',
			'value'   => gmdate( 'Y-m-d' ),
			'compare' => '>',
		];
	}

	/** Geeft type vacature terug */
	protected function get_job_type(): string {

		$job_type = match ( siw_meta( 'job_type' ) ) {
			'paid'       => __( 'Betaalde functie', 'siw' ),
			'internship' => __( 'Stage', 'siw' ),
			default      => __( 'Vrijwillige functie', 'siw' ),
		};
		return $job_type;
	}

	/** {@inheritDoc} */
	public function add_single_content() {
		$template_vars = [
			'job_type'  => $this->get_job_type(),
			'hours'     => siw_meta( 'hours' ),
			'intro'     => siw_meta( 'introduction' ),
			'deadline'  => siw_format_date( siw_meta( 'deadline' ) ),
			'accordion' => Accordion_Tabs::create()->add_items( $this->get_accordion_items() )->generate(),
		];

		// contactpersoon voor informatie
		if ( siw_meta( 'different_contact_person' ) ) {
			$contact_person = $this->get_contact_person();

			$template_vars['contact_person'] = [
				'name'  => $contact_person['name'],
				'title' => $contact_person['title'],
				'email' => Links::generate_mailto_link( $contact_person['email'] ), // TODO: link verplaatsen naar template
			];
		}
		$application_manager = $this->get_application_manager();
		$template_vars['application_manager'] = [
			'name'  => $application_manager['name'],
			'title' => $application_manager['title'],
			'email' => Links::generate_mailto_link( $application_manager['email'] ), // TODO: link verplaatsen naar template
		];

		Template::create()->set_template( 'types/job_posting_single' )->set_context( $template_vars )->render_template();

		// JSON_LD toevoegen
		echo siw_generate_job_posting_json_ld( get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/** Geeft items voor accordion terug */
	protected function get_accordion_items(): array {
		$description = siw_meta( 'description' );
		$items = [
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
		];
		return $items;
	}

	/** Haal gegevens van hr manager op */
	protected function get_application_manager(): array {
		if ( siw_meta( 'different_application_manager' ) ) {
			return siw_meta( 'application_manager' );
		}
		return siw_get_option( 'hr_manager' );
	}

	/** Haal gegevens van contactpersoon op */
	protected function get_contact_person(): array {
		if ( siw_meta( 'different_contact_person' ) ) {
			return siw_meta( 'contact_person' );
		}
		return $this->get_application_manager();
	}

	/** {@inheritDoc} */
	protected function get_archive_intro(): array {
		$intro = siw_get_option( 'job_posting.archive_intro' );
		return [ $intro ];
	}

	/** {@inheritDoc} */
	public function add_archive_content() {
		$template_vars = [
			'job_type' => $this->get_job_type(),
			'hours'    => siw_meta( 'hours' ),
			'abstract' => siw_meta( 'abstract' ),
			'link'     => Links::generate_button_link( get_permalink(), __( 'Lees meer', 'siw' ) ), // TODO: link verplaatsen naar template
		];
		Template::create()->set_template( 'types/job_posting_archive' )->set_context( $template_vars )->render_template();
	}

	/** {@inheritDoc} */
	protected function generate_slug( array $data, array $postarr ): string {
		return $data['post_title'];
	}
}
