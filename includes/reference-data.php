<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once('reference-data/constants.php');
//includes
require_once('reference-data/project-currencies.php');
require_once('reference-data/project-countries.php');
require_once('reference-data/project-work.php');
require_once('reference-data/languages.php');
require_once('reference-data/nationalities.php');



//losse functies TODO: verplaatsen naar helper functions
function siw_get_genders(){
	$genders = array(
		'M' => __('Man', 'siw'),
		'F' => __('Vrouw', 'siw'),
	);
	return $genders;
}
