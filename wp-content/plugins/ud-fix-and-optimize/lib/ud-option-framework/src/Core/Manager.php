<?php

namespace UDFixAndOptimize\UDOptionFramework\Core;

use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractField;
use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractOption;
use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractPage;
use UDFixAndOptimize\UDOptionFramework\Component\Exception\InvalidOptionValueException;
use UDFixAndOptimize\UDOptionFramework\Component\Page\Page;
use UDFixAndOptimize\UDOptionFramework\Component\Page\SubPage;
use UDFixAndOptimize\UDOptionFramework\OptionFramework;

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
            //            add_filter("default_option_{$key}", array($this, 'getDefaultOptions'), 10, 3);
        }

        foreach ($this->root_pages as $root_page) {
            $root_page->init();
        }
    }

    public function sanitizeOptions($value, $option_name, $original_value)
    {
        $invalid_value_exceptions = array();
        $sanitized_values = null;

        if (OptionManager::isOptionGroup($option_name)) {
            if (! is_array($value)) {
                return null;
            }

            $old_values = get_option($option_name);
            if (empty($old_values)) {
                $old_values = array();
            }

            try {
                $options = OptionManager::getOptionGroup($option_name);
                $default = OptionManager::getDefaultOptionStructure($option_name);
            } catch (\Exception $e) {
                return null;
            }

            foreach ($value as $option_id => $val) {
                //filter out non-register option
                if (! isset($options[$option_id])) {
                    continue;
                }

                /* @var AbstractOption $option */
                $option = $options[$option_id];

                try {
                    $val = $option->validate($val);
                    $sanitized_values[$option_id] = $val;
                } catch (InvalidOptionValueException $e) {
                    $invalid_value_exceptions[$option_id] = $e;
                }
            }

            $sanitized_values = array_merge($old_values, $sanitized_values);

            foreach (array_keys($sanitized_values) as $option_id) {
                if ($default[$option_id] === $sanitized_values[$option_id]) {
                    unset($sanitized_values[$option_id]);
                }
            }

        } else {
            try {
                $option = OptionManager::getOption($option_name);
                $default = OptionManager::getDefaultOptionStructure($option_name);
            } catch (\Exception $e) {
                return null;
            }

            try {
                $val = $option->validate($value);
                if ($default !== $val) {
                    $sanitized_values = $val;
                }
            } catch (InvalidOptionValueException $e) {
                $invalid_value_exceptions = $e;
            }
        }


        if (! empty($invalid_value_exceptions)) {
            if (is_array($invalid_value_exceptions)) {
                foreach ($invalid_value_exceptions as $e) {
                    add_settings_error(
                        $option_name,
                        $e->getCode(),
                        $e->getMessage(),
                        'error'
                    );
                }
            } else {
                $e = $invalid_value_exceptions;
                add_settings_error(
                    $option_name,
                    $e->getCode(),
                    $e->getMessage(),
                    'error'
                );
            }
        }

        return $sanitized_values;
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
