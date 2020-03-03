/** global: sal, siw_animation */

/**
 * @file      Animaties
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @todo      update bij resize
 */

//Tijdelijke workaround
try {
	sal({
		threshold: siw_animation.threshold,
		disabled: ( window.matchMedia( "(max-width:" + siw_animation.breakpoint + "px)" ).matches ),
		once: siw_animation.once
	});
} catch ( error ) {
	console.warn( error.message );
}