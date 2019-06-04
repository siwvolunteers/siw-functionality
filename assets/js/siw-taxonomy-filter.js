//TODO: functies maken
(function( $ ) {
	var filters = {};
	var $grid = $('.init-isotope-siw');
	$('.filter-button-group').on( 'click', 'button', function( event ) {
		var $button = $( event.currentTarget );
		// get group key
		var $buttonGroup = $button.parents('.filter-button-group');
		var filterGroup = $buttonGroup.attr('data-filter-group');
		// set filter for group
		filters[ filterGroup ] = $button.attr('data-filter');
		// combine filters
		var filterValue = concatValues( filters );
		// set filter for Isotope
		$grid.isotopeb({ filter: filterValue });
	});

	// change is-checked class on buttons
	$('.filter-button-group').each( function( i, buttonGroup ) {
		var $buttonGroup = $( buttonGroup );
		$buttonGroup.on( 'click', 'button', function( event ) {
			$buttonGroup.find('.is-checked').removeClass('is-checked');
			var $button = $( event.currentTarget );
			$button.addClass('is-checked');
		});
	});

	// flatten object by concatting values
	function concatValues( obj ) {
		var value = '';
		for ( var prop in obj ) {
			value += obj[ prop ];
		}
		return value;
	}
})( jQuery );