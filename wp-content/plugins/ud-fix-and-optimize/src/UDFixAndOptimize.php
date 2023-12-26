<?php

namespace UDFixAndOptimize;

/**
 * UDFixAndOptimize
 * @category Class
 * @package  UDFixAndOptimize
 * @author   Unixdev
 */

if (! defined('ABSPATH')) {
    exit;
}

define('UD_FIX_AND_OPTIMIZE_VERSION', '1.5.12');

if (! defined('UD_FIX_AND_OPTIMIZE_FILE')) {
    define('UD_FIX_AND_OPTIMIZE_FILE', dirname(dirname(__FILE__)) . '/ud-fix-and-optimize.php');
}

if (! defined('UD_FIX_AND_OPTIMIZE_PATH')) {
    define('UD_FIX_AND_OPTIMIZE_PATH', plugin_dir_path(UD_FIX_AND_OPTIMIZE_FILE));
}

if (! defined('UD_FIX_AND_OPTIMIZE_BASENAME')) {
    define('UD_FIX_AND_OPTIMIZE_BASENAME', plugin_basename(UD_FIX_AND_OPTIMIZE_FILE));
}

if (! defined('UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN')) {
    define('UD_FIX_AND_OPTIMIZE_TEXT_DOMAIN', 'ud-fix-and-optimize');
}

use UDFixAndOptimize\Admin\Admin;
use UDFixAndOptimize\CLI\CLIManager;
use UDFixAndOptimize\Core\DuracelltomiGTM;
use UDFixAndOptimize\Core\InstantArticlesForWP;
use UDFixAndOptimize\Core\MediaLibraryCategories;
use UDFixAndOptimize\Core\Nobuna;
use UDFixAndOptimize\Core\PageSpeed;
use UDFixAndOptimize\Core\WPCore;
use UDFixAndOptimize\Core\WPRSSAggregator;
use UDFixAndOptimize\Core\YoastSEO;

/**
 * Class UDFixAndOptimize
 */
class UDFixAndOptimize
{

    const OPTION_KEY = 'ud_fix_and_optimize_options';

    /**
     * Maintain singleton.
     * @var $instance
     */
    private static $instance;

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        if (class_exists('\WP_CLI')) {
            new CLIManager();
        } else {
            new Admin();
            new WPCore();
            new InstantArticlesForWP();
            new WPRSSAggregator();
            new YoastSEO();
            new MediaLibraryCategories();
            new Nobuna();
            new DuracelltomiGTM();

            new PageSpeed();
        }
    }

    public function enqueueScriptAndStyle()
    {
        //        wp_enqueue_style( 'ud-fix-and-optimize', plugins_url( '/assets/css/ud-fix-and-optimize.css', UD_FIX_AND_OPTIMIZE_FILE ), null, UD_FIX_AND_OPTIMIZE_VERSION );
        //        wp_enqueue_script('ud-fix-and-optimize', plugins_url('/assets/js/ud-fix-and-optimize.js', UD_FIX_AND_OPTIMIZE_FILE), array('jquery'), UD_FIX_AND_OPTIMIZE_VERSION);
    }
}
