<?php declare(strict_types=1);

namespace SIW;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SIW Functionaliteit
 *
 * @copyright   2017-2024 SIW Internationale Vrijwilligersprojecten
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       SIW Functionaliteit
 * Plugin URI:        https://github.com/siwvolunteers/siw-functionality
 * Description:       Extra functionaliteit t.b.v website SIW
 * Version:           3.6.15
 * Author:            SIW Internationale Vrijwilligersprojecten
 * Author URI:        https://www.siw.nl
 * Text Domain:       siw
 * License:           GPLv2 or later
 * Requires at least: 6.4
 * Requires PHP:      8.2
 */

define( 'SIW_FUNCTIONALITY_PLUGIN_FILE', __FILE__ );
require_once __DIR__ . '/bootstrap.php';
$siw_bootstrap = new Bootstrap();
$siw_bootstrap->init();
