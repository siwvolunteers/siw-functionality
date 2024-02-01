<?php declare(strict_types=1);

namespace SIW\Admin;

class User_Columns extends \MBAC\User {

	#[\Override]
	public function columns( $columns ) {
		$columns = parent::columns( $columns );
		$this->add( $columns, 'lastlogin', __( 'Laatste login', 'siw' ) );
		return $columns;
	}

	#[\Override]
	public function show( $output, $column, $user_id ) {
		switch ( $column ) {
			case 'lastlogin':
				$last_login = (int) get_user_meta( $user_id, 'last_login', true );
				if ( ! empty( $last_login ) && $last_login > 0 ) {
					$output = wp_date( 'j F Y H:i', $last_login );
				} else {
					$output = __( 'Nog nooit ingelogd', 'siw' );
				}
		}
		return $output;
	}
}
