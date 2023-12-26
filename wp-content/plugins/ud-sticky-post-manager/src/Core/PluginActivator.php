<?php

namespace UDStickyPostManager\Core;

if (! defined('ABSPATH')) {
    exit;
}

class PluginActivator
{
    public function __construct()
    {
        register_activation_hook(UD_STICKY_POST_MANAGER_FILE, array(__NAMESPACE__ . '\PluginActivator', 'activate'));
        register_deactivation_hook(UD_STICKY_POST_MANAGER_FILE, array(__NAMESPACE__ . '\PluginActivator', 'deactivate'));
    }

    public static function activate()
    {
        flush_rewrite_rules();
    }

    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}
