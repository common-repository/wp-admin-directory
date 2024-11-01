<div class="wrap">
    <?php do_action( 'wpad_before_listing_page_title' ); ?>
    <h2><?php _e( 'Directory', WP_ADMIN_DIR_SLUG ); ?></h2>
    <?php do_action( 'wpad_after_listing_page_title' ); ?>
    <?php do_action( 'wpad_before_listings' ); ?>
    <div id="poststuff">
        <?php
        global $menu, $submenu;
        WP_Admin_Dir_Admin_Menu_Url::get_menu_list($menu, $submenu);
        ?>
    </div>
    <?php do_action( 'wpad_after_listings' ); ?>
</div>