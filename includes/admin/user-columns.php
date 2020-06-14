<?php

namespace SIW\Admin;

/**
 * Extra Admin columns voor gebruikers
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class User_Columns extends \MBAC\User {

	/**
	 * Voegt extra columns toe
	 *
	 * @param array $columns
	 * @return array
	 */
	public function columns( $columns ) {
		$columns = parent::columns( $columns );
		$this->add( $columns, 'lastlogin', __( 'Laatste login', 'siw' )  );
		return $columns;
	}

	/**
	 * Undocumented function
	 *
	 * @param string $output
	 * @param string $column
	 * @param int $user_id
	 * 
	 * @return string
	 */
	public function show( $output, $column, $user_id ) {
		switch ( $column ) {
			case 'lastlogin' :
				$last_login = (int) get_user_meta( $user_id, 'last_login', true );
				if ( ! empty( $last_login ) && $last_login > 0 ) {
					$output = wp_date( 'j F Y H:i', $last_login );
				}
				else {
					$output = __( 'Nog nooit ingelogd', 'siw' );
				}
		}
		return $output;
	}
}
