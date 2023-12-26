<?php

namespace UDPostViewCounter\Core\CLI;

use UDPostViewCounter\Core\Model\Counter;
use UDPostViewCounter\Core\Model\Stat;
use UDPostViewCounter\Core\StatManager;
use UDPostViewCounter\UDPostViewCounter;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Re-generate stat.
 *
 * ## EXAMPLES
 *
 *     # Re-generate stat of all post
 *     $ wp udpvc regenerate-stat
 *     Found 3 post to generate stat for
 *     1/3 Regenerated stat for post "Sydney Harbor Bridge" (ID 760).
 *     2/3 Regenerated stat for post "Boardwalk" (ID 757).
 *     3/3 Regenerated stat for post "Sunburst Over River" (ID 756).
 *     Success: Regenerated stat 3 of 3 posts.
 *
 * @package wp-cli
 */
class UDPVCCommand extends \WP_CLI_Command
{

    private $stat_manager;

    public function __construct()
    {
        $this->stat_manager = new StatManager();
    }

    /**
     * Regenerate thumbnails for one or more attachments.
     *
     *
     * ## OPTIONS
     *
     * [<post_id>...]
     * : One or more IDs of the attachments to regenerate.
     *
     * ## EXAMPLES
     *
     *     # Re-generate stat of all post
     *     $ wp udpvc regenerate-stat
     *     Found 3 post to generate stat for
     *     1/3 Regenerated stat for post "Sydney Harbor Bridge" (ID 760).
     *     2/3 Regenerated stat for post "Boardwalk" (ID 757).
     *     3/3 Regenerated stat for post "Sunburst Over River" (ID 756).
     *     Success: Regenerated stat 3 of 3 posts.
     *
     *     $ wp media regenerate-stat 123 124 125
     *     Found 3 images to regenerate.
     *     1/3 Regenerated stat for post "Vertical Image" (ID 123).
     *     2/3 Regenerated stat for post "Horizontal Image" (ID 124).
     *     3/3 Regenerated stat for post "Beautiful Picture" (ID 125).
     *     Success: Regenerated stat 3 of 3 posts.
     *
     *
     * @subcommand regenerate-stat
     */

    public function regenerateStat($args, $assoc_args)
    {
        $query_args = array(
            'post_type'      => 'post',
            'post__in'       => $args,
            'post_status'    => 'any',
            'posts_per_page' => -1,
            'fields'         => 'ids'
        );

        $wp_query = new \WP_Query($query_args);

        $successes = $errors = $skips = 0;

        $count = $wp_query->post_count;

        \WP_CLI::log(sprintf('Found %1$d %2$s to regenerate stat for.', $count, _n('post', 'posts', $count)));
        $this->processStatRegeneration($wp_query->posts, $count, $sucesses, $errors, $skips);
        \WP_CLI\Utils\report_batch_operation_results('post\'s stat', 'regenerate', $count, $successes, $errors, $skips);
    }

    private function processStatRegeneration($post_ids, $count, &$sucesses, &$errors, &$skips)
    {
        $now_ts = time();
        $now = gmdate('Y-m-d H:i:s', $now_ts);

        $last_day_ts = $this->stat_manager->getLastSecOfLastDay($now_ts);
        $last_week_ts = $this->stat_manager->getLastSecOfLastWeek($now_ts);

        $last_counter = Counter::getLastCounter();
        $sum_1day_counters = array();
        $sum_7day_counters = array();

        if (! empty($last_counter)) {
            $last_day = gmdate('Y-m-d H:i:s', $last_day_ts);
            $last_week = gmdate('Y-m-d H:i:s', $last_week_ts);

            $sum_1day_counters = Counter::getSumCountersBetween($last_day, $now);
            $sum_7day_counters = Counter::getSumCountersBetween($last_week, $now);
        }

        $number = 0;
        foreach ($post_ids as $post_id) {
            $count_1day = isset($sum_1day_counters[$post_id]) ? $sum_1day_counters[$post_id]->count : 0;
            $count_7day = isset($sum_7day_counters[$post_id]) ? $sum_7day_counters[$post_id]->count : 0;

            $stat = new Stat(
                $post_id,
                $count_1day,
                $count_7day,
                null
            );

            $number++;
            $progress = "$number/$count";
            $result = Stat::insertOrUpdate($stat);
            if ($result === false) {
                $att_desc = "(ID: $stat->post_id)";
                \WP_CLI::warning("$progress Couldn't regenerate stat for post $att_desc.");
                $errors++;
            } elseif ($result === 0) {
                $stat = Stat::get($post_id);
                $att_desc = "(ID: $stat->post_id, count_1day: $stat->count_1day, count_7day: $stat->count_7day, count_total: $stat->count_total)";
                \WP_CLI::log("$progress Regenerated stat for post $att_desc. (Not Changed)");
                $sucesses++;
            } else {
                $stat = Stat::get($post_id);
                $att_desc = "(ID: $stat->post_id, count_1day: $stat->count_1day, count_7day: $stat->count_7day, count_total: $stat->count_total)";
                \WP_CLI::log("$progress Regenerated stat for post $att_desc.");
                $sucesses++;
            }
        }
    }
}
