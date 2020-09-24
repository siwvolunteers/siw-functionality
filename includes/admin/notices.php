<?php declare(strict_types=1);

namespace SIW\Admin;

/**
 * Admin notices
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Notices {

	/**
	 * Transient naam
	 *
	 * @var string $transient_name
	 */
	protected $transient_name;

	/**
	 * Toegestane notice types
	 *
	 * @var array
	 */
	protected $types = [ 
		'success',
		'info',
		'warning',
		'error'
	];

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_transient_name();
	}

	/**
	 * Zet de gebruikersspecifieke naam van de tansient
	 */
	protected function set_transient_name() {
		$this->transient_name = 'siw_admin_notices_' . get_current_user_id(); 
	}

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'admin_notices', [ $self, 'display_notices' ] );
	}

	/**
	 * Toont admin notices
	 */
	public function display_notices() {

		$notices = $this->get_notices();
		if ( ! is_array( $notices ) ) {
			return;
		}

		foreach ( $notices as $notice ) {
			$dismissable = ( $notice['dismissable'] ) ? ' is-dismissible' : '';
			?>
				<div class="notice notice-<?= esc_attr( $notice['type'] ); ?> <?= esc_attr( $dismissable );?>">
					<p><?= esc_html( $notice['message'] ); ?></p>
				</div>
			<?php
		}
		$this->clear_notices();
	}

	/**
	 * Voegt admin notice toe
	 *
	 * @param string $type success|info|error|warning
	 * @param string $message
	 * @param bool $dismissable
	 */
	public function add_notice( string $type, string $message, bool $dismissable = false ) {

		$type = in_array( $type, $this->types ) ? $type : 'info';
		
		$notices = $this->get_notices();
		$notices[] = [
			'type'        => $type,
			'message'     => $message,
			'dismissable' => $dismissable,
		];
		$this->set_notices( $notices );
	}

	/**
	 * Verwijdert alle notices
	 */
	protected function clear_notices() {
		delete_transient( $this->transient_name );
	}

	/**
	 * Haalt notices op
	 * 
	 * @return array
	 */
	protected function get_notices() {
		return get_transient( $this->transient_name );
	}

	/**
	 * Slaat notices op
	 *
	 * @param array $notices
	 */
	protected function set_notices( array $notices ) {
		set_transient( $this->transient_name, $notices, 60 );
	}
}
