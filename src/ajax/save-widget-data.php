<?php
$pluginPath = __DIR__ . '/../..';
$wp_path = $pluginPath . '/../../..';
require_once $wp_path . '/wp-load.php';

$widgets = 'widget_bol_partner_selected_products|widget_bol_partner_bestsellers|widget_bol_partner_search_form';

if (empty($_POST['widget'])) {
    die;
}

// @TODO: filter!

if (preg_match('/^(' . $widgets . ')-([\d]+)$/', $_POST['widget'], $matches)) {
    $widget_name = $matches[1];
    $number = $matches[2];

    unset($_POST['widget']);

    $settings = get_option($widget_name);

    $settings = is_array($settings) ? $settings : array();

    $max = 0;
    foreach ($settings as $key => $stack) {
        if (is_int($key) && $key > $max) {
            $max = $key;
        }
    }

    $settings[$max] = isset($settings[$max]) ? array_merge($settings[$max], $_POST) : $_POST;
    update_option($widget_name, $settings);
    echo 'success';
}


