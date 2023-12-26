<?php

/*  ----------------------------------------------------------------------------
    This is the search box used at the top of the search results
    It's used by /search.php


 */

/**
 * @note:
 * we use esc_url(home_url( '/' )) instead of the WordPress @see get_search_link function because that's what the internal
 * WordPress widget it's using and it was creating duplicate links like: yoursite.com/search/search_query and yoursite.com?s=search_query
 */
$cx_value = get_option( 'google_search_cx' );
?>

<h1 class="entry-title td-page-title">
    <span class="td-search-query"><?php echo isset( $_GET['q'] ) ? esc_html( $_GET['q'] ) : ''; ?></span> - <span> <?php  echo __td('search results', TD_THEME_NAME);?></span>
</h1>

<div class="search-page-search-wrap">
    <script async src="https://cse.google.com/cse.js?cx=<?php echo esc_attr( $cx_value ); ?>"></script>
	<div class="gcse-searchbox-only"></div>
</div>