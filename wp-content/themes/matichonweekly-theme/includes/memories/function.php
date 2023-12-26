<?php


function add_post_filter( $template ) {
    global $post;

    if ( in_category('memories', $post->ID) ) {
        $locate_template = locate_template( "single-memories.php" );
        if ( ! empty( $locate_template ) ) {
            $template = $locate_template;
        }
    }
    return $template;
}

add_filter('single_template' ,'add_post_filter');