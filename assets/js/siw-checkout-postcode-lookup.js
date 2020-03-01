/** global: siwPostcodeApi, siw_checkout_postcode_selectors */

/**
 * @file      Postcode lookup tijdens checkout
 * @copyright 2015-2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */

if ( typeof siw_checkout_postcode_selectors !== 'undefined') {
	var selectors = siw_checkout_postcode_selectors;
	siwPostcodeApi.addHandler( selectors.postcode, selectors.housenumber, selectors.street, selectors.city );
}
