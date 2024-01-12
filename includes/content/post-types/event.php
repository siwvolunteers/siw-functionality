<?php declare(strict_types=1);
namespace SIW\Content\Post_Types;

use SIW\Attributes\Add_Action;
use SIW\Content\Post\Event as Event_Post;
use SIW\Content\Post_Types\Post_Type;
use SIW\Data\Icons\Genericons_Neue;
use SIW\Data\Post_Type_Support;
use SIW\Elements\Calendar_Icon;
use SIW\Elements\Icon;
use SIW\Elements\Form;
use SIW\Elements\Leaflet_Map;
use SIW\Forms\Forms\Info_Day;
use SIW\Helpers\Template;
use SIW\Integrations\Mailjet;
use SIW\Properties;
use SIW\Structured_Data\Event as Event_Structured_Data;
use SIW\Structured_Data\Event_Attendance_Mode;
use SIW\Structured_Data\Event_Status_Type;
use SIW\Structured_Data\NL_Non_Profit_Type;
use SIW\Structured_Data\Organization;
use SIW\Structured_Data\Place;
use SIW\Structured_Data\Postal_Address;
use SIW\Structured_Data\Thing;
use SIW\Structured_Data\Virtual_Location;

class Event extends Post_Type {

	/** {@inheritDoc} */
	protected static function get_dashicon(): string {
		return 'calendar-alt';
	}

	/** {@inheritDoc} */
	protected static function get_slug(): string {
		return 'evenementen';
	}

	/** {@inheritDoc} */
	protected static function get_singular_label(): string {
		return __( 'Evenement', 'siw' );
	}

	/** {@inheritDoc} */
	protected static function get_plural_label(): string {
		return __( 'Evenementen', 'siw' );
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
		return [];
	}

	/** {@inheritDoc} */
	protected static function get_site_sortables(): array {
		return [
			'deadline' => [
				'meta_key' => 'event_date',
				'default'  => 'ASC',
			],
		];
	}

	/** {@inheritDoc} */
	protected function get_custom_post( \WP_Post|int $post ): Event_Post {
		return new Event_Post( $post );
	}

	/** {@inheritDoc} */
	public static function get_meta_box_fields(): array {
		$meta_box_fields = [
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
				'id'       => 'description',
				'name'     => __( 'Beschrijving', 'siw' ),
				'type'     => 'wysiwyg',
				'required' => true,
			],
			[
				'id'            => 'event_date',
				'name'          => __( 'Datum', 'siw' ),
				'type'          => 'date',
				'required'      => true,
				'admin_columns' => 'after title',
			],
			[
				'id'            => 'start_time',
				'name'          => __( 'Starttijd', 'siw' ),
				'type'          => 'time',
				'required'      => true,
				'admin_columns' => 'after event_date',
			],
			[
				'id'            => 'end_time',
				'name'          => __( 'Eindtijd', 'siw' ),
				'type'          => 'time',
				'required'      => true,
				'admin_columns' => 'after start_time',
			],
			[
				'id'            => 'info_day',
				'name'          => __( 'Infodag van SIW', 'siw' ),
				'type'          => 'switch',
				'on_label'      => __( 'Ja', 'siw' ),
				'off_label'     => __( 'Nee', 'siw' ),
				'admin_columns' => 'after end_time',
			],
			[
				'id'            => 'online',
				'name'          => __( 'Online evenement', 'siw' ),
				'type'          => 'switch',
				'on_label'      => __( 'Ja', 'siw' ),
				'off_label'     => __( 'Nee', 'siw' ),
				'admin_columns' => 'after info_day',
			],
			[
				'id'      => 'location',
				'type'    => 'group',
				'visible' => [ 'online', false ],
				'binding' => false,
				'fields'  => [
					[
						'name' => __( 'Locatie', 'siw' ),
						'type' => 'heading',
					],
					[
						'id'         => 'address_search',
						'name'       => 'Zoeken',
						'type'       => 'text',
						'save_field' => false,
						'size'       => 100,
					],
					[
						'id'       => 'name',
						'name'     => __( 'Naam', 'siw' ),
						'type'     => 'text',
						'size'     => 100,
						'required' => true,
						'binding'  => 'name',
					],
					[
						'id'       => 'street',
						'name'     => __( 'Straat', 'siw' ),
						'type'     => 'text',
						'required' => true,
						'binding'  => 'route',
					],
					[
						'id'       => 'house_number',
						'name'     => __( 'Huisnummer', 'siw' ),
						'type'     => 'text',
						'required' => false,
						'binding'  => 'street_number',
					],
					[
						'id'       => 'postcode',
						'name'     => __( 'Postcode', 'siw' ),
						'type'     => 'text',
						'required' => true,
						'binding'  => 'postal_code',
					],
					[
						'id'       => 'city',
						'name'     => __( 'Plaats', 'siw' ),
						'type'     => 'text',
						'required' => true,
						'binding'  => 'locality',
					],
				],
			],
			[
				'id'      => 'application',
				'type'    => 'group',
				'visible' => [ 'info_day', false ],
				'fields'  => [
					[
						'name' => __( 'Aanmelden', 'siw' ),
						'type' => 'heading',
					],
					[
						'id'   => 'explanation',
						'name' => __( 'Toelichting', 'siw' ),
						'type' => 'wysiwyg',
					],
					[
						'id'        => 'has_link',
						'name'      => __( 'Heeft link', 'siw' ),
						'type'      => 'switch',
						'on_label'  => __( 'Ja', 'siw' ),
						'off_label' => __( 'Nee', 'siw' ),
					],
					[
						'id'       => 'url',
						'name'     => __( 'Url', 'siw' ),
						'type'     => 'url',
						'visible'  => [ 'has_link', true ],
						'required' => false, // op true zetten als deze bug is opgelost https://support.metabox.io/topic/nested-conditional-logic-leads-to-incorrect-validation-of-required-fields/
						'size'     => 100,
						'binding'  => false,
					],
				],
			],
			[
				'name'    => __( 'Organisator', 'siw' ),
				'type'    => 'heading',
				'visible' => [ 'info_day', false ],
			],
			[
				'id'        => 'different_organizer',
				'name'      => __( 'Andere organisator dan SIW', 'siw' ),
				'type'      => 'switch',
				'visible'   => [ 'info_day', false ],
				'on_label'  => __( 'Ja', 'siw' ),
				'off_label' => __( 'Nee', 'siw' ),
			],
			[
				'id'      => 'organizer',
				'type'    => 'group',
				'visible' => [ 'different_organizer', true ],
				'fields'  => [

					[
						'id'       => 'name',
						'name'     => __( 'Naam', 'siw' ),
						'type'     => 'text',
						'required' => true,
						'binding'  => false,
					],
					[
						'id'       => 'url',
						'name'     => __( 'Url', 'siw' ),
						'type'     => 'url',
						'required' => true,
						'size'     => 100,
						'binding'  => false,
					],
				],
			],
			[
				'name'    => __( 'Mailjet', 'siw' ),
				'type'    => 'heading',
				'visible' => [ 'info_day', true ],
			],
			[
				'id'       => 'mailjet_list_id',
				'name'     => __( 'Mailjet lijst id', 'siw' ),
				'type'     => 'number',
				'visible'  => [ 'info_day', true ],
				'readonly' => true,
				'size'     => 10,
			],
		];
		return $meta_box_fields;
	}

	/** {@inheritDoc} */
	protected function get_taxonomies(): array {
		return [];
	}

	/** {@inheritDoc} */
	protected static function get_active_posts_meta_query(): array {
		return [
			'key'     => 'event_date',
			'value'   => gmdate( 'Y-m-d' ),
			'compare' => '>',
		];
	}

	/** {@inheritDoc} */
	protected function generate_slug( array $data, array $postarr ): string {
		return sprintf( '%s %s', $data['post_title'], siw_format_date( $postarr['event_date'] ) );
	}

	public function get_template_variables( string $type, int $post_id ): array {
		$post = new Event_Post( $post_id );

		$template_variables = [
			'event' => $post,
			'icons' => [
				'location' => Icon::create()->set_icon_class( Genericons_Neue::LOCATION )->set_size( 3 )->generate(),
				'online'   => Icon::create()->set_icon_class( Genericons_Neue::CLOUD )->set_size( 3 )->generate(),
				'clock'    => Icon::create()->set_icon_class( Genericons_Neue::TIME )->set_size( 3 )->generate(),
				'calendar' => Icon::create()->set_icon_class( Genericons_Neue::MONTH )->set_size( 3 )->generate(),
			],
		];

		if ( 'single' === $type ) {
			if ( $post->is_info_day() ) {
				$template_variables['application_form_info_day'] = Form::create()
					->set_form_id( Info_Day::FORM_ID )
					->set_field_value( 'info_day_date', $post->get_id() )
					->generate();
			}
			if ( ! $post->is_online() ) {
				$location = sprintf(
					'%s %s, %s %s',
					$post->get_location()['street'],
					$post->get_location()['house_number'],
					$post->get_location()['postcode'],
					$post->get_location()['city']
				);
				$location_map = Leaflet_Map::create()
				->add_location_marker(
					$location,
					$post->get_location()['name'],
					$location
				)
				->set_zoom( 15 );
				$template_variables['map'] = $location_map->generate();
			}
		}
		return $template_variables;
	}

	#[Add_Action( 'save_post_siw_event', PHP_INT_MAX )]
	public function after_save_post( int $post_id, \WP_Post $post, bool $update ) {
		if ( siw_meta( 'info_day', [], $post_id ) && empty( siw_meta( 'mailjet_list_id', [], $post_id ) ) ) {

			$name = 'infodag ' . siw_format_date( siw_meta( 'event_date', [], $post_id ) );

			$mailjet = Mailjet::create();
			$list_id = $mailjet->create_list( $name );
			if ( null !== $list_id ) {
				siw_set_meta( $post_id, 'mailjet_list_id', $list_id );
			}
		}
	}

	/** {@inheritDoc} */
	protected function get_structured_data( int $post_id ): ?Thing {
		$post = new Event_Post( $post_id );
		$structured_data = Event_Structured_Data::create()
			->set_name( $post->get_title() )
			->set_description( $post->get_excerpt() )
			->set_start_date( $post->get_start_datetime() )
			->set_end_date( $post->get_end_datetime() )
			->set_url( $post->get_permalink() );

		// Locatie toevoegen
		if ( $post->is_online() ) {
			$structured_data->set_event_attendance_mode( Event_Attendance_Mode::ONLINE );
			$location = Virtual_Location::create()->set_url( $post->get_permalink() ); // TODO: of externe aanmeldlink
		} else {
			$structured_data->set_event_attendance_mode( Event_Attendance_Mode::OFFLINE );
			$location = Place::create()
				->set_name( $post->get_location()['name'] )
				->set_address(
					Postal_Address::create()
						->set_street_address( $post->get_location()['street'] . ' ' . $post->get_location()['house_number'] )
						->set_address_locality( $post->get_location()['city'] )
						->set_postal_code( $post->get_location()['postcode'] )
						->set_address_country( 'NL' )
				);
		}
		$structured_data->set_location( $location );

		if ( 0 !== $post->get_thumbnail_id() ) {
			$structured_data->set_image( wp_get_attachment_image_url( $post->get_thumbnail_id() ) );
		}

		// Organizer toevoegen
		$organizer = Organization::create();
		if ( $post->has_different_organizer() ) {
			$organizer
			->set_name( $post->get_organizer()['name'] )
			->set_url( $post->get_organizer()['url'] );
		} else {
			$organizer
				->set_name( Properties::NAME )
				->set_url( SIW_SITE_URL )
				->set_same_as( SIW_SITE_URL )
				->set_logo( get_site_icon_url() )
				->set_non_profit_status( NL_Non_Profit_Type::ANBI );
		}
		$structured_data->set_organizer( $organizer );

		// Event status TODO:: meerdere statussen o.b.v. meta event_status
		$structured_data->set_event_status( Event_Status_Type::SCHEDULED );

		return $structured_data;
	}

	#[Add_Action( 'generate_after_entry_header' )]
	public function set_event_info(): void {
		if ( ! $this->is_archive_query() ) {
			return;
		}

		Template::create()
			->set_template( "types/{$this::get_post_type_base()}/archive" )
			->set_context( $this->get_template_variables( 'archive', get_the_ID() ) )
			->render_template();
	}

	#[Add_Action( 'generate_before_content' )]
	public function show_event_date(): void {
		if ( ! $this->is_archive_query() ) {
			return;
		}
		$event = $this->get_custom_post( get_the_ID() );
		Calendar_Icon::create()->set_datetime( $event->get_event_date() )->render();
	}
}
