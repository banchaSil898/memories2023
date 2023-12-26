<?php

namespace UDAdsManager;

use UDAdsManager\Admin\AdItemMetaBox;
use UDAdsManager\Admin\SettingPageManager;
use UDAdsManager\Core\AdsManager;
use UDAdsManager\Core\AdsTXTManager;
use UDAdsManager\Core\DFPAdSize\DeserializeMultiSize;
use UDAdsManager\Core\PostType;
use UDAdsManager\Core\Setting;

class Plugin
{
    public const GLOBAL_OPTION_KEY = 'udam_options';
    public const ADS_TXT_CONTENT_OPTION_KEY = 'udam_ads_txt_content';
    public const ADS_ITEM_POST_TYPE_NAME = 'ud_ad_item';
    public const AD_INFO_OPTION_KEY = 'ud_ad_info';

    private static $instance;

    private $setting;
    private $ads_txt_manager;

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    public function activationHook()
    {
        (new AdsTXTManager(new Setting()))->addRoutes();
        flush_rewrite_rules();
    }

    public function deactivationHook()
    {
        $this->ads_txt_manager->removeRoutes();
        flush_rewrite_rules();
    }

    public function pluginsLoadedHook()
    {
        $this->setting = new Setting();
        $this->ads_txt_manager = new AdsTXTManager($this->setting);

        $wp_integrations = [];
        $wp_integrations[] = $this->setting;
        $wp_integrations[] = $this->ads_txt_manager;
        $wp_integrations[] = new PostType();
        $wp_integrations[] = new AdsManager($this->setting);
        if (is_admin()) {
            $wp_integrations[] = new SettingPageManager($this->setting);
            $wp_integrations[] = new AdItemMetaBox($this->setting);
        }

        foreach ($wp_integrations as $integrations) {
            $integrations->register();
        }

        add_action('cli_init', function () {
        });
    }
}
