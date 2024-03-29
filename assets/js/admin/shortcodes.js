jQuery( document ).on( 'tinymce-editor-setup', function( event, editor ) {

	//Shortcodes ophalen
	var siw_shortcode_menu = [];

	siw_shortcodes.shortcodes.forEach( function( shortcode ) {

		if ( typeof shortcode.attributes == 'undefined' ) {
			siw_shortcode_menu.push(
				{
					text: shortcode.title,
					onclick: function() {
						editor.insertContent( '[siw_' + shortcode.shortcode + ']');
					}
				}
			);
		} else {
			siw_shortcode_menu.push(
				{
					text: shortcode.title,
					onclick: function() {
						editor.windowManager.open( {
							title: shortcode.title,
							body: shortcode.attributes,
							onsubmit: function( e ) {
								var content = '[siw_' + shortcode.shortcode;
								for ( let attr in e.data ) {
									content += ' ' + attr + '="' + e.data[attr] + '"';
								}
								content += ']';
								editor.insertContent( content );
							}
						});
					}
				}
			);
		}
	});

	//Knop toevoegen
	editor.settings.toolbar1 += ',siw_shortcodes';
	editor.addButton( 'siw_shortcodes', {
		text: siw_shortcodes.title,
		icon: false,
		type: 'menubutton',
		menu: siw_shortcode_menu
	});

	//Link-knop verwijderen
	editor.settings.toolbar1 = editor.settings.toolbar1.replace( ',link', '');

});
