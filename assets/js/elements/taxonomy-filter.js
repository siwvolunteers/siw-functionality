/** global: Masonry */

/**
 * @file      Functies t.b.v. taxonomy filters
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */

//TODO: variabele voor selectors -> wp_localize_script
//TODO: check op masonry actief
var siwTaxonomyFilter = (function () {

	//Variabele met filters
	var filters = {};

	//Instantie van Masonry
	var msnry;

	/* Public methodes */
	return {
		init: init
	};

	/**
	 * Init
	 */
	function init () {
		var button_groups = document.querySelectorAll('.siw-taxonomy-filter');
		for ( var i=0, len = button_groups.length; i < len; i++ ) {
			var button_group = button_groups[i];
			_addButtonGroupHandlers( button_group );
		}
	}

	/**
	 * Voegt event handlers voor button group toe
	 *
	 * @param {*} button_group
	 */
	function _addButtonGroupHandlers( buttonGroup ) {
		var buttons = buttonGroup.querySelectorAll( 'button' );

		for ( var i=0, len = buttons.length; i < len; i++ ) {
			var button = buttons[i];
			button.addEventListener( 'click', _buttonHandler );
		}
	}

	/**
	 * Handelt klikken op knop af
	 *
	 * @param {Event} event
	 */
	function _buttonHandler( event ) {

		var selectedButton = event.target;
		var buttonGroup = selectedButton.parentElement;

		//Filter bijwerken
		filters[ buttonGroup.dataset.filterGroup ] = selectedButton.dataset.filter;
		_updateFilter();

		//Classes bijwerken
		var buttons = buttonGroup.querySelectorAll('.is-checked');
		for ( var i=0, len = buttons.length; i < len; i++ ) {
			var button = buttons[i];
			button.classList.remove('is-checked');
		}
		selectedButton.classList.add('is-checked');
	}

	/**
	 * Werkt filter bij
	 */
	function _updateFilter() {
		//TODO: verplaatsen, dan hoeft dit maar 1 keer
		var grid = document.querySelector('.masonry-container' )
		msnry = Masonry.data( grid );


		//Queryselector ophalen
		var querySelector = _getQuerySelector();

		//Geselecteerde elementen tonen TODO: wat als er geen masonry is?
		var elements = msnry.getItemElements();
		for ( var i=0, len = elements.length; i < len; i++ ) {
			var element = elements[i];
			if ( '' == querySelector || element.matches( querySelector ) ) {
				element.removeAttribute( 'hidden' );
			}
			else {
				element.setAttribute( 'hidden', 'hidden' );
			}
		}

		//Layout bijwerken
		msnry.layout();
	}

	/**
	 * Geeft queryselector-string terug
	 */
	function _getQuerySelector() {
		var querySelector = '';
		for ( var tax in filters ) {
			if ( '' != filters[ tax ] ){
				querySelector += '.' + tax + '-' + filters[ tax ];
			}
		}
		return querySelector;
	}

})();

siwTaxonomyFilter.init();
