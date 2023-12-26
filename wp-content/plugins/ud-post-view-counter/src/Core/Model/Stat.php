<?php

namespace UDPostViewCounter\Core\Model;

if (! defined('ABSPATH')) {
    exit;
}


class Stat
{
    const CACHE_GROUP_NAME = 'udpvc_stat';
    const TABLE_NAME = 'udpvc_stat';

    public $post_id;

    public $count_1day;

    public $count_7day;

    public $count_total;

    /**
     * Stat constructor.
     * @param $post_id
     * @param $count_1day
     * @param $count_7day
     * @param $count_total
     */
    public function __construct($post_id, $count_1day, $count_7day, $count_total)
    {
        $this->post_id = $post_id;
        $this->count_1day = $count_1day;
        $this->count_7day = $count_7day;
        $this->count_total = $count_total;
    }

    public function getValue($key)
    {
        if ('count_1day' === $key) {
            return $this->count_1day;
        } elseif ('count_7day' === $key) {
            return $this->count_7day;
        } elseif ('count_total' === $key) {
            return $this->count_total;
        }

        return null;
    }


    public static function delete($post_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::TABLE_NAME;

        $id_column = 'post_id';

        $query = "DELETE FROM $table_name WHERE $id_column = $post_id";

        $count = $wpdb->query($query);

        if (! $count) {
            return false;
        }

        wp_cache_delete($post_id, self::CACHE_GROUP_NAME);

        return true;
    }

    /**
     * @param Stat $stat
     * @return bool
     */
    public static function insertOrUpdate($stat)
    {
        global $wpdb;
        $table_name = self::getTableName();

        $clauses = array(
            isset($stat->count_1day) ? 'count_1day = VALUES(count_1day)' : null,
            isset($stat->count_7day) ? 'count_7day = VALUES(count_7day)' : null,
            isset($stat->count_total) ? 'count_total = VALUES(count_total)' : null,
        );

        $sql_update_clause = join(', ', array_filter($clauses));

        $sql = "INSERT INTO $table_name (post_id,count_1day,count_7day,count_total) 
                VALUES (%d,%d,%d,%d) 
                ON DUPLICATE KEY UPDATE 
                  $sql_update_clause";

        $sql = $wpdb->prepare($sql, $stat->post_id, $stat->count_1day, $stat->count_7day, $stat->count_total);
        if (empty($sql)) {
            return false;
        }

        $result = $wpdb->query($sql);

        if ($result !== false and $result !== 0) {
            wp_cache_delete($stat->post_id, self::CACHE_GROUP_NAME);
        }

        return $result;
    }

    public static function insertOrChange($change_stat)
    {
        global $wpdb;
        $table_name = self::getTableName();

        $count_1day_clause = null;
        if (isset($change_stat->count_1day)) {
            $count_1day_abs = absint($change_stat->count_1day);
            if ($change_stat->count_1day < 0) {
                $count_1day_clause = "count_1day = IF(count_1day < $count_1day_abs, 0, count_1day - $count_1day_abs )";
            } else {
                $count_1day_clause = "count_1day = count_1day + $count_1day_abs";
            }
        }

        $count_7day_clause = null;
        if (isset($change_stat->count_7day)) {
            $count_7day_abs = absint($change_stat->count_7day);
            if ($change_stat->count_7day < 0) {
                $count_7day_clause = "count_7day = IF(count_7day < $count_7day_abs, 0, count_7day - $count_7day_abs )";
            } else {
                $count_7day_clause = "count_7day = count_7day + $count_7day_abs";
            }
        }

        $count_total_clause = null;
        if (isset($change_stat->count_total)) {
            $count_total_abs = absint($change_stat->count_total);
            if ($change_stat->count_total < 0) {
                $count_total_clause = "count_total = IF(count_total < $count_total_abs, 0, count_total - $count_total_abs )";
            } else {
                $count_total_clause = "count_total = count_total + $count_total_abs";
            }
        }

        $clauses = array($count_1day_clause, $count_7day_clause, $count_total_clause);

        $sql_update_clause = join(', ', array_filter($clauses));

        $sql = "INSERT INTO $table_name (post_id,count_1day,count_7day,count_total) 
                VALUES (%d,%d,%d,%d) 
                ON DUPLICATE KEY UPDATE 
                  $sql_update_clause";

        $sql = $wpdb->prepare(
            $sql,
            $change_stat->post_id,
            $change_stat->count_1day < 0 ? 0 : $change_stat->count_1day,
            $change_stat->count_7day < 0 ? 0 : $change_stat->count_7day,
            $change_stat->count_total < 0 ? 0 : $change_stat->count_total
        );

        if (empty($sql)) {
            return false;
        }

        $result = $wpdb->query($sql);

        if ($result !== false and $result !== 0) {
            wp_cache_delete($change_stat->post_id, self::CACHE_GROUP_NAME);
        }

        return $result;
    }

    public static function increment($post_id, $count)
    {
        $change_stat = new Stat($post_id, $count, $count, $count);
        $result = self::insertOrChange($change_stat);

        return $result;
    }

    public static function updateCache($post_ids)
    {
        global $wpdb;

        if (! $post_ids) {
            return false;
        }

        if (! is_array($post_ids)) {
            $post_ids = preg_replace('|[^0-9,]|', '', $post_ids);
            $post_ids = explode(',', $post_ids);
        }

        $post_ids = array_map('intval', $post_ids);

        $cache_key = self::CACHE_GROUP_NAME;

        $ids = array();
        $cache = array();
        foreach ($post_ids as $id) {
            $cached_object = wp_cache_get($id, $cache_key);
            if (false === $cached_object) {
                $ids[] = $id;
            } else {
                $cache[$id] = $cached_object;
            }
        }

        if (empty($ids)) {
            return $cache;
        }

        $table_name = self::getTableName();
        $id_list = join(',', $ids);
        $id_column = 'post_id';

        $columns = array(
            'count_1day',
            'count_7day',
            'count_total',
        );
        $column = join(',', array_merge(array($id_column), $columns));


        $sql = "SELECT $column
                    FROM $table_name
                    WHERE $id_column
                    IN ($id_list)
                    ORDER BY $id_column
                    ASC";

        $meta_list = $wpdb->get_results($sql, ARRAY_A);

        if (! empty($meta_list)) {
            foreach ($meta_list as $metarow) {
                $mpid = intval($metarow[$id_column]);
                foreach ($columns as $key) {
                    if (! isset($cache[$mpid]) || ! is_array($cache[$mpid])) {
                        $cache[$mpid] = array();
                    }
                    if (! isset($cache[$mpid][$key]) || ! is_array($cache[$mpid][$key])) {
                        $cache[$mpid][$key] = array();
                    }
                    $cache[$mpid][$key] = $metarow[$key];
                }
            }
        }

        foreach ($ids as $id) {
            if (! isset($cache[$id])) {
                $cache[$id] = array();
            }
            wp_cache_add($id, $cache[$id], $cache_key);
        }

        return $cache;
    }

    public static function get($post_id)
    {
        $stat_cache = wp_cache_get($post_id, self::CACHE_GROUP_NAME);
        if (! $stat_cache) {
            $stat_cache = self::updateCache(array($post_id));
            $stat_cache = $stat_cache[$post_id];
        }

        if (empty($stat_cache)) {
            return null;
        }

        $stat = new Stat(
            $post_id,
            intval($stat_cache['count_1day']),
            intval($stat_cache['count_7day']),
            intval($stat_cache['count_total'])
        );

        return $stat;
    }

    public static function getResetStats($include_count_1day, $include_count_7day, $include_count_total)
    {
        global $wpdb;
        $table_name = self::getTableName();

        $where_clauses = array(
            ($include_count_1day ? 'count_1day > 0' : ''),
            ($include_count_7day ? 'count_7day > 0' : ''),
            ($include_count_total ? 'count_total > 0' : ''),
        );

        $sql_where_clauses = join(' OR ', array_filter($where_clauses));

        if (empty($sql_where_clauses)) {
            return array();
        }

        $sql = "SELECT * 
                FROM $table_name
                WHERE $sql_where_clauses";

        $results = $wpdb->get_results($sql);

        $stats = array();
        foreach ($results as $result) {
            $stats[$result->post_id] = new Stat(
                $result->post_id,
                ($include_count_1day and $result->count_1day !== 0) ? -$result->count_1day : null,
                ($include_count_7day and $result->count_7day !== 0) ? -$result->count_7day : null,
                ($include_count_total and $result->count_total !== 0) ? -$result->count_total : null
            );
        }

        return $stats;
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
                count_1day bigint(20) unsigned NOT NULL default '0',
                count_7day bigint(20) unsigned NOT NULL default '0',
                count_total bigint(20) unsigned NOT NULL default '0',
                PRIMARY KEY post_id (post_id),
                KEY count_1day (count_1day),
                KEY count_7day (count_7day),
                KEY count_total (count_total)
                ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
