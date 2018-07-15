<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/** 
 * Class om Transient Notices te genereren
 * 
 * Gebaseerd op: Iconic_Transient_Notices (https://github.com/iconicwp/wordpress-transient-notices)
 */
class SIW_Transient_Notices {

    /**
     * Transient naam
     *
     * @access protected
     * @var string $transient_name
     */
    protected $transient_name = 'siw_transient_notices';

    /**
     * Construct the transient notices class
     */
    public function __construct() {

        add_action( 'admin_notices', array( $this, 'display_notices' ) );
        $this->transient_name .= '_' . get_current_user_id(); 
    }

    /**
     *  Admin notificaties weergeven
     */
    public function display_notices() {

        if ( $notices = get_transient( $this->transient_name ) ) { ?>

            <?php foreach( $notices as $notice ) { ?>

                <?php $dismissable = ( $notice->dismissable ) ? ' is-dismissible' : ''  ?>

                <div class="notice notice-<?php echo esc_attr( $notice->type ); ?> <?php echo esc_attr( $dismissable );?>">
                    <p><?php echo esc_html( $notice->message ); ?></p>
                </div>

            <?php } ?>

            <?php delete_transient( $this->transient_name );

        }

    }

    /**
     * Toevoegen admin notice
     *
     * @param string $type success|info|error|warning
     * @param string $message
     * @param bool $dismissable
     */
    public function add_notice( $type = false, $message = false, $dismissable = false ) {

        if ( ! $type || ! $message )
            return;

        $notices = get_transient( $this->transient_name );
        $notices = $notices ? $notices : array();
        
        $notice = $location = new stdClass();
        $notice->type = $type;
        $notice->message = $message;
        $notice->dismissable = $dismissable;

        $notices[] = $notice;
            

        set_transient( $this->transient_name, $notices, 60 );

    }

}

add_action( 'admin_init', function() {
    new SIW_Transient_Notices;
});