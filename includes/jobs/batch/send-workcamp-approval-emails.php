<?php declare(strict_types=1);

namespace SIW\Jobs\Batch;

use SIW\Attributes\Add_Action;
use SIW\Data\Job_Frequency;
use SIW\Helpers\Email;
use SIW\Helpers\Email_Template;
use SIW\Jobs\Scheduled_Job;
use SIW\Util\Links;
use SIW\WooCommerce\Import\Product as Import_Product;
use SIW\WooCommerce\Taxonomy_Attribute;

class Send_Workcamp_Approval_Emails extends Scheduled_Job {
	private const ACTION_HOOK = self::class;

	/** {@inheritDoc} */
	public function get_name(): string {
		return 'Versturen email goedkeuren groepsprojecten';
	}

	/** {@inheritDoc} */
	protected function get_frequency(): Job_Frequency {
		return Job_Frequency::DAILY;
	}

	public function start(): void {

		$data = get_terms(
			[
				'taxonomy'   => Taxonomy_Attribute::CONTINENT->value,
				'hide_empty' => true,
				'fields'     => 'tt_ids',
			]
		);

		if ( is_wp_error( $data ) ) {
			return;
		}

		$this->enqueue_items( $data, self::ACTION_HOOK );
	}

	#[Add_Action( self::ACTION_HOOK )]
	public function maybe_send_email( int $term_taxonomy_id ) {

		$term = get_term_by( 'term_taxonomy_id', $term_taxonomy_id );
		if ( ! is_a( $term, \WP_Term::class ) ) {
			return;
		}

		$products = siw_get_product_ids(
			[
				'continent' => $term->slug,
				'status'    => Import_Product::REVIEW_STATUS,
			]
		);
		if ( empty( $products ) ) {
			return false;
		}

		$supervisor = $this->get_supervisor();
		if ( is_null( $supervisor ) ) {
			return false; // TODO:logging
		}
		$responsible_user = $this->get_responsible_user( $term->slug ) ?? $supervisor;

		$admin_url = add_query_arg(
			[
				'post_type'                          => 'product',
				'post_status'                        => Import_Product::REVIEW_STATUS,
				Taxonomy_Attribute::CONTINENT->value => $term->slug,
			],
			admin_url( 'edit.php' )
		);

		$message =
			sprintf( 'Beste %s,', $responsible_user->user_firstname ) . BR2 .
			sprintf( 'Er wachten nog %d projecten in %s op jouw beoordeling.', count( $products ), $term->name ) . BR .
			sprintf( 'Klik %s om de projecten te bekijken.', Links::generate_link( $admin_url, 'hier' ) );

		$template = Email_Template::create()
			->set_message( $message )
			->set_subject( sprintf( 'Nog te beoordelen projecten in %s', $term->name ) )
			->generate();

		$this->send_mail(
			$responsible_user,
			$supervisor,
			sprintf( 'Nog te beoordelen projecten in %s', $term->name ),
			$template
		);
	}

	protected function get_supervisor(): ?\WP_User {
		$workcamp_approval = siw_get_option( 'workcamp_approval' );
		if ( isset( $workcamp_approval['supervisor'] ) ) {
			$supervisor = get_userdata( $workcamp_approval['supervisor'] );
			return is_a( $supervisor, \WP_User::class ) ? $supervisor : null;
		}
		return null;
	}

	protected function get_responsible_user( string $category_slug ): ?\WP_User {
		$workcamp_approval = siw_get_option( 'workcamp_approval' );
		if ( isset( $workcamp_approval[ "responsible_{$category_slug}" ] ) ) {
			$responsible_user = get_userdata( $workcamp_approval[ "responsible_{$category_slug}" ] );
			return is_a( $responsible_user, \WP_User::class ) ? $responsible_user : null;
		}
		return null;
	}

	protected function send_mail( \WP_User $to, \WP_User $from, string $subject, string $message ) {

		$email = Email::create()
			->set_subject( $subject )
			->set_message( $message )
			->add_recipient( $to->user_email, $to->display_name )
			->set_content_type( Email::TEXT_HTML )
			->set_from( $from->user_email, $from->display_name );

		if ( $to !== $from ) {
			$email->add_cc( $from->user_email, $from->display_name );
		}
		$email->send();
	}
}
