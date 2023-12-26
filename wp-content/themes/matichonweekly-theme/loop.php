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

//Unixdev MOD: come from category.php
global $ud_loop_wrap_mode;
$ud_loop_wrap_open = '<div class="ud_loop_inner">';
$ud_loop_wrap_close = '</div>';

if ( ! empty($ud_loop_wrap_mode) and 'disable' === $ud_loop_wrap_mode ) {
    $ud_loop_wrap_open = '';
    $ud_loop_wrap_close = '';
}

//--------------------------------

//disable the grid for some of the modules
$td_module = td_api_module::get_by_id($td_module_class);
if ($td_module['uses_columns'] === false) {
    $td_template_layout->disable_output();
}


echo $ud_loop_wrap_open ; //Unixdev MOD
if (have_posts()) {
    while ( have_posts() ) : the_post();
        echo $td_template_layout->layout_open_element();

        if (class_exists($td_module_class)) {
            $td_mod = new $td_module_class($post);
            echo $td_mod->render();
        } else {
            td_util::error(__FILE__, 'Missing module: ' . $td_module_class);
        }

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
echo $ud_loop_wrap_close ; //Unixdev MOD