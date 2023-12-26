<?php
/**
 * Created by PhpStorm.
 * User: Pai
 * Date: 6/23/2016
 * Time: 4:24 PM
 */
class UDRefToMagazineAjax {
    /**
     * Maintain singleton.
     * @var $instance
     */
    private static $instance;


    /**
     * Returns the instance of the class [Singleton]
     * @return UDRefToMagazineAjax
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        add_action( 'wp_ajax_ud_search_magazine_column', array($this,'search_magazine_column') );

    }
    
    public function search_magazine_column(){
        
//        if ( ! isset( $_GET['tax'] ) ) {
//            wp_die( 0 );
//        }
//
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_die();
        }

        $taxonomy = UDRefToMagazine::$taxonomy_name;
       
        
        $s = wp_unslash( $_GET['term'] );
        $s = trim( $s );
//
//        /**
//         * Filter the minimum number of characters required to fire a tag search via AJAX.
//         *
//         * @since 4.0.0
//         *
//         * @param int    $characters The minimum number of characters required. Default 2.
//         * @param object $tax        The taxonomy object.
//         * @param string $s          The search term.
//         */
        $term_search_min_chars = (int) apply_filters( 'term_search_min_chars', 2, $taxonomy, $s );
//
//        /*
//         * Require $term_search_min_chars chars for matching (default: 2)
//         * ensure it's a non-negative, non-zero integer.
//         */
        if ( ( $term_search_min_chars == 0 ) || ( strlen( $s ) < $term_search_min_chars ) ){
            wp_die();
        }

        $results = get_terms( $taxonomy, array( 'name__like' => $s, 'fields' => 'id=>name', 'hide_empty' => false ) );

        echo json_encode($results);
        wp_die();
    }
}