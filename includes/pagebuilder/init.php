<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Siteorigin Live Editor over ssl */
add_filter( 'woocommerce_unforce_ssl_checkout', function ( $unforce_ssl ) {
	if ( isset( $_REQUEST['siteorigin_panels_live_editor'] ) && 'true' == $_REQUEST['siteorigin_panels_live_editor'] ) {
		return false;
	}
	return $unforce_ssl;
} );


/*Pagebuilder optiegroep voor zichtbaarheid */
add_filters( array( 'siteorigin_panels_widget_style_groups', 'siteorigin_panels_row_style_groups' ), function( $groups ) {
	$groups['visibility'] = array(
		'name'		=> __('Zichtbaarheid', 'siw'),
		'priority'	=> 99,
	);
	return $groups;

}, 10 );


/*Pagebuilder optie voor zichtbaarheid op mobiel */
add_filters( array( 'siteorigin_panels_widget_style_fields', 'siteorigin_panels_row_style_fields' ), function( $fields ){
	$fields['hide_on_mobile'] = array(
		'name'			=> '<span class="dashicons dashicons-smartphone"></span>' . __( 'Mobiel', 'siw'),
		'label'			=> __( 'Verbergen', 'siw'),
		'group'			=> 'visibility',
		'type'			=> 'checkbox',
		'priority'		=> 10,
	);
	return $fields;

}, 10 );

/* Klasse toevoegen om content te verberge op mobiel*/
add_filters( array( 'siteorigin_panels_widget_style_attributes', 'siteorigin_panels_cell_style_attributes' ), function( $style_attributes, $style_args ) {
	if ( isset( $style_args['hide_on_mobile'] ) && 1 == $style_args['hide_on_mobile'] ) {
		$style_attributes['class'][] = 'hide_on_mobile';
	}
	return $style_attributes;
}, 10, 2 );


/*Eigen Pagebuilder-tabs voor SIW en Pinnacle widgets */
add_filter( 'siteorigin_panels_widget_dialog_tabs', function ( $tabs ) {
	$tabs[] = array(
		'title' => __('SIW Widgets', 'siw'),
		'filter' => array(
			'groups' => array('siw'),
		),
	);
	$tabs[] = array(
		'title' => __('Pinnacle Widgets', 'siw'),
		'filter' => array(
			'groups' => array('kad'),
		),
	);
	return $tabs;
}, 20 );


/* Pinnacle widgets in eigen tab */
add_filter('siteorigin_panels_widgets', function ( $widgets ) {
	foreach ( $widgets as $widget_id => &$widget ) {
		if ( 0 === strpos( $widget_id, 'kad_' ) ) {
			$widget['groups'][] = 'kad';
		}
	}
	return $widgets;
} );
