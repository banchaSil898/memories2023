<?php

namespace UDPostViewCounter\Core;

if (! defined('ABSPATH')) {
    exit;
}

use UDPostViewCounter\Core\Model\Counter;
use UDPostViewCounter\Core\Model\Stat;
use UDPostViewCounter\UDPostViewCounter;
use UDPostViewCounter\UDOptionFramework\OptionFramework;
use UDPostViewCounter\Util\JWT;

class StatManager
{

    public function __construct()
    {
        add_action('udpvc_5min_event', array($this, 'fiveMinEventHook'));
        add_action('udpvc_hourly_event', array($this, 'hourlyEventHook'));

        add_action('wp_enqueue_scripts', array($this, 'enqueueJquery'));
        add_action('wp_footer', array($this, 'printJStoSinglePage'));

        add_action('delete_post', array($this, 'deletePostHook'), 10, 1);

        //        add_action( 'rest_api_init', function () {
        //            register_rest_route( 'udpvcget/v1', '/get', array(
        //                'methods' => 'GET',
        //                'callback' => array($this, 'retrieveCounterFromCache'),
        //            ) );
        //        } );
    }

    public function fiveMinEventHook()
    {
        $count_data_from_external = $this->retrieveCounterFromExternal();

        $now = current_time('mysql', true);
        $this->calculateStat($count_data_from_external, $now);
    }

    public function hourlyEventHook()
    {
        $this->housekeepCounterTable();
    }

    public function deletePostHook($post_id)
    {
        Stat::delete($post_id);
        Counter::deleteByPostID($post_id);
    }


    public function enqueueJquery()
    {
        wp_enqueue_script('jquery');
    }

    public function printJStoSinglePage()
    {
        if (is_single()) {
            $secret_key = OptionFramework::getOptionValue('secret_key', UDPostViewCounter::OPTION_KEY);
            $url = OptionFramework::getOptionValue('url', UDPostViewCounter::OPTION_KEY);
            $slug = OptionFramework::getOptionValue('slug', UDPostViewCounter::OPTION_KEY);
            $id = get_the_ID();
            if (! empty($id)) {
                $token = array();
                $token['id'] = $id;
                $token['webslug'] = $slug;
                $data = JWT::encode($token, $secret_key);
                ?>
                <script>
                    jQuery.post('<?php echo $url; ?>/save',
                        {
                            'jwt': '<?php echo $data; ?>'
                        }
                        , function (data) {
                            //console.log('test');
                        }, 'json');
                </script>

                <?php
            }
        }
    }

    private function calculateStat($count_data_from_external, $now_time)
    {
        #find last second of day in GMT according to timezone
        $now_ts = strtotime($now_time . ' +0000');
        $last_day_ts = $this->getLastSecOfLastDay($now_ts);
        $last_week_ts = $this->getLastSecOfLastWeek($now_ts);

        $last_counter = Counter::getLastCounter();
        $last_counter_ts = $now_ts;
        if (! empty($last_counter)) {
            $last_counter_ts = strtotime($last_counter->count_timestamp . ' +0000', $now_ts);
        }

        $is_new_date = ($last_counter_ts <= $last_day_ts);
        $is_new_week = ($last_counter_ts <= $last_week_ts);

        # check if start new date
        if ($is_new_date) {
            # start sync all counter timestamp to end of date
            $this->syncCounterTimestampToLastSecOfDate($last_day_ts);

            #insert new counter for new date
            foreach ($count_data_from_external as $post_id => $count) {
                Counter::insert(new Counter($post_id, $now_time, $count));
            }
        } else {
            foreach ($count_data_from_external as $post_id => $count) {
                $counter = Counter::getLastCounterByPostID($post_id);
                if (empty($counter)) {
                    Counter::insert(new Counter($post_id, $now_time, $count));
                } else {
                    Counter::incrementLastCounter($post_id, $now_time, $count);
                }
            }
        }

        $reset_stats = Stat::getResetStats($is_new_date, $is_new_week, false);
        $all_post_ids = array_keys($count_data_from_external + $reset_stats);

        foreach ($all_post_ids as $post_id) {
            $change_stat = new Stat($post_id, 0, 0, 0);
            if (isset($reset_stats[$post_id])) {
                $change_stat = $reset_stats[$post_id];
            }

            if (isset($count_data_from_external[$post_id])) {
                $change_stat->count_1day += $count_data_from_external[$post_id];
                $change_stat->count_7day += $count_data_from_external[$post_id];
                $change_stat->count_total += $count_data_from_external[$post_id];
            }

            $post = get_post($post_id);
            if (! empty($post)) {
                Stat::insertOrChange($change_stat);
            }
        }
    }

    private function syncCounterTimestampToLastSecOfDate($last_day_ts)
    {
        $last_day = gmdate('Y-m-d H:i:s', $last_day_ts);
        $yesterday_of_last_day = gmdate('Y-m-d H:i:s', $last_day_ts - DAY_IN_SECONDS);
        $last_day_sum_counters = Counter::getSumCountersBetween($yesterday_of_last_day, $last_day);
        foreach ($last_day_sum_counters as $post_id => $counter) {
            # 0 mean not change count, only sync time to end of date.
            Counter::incrementLastCounter($post_id, $last_day, 0);
        }
    }

    public function getLastSecOfLastDay($now_ts)
    {
        $gmt_offset_sec = get_option('gmt_offset') * HOUR_IN_SECONDS;

        return strtotime('midnight +0000', $now_ts + $gmt_offset_sec) - $gmt_offset_sec - 1;
    }

    public function getLastSecOfLastWeek($now_ts)
    {
        $gmt_offset_sec = get_option('gmt_offset') * HOUR_IN_SECONDS;

        return strtotime('last Monday midnight +0000', $now_ts + DAY_IN_SECONDS + $gmt_offset_sec) - $gmt_offset_sec - 1;
    }

    private function retrieveCounterFromExternal()
    {
        $secret_key = OptionFramework::getOptionValue('secret_key', UDPostViewCounter::OPTION_KEY);
        $url = OptionFramework::getOptionValue('url', UDPostViewCounter::OPTION_KEY);
        $slug = OptionFramework::getOptionValue('slug', UDPostViewCounter::OPTION_KEY);

        $token = array();
        $token['webslug'] = $slug;
        $jwt = JWT::encode($token, $secret_key);
        $response = wp_remote_post($url . '/get', array(
            'method'      => 'POST',
            'timeout'     => 100,
            'redirection' => 50,
            'blocking'    => true,
            'sslverify'   => false,
            'headers'     => array("Content-type" => "application/x-www-form-urlencoded;charset=UTF-8"),
            'body'        => array(
                'jwt' => $jwt,
            )
        ));
        if (is_wp_error($response)) {
            return false;
        }
        $body_json = $response['body'];
        $body = json_decode($body_json);
        $data = $body->data;
        $data2 = [];
        foreach ($data as $item) {
            $data2[intval($item->post_id)] = intval($item->count);
        }

        return $data2;
    }

    private function housekeepCounterTable()
    {
        $now_ts = time();

        $last_10day_time = date('Y-m-d H:i:s', strtotime(" -10 days ", $now_ts));
        $zero_time = date('Y-m-d H:i:s', 0);

        Counter::deleteBetween($zero_time, $last_10day_time);
    }
}
