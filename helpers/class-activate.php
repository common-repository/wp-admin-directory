<?php
/**
 * Run on activation
 *
 * @since  1.0.0
 */
class WP_Admin_Dir_Activate {
    /**
     * Class Constructor
     *
     * @since  1.0.0
     */
    public function __construct() {
        // Activate plugin when new blog is added
        add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );
    }
    /**
     * Fired when the plugin is activated.
     *
     * @param boolean $network_wide True if WPMU superadmin uses "Network Deactivate" action
     * @since  1.0.0
     */
    public static function activate( $network_wide ) {
        if ( function_exists( 'is_multisite' ) && is_multisite() ) {
            if ( $network_wide  ) {
                // Get all blog ids
                $blog_ids = WP_Admin_Dir_Get_Blog_IDs::get_blog_ids();
                foreach ( $blog_ids as $blog_id ) {
                    switch_to_blog( $blog_id );
                    self::single_activate();
                }
                restore_current_blog();
            } else {
                self::single_activate();
            }
        } else {
            self::single_activate();
        }
    }
    /**
     * Fired when a new site is activated with a WPMU environment
     *
     * @param int $blog_id ID of the new blog
     * @since  1.0.0
     */
    public function activate_new_site( $blog_id ) {
        if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
            return;
        }
        switch_to_blog( $blog_id );
        self::single_activate();
        restore_current_blog();
    }
    /**
     * Fired for each blog when the plugin is activated
     *
     * @since  1.0.0
     */
    private static function single_activate() {
        WP_Admin_Dir_WP_Version_Check::activation_check('3.7');
    }
}