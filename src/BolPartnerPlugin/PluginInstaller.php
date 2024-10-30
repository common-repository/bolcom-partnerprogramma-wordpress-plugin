<?php
/*
 * This file is part of the Bol-Partner-Plugin for Wordpress.
 *
 * (c) Netvlies Internetdiensten
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BolPartnerPlugin;

/**
 * Standard installer class for handling the hooks into wordpress' activation, deactivation and uninstall actions
 */
class Installer
{
    // Set this to true to get the state of origin, so you don't need to always uninstall during development.
    const STATE_OF_ORIGIN = false;

    function __construct( $case = false )
    {
        if ( ! $case ) {
            wp_die( 'Busted! You should not call this class directly', 'Doing it wrong!' );
        }

        if (! in_array($case, array('activate', 'deactivate', 'uninstall'))) {
            wp_die( 'Busted! Invalid case: ' . $case, 'Doing it wrong!' );
        }

        add_action('init', array($this, $case));
    }

    /**
     * Set up tables, add options, etc. - All preparation that only needs to be done once
     */
    public static function onActivate()
    {
        new Bol_Partner_PluginInstaller('activate');
    }

    /**
     * Do nothing like removing settings, etc.
     * The user could reactivate the plugin and wants everything in the state before activation.
     * Take a constant to remove everything, so you can develop & test easier.
     */
    public static function onDeactivate()
    {
        $case = 'deactivate';
        if ( STATE_OF_ORIGIN )
            $case = 'uninstall';

        new Bol_Partner_PluginInstaller($case);
    }

    /**
     * Remove/Delete everything - If the user wants to uninstall, then he wants the state of origin.
     *
     * Will be called when the user clicks on the uninstall link that calls for the plugin to uninstall itself
     */
    public static function onUninstall()
    {
        // important: check if the file is the one that was registered with the uninstall hook (function)
        if (__FILE__ != WP_UNINSTALL_PLUGIN)
            return;

        new Bol_Partner_PluginInstaller('uninstall');
    }

    function activate()
    {
        chdir(BOL_PARTNER_BASEDIR);
        // check if we have the vendors installed, make it happen if not yet installed!
        if (! is_writable(BOL_PARTNER_BASEDIR)) {
            self::error(BOL_PARTNER_BASEDIR . ' directory needs to be writable. '
                . 'Please refer to the plugin readme.txt for more information.', E_USER_ERROR);
            return false;
        }

        if (! file_exists('composer.phar')) {
            self::error('Installing composer for loading additional libraries', E_USER_ERROR);
            $return = exec('curl -s http://getcomposer.org/installer | php');

            if ($return == false) {
                self::error('Unable to install vendors, see readme.txt for more information.', E_USER_ERROR);
                return false;
            }
        }

        $return = exec('./composer.phar install');

        if ($return == false) {
            self::error('Unable to install vendors, see readme.txt for more information.', E_USER_ERROR);
            return false;
        }

        return true;
    }

    function deactivate()
    {
    }

    function uninstall()
    {
//        chdir(BOL_PARTNER_BASEDIR);
//        exec('rm -rf composer.lock vendor');
//        return true;
    }

    /**
     * trigger_error()
     *
     * @param (string) $error_msg
     * @param (boolean) $fatal_error | catched a fatal error - when we exit, then we can't go further than this point
     * @param unknown_type $error_type
     * @return void
     */
    function error( $msg, $fatal = false, $type = E_USER_ERROR )
    {
        if( isset( $_GET['action'] ) && 'error_scrape' == $_GET['action'] ) {
            echo $msg . PHP_EOL;
            if ($fatal) {
                exit;
            }
        } else {
            trigger_error($msg, $type);
        }
    }
}

