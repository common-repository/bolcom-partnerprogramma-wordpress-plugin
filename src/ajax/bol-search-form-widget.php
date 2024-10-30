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
use Buzz\Browser as BuzzBrowser;
use BolOpenApi\Client;
use BolPartnerPlugin\Widgets\Renderer\ProductSearchFormRenderer;
use BolPartnerPlugin\Widgets\SearchForm;

function getCategories(Client $client, $id = 0)
{
    $options = array('includeRefinements' => false);

    try {
        $response = $client->listResults('toplist_default', $id, $options);
    } catch (\Exception $e) {
        // Error occurred notify the user
        echo __('Error: Connection with Bol.com cannot be established', 'bolcom-partnerprogramma-wordpress-plugin'); die;
    } catch (\RuntimeException $e) {
        echo __('Error: Connection with Bol.com cannot be established', 'bolcom-partnerprogramma-wordpress-plugin'); die;
    }

    return $response->getCategories();
}

/**
 * @param BolOpenApi\Client $client
 * @param array $options
 * @param string $type
 * @return array|BolOpenApi\Response\ListResultsResponse
 */
function getResult(Client $client, array $options, $type = 'toplist_default')
{
    $categoryId = $options['cat_id'];

    $params = array(
        'nrProducts' => intval(isset($options['limit']) && ($options['limit'] > 0) ? $options['limit'] : 5),
        'includeProducts' => true,
        'includeCategories' => true,
        'includeRefinements' => false
    );

    try {
        $response = $client->listResults($type, $categoryId, $params);
    } catch (\Exception $e) {
        // Error occurred notify the user
        echo __('Error: Connection with Bol.com cannot be established', 'bolcom-partnerprogramma-wordpress-plugin');
    } catch (\RuntimeException $e) {
        echo __('Error: Connection with Bol.com cannot be established', 'bolcom-partnerprogramma-wordpress-plugin');
    }
}

/**
 * @param BolOpenApi\Client $client
 * @param $text
 * @param int $category
 * @param int $limit
 * @param int $offset
 * @return BolOpenApi\Response\SearchResultsResponse
 */
function getSearch(Client $client, $text, $category = 0, $limit = 10, $offset = 0)
{
    // When no search term is given return
    if (empty($text)) {
        return;
    }

    $options = array(
        'offset' => $offset,
        'nrProducts' => $limit,
    );

    if ($category > 0) {
        $options['categoryId'] = $category;
    }

    try {
        $response = $client->searchResults($text, $options);
        return $response;
    } catch (\BolOpenApi\Exception $e) {
        // Error occurred notify the user
        return null;
    } catch (\RuntimeException $e) {
        echo __('Error: Connection with Bol.com cannot be established', 'bolcom-partnerprogramma-wordpress-plugin'); die;
    }
}

$valid = array_keys(SearchForm::getDefaultAttributes());

// check the post credentials
$options = array();
foreach ($valid as $id) {
    if (isset($_POST[$id])) {
        $options[$id] = $_POST[$id];
    }
}

$partnerSettings = get_option('bol_partner_settings');
$openApiSettings = get_option('bol_openapi_settings');
$options['partnerId'] = $partnerSettings['site_id'];

// load the products
$partnerSettings = get_option('bol_partner_settings');
$openApiSettings = get_option('bol_openapi_settings');
$accessKey = $partnerSettings['access_key'];
$secretKey = $openApiSettings['access_key'];
$client = ApiClientFactory::getCreateClient($accessKey, $secretKey);
if(!isset($options['offset']) || empty($options['offset'])) $options['offset'] = 0;
$result = getSearch($client, $options['default_search'], $options['cat_id'], $options['limit'], $options['offset']);

// display the products
$options['totalResults'] = is_null($result) ? 0 : $result->getTotalResultSize();

// We want to show a preview
if (isset($_POST['admin_preview']) && $_POST['admin_preview'] == 1) {
    // Render all elements that can be hidden
    $options['show_bol_logo'] = 1;
    $options['show_price'] = 1;
    $options['show_rating'] = 1;
    $options['show_deliverytime'] = 1;
}

// Add widget identifier
$options['widget_type'] = 'search-form';

$renderer = new ProductSearchFormRenderer($result, $options);

$categories = getCategories($client);

$renderer->setCategories($categories);
echo $renderer;
