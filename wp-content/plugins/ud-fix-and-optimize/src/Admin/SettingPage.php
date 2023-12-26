<?php

namespace UDFixAndOptimize\Admin;

use UDFixAndOptimize\UDFixAndOptimize;
use UDFixAndOptimize\UDOptionFramework\Component\Field\CategoryChecklistField;
use UDFixAndOptimize\UDOptionFramework\Component\Field\CheckBoxField;
use UDFixAndOptimize\UDOptionFramework\Component\Field\IntegerWithUnitField;
use UDFixAndOptimize\UDOptionFramework\Component\Field\SelectField;
use UDFixAndOptimize\UDOptionFramework\Component\Field\TextField;
use UDFixAndOptimize\UDOptionFramework\Component\Option\IntegerWithUnitOption;
use UDFixAndOptimize\UDOptionFramework\Component\Page\Page;
use UDFixAndOptimize\UDOptionFramework\Component\Page\SubPage;
use UDFixAndOptimize\UDOptionFramework\Component\Section\Section;
use UDFixAndOptimize\UDOptionFramework\Component\Section\TabContainerSection;
use UDFixAndOptimize\UDOptionFramework\OptionFramework;
use UDFixAndOptimize\UDOptionFramework\Util\CategoryChecklistWalker;

if (! defined('ABSPATH')) {
    exit;
}

class SettingPage
{

    /**
     * @var OptionFramework $option_manager
     */
    private $option_manager;

    public function __construct()
    {
        $this->initMainOptionPage();

        add_action('admin_enqueue_scripts', array($this, 'initScriptsAndStyles'));
    }

    private function initMainOptionPage()
    {
        $main_page = new Page(
            'ud_fix_and_optimize_setting', //page slug
            'Unixdev Fix And Optimize Settings',
            'Unixdev Fix And Optimize Settings',
            'manage_options', //permission
            'dashicons-admin-tools', //icon
            null //position
        );

        OptionFramework::addPage($main_page);

        $general_page = new SubPage(
            'ud_fix_and_optimize_setting',
            $main_page,
            'General Settings',
            'General',
            'manage_options'
        );


        $general_tab_container = new TabContainerSection(
            'general_tab_container',
            $general_page
        );

        $wp_core_general_section = new Section(
            'wp_core_general_section',
            $general_tab_container,
            'General Fix and Optimization Setting',
            ''
        );

        $wprss_aggregator_section = new Section(
            'wprss_aggregator_section',
            $general_tab_container,
            'WP RSS Aggregator plugnin fix and optimize Settings',
            ''
        );

        $yoast_seo_section = new Section(
            'yoast_seo_section',
            $general_tab_container,
            'Yoast SEO fix and optimize Setting',
            ''
        );

        $instant_article_for_wp_section = new Section(
            'instant_article_for_wp_section',
            $general_tab_container,
            'Instant Article for WP fix and optimize Setting',
            ''
        );

        $media_library_categories_section = new Section(
            'media_library_categories_section',
            $general_tab_container,
            'Media Library Categories fix and optimize Setting',
            ''
        );

        $nobuna_section = new Section(
            'nobuna_section',
            $general_tab_container,
            'Nobuna fix and optimize Setting',
            ''
        );

        $duracellnomi_gtm_section = new Section(
            'duracellnomi_gtm_section',
            $general_tab_container,
            'Duracelltomi Google Tag Manager fix and optimize Setting',
            ''
        );

        $general_tab_container->setTabTitle($wp_core_general_section, 'Wordpress Core');
        $general_tab_container->setTabTitle($wprss_aggregator_section, 'WP RSS Aggregator');
        $general_tab_container->setTabTitle($yoast_seo_section, 'Yoast SEO');
        $general_tab_container->setTabTitle($instant_article_for_wp_section, 'Instant Article for WP');
        $general_tab_container->setTabTitle($media_library_categories_section, 'Media Library Categories');
        $general_tab_container->setTabTitle($nobuna_section, 'Nobuna');
        $general_tab_container->setTabTitle($duracellnomi_gtm_section, 'DuracelltomiGTM');

        //  Wordpress Core fix and optimize Setting' ----------------
        new CheckBoxField(
            'wpcore_optimize_postmeta_form_keys_enable',
            UDFixAndOptimize::OPTION_KEY,
            $wp_core_general_section,
            __('Enable postmeta form key optimization', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option optimize postmeta form key retriving by hooking postmeta_form_keys filter', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        new CheckBoxField(
            'wpcore_optimize_search_title_only_enable',
            UDFixAndOptimize::OPTION_KEY,
            $wp_core_general_section,
            __('Enable search only title', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option optimize search by get wordpress searching on post_title field only', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        new SelectField(
            'wpcore_optimize_wp_enqueue_media_audio_mode',
            UDFixAndOptimize::OPTION_KEY,
            $wp_core_general_section,
            __('Optimize wp_enqueue_media() for audio', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Set this option to showing or hiding the "Create Audio Playlist" button in the media library', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            array(
                'none'        => 'Default',
                'force_true'  => 'Force True',
                'force_false' => 'Force False',
                'force_null'  => 'Force Null'
            )
        );

        new SelectField(
            'wpcore_optimize_wp_enqueue_media_video_mode',
            UDFixAndOptimize::OPTION_KEY,
            $wp_core_general_section,
            __('Optimize wp_enqueue_media() for video', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Set this option to showing or hiding the "Create Video Playlist" button in the media library', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            array(
                'none'        => 'Default',
                'force_true'  => 'Force True',
                'force_false' => 'Force False',
                'force_null'  => 'Force Null'
            )
        );

        new IntegerWithUnitField(
            'wpcore_time_ago_after_publish_period',
            UDFixAndOptimize::OPTION_KEY,
            $wp_core_general_section,
            __('Apply "time ago" format after publish for ', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Set this option to show "time ago" format in post list page', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            array(
                'sec'  => 'Seconds',
                'min'  => 'Minutes',
                'hour' => 'Hours',
                'day'  => 'Days'
            )
        );

        new CheckBoxField(
            'wpcore_enable_time_on_date_column',
            UDFixAndOptimize::OPTION_KEY,
            $wp_core_general_section,
            __('Enable appending time on date column', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option to append time on date column', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        new CheckBoxField(
            'wpcore_enable_thumbnail_in_feed',
            UDFixAndOptimize::OPTION_KEY,
            $wp_core_general_section,
            __('Enable thumbnail in feed', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option will insert post\'s thumbnail in rss2 feed', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        new CheckBoxField(
            'wpcore_maintain_hierachical_term',
            UDFixAndOptimize::OPTION_KEY,
            $wp_core_general_section,
            __('Maintain hierachical term in metabox', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Maintain hierachical term in metabox on edit post page', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        //-------- WP RSS Aggregator plugin fix and optimize Settings' ---------------
        new CheckBoxField(
            'wprss_aggregator_utf8_encode_fix_enable',
            UDFixAndOptimize::OPTION_KEY,
            $wprss_aggregator_section,
            __('Enable UTF8 Encoding fix on WP RSS Aggregator plugin', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option encoding bug issue on WP RSS Aggregator plugin', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        new CheckBoxField(
            'wprss_aggregator_show_source_url_enable',
            UDFixAndOptimize::OPTION_KEY,
            $wprss_aggregator_section,
            __('Enable showing source url on edit page', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option will show source url of this post on edit page', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        new CategoryChecklistField(
            'wprss_aggregator_cats_enable_ext_source_post_link',
            UDFixAndOptimize::OPTION_KEY,
            $wprss_aggregator_section,
            __('Set post_link to external source', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option will set post_link to external source', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        //--------Instant Article for WP Plugin fix and optimize Settings' ---------------
        new CheckBoxField(
            'instant_article_use_yoast_primary_category_enable',
            UDFixAndOptimize::OPTION_KEY,
            $instant_article_for_wp_section,
            __('Instant Article for WP use Yoast Primary Category', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option will get Instant Article for WP using Yoast Primary Category', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        // ------------- Yoast SEO --------------------------------
        new CheckBoxField(
            'yoast_seo_remove_separator_from_title_enable',
            UDFixAndOptimize::OPTION_KEY,
            $yoast_seo_section,
            __('Remove all separator from title on og:title and twitter:title', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option will remove all separator from title on og:title and twitter:title', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        new CheckBoxField(
            'yoast_seo_enable_image_size_for_sharing',
            UDFixAndOptimize::OPTION_KEY,
            $yoast_seo_section,
            __('Enable image size for sharing', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option enable image size for sharing by calling function add_image_size("udfao_sharing_image", 1200, 9999)', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        // ------------- Media Library Categories --------------------------------
        new CheckBoxField(
            'mlc_disable_post_category_binding',
            UDFixAndOptimize::OPTION_KEY,
            $media_library_categories_section,
            __('Disable binding post_category taxonomy to attachment', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Disabling this option to disable binding post_category taxonomy to attachment', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        // ------------- Nobuna --------------------------------
        new CheckBoxField(
            'nobuna_remove_backup_button',
            UDFixAndOptimize::OPTION_KEY,
            $nobuna_section,
            __('Remove all Nobuna Backup Button on Plugin page', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option will remove all Nobuna Backup Button on Plugin page', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        new CheckBoxField(
            'nobuna_remove_admin_column_pro_license_notice',
            UDFixAndOptimize::OPTION_KEY,
            $nobuna_section,
            __('Remove Admin Column Pro License Notice', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option will remove Admin Column Pro License Notice on Plugin page', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        // ------------- Duracelltomi Google Tag Manager --------------------
        new CheckBoxField(
            'duracelltomi_gtm_fix_post_date_enable',
            UDFixAndOptimize::OPTION_KEY,
            $duracellnomi_gtm_section,
            __('Fix $dataLayer["pagePostDate"] date format', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option will fix $dataLayer["pagePostDate"] date format', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        new CheckBoxField(
            'duracelltomi_gtm_add_post_time_enable',
            UDFixAndOptimize::OPTION_KEY,
            $duracellnomi_gtm_section,
            __('Add $dataLayer["pagePostTime"]', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option will add $dataLayer["pagePostTime"]', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        new CheckBoxField(
            'duracelltomi_gtm_add_primary_category_enable',
            UDFixAndOptimize::OPTION_KEY,
            $duracellnomi_gtm_section,
            __('Add $dataLayer["pagePrimaryCategory"]', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option will add $dataLayer["pagePrimaryCategory"]', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        //================== pagespeed setting page =======================

        $pagespeed_page = new SubPage(
            'ud_fix_and_optimize_pagespeed_page',
            $main_page,
            'PageSpeed Optimization Setting',
            'PageSpeed',
            'manage_options'
        );

        $pagespeed_image_optimization_section = new Section(
            'pagespeed_image_optimization_section',
            $pagespeed_page,
            'Image Optimization Setting',
            ''
        );

        $pagespeed_javascript_optimization_section = new Section(
            'pagespeed_javascript_optimization_section',
            $pagespeed_page,
            'javascript Optimization Setting',
            ''
        );

        new CheckBoxField(
            'pagespeed_image_optimize_enable',
            UDFixAndOptimize::OPTION_KEY,
            $pagespeed_image_optimization_section,
            __('Enable Image optimization', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option to enable image optimization', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        new CheckBoxField(
            'pagespeed_original_image_optimize_enable',
            UDFixAndOptimize::OPTION_KEY,
            $pagespeed_image_optimization_section,
            __('Enable Original Image optimization', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Enabling this option to enable original image optimization. Note: "Enable Image optimization" must be enabled', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        new TextField(
            'pagespeed_defer_js_list',
            UDFixAndOptimize::OPTION_KEY,
            $pagespeed_javascript_optimization_section,
            __('Defer Javascript Loading', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Put comma-separated handle name that use on wp_enquere_script() function to use defer loading. Example: "jquery-migrate"', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        new TextField(
            'pagespeed_async_js_list',
            UDFixAndOptimize::OPTION_KEY,
            $pagespeed_javascript_optimization_section,
            __('Async Javascript Loading', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN),
            __('Put comma-separated handle name that use on wp_enquere_script() function to use async loading. Example: "jquery-migrate". Note: if There are handle name on Defer Javascript Loading option, they override these', UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN)
        );

        return $main_page;
    }

    public function initScriptsAndStyles($hook)
    {
        $hook_suffixes = OptionFramework::getScreensOfDomain();

        if (! empty($hook_suffixes) and in_array($hook, $hook_suffixes)) {
            wp_enqueue_style('ud-fix-and-optimize-admin', plugins_url('/assets/css/ud-fix-and-optimize-admin.css', UD_FIX_AND_OPTIMIZE_FILE), null, UD_FIX_AND_OPTIMIZE_VERSION);
            wp_enqueue_script('ud-fix-and-optimize-admin', plugins_url('/assets/js/ud-fix-and-optimize-admin.js', UD_FIX_AND_OPTIMIZE_FILE), array('jquery'), UD_FIX_AND_OPTIMIZE_VERSION);
        }
    }

    public function getOptionFramework()
    {
        return $this->option_manager;
    }
}
