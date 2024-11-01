<?php
/**
 * Load and Initalize Admin classes
 *
 * @since  1.0.0
 */
class WP_Admin_Dir_Admin_Init {
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
    private function __construct() {
        // Load Library 
        $args = array(
            'included_files' => array( 'class.php', 'wrapper.php' ),
        );
        $wp_admin_dir_include_files = new WP_Admin_Dir_Include_Files( plugin_dir_path( __FILE__ ) . 'lib', $args );
        WP_Admin_Dir_Admin_Styles::get_instance();
        WP_Admin_Dir_Admin_Menus::get_instance();
        WP_Admin_Dir_Remove_Menus::get_instance();
    }
}