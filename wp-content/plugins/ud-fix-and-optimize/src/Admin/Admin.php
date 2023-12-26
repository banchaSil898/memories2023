<?php

namespace UDFixAndOptimize\Admin;

if (!defined('ABSPATH')) {
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

        if (is_admin()) {
            add_action('admin_enqueue_scripts', array($this, 'enqueueScriptAndStyle'), 10, 1);

            new SettingPage();
        }
    }

    public function enqueueScriptAndStyle($hook)
    {
        //        wp_enqueue_style( 'ud-fix-and-optimize', plugins_url( '/assets/css/ud-fix-and-optimize.css', UD_FIX_AND_OPTIMIZE_FILE ), null, UD_FIX_AND_OPTIMIZE_VERSION );
        wp_enqueue_script('ud-fix-and-optimize-edit-post', plugins_url('/assets/js/ud-fix-and-optimize-edit-post.js', UD_FIX_AND_OPTIMIZE_FILE), array(),  UD_FIX_AND_OPTIMIZE_VERSION);
    }
}
