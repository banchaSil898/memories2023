<?php

namespace UDStickyPostManager\Core;

if (! defined('ABSPATH')) {
    exit;
}

class PostType
{
    const POST_TYPE_NAME = 'udspm_schedule';
    const POST_TYPE_SLUG = 'udspm_schedule';
    const META_KEY = 'udspm_schedule_info';

    public function __construct()
    {
        add_action('init', array($this, 'initHook'));
        add_action('admin_menu', array($this, 'adminMenuHook'));
    }

    public function initHook()
    {
        $this->registerPostType();
    }

    public function adminMenuHook()
    {
        $this->removeCategorySubMenu();
    }

    public function removeCategorySubMenu()
    {
        remove_submenu_page('edit.php?post_type=' . self::POST_TYPE_SLUG, "edit-tags.php?taxonomy=category&amp;post_type=" . self::POST_TYPE_SLUG);
    }


    // Register Custom Post Type
    public function registerPostType()
    {
        $labels = array(
            'name'                  => _x('Sticky Post Schedules', 'Post Type General Name', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'singular_name'         => _x('Sticky Post Schedule', 'Post Type Singular Name', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'menu_name'             => __('Sticky Post Schedules', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'name_admin_bar'        => __('Sticky Post Schedule', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'archives'              => __('Item Archives', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'parent_item_colon'     => __('Parent Schedule:', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'all_items'             => __('All Schedules', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'add_new_item'          => __('Add New Schedule', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'add_new'               => __('Add New', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'new_item'              => __('New Schedule', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'edit_item'             => __('Edit Schedule', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'update_item'           => __('Update Schedule', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'view_item'             => __('View Schedule', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'search_items'          => __('Search Schedule', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'not_found'             => __('Not found', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'not_found_in_trash'    => __('Not found in Trash', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'featured_image'        => __('Featured Image', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'set_featured_image'    => __('Set featured image', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'remove_featured_image' => __('Remove featured image', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'use_featured_image'    => __('Use as featured image', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'insert_into_item'      => __('INSERT INTO item', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'uploaded_to_this_item' => __('Uploaded to this item', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'items_list'            => __('Items list', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'items_list_navigation' => __('Items list navigation', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'filter_items_list'     => __('Filter items list', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
        );
//        $rewrite = array(
//            'slug'       => self::POST_TYPE_SLUG,
//            'with_front' => true,
//            'pages'      => true,
//            'feeds'      => true,
//        );
        $args = array(
            'label'               => __('Sticky Post Schedule', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'description'         => __('Sticky Post Schedule', UD_STICKY_POST_MANAGER_TEXT_DOMAIN),
            'labels'              => $labels,
            'supports'            => array('dummy'),
            'taxonomies'          => array(),
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => null,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'rewrite'             => false,
            'capability_type'     => 'post',
        );
        register_post_type(self::POST_TYPE_NAME, $args);
    }
}
