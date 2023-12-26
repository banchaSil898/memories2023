<?php
/**
 * Plugin Name: Unixdev Ads Manager
 * Plugin URI:
 * Description: Unixdev Ads Manager
 * Version: 2.4.0
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Unixdev
 * Author URI:
 * License: Unixdev
 *
 * @package UDAdsManager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


define( 'UD_ADS_MANAGER_VERSION', '2.3.3' );
define( 'UD_ADS_MANAGER_FILE', __FILE__ );
define( 'UD_ADS_MANAGER_PATH', plugin_dir_path( UD_ADS_MANAGER_FILE ) );
define( 'UD_ADS_MANAGER_URL', plugins_url( '', UD_ADS_MANAGER_FILE ) );
define( 'UD_ADS_MANAGER_BASENAME', plugin_basename( UD_ADS_MANAGER_FILE ) );
define( 'UD_ADS_MANAGER_TEXT_DOMAIN', 'ud-ads-manager' );

use UDAdsManager\Plugin;

require __DIR__ . '/vendor/autoload.php';

$ud_ads_plugin = Plugin::getInstance();

add_action( 'plugins_loaded', array( $ud_ads_plugin, 'pluginsLoadedHook' ) );
register_activation_hook( UD_ADS_MANAGER_FILE, array( $ud_ads_plugin, 'activationHook' ) );
register_deactivation_hook( UD_ADS_MANAGER_FILE, array( $ud_ads_plugin, 'deactivationHook' ) );
