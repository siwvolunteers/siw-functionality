wp.hooks.addFilter(
	'generate_font_manager_system_fonts',
	'siw',
	() => [
		{ value: 'Open Sans', label: 'Open Sans' },
	]
);
