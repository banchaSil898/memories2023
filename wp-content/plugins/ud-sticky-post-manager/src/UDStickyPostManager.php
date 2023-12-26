<?php

namespace UDStickyPostManager;

use UDStickyPostManager\Admin\Admin;
use UDStickyPostManager\Core\PluginActivator;
use UDStickyPostManager\Core\PostType;
use UDStickyPostManager\Core\StickyPostManager;
use UDStickyPostManager\Util\JSBuffer;

if (! defined('ABSPATH')) {
    exit;
}

define('UD_STICKY_POST_MANAGER_VERSION', '1.2.3');

if (! defined('UD_STICKY_POST_MANAGER_FILE')) {
    define('UD_STICKY_POST_MANAGER_FILE', dirname(dirname(__FILE__)) . '/ud-sticky-post-manager.php');
}

if (! defined('UD_STICKY_POST_MANAGER_PATH')) {
    define('UD_STICKY_POST_MANAGER_PATH', plugin_dir_path(UD_STICKY_POST_MANAGER_FILE));
}

if (! defined('UD_STICKY_POST_MANAGER_BASENAME')) {
    define('UD_STICKY_POST_MANAGER_BASENAME', plugin_basename(UD_STICKY_POST_MANAGER_FILE));
}

if (! defined('UD_STICKY_POST_MANAGER_TEXT_DOMAIN')) {
    define('UD_STICKY_POST_MANAGER_TEXT_DOMAIN', 'ud-ads-manager');
}

/**
 * Class UDStickyPostManager
 */
class UDStickyPostManager
{
    public static $instance;

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
        $this->js_buffer = new JSBuffer();
        $this->post_type = new PostType();
        $this->sticky_post_manager = new StickyPostManager();
    }
}
