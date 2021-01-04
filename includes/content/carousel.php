<?php declare(strict_types=1);

namespace SIW\Content;

use SIW\Interfaces\Content\Carousel as Carousel_Interface;

/**
 * TODO:
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.3.0
 */
class Carousel {

	/**
	 * 
	 */
	protected string $post_type;

	protected array $taxonomies;

	protected string $name = 'test';

	/**
	 * Undocumented function
	 *
	 * @param string $post_type
	 * @param string $name
	 * @param array $taxonomies
	 */
	public function __construct( Carousel_Interface $type ) {
		$this->post_type = $type->get_post_type();
		$this->taxonomies = $type->get_taxonomies();
		add_filter( 'siw_carousel_post_types', [ $this, 'add_carousel_post_type'] );
		add_filter( 'siw_carousel_post_type_templates', [ $this, 'add_carousel_post_type_template'] );
		add_filter( 'siw_carousel_post_type_taxonomies', [ $this, 'add_carousel_post_type_taxonomies'] );
	}

	/**
	 * Voegt post type toe aan carousel widget
	 *
	 * @param array $post_types
	 *
	 * @return array
	 */
	public function add_carousel_post_type( array $post_types ) : array {
		$post_types["siw_{$this->post_type}" ] = $this->name;
		return $post_types;
	}

	/**
	 * Voegt taxonomieën toe aan carousel widget
	 *
	 * @param array $post_type_taxonomies
	 *
	 * @return array
	 */
	public function add_carousel_post_type_taxonomies( array $post_type_taxonomies ) : array {
		foreach ( $this->taxonomies as $taxonomy => $settings ) {
			$post_type_taxonomies["siw_{$this->post_type}"]["siw_{$this->post_type}_{$taxonomy}"] = $settings['labels']['name'];
		}
		return $post_type_taxonomies;
	}

	/**
	 * Zet template voor carousel
	 *
	 * @param array $post_type_templates
	 *
	 * @return array
	 */
	public function add_carousel_post_type_template( array $post_type_templates ) : array {
		$post_type_templates["siw_{$this->post_type}"] = locate_template( "content-siw_{$this->post_type}.php" ); //TODO: this->get_template
		return $post_type_templates;
	}
}
