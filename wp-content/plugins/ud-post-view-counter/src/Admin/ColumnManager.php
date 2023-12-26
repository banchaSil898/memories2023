<?php

namespace UDPostViewCounter\Admin;

if (! defined('ABSPATH')) {
    exit;
}

use UDPostViewCounter\Core\Model\Counter;
use UDPostViewCounter\Core\Model\Stat;
use UDPostViewCounter\UDPostViewCounter;

class ColumnManager
{
    public function __construct()
    {
        add_filter('manage_posts_columns', array($this, 'managePostsColumnsHook'), 10, 1);
        add_action('manage_posts_custom_column', array($this, 'managePostsCustomColumnHook'), 5, 2);
        add_filter('manage_edit-post_sortable_columns', array($this, 'manageEditPostsSortableColumnsHook'), 10, 1);
    }

    public function managePostsColumnsHook($defaults)
    {
        $defaults['udpvc_count_1day'] = 'Today Views';
        $defaults['udpvc_count_7day'] = 'Weekly Views';
        $defaults['udpvc_count_total'] = 'Views';

        return $defaults;
    }

    public function managePostsCustomColumnHook($column_name, $post_id)
    {
        if ($column_name === 'udpvc_count_total') {
            $stat = Stat::get($post_id);
            echo empty($stat) ? 0 : $stat->count_total;
        } elseif ($column_name === 'udpvc_count_7day') {
            $stat = Stat::get($post_id);
            echo empty($stat) ? 0 : $stat->count_7day;
        } elseif ($column_name === 'udpvc_count_1day') {
            $stat = Stat::get($post_id);
            echo empty($stat) ? 0 : $stat->count_1day;
        }
    }

    public function manageEditPostsSortableColumnsHook($columns)
    {
        $columns['udpvc_count_1day'] = array('udpvc_count_1day', 1);
        $columns['udpvc_count_7day'] = array('udpvc_count_7day', 1);
        $columns['udpvc_count_total'] = array('udpvc_count_total', 1);

        return $columns;
    }
}
