<?php

namespace UDFixAndOptimize\Core;

use UDFixAndOptimize\UDFixAndOptimize;
use UDFixAndOptimize\UDOptionFramework\OptionFramework;

if (! defined('ABSPATH')) {
    exit;
}

class DuracelltomiGTM
{
    public function __construct()
    {
        if (true === OptionFramework::getOptionValue('duracelltomi_gtm_fix_post_date_enable', UDFixAndOptimize::OPTION_KEY)
            or true === OptionFramework::getOptionValue('duracelltomi_gtm_add_post_time_enable', UDFixAndOptimize::OPTION_KEY)
            or true === OptionFramework::getOptionValue('duracelltomi_gtm_add_primary_category_enable', UDFixAndOptimize::OPTION_KEY)
        ) {
            add_filter('gtm4wp_compile_datalayer', array($this, 'gtm4wpCompileDataLayerHook'), 9999, 1);
        }
    }

    public function gtm4wpCompileDataLayerHook($data_layer)
    {
        if (true === OptionFramework::getOptionValue('duracelltomi_gtm_fix_post_date_enable', UDFixAndOptimize::OPTION_KEY)) {
            $data_layer = $this->fixPostDateFormat($data_layer);
        }

        if (true === OptionFramework::getOptionValue('duracelltomi_gtm_add_post_time_enable', UDFixAndOptimize::OPTION_KEY)) {
            $data_layer = $this->addPostTime($data_layer);
        }

        if (true === OptionFramework::getOptionValue('duracelltomi_gtm_add_primary_category_enable', UDFixAndOptimize::OPTION_KEY)) {
            $data_layer = $this->addPrimaryCategory($data_layer);
        }


        return $data_layer;
    }

    private function addPostTime($data_layer)
    {
        if (isset($data_layer["pagePostDate"])) {
            $data_layer["pagePostTime"] = get_the_time("H:i:s");
        }

        return $data_layer;
    }

    private function fixPostDateFormat($data_layer)
    {
        if (isset($data_layer["pagePostDate"])) {
            $data_layer["pagePostDate"] = get_the_date("Y-m-d");
        }

        return $data_layer;
    }

    private function addPrimaryCategory($data_layer)
    {
        if (is_single()) {
            $post = get_post();
            $category = $this->getPrimaryCategory($post);
            if (! empty($category)) {
                $parents = array_reverse(get_ancestors($category->term_id, 'category', 'taxonomy'));
                $data_layer["pagePrimaryCategory"] = array();
                foreach ($parents as $id) {
                    $cat = get_category($id);
                    $data_layer["pagePrimaryCategory"][] = $cat->slug;
                }
                $data_layer["pagePrimaryCategory"][] = $category->slug;
            }
        } elseif (is_page()) {
            $post = get_post();
            $data_layer["pagePrimaryCategory"][] = $post->post_name;
        } elseif (is_category()){
            $category = get_category_by_path(get_query_var('category_name'), false);
            if (! empty($category) and ! is_wp_error($category)) {
                $data_layer["pagePrimaryCategory"][] = $category->slug;
            }
        }

        return $data_layer;
    }

    private function getPrimaryCategory($post)
    {
        $category = get_category_by_path(get_query_var('category_name'), false);
        if (! empty($category) and ! is_wp_error($category)) {
            return $category;
        }

        if (class_exists('\WPSEO_Primary_Term')) {
            $primary_term_object = new \WPSEO_Primary_Term('category', $post->ID);
            $cat_id = $primary_term_object->get_primary_term();
            $category = get_category($cat_id);

            if (! empty($category) and ! is_wp_error($category)) {
                return $category;
            }
        }

        $categories = get_the_category($post->ID);
        if (! empty($categories)) {
            $category = $categories[0];

            return $category;
        }

        return null;
    }
}
