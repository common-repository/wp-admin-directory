<?php
/**
 * Run on Deactivation
 *
 * @since  1.0.0
 */
class WP_Admin_Dir_Deactivate {
    /**
     * Class Constructor
     *
     * @since  1.0.0
     */
    public function __construct() {
    }
    /**
     * Fired when the plugin is deactivated
     *
     * @param boolean $network_wide True if WPMU superadmin uses "Network Deactivate" action
     * @since  1.0.0
     */
    public static function deactivate( $network_wide ) {
        if ( function_exists( 'is_multisite' ) && is_multisite() ) {
            if ( $network_wide ) {
                // Get all blog ids
                $blog_ids = WP_Admin_Dir_Get_Blog_IDs::get_blog_ids();
                foreach ( $blog_ids as $blog_id ) {
                    switch_to_blog( $blog_id );
                    self::single_deactivate();
                }
                restore_current_blog();
            } else {
                self::single_deactivate();
            }
        } else {
            self::single_deactivate();
        }
    }
    /**
     * Fired for each blog when the plugin is deactivated
     *
     * @since  1.0.0
     */
    private static function single_deactivate() {
        self::flush_rewrite_rules();
    }
    /**
     * Flush rewrite rules for new Custom Post Types
     *
     * @since  1.0.0
     */
    public static function flush_rewrite_rules() {
        flush_rewrite_rules( false );
    }
}
