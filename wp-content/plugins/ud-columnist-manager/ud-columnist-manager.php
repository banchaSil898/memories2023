<?php
/**
 * Plugin Name: Unixdev Columnist Manager
 * Plugin URI:
 * Description: Unixdev Columnist Manager use only with Unixdev Newspaper based theme
 * Version: 1.0.14
 * Author: Unixdev
 * Author URI:
 * License: Unixdev
 *
 * PHP version 5
 *
 * @category PHP
 * @package  UnixdevColumnistManager
 * @author   Unixdev <support@unixdev.co.th>
 * @license  proprietary
 * @link     www.unixdev.co.th
 */

if (! defined('ABSPATH')) {
    exit;
}

define('UD_COLUMNIST_MANAGER_VERSION', '1.0.14');

if (! defined('UD_COLUMNIST_MANAGER_FILE')) {
    define('UD_COLUMNIST_MANAGER_FILE', __FILE__);
}

if (! defined('UD_COLUMNIST_MANAGER_PATH')) {
    define('UD_COLUMNIST_MANAGER_PATH', plugin_dir_path(UD_COLUMNIST_MANAGER_FILE));
}

if (! defined('UD_COLUMNIST_MANAGER_BASENAME')) {
    define('UD_COLUMNIST_MANAGER_BASENAME', plugin_basename(UD_COLUMNIST_MANAGER_FILE));
}

if (! defined('UD_COLUMNIST_MANAGER_URL')) {
    define('UD_COLUMNIST_MANAGER_URL', plugin_dir_url(UD_COLUMNIST_MANAGER_FILE));
}

if (! defined('UD_COLUMNIST_MANAGER_TEXT_DOMAIN')) {
    define('UD_COLUMNIST_MANAGER_TEXT_DOMAIN', 'ud-columnist-manager');
}

/**
 * Init social plugins
 */

use UDColumnistManager\ColumnistManager;

require_once UD_COLUMNIST_MANAGER_PATH . 'vendor/autoload.php';

new ColumnistManager();