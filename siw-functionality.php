<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SIW Functionaliteit
 *
 * @package     SIW
 * @author      Maarten Bruna
 * @copyright   2017-2019 SIW Internationale Vrijwilligersprojecten
 *
 * @wordpress-plugin
 * Plugin Name: SIW Functionaliteit
 * Plugin URI:  https://github.com/siwvolunteers/siw-functionality
 * Description: Extra functionaliteit t.b.v website SIW
 * Version:     2.1.1
 * Author:      Maarten Bruna
 * Text Domain: siw
 */

require_once dirname( __FILE__ ) . '/class-siw-bootstrap.php';
$bootstrap = new SIW_Bootstrap();
$bootstrap->init();

/* Basisfunctionaliteit: functies en instellingen */
require_once( SIW_INCLUDES_DIR . '/options/options.php' );

/* Diverse aanpassingen */
require_once( SIW_INCLUDES_DIR . '/content-types/content-types.php' );
require_once( SIW_INCLUDES_DIR . '/email/init.php');
require_once( SIW_INCLUDES_DIR . '/maps/maps.php' );
require_once( SIW_INCLUDES_DIR . '/post-types/init.php' );
require_once( SIW_INCLUDES_DIR . '/woocommerce/init.php' );

