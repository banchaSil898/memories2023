<?php
/**
 * Created by PhpStorm.
 * User: Pai
 * Date: 7/28/2016
 * Time: 12:09 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once( UD_REF_TO_MAGAZINE_BASE_PATH . 'admin/class-ud-ref-to-magazine-admin.php' );

class UDRefToMagazine {
    /**
     * Maintain singleton.
     * @var $instance
     */
    private static $instance;

    public static $magazine_info_key = 'ud_magazine_info';
    public static $taxonomy_name = 'ud_magazine_column';
    public static $taxonomy_slug = 'magazine-column';

    /**
     * Returns the instance of the class [Singleton]
     * @return UDRefToMagazine
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        UDRefToMagazineAdmin::get_instance();

        add_action( 'init', array( $this, 'taxonomy_init' ) );
    }

    public function taxonomy_init() {

        $labels = array(
            'name'                       => _x( 'Magazine Columns', 'Taxonomy General Name', 'ud_ref_to_magazine' ),
            'singular_name'              => _x( 'Magazine Column', 'Taxonomy Singular Name', 'ud_ref_to_magazine' ),
            'menu_name'                  => __( 'Magazine Column', 'ud_ref_to_magazine' ),
            'all_items'                  => __( 'All Items', 'ud_ref_to_magazine' ),
            'parent_item'                => __( 'Parent Item', 'ud_ref_to_magazine' ),
            'parent_item_colon'          => __( 'Parent Item:', 'ud_ref_to_magazine' ),
            'new_item_name'              => __( 'New Item Name', 'ud_ref_to_magazine' ),
            'add_new_item'               => __( 'Add New Item', 'ud_ref_to_magazine' ),
            'edit_item'                  => __( 'Edit Item', 'ud_ref_to_magazine' ),
            'update_item'                => __( 'Update Item', 'ud_ref_to_magazine' ),
            'view_item'                  => __( 'View Item', 'ud_ref_to_magazine' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'ud_ref_to_magazine' ),
            'add_or_remove_items'        => __( 'Add or remove items', 'ud_ref_to_magazine' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'ud_ref_to_magazine' ),
            'popular_items'              => __( 'Popular Items', 'ud_ref_to_magazine' ),
            'search_items'               => __( 'Search Items', 'ud_ref_to_magazine' ),
            'not_found'                  => __( 'Not Found', 'ud_ref_to_magazine' ),
            'no_terms'                   => __( 'No items', 'ud_ref_to_magazine' ),
            'items_list'                 => __( 'Items list', 'ud_ref_to_magazine' ),
            'items_list_navigation'      => __( 'Items list navigation', 'ud_ref_to_magazine' ),
        );
        $rewrite = array(
            'slug'                       => self::$taxonomy_slug,
            'with_front'                 => true,
            'hierarchical'               => false,
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_in_quick_edit'         => false,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => $rewrite,
        );
        register_taxonomy( self::$taxonomy_name, array( 'post' ), $args );

    }

    public static function get_magazine_ref($post_id){
        $ud_ref_to_magazine_info = get_post_meta( $post_id, UDRefToMagazine::$magazine_info_key, true );

        $ref_string = "";
        $date_str_arr = array();

        $ref_string = "มติชนสุดสัปดาห์ ฉบับวันที่";

        $same_str = "";
        $range_str = "";

        if ( ! empty($ud_ref_to_magazine_info['start_date']) ) {
            $startdate_arr = explode( '/', mysql2date( 'j/F/Y', $ud_ref_to_magazine_info['start_date']) );

            if( 'th' === get_locale()){
                $startdate_arr[2] =  (string)(intval($startdate_arr[2])+543);
            }
        }else{
            return '';
        }

        if ( ! empty($ud_ref_to_magazine_info['end_date'])) {
            $enddate_arr = explode( '/', mysql2date( 'j/F/Y', $ud_ref_to_magazine_info['end_date']) );

            if( 'th' === get_locale()){
                $enddate_arr[2] =  (string)(intval($enddate_arr[2])+543);
            }

            for ($i = count($startdate_arr)-1; $i >= 0 ; $i--) {
                if($startdate_arr[$i] === $enddate_arr[$i]) {
                    $same_str = ' '.$startdate_arr[$i].$same_str ;
                    unset($startdate_arr[$i]);
                    unset($enddate_arr[$i]);
                } else {
                    break;
                }
            }
            $range_str .= implode(' ',$startdate_arr) . " - " . implode(' ',$enddate_arr);

        }else {
            $same_str .= implode(' ',$startdate_arr);
        }

        $ref_string .= " ".$range_str.$same_str;

        return $ref_string;


    }

    public static function get_magazine_column_term($post_id){
        $column_term = null;
        $existing_terms = wp_get_object_terms( $post_id, UDRefToMagazine::$taxonomy_name );
        if ( ! empty($existing_terms[0])) {
            $column_term = $existing_terms[0];
        }

        return $column_term;
    }


}