/** global: siw_meta_box */

/**
 * @file      Functies t.b.v MetaBox
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */

//Validatiemelding toevoegen
(function( $ ) {

	let validation_messages = siw_meta_box.validation_messages;

	$.extend( $.validator.messages, {
		required: validation_messages.required,
		remote: validation_messages.remote,
		email: validation_messages.email,
		url: validation_messages.url,
		date: validation_messages.date,
		dateISO: validation_messages.dateISO,
		number: validation_messages.number,
		digits: validation_messages.digits,
		creditcard: validation_messages.creditcard,
		equalTo: validation_messages.equalTo,
		extension: validation_messages.extension,
		maxlength: $.validator.format( validation_messages.maxlength ),
		minlength: $.validator.format( validation_messages.minlength ),
		rangelength: $.validator.format( validation_messages.rangelength ),
		range: $.validator.format( validation_messages.range ),
		max: $.validator.format( validation_messages.max ),
		min: $.validator.format( validation_messages.min ),
		step: $.validator.format( validation_messages.step ),
		accept: $.validator.format( validation_messages.accept ),
	} );
})( jQuery );
