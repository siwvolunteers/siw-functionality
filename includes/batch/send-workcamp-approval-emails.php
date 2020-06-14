<?php

namespace SIW\Batch;

use SIW\Email\Template;
use SIW\Util\Links;
use SIW\WooCommerce\Import\Product as Import_Product;

/**
 * Versturen email voor goedkeuren Groepsprojecten
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Send_Workcamp_Approval_Emails extends Job {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'send_workcamp_approval_emails';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'versturen email goedkeuren groepsprojecten';

	/**
	 * {@inheritDoc}
	 */
	protected $category = 'groepsprojecten';

	/**
	 * Selecteer categorieën
	 *
	 * @return array
	 */
	protected function select_data() {

		$categories = get_terms( [
			'taxonomy'   => 'product_cat',
			'hide_empty' => true
		]);
		
		if ( empty( $categories ) || is_wp_error( $categories ) ) {
			return false;
		}

		foreach ( $categories as $category ) {
			$data[] = [
				'slug' => $category->slug,
				'name' => $category->name,
			];
		}

		return $data;
	}

	/**
	 * Versturen van email
	 *
	 * @param string $category
	 *
	 * @return mixed
	 */
	protected function task( $category ) {

		// Zoek te beoordelen projecten per category
		$products = wc_get_products([
			'return'   => 'ids',
			'category' => [ $category['slug'] ],
			'status'   => Import_Product::REVIEW_STATUS,
			'limit'    => -1,
		]);
		if ( empty( $products ) ) {
			return false;
		}

		$supervisor = $this->get_supervisor();
		if ( false === $supervisor ) {
			return false; //TODO:logging
		}
		$responsible_user = $this->get_responsible_user( $category['slug'] );
		if ( false === $responsible_user ) {
			$responsible_user = $supervisor;
		}

		$admin_url = add_query_arg(
			[
				'post_type'   => 'product',
				'post_status' => Import_Product::REVIEW_STATUS,
				'product_cat' => $category['slug'],
			],
			admin_url( 'edit.php' )
		);

		$message = 
			sprintf( 'Beste %s,', $responsible_user->user_firstname ) . BR2 .
			sprintf( 'Er wachten nog %d projecten in %s op jouw beoordeling.', count( $products ), $category['name'] ) . BR .
			sprintf( 'Klik %s om de projecten te bekijken.', Links::generate_link( $admin_url, 'hier') );

		$template = new Template(
			[
				'subject'           => sprintf( 'Nog te beoordelen projecten in %s', $category['name'] ),
				'message'           => $message,
				'show_signature'    => true,
				'signature_name'    => $supervisor->display_name
			]
		);

		$this->send_mail(
			$responsible_user,
			$supervisor, 
			sprintf( 'Nog te beoordelen projecten in %s', $category['name'] ),
			$template->generate()
		);
		$this->increment_processed_count();
		return false;
	}

	/**
	 * Zoekt coordinator voor import Groepsprojecten
	 * 
	 * @return \WP_User|bool
	 */
	protected function get_supervisor() {
		$workcamp_approval = siw_get_option( 'workcamp_approval' );
		if ( isset( $workcamp_approval['supervisor'] ) ) {
			return get_userdata( $workcamp_approval['supervisor'] );
		}
		return false;
	}

	/**
	 * Zoekt verantwoordelijke voor specifiek continent
	 *
	 * @param string $category_slug
	 * @return \WP_User|bool
	 */
	protected function get_responsible_user( string $category_slug ) {
		$workcamp_approval = siw_get_option( 'workcamp_approval' );
		if ( isset( $workcamp_approval[ "responsible_{$category_slug}" ] ) ) {
			return get_userdata( $workcamp_approval[ "responsible_{$category_slug}" ] );
		}
		return false;
	}

	/**
	 * Verstuur e-mail
	 *
	 * @param \WP_User $to
	 * @param \WP_User $from
	 * @param string $subject
	 * @param string $message
	 */
	protected function send_mail( \WP_User $to, \WP_User $from, string $subject, string $message ) {
		$headers = [
			'Content-Type: text/html; charset=UTF-8', 
			sprintf( 'From: %s <%s>', $from->display_name, $from->user_email ),
		];
		if ( $to != $from ) {
			$headers[] = sprintf( 'CC: %s <%s>', $from->display_name, $from->user_email );
		}

		wp_mail(
			$to->user_email,
			$subject,
			$message,
			$headers
		);
	}

}
