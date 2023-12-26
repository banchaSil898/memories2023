<?php
class td_category_top_posts_style_unixdev_1 extends td_category_top_posts_style {
    function show_top_posts() {

        //parent::render_posts_to_buffer();

        $limit = td_api_category_top_posts_style::_helper_get_posts_shown_in_the_loop();
        $buffy = '';


        //parameters to filter to for big grid
        $atts = array(
            'limit' => 5,
            'category_id' => td_global::$current_category_obj->cat_ID,
            'sort' => get_query_var('filter_by'),
            'td_column_number' => '2',
        );
        
        $block_instance = td_global_blocks::get_instance('td_block_slide');
        
        $buffy .= '<div class="td-pb-span8">';
        $buffy .= $block_instance->render($atts);
        $buffy .= '</div>';

        $rendered_posts_count = $block_instance->td_query->post_count;
        
        if($limit > 5) {
            $atts = array(
                'limit' => $limit - 5,
                'category_id' => td_global::$current_category_obj->cat_ID,
                'sort' => get_query_var( 'filter_by' ),
                'td_column_number' => '1',
                'offset' => 5
            );

            $block_instance = td_global_blocks::get_instance( 'td_block_unixdev_1' );
            $buffy .= '<div class="td-pb-span4">';
            $buffy .= $block_instance->render($atts);
            $buffy .= '</div>';
            
            $rendered_posts_count += $block_instance->td_query->post_count;
        }

        if ($rendered_posts_count > 0) {
            td_global::$custom_no_posts_message = false;
        }else {
            return;
        }


        ?>
        
        

        <!-- big grid -->
        <div class="td-category-grid">
            <div class="td-container">
                <div class="td-pb-row ud-main-slide-row">
                    <?php
                    echo $buffy;
                    ?>
                </div>
            </div>
        </div>
    <?php
    }
}