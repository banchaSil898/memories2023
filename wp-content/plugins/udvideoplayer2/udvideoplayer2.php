<?php

/*
Plugin Name: Unixdev Video Player
Plugin URI: https://www.unixdev.co.th/
Description: Videojs Player with vast ads from Unixdev Video platform
Author: Unixdev
Author URI: https://www.unixdev.co.th/
Version: 1.4
*/

/*
    1.2  add post_id and category for cust_params
    1.3  wrapper with tag p
    1.4  change plugin name
    1.5 embed css
*/

/* function ud_videoplayer2_script(){
    $ud_videoplayer_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'ud_videoplayer2.css', $ud_videoplayer_url . 'css/ud_videoplayer2.css', array(), '1.1' );
}
add_action( 'wp_enqueue_scripts', 'ud_videoplayer2_script' ); */

function udvideoplayer2_func($atts){
    $udvideoplayer_params = shortcode_atts( array(
        'src' => ''
    ), $atts );
    $src = $udvideoplayer_params['src'];
    global $post;
    $post_id = 0;
    if($post){
        $post_id = $post->ID;
    }
    $category_list = get_the_category($post_id);
    $category = [];
    foreach($category_list as $cate){
        array_push($category, $cate->slug);
    }
    $category_name = implode(',', $category);
    $category_name = urlencode($category_name);
    $buffy = '';
    $buffy .= '
        <style>
            .ud-video-wrapper{position:relative;padding-bottom:56.2%;height:0}.ud-video-wrapper .ud_content_iframe_custom{position:absolute;top:0;left:0;width:100%;height:100%}
        </style>
            <p class="ud-video-wrapper" >
                <iframe src="'.$src.'?post_id='.$post_id.'&category='.$category_name.'"
                    id="ud_content_iframe" 
                    class="ud_content_iframe_custom" 
                    frameborder="0"
                    border="0"
                    width="100%"
                    scrolling="no"
                    allowfullscreen>
                </iframe>
            </p>
    ';
        
    return $buffy;
}

add_shortcode( 'udplayer2', 'udvideoplayer2_func' );

?>