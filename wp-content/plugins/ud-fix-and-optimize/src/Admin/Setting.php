<?php

namespace UDFixAndOptimize\Admin;

use UDFixAndOptimize\UDFixAndOptimize;
use UDFixAndOptimize\UDOptionFramework\Component\Option\ArrayOption;
use UDFixAndOptimize\UDOptionFramework\Component\Option\BooleanOption;
use UDFixAndOptimize\UDOptionFramework\Component\Option\ChoiceOption;
use UDFixAndOptimize\UDOptionFramework\Component\Option\IntegerWithUnitOption;
use UDFixAndOptimize\UDOptionFramework\Component\Option\StringOption;
use UDFixAndOptimize\UDOptionFramework\OptionFramework;

if (! defined('ABSPATH')) {
    exit;
}

class Setting
{
    public function __construct()
    {
        $this->initWPCoreSettings();
        $this->initWPRSSAggregatorPluginSettings();
        $this->initInstantArticleForWPPluginSettings();
        $this->initYoastSeoPluginSettings();
        $this->initMediaLibraryCategoriesPluginSettings();
        $this->initNobunaPluginSettings();
        $this->initDuracelltomiGTMPluginSettings();

        $this->initPageSpeedSettings();
    }

    private function initWPCoreSettings()
    {
        OptionFramework::registerOption(
            new BooleanOption(
                'wpcore_optimize_postmeta_form_keys_enable',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );

        OptionFramework::registerOption(
            new BooleanOption(
                'wpcore_optimize_search_title_only_enable',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );

        OptionFramework::registerOption(
            new ChoiceOption(
                'wpcore_optimize_wp_enqueue_media_audio_mode',
                UDFixAndOptimize::OPTION_KEY,
                'none',
                array(
                    'none',
                    'force_true',
                    'force_false',
                    'force_null',
                )
            )
        );

        OptionFramework::registerOption(
            new ChoiceOption(
                'wpcore_optimize_wp_enqueue_media_video_mode',
                UDFixAndOptimize::OPTION_KEY,
                'none',
                array(
                    'none',
                    'force_true',
                    'force_false',
                    'force_null',
                )
            )
        );

        OptionFramework::registerOption(
            new IntegerWithUnitOption(
                'wpcore_time_ago_after_publish_period',
                UDFixAndOptimize::OPTION_KEY,
                array(
                    'value' => 1,
                    'unit'  => 'day'
                ),
                array(
                    'sec'  => 1,
                    'min'  => MINUTE_IN_SECONDS,
                    'hour' => HOUR_IN_SECONDS,
                    'day'  => DAY_IN_SECONDS
                )
            )
        );

        OptionFramework::registerOption(
            new BooleanOption(
                'wpcore_enable_time_on_date_column',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );

        OptionFramework::registerOption(
            new BooleanOption(
                'wpcore_enable_thumbnail_in_feed',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );

        OptionFramework::registerOption(
            new BooleanOption(
                'wpcore_maintain_hierachical_term',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );
    }

    private function initWPRSSAggregatorPluginSettings()
    {
        OptionFramework::registerOption(
            new BooleanOption(
                'wprss_aggregator_utf8_encode_fix_enable',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );

        OptionFramework::registerOption(
            new BooleanOption(
                'wprss_aggregator_show_source_url_enable',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );

        OptionFramework::registerOption(
            new ArrayOption(
                'wprss_aggregator_cats_enable_ext_source_post_link',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );
    }

    private function initInstantArticleForWPPluginSettings()
    {
        OptionFramework::registerOption(
            new BooleanOption(
                'instant_article_use_yoast_primary_category_enable',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );
    }

    private function initYoastSeoPluginSettings()
    {
        OptionFramework::registerOption(
            new BooleanOption(
                'yoast_seo_remove_separator_from_title_enable',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );

        OptionFramework::registerOption(
            new BooleanOption(
                'yoast_seo_enable_image_size_for_sharing',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );
    }

    private function initMediaLibraryCategoriesPluginSettings()
    {
        OptionFramework::registerOption(
            new BooleanOption(
                'mlc_disable_post_category_binding',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );
    }

    private function initNobunaPluginSettings()
    {
        OptionFramework::registerOption(
            new BooleanOption(
                'nobuna_remove_backup_button',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );

        OptionFramework::registerOption(
            new BooleanOption(
                'nobuna_remove_admin_column_pro_license_notice',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );
    }


    private function initPageSpeedSettings()
    {
        OptionFramework::registerOption(
            new BooleanOption(
                'pagespeed_image_optimize_enable',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );

        OptionFramework::registerOption(
            new BooleanOption(
                'pagespeed_original_image_optimize_enable',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );

        OptionFramework::registerOption(
            new StringOption(
                'pagespeed_defer_js_list',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );

        OptionFramework::registerOption(
            new StringOption(
                'pagespeed_async_js_list',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );
    }

    private function initDuracelltomiGTMPluginSettings()
    {
        OptionFramework::registerOption(
            new BooleanOption(
                'duracelltomi_gtm_fix_post_date_enable',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );

        OptionFramework::registerOption(
            new BooleanOption(
                'duracelltomi_gtm_add_post_time_enable',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );

        OptionFramework::registerOption(
            new BooleanOption(
                'duracelltomi_gtm_add_primary_category_enable',
                UDFixAndOptimize::OPTION_KEY,
                false
            )
        );
    }
}
