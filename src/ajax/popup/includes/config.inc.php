<?php
/**
 * This file is part of the Bol-Partner-Plugin for Wordpress.
 *
 * (c) Netvlies Internetdiensten
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
define('WP_USE_THEMES', false);

$pluginPath = __DIR__ . '/../../../..';
$wp_path = $pluginPath . '/../../..';
require_once $wp_path . '/wp-load.php';

if (! is_user_logged_in()) {
    die;
}

?>
