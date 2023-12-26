<?php

namespace UDPostViewCounter\Admin;

if (! defined('ABSPATH')) {
    exit;
}

use UDPostViewCounter\UDOptionFramework\Component\Field\TextField;
use UDPostViewCounter\UDPostViewCounter;
use UDPostViewCounter\UDOptionFramework\Component\Page\Page;
use UDPostViewCounter\UDOptionFramework\Component\Section\Section;
use UDPostViewCounter\UDOptionFramework\OptionFramework;

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
            'ud_post_view_counter_setting', //page slug
            'Unixdev Post View Counter Settings',
            'Unixdev Post View Counter Settings',
            'manage_options', //permission
            'dashicons-admin-tools', //icon
            null //position
        );

        OptionFramework::addPage($main_page);

        $main_section = new Section(
            'main_section',
            $main_page,
            '',
            ''
        );

        //  Wordpress Core fix and optimize Setting' ----------------
        new TextField(
            'secret_key',
            UDPostViewCounter::OPTION_KEY,
            $main_section,
            __('Secret Key', UD_POST_VIEW_COUNTER_TEXT_DOMAIN),
            __('Fill Secret Key', UD_POST_VIEW_COUNTER_TEXT_DOMAIN)
        );

        new TextField(
            'url',
            UDPostViewCounter::OPTION_KEY,
            $main_section,
            __('URL to redis server', UD_POST_VIEW_COUNTER_TEXT_DOMAIN),
            __('URL to redis server', UD_POST_VIEW_COUNTER_TEXT_DOMAIN)
        );

        new TextField(
            'slug',
            UDPostViewCounter::OPTION_KEY,
            $main_section,
            __('Website Slug', UD_POST_VIEW_COUNTER_TEXT_DOMAIN),
            __('slug for each website (unique ID)', UD_POST_VIEW_COUNTER_TEXT_DOMAIN)
        );

        return $main_page;
    }

    public function initScriptsAndStyles($hook)
    {
        $hook_suffixes = OptionFramework::getScreensOfDomain();

        if (! empty($hook_suffixes) and in_array($hook, $hook_suffixes)) {
            //wp_enqueue_style( 'ud-fix-and-optimize-admin', plugins_url( '/assets/css/ud-fix-and-optimize-admin.css', UD_POST_VIEW_COUNTER_FILE ), null,UD_POST_VIEW_COUNTER_VERSION );
//            wp_enqueue_script('ud-fix-and-optimize-admin', plugins_url('assets/js/ud-fix-and-optimize-admin.js', UD_POST_VIEW_COUNTER_FILE), array('jquery'), UD_POST_VIEW_COUNTER_VERSION);
        }
    }

    public function getOptionFramework()
    {
        return $this->option_manager;
    }
}
