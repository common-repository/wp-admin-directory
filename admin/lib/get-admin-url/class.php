<?php
/**
 * Create the Admin menu
 *
 * filters -
 *     wpad_listing_heading
 *     wpad_wrapper_classes
 *     wpad_listing_heading
 *     wpad_listing_heading_link
 *     wpad_item_list
 *     wpad_list_item
 *     wpad_list_item_link
 *
 * actions -
 *     wpad_before_listing_box
 *     wpad_after_listing_box
 *     wpad_before_listing_heading
 *     wpad_after_listing_heading
 *     wpad_before_listing_list
 *     wpad_before_listing_item
 *     wpad_after_listing_list
 *     wpad_after_listing_item
 *
 * @since  1.0.0
 */
class WP_Admin_Dir_Admin_Menu_Url {
    /**
     * Get the URL of an admin menu item
     *
     * @param   string $menu_item_file admin menu item file
     * @param   boolean $submenu_as_parent
     * @return  string URL of admin menu item, NULL if the menu item file can't be found in $menu or $submenu
     *
     * @since  1.0.0
     * @see  wp-admin/menu-header.php
     */
    public static function get_menu_list( $menu_item_file, $submenu_as_parent = true ) {
        global $self, $parent_file, $submenu_file, $plugin_page, $typenow, $submenu;
        $menu = get_transient( '_transient_wpad_menu' );
        $types = get_option( 'wp_admin_dir_options' );
        $includes = $types['wp_admin_dir_post_multicheckbox'];
        // 0 = menu_title, 1 = capability, 2 = menu_slug, 3 = page_title, 4 = classes, 5 = hookname, 6 = icon_url
        foreach ( $menu as $key => $item ) {
            $the_item[] = $item[2];
            $admin_is_parent = false;
            $aria_attributes = '';
            $submenu_items = false;
            if ( ! empty( $submenu[$item[2]] ) ) {
                $submenu_items = $submenu[$item[2]];
            }
            // wrapper classes
            $wrapper_classes = array();
            $wrapper_classes[] = 'postbox';
            $wrapper_classes[] = 'wpad_listing';
            if(has_filter('wpad_wrapper_classes')) {
                $wrapper_classes = apply_filters('wpad_wrapper_classes', $wrapper_classes);
            }
            $wrapper_classes = $wrapper_classes ? ' class="' . join( ' ', $wrapper_classes ) . '"' : '';
            // heading classes
            $heading_classes = array();
            $heading_classes[] = 'wpad_list_heading';
            if(has_filter('wpad_listing_heading')) {
                $heading_classes = apply_filters('wpad_listing_heading', $heading_classes);
            }
            $heading_classes = $heading_classes ? ' class="' . join( ' ', $heading_classes ) . '"' : '';
            // heading link classes
            $heading_link_classes = array();
            $heading_link_classes[] = 'wpad_heading_link';
            if(has_filter('wpad_listing_heading_link')) {
                $heading_link_classes = apply_filters('wpad_listing_heading_link', $heading_link_classes);
            }
            $heading_link_classes = $heading_link_classes ? ' class="' . join( ' ', $heading_link_classes ) . '"' : '';
            // sub-item list
            $sub_item_list = array();
            $sub_item_list[] = 'inside';
            $sub_item_list[] = 'wpad_sub_item_list';
            if(has_filter('wpad_item_list')) {
                $sub_item_list = apply_filters('wpad_item_list', $sub_item_list);
            }
            $sub_item_list = $sub_item_list ? ' class="' . join( ' ', $sub_item_list ) . '"' : '';
            // sub-item list item
            $sub_item_list_item = array();
            $sub_item_list_item[] = 'wpad_sub_item';
            if(has_filter('wpad_list_item')) {
                $sub_item_list_item = apply_filters('wpad_list_item', $sub_item_list_item);
            }
            $sub_item_list_item = $sub_item_list_item ? ' class="' . join( ' ', $sub_item_list_item ) . '"' : '';
            // sub-item link
            $sub_item_link = array();
            $sub_item_link[] = 'wpad_sub_item_link';
            if(has_filter('wpad_list_item_link')) {
                $sub_item_link = apply_filters('wpad_list_item_link', $sub_item_link);
            }
            $sub_item_link = $sub_item_link ? ' class="' . join( ' ', $sub_item_link ) . '"' : '';
            $title = wptexturize( $item[0] );
            if( isset( $includes ) ) {
                foreach( $includes as $include ) {
                    if( isset( $item[2]) && $include == $item[2] ) {
                        do_action( 'wpad_before_listing_box' );
                        echo "\n\t<div " . $wrapper_classes . ">";
                        do_action( 'wpad_before_listing_heading' );
                        if ( $submenu_as_parent && ! empty( $submenu_items ) ) {
                            $submenu_items = array_values( $submenu_items );  // Re-index.
                            $menu_hook = get_plugin_page_hook( $submenu_items[0][2], $item[2] );
                            $menu_file = $submenu_items[0][2];
                            if ( false !== ( $pos = strpos( $menu_file, '?' ) ) )
                                $menu_file = substr( $menu_file, 0, $pos );
                            if ( ! empty( $menu_hook ) || ( ( 'index.php' != $submenu_items[0][2] ) && file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! file_exists( ABSPATH . "/wp-admin/$menu_file" ) ) ) {
                                $admin_is_parent = true;
                                echo "<h3 " . $heading_classes . "><a href='admin.php?page={$submenu_items[0][2]}' " . $heading_link_classes . ">" . trim(str_replace(range(0,9),'',$title)) . "</a></h3>";
                            } else {
                                echo "\n\t<h3 " . $heading_classes . "><a href='{$submenu_items[0][2]}'" . $heading_link_classes . ">" . trim(str_replace(range(0,9),'',$title)) . "</a></h3>";
                            }
                        } elseif ( ! empty( $item[2] ) && current_user_can( $item[1] ) ) {
                            $menu_hook = get_plugin_page_hook( $item[2], 'admin.php' );
                            $menu_file = $item[2];
                            if ( false !== ( $pos = strpos( $menu_file, '?' ) ) ) {
                                $menu_file = substr( $menu_file, 0, $pos );
                            }
                            if ( ! empty( $menu_hook ) || ( ( 'index.php' != $item[2] ) && file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! file_exists( ABSPATH . "/wp-admin/$menu_file" ) ) ) {
                                $admin_is_parent = true;
                                echo "\n\t<h3 " . $heading_classes . "><a href='admin.php?page={$item[2]}'" . $heading_link_classes . ">" . trim(str_replace(range(0,9),'',$title)) . "</a></h3>";
                            } else {
                                echo "\n\t<h3 " . $heading_classes . "><a href='{$item[2]}'" . $heading_link_classes . ">" . trim(str_replace(range(0,9),'',$title)) . "</a></h3>";
                            }
                            echo "\n\t<ul class='inside'>";
                            if ( ! empty( $menu_hook ) || ( ( 'index.php' != $item[2] ) && file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! file_exists( ABSPATH . "/wp-admin/$menu_file" ) ) ) {
                                $admin_is_parent = true;
                                echo "\n\t<li><a href='admin.php?page={$item[2]}'>" . trim(str_replace(range(0,9),'',$title)) . "</a></li>";
                            } else {
                                echo "\n\t<li><a href='{$item[2]}'>" . trim(str_replace(range(0,9),'',$title)) . "</a></li>";
                            }
                        }
                        do_action( 'wpad_after_listing_heading' );
                        do_action( 'wpad_before_listing_list' );
                        if ( ! empty( $submenu_items ) ) {
                            echo "\n\t<ul " . $sub_item_list . ">";
                            do_action( 'wpad_before_listing_item' );
                            foreach ( $submenu_items as $sub_key => $sub_item ) {
                                if ( ! current_user_can( $sub_item[1] ) )
                                    continue;
                                $menu_file = $item[2];
                                if ( false !== ( $pos = strpos( $menu_file, '?' ) ) ){
                                    $menu_file = substr( $menu_file, 0, $pos );
                                }
                                // Handle current for post_type=post|page|foo pages, which won't match $self.
                                $self_type = ! empty( $typenow ) ? $self . '?post_type=' . $typenow : 'nothing';
                                $menu_hook = get_plugin_page_hook($sub_item[2], $item[2]);
                                $sub_file = $sub_item[2];
                                if ( false !== ( $pos = strpos( $sub_file, '?' ) ) ){
                                    $sub_file = substr($sub_file, 0, $pos);
                                }
                                $title = wptexturize($sub_item[0]);
                                if ( ! empty( $menu_hook ) || ( ( 'index.php' != $sub_item[2] ) && file_exists( WP_PLUGIN_DIR . "/$sub_file" ) && ! file_exists( ABSPATH . "/wp-admin/$sub_file" ) ) ) {
                                    // If admin.php is the current page or if the parent exists as a file in the plugins or admin dir
                                    if ( ( ! $admin_is_parent && file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! is_dir( WP_PLUGIN_DIR . "/{$item[2]}" ) ) || file_exists( $menu_file ) )
                                        $sub_item_url = add_query_arg( array( 'page' => $sub_item[2] ), $item[2] );
                                    else
                                        $sub_item_url = add_query_arg( array( 'page' => $sub_item[2] ), 'admin.php' );

                                    $sub_item_url = esc_url( $sub_item_url );
                                    echo "<li " . $sub_item_list_item . "><a href='$sub_item_url' " . $sub_item_link . ">$title</a></li>";
                                } else {
                                    echo "<li " . $sub_item_list_item . "><a href='{$sub_item[2]}'" . $sub_item_link . ">$title</a></li>";
                                }
                            }
                            do_action( 'wpad_after_listing_item' );
                            echo "</ul>";
                        }
                        do_action( 'wpad_after_listing_list' );
                        echo "</div>";
                        do_action( 'wpad_after_listing_box' );
                    }
                }
            }
        }
    }
}
