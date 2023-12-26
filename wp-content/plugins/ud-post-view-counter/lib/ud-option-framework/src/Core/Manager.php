<?php

namespace UDPostViewCounter\UDOptionFramework\Core;

use UDPostViewCounter\UDOptionFramework\Component\Base\AbstractField;
use UDPostViewCounter\UDOptionFramework\Component\Base\AbstractPage;
use UDPostViewCounter\UDOptionFramework\Component\Exception\InvalidOptionValueException;
use UDPostViewCounter\UDOptionFramework\Component\Page\Page;
use UDPostViewCounter\UDOptionFramework\Component\Page\SubPage;
use UDPostViewCounter\UDOptionFramework\OptionFramework;

if (! defined('ABSPATH')) {
    exit;
}


class Manager
{
    /**
     * @var AbstractPage[]
     */
    private $pages;

    /**
     * @var AbstractPage[]
     */
    private $root_pages;

    //    /**
    //     * @var SubPage[]
    //     */
    //    private $sub_pages;


    /**
     * @var AbstractField[]
     */
    private $option_fields;


    public function __construct()
    {
        $this->pages = array();
        $this->root_pages = array();
        $this->option_fields = array();

        add_action('admin_init', array($this, 'initOption'));
        add_action('admin_menu', array($this, 'initMenu'));
        //        add_action('init', array($this, 'buildOptionFieldsMap'));
    }

    /**
     * @param AbstractPage $page
     */
    public function addPage($page)
    {
        //        if ($page instanceof Page) {
        //
        //        } else {
        //            if ($page instanceof SubPage) {
        //
        //            }
        //        }
        array_push($this->pages, $page);
    }

    public function initMenu()
    {
        foreach ($this->pages as $page) {
            if ($page instanceof Page) {
                $page->initMenu();
                if (! $page->hasSharedSlugSubPage()) {
                    $this->root_pages[$page->getID()] = $page;
                }

                foreach ($page->getSubPages() as $sub_page) {
                    $sub_page->initMenu();
                    $this->root_pages[$sub_page->getID()] = $sub_page;
                }
            }
        }
    }

    public function initOption()
    {

        $option_keys = OptionManager::getAllOptionKeys();

        foreach ($option_keys as $key) {
            add_filter("sanitize_option_{$key}", array($this, 'sanitizeOptions'), 10, 3);
            add_filter("default_option_{$key}", array($this, 'getDefaultOptions'), 10, 3);
        }

        foreach ($this->root_pages as $root_page) {
            $root_page->init();
        }
    }

    public function sanitizeOptions($value, $option_name, $original_value)
    {
        $invalid_value_exceptions = array();

        $page_id = $_POST['option_page'];

        $fields = $this->root_pages[$page_id]->getOptionFields();

        if (is_array($fields[$option_name])) {
            $old_values = get_option($option_name);
            $default = OptionManager::getDefaultOptionStructure($option_name);

            $old_values = array_intersect_key($old_values, $default);

            foreach ($fields[$option_name] as $option_id => $field) {
                $val = $value[$option_id];
                try {
                    $val = $field->validate($val);
                    $value[$option_id] = $val;
                } catch (InvalidOptionValueException $e) {
                    $invalid_value_exceptions[$option_id] = $e;

                    //clear wrong option
                    unset($value[$option_id]);
                    unset($old_values[$option_id]);
                }

                //check if value equal default value so don't need to save it
                if ($default[$option_id] === $value[$option_id]) {
                    unset($value[$option_id]);
                    unset($old_values[$option_id]);
                }
            }

            $value = array_intersect_key($value, $default);
            $value = array_merge($old_values, $value);
        } else {
            try {
                $value = $fields[$option_name]->validate($value);
            } catch (InvalidOptionValueException $e) {
                $invalid_value_exceptions[$option_name] = $e;

                //clear wrong option
                $value = '';
            }
        }

        if (! empty($invalid_value_exceptions)) {
            foreach ($invalid_value_exceptions as $e) {
                add_settings_error(
                    $option_name,
                    $e->getCode(),
                    $e->getMessage(),
                    'error'
                );
            }
        } else {
            //
            //            $buffy = '<ul>';
            //            foreach ($err_messages as $message) {
            //                $buffy .= '<li>' . $message . '</li>';
            //            }
            //            $buffy .= '</ul>';
            //
            //            add_settings_error(
            //                $this->option_domain_name,
            //                esc_attr('settings_failed'),
            //                $buffy,
            //                'error'
            //            );
        }

        return $value;
    }

    public function getDefaultOptions($default, $option, $passed_default)
    {
        if (false === $passed_default) {
            $default = OptionManager::getDefaultOptionStructure($option);
        }

        return $default;
    }

    public function getHookSuffixes()
    {
        $hook_suffixes = array();
        foreach ($this->root_pages as $page) {
            $hook_suffixes = array_merge($hook_suffixes, $page->getHookSuffixes());
        }

        return $hook_suffixes;
    }

    public function getOption($option_group_name, $option_id)
    {
        $value = null;
        if (isset($option_fields[$option_group_name][$option_id])) {
            $value = $option_fields[$option_group_name][$option_id]->getOptionValue();
        }

        return $value;
    }

    public function getOptionFields()
    {
        return $this->getOptionFields();
    }
}
