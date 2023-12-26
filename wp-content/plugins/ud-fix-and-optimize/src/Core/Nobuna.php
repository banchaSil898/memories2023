<?php

namespace UDFixAndOptimize\Core;

use UDFixAndOptimize\UDFixAndOptimize;
use UDFixAndOptimize\UDOptionFramework\OptionFramework;

if (! defined('ABSPATH')) {
    exit;
}

class Nobuna
{
    public function __construct()
    {
        if (true === OptionFramework::getOptionValue('nobuna_remove_backup_button', UDFixAndOptimize::OPTION_KEY)) {
            add_action('current_screen', array($this, 'removeNobunaBackupButton'), 9999);
        }

        if (true === OptionFramework::getOptionValue('nobuna_remove_admin_column_pro_license_notice', UDFixAndOptimize::OPTION_KEY)) {
            add_action('current_screen', array($this, 'removeAdminColumnProLicenseNotice'), 9999);
        }
    }

    public function removeNobunaBackupButton($current_screen)
    {
        if (is_admin() && $current_screen->id === 'plugins' && class_exists('\NobunaPlugins\Controllers\NobunaAdminUIWPPluginsPage')) {
            $plugin_main_files = array_keys(get_plugins());
            foreach ($plugin_main_files as $plugin_main_file) {
                remove_filter('plugin_action_links_' . plugin_basename($plugin_main_file), array('NobunaPlugins\Controllers\NobunaAdminUIWPPluginsPage', 'AddActionLinks'));
            }
        }
    }

    public function removeAdminColumnProLicenseNotice($current_screen)
    {
        if (is_admin() && $current_screen->id === 'plugins' && class_exists('\Acp')) {
            remove_all_actions('after_plugin_row_' . \ACP::instance()->get_basename(), 11);
        }
    }
}
