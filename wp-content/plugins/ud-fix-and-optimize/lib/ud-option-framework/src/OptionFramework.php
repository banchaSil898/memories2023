<?php

namespace UDFixAndOptimize\UDOptionFramework;

use UDFixAndOptimize\UDOptionFramework\Core\Manager;
use UDFixAndOptimize\UDOptionFramework\Core\OptionManager;

if (! defined('ABSPATH')) {
    exit;
}


class OptionFramework
{

    /**
     * @var Manager $managers
     */
    private static $manager = null;


    public static function addPage($page)
    {
        if (! isset(self::$manager)) {
            self::$manager = new Manager();
        }

        self::$manager->addPage($page);
    }

    public static function getScreensOfDomain()
    {
        return self::$manager->getHookSuffixes();
    }

    public static function registerOption($option)
    {
        OptionManager::registerOption($option);
    }

    public static function getOptionValue($id, $option_group = '')
    {
        try {
            $option = OptionManager::getOption($id, $option_group);
        } catch (\Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);

            return null;
        }

        return $option->getCalculatedValue();
    }
}
