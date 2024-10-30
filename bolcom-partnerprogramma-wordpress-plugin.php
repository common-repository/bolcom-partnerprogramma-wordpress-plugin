<?php
/*
Plugin Name: Bol.com partner plugin for Wordpress
Plugin URI: http://wordpress.org/plugins/bolcom-partnerprogramma-wordpress-plugin/
Description: This plugin is for bol.com affiliate partners. It enables the placement of Bol.com products from the Bol.com openAPI. Content can be added through the text editor and by widgets.
Author: Netvlies Internetdiensten
Version: 1.4.15
Author URI: http://www.netvlies.nl
License: MIT
*/
if (PHP_VERSION_ID < 50300) {
    exit('Your PHP version is ' . PHP_VERSION . ', but to use the Bol.com partner plugin you need PHP version >= 5.3');
}

require_once 'vendor/bolcom-autoload.php';
include_once 'src/BolPartnerPlugin/Plugin.php';
include_once 'src/BolPartnerPlugin/PluginInstaller.php';

if (! defined('BOL_PARTNER_BASEDIR')) {
    define('BOL_PARTNER_BASEDIR', __DIR__);
    define('BOL_PARTNER_PLUGIN_PATH', plugin_dir_url(dirname(__FILE__)) . plugin_basename(dirname(__FILE__)));
    $include = array(ini_get('include_path'));
    $include[] = BOL_PARTNER_BASEDIR . '/src';
    $include[] = BOL_PARTNER_BASEDIR . '/vendor';
    ini_set('include_path', implode(PATH_SEPARATOR, $include));
}

if (! defined('BOL_PARTNER_CONFIG_MENU_SLUG')) {
    define('BOL_PARTNER_CONFIG_MENU_SLUG', 'boldotcom_partnerplugin');
}

// Registering the activate, deactivate and uninstall action hooks with the installer class
//register_activation_hook( __FILE__, array( 'BolPartnerPlugin\Installer', 'activate' ) );
//register_deactivation_hook( __FILE__, array( 'BolPartnerPlugin\Installer', 'deactivate' ) );
//register_uninstall_hook( __FILE__, array( 'BolPartnerPlugin\Installer', 'uninstall' ) );

// Register translation
function boldotcom_partnerplugin_load_textdomain()
{
    load_plugin_textdomain('bolcom-partnerprogramma-wordpress-plugin', false, basename( dirname( __FILE__ ) ) . '/resources/translation' );
}

add_action('init', 'boldotcom_partnerplugin_load_textdomain');

// Loading the plugin class and widgets
$config = parse_ini_file('config.ini', true);
$class = 'BolPartnerPlugin\\Plugin';
$bolPartnerPlugin = new $class($config['plugin']);
$bolPartnerPlugin->init();

// When activating plugin save options in the database
register_activation_hook( __FILE__, array('BolPartnerPlugin\AdminPage\Config', 'activate'));
