<?php

namespace UDPostViewCounter;

if (! defined('ABSPATH')) {
    exit;
}

define('UD_POST_VIEW_COUNTER_VERSION', '2.1.6');
define('UD_POST_VIEW_COUNTER_DB_VERSION', '1.0');

if (! defined('UD_POST_VIEW_COUNTER_FILE')) {
    define('UD_POST_VIEW_COUNTER_FILE', dirname(dirname(__FILE__)) . '/ud-post-view-counter.php');
}

if (! defined('UD_POST_VIEW_COUNTER_PATH')) {
    define('UD_POST_VIEW_COUNTER_PATH', plugin_dir_path(UD_POST_VIEW_COUNTER_FILE));
}

if (! defined('UD_POST_VIEW_COUNTER_BASENAME')) {
    define('UD_POST_VIEW_COUNTER_BASENAME', plugin_basename(UD_POST_VIEW_COUNTER_FILE));
}

if (! defined('UD_POST_VIEW_COUNTER_TEXT_DOMAIN')) {
    define('UD_POST_VIEW_COUNTER_TEXT_DOMAIN', 'ud-post-view-counter');
}

use UDPostViewCounter\Admin\Admin;
use UDPostViewCounter\Core\CLI\UDPVCCommand;
use UDPostViewCounter\Core\Model\Stat;
use UDPostViewCounter\Core\PluginActivator;
use UDPostViewCounter\Core\StatManager;
use UDPostViewCounter\Core\WPQueryManager;

/**
 * Class UDPostViewCounter
 */
class UDPostViewCounter
{
    const STAT_TABLE_NAME = 'udpvc_stat';
    const COUNTER_TABLE_NAME = 'udpvc_counter';
    const OPTION_KEY = 'udpvc_options';
    const DB_VERSION_KEY = 'udpvc_db_version';

    /**
     * Maintain singleton.
     * @var $instance
     */
    private static $instance;

    private $stat_manager;

    private $admin;

    private $plugin_activator;

    private $wp_query_manager;

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->plugin_activator = new PluginActivator();
        $this->admin = new Admin();
        $this->stat_manager = new StatManager();
        $this->wp_query_manager = new WPQueryManager();

        if (class_exists('\WP_CLI')) {
            \WP_CLI::add_command('udpvc', UDPVCCommand::class);
        }
    }

    public function getStat($post, $key)
    {
        $post = get_post($post);

        if (empty($post)) {
            return null;
        }

        if (! is_string($key) || empty($key)) {
            return null;
        }

        $stat = Stat::get($post->ID);
        $count = 0;
        if (! empty($stat)) {
            $count = $stat->getValue($key);
        }

        return $count;
    }
}
