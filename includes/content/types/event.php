<?php declare(strict_types=1);
namespace SIW\Content\Types;

use SIW\Content\Type;
use SIW\Elements\Google_Maps;
use SIW\Elements\Icon;
use SIW\Util\Links;
use SIW\Core\Template;

/**
 * Evenementen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.0
 */
class Event extends Type {

	/**
	 * {@inheritDoc}
	 */
	protected string $post_type = 'event';

	/**
	 * {@inheritDoc}
	 */
	protected string $menu_icon = 'dashicons-calendar-alt';

	/**
	 * {@inheritDoc}
	 */
	protected string $slug = 'evenementen';

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
	protected string $orderby_meta_key = 'event_date';

	/**
	 * {@inheritDoc}
	 */
	protected string $archive_order = 'ASC';

	/**
	 * {@inheritDoc}
	 */
	public function get_meta_box_fields() : array {
		$meta_box_fields = [
			[
				'type' => 'heading',
				'name' => __( 'Gegevens', 'siw' ),
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
				'name'          => __( 'Infodag', 'siw' ),
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
				'id'        => 'location',
				'type'      => 'group',
				'visible'   => [ 'online', false ],
				'fields'    => [
					[
						'name'     => __( 'Locatie', 'siw' ),
						'type'     => 'heading',
					],
					[
						'id'       => 'address_search',
						'name'     => 'Zoeken',
						'type'     => 'text',
						'size'     => 100,
					],
					[
						'id'       => 'name',
						'name'     => __( 'Naam', 'siw' ),
						'type'     => 'text',
						'size'     => 100,
						'required' => true,
					],
					[
						'id'       => 'street',
						'name'     => __( 'Straat', 'siw' ),
						'type'     => 'text',
						'required' => true,
						'binding'  => 'route'
					],
					[
						'id'       => 'house_number',
						'name'     => __( 'Huisnummer', 'siw' ),
						'type'     => 'text',
						'required' => false,
						'binding'  => 'street_number'
					],
					[
						'id'       => 'postcode',
						'name'     => __( 'Postcode', 'siw' ),
						'type'     => 'text',
						'required' => true,
						'binding'  => 'postal_code'
					],
					[
						'id'       => 'city',
						'name'     => __( 'Plaats', 'siw' ),
						'type'     => 'text',
						'required' => true,
						'binding'  => 'locality'
					]
				],
			],
			[
				'id'        => 'online_location',
				'type'      => 'group',
				'visible'   => [ 'online', true ],
				'fields'    => [
					[
						'name'     => __( 'Online locatie', 'siw' ),
						'type'     => 'heading',
					],
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
				'id'        => 'application',
				'type'      => 'group',
				'visible'   => [ 'info_day', false ],
				'fields'    => [
					[
						'name'     => __( 'Aanmelden', 'siw' ),
						'type'     => 'heading',
					],
					[
						'id'       => 'explanation',
						'name'     => __( 'Toelichting', 'siw' ),
						'type'     => 'wysiwyg',
					],
					[
						'id'        => 'has_link',
						'name'      => __( 'Heeft link', 'siw' ),
						'type'      => 'switch',
						'on_label'  => __( 'Ja', 'siw' ),
						'off_label' => __( 'Nee', 'siw' ),
					],
					[
						'id'        => 'url',
						'name'      => __( 'Url', 'siw' ),
						'type'      => 'url',
						'visible'   => [ 'has_link', true ],
						'required'  => true,
						'size'      => 100,
						'binding'   => false,
					],
				],
			],
			[
				'name'     => __( 'Organisator', 'siw' ),
				'type'     => 'heading',
				'visible'   => [ 'info_day', false ],
			],
			[
				'id'        => 'different_organizer',
				'name'      => __( 'Andere organisator', 'siw' ),
				'type'      => 'switch',
				'visible'   => [ 'info_day', false ],
				'on_label'  => __( 'Ja', 'siw' ),
				'off_label' => __( 'Nee', 'siw' ),
			],

			[
				'id'        => 'organizer',
				'type'      => 'group',
				'visible'   => [ 'different_organizer', true ],
				'fields'    => [

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
		];
		return $meta_box_fields;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_taxonomies() : array {
		return [];
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_labels() : array {
		$labels = [
			'name'               => __( 'Evenementen', 'siw' ),
			'singular_name'      => __( 'Evenement', 'siw' ),
			'add_new'            => __( 'Nieuw evenement', 'siw' ),
			'add_new_item'       => __( 'Nieuw evenement toevoegen', 'siw' ),
			'edit_item'          => __( 'Evenement bewerken', 'siw' ),
			'all_items'          => __( 'Alle evenementen', 'siw' ),
			'search_items'       => __( 'Evenementen zoeken', 'siw' ),
			'not_found'          => __( 'Geen evenementen gevonden', 'siw' ),
		];
		return $labels;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_social_share_cta() : string {
		return __( 'Deel dit evenement', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_seo_noindex( int $post_id ) : bool {
		return siw_meta( 'event_date', [], $post_id ) < date( 'Y-m-d' );
	}

	/**
	 * {@inheritDoc}
	 */
	function get_active_posts_meta_query() : array {
		return [
			'key'     => 'event_date',
			'value'   => date('Y-m-d'),
			'compare' => '>'
		];
	}

	/**
	 * {@inheritDoc}
	 */
	protected function generate_slug( array $data, array $postarr ) : string {
		return sprintf( '%s %s', $data['post_title'], siw_format_date( $postarr['event_date'] ) );
	}
	
	/**
	 * {@inheritDoc}
	 * 
	 * @todo refactor enzo
	 */
	public function add_single_content() {
		// bij een informatie bijeenkomst een invulformulier tonen
		$infoform=$application_explanation=$application_link='';
		if ( siw_meta( 'info_day' ) ) {
			$default_date = sanitize_title( siw_format_date( siw_meta('event_date' ), false ) );
			$infoform=do_shortcode( sprintf( '[caldera_form id="infodag" datum="%s"]', $default_date) );
		}
		// anders  tonen hoe je kunt aanmelden.
		else
		{
			$application = siw_meta( 'application' );
			$application_explanation = wp_kses_post( $application['explanation'] );
			if ( $application['has_link'] ) {
				$application_link = Links::generate_external_link( $application['url'] );
			}
		}
		$template_vars = $this->TemplateVars();
		$template_vars += array(
			"infoform" => $infoform,
			"application_explanation" => $application_explanation,
			"application_link" => $application_link,
		);
		echo Template::parse_template( "types/event_single", $template_vars );
	}
	/**
	 * {@inheritDoc}
	 */
	public function add_archive_content() {
		
		echo Template::parse_template( "types/event_archive", $this->TemplateVars() );
	}
	/**
	 * TemplateVars
	 * Maakt een array van variabelen voor de mustache template
	 */
	public function TemplateVars() : array {
		
		$template_vars = array(
			"format" => function($text) {
				#return wpautop( wp_kses_post( $text)); # Dit werkt niet bij $text = {{{text}}} wel bij {{text}}, maar dan is output met html tags
				return($text); # voorlopig maar even zo.
			  },
			'link' => Links::generate_button_link( get_permalink() , __( 'Lees meer', 'siw' ) ),
			'excerpt' => apply_filters( 'the_excerpt', get_the_excerpt() ),			// samenvatting todo:in metadata opnemen
			"icon_map-marker-alt" => Icon::create()->set_icon_class( 'siw-icon-map-marker-alt' )->generate(),
			"icon_globe" => Icon::create()->set_icon_class( 'siw-icon-globe' )->generate(),
			"icon_clock" => Icon::create()->set_icon_class( 'siw-icon-clock' )->generate(),
			"event_day" => wp_date( 'd', strtotime( siw_meta( 'event_date' ) ) ),
			"event_month" => wp_date( 'F', strtotime( siw_meta( 'event_date' ) ) ),
			"start_time" => siw_meta( 'start_time'),
			"end_time" => siw_meta( 'end_time'),
			"event_date" => siw_meta( 'event_date' ),
			"description" => siw_meta('description'),
			"infodag" => siw_meta( 'info_day' ),
			"verlopen"	=> siw_meta( 'event_date' ) < date( 'Y-m-d' ),
		);
		// online evenement
		if(siw_meta('online')) {
			$online_location = siw_meta( 'online_location' );
			$template_vars += array(
				"online"	=> TRUE,
				"location_name" => $online_location['name'],
				"location_link" => Links::generate_external_link( $online_location['url']),
			);
		}
		// evenement op locatie
		else {
			//Locatie gegevens
				$location = siw_meta( 'location' );
				// locatie op kaart
				$location_map = Google_Maps::create()
				->add_location_marker(
					sprintf( '%s, %s %s', $location['street'], $location['postcode'], $location['city'] ),
					$location['name'],
					sprintf( '%s, %s %s', $location['street'], $location['postcode'], $location['city'] )
				)
				->set_zoom( 15 );
				$template_vars += array(
					"location"	=> TRUE,
					"location_name" => $location['name'],
					"location_street" => $location['street'],
					"location_postcode" => $location['postcode'],
					"location_city" => $location['city'],
					"location_map" => $location_map->generate(),
				);
		}
		//Organisator
		if(siw_meta( 'different_organizer'))
		{
			$template_vars += array(
				"organizer"		=> TRUE,
				"organizer_name" => siw_meta('organizer_name'),
				"organizer_link" => Links::generate_external_link( siw_meta( 'organizer.url' )),
			);
		}
		return($template_vars);
	}
	/*
		public function add_single_content_old() {

		echo '<h4>';
		//Tijd
		printf(
			'%s %s %s-%s',
			Icon::create()->set_icon_class( 'siw-icon-clock' )->generate(),
			siw_format_date( siw_meta( 'event_date' ), false),
			siw_meta( 'start_time'),
			siw_meta( 'end_time' )
		);
		echo '</h4>';
		echo '<h4>';
		if ( siw_meta('online') ) {
			$online_location = siw_meta( 'online_location' );

			printf(
				'%s %s %s',
				Icon::create()->set_icon_class( 'siw-icon-globe' )->generate(),
				$online_location['name'],
				Links::generate_external_link( $online_location['url'] )
			);
		}
		else {
		//Locatie
			$location = siw_meta( 'location' );
			printf(
				'%s %s, %s, %s %s',
				Icon::create()->set_icon_class( 'siw-icon-map-marker-alt' )->generate(),
				$location['name'],
				$location['street'],
				$location['postcode'],
				$location['city']
			);
		}

		echo '</h4>';
		echo '<hr>';

		//Inleiding
		echo wpautop( wp_kses_post( siw_meta( 'description') ) );

		//Aanmelden
		echo '<h2>' . esc_html__( 'Aanmelden', 'siw') . '</h2>';
		if ( siw_meta( 'event_date' ) < date( 'Y-m-d' ) ) {
			esc_html_e( 'Dit evenement is helaas al afgelopen.', 'siw' );
		}

		elseif ( siw_meta( 'info_day' ) ) {
			$default_date = sanitize_title( siw_format_date( siw_meta('event_date' ), false ) );
			echo do_shortcode( sprintf( '[caldera_form id="infodag" datum="%s"]', $default_date) );
		}
		else {
			$application = siw_meta( 'application' );
			echo wp_kses_post( $application['explanation'] );
			if ( $application['has_link'] ) {
				echo Links::generate_external_link( $application['url'] );
			}
		}

		//Locatie kaart
		if ( ! siw_meta( 'online') ) {
			$location = siw_meta( 'location' );

			$location_map = Google_Maps::create()
				->add_location_marker(
					sprintf( '%s, %s %s', $location['street'], $location['postcode'], $location['city'] ),
					$location['name'],
					sprintf( '%s, %s %s', $location['street'], $location['postcode'], $location['city'] )
				)
				->set_zoom( 15 );

			echo '<h2>' . esc_html__( 'Locatie', 'siw') . '</h2>';
			echo '<p>' . $location_map->generate() . '</p>';
		}

		//Organisator
		if ( ! siw_meta( 'info_day' ) && siw_meta( 'different_organizer') ) {
			echo '<h2>' . esc_html__( 'Organisator', 'siw') . '</h2>';
			echo sprintf(
				__( 'Dit evenement wordt georganiseerd door %s (%s).'),
				esc_html( siw_meta('organizer.name') ),
				Links::generate_external_link( siw_meta( 'organizer.url' ) )
			);
		}
	}

	public function add_archive_content_old() {
		$event_date = siw_meta( 'event_date' );
		?>
		<div class="grid-20">
			<span class="event-date">
				<span class="day">
					<?php echo wp_date( 'd', strtotime( $event_date ) ); ?>
				</span>
				<br>
				<span class="month">
					<?php echo wp_date( 'F', strtotime( $event_date ) ); ?>
				</span>
				<br>
				<span class="time">
					<?php
						printf(
							'%s %s-%s',
							Icon::create()->set_icon_class( 'siw-icon-clock' )->generate(),
							siw_meta( 'start_time'),
							siw_meta( 'end_time' )
						);
					?>
				</span>
			</span>
		</div>
		<div class="grid-60">
			<span class="event-location">
			<?php
				if ( siw_meta('online') ) {
					$online_location = siw_meta( 'online_location' );
		
					printf(
						'%s %s %s',
						Icon::create()->set_icon_class( 'siw-icon-globe' )->generate(),
						$online_location['name'],
						Links::generate_external_link( $online_location['url'] )
					);
				}
				else {
				//Locatie
					$location = siw_meta( 'location' );
					printf(
						'%s %s, %s, %s %s',
						Icon::create()->set_icon_class( 'siw-icon-map-marker-alt' )->generate(),
						$location['name'],
						$location['street'],
						$location['postcode'],
						$location['city']
					);
				}
				echo '</span><br><br>';
				the_excerpt();
			?>
		</div>
		<div class="grid-20">
			<?php echo Links::generate_button_link( get_permalink() , __( 'Lees meer', 'siw' ) );?>
		</div>

		<?php
	}
	*/

}
