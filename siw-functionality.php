<?php

namespace SIW;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SIW Functionaliteit
 *
 * @copyright   2017-2019 SIW Internationale Vrijwilligersprojecten
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: SIW Functionaliteit
 * Plugin URI:  https://github.com/siwvolunteers/siw-functionality
 * Description: Extra functionaliteit t.b.v website SIW
 * Version:     3.0.5
 * Author:      SIW Internationale Vrijwilligersprojecten
 * Author URI:  https://www.siw.nl
 * Text Domain: siw
 * License:     GPLv2 or later
 */

require_once dirname( __FILE__ ) . '/bootstrap.php';
$bootstrap = new Bootstrap();
$bootstrap->init();
