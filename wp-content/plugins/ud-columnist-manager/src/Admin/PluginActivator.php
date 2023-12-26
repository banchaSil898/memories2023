<?php

namespace UDColumnistManager\Admin;

class PluginActivator
{

    public function __construct()
    {
        register_activation_hook(UD_COLUMNIST_MANAGER_FILE, array($this, 'activate'));
        register_deactivation_hook(UD_COLUMNIST_MANAGER_FILE, array($this, 'deactivate'));
    }

    public function activate()
    {
        // clear the permalinks after the post type has been registered
        flush_rewrite_rules();
    }

    public function deactivate()
    {
        // our post type will be automatically removed, so no need to unregister it

        // clear the permalinks to remove our post type's rules
        flush_rewrite_rules();
    }
}