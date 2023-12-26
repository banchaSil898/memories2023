<?php

namespace UDAdsManager\Core;

use UDAdsManager\Plugin;

class PostType implements WPIntegrationInterface
{
    public function __construct()
    {
    }

    public function register()
    {
        add_action('init', [$this, 'registerPostType']);
        add_filter('manage_' . Plugin::ADS_ITEM_POST_TYPE_NAME . '_posts_columns', [$this, 'addColumns']);
        add_action('manage_' . Plugin::ADS_ITEM_POST_TYPE_NAME . '_posts_custom_column', [$this, 'customColumnHandler'], 5, 2);
    }

    // Register Custom Post Type
    public function registerPostType()
    {
        $labels = [
            'name'                  => _x('Ad Items', 'Post Type General Name', UD_ADS_MANAGER_TEXT_DOMAIN),
            'singular_name'         => _x('Ad Item', 'Post Type Singular Name', UD_ADS_MANAGER_TEXT_DOMAIN),
            'menu_name'             => __('Ad Items', UD_ADS_MANAGER_TEXT_DOMAIN),
            'name_admin_bar'        => __('Ad Items', UD_ADS_MANAGER_TEXT_DOMAIN),
            'archives'              => __('Item Archives', UD_ADS_MANAGER_TEXT_DOMAIN),
            'parent_item_colon'     => __('Parent Item:', UD_ADS_MANAGER_TEXT_DOMAIN),
            'all_items'             => __('All Ad Items', UD_ADS_MANAGER_TEXT_DOMAIN),
            'add_new_item'          => __('Add New Ad Item', UD_ADS_MANAGER_TEXT_DOMAIN),
            'add_new'               => __('Add New Ad Item', UD_ADS_MANAGER_TEXT_DOMAIN),
            'new_item'              => __('New Ad Item', UD_ADS_MANAGER_TEXT_DOMAIN),
            'edit_item'             => __('Edit Item', UD_ADS_MANAGER_TEXT_DOMAIN),
            'update_item'           => __('Update Item', UD_ADS_MANAGER_TEXT_DOMAIN),
            'view_item'             => __('View Item', UD_ADS_MANAGER_TEXT_DOMAIN),
            'search_items'          => __('Search Item', UD_ADS_MANAGER_TEXT_DOMAIN),
            'not_found'             => __('Not found', UD_ADS_MANAGER_TEXT_DOMAIN),
            'not_found_in_trash'    => __('Not found in Trash', UD_ADS_MANAGER_TEXT_DOMAIN),
            'featured_image'        => __('Featured Image', UD_ADS_MANAGER_TEXT_DOMAIN),
            'set_featured_image'    => __('Set featured image', UD_ADS_MANAGER_TEXT_DOMAIN),
            'remove_featured_image' => __('Remove featured image', UD_ADS_MANAGER_TEXT_DOMAIN),
            'use_featured_image'    => __('Use as featured image', UD_ADS_MANAGER_TEXT_DOMAIN),
            'insert_into_item'      => __('INSERT INTO item', UD_ADS_MANAGER_TEXT_DOMAIN),
            'uploaded_to_this_item' => __('Uploaded to this item', UD_ADS_MANAGER_TEXT_DOMAIN),
            'items_list'            => __('Items list', UD_ADS_MANAGER_TEXT_DOMAIN),
            'items_list_navigation' => __('Items list navigation', UD_ADS_MANAGER_TEXT_DOMAIN),
            'filter_items_list'     => __('Filter items list', UD_ADS_MANAGER_TEXT_DOMAIN),
        ];
        $args = [
            'label'               => __('Ad Items', UD_ADS_MANAGER_TEXT_DOMAIN),
            'description'         => __('Ad Item', UD_ADS_MANAGER_TEXT_DOMAIN),
            'labels'              => $labels,
            'supports'            => ['title'],
            'taxonomies'          => [],
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => null,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'post'
        ];

        register_post_type(Plugin::ADS_ITEM_POST_TYPE_NAME, $args);
    }

    public function addColumns($columns)
    {
        $columns = [
            'cb'        => $columns['cb'],
            'title'     => __('Ads Title', UD_ADS_MANAGER_TEXT_DOMAIN),
            'shortcode' => __('Shortcode', UD_ADS_MANAGER_TEXT_DOMAIN),
            'ad_type'   => __('Ad Type', UD_ADS_MANAGER_TEXT_DOMAIN),
            'enable'    => __('Enable', UD_ADS_MANAGER_TEXT_DOMAIN),
        ];

        return $columns;
    }

    public function customColumnHandler($column_name, $post_id)
    {
        switch ($column_name) {
            case 'shortcode':
                $post = get_post($post_id);
                echo '[ud_ad_pos id=\'' . $post->post_name . '\']';
                break;
            case 'ad_type':
                $post = get_post($post_id);
                $ad_info = AdInfo::fromPostID($post->ID);
                echo $ad_info->ad_type;
                break;
            case 'enable':
                $post = get_post($post_id);
                $ad_info = AdInfo::fromPostID($post->ID);
                echo ($ad_info->enable) ? '✓' : '☓';
                break;
        }
    }
}
