<?php
/**
 * Load the Text Domain for localization
 */
class WP_Admin_Dir_Load_Text_Domain {
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
     * @param string $prefix_slug The plugin slug
     * @since  1.0.0
     */
    public function __construct() {
        // Load plugin text domain
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
    }
    /**
     * Load the plugin text domain for translation
     *
     * @since  1.0.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain( WP_ADMIN_DIR_SLUG , false, plugin_dir_path( dirname( dirname( dirname( __FILE__ ) ) ) ) . 'languages' );
    }
}