<?php
add_filter('the_content', 'add_dable_div', 10, 1);

function add_dable_div( $content ) {
  if ( is_single() ) {
    $content = '<div itemprop="articleBody">' . $content . '</div>';
  }
  return $content;
}

function add_dable_metadata() {
  if ( is_single() ) {
    echo '<meta property="dable:item_id" content="' . get_the_ID() . '" />';

    if ( class_exists( 'WPSEO_Primary_Term' ) ) {
      $primary_term_object = new WPSEO_Primary_Term( 'category', get_the_ID() );
      $cat_id = $primary_term_object->get_primary_term();
      $category = get_category( $cat_id );
    } else {
      $categories = wp_get_post_categories( get_the_ID() );
      $category = get_category( $categories[0] );
    }

    echo '<meta property="article:section" content="' . $category->name . '">';
  }
}
add_action( 'wp_head', 'add_dable_metadata' );
