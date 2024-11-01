<?php
/**
 * Create the Admin menu
 *
 * filters -
 *     wpad_menu_lable
 *     wpad_page_title
 *     wpad_menu_icon
 *     wpad_menu_position
 *     wpad_settings_menu_lable
 *     wpad_settings_page_title
 *     wpad_settings_option_title
 *     wpad_settings_option_desc
 *     wpad_exclude_list
 *
 * actions -
 *     wpad_before_setting_page_title
 *     wpad_after_setting_page_title
 *     wpad_before_settings_form
 *     wpad_after_settings_form
 *
 * @since  1.0.0
 */
class WP_Admin_Dir_Admin_Menus {
    private static $instance = null;
    public $menu_lable;
    public $page_title;
    public $menu_icon;
    public $menu_position;
    public $settings_menu_lable;
    public $settings_page_title;
    public $settings_option_title;
    public $settings_option_desc;
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
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_menu', array( $this, 'add_settings_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'initialize_settings' ) );
        $this->menu_lable = apply_filters( 'wpad_menu_lable', __( 'Directory', WP_ADMIN_DIR_SLUG ) );
        $this->page_title = apply_filters( 'wpad_page_title', __( 'Directory', WP_ADMIN_DIR_SLUG ) );
        if ( version_compare( $GLOBALS['wp_version'], 3.8, '<' ) ) {
            $this->menu_icon = apply_filters( 'wpad_menu_icon', '' );
        } else {
            $this->menu_icon = apply_filters( 'wpad_menu_icon', 'dashicons-list-view' );
        }
        // use null to allow for cascading position
        $this->menu_position = apply_filters( 'wpad_menu_position', 3 );
        $this->settings_menu_lable = apply_filters( 'wpad_settings_menu_lable', __( 'Directory Settings', WP_ADMIN_DIR_SLUG ) );
        $this->settings_page_title = apply_filters( 'wpad_settings_page_title', __( 'WP Admin Directory Settings', WP_ADMIN_DIR_SLUG ) );
        $this->settings_option_title = apply_filters( 'wpad_settings_option_title', __( 'Menu Items', WP_ADMIN_DIR_SLUG ) );
        $this->settings_option_desc = apply_filters( 'wpad_settings_option_desc', __( 'Choose the menu items to include in the Directory menu item', WP_ADMIN_DIR_SLUG ) );
    }
    /**
     * The menu item for displaying the functionality details
     *
     * @since  1.0.0
     */
    public function add_admin_menu() {
        $this->plugin_screen_hook_suffix = add_menu_page( 
            __( $this->page_title, WP_ADMIN_DIR_SLUG ),
            __( $this->menu_lable, WP_ADMIN_DIR_SLUG ),
            'read', 
            WP_ADMIN_DIR_SLUG . '-directory', 
            array( $this, 'display_main_admin_page'),
            $this->menu_icon,
            $this->menu_position
        );

    }
    /**
     * Render the page
     *
     * @since  1.0.0
     */
    public function display_main_admin_page() {
        include( plugin_dir_path( __FILE__ ) . '../../views/types-display.php' );
    }
    /**
     * The menu item for displaying the functionality details
     *
     * @since  1.0.0
     */
    public function add_settings_admin_menu() {
        $this->plugin_screen_hook_suffix = add_submenu_page( 
            'options-general.php',
            $this->settings_page_title,
            $this->settings_menu_lable,
            'manage_options', 
            WP_ADMIN_DIR_SLUG . '-settings', 
            array( $this, 'display_settings_admin_page' )
        );

    }
    /**
     * Render the page
     *
     * @since  1.0.0
     */
    public function display_settings_admin_page() {
        ?>
        <div class="wrap">
            <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
            <form method="post" action="options.php" class="wpad-form">
                <?php
                settings_fields( 'wp_admin_dir_options' );
                do_settings_sections( WP_ADMIN_DIR_SLUG . '-settings' );
                submit_button();
                ?>
            </form>
            
        </div>
    <?php
    }

    public function initialize_settings(){
        if( !get_option( 'wp_admin_dir_options' ) ) {
            add_option( 'wp_admin_dir_options' );
        }
        add_settings_section(
            'wp_admin_dir_options',
            '',
            '',
            WP_ADMIN_DIR_SLUG . '-settings'
        );
        add_settings_field( 
            'wp_admin_dir_post_multicheckbox',
            $this->settings_option_title,
            array( $this, 'multicheck_callback'),
             WP_ADMIN_DIR_SLUG . '-settings',
            'wp_admin_dir_options'
        );
        register_setting(
            'wp_admin_dir_options',
            'wp_admin_dir_options'
        );
    }
    
    public function multicheck_callback() {
        $list_items = $this->return_menu_items();
        $counter = '';
        $checker = '';
        $types = get_option( 'wp_admin_dir_options' );
        $selected = '';
        if( !empty( $types ) ) {
            $selected = $types['wp_admin_dir_post_multicheckbox'];
        }
        echo '<ul>';
        foreach( $list_items as $key => $item ) {
            $counter++;
            if( get_option( 'wp_admin_dir_options' ) ) {
                $checker = ( ( array_search( $key , $selected ) !== FALSE ) ? 'checked="checked"' : '');
            }
            ?>
            <li>
                <input 
                    type="checkbox" 
                    class="cmb_option" 
                    name="wp_admin_dir_options[wp_admin_dir_post_multicheckbox][]" 
                    id="wp_admin_dir_post_multicheckbox<?php echo $counter; ?>" 
                    value="<?php echo $key; ?>" 
                    <?php echo $checker; ?>>
                <label for="wp_admin_dir_post_multicheckbox<?php echo $counter; ?>"><?php echo $item; ?></label>
            </li>
            <?php
        }
        echo '</ul>';
        echo '<p style="color: #AAA; font-style: italic;">'. $this->settings_option_desc .'</p>';
    }
    
    private function return_menu_items() {
        $menu = get_transient( '_transient_wpad_menu' );
        $list = array();
        // Items to exclude from settings checkbox list
        $excludes = array(
            'index.php',
            'wp-admin-dir-directory',
            'themes.php',
            'plugins.php',
            'users.php',
            'tools.php',
            'options-general.php',
        );
        if(has_filter('wpad_exclude_list')) {
            $excludes = apply_filters('wpad_exclude_list', $excludes);
        }
        foreach ( $menu  as $key => $item ) {
            if( !empty( $item[0] ) ){
                $list[$item[2]] = str_replace(range(0,9),'', $item[0] );
            }
            // remove item in the excludes array
            foreach( $excludes as $exclude) {
                if( $item[2] == $exclude) {
                    unset($list[$item[2]]);
                }
            }
        }
        return $list;
    }
}