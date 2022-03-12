<?php declare(strict_types=1);

namespace SIW\Blocks;

use SIW\Interfaces\Blocks\Block as Block_Interface;
use SIW\Util\Meta_Box;
use SIW\Helpers\Template;

/**
 * Class om een Blockulier via MetaBox te genereren
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Block {

	/** API versie */
	const API_VERSION = 'v1';

	/** Blockulier */
	protected Block_Interface $block;
	
	/** Constructor */
	public function __construct( Block_Interface $block ) {
		$this->block = $block;
	}

	/** Registreer Blockulier */
	public function register() {
        #echo "startblock";
        #$x = $this->testtemplate();
        #echo $x;
		add_filter( 'block_categories_all', [ $this, 'gwg_block_categories'] );
		add_filter( 'rwmb_meta_boxes', [ $this, 'add_meta_box' ] );
	}
    /** Voegt metabox toe */
	public function add_meta_box( array $meta_boxes ): array {
            $meta_boxes[] = [
                'id'        => "{$this->block->get_id()}",
                'title'     => "{$this->block->get_name()}",
                'type'      => 'block',
                'icon'      => 'awards',
                'category'  => 'siw', #category, which is used to help users browse and discover them
                #'context'   => 'side', #Where to show the block settings.
                'fields'    => $this->block->get_fields(),
                'render_callback' => [$this , 'BlockCallback'], #Specify a custom PHP callback to display the block.
            ];
		return $meta_boxes;
	}
     /**
     * Add a block category for "Get With Gutenberg" if it doesn't exist already.
     *
     * @param array $categories Array of block categories.
     *
     * @return array
     */
     public function gwg_block_categories( $categories ) {
        $category_slugs = wp_list_pluck( $categories, 'slug' );
        return in_array( 'siw', $category_slugs, true ) ? $categories : array_merge(
            $categories,
            array(
                array(
                    'slug'  => 'siw',
                    'title' => __( 'SIW blocks', 'siw' ),
                    'icon'  => null,
                ),
            )
        );
    }
    public function BlockCallback( $attributes, $is_preview = false, $post_id = null ) {
       
        // Fields data.
        if ( empty( $attributes['data'] ) ) 
        {
            return;
        }
        $vars = $this->block->get_template_vars($attributes);
        $template = $this->block->get_template();
        Template::create()->set_template( 'blocks/' . $template )->set_context( $vars )->render_template();
    }
}