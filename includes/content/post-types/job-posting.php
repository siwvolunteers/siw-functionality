<?php declare(strict_types=1);
namespace SIW\Content\Post_Types;

use SIW\Attributes\Add_Action;
use SIW\Content\Post\Job_Posting as Job_Posting_Post;
use SIW\Content\Post_Types\Post_Type;
use SIW\Data\Job_Type;
use SIW\Data\Post_Type_Support;
use SIW\Elements\Accordion_Tabs;
use SIW\Helpers\Template;
use SIW\Properties;
use SIW\Structured_Data\Employment_Type;
use SIW\Structured_Data\Job_Posting as Job_Posting_Structured_Data;
use SIW\Structured_Data\NL_Non_Profit_Type;
use SIW\Structured_Data\Organization;
use SIW\Structured_Data\Place;
use SIW\Structured_Data\Postal_Address;
use SIW\Structured_Data\Thing;

class Job_Posting extends Post_Type {

	/** {@inheritDoc} */
	protected static function get_dashicon(): string {
		return 'nametag';
	}

		/** {@inheritDoc} */
	protected static function get_slug(): string {
		return 'vacatures';
	}

	/** {@inheritDoc} */
	protected static function get_post_type_supports(): array {
		return [
			Post_Type_Support::TITLE,
			Post_Type_Support::SOCIAL_SHARE,
		];
	}

	/** {@inheritDoc} */
	protected static function get_admin_columns(): array {
		return [
			'title',
			'job_type' => [
				'title'    => __( 'Soort functie', 'siw' ),
				'function' => function ( \WP_Post $post ): void {
					//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo Job_Type::tryFrom( siw_meta( 'job_type', [], $post->ID ) )->label();
				},
			],
			'deadline' => [
				'title'       => __( 'Deadline', 'siw' ),
				'meta_key'    => 'deadline',
				'date_format' => 'd-m-Y',
				'default'     => 'DESC',
			],
		];
	}

	/** {@inheritDoc} */
	protected static function get_site_sortables(): array {
		return [
			'deadline' => [
				'meta_key' => 'deadline',
				'default'  => 'ASC',
			],
		];
	}

	protected static function get_settings_fields(): array {
		return [
			[
				'type' => 'heading',
				'name' => __( 'Vacaturetekst', 'siw' ),
			],
			[
				'id'       => 'organization_profile',
				'name'     => __( 'Wie zijn wij', 'siw' ),
				'type'     => 'wysiwyg',
				'required' => true,
			],
			[
				'id'     => 'hr_manager',
				'type'   => 'group',
				'fields' => [
					[
						'type' => 'heading',
						'name' => __( 'P&O manager', 'siw' ),
						'desc' => __( 'Standaard contactpersoon voor sollicitaties', 'siw' ),
					],
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
		];
	}

	/** {@inheritDoc} */
	protected static function get_singular_label(): string {
		return __( 'Vacature', 'siw' );
	}

	/** {@inheritDoc} */
	protected static function get_plural_label(): string {
		return __( 'Vacatures', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_custom_post( \WP_Post|int $post ): Job_Posting_Post {
		return new Job_Posting_Post( $post );
	}

	/** {@inheritDoc} */
	public static function get_meta_box_fields(): array {
		$hr_manager = siw_get_option( 'job_posting.hr_manager' );
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
				'id'                => 'excerpt',
				'name'              => __( 'Korte samenvatting', 'siw' ),
				'label_description' => __( 'Wordt getoond op overzichtspagina', 'siw' ),
				'type'              => 'textarea',
				'required'          => true,
			],
			[
				'id'               => 'image',
				'name'             => __( 'Afbeelding', 'siw' ),
				'type'             => 'image_advanced',
				'required'         => false,
				'force_delete'     => false,
				'max_file_uploads' => 1,
				'max_status'       => false,
				'image_size'       => 'thumbnail',
			],
			[
				'id'       => 'job_type',
				'name'     => __( 'Soort functie', 'siw' ),
				'type'     => 'button_group',
				'required' => true,
				'options'  => siw_get_enum_array( Job_Type::cases() ),
				'std'      => Job_Type::VOLUNTEER->value,
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
				'id'       => 'deadline',
				'name'     => __( 'Deadline', 'siw' ),
				'type'     => 'date',
				'required' => true,
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
						'std'  => siw_get_option( 'job_posting.organization_profile' ),
					],
				],
			],
		];
		return $metabox_fields;
	}

	/** {@inheritDoc} */
	protected function get_taxonomies(): array {
		return [];
	}

	/** {@inheritDoc} */
	protected static function get_active_posts_meta_query(): array {
		return [
			'key'     => 'deadline',
			'value'   => gmdate( 'Y-m-d' ),
			'compare' => '>',
		];
	}

	/** Geeft type vacature terug */
	protected function get_job_type(): string {
		$job_type = Job_Type::tryFrom( siw_meta( 'job_type' ) ) ?? Job_Type::VOLUNTEER;
		return $job_type->value;
	}

	public function get_template_variables( string $type, int $post_id ): array {

		$post = new Job_Posting_Post( $post_id );

		$template_variables = [
			'job_posting' => $post,
		];

		if ( 'single' === $type ) {
			$accordion_items = [
				[
					'title'   => __( 'Wat ga je doen?', 'siw' ),
					'content' => $post->get_work(),
				],
				[
					'title'   => __( 'Wie ben jij?', 'siw' ),
					'content' => $post->get_qualifications(),
				],
				[
					'title'   => __( 'Wat bieden wij jou?', 'siw' ),
					'content' => $post->get_perks(),
				],
				[
					'title'   => __( 'Wie zijn wij?', 'siw' ),
					'content' => siw_get_option( 'job_posting.organization_profile' ),
				],
			];
			$template_variables['accordion'] = Accordion_Tabs::create()->add_items( $accordion_items )->generate();
		}
		return $template_variables;
	}

	protected function get_structured_data( int $post_id ): ?Thing {

		$post = new Job_Posting_Post( $post_id );
		$structured_data = Job_Posting_Structured_Data::create()
			->set_title( $post->get_title() )
			->set_description( $post->get_introduction() )
			->set_date_posted( new \DateTime( get_the_modified_date( 'Y-m-d', $post_id ) ) )
			->set_valid_through( $post->get_deadline() )
			->set_employment_type( Employment_Type::PART_TIME );

		switch ( $post->get_job_type() ) {
			case Job_Type::PAID:
				break;
			case Job_Type::INTERNSHIP:
				$structured_data->add_employment_type( Employment_Type::INTERN );
				break;
			case Job_Type::VOLUNTEER:
			default:
				$structured_data->add_employment_type( Employment_Type::VOLUNTEER );
		}

		if ( 0 !== $post->get_thumbnail_id() ) {
			$structured_data->set_image( wp_get_attachment_image_url( $post->get_thumbnail_id() ) );
		}

		$structured_data->set_hiring_organization(
			Organization::create()
			->set_name( Properties::NAME )
			->set_same_as( SIW_SITE_URL )
			->set_logo( get_site_icon_url() )
			->set_non_profit_status( NL_Non_Profit_Type::ANBI )
		)
		->set_qualifications( $post->get_qualifications() )
		->set_responsibilities( $post->get_work() )
		->set_employer_overview( siw_get_option( 'job_posting.organization_profile', '' ) )
		->set_job_benefits( $post->get_perks() )
		->set_job_location(
			Place::create()
			->set_address(
				Postal_Address::create()
					->set_street_address( Properties::ADDRESS )
					->set_address_locality( Properties::CITY )
					->set_postal_code( Properties::POSTCODE )
					->set_address_region( 'NL' )
					->set_address_country( 'NL' )
			)
		);
		return $structured_data;
	}

	/** {@inheritDoc} */
	protected function generate_slug( array $data, array $postarr ): string {
		return $data['post_title'];
	}

	#[Add_Action( 'generate_after_entry_header' )]
	public function set_event_info(): void {
		if ( ! $this->is_archive_query() || false === get_the_ID() ) {
			return;
		}

		Template::create()
			->set_template( "types/{$this::get_post_type_base()}/archive" )
			->set_context( $this->get_template_variables( 'archive', get_the_ID() ) )
			->render_template();
	}
}
