<?php
/**
 * Created by PhpStorm.
 * User: Pai
 * Date: 7/28/2016
 * Time: 12:02 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class UDRefToMagazineMeta {
    /**
     * Maintain singleton.
     * @var $instance
     */
    private static $instance;


    /**
     * Returns the instance of the class [Singleton]
     * @return UDRefToMagazineMeta
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        if ( is_admin() ) {
            add_action( 'load-post.php', array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        }
    }

    public function init_metabox() {

        add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
        add_action( 'save_post', array( $this, 'save_metabox' ), 10, 2 );
        add_action( 'add_meta_boxes_book' , 'remove_taxonomies_metaboxes' );

    }

    public function add_metabox() {

        add_meta_box(
            'ud_ref_to_magazine',
            __( 'Ref to Magazine', 'ud_ref_to_magazine' ),
            array( $this, 'render_metabox' ),
            'post',
            'side',
            'default'
        );

        // remove default metabox
        remove_meta_box('tagsdiv-'.UDRefToMagazine::$taxonomy_name,'post','side');

    }

    public function render_metabox( $post ) {

        // Add nonce for security and authentication.
        wp_nonce_field( 'ud_ref_to_magazine_info_nonce_action', 'ud_ref_to_magazine_info_nonce' );

        $ud_ref_to_magazine_info = get_post_meta( $post->ID, UDRefToMagazine::$magazine_info_key, true );

        $startdate = '';
        $enddate = '';
        if ( is_array( $ud_ref_to_magazine_info ) ) {
            if (! empty ($ud_ref_to_magazine_info['start_date']) ){
                $startdate = mysql2date('d/m/Y', $ud_ref_to_magazine_info['start_date']);
            }

            if (! empty ($ud_ref_to_magazine_info['end_date']) ){
                $enddate = mysql2date('d/m/Y', $ud_ref_to_magazine_info['end_date']);
            }
        }

        $column_name = '';
        $existing_terms = wp_get_object_terms( $post->ID, UDRefToMagazine::$taxonomy_name );
        if ( ! empty($existing_terms[0])) {
            $column_name = $existing_terms[0]->name;
        }


        ?>

        <div class="ud-ref-to-magazine-box">
            <h4><?php echo __( 'Start Date', 'ud_ref_to_magazine' ) ?></h4>
            <p><input type="text" id="ud_ref_to_magazine_info_start_date" name="ud_ref_to_magazine_info[start_date]"
                      class="ud_ref_to_magazine_info_start_date_field"
                      placeholder="<?php echo esc_attr__( '', 'ud_ref_to_magazine' ) ?>"
                      value="<?php echo esc_attr( $startdate ) ?>"></p>
            <h4><?php echo __( 'End Date', 'ud_ref_to_magazine' ) ?></h4>
            <p><input type="text" id="ud_ref_to_magazine_info_end_date" name="ud_ref_to_magazine_info[end_date]"
                      class="ud_ref_to_magazine_info_end_date_field"
                      placeholder="<?php echo esc_attr__( '', 'ud_ref_to_magazine' ) ?>"
                      value="<?php echo esc_attr( $enddate ) ?>"></p>
            <h4><?php echo __( 'Magazine Column', 'ud_ref_to_magazine' ) ?></h4>
            <p class="hide-if-js">
                <input type="text" name="ud_ref_to_magazine_info[column_name]" class="ud_ref_to_magazine_info-input"
                       id="ud_ref_to_magazine_info_column_name"
                       aria-describedby="ud_ref_to_magazine_info-desc" value="<?php echo esc_attr( $column_name ) ?>"/>
            </p>
            <p class="hide-if-no-js">
                <input type="text"
                       class="ud_ref_to_magazine_info-fake-input" autocomplete="off"
                       aria-describedby="ud_ref_to_magazine_info-desc"
                       value=""/>
            </p>
            <p class="howto" id="ud_ref_to_magazine_info-desc">Choose a Magazine column</p>
            <p class="ud_ref_to_magazine_info_chosen">
                <span><?php echo esc_html( $column_name )?></span>
            </p>
            <p class="hide-if-no-js">
                <a id="ud_ref_to_magazine_info_del" href="#" tabindex="0">Delete Magazine Column</a>
            </p>
        </div>


        <?php

    }

    // After post save, making relationship with columnist tag
    public function save_metabox( $post_id, $post ) {

        // Add nonce for security and authentication.
        $nonce_name = isset( $_POST['ud_ref_to_magazine_info_nonce'] ) ? $_POST['ud_ref_to_magazine_info_nonce'] : '';
        $nonce_action = 'ud_ref_to_magazine_info_nonce_action';

        // Check if a nonce is set.
        if ( ! isset( $nonce_name ) )
            return $post_id;

        // Check if a nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
            return $post_id;

        // Check if the user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) )
            return $post_id;

        $taxonomy = UDRefToMagazine::$taxonomy_name;
        $magazine_column_name = isset( $_POST['ud_ref_to_magazine_info']['column_name'] ) ? sanitize_text_field( $_POST['ud_ref_to_magazine_info']['column_name'] ) : '';

        $existing_terms = wp_get_object_terms( $post_id, $taxonomy );

        if ( ! empty( $magazine_column_name ) ) {
            $term = get_term_by( 'name', $magazine_column_name, $taxonomy );
            // check if term already exist?
            if ( empty ( $term ) ) {
                return $post_id;
            }

            // bind term
            $result = wp_set_object_terms( $post_id, $term->term_id, $taxonomy );
            if ( is_wp_error( $result ) ) {
                error_log( $result->get_error_message() );

                return $post_id;
            }

        } elseif ( ! empty( $existing_terms[0] ) ) {
            wp_remove_object_terms( $post_id, $existing_terms[0]->term_id, $taxonomy );
        }

        //save start date and end date
        $magazine_start_date_gmt = isset( $_POST['ud_ref_to_magazine_info']['start_date'] ) ? $this->sanitized_date( $_POST['ud_ref_to_magazine_info']['start_date'] ) : '';
        $magazine_end_date_gmt = isset( $_POST['ud_ref_to_magazine_info']['end_date'] ) ? $this->sanitized_date( $_POST['ud_ref_to_magazine_info']['end_date']  ) : '';

        if (! empty($magazine_start_date_gmt)) {
            $ud_ref_to_magazine_info = array();
            $ud_ref_to_magazine_info['start_date'] = $magazine_start_date_gmt;

            if ( ! empty($magazine_end_date_gmt) ) {
                $ud_ref_to_magazine_info['end_date'] = $magazine_end_date_gmt;
            }

            if ( ! add_post_meta( $post_id, UDRefToMagazine::$magazine_info_key, $ud_ref_to_magazine_info, true ) ) {
                update_post_meta( $post_id,  UDRefToMagazine::$magazine_info_key, $ud_ref_to_magazine_info );
            }
        }


        return $post_id;
    }

    // return gmt date string
    private function sanitized_date( $date_string ) {
        $date_string = sanitize_text_field($date_string);

        $date_arr = explode('/', $date_string);

        if (3 !== count($date_arr)) {
            return '';
        }

        $month = $date_arr[1];
        $day = $date_arr[0];
        $year = $date_arr[2];

        $date_string = sprintf( "%04d-%02d-%02d 00:00:00", $year, $month, $day );

        $valid_date = wp_checkdate( $month, $day, $year, $date_string );
        if ( !$valid_date ) {
            return '';
        }

        return $date_string;
    }

//    private function remove_taxonomies_metaboxes() {
//        remove_meta_box( UDRefToMagazine::$taxonomy_name.'div', 'post', 'side' );
//    }

}