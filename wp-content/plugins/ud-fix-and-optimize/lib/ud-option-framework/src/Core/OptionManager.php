<?php

namespace UDFixAndOptimize\UDOptionFramework\Core;

use UDFixAndOptimize\UDOptionFramework\Component\Base\AbstractOption;

if (! defined('ABSPATH')) {
    exit;
}


class OptionManager
{

    /**
     * @var AbstractOption[] $options
     */
    private static $options = array();

    private static $default_option_structures = array();


    /**
     * @param AbstractOption $option
     * @throws \Exception
     */
    public static function registerOption($option)
    {
        $option_group = $option->getGroupName();
        $option_id = $option->getID();
        $default_value = $option->getDefaultValue();

        if (empty($option_group)) {
            if (isset($option_id)) {
                throw new \Exception("Duplicate option id:{$option_id}");
            }
            self::$options[$option_id] = $option;
            self::$default_option_structures[$option_id] = $default_value;
        } else {
            if (isset(self::$options[$option_group][$option_id])) {
                throw new \Exception("Duplicate option id:{$option_id} group:{$option_group}");
            }

            if (! isset(self::$options[$option_group])) {
                self::$options[$option_group] = array();
                self::$default_option_structures[$option_group][$option_id] = array();
            }
            self::$options[$option_group][$option_id] = $option;
            self::$default_option_structures[$option_group][$option_id] = $default_value;
        }
    }


    /**
     * @param        $id
     * @param string $option_group
     * @return AbstractOption
     * @throws \Exception
     */
    public static function getOption($id, $option_group = '')
    {
        if (empty($option_group)) {
            if (! isset(self::$options[$id]) or is_array(self::$options[$id])) {
                throw new \Exception("Can't get option {$id}, it's not been registered yet");
            }

            return self::$options[$id];
        } else {
            if (! isset(self::$options[$option_group][$id])) {
                throw new \Exception("Can't get option {$option_group}[$id], it's not been registered yet");
            }

            return self::$options[$option_group][$id];
        }
    }

    public static function getOptionGroup($option_group)
    {
        if (! isset(self::$options[$option_group])) {
            if (is_array(self::$options[$option_group])) {
                throw new \Exception("Can't get option group {$option_group}, it's not been registered yet");
            } else {
                throw new \Exception("Can't get option {$option_group}, it's not been registered yet");
            }
        }

        return self::$options[$option_group];
    }

    public static function isOptionGroup($id)
    {
        return is_array(self::$options[$id]);
    }

    public static function getAllOption()
    {
        return self::$options;
    }

    public static function getAllOptionKeys()
    {
        return array_keys(self::$options);
    }

    public static function getDefaultOptionStructure($option_key)
    {
        return self::$default_option_structures[$option_key];
    }
}
