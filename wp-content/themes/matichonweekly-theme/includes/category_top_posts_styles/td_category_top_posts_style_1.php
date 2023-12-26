<?php

//Unixdev MOD
class td_category_top_posts_style_1 extends td_category_top_posts_style {
    function show_top_posts() {

        //parent::render_posts_to_buffer();

        $limit = td_api_category_top_posts_style::_helper_get_posts_shown_in_the_loop();
        $buffy = '';


        //parameters to filter to for big grid
        $atts = array(
            'limit'              => $limit,
            'category_id'        => td_global::$current_category_obj->cat_ID,
            'sort'               => get_query_var( 'filter_by' ),
            'td_column_number'   => '2',
            'ud_block_id_for_ad' => td_global::$current_category_obj->slug . ',__all-category'
        );

        $block_instance = td_global_blocks::get_instance( 'td_block_slide' );

        $buffy .= $block_instance->render( $atts );

        $rendered_posts_count = $block_instance->td_query->post_count;

        if ( $rendered_posts_count > 0 ) {
            td_global::$custom_no_posts_message = false;
        } else {
            return;
        }
        ?>

        <?php
        echo $buffy;
        ?>
        <?php
    }
}
