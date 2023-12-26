<?php

namespace UDPostViewCounter\Admin;

if (! defined('ABSPATH')) {
    exit;
}

class Admin
{

    /**
     * Admin constructor.
     */
    public function __construct()
    {
        new Setting();

        if (is_admin() && (! defined('DOING_AJAX') || ! DOING_AJAX)) {
            new ColumnManager();
            new SettingPage();
        }
    }
}
