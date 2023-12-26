<?php

namespace UDStickyPostManager\Admin;

if (! defined('ABSPATH')) {
    exit;
}

class Ajax
{

    /**
     *  constructor.
     */
    public function __construct()
    {
        if (is_admin()) {
            add_action('wp_ajax_ud_sticky_post_search_post', array($this, 'searchPost'));
            add_action('wp_ajax_ud_sticky_post_search_category', array($this, 'searchCategory'));
        }
    }

    public function searchPost()
    {
        $result = array();
        //the search string
        if (! empty($_POST['ud_string'])) {
            //Unixdev MOD: add some security on search
            $ud_string = sanitize_text_field(wp_unslash($_POST['ud_string']));
        } else {
            $ud_string = '';
        }

        $args = array(
            's'              => $ud_string,
            // 'post_type' => array('post'),
            'posts_per_page' => 15,
            //'post_status' => 'publish'
        );

        $td_query = new \WP_Query($args);

        foreach ($td_query->posts as $post) {
            $result[(string)$post->ID] = $post->post_title;
        }

        die(json_encode($result));
    }

    public function searchCategory()
    {
        $result = array();
        //the search string
        if (! empty($_POST['ud_string'])) {
            //Unixdev MOD: add some security on search
            $ud_string = sanitize_text_field(wp_unslash($_POST['ud_string']));
        } else {
            $ud_string = '';
        }

        $terms = get_terms('category', array('name__like' => $ud_string, 'hide_empty' => false));

        foreach ($terms as $term) {
            $result[(string)$term->slug] = $term->name;
        }

        die(json_encode($result));
    }
}
