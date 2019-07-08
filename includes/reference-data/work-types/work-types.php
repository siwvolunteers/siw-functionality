<?php
/**
 * Functies m.b.t. soorten werk
 * 
 * @author      Maarten Bruna
 * @package     SIW\Reference-Data
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( __DIR__ . '/class-siw-work-type.php' );

/**
 * Geeft een array met soort werk voor projecten terug
 *
 * @param string $index
 * @param string $context all|dutch_projects|tailor_made_projects
 * @return SIW_Work_Type[]
 */
function siw_get_work_types( $context = 'all', $index = 'slug' ) {


	$data = require SIW_DATA_DIR . '/work-types.php';

	foreach ( $data as $item ) {
		$work_type = new SIW_Work_Type( $item );
		if ( 'all' == $context
			|| ( 'dutch_projects' == $context && true == $work_type->is_for_dutch_projects() ) 
			|| ( 'tailor_made_projects' == $context && true == $work_type->is_for_tailor_made_projects() )
		) {
			$work_types[ $item[ $index ] ] = $work_type;
		}
	}

	return $work_types;
}

/**
 * Geeft informatie over soort werk terug
 *
 * @param string $work_type
 * @param string $index
 * @return SIW_Work_Type
 */
function siw_get_work_type( $work_type, $index = 'slug' ) {
	
	$work_types = siw_get_work_types( 'all', $index );

	if ( isset( $work_types[ $work_type ] ) ) {
		return $work_types[ $work_type ];
	}

	return false;
}