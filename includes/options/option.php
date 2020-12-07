<?php declare(strict_types=1);

namespace SIW\Options;

/**
 * Class om opties toe te voegen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.2.0
 */
abstract class Option {

	/**
	 * Prefix voor paginaslug
	 * 
	 * @var string
	 */
	const PAGE_PREFIX = 'siw-';

	//protected $option_name = 'siw_options';

	/**
	 * ID van optie
	 */
	protected string $id = 'settings';

	/**
	 * Titel van optie
	 */
	protected string $title;

	/**
	 * Capability voor optie
	 */
	protected string $capability = 'manage_options';

	/**
	 * Parent pagina van optie
	 */
	protected string $parent_page = 'options-general.php';

	/**
	 * Init
	 */
	public static function init() {
		$self = new static();
		$self->title = $self->get_title();

		add_filter( 'rwmb_meta_boxes', [ $self, 'add_settings_meta_boxes'] );
		add_filter( 'siw_option_value', [ $self, 'format_option_value' ], 10, 2 );
		add_filter( 'mb_settings_pages', [ $self, 'add_settings_page'] ); //TODO: alleen in admin?
	}

	/**
	 * Voegt admin-pagina toe
	 *
	 * @param array $settings_pages
	 *
	 * @return array
	 */
	public function add_settings_page( array $settings_pages ) : array {
		$tabs = apply_filters( "siw_option_{$this->id}_tabs", $this->get_tabs() );
		$settings_pages[] = [
			'option_name'   => 'siw_options',
			'id'            => "siw-{$this->id}",
			'menu_title'    => "SIW - {$this->title}",
			'capability'    => $this->capability,
			'tabs'          => array_column( $tabs , null, 'id' ),
			'submit_button' => __( 'Opslaan', 'siw' ),
			'message'       => __( 'Instellingen opgeslagen', 'siw' ),
			'columns'       => 1,
			'tab_style'     => 'left',
			'parent'        => $this->parent_page,
		];

		return $settings_pages;
	}
	
	/**
	 * Voegt metaboxes toe
	 *
	 * @param array $meta_boxes
	 *
	 * @return array
	 * 
	 * @todo validatie van veld naar metabox verplaatsen
	 * @todo filter voor extensies
	 */
	public function add_settings_meta_boxes( array $meta_boxes ) : array {
		
		$tabs = apply_filters( "siw_option_{$this->id}_tabs", $this->get_tabs() );
		$fields = apply_filters( "siw_option_{$this->id}_fields", $this->get_fields() );

		foreach ( $tabs as $tab ) { 
			$meta_boxes[] = [
				'id'             => "{$this->id}_{$tab['id']}",
				'title'          => $tab['label'],
				'settings_pages' => "siw-{$this->id}",
				'tab'            => $tab['id'],
				'fields'         => wp_list_filter( $fields, [ 'tab' => $tab['id'] ] ),
				'toggle_type'    => 'slide',
			];
		}
		return $meta_boxes;
	}
	
	/**
	 * Title van de optie
	 *
	 * @return string
	 */
	abstract protected function get_title() : string;

	/**
	 * Haal tabs op
	 *
	 * @return array
	 */
	abstract protected function get_tabs() : array;

	/**
	 * Haal velden op
	 *
	 * @return array
	 */
	abstract protected function get_fields() : array;

	/**
	 * Undocumented function
	 *
	 * @param mixed $value
	 * @param string $option
	 * 
	 * @return mixed
	 */
	public function format_option_value( $value, string $option ) {
		return $value;
	}
}
