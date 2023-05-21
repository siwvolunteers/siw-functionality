<?php declare(strict_types=1);

namespace SIW\Actions\Async;

use SIW\Interfaces\Actions\Async as Async_Action_Interface;

use SIW\Plato\Export_Application as Plato_Export_Application;
use SIW\Properties;

/**
 * Exporteren aanmelding naar Plato
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Export_Plato_Application implements Async_Action_Interface {

	const ORDER_META_EXPORTED_TO_PLATO = '_exported_to_plato';
	const SUCCESS = 'success';
	const FAILED = 'failed';

	/** Aantal gefaalde geexporteerde aanmeldingen */
	protected int $failed_count = 0;

	/** Aantal succesvol geexporteerde aanmeldingen */
	protected int $success_count = 0;

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'export_plato_application';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Exporteren aanmelding naar Plato', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_argument_count(): int {
		return 1;
	}

	/** {@inheritDoc} */
	public function process( int $order_id = 0 ) {
		$order = wc_get_order( $order_id );
		if ( ! is_a( $order, \WC_Order::class ) ) {
			return;
		}

		/* Haal velden voor aanmelding op */
		$order_data = $this->get_order_data( $order );

		/** @var \WC_Order_Item_Product[] */
		$order_items = $order->get_items();

		/* Elk project per aanmelding apart exporteren. */
		foreach ( $order_items as $order_item ) {
			$product = siw_get_product( $order_item->get_product_id() );
			$result = $this->export_application( $order_data, $product );
			$order->add_order_note( $result['message'] );
		}

		/* Resultaat opslaan bij aanmelding */
		if ( 0 !== $this->failed_count ) {
			$order->update_meta_data( self::ORDER_META_EXPORTED_TO_PLATO, self::FAILED );
			$order->save();
		} elseif ( 0 !== $this->success_count ) {
			$order->update_meta_data( self::ORDER_META_EXPORTED_TO_PLATO, self::SUCCESS );
			$order->save();
		}
	}

	/** Exporteert aanmelding voor 1 project naar Plato */
	protected function export_application( array $order_data, \WC_Product $product ): array {

		$projectcode = $product->get_sku();
		$order_data['choice1'] = $projectcode;
		$export = new Plato_Export_Application();
		$result = $export->run( $order_data );

		if ( true === $result['success'] ) {
			$this->success_count++;
		} else {
			$this->failed_count++;
		}
		return $result;
	}

	/** Genereert array met gegevens aanmelding voor export-xml*/
	protected function get_order_data( \WC_Order $order ) : array {
		return [
			'firstname'         => $order->get_billing_first_name(),
			'lastname'          => $order->get_billing_last_name(),
			'sex'               => $order->get_meta( '_billing_gender' ),
			'birthdate'         => gmdate( 'Y-m-d', strtotime( $order->get_meta( '_billing_dob' ) ) ),
			'email'             => $order->get_billing_email(),
			'nationality'       => $order->get_meta( '_billing_nationality' ),
			'telephone'         => $order->get_billing_phone(),
			'country'           => 'NLD', // TODO: uitvragen
			'occupation'        => 'OTH', // TODO: uitvragen
			'emergency_contact' => sprintf( '%s %s', $order->get_meta( 'emergency_contact_name' ), $order->get_meta( 'emergency_contact_phone' ) ),
			'language1'         => $order->get_meta( 'language_1' ),
			'language2'         => $order->get_meta( 'language_2' ),
			'language3'         => $order->get_meta( 'language_3' ),
			'langlevel1'        => $order->get_meta( 'language_1_skill' ),
			'langlevel2'        => $order->get_meta( 'language_2_skill' ),
			'langlevel3'        => $order->get_meta( 'language_3_skill' ),
			'special_needs'     => $order->get_meta( 'health_issues' ),
			'experience'        => $order->get_meta( 'volunteer_experience' ),
			'motivation'        => $order->get_meta( 'motivation' ),
			'together_with'     => $order->get_meta( 'together_with' ),
			'req_sent_by'       => Properties::NAME,
			'req_sender_email'  => Properties::EMAIL,
			'date_filed'        => gmdate( 'Y-m-d' ),
		];
	}
}
