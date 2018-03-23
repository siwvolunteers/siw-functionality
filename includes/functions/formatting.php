<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Genereert <ul> of <ol> van array
 * @param array $items
 * @param bool $ordered
 * @return string
 */
function siw_generate_list( $items, $ordered = false ) {
	if ( empty ( $items ) ) {
		return false;
	}
	$tag = $ordered ? 'ol' : 'ul';

	$list = "<{$tag}>";
	foreach ( $items as $item ) {
		$list .= '<li>' . (string) $item . '</li>';
	}
	$list .= "</{$tag}>";

	return $list;
}
add_filter( 'siw_list', function( $list, $items, $ordered = true ) {
	return siw_generate_list( $items, $ordered );
}, 10, 2 );


/**
 * Genereert externe link
 * @param  string $url
 * @param  string $text
 * @return string
 */
function siw_generate_external_link( $url, $text = false ) {

	if ( false == $text ) {
		$text = $url;
	}
	$external_link = sprintf( '<a class="siw-external-link" href="%s" target="_blank" rel="noopener">%s&nbsp;<i class="kt-icon-newtab"></i></a>', esc_url( $url ), esc_html( $text ) );

	return $external_link;
}


/**
 * Formatteert getal als bedrag
 * @param  float  $amount
 * @param  integer $decimals
 * @return string
 */
function siw_format_amount( $amount, $decimals = 0 ) {
	$amount = number_format( $amount, $decimals );
	return sprintf( '&euro; %s', $amount );
}


/**
 * Formatteert getal als percentage
 * @param  float  $percentage
 * @param  integer $decimals
 * @return string
 */
function siw_format_percentage( $percentage, $decimals = 0 ) {
	$percentage = number_format( $percentage, $decimals );
	return sprintf( '%s &percnt;', $percentage );
}


/**
 * Geneereer pinnacle accordion
 * @param  array $panes
 * @return string
 */
function siw_generate_accordion( $panes ) {

	if ( empty( $panes) ) {
		return;
	}
	$accordion = '[accordion]';
		foreach ( $panes as $pane ) {
		$accordion .= sprintf( '[pane title="%s"]%s[/pane]', esc_html( $pane['title'] ), wp_kses_post( wpautop( $pane['content'] ) ) );
	}
	$accordion .= '[/accordion]';

	return $accordion;
}
add_filter( 'siw_accordion', function( $accordion, $panes ) {
	return siw_generate_accordion( $panes );
}, 10, 2 );



/**
 * Functie om HTML-input te tonen
 *
 * @param string $type
 * @param array $input_args
 * @param array $wrapper_args
 * @return void
 */
function siw_render_field( $type, $input_args, $wrapper_args = array() ) {
	echo siw_generate_field( $type, $input_args, $wrapper_args );
}


/**
 * Functie om HTML-input te genereren
 *
 * @param string $type
 * @param array $input_args
 * @param array $wrapper_args
 * @return string
 */
function siw_generate_field( $type, $input_args, $wrapper_args = array() ) {

	/* Controleer of het een toegestaan type is */
	$allowed_types = array(
		'text',
		'tel',
		'time',
		'number',
		'email',
		'submit',
		'radio',
		'select',
		'url',
		'date',
		'textarea',
		'hidden',
	);
	if ( ! in_array( $type, $allowed_types ) ) {
		return false;
	}

	$input_args = wp_parse_args( $input_args, array(
		'id'			=> '',
		'name'			=> '',
		'value'			=> '',
		'options'		=> array(),
		'placeholder'	=> '',
		'pattern'		=> '',
		'class'			=> '',
		'disabled'		=> false,
		'required'		=> false,
		'attributes'	=> array(),
		'options'		=> array(),
		)		
	);

	$attributes = wp_parse_args( $input_args['attributes'], array(
		'disabled'		=> $input_args['disabled'],
		'required'		=> $input_args['required'],
		'class'			=> sanitize_html_classes( $input_args['class'] ),
		'id'			=> $input_args['id'],
		'name'			=> $input_args['name'],
		'value'			=> $input_args['value'],
		'placeholder'	=> $input_args['placeholder'],
	) );

	/* Start genereren veld */
	$field = '';

	/* Label */
	if( ! empty( $input_args['label'] ) ) {
		$field .= sprintf( '<label for="%s">%s</label>', esc_attr( $input_args['id'] ), esc_html( $input_args['label'] ) );
	}

	if ( 'select' == $type ) {
		$options = $input_args['options'];
		$value = $attributes['value'];

		$field .= sprintf( '<select %s>', siw_generate_field_attributes( $attributes ) );
		if ( ! empty( $options ) && is_array( $options ) ) {
			foreach ( $options as $key => $option ) {
				$selected = selected( $value, $key, false );
				$field .= sprintf('<option value="%s" %s>%s</option>', esc_attr( $key ), $selected, esc_html( $option ) );
			}
		}
		$field .= '</select>';
	}
	elseif ( 'radio' == $type ) {
		$options = $input_args['options'];
		$value = $attributes['value'];
		if ( ! empty( $options ) && is_array( $options ) ) {
			foreach ( $options as $key => $option ) {
				$checked = checked( $value, $key, false );
				$field .= sprintf( '<label><input type="radio" value="%s" %s %s>%s</label>', esc_attr( $key ), siw_generate_field_attributes( $attributes ), $checked, esc_html( $option ) );
			}
		}
	}
	elseif ( 'textarea' == $type ) {
		$value = $attributes['value'];
		unset( $attributes['value'] );
		$field .= sprintf( '<textarea %s>%s</textarea>', siw_generate_field_attributes( $attributes ), $value );
	}
	elseif( 'checkbox' == $type ) {
		$value = $attributes['value'];
		unset( $attributes['value'] );
		$field .= sprintf( '<label for="%s">', esc_attr( $attributes['id'] ) );
		$field .= sprintf( '<input type="checkbox" %s %s/>', siw_generate_field_attributes( $attributes ), checked( 1, $value, false ) );
		$field .= sprintf( '%s</label>', esc_html( $input_args['label'] ) );
	}
	else {
		$field .= sprintf('<input type="%s" %s>', esc_attr( $type ), siw_generate_field_attributes( $attributes ) );
	}


	/* Wrapper toevoegen */
	$wrapper_args = wp_parse_args( $wrapper_args, array(
		'tag' => '',
		'class' => array(),
		)
	);

	if ( '' != $wrapper_args['tag'] ) {
		$wrapper_class = '';
		if ( ! empty( $wrapper_args['class'] ) ) {
			$wrapper_class = sprintf( 'class="%s"', sanitize_html_classes( $wrapper_args['class'] ) );
		}

		$wrapper_open = sprintf( '<%s %s>', tag_escape( $wrapper_args['tag'] ), $wrapper_class );
		$wrapper_close = sprintf( '</%s>', tag_escape( $wrapper_args['tag'] ) );

		$field = $wrapper_open . $field . $wrapper_close;
	}

	return $field;
}


/**
 * Genereert attributes op basis van array
 *
 * @param array $attributes
 * @return string
 */
function siw_generate_field_attributes( $attributes ) {
	$field_attributes = '';
	foreach ( $attributes as $key => $value ) {
		if ( false == $value )
			continue;
		if ( is_array( $value ) ) {
			$value = json_encode( $value );
		}
		$field_attributes .= sprintf( true === $value ? ' %s' : ' %s="%s"', $key, esc_attr( $value ) );
	}

	return $field_attributes;
}


if ( ! function_exists( 'sanitize_html_classes' ) && function_exists( 'sanitize_html_class' ) ) {
	/**
	 * sanitize_html_class voor meerdere classes
	 *
	 * @uses   sanitize_html_class
	 * @param  mixed $class 
	 * @param  string $fallback
	 * @return string
	 */
	function sanitize_html_classes( $class, $fallback = null ) {
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
}
