<?php
/**
 * Plugin Name:       WP Admin Directory
 * Plugin URI:        http://wordpress.org/plugins/buddypress/
 * Description:       Group together multiple admin menu items under a single Driectory menu item
 * Version:           1.1.2
 * Author:            Jason Witt
 * Author URI:        http://jawittdesigns@gmail.com
 * Text Domain:       wp-admin-dir
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/jawittdesigns/WP-Admin-Directory
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
class WP_Admin_Dir_Init {
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
        // Required Files
        foreach( glob( plugin_dir_path( __FILE__ ) . 'helpers/*.php' ) as $files ){
            require_once( $files );
        }
        // Register Activation and Deactivation Hook
        register_activation_hook( __FILE__, array( 'WP_Admin_Dir_Activate', 'activate' ) );
        register_deactivation_hook( __FILE__, array( 'WP_Admin_Dir_Deactivate', 'deactivate' ) );
        // Load Admin Files
        if( is_admin() ) {
            require_once( plugin_dir_path( __FILE__ ) . 'admin/class-admin-init.php' );
            WP_Admin_Dir_Admin_Init::get_instance();
        }
        // Load Public Files
        require_once( plugin_dir_path( __FILE__ ) . 'public/class-public-init.php' );
        WP_Admin_Dir_Public_Init::get_instance();
        // Action Links
        add_filter( 'plugin_action_links', array( $this, 'add_action_links' ), 10, 2 );
    }
    /**
     * Add action links
     * 
     * @param array $links the current action links
     * @param string $file the name of the plugin
     * @since 1.0.0
     * @see http://pippinsplugins.com/customize-plugin-action-links/
     */
    public function add_action_links( $links, $file ) {
        static $this_plugin;
        if(!$this_plugin) {
            $this_plugin = plugin_basename( __FILE__ );
        } 
        if( $file == $this_plugin ) {
            $plugin_links[] = '<a href="' . get_admin_url(null, 'options-general.php?page=wp-admin-dir-settings') . '">Settings</a>';
            foreach( $plugin_links as $link ) {
                array_unshift( $links, $link );
            }
        }
        return $links;
    }
}
WP_Admin_Dir_Init::get_instance();