<?php
/**
 * Standard Bol Plugin Widget Class
 *
 * This file is part of the Bol-Partner-Plugin for Wordpress.
 *
 * (c) Netvlies Internetdiensten
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BolPartnerPlugin\Widgets;

use Buzz\Browser as BuzzBrowser;;
use BolPartnerPlugin\ApiClientFactory;
use BolOpenApi\Client;

class Widget extends \WP_Widget {

    /**
     * @var \BolOpenApi\Client
     */
    protected $client;

    protected $output;

    public function __construct($id_base = false, $name = null) {

        $partnerSettings = get_option('bol_partner_settings');
        $openApiSettings = get_option('bol_openapi_settings');
        $accessKey = $partnerSettings['access_key'];
        $secretKey = $openApiSettings['access_key'];
        $this->client = ApiClientFactory::getCreateClient($accessKey, $secretKey);
        $this->output = '';

        parent::__construct($id_base, $name ? $name : 'bol.com Plugin Widget');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $id = $this->get_field_id('title');
        $label = __('Widget title', 'bolcom-partnerprogramma-wordpress-plugin') . ':';
        $inputName = $this->get_field_name('title');
        $inputValue = $instance['title'];

        $html = '<p><label for="%s">%s</label><br/><input type="text" id="%s" name="%s" value="%s"></p>';
        echo sprintf($html, $id, $label, $id, $inputName, $inputValue);
    }

    protected function get_function_id($name) {
        $tmp = $this->get_field_id($name);
        return str_replace("-", "_", $tmp);
    }

    /**
     * Creates an array with empty products for the placeholders to use
     * in rendering a temporary layout for the widget
     *
     * @param $count
     * @return array
     */
    protected function getEmptyProducts($count)
    {
        if ($count < 1) {
            throw new \InvalidArgumentException('$count needs to be >= 1');
        }

        $products = array();
        for ($i = 0; $i < $count; ++$i) {
            $products[] = new \BolOpenApi\Model\Product();
        }
        return $products;
    }
}
