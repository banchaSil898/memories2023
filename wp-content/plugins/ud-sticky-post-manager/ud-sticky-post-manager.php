<?php
/**
 * Plugin Name: Unixdev Sticky Post Manager
 * Plugin URI:
 * Description: Unixdev Sticky Post Manager use only with Unixdev Newspaper based theme
 * Version: 1.2.3
 * Author: Unixdev
 * Author URI:
 * License: Unixdev
 *
 * PHP version 5
 *
 * @category PHP
 * @package  UnixdevStickyPostManager
 * @author   Unixdev <support@unixdev.co.th>
 * @license  proprietary
 * @link     www.unixdev.co.th
 */

if (! defined('ABSPATH')) {
    exit;
}

use UDStickyPostManager\UDStickyPostManager;

require __DIR__ . '/vendor/autoload.php';

UDStickyPostManager::getInstance();
