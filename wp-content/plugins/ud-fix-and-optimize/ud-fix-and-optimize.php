<?php
/**
 * Plugin Name: Unixdev Fix And Optimize Plugin
 * Plugin URI:
 * Description: This plugin provides some function hook to optimize or fix some core bug of wordpress and other plugins
 * Version: 1.5.12
 * Author: Unixdev
 * Author URI:
 * License: Unixdev
 *
 * PHP version 5
 *
 * @category PHP
 * @package  UDFixAndOptimize
 * @author   Unixdev <support@unixdev.co.th>
 * @license  www.unixdev.co.th Unixdev
 * @link     www.unixdev.co.th
 */

if (! defined('ABSPATH')) {
    exit;
}

use UDFixAndOptimize\UDFixAndOptimize;

require __DIR__ . '/vendor/autoload.php';

UDFixAndOptimize::getInstance();
