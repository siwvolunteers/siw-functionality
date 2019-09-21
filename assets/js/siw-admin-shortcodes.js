/** global: tinymce, siw_shortcodes */

/**
 * @file      TinyMCE-shortcodemenu
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

tinymce.PluginManager.add( 'siw_shortcodes', function( editor, url ) {

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

	editor.addButton( 'siw_shortcodes', {
		text: siw_shortcodes.title,
		icon: false,
		type: 'menubutton',
		menu: siw_shortcode_menu
	});
});