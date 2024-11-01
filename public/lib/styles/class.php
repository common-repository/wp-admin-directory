<?php
/**
 * Load the Public styles
 *
 * @since  1.0.0
 */
class WP_Admin_Dir_Public_Styles {
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
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
    }
    /**
     * Enqueue the stylesheets
     *
     * @since  1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style( WP_ADMIN_DIR_SLUG, plugins_url( 'assets/css/wp-admin-dir-public.min.css', dirname( dirname( __FILE__ ) ) ), array(), WP_ADMIN_DIR_VERSION );
    }
}