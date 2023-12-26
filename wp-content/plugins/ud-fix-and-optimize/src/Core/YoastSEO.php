<?php

namespace UDFixAndOptimize\Core;

use UDFixAndOptimize\UDFixAndOptimize;
use UDFixAndOptimize\UDOptionFramework\OptionFramework;

if (! defined('ABSPATH')) {
    exit;
}

class YoastSEO
{
    public function __construct()
    {
        if (true === OptionFramework::getOptionValue('yoast_seo_remove_separator_from_title_enable', UDFixAndOptimize::OPTION_KEY)) {
            add_filter('wpseo_opengraph_title', array($this, 'wpseoOpengraphTitleHook'), 10, 1);
            add_filter('wpseo_twitter_title', array($this, 'wpseoTwitterTitleHook'), 10, 1);
        }

        if (true === OptionFramework::getOptionValue('yoast_seo_enable_image_size_for_sharing', UDFixAndOptimize::OPTION_KEY)) {
            add_action('init', array($this, 'initHook'));
            add_filter('wpseo_image_sizes', array($this, 'wpseoImageSizesHook'), 10, 1);
        }
    }

    public function initHook()
    {
        $this->addSharingImageSize();
    }

    public function wpseoOpengraphTitleHook($title)
    {
        return $this->removeSeparaterOfTitle($title);
    }

    public function wpseoTwitterTitleHook($title)
    {
        return $this->removeSeparaterOfTitle($title);
    }

    public function wpseoImageSizesHook($sizes)
    {
        return $this->getAllSharingImageSizes($sizes);
    }

    // fix wp seo social title
    private function removeSeparaterOfTitle($title)
    {

        $is_posts_page = (is_home() && 'page' == get_option('show_on_front'));

        if (is_front_page()) {
            $title = get_bloginfo('name');
        } else {
            if (is_singular() || $is_posts_page) {
                $post_id = ($is_posts_page) ? get_option('page_for_posts') : get_the_ID();
                $post = get_post($post_id);
                $title = $post->post_title;
            } else {
                if (is_category() || is_tax() || is_tag()) {
                    $term = get_term(get_queried_object()->term_id);
                    $title = $term->name;
                } else {
                    $title = get_bloginfo('name');
                }
            }
        }

        return $title;
    }

    private function addSharingImageSize()
    {
        add_image_size('udfao_sharing_image', 1200, 9999); //mobile
    }

    private function getAllSharingImageSizes($sizes)
    {
        return array('full', 'udfao_sharing_image', 'large', 'medium_large');
    }
}
