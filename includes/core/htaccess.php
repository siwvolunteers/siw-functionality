<?php declare(strict_types=1);

namespace SIW\Core;

/**
 * Class om htaccess regels toe te voegen
 * 
 * @copyright   2019 SIW Internationale Vrijwilligersprojecten
 * @since       3.0.0
 */
class htaccess {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'siw_update_plugin', [ $self, 'regenerate_htaccess' ] );
		add_filter( 'after_rocket_htaccess_rules', [ $self, 'add_rules'] );
	}

	/**
	 * Genereert htaccess opnieuw
	 */
	public function regenerate_htaccess() {
		/* htaccess opnieuw genereren na update plugin */
		if ( ! function_exists( 'flush_rocket_htaccess' ) || ! function_exists( 'rocket_generate_config_file' ) ) {
			return false;
		}
		flush_rocket_htaccess();
		rocket_generate_config_file();
	}

	/**
	 * Voegt regels toe
	 *
	 * @param string $marker
	 * @return string
	 */
	public function add_rules( string $marker ) : string {
		$htaccess_rules = siw_get_data('htaccess');
		foreach ( $htaccess_rules as $rules ) {
			$marker .= $this->format_rules( $rules );
		}
		return $marker;
	}
	
	/**
	 * Formatteert htaccess-regels o.b.v. array
	 *
	 * @param array $rules
	 * @return string
	 */
	protected function format_rules( array $rules ) : string {

		$defaults = [
			'comment' => '',
			'tag'     => '',
			'value'   => '',
			'lines'   => [],
		];
		$rules = wp_parse_args( $rules, $defaults );

		foreach ( $rules['lines'] as $index => $line ) {
			if ( ! is_array( $line ) && ! empty( $rules['tag'] ) ) { 
				$rules['lines'][$index] = "\t" . $rules['lines'][$index];
			}
			elseif ( is_array( $line ) ) {
				$rules['lines'][ $index ] = $this->format_rules( $line );
			}
		}

		$formatted_rules = implode( PHP_EOL, $rules['lines'] );
		if ( ! empty( $rules['tag'] ) ) {
			$tag_open = sprintf( '<%s %s>', $rules['tag'], $rules['value'] );
			$tag_close = sprintf( '</%s>', $rules['tag'] );
			$formatted_rules = $tag_open . PHP_EOL . $formatted_rules . PHP_EOL . $tag_close;
		}
		if ( ! empty( $rules['comment'] ) ) {
			$comment_open = sprintf( '# START %s', $rules['comment'] );
			$comment_close = sprintf( '# END %s', $rules['comment'] ); 
			$formatted_rules = $comment_open . PHP_EOL . $formatted_rules . PHP_EOL . $comment_close;
		}
		$formatted_rules .= PHP_EOL;

		return $formatted_rules;
	}
}
