<?php

namespace UDColumnistManager;


if (! defined('ABSPATH')) {
    exit;
}

class ColumnistPostType
{

    public function __construct()
    {
        add_action('init', array($this, 'registerTaxonomy'));
        add_action('init', array($this, 'registerPostType'));
        add_action('init', array($this, 'registerColumnistCatTaxonomy'));

        add_filter('post_type_link', array($this, 'getPostTypeLink'), 10, 4);
        add_action('pre_get_posts', array($this, 'excludeColumnistProfileFromQuery'));
    }


    // Register Custom Post Type
    public function registerPostType()
    {

        $labels = array(
            'name'                  => _x('Columnist Profiles', 'Post Type General Name', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'singular_name'         => _x('Columnist Profile', 'Post Type Singular Name', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'menu_name'             => __('Columnist Profile', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'name_admin_bar'        => __('Columnist Profile', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'archives'              => __('Columnist Profile Archives', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'parent_item_colon'     => __('Parent Item:', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'all_items'             => __('All Columnist Profiles', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'add_new_item'          => __('Add New Columnist Profile', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'add_new'               => __('Add New', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'new_item'              => __('New Item', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'edit_item'             => __('Edit Item', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'update_item'           => __('Update Item', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'view_item'             => __('View Item', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'search_items'          => __('Search Item', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'not_found'             => __('Not found', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'not_found_in_trash'    => __('Not found in Trash', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'featured_image'        => __('Featured Image', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'set_featured_image'    => __('Set featured image', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'remove_featured_image' => __('Remove featured image', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'use_featured_image'    => __('Use as featured image', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'insert_into_item'      => __('Insert into item', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'uploaded_to_this_item' => __('Uploaded to this item', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'items_list'            => __('Items list', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'items_list_navigation' => __('Items list navigation', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'filter_items_list'     => __('Filter items list', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
        );
        $rewrite = array(
            'slug'       => 'columnist_profile',
            'with_front' => true,
            'pages'      => true,
            'feeds'      => false,
        );
        $args = array(
            'label'               => __('Columnist Profile', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'description'         => __('Columnist Profile', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'labels'              => $labels,
            'supports'            => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'custom-fields',),
            'taxonomies'          => array('ud_columnist_category'),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-businessman',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'rewrite'             => $rewrite,
            'capability_type'     => 'post',
        );
        register_post_type(ColumnistManager::POST_TYPE_NAME, $args);

    }

    // Register Custom Taxonomy
    public function registerTaxonomy()
    {

        $labels = array(
            'name'                       => _x('Columnists', 'Taxonomy General Name', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'singular_name'              => _x('Columnist', 'Taxonomy Singular Name', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'menu_name'                  => __('Columnist', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'all_items'                  => __('All Columnists', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'parent_item'                => __('Parent Columnist Item', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'parent_item_colon'          => __('Parent Columnist Item:', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'new_item_name'              => __('New Columnist Name', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'add_new_item'               => __('Add New Columnist', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'edit_item'                  => __('Edit Columnist', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'update_item'                => __('Update Columnist', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'view_item'                  => __('View Columnist', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'separate_items_with_commas' => __('Separate Columnists with commas', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'add_or_remove_items'        => __('Add or remove Columnists', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'choose_from_most_used'      => __('Choose from the most used', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'popular_items'              => __('Popular Columnists', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'search_items'               => __('Search Columnists', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'not_found'                  => __('Not Found', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'no_terms'                   => __('No Columnists', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'items_list'                 => __('Columnists list', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'items_list_navigation'      => __('Columnists list navigation', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
        );
        $rewrite = array(
            'slug'         => 'columnist',
            'with_front'   => true,
            'hierarchical' => false,
        );
        $args = array(
            'labels'             => $labels,
            'hierarchical'       => false,
            'public'             => true,
            'show_ui'            => true,
            'show_in_quick_edit' => false,
            'show_admin_column'  => true,
            'show_in_nav_menus'  => true,
            'show_tagcloud'      => true,
            'rewrite'            => $rewrite,
        );
        register_taxonomy(ColumnistManager::TAXONOMY_NAME, array(''), $args);

    }

    // Register Custom Taxonomy
    function registerColumnistCatTaxonomy()
    {

        $labels = array(
            'name'                       => _x('Columnist Categories', 'Taxonomy General Name', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'singular_name'              => _x('Columnist Category', 'Taxonomy Singular Name', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'menu_name'                  => __('Columnist Category', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'all_items'                  => __('All Items', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'parent_item'                => __('Parent Item', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'parent_item_colon'          => __('Parent Item:', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'new_item_name'              => __('New Item Name', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'add_new_item'               => __('Add New Item', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'edit_item'                  => __('Edit Item', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'update_item'                => __('Update Item', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'view_item'                  => __('View Item', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'separate_items_with_commas' => __('Separate items with commas', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'add_or_remove_items'        => __('Add or remove items', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'choose_from_most_used'      => __('Choose from the most used', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'popular_items'              => __('Popular Items', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'search_items'               => __('Search Items', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'not_found'                  => __('Not Found', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'no_terms'                   => __('No items', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'items_list'                 => __('Items list', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
            'items_list_navigation'      => __('Items list navigation', UD_COLUMNIST_MANAGER_TEXT_DOMAIN),
        );
        $args = array(
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
        );
        register_taxonomy(ColumnistManager::COLUMNIST_CAT_TAXONOMY_NAME, array('ud_columnist_profile'), $args);
    }

    public function excludeColumnistProfileFromQuery($query)
    {
        if (($query->is_main_query()) && (is_tax(ColumnistManager::TAXONOMY_NAME))) {
            $query->set('post_type', 'post');
        }
    }

    public function getPostTypeLink($url, $post, $leavename, $sample)
    {
        global $wp_rewrite;
        // If the id parameter was not passed, do nothing and return the title.
        if (ColumnistManager::POST_TYPE_NAME !== $post->post_type) {
            return $url;
        }

        $taxonomy = ColumnistManager::TAXONOMY_NAME;
        $columnist = ColumnistManagerUtil::getColumnistTerm($post);

        if (empty($columnist)) {
            return $url;
        }

        $permalink = $wp_rewrite->get_extra_permastruct($taxonomy);
        if (! $leavename) {
            $permalink = str_replace("%$taxonomy%", $columnist->slug, $permalink);
        } else {
            $permalink = str_replace("%$taxonomy%", '%postname%', $permalink);
        }

        $url = home_url($permalink);

        return $url;
    }

}