<?php
/**
 * Load the Text Domain for localization
 *
 * filters -
 *     wpad_admin_bar_icon
 *     wpad_admin_bar_name
 *
 * @since  1.0.0
 */
class WP_Admin_Dir_Admin_Bar_Link {
    private static $instance = null;
    public $icon;
    public $name;
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
        add_action( 'admin_bar_menu', array( $this, 'add_to_admin_bar' ), 50 );
        if ( version_compare( $GLOBALS['wp_version'], 3.8, '<' ) ) {
            $this->icon = apply_filters( 'wpad_admin_bar_icon', '' );
        } else {
            $this->icon = apply_filters( 'wpad_admin_bar_icon', '<div class="dashicons dashicons-list-view"></div>' );
        }
        $this->name = apply_filters( 'wpad_admin_bar_name', 'Directory' );
    }
    /**
     * Add Directory link to the admin bar
     *
     * @since  1.0.0
     */
    public function add_to_admin_bar(){
        global $wp_admin_bar;
        $url = get_admin_url();
        if ( !is_super_admin() || !is_admin_bar_showing() ){
            return;
        }
        $wp_admin_bar->add_node( array(
        'id'   => WP_ADMIN_DIR_SLUG . '-directory',
        'meta'  => false,
        'title' => $this->icon . $this->name,
        'href' => $url . 'admin.php?page=wp-admin-dir-directory' ) );
    }
}