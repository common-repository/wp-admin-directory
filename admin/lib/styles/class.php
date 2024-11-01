<?php
/**
 * Load the Admin Styles
 *
 * @since  1.0.0
 */
class WP_Admin_Dir_Admin_Styles {
    private static $instance = null;
    /**
     * Creates or returns an instance of this class.
     *
     * @return  A single instance of this class.
     * @since  1.0.0
     */
    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    /**
     * Class Constructor
     *
     * @since  1.0.0
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
    }
    /**
     * Styles for the Functionlity Settings page
     *
     * @since  1.0.0
     */
    public function enqueue_styles( $hook ) {
        if( 'admin.php?page=wp_admin_dir_types' != $hook ) {
            wp_enqueue_style( 'wp-admin-dir', plugins_url( 'assets/css/wp-admin-dir.min.css', dirname( dirname( __FILE__ ) ) ), array(), WP_ADMIN_DIR_VERSION );
        }
    }
}