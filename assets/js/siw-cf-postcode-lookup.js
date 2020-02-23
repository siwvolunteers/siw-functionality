/** global: siwPostcodeApi */

/**
 * @file      Postcode lookup voor Caldera Forms
 * @copyright 2015-2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */

 //Caldera forms postcode lookup
//TODO: geen anon-functies + verplaatsen naar eigen bestand
jQuery( document ).on( 'cf.form.init', function ( event, data ) {
	var form = document.getElementById( data.idAttr );
	if ( form.dataset.siwPostcodeLookup ) {
		var suffix = data.idAttr.replace( data.formId, '');
		var postcode_id = 'postcode' + suffix;
		var housenumber_id = 'huisnummer' + suffix;
		var street_id = 'straat' + suffix;
		var city_id = 'woonplaats' + suffix;

		siwPostcodeApi.addHandler( postcode_id, housenumber_id, street_id, city_id)
	}
});
