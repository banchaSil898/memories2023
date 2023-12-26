<?php

namespace UDPostViewCounter\Core;

if (! defined('ABSPATH')) {
    exit;
}

use UDPostViewCounter\Core\Model\Counter;
use UDPostViewCounter\Core\Model\Stat;
use UDPostViewCounter\UDPostViewCounter;

class PluginActivator
{
    public function __construct()
    {
        add_filter('cron_schedules', array(__NAMESPACE__ . '\PluginActivator', 'addCustomScheduleInterval'));
        register_activation_hook(UD_POST_VIEW_COUNTER_FILE, array(__NAMESPACE__ . '\PluginActivator', 'activate'));
        register_deactivation_hook(UD_POST_VIEW_COUNTER_FILE, array(__NAMESPACE__ . '\PluginActivator', 'deactivate'));

        if (is_admin() && (! defined('DOING_AJAX') || ! DOING_AJAX)) {
            add_action('plugins_loaded', array(__NAMESPACE__ . '\PluginActivator', 'update'));
        }
    }

    public static function activate()
    {
        Counter::createTable();
        Stat::createTable();

        add_option(UDPostViewCounter::DB_VERSION_KEY, UD_POST_VIEW_COUNTER_DB_VERSION);

        self::registerEvents();
    }

    public static function deactivate()
    {
        self::unregisterEvents();
    }

    public static function update()
    {
        if (! self::shouldUpdateDatabase()) {
            return;
        }

        Counter::createTable();
        Stat::createTable();

        update_option(UDPostViewCounter::DB_VERSION_KEY, UD_POST_VIEW_COUNTER_DB_VERSION);

        self::registerEvents();
    }

    public static function addCustomScheduleInterval($schedules)
    {
        $schedules["5min"] = array(
            'interval' => 5 * MINUTE_IN_SECONDS,
            'display'  => __('Every 5 minutes')
        );

        return $schedules;
    }

    private static function shouldUpdateDatabase()
    {
        return get_option(UDPostViewCounter::DB_VERSION_KEY) !== UD_POST_VIEW_COUNTER_DB_VERSION;
    }

    private static function registerEvents()
    {
        if (! wp_next_scheduled('udpvc_5min_event')) {
            wp_schedule_event(time(), '5min', 'udpvc_5min_event');
        }

        if (! wp_next_scheduled('udpvc_hourly_event')) {
            wp_schedule_event(time(), 'hourly', 'udpvc_hourly_event');
        }
    }

    private static function unregisterEvents()
    {
        wp_clear_scheduled_hook('udpvc_5min_event');
        wp_clear_scheduled_hook('udpvc_hourly_event');
    }
}
