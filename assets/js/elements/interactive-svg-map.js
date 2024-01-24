var siwInteractiveSVGMaps = (function () {

	/* Public methodes */
	return {
		init: init
	};

	/**
	 * Initialiseert alle kaarten
	 */
	function init () {
		//Zoek alle kaarten
		var maps = document.querySelectorAll( '.siw-interactive-svg-map' );

		//Initialiseer elke kaart
		for ( var i=0, len = maps.length; i < len; i++ ) {
			var map = maps[i];
			_initSingle( map );
		}
	}

	/**
	 * Initialiseert 1 kaart
	 *
	 * @param {Element} el
	 */
	function _initSingle( el ) {

		const options = JSON.parse( el.dataset.options );
		const markers = JSON.parse( el.dataset.markers );

		new jsVectorMap({
			selector: el,
			map: options.map,
			backgroundColor: options.backgroundColor,
			draggable: options.draggable,
			zoomButtons: options.zoomButtons,
			zoomOnScroll: options.zoomOnScroll,
			zoomOnScrollSpeed: options.zoomOnScrollSpeed,
			zoomMax: options.zoomMax,
			zoomMin: options.zoomMin,
			zoomAnimate: options.zoomAnimate,
			showTooltip: options.showTooltip,
			zoomStep: options.zoomStep,
			bindTouchEvents: options.bindTouchEvents,
			markersSelectable: options.markersSelectable,
			markersSelectableOne: options.markersSelectableOne,
			selectedMarkers: options.selectedMarkers,
			regionsSelectable: options.regionsSelectable,
			regionsSelectableOne: options.regionsSelectableOne,
			selectedRegions: options.selectedRegions,
			focusOn: options.focusOn,
			regionStyle: options.regionStyle,
			regionLabelStyle: options.regionLabelStyle,
			markerStyle: options.markerStyle,
			markerLabelStyle: options.markerLabelStyle,
			markers: markers,
		});

	}

})();

siwInteractiveSVGMaps.init();
