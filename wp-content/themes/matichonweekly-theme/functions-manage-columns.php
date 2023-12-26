<?php
add_action('admin_head', 'edit_admin_css');

function edit_admin_css() {
    echo '<style type="text/css">';
    echo '.column-title { width: 200px; }';
    echo '.column-comments { display: none; }';
    echo '.column-featured_image { width: 100px; }';
    echo '.fixed .column-udpvc_count_total { width: 80px; }';
    echo '.fixed .column-udpvc_count_7day, .fixed .column-udpvc_count_1day { width: 100px; }';
    echo '.column-author, .column-modified_author { width: 100px; }';
    echo '.fixed .column-categories, .fixed .column-tags, .fixed .column-date { width: 100px; }';
    echo '.column-featured_image img { width: 100%; height: auto; }';
    echo '.fixed .column-author, .fixed .column-modified_author { width: 100px; }';
    echo '.column-td_post_views { display: none; }';
    echo '</style>';
}

add_filter( 'manage_post_posts_columns', 'set_custom_edit_post_columns' );
function set_custom_edit_post_columns($columns) {
    $columns['modified_author'] = __( 'ผู้เขียนที่แก้ไขล่าสุด', 'matichon' );
    $columns['featured_image'] = __( 'รูปประกอบ', 'matichon' );
    return $columns;
}

add_filter('manage_post_posts_columns', 'column_order');
function column_order($columns) {
  $n_columns = array();
  $move = 'modified_author'; // what to move
  $before = 'categories'; // move before this
  foreach($columns as $key => $value) {
    if ($key==$before){
      $n_columns[$move] = $move;
    }
    $n_columns[$key] = $value;
  }
  return $n_columns;
}

add_filter('manage_post_posts_columns', 'image_column_order');
function image_column_order($columns) {
  $n_columns = array();
  $move = 'featured_image'; // what to move
  $before = 'author'; // move before this
  foreach($columns as $key => $value) {
    if ($key==$before){
      $n_columns[$move] = $move;
    }
    $n_columns[$key] = $value;
  }
  return $n_columns;
}

add_action('manage_post_posts_custom_column', 'add_post_content_column', 10, 2);
function add_post_content_column($column_key, $post_id) {
	if ($column_key == 'modified_author') {
		$modified_author_id = get_post_meta( $post_id, '_edit_last', true );
		if ($modified_author_id) {
            $modified_author = get_the_author_meta('display_name', $modified_author_id);
			echo '<a href="edit.php?post_type=post&author=' . $modified_author_id . '">' . $modified_author . '</a>';
		} else {
			echo '-';
		}
	}

    if ($column_key == 'featured_image') {
		$featured_image = get_the_post_thumbnail( $post_id, 'td_100x70' );
		if ($featured_image) {
			echo $featured_image;
		} else {
			echo '';
		}
	}
}
