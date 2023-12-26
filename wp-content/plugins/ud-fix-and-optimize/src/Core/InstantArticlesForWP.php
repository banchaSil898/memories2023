<?php

namespace UDFixAndOptimize\Core;

use UDFixAndOptimize\UDFixAndOptimize;
use UDFixAndOptimize\UDOptionFramework\OptionFramework;

if (! defined('ABSPATH')) {
    exit;
}

class InstantArticlesForWP
{
    public function __construct()
    {
        if (true === OptionFramework::getOptionValue('instant_article_use_yoast_primary_category_enable', UDFixAndOptimize::OPTION_KEY)) {
            add_filter('instant_articles_cover_kicker', array($this, 'getYoastSEOPrimaryCategory'), 10, 2);
        }
    }

    public function getYoastSEOPrimaryCategory($category, $post_id)
    {
        if (class_exists('\WPSEO_Primary_Term')) {
            $primary_term_object = new \WPSEO_Primary_Term('category', $post_id);
            $cat_id = $primary_term_object->get_primary_term();
            $cat = get_category($cat_id);

            if (! is_wp_error($cat) and ! is_null($cat)) {
                $category = $cat->name;
            }
        }

        return $category;
    }
}
