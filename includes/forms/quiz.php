<?php declare(strict_types=1);

namespace SIW\Forms;

use SIW\Interfaces\Forms\Form;

class Quiz {

	public static function add_quiz_fields( array $fields ): array {
		$one = wp_rand( 2, 5 );
		$two = wp_rand( 2, 5 );

		if ( $one > $two ) {
			$operator = __( 'min', 'siw' );
			$answer = $one - $two;
		} else {
			$operator = __( 'plus', 'siw' );
			$answer = $one + $two;
		}

		$fields[] = [
			'id'       => 'quiz',
			'type'     => 'number',
			'required' => true,
			/* translators: %1$d en %3$d twee zijn allebei getallen, %2$s is de operator (plus of min) */
			'name'     => sprintf( __( 'Hoeveel is %1$d %2$s %3$d?', 'siw' ), $one, $operator, $two ),
			'columns'  => Form::HALF_WIDTH,
		];
		$fields[] = [
			'id'      => 'quiz_hash',
			'type'    => 'hidden',
			'std'     => siw_hash( (string) $answer ),
			'columns' => Form::FULL_WIDTH,
		];
		return $fields;
	}

	public static function is_correct( \WP_REST_Request $request ): bool {
		$quiz = sanitize_text_field( $request->get_param( 'quiz' ) );
		$quiz_hash = sanitize_text_field( $request->get_param( 'quiz_hash' ) );

		return siw_hash( $quiz ) === $quiz_hash;
	}
}
