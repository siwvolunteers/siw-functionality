<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Controleer de extra velden
 */
add_action( 'woocommerce_checkout_process', function() {
	// controleer of geboortedatum het juiste formaat heeft en of de deelnemer minimaal 14 jaar is. //TODO: kijken naar minimumleeftijd project
	if ( $_POST['billing_dob'] ) {
		$date = explode( '-', $_POST['billing_dob']);
		if( ! checkdate( $date[1], $date[0], $date[2] ) || $date[2]<1900 )
			wc_add_notice( __( '<strong>Geboortedatum</strong> is ongeldig.', 'siw' ), 'error' );
		else {
			$date14YearsAdded = strtotime(date("Y-m-d", strtotime($date[2].'-'.$date[1].'-'.$date[0])) . " +14 year");
			if ( date("Y-m-d", $date14YearsAdded ) > date("Y-m-d") ) {
				wc_add_notice( __( '<strong>Geboortedatum</strong> de minimumleeftijd voor deelname is 14 jaar.', 'siw' ), 'error' );
			}
		}
	}

	//controleer of de taal 1 en het niveau van taal 1 geselecteerd zijn.
	if ( ! $_POST['language1'] ) {
		wc_add_notice( __( '<strong>Taal 1</strong> is niet geselecteerd.', 'siw' ), 'error' );
	}
	if ( ! $_POST['language1Skill'] ) {
		wc_add_notice( __( '<strong>Niveau taal 1</strong> is niet geselecteerd.', 'siw' ), 'error' );
	}

	//controleer of het niveau van taal 2 gekozen is als er een tweede taal geselecteerd is
	if ( $_POST['language2'] && ! $_POST['language2Skill'] ) {
		wc_add_notice( __( '<strong>Niveau taal 2</strong> is niet geselecteerd.', 'siw' ), 'error' );
	}

	//controleer of het niveau van taal 3 gekozen is als er een derde taal geselecteerd is
	if ( $_POST['language3'] && ! $_POST['language3Skill'] ) {
		wc_add_notice( __( '<strong>Niveau taal 3</strong> is niet geselecteerd.', 'siw' ), 'error' );
	}

	//controleer of gegevens noodcontact gevuld zijn
	if ( ! $_POST['emergencyContactName'] || ! $_POST['emergencyContactPhone'] ) {
		wc_add_notice( __( '<strong>Gegevens noodcontact</strong> zijn niet ingevuld.', 'siw' ), 'error' );
	}

	//controleer of de motivatie gevuld is
	if ( ! $_POST['motivation'] ) {
		wc_add_notice( __( '<strong>Motivation</strong> is niet ingevuld.', 'siw' ), 'error' );
	}
} );
