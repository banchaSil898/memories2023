<?php
/**
 * Created by PhpStorm.
 * User: Pai
 * Date: 7/27/2016
 * Time: 11:37 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'UD_REF_TO_MAGAZINE_BASE_PATH', trailingslashit( dirname( __FILE__ ) ) );
define( 'UD_REF_TO_MAGAZINE_URL_PATH', trailingslashit(get_template_directory_uri().'/includes/ud_ref_to_magazine') );


require_once( UD_REF_TO_MAGAZINE_BASE_PATH . 'inc/class-ud-ref-to-magazine.php' );

$plugins = UDRefToMagazine::get_instance();