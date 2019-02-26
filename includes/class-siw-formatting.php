<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hulpfuncties t.b.v. formattering
 *
 * @package   SIW
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Formatting {

	/**
	 * Formatteert getal als percentage
	 * @param  float  $percentage
	 * @param  integer $decimals
	 * @return string
	 */
	public static function format_percentage( $percentage, $decimals = 0 ) {
		$percentage = number_format( $percentage, $decimals );
		return sprintf( '%s&nbsp;&percnt;', $percentage );
	}

	/**
	 * Formatteert getal als bedrag
	 *
	 * @param float $amount
	 * @param integer $decimals
	 * @param string $currency
	 * @return string
	 * 
	 * @uses siw_get_currency()
	 */
	public static function format_amount( $amount, $decimals = 0, $currency_code = 'EUR' ) {
		$currency = siw_get_currency( $currency_code );
		$currency_symbol = $currency->get_symbol();
		$amount = number_format( $amount, $decimals, ',', '.' );
		return sprintf( '%s&nbsp;%s', $currency_symbol, $amount );
	}

	/**
	 * Genereert een `<ol>` of `<ul>` lijst van array
	 * @param array $items
	 * @param bool $ordered
	 *
	 * @return string
	 */
	public static function generate_list( $items, $ordered = false ) {
		if ( empty ( $items ) ) {
			return false;
		}
		$tag = $ordered ? 'ol' : 'ul';

		$list = "<{$tag}>";
		foreach ( $items as $item ) {
			$list .= '<li>' . (string) $item . '</li>'; //TODO: escaping
		}
		$list .= "</{$tag}>";

		return $list;
	}

	/**
	 * Genereert attributes op basis van array
	 *
	 * @param array $attributes
	 * @return string
	 */
	public static function render_attributes( $attributes ) {
		$rendered_attributes = '';
		foreach ( $attributes as $key => $value ) {
			if ( false == $value )
				continue;
			if ( is_array( $value ) ) {
				$value = json_encode( $value );
			}
			if ( 'class' == $key ) {
				$value = self::sanitize_html_classes( $value );
			}
			$rendered_attributes .= sprintf( true === $value ? ' %s' : ' %s="%s"', $key, esc_attr( $value ) );
		}
		return $rendered_attributes;
	}

	/**
	 * Genereert form field
	 *
	 * @param string $type
	 * @param array $input_args
	 * @param array $wrapper_args
	 * @return string
	 */
	public static function generate_field( $type, $input_args, $wrapper_args = [] ) {

		$input_args = wp_parse_args( $input_args, [
			'id'          => '',
			'name'        => '',
			'value'       => '',
			'options'     => [],
			'placeholder' => '',
			'pattern'     => '',
			'class'       => '',
			'disabled'    => false,
			'required'    => false,
			'attributes'  => [],
			'options'     => [],
		]);

		$attributes = wp_parse_args( $input_args['attributes'], [
			'disabled'    => $input_args['disabled'],
			'required'    => $input_args['required'],
			'class'       => $input_args['class'],
			'id'          => $input_args['id'],
			'name'        => $input_args['name'],
			'value'       => $input_args['value'],
			'placeholder' => $input_args['placeholder'],
		]);

		$field = '';

		if ( ! empty( $input_args['label'] ) ) {
			$field .= sprintf( '<label for="%s">%s</label>', esc_attr( $input_args['id'] ), esc_html( $input_args['label'] ) );
		}

		switch ( $type ) {
			case 'text':
			case 'tel':
			case 'email':
			case 'url':
			case 'number':
			case 'hidden':
			case 'date':
			case 'submit':
				$field .= sprintf('<input type="%s" %s>', esc_attr( $type ), self::render_attributes( $attributes ) );
				break;
			case 'radio':
				$options = $input_args['options'];
				$value = $attributes['value'];
				if ( ! empty( $options ) && is_array( $options ) ) {
					foreach ( $options as $key => $option ) {
						$checked = checked( $value, $key, false );
						$field .= sprintf( '<label><input type="radio" value="%s" %s %s>%s</label>', esc_attr( $key ), self::render_attributes( $attributes ), $checked, esc_html( $option ) );
					}
				}
				break;

			case 'select':
				$options = $input_args['options'];
	
				$field .= sprintf( '<select %s>', self::render_attributes( $attributes ) );
				if ( ! empty( $options ) && is_array( $options ) ) {
					foreach ( $options as $key => $option ) {
						$selected = selected( $attributes['value'], $key, false );
						$field .= sprintf('<option value="%s" %s>%s</option>', esc_attr( $key ), $selected, esc_html( $option ) );
					}
				}
				$field .= '</select>';
				break;

			case 'checkbox':
				$value = $attributes['value'];
				unset( $attributes['value'] );
				$field .= sprintf( '<label for="%s">', esc_attr( $attributes['id'] ) );
				$field .= sprintf( '<input type="checkbox" %s %s/>', self::render_attributes( $attributes ), checked( 1, $value, false ) );
				$field .= sprintf( '%s</label>', esc_html( $input_args['label'] ) );
				break;

			case 'textarea':
				$value = $attributes['value'];
				unset( $attributes['value'] );
				$field .= sprintf( '<textarea %s>%s</textarea>', self::render_attributes( $attributes ), $value );
				break;

			default:
				return false;
		}

		/* Wrapper toevoegen */
		$wrapper_args = wp_parse_args( $wrapper_args, [
			'tag'   => '',
			'class' => [],
			]
		);

		if ( ! empty( $wrapper_args['tag'] ) ) {
			$wrapper_class = '';
			if ( ! empty( $wrapper_args['class'] ) ) {
				$wrapper_class = sprintf( 'class="%s"', self::sanitize_html_classes( $wrapper_args['class'] ) );
			}

			$wrapper_open = sprintf( '<%s %s>', tag_escape( $wrapper_args['tag'] ), $wrapper_class );
			$wrapper_close = sprintf( '</%s>', tag_escape( $wrapper_args['tag'] ) );

			$field = $wrapper_open . $field . $wrapper_close;
		}

		return $field;
	}

	/**
	 * Genereert link
	 *
	 * @todo attributes, target en rel
	 *
	 * @param string $url
	 * @param string $text
	 * @param array $attributes
	 * @return string
	 */
	public static function generate_link( $url, $text = false, $attributes = [], $icon_class = false ) {

		if ( false == $text ) {
			$text = $url;
		}
		$icon_html = ( $icon_class) ? sprintf( '<i class="%s"></i>', self::sanitize_html_classes( $icon_class ) ) : '';

		$link = sprintf(
			'<a href="%s" %s>%s</a>',
			esc_url( $url ),
			self::render_attributes( $attributes ),
			wp_kses_post( $text . $icon_html )
		);
		return $link;
	}

	/**
	 * Genereert html-tag
	 *
	 * @param string $tag
	 * @param array $attributes
	 * @return string
	 */
	public static function generate_tag( $tag, $attributes ) {
		$tag = sprintf(
			'<%s %s>',
			tag_escape( $tag ),
			self::render_attributes ( $attributes )
		);
		return $tag;
	}

	/**
	 * Genereert externe link
	 *
	 * @param  string $url
	 * @param  string $text
	 * @return string
	 */
	public static function generate_external_link( $url, $text = false ) {
		return self::generate_link( $url, $text . '&nbsp;', [ 'class' => 'siw-external-link', 'target' => '_blank', 'rel' => 'noopener'], 'kt-icon-newtab' );
	}

	/**
	 * Genereer pinnacle accordion
	 * @param  array $panes
	 * @return string
	 */
	public static function generate_accordion( $panes ) {
		if ( empty( $panes) ) {
			return;
		}
		$accordion = '[accordion]';
		foreach ( $panes as $pane ) {
			if ( empty( trim( $pane['content'] ) ) ) {
				continue;
			}
			if ( isset( $pane['show_button'] ) && true == $pane['show_button'] ) {
				$pane['content'] .= wpautop( self::generate_link( $pane['button_url'], $pane['button_text'], [ 'class' => 'kad-btn' ] ) );
			}
			$accordion .= sprintf( '[pane title="%s"]%s[/pane]', esc_html( $pane['title'] ), wp_kses_post( wpautop( $pane['content'] )  ) );
		}
		$accordion .= '[/accordion]';
		return $accordion;
	}

	/**
	 * Parset template o.b.v. variabelen
	 *
	 * @param string $template
	 * @param array $vars
	 * @return string
	 */
	public static function parse_template( $template, $vars ) {
		$variables = [];
		foreach ( $vars as $key => $value ) {
			$variables[ '{{ ' . $key . ' }}' ] = $value;
		}
		return strtr( $template, $variables );
	}

	/**
	 * Genereert script-tag met JSON-LD op basis van array
	 *
	 * @param array $data
	 * @return string
	 */
	public static function generate_json_ld( $data ) {
		ob_start();
		?>
		<script type="application/ld+json">
		<?php echo json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);?>
		</script>
		<?php
		$json_ld = ob_get_clean();
		return $json_ld;
	}

	/**
	* sanitize_html_class voor meerdere classes
	*
	* @uses   sanitize_html_class()
	* @param  mixed $class
	* @param  string $fallback
	* @return string
	*/
	protected static function sanitize_html_classes( $class, $fallback = null ) {
		if ( is_string( $class ) ) {
			$class = explode( ' ', $class );
		}
		if ( is_array( $class ) && count( $class ) > 0 ) {
			$class = array_map( 'sanitize_html_class', $class );
			return implode( ' ', $class );
		}
		else {
			return sanitize_html_class( $class, $fallback );
		}
	}

	/**
	 * Formatteert datum als tekst
	 *
	 * @param string $date Y-m-d
	 * @param boolean $year jaar toevoegen aan tekst
	 * @return string
	 */
	public static function format_date( $date, $year = true ) {
		$format = $year ? 'j F Y' : 'j F';
		return date_i18n( $format, strtotime( $date ) );
	}

	/**
	 * Formatteert datumrange als tekst
	 *
	 * @param string $date_start Y-m-d
	 * @param string $date_end Y-m-d
	 * @param bool $year jaar toevoegen aan tekst
	 *
	 * @return string
	 */
	public static function format_date_range( $date_start, $date_end, $year = true ) {
		
		if ( $date_start == $date_end ) {
			return self::format_date( $date_start, $year );
		}

		$date_start_array = date_parse( $date_start );
		$date_end_array = date_parse( $date_end );

		$format_end = $year ? 'j F Y' :  'j F';
		if ( $year && ( $date_start_array['year'] != $date_end_array['year'] ) ) {
			$format_start = 'j F Y';
		}
		elseif ( $date_start_array['month'] != $date_end_array['month'] ) {
			$format_start = 'j F';
		}
		else {
			$format_start = 'j';
		}

		$date_start_in_text = date_i18n( $format_start, strtotime( $date_start ) );
		$date_end_in_text = date_i18n( $format_end, strtotime( $date_end ) );

		$date_range_in_text = sprintf( __( '%s t/m %s', 'siw' ), $date_start_in_text, $date_end_in_text );

		return $date_range_in_text;
	}
	

	/**
	 * Formatteert maand uit datum als tekst
	 *
	 * @param string $date Y-m-d
	 * @param bool $year Jaar toevoegen aan tekst
	 *
	 * @return string
	 */
	public static function format_month( $date, $year = true ) {
		$format = $year ? 'F Y' :  'F';
		return date_i18n( $format, strtotime( $date ) );
	}

	/**
	 * Formatteert maand-range uit datums als tekst
	 *
	 * @param string $date_start Y-m-d
	 * @param string $date_end Y-m-d
	 * @param bool $year jaar toevoegen aan tekst
	 *
	 * @return string
	 */
	public static function format_month_range( $date_start, $date_end, $year = true ) {

		$date_start_array = date_parse( $date_start );
		$date_end_array = date_parse( $date_end );

		if ( $date_start == $date_end || ( $date_start_array['month'] == $date_end_array['month'] && $date_start_array['year'] == $date_end_array['year'] ) ) {
			return self::format_month( $date_start, $year );
		}

		$format_end = $year ? 'F Y' :  'F';
		if ( $year && ( $date_start_array['year'] != $date_end_array['year'] ) ) {
			$format_start = 'F Y';
		}
		else {
			$format_start = 'F';
		}

		$month_start_in_text = date_i18n( $format_start, strtotime( $date_start ) );
		$month_end_in_text = date_i18n( $format_end, strtotime( $date_end ) );

		$month_range_in_text = sprintf( __( '%s t/m %s', 'siw' ), $month_start_in_text, $month_end_in_text );

		return $month_range_in_text;
	}

	/**
	 * Genereert html voor bootstap-modal o.b.v. pagina-id
	 *
	 * @param int $page_id
	 * @return string
	 */
	public static function generate_modal( $page_id ) {
		$page = get_post( $page_id );
		ob_start();
		?>
		<div class="modal fade" id="siw-page-<?php echo esc_attr( $page_id );?>-modal" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><?php esc_html_e( $page->post_title );?></h4>
					</div>
					<div class="modal-body">
					<?php echo wp_kses_post( wpautop( do_shortcode( $page->post_content ) ) ); ?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default kad-btn" data-dismiss="modal"><?php esc_html_e( 'Sluiten', 'siw' );?></button>
					</div>
				</div>
			</div>
		</div>
		<?php
		$modal = ob_get_clean();
		return $modal;
	}
}
