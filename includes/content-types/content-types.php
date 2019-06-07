<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Content types
 * 
 * @package   SIW\Content
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */



require_once __DIR__ . '/class-siw-post-type.php';
require_once __DIR__ . '/class-siw-taxonomy.php';

require_once __DIR__ . '/abstract-siw-content-type.php';
require_once __DIR__ . '/class-siw-content-type-tm-country.php';
new SIW_Content_Type_TM_Country;
