/** global: sal, siw_animation */

/**
 * @file      Animaties
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 *
 * @todo      update bij resize
 */

// Try/catch voor incompatible browsers
try {
	sal({
		threshold: siw_animation.threshold,
		disabled: ( window.matchMedia( "(max-width:" + siw_animation.breakpoint + "px)" ).matches ),
		once: siw_animation.once
	});
} catch ( error ) {
	console.warn( error.message );
}
