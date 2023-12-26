<?php

namespace UDPostViewCounter\Core;

use UDPostViewCounter\Core\Model\Stat;
use UDPostViewCounter\UDPostViewCounter;

if (! defined('ABSPATH')) {
    exit;
}


class WPQueryManager
{

    public function __construct()
    {
        add_filter('posts_orderby', array($this, 'postsOrderByHook'), 10, 2);
        add_filter('posts_join', array($this, 'postsJoinHook'), 10, 2);
        add_action('pre_get_posts', array($this, 'preGetPostsHook'), 10, 1);
    }

    /**
     * @param \WP_Query $wp_query
     * @return void
     */
    public function preGetPostsHook($wp_query)
    {
        $udpvc_orderby = $wp_query->get('udpvc_orderby');
        $orderby = $wp_query->get('orderby');
        $order = $wp_query->get('order');

        if (! empty($udpvc_orderby)) {
            if (is_array($udpvc_orderby)) {
                if (empty($orderby)) {
                    $wp_query->set('orderby', 'none');
                }
            } else {
                $wp_query->set('udpvc_orderby', null);
            }
        } else {
            $udpvc_orderby_allowed_keys = array('udpvc_count_1day', 'udpvc_count_7day', 'udpvc_count_total');
            $udpvc_orderby = array();

            if (! empty($orderby)) {
                $udpvc_key = '';
                if (is_array($orderby) && count($orderby) === 1 && in_array($orderby, $udpvc_orderby_allowed_keys)) {
                    $udpvc_key = $this->removePrefix($orderby[0]);
                } elseif (in_array($orderby, $udpvc_orderby_allowed_keys)) {
                    $udpvc_key = $this->removePrefix($orderby);
                }

                if (! empty($udpvc_key)) {
                    $udpvc_orderby = array(
                        $udpvc_key => $this->parseOrder($order),
                        'post_id'  => $this->parseOrder($order),
                    );
                    $orderby = 'none';
                }

                $wp_query->set('orderby', $orderby);
                $wp_query->set('udpvc_orderby', $udpvc_orderby);
            }
        }
    }

    /**
     * @param String    $orderby
     * @param \WP_Query $wp_query
     * @return String
     */
    public function postsOrderByHook($orderby, $wp_query)
    {
        $udpvc_orderby_array = $wp_query->get('udpvc_orderby');
        if (empty($udpvc_orderby_array) || ! is_array($udpvc_orderby_array)) {
            return $orderby;
        }

        $stat_table_name = Stat::getTableName();

        $orderby_array = array();

        foreach ($udpvc_orderby_array as $udpvc_orderby => $udpvc_order) {
            $orderby_array[] = "$stat_table_name.$udpvc_orderby $udpvc_order";
        }

        if (! empty(trim($orderby))) {
            $orderby_array[] = "$orderby";
        }

        $orderby = join(', ', $orderby_array);

        return $orderby;
    }

    /**
     * @param String    $join
     * @param \WP_Query $wp_query
     * @return String
     */
    public function postsJoinHook($join, $wp_query)
    {
        $udpvc_orderby_array = $wp_query->get('udpvc_orderby');
        if (empty($udpvc_orderby_array) || ! is_array($udpvc_orderby_array)) {
            return $join;
        }
        $stat_table_name = Stat::getTableName();

        global $wpdb;
        $join = "INNER JOIN $stat_table_name ON {$wpdb->posts}.ID = $stat_table_name.post_id $join";

        return $join;
    }

    private function removePrefix($string)
    {
        $prefix = 'udpvc_';
        if (substr($string, 0, strlen($prefix)) == $prefix) {
            $string = substr($string, strlen($prefix));
        }

        return $string;
    }

    private function parseOrder($order)
    {
        if (! is_string($order) || empty($order)) {
            return 'DESC';
        }

        if ('ASC' === strtoupper($order)) {
            return 'ASC';
        } else {
            return 'DESC';
        }
    }
}
