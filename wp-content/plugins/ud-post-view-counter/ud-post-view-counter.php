<?php
/**
 * Plugin Name: Unixdev Post View Counter
 * Plugin URI:
 * Description: This plugin provide counter functional
 * Version: 2.1.6
 * Author: Unixdev
 * Author URI:
 * License: Unixdev
 *
 * PHP version 5
 *
 * @category PHP
 * @package  UDPostViewCounter
 * @author   Unixdev <support@unixdev.co.th>
 * @license  www.unixdev.co.th Unixdev
 * @link     www.unixdev.co.th
 */

if (! defined('ABSPATH')) {
    exit;
}

use UDPostViewCounter\UDPostViewCounter;

require __DIR__ . '/vendor/autoload.php';

UDPostViewCounter::getInstance();
