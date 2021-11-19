<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Email\Template;
use SIW\Helpers\Email;
use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;
use SIW\Util\Links;
use SIW\WooCommerce\Import\Product as Import_Product;

/**
 * Versturen email voor goedkeuren Groepsprojecten
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Send_Workcamp_Approval_Emails implements Batch_Action_Interface {

	/** Taxonomie voor continenten */
	const CONTINENT_TAXONOMY = 'product_cat';

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'send_workcamp_approval_emails';
	}
	
	/** {@inheritDoc} */
	public function get_name(): string {
		return 'Versturen email goedkeuren groepsprojecten';
	}
	
	/** {@inheritDoc} */
	public function must_be_scheduled(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function must_be_run_on_update(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function select_data(): array {
		
		$data = get_terms( [
			'taxonomy'   => self::CONTINENT_TAXONOMY,
			'hide_empty' => true,
			'fields'     => 'tt_ids'
		]);
		
		if ( is_wp_error( $data ) ) {
			return [];
		}

		return $data;
	}
	
	/** {@inheritDoc} */
	public function process( $term_taxonomy_id ) {
		if ( ! is_int( $term_taxonomy_id ) ) {
			return;
		}

		$term = get_term_by( 'term_taxonomy_id', $term_taxonomy_id );
		if ( ! is_a( $term, \WP_Term::class ) ) {
			return;
		}
	
		// Zoek te beoordelen projecten per category (continent)
		$products = siw_get_product_ids([
			'category' => $term->slug,
			'status'   => Import_Product::REVIEW_STATUS,
		]);
		if ( empty( $products ) ) {
			return false;
		}

		$supervisor = $this->get_supervisor();
		if ( is_null( $supervisor ) ) {
			return false; //TODO:logging
		}
		$responsible_user = $this->get_responsible_user( $term->slug ) ?? $supervisor;

		$admin_url = add_query_arg(
			[
				'post_type'   => 'product',
				'post_status' => Import_Product::REVIEW_STATUS,
				'product_cat' => $term->slug,
			],
			admin_url( 'edit.php' )
		);

		$message = 
			sprintf( 'Beste %s,', $responsible_user->user_firstname ) . BR2 .
			sprintf( 'Er wachten nog %d projecten in %s op jouw beoordeling.', count( $products ), $term->name ) . BR .
			sprintf( 'Klik %s om de projecten te bekijken.', Links::generate_link( $admin_url, 'hier') );

		$template = new Template(
			[
				'subject'           => sprintf( 'Nog te beoordelen projecten in %s', $term->name ),
				'message'           => $message,
				'show_signature'    => true,
				'signature_name'    => $supervisor->display_name
			]
		);

		$this->send_mail(
			$responsible_user,
			$supervisor, 
			sprintf( 'Nog te beoordelen projecten in %s', $term->name ),
			$template->generate()
		);
	}

	/** Zoekt coordinator voor import Groepsprojecten */
	protected function get_supervisor() : ?\WP_User {
		$workcamp_approval = siw_get_option( 'workcamp_approval' );
		if ( isset( $workcamp_approval['supervisor'] ) ) {
			$supervisor = get_userdata( $workcamp_approval['supervisor'] );
			return is_a( $supervisor, \WP_User::class ) ? $supervisor : null ;
		}
		return null;
	}

	/** Zoekt verantwoordelijke voor specifiek continent */
	protected function get_responsible_user( string $category_slug ) : ?\WP_User {
		$workcamp_approval = siw_get_option( 'workcamp_approval' );
		if ( isset( $workcamp_approval[ "responsible_{$category_slug}" ] ) ) {
			$responsible_user = get_userdata( $workcamp_approval[ "responsible_{$category_slug}"] );
			return is_a( $responsible_user, \WP_User::class ) ? $responsible_user : null ;
		}
		return null;
	}

	/** Verstuur e-mail */
	protected function send_mail( \WP_User $to, \WP_User $from, string $subject, string $message ) {

		$email = Email::create(
			$subject,
			$message,
			$to->user_email,
			$to->display_name
		)
		->set_content_type( Email::TEXT_HTML )
		->set_from( $from->user_email, $from->display_name );

		if ( $to != $from ) {
			$email->add_cc( $from->user_email, $from->display_name );
		}
		$email->send();
	}
}
