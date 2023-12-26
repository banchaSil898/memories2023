<?php
/**
 * If you are looking for the loop that's handling the single post page (single.php), check out loop-single.php
 **/


// $global_flag_to_hide_no_post_to_display - comes from page-category-big-grid.php and is a flag to hide the 'No posts to display' message if on category page there are between 1 and 5  posts
global $loop_module_id, $loop_sidebar_position, $global_flag_to_hide_no_post_to_display;

///if we are in wordpress loop; used by quotes in blocks to check if the blocks are displayed in blocks or in loop
td_global::$is_wordpress_loop = true;

$td_template_layout = new td_template_layout($loop_sidebar_position);

if (empty($loop_module_id)) {  //not sure if we need a default here
    $loop_module_id = 1;
}

$td_module_class = td_api_module::_helper_get_module_class_from_loop_id($loop_module_id);

//Unixdev MOD: come from category.php
global $ud_force_column_number;
if ( ! empty ($ud_force_column_number)) {
    $td_template_layout->set_columns($ud_force_column_number);
}
//------------

//disable the grid for some of the modules
$td_module = td_api_module::get_by_id($td_module_class);
if ($td_module['uses_columns'] === false) {
    $td_template_layout->disable_output();
}

if ('no_sidebar' === $loop_sidebar_position){
   $ud_in_block_column_number = 3;
}else {
   $ud_in_block_column_number = 2;
}

$ud_limit = $ud_in_block_column_number;


$ud_block = td_global_blocks::get_instance('td_block_5');

global $ud_loop_block_mode;
switch ($ud_loop_block_mode) {
    case 'columnist' :
        $ud_taxonomy = UDColumnistManager\ColumnistManager::TAXONOMY_NAME;
        break;

    default :
        td_util::error(__FILE__, 'Unknown ud_loop_block_mode: ' . $ud_loop_block_mode);
        return;
}


if (have_posts()) {
    while ( have_posts() ) : the_post();
        echo $td_template_layout->layout_open_element();

        $ud_block_name = $post->post_title;

        $ud_terms = get_the_terms($post->ID, $ud_taxonomy );
        if ( empty($ud_terms[0])) {
            td_util::error(__FILE__, "missing term on post:". $post->post_title );
            break;
        }

        $ud_term_url = get_term_link($ud_terms[0], $ud_taxonomy);

        $atts = array(
            'limit' => $ud_limit,
            'custom_title' => $ud_block_name,
            'custom_url' => $ud_term_url,
            'td_column_number' => $ud_in_block_column_number,
            'ud_readmore_url' => $ud_term_url,
            'ud_taxonomy_name' => $ud_taxonomy,
            'ud_taxonomy_term_include_ids' => $ud_terms[0]->term_id,
            'installed_post_types' => 'post',
        );
        echo $ud_block->render($atts);

        echo $td_template_layout->layout_close_element();
        $td_template_layout->layout_next();
    endwhile; //end loop
    echo $td_template_layout->close_all_tags();


} else {
    /**
     * no posts to display. This function generates the __td('No posts to display').
     * the text can be overwritten by the themplate using the global @see td_global::$custom_no_posts_message
     */

    echo td_page_generator::no_posts();
}
