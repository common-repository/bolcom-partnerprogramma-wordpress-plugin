<?php
/**
 * This file is part of the Bol-Partner-Plugin for Wordpress.
 *
 * (c) Netvlies Internetdiensten
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
$pluginPath = __DIR__ . '/../..';
$wp_path = $pluginPath . '/../../..';
require_once $wp_path . '/wp-load.php';

use BolPartnerPlugin\ApiClientFactory;
use BolOpenApi\Client;
use BolPartnerPlugin\Widgets\Renderer\ProductLinksRenderer;
use BolPartnerPlugin\Widgets\SelectedProducts;

function getProducts($client, array $productIds)
{
    $products = array();

    foreach ($productIds as $product) {

        try {
            $response = $client->products($product);

            if ($response->getProduct()) {
                $products[] = $response->getProduct();
            }
        } catch (\BolOpenApi\Exception $e) {
            // Error occurred notify the user
            echo __('Error: Connection with Bol.com cannot be established', 'bolcom-partnerprogramma-wordpress-plugin');
            break;
        } catch (\RuntimeException $e) {
            echo __('Error: Connection with Bol.com cannot be established', 'bolcom-partnerprogramma-wordpress-plugin');
            break;
        }
    }

    return $products;
}

$valid = array_keys(SelectedProducts::getDefaultAttributes());

// check the post credentials
$options = array();
foreach ($valid as $id) {
    if (isset($_POST[$id])) {
        $options[$id] = $_POST[$id];
    }
}

// @Todo Danny should this not be removed?
$options = $_POST;
$products = explode(',', trim($_POST['products'], ','));
if (empty($products)) {
    die;
}

$partnerSettings = get_option('bol_partner_settings');
$openApiSettings = get_option('bol_openapi_settings');
$options['partnerId'] = $partnerSettings['site_id'];

// load the products
$accessKey = $partnerSettings['access_key'];
$secretKey = $openApiSettings['access_key'];
$client = ApiClientFactory::getCreateClient($accessKey, $secretKey);

// We want to show a preview
if (isset($_POST['admin_preview']) && $_POST['admin_preview'] == 1) {
    // Render all elements that can be hidden
    $options['show_bol_logo'] = 1;
    $options['show_price'] = 1;
    $options['show_rating'] = 1;
    $options['show_deliverytime'] = 1;
}

$products = getProducts($client, $products);

// display the products
if (! empty($products)) {
    // Add widget identifier
    $options['widget_type'] ='product-link';

    $renderer = new ProductLinksRenderer($products, $options);
    echo $renderer;
}
