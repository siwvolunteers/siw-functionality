<?php declare(strict_types=1);

namespace SIW\Interfaces\Forms;

interface Export_To_Mailjet {

	public function get_mailjet_list_id( \WP_REST_Request $request ): ?int;

	public function get_mailjet_properties( \WP_REST_Request $request ): array;
}
