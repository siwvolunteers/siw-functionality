<?php declare(strict_types=1);

use SIW\Admin\Page_Settings;
use SIW\Data\Email_Settings;
use SIW\Data\Project_Type;
use SIW\Data\Special_Page;

/** Haalt data uit bestand */
function siw_get_data( string $file ) {
	$file = strtolower( str_replace( '_', '-', $file ) );

	$data_file = SIW_DATA_DIR . "{$file}.php";

	if ( ! file_exists( $data_file ) ) {
		return null;
	}
	$data = require $data_file;
	return $data;
}

/** Geeft een lijst met nationaliteiten terug */
function siw_get_nationalities(): array {
	$nationalities = siw_get_data( 'nationalities' );
	asort( $nationalities );
	$nationalities = [ '' => __( 'Selecteer een nationaliteit', 'siw' ) ] + $nationalities;
	return $nationalities;
}

/**
 * Haalt email-instellingen op
 *
 * @todo fallback naar admin-email
 */
function siw_get_email_settings( string $id ): Email_Settings {
	$mail_settings = siw_get_option( "email_settings.{$id}" );
	if ( ! isset( $mail_settings['use_specific'] ) || ! $mail_settings['use_specific'] ) {
		$mail_settings = siw_get_option( 'email_settings.default' );
	}
	return new Email_Settings( $mail_settings );
}

/** Geeft lijst met formulieren terug */
function siw_get_forms(): array {
	$forms = apply_filters( 'siw_forms', [] );
	asort( $forms );

	return $forms;
}

function siw_get_special_page( Special_Page $special_page ): \WP_Post {
	/** @var \WP_Post[]|false */
	$pages = get_pages(
		[
			'meta_key'     => Page_Settings::SPECIAL_PAGE_META,
			'meta_value'   => $special_page->value,
			'hierarchical' => false,
		]
	);
	// Fallback naar homepagina
	if ( false === $pages || 0 === count( $pages ) ) {
		return get_post( get_option( 'page_on_front' ) );
	}

	$page = reset( $pages );
	return $page;
}

function siw_get_project_type_page( Project_Type $project_type ): ?\WP_Post {
	/** @var \WP_Post[]|false */
	$pages = get_pages(
		[
			'meta_key'     => Page_Settings::PROJECT_TYPE_PAGE_META,
			'meta_value'   => $project_type->value,
			'hierarchical' => false,
		]
	);
	// Fallback naar homepagina
	if ( false === $pages || 0 === count( $pages ) ) {
		return get_post( get_option( 'page_on_front' ) );
	}

	$page = reset( $pages );
	return $page;
}
