<?php declare(strict_types=1);

namespace SIW\Blocks;

use SIW\Interfaces\Blocks\Block as Block_Interface;
use SIW\Helpers\Template;

/**
 * Class om een Blockulier via MetaBox te genereren
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Block {

	/** Block */
	protected Block_Interface $block;
	
	/** Constructor */
	public function __construct( Block_Interface $block ) {
		$this->block = $block;
	}

	/** Registreer Block */
	public function register() {
		add_filter( 'rwmb_meta_boxes', [ $this, 'add_meta_box' ] );
	}

	/** Voegt metabox toe */
	public function add_meta_box( array $meta_boxes ): array {
		$meta_boxes[] = [
			'id'              => $this->block->get_id(),
			'title'           => $this->block->get_name(),
			'description'     => $this->block->get_description(),
			'type'            => 'block',
			'icon'            => $this->block->get_icon(),
			'category'        => 'siw',
			#'context'         => 'side',
			'fields'          => $this->block->get_fields(),
			'render_callback' => [$this , 'render_block'],
		];
		return $meta_boxes;
	}

	/** Render block */
	public function render_block( array $attributes, bool $is_preview = false, int $post_id = null ) {
	
		if ( empty( $attributes['data'] ) ) {
			return;
		}
		$template_vars = $this->block->get_template_vars( $attributes );
		$template = $this->block->get_template();
		Template::create()->set_template( 'blocks/' . $template )->set_context( $template_vars )->render_template();
	}
}
