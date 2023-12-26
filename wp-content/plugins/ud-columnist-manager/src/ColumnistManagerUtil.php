<?php

namespace UDColumnistManager;


if (! defined('ABSPATH')) {
    exit;
}

/**
 * Class ColumnistManagerUtil
 */
class ColumnistManagerUtil
{
    public static function getColumnistOnPost($post)
    {
        $post = get_post($post);

        if (empty($post)) {
            return null;
        }

        $terms = get_the_terms($post->ID, ColumnistManager::TAXONOMY_NAME);

        if (empty($terms[0])) {
            return null;
        }

        $term = $terms[0];


        $args = array(
            "post_type"     => ColumnistManager::POST_TYPE_NAME,
            "post_per_page" => 1,
            'tax_query'     => array(
                array(
                    'taxonomy' => ColumnistManager::TAXONOMY_NAME,
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ),
            ),
        );

        $columnist_profiles = get_posts($args);

        if (empty($columnist_profiles[0])) {
            return null;
        }


        return $columnist_profiles[0];
    }

    public static function getColumnistMeta($columnist)
    {
        $columnist = get_post($columnist);

        if (empty($columnist)) {
            return null;
        }

        $columnist_info_meta = get_post_meta($columnist->ID, ColumnistManager::PROFILE_INFO_META_KEY, true);

        return $columnist_info_meta;
    }

    public static function getColumnistTerm($columnist)
    {
        $columnist = get_post($columnist);

        if (empty($columnist)) {
            return null;
        }

        $terms = get_the_terms($columnist->ID, ColumnistManager::TAXONOMY_NAME);

        if (empty($terms[0])) {
            return null;
        }

        return $terms[0];
    }

    public static function getColumnistTermLink($columnist)
    {
        $columnist = get_post($columnist);

        if (empty($columnist)) {
            return '';
        }

        $terms = get_the_terms($columnist->ID, ColumnistManager::TAXONOMY_NAME);

        if (empty($terms[0])) {
            return '';
        }

        return get_term_link($terms[0], ColumnistManager::TAXONOMY_NAME);
    }

}