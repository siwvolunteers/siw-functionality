<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Context voor landen
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
enum Country_Context: string {
	case ALL = 'all';
	case WORKCAMPS = 'workcamps';
	case ESC = 'esc_projects';
	case TAILOR_MADE = 'tailor_made_projects';
	case PROJECTS = 'projects';
	case AFRICA = 'afrika';
	case ASIA = 'azie';
	case EUROPE = 'europa';
	case NORTH_AMERICA = 'noord_amerika';
	case LATIN_AMERICA = 'latijns_amerika';
}
