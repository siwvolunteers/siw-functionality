<?php
namespace SIW\Content\Types;

use SIW\Content\Type;
use SIW\Elements;
use SIW\Elements\Google_Maps;
use SIW\Formatting;
use SIW\HTML;

/**
 * Evenementen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.?
 */
class Event extends Type {

	/**
	 * {@inheritDoc}
	 */
	protected $post_type = 'event';

	/**
	 * {@inheritDoc}
	 */
	protected $menu_icon = 'dashicons-calendar-alt';

	/**
	 * {@inheritDoc}
	 */
	protected $slug = 'evenementen';

	/**
	 * {@inheritDoc}
	 */
	protected $single_width = 'mobile';

	/**
	 * {@inheritDoc}
	 */
	protected $archive_orderby = 'meta_value';

	/**
	 * {@inheritDoc}
	 */
	protected $archive_meta_key = 'event_date';

	/**
	 * {@inheritDoc}
	 */
	public function get_meta_box_fields() {
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
						'required' => true,
					],
					[
						'id'       => 'street',
						'name'     => __( 'Adres', 'siw' ),
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
					],
					[
						'id'       => 'url',
						'name'     => __( 'Url', 'siw' ),
						'type'     => 'url',
						'required' => true,
						'size'     => 100,
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
						'id'       => 'link_url',
						'name'     => __( 'Link om aan te melden', 'siw' ),
						'type'     => 'url',
						'size'     => 100,
					],
					[
						'id'       => 'link_text',
						'name'     => __( 'Tekst voor link', 'siw' ),
						'type'     => 'text',
						'size'     => 100,
					],
				],
			],
		];
		return $meta_box_fields;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_taxonomies() {
		return [];
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_labels() {
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
	protected function get_social_share_cta() {
		return __( 'Deel dit evenement', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_seo_noindex( int $post_id ) {
		return siw_meta( 'date', [], $post_id ) < date( 'Y-m-d' );
	}

	/**
	 * {@inheritDoc}
	 */
	function get_archive_meta_query() : array {
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
		$date = $postarr['event_date'];
		$slug = sanitize_title( sprintf( '%s %s', $data['post_title'], Formatting::format_date( $date ) ) );
		return wp_unique_post_slug( $slug, $postarr['ID'], $data['post_status'], $data['post_type'], $data['post_parent'] );
	}
	
	/**
	 * {@inheritDoc}
	 * 
	 * @todo refactor enzo
	 */
	public function add_single_content() {

		echo '<h4>';
		//Tijd
		printf(
			'%s %s %s-%s',
			Elements::generate_icon( 'siw-icon-clock' ),
			Formatting::format_date( siw_meta( 'event_date' ), false),
			siw_meta( 'start_time'),
			siw_meta( 'end_time' )
		);
		echo '</h4>';
		echo '<h4>';
		if ( siw_meta('online') ) {
			$online_location = siw_meta( 'online_location' );

			printf(
				'%s %s %s',
				Elements::generate_icon( 'siw-icon-globe'),
				$online_location['name'],
				HTML::generate_external_link( $online_location['url'] )
			);
		}
		else {
		//Locatie
			$location = siw_meta( 'location' );
			printf(
				'%s %s, %s, %s %s',
				Elements::generate_icon( 'siw-icon-map-marker-alt'),
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
			$default_date = sanitize_title( Formatting::format_date( siw_meta('event_date' ), false ) );
			echo do_shortcode( sprintf( '[caldera_form id="infodag" datum="%s"]', $default_date) );
		}
		else {
			//TODO: aanmelden
		}

		//Locatie kaart
		if ( ! siw_meta( 'online') ) {
			$location = siw_meta( 'location' );

			$location_map = new Google_Maps;
			$location_map->add_location_marker(
				sprintf( '%s, %s %s', $location['street'], $location['postcode'], $location['city'] ),
				$location['name'],
				sprintf( '%s, %s %s', $location['street'], $location['postcode'], $location['city'] )
			);
			$location_map->set_options(['zoom' => 15 ]);

			echo '<h2>' . esc_html__( 'Locatie', 'siw') . '</h2>';
			$location_map->render();
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function add_archive_content() {
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
							Elements::generate_icon( 'siw-icon-clock' ),
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
						Elements::generate_icon( 'siw-icon-globe'),
						$online_location['name'],
						HTML::generate_external_link( $online_location['url'] )
					);
				}
				else {
				//Locatie
					$location = siw_meta( 'location' );
					printf(
						'%s %s, %s, %s %s',
						Elements::generate_icon( 'siw-icon-map-marker-alt'),
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
			<?php echo HTML::generate_link( get_permalink() , __( 'Lees meer', 'siw' ), [ 'class' => 'button ghost'] );?>
		</div>

		<?php
	}

}
