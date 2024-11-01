<?php
/**
 * Create the Admin menu
 *
 * @since  1.0.0
 */
class WP_Admin_Dir_Remove_Menus {
    private static $instance = null;
    protected static $key = 'wp_admin_dir_options';
    protected static $plugin_options = array();
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
        add_action( 'admin_head', array($this, 'remove_menu'), 999 );
        add_action( 'admin_init', array( $this, 'reset_trans' ), 10, 2 );
    }
    /**
     * Reset the transient
     *
     * @since  1.0.0
     */
    public function reset_trans(){
        global $menu;
        delete_transient( '_transient_wpad_menu' );
        set_transient( '_transient_wpad_menu', $menu, 3600 );
    }
    /**
     * Remove the menu items from menu sidebar
     *
     * @since  1.0.0
     */
    public function remove_menu() {
        global $menu;
        $types = get_option( 'wp_admin_dir_options' );
        $selected = '';
        if( !empty( $types ) ) {
            $selected = $types['wp_admin_dir_post_multicheckbox'];
        }
        $check = array();
        if( $selected ) {
            foreach( $menu as $key => $item ) {
                foreach( $selected as $value ) {
                    if( $item[2] == $value ){
                        $check[] = $item[2];
                    }
                }
            }
        }
        // add the second seperator to the $check array
        $check[] = 'separator2';
        // stop updating $menu
        end( $menu );
        // check the last version of $menu
        while( prev( $menu ) ){
            foreach( $check as $value ){
                remove_menu_page( $value );
            }
        }
    }
}