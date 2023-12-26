<?php

namespace UDPostViewCounter\Core\Model;

use UDPostViewCounter\UDPostViewCounter;

if (! defined('ABSPATH')) {
    exit;
}


class Counter
{
    const TABLE_NAME = 'udpvc_counter';

    public $post_id;

    public $count_timestamp;

    public $count;

    /**
     * Counter constructor.
     * @param $post_id
     * @param $count_timestamp
     * @param $count
     */
    public function __construct($post_id, $count_timestamp, $count)
    {
        $this->post_id = $post_id;
        $this->count_timestamp = $count_timestamp;
        $this->count = $count;
    }

    public static function delete($post_id, $count_timestamp)
    {
        global $wpdb;
        $table_name = self::getTableName();

        $count = $wpdb->delete(
            $table_name,
            array(
                'post_id'         => $post_id,
                'count_timestamp' => $count_timestamp
            ),
            array(
                '%d',
                '%s'
            )
        );

        if (! $count) {
            return false;
        }

        return true;
    }

    public static function deleteByPostID($post_id)
    {
        global $wpdb;
        $table_name = self::getTableName();

        $count = $wpdb->delete(
            $table_name,
            array(
                'post_id' => $post_id,
            ),
            array(
                '%d',
            )
        );

        return $count;
    }

    public static function deleteBetween($start_timestamp, $end_timestamp)
    {
        global $wpdb;
        $table_name = self::getTableName();

        $sql = "DELETE FROM $table_name 
                WHERE count_timestamp > %s 
                  AND  count_timestamp <= %s";

        $query = $wpdb->prepare($sql, $start_timestamp, $end_timestamp);

        $deleted_row_count = $wpdb->query($query);

        return $deleted_row_count;
    }

    public static function insert($counter)
    {
        global $wpdb;
        $table_name = self::getTableName();

        $result = $wpdb->insert(
            $table_name,
            array(
                'post_id'         => $counter->post_id,
                'count_timestamp' => $counter->count_timestamp,
                'count'           => $counter->count,
            ),
            array(
                '%d',
                '%s',
                '%d',
            )
        );

        if (! $result) {
            return false;
        }

        return true;
    }

    public static function get($post_id, $count_timestamp)
    {
        global $wpdb;
        $table_name = self::getTableName();
        $result = $wpdb->get_row("SELECT * FROM $table_name WHERE post_id = $post_id AND count_timestamp = '$count_timestamp'");

        if (empty($result)) {
            return null;
        }

        return new Counter(intval($result->post_id), $result->count_timestamp, intval($result->count));
    }

    public static function getLastCounter()
    {
        global $wpdb;
        $table_name = self::getTableName();
        $result = $wpdb->get_row("SELECT * FROM $table_name ORDER BY count_timestamp DESC LIMIT 1");

        if (empty($result)) {
            return null;
        }

        return new Counter(intval($result->post_id), $result->count_timestamp, intval($result->count));
    }

    public static function getLastCounterByPostID($post_id)
    {
        global $wpdb;
        $table_name = self::getTableName();
        $result = $wpdb->get_row("SELECT * FROM $table_name WHERE post_id = $post_id ORDER BY count_timestamp DESC LIMIT 1");

        if (empty($result)) {
            return null;
        }

        return new Counter(intval($result->post_id), $result->count_timestamp, intval($result->count));
    }

    public static function incrementLastCounter($post_id, $new_count_timestamp, $count)
    {
        global $wpdb;
        $table_name = self::getTableName();

        $sql = "UPDATE $table_name
                SET count_timestamp = %s,
                    count = count + %d
                WHERE post_id = %d
                ORDER BY count_timestamp DESC 
                LIMIT 1";

        $query = $wpdb->prepare($sql, $new_count_timestamp, $count, $post_id);

        $count = $wpdb->query($query);

        return $count;
    }

    public static function getCounterOfTime($post_id, $count_timestamp)
    {
        global $wpdb;
        $table_name = self::getTableName();
        $result = $wpdb->get_row("SELECT * FROM $table_name WHERE post_id = $post_id AND count_timestamp >= '$count_timestamp' LIMIT 1");

        if (empty($result)) {
            return null;
        }

        return new Counter(intval($result->post_id), $result->count_timestamp, intval($result->count));
    }

    public static function getSumCountersBetween($start_timestamp, $end_timestamp)
    {
        global $wpdb;
        $table_name = self::getTableName();


        $sql = "SELECT post_id, sum(count) as count 
                FROM $table_name 
                WHERE count_timestamp > %s 
                  AND  count_timestamp <= %s
                GROUP BY post_id";

        $query = $wpdb->prepare($sql, $start_timestamp, $end_timestamp);

        $results = $wpdb->get_results($query);

        if (empty($results)) {
            return array();
        }

        $sum_counters = array();
        foreach ($results as $result) {
            $sum_counters[$result->post_id] = new Counter(intval($result->post_id), $end_timestamp, intval($result->count));
        }

        return $sum_counters;
    }

    public static function getSumCountersByPostIDBetween($post_id, $start_timestamp, $end_timestamp)
    {
        global $wpdb;
        $table_name = self::getTableName();

        $sql = "SELECT sum(count) as count 
                FROM $table_name 
                WHERE post_id = $post_id
                  AND count_timestamp > %s 
                  AND count_timestamp <= %s
                GROUP BY post_id";

        $query = $wpdb->prepare($sql, $start_timestamp, $end_timestamp);

        $result = $wpdb->get_row($query);

        if (empty($result)) {
            return null;
        }

        $sum_counter = new Counter(intval($result->post_id), $end_timestamp, intval($result->count));

        return $sum_counter;
    }

    /**
     * @return mixed
     */
    public static function getTableName()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::TABLE_NAME;

        return $table_name;
    }

    public static function createTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = self::getTableName();

        $sql = "CREATE TABLE $table_name (
                post_id bigint(20) unsigned NOT NULL default '0',
                count_timestamp datetime NOT NULL default '0000-00-00 00:00:00',
                count bigint(20) unsigned NOT NULL default '0',
                PRIMARY KEY  (post_id, count_timestamp),
                KEY post_id (post_id)
                ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
