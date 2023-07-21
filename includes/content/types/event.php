<?php declare(strict_types=1);
namespace SIW\Content\Types;

use SIW\Content\Type;
use SIW\Elements\Icon;
use SIW\Util\Links;
use SIW\Elements\Form;
use SIW\Elements\Leaflet_Map;
use SIW\Forms\Forms\Info_Day;
use SIW\Helpers\Template;
use SIW\Integrations\Mailjet;

/**
 * Evenementen
 *
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 */
class Event extends Type {

	/** {@inheritDoc} */
	protected string $post_type = 'event';

	/** {@inheritDoc} */
	protected string $menu_icon = 'dashicons-calendar-alt';

	/** {@inheritDoc} */
	protected string $slug = 'evenementen';

	/** {@inheritDoc} */
	protected string $single_width = 'mobile';

	/** {@inheritDoc} */
	protected string $orderby = 'meta_value';

	/** {@inheritDoc} */
	protected string $orderby_meta_key = 'event_date';

	/** {@inheritDoc} */
	protected string $archive_order = 'ASC';

	/** {@inheritDoc} */
	public function get_meta_box_fields(): array {
		$meta_box_fields = [
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
	protected function get_labels(): array {
		$labels = [
			'name'          => __( 'Evenementen', 'siw' ),
			'singular_name' => __( 'Evenement', 'siw' ),
			'add_new'       => __( 'Nieuw evenement', 'siw' ),
			'add_new_item'  => __( 'Nieuw evenement toevoegen', 'siw' ),
			'edit_item'     => __( 'Evenement bewerken', 'siw' ),
			'all_items'     => __( 'Alle evenementen', 'siw' ),
			'search_items'  => __( 'Evenementen zoeken', 'siw' ),
			'not_found'     => __( 'Geen evenementen gevonden', 'siw' ),
		];
		return $labels;
	}

	/** {@inheritDoc} */
	protected function get_social_share_cta(): string {
		return __( 'Deel dit evenement', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_seo_noindex( int $post_id ): bool {
		return siw_meta( 'event_date', [], $post_id ) < gmdate( 'Y-m-d' );
	}

	/** {@inheritDoc} */
	public function get_active_posts_meta_query(): array {
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

	/** * {@inheritDoc} */
	public function add_single_content() {

		$infoform = '';
		$application_explanation = '';
		$application_link = '';

		if ( siw_meta( 'info_day' ) ) {
			// bij een informatie bijeenkomst een invulformulier tonen
			$infoform = Form::create()->set_form_id( Info_Day::FORM_ID )->set_field_value( 'info_day_date', get_the_ID() )->generate();
		} else {
			// anders  tonen hoe je kunt aanmelden.
			$application = siw_meta( 'application' );
			$application_explanation = wp_kses_post( $application['explanation'] );
			if ( $application['has_link'] ) {
				$application_link = Links::generate_external_link( $application['url'] );
			}
		}

		$template_vars = $this->get_template_vars();
		$template_vars += [
			'infoform'                => $infoform,
			'application_explanation' => $application_explanation,
			'application_link'        => $application_link,
		];

		// locatie op kaart toevoegen
		if ( ! siw_meta( 'online' ) ) {
			$location = siw_meta( 'location' );
			$location_map = Leaflet_Map::create()
			->add_location_marker(
				sprintf( '%s, %s %s %s', $location['street'], $location['house_number'], $location['postcode'], $location['city'] ),
				$location['name'],
				sprintf( '%s, %s %s %s', $location['street'], $location['house_number'], $location['postcode'], $location['city'] )
			)
			->set_zoom( 15 );
			$template_vars['location']['map'] = $location_map->generate();
		}

		Template::create()->set_template( 'types/event_single' )->set_context( $template_vars )->render_template();
	}

	/*** {@inheritDoc}*/
	public function add_archive_content() {
		$template_vars = $this->get_template_vars();
		Template::create()->set_template( 'types/event_archive' )->set_context( $template_vars )->render_template();
	}

	/**
	 * TemplateVars
	 * Maakt een array van variabelen voor de mustache template
	 */
	public function get_template_vars(): array {

		$template_vars = [
			'link'          => Links::generate_button_link( get_permalink(), __( 'Lees meer', 'siw' ) ),
			'abstract'      => siw_meta( 'abstract' ),
			'icons' => [
				'location' => Icon::create()->set_icon_class( 'location' )->generate(),
				'online'   => Icon::create()->set_icon_class( 'admin-site-alt3' )->generate(),
				'clock'    => Icon::create()->set_icon_class( 'clock' )->generate(),
			],
			'event_day'     => wp_date( 'd', strtotime( siw_meta( 'event_date' ) ) ),
			'event_month'   => wp_date( 'F', strtotime( siw_meta( 'event_date' ) ) ),
			'start_time'    => siw_meta( 'start_time' ),
			'end_time'      => siw_meta( 'end_time' ),
			'event_date'    => siw_format_date( siw_meta( 'event_date' ), false ),
			'description'   => siw_meta( 'description' ),
			'infodag'       => siw_meta( 'info_day' ),
			'verlopen'      => siw_meta( 'event_date' ) < gmdate( 'Y-m-d' ),
			'online'        => siw_meta( 'online' ),
			'i18n'          => [
				'online' => __( 'Online', 'siw' ),
			],
		];
		if ( ! siw_meta( 'online' ) ) {
			$location = siw_meta( 'location' );
			$template_vars ['location'] = [
				'name'         => $location['name'],
				'street'       => $location['street'],
				'house_number' => $location['house_number'],
				'postcode'     => $location['postcode'],
				'city'         => $location['city'],
			];
		}
		// Organisator
		if ( siw_meta( 'different_organizer' ) ) {
			$template_vars['organizer'] = [
				'name' => siw_meta( 'organizer.name' ),
				'link' => Links::generate_external_link( siw_meta( 'organizer.url' ) ),
			];
		}
		return $template_vars;
	}

	/** {@inheritDoc} */
	protected function get_archive_intro(): array {
		$intro = siw_get_option( 'event.archive_intro' );
		return [ $intro ];
	}

	/** {@inheritDoc} */
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
}
