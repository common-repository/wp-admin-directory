<?php
/**
 * Include Files
 *
 * @since  1.0.0
 */
class WP_Admin_Dir_Include_Files {
    private $parent_dir;
    private $included_dirs;
    private $included_files;
    private $excluded_list = array();
    private $excluded_files;
    /**
     * Class Constructor
     * 
     * @param string $dir  the directory to scan
     * @param array  $args the user defined arguments
     * @since  1.0.0
     */
    public function __construct( $dir, $args = array() ) {
        $defaults = array(
              'excluded_dirs'  => array(),
              'included_files' => array( '*.php' ),
              'excluded_files' => array(),
        );
        $args = array_merge( $defaults, $args );
        extract( $args );
        $this->parent_dir = $dir;
        $child_dirs = array_diff( scandir( $this->parent_dir ), array( '..', '.' ) );
        $this->included_dirs = array_diff( $child_dirs, $excluded_dirs );
        $this->included_files = $included_files;
        $this->excluded_list = '';
        $this->excluded_files = $excluded_files;
        $this->exclude_files();
        $this->include_files();
    }
    /**
     * Exlude files for the include list
     * 
     * @return array the excluded files
     * @since  1.0.0
     */
    private function exclude_files() {
        foreach( $this->excluded_files as $excluded_file ) {
            $this->excluded_list[] = $this->parent_dir . '/' . $excluded_file;
        }
    }
    /**
     * Include the files
     *
     * @since  1.0.0
     */
    private function include_files(){
        foreach( $this->included_dirs as $included_dir ){
            foreach( $this->included_files as $included_file ) {
                foreach( glob( $this->parent_dir . '/' . $included_dir . '/' . $included_file ) as $files ) {
                    if( !empty( $this->excluded_files ) ) {
                        if ( !in_array( $files, $this->excluded_list ) ) { 
                            require_once( $files );
                        }
                    } else {
                        require_once( $files );
                    }
                }
            }
        }
    }
}