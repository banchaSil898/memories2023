<?php
/**
 * Created by PhpStorm.
 * User: Pai
 * Date: 6/23/2016
 * Time: 4:29 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once( UD_REF_TO_MAGAZINE_BASE_PATH . 'admin/inc/class-ud-ref-to-magazine-meta.php' );
require_once( UD_REF_TO_MAGAZINE_BASE_PATH . 'admin/inc/class-ud-ref-to-magazine-ajax.php' );

class UDRefToMagazineAdmin {
    /**
     * Maintain singleton.
     * @var $instance
     */
    private static $instance;


    /**
     * Returns the instance of the class [Singleton]
     * @return UDRefToMagazineAdmin
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        if ( is_admin() ) {
            UDRefToMagazineMeta::get_instance();
            UDRefToMagazineAjax::get_instance();
        }
    }

    public function enqueue_scripts( $hook ) {
        if ( 'post.php' == $hook || 'post-new.php' == $hook ) {
            wp_enqueue_script( 'ud-ref-to-magazine', UD_REF_TO_MAGAZINE_URL_PATH . 'admin/js/ud-ref-to-magazine.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-autocomplete'  ) );

            wp_enqueue_style( 'ud-ref-to-magazine', UD_REF_TO_MAGAZINE_URL_PATH . 'admin/css/ud-ref-to-magazine.css' );

            wp_localize_script( 'ud-ref-to-magazine', 'udRefToMagazineAjax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
        }
    }

   
}