<?php
/**
 * Get the blogs ID's
 *
 * @since  1.0.0
 */
class WP_Admin_Dir_Get_Blog_IDs {
    /**
     * Class Constructor
     *
     * @since  1.0.0
     */
    public function __construct() {
    }
    /**
     * Get all blog ids of blogs in the current network
     *
     * @since  1.0.0
     */
    public static function get_blog_ids() {
        global $wpdb;
        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
            WHERE archived = '0' AND spam = '0'
            AND deleted = '0'";
        return $wpdb->get_col( $sql );

    }
}