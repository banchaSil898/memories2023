<?php


class UDBook {
    const POST_TYPE_NAME = 'ud-book';
    const POST_TYPE_SLUG = 'magazine';

    public function __construct() {
        add_action( 'init', array( $this, 'register_post_type' ) );
    }

    public function register_post_type() {
        $labels = array(
            'name'                  => _x( 'Books', 'Post Type General Name', 'ud_book' ),
            'singular_name'         => _x( 'Book', 'Post Type Singular Name', 'ud_book' ),
            'menu_name'             => __( 'Books', 'ud_book' ),
            'name_admin_bar'        => __( 'Book', 'ud_book' ),
            'archives'              => __( 'Item Archives', 'ud_book' ),
            'parent_item_colon'     => __( 'Parent Item:', 'ud_book' ),
            'all_items'             => __( 'All Items', 'ud_book' ),
            'add_new_item'          => __( 'Add New Item', 'ud_book' ),
            'add_new'               => __( 'Add New', 'ud_book' ),
            'new_item'              => __( 'New Item', 'ud_book' ),
            'edit_item'             => __( 'Edit Item', 'ud_book' ),
            'update_item'           => __( 'Update Item', 'ud_book' ),
            'view_item'             => __( 'View Item', 'ud_book' ),
            'search_items'          => __( 'Search Item', 'ud_book' ),
            'not_found'             => __( 'Not found', 'ud_book' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'ud_book' ),
            'featured_image'        => __( 'Featured Image', 'ud_book' ),
            'set_featured_image'    => __( 'Set featured image', 'ud_book' ),
            'remove_featured_image' => __( 'Remove featured image', 'ud_book' ),
            'use_featured_image'    => __( 'Use as featured image', 'ud_book' ),
            'insert_into_item'      => __( 'Insert into item', 'ud_book' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'ud_book' ),
            'items_list'            => __( 'Items list', 'ud_book' ),
            'items_list_navigation' => __( 'Items list navigation', 'ud_book' ),
            'filter_items_list'     => __( 'Filter items list', 'ud_book' ),
        );
        $rewrite = array(
            'slug'       => self::POST_TYPE_SLUG,
            'with_front' => true,
            'pages'      => true,
            'feeds'      => true,
        );
        $args = array(
            'label'               => __( 'Book', 'ud_book' ),
            'description'         => __( 'Book', 'ud_book' ),
            'labels'              => $labels,
            'supports'            => array(
                'title',
                'editor',
                'excerpt',
                'author',
                'thumbnail',
                'comments',
                'trackbacks',
                'revisions',
                'custom-fields',
            ),
            //'taxonomies' => array( 'post_tag' ),
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'rewrite'             => $rewrite,
            'capability_type'     => 'post',
        );
        register_post_type( self::POST_TYPE_NAME, $args );
    }
}

new UDBook();
