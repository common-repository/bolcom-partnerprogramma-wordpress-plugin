<?php
/**
 * Handles the rendering of the selected products box
 *
 * This file is part of the Bol-Partner-Plugin for Wordpress.
 *
 * (c) Netvlies Internetdiensten
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BolPartnerPlugin\Widgets\Renderer;

use BolOpenApi\Model\Product;
use BolPartnerPlugin\Widgets\Renderer\PagerRenderer;

class ProductLinksRenderer
{
    /**
     * @var array
     */
    protected $products;
    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $options
     * @return ProductLinksRenderer
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param array $products
     * @return ProductLinksRenderer
     */
    public function setProducts($products)
    {
        $this->products = $products;
        return $this;
    }

    public function __construct(array $products, array $options)
    {
        $this->setProducts($products);
        $this->setOptions($options);
    }

    public function render()
    {
        $html = $this->getHtmlBody();

        $this->options['cols'] = isset($this->options['cols']) ? $this->options['cols'] : 1;
        $this->options['element_width'] = (int) floor($this->options['width'] / $this->options['cols']) - 22;
        $this->options['image_width'] = $this->options['image_size'] ? 65 : 45;
        $renderer = new ProductRenderer();

        // Check or old setting text_color is set
        if (!empty($this->options['text_color'])) {
            // Reset all new option color options to the old color, so the rendering is the same as before
            // this only applies to shortcodes without subtitle_color, pricetype_color, price_color and deliverytime_color undefined
            $this->options['subtitle_color'] = $this->options['text_color'];
            $this->options['pricetype_color'] = $this->options['text_color'];
            $this->options['price_color'] = $this->options['text_color'];
            $this->options['deliverytime_color'] = $this->options['text_color'];
        }

        $renderer->setOptions($this->options);

        $productsBody = '';
        $count = 0;
        foreach ($this->products as $product) {
            $renderer->setProduct($product);
            $productsBody .= $renderer->render();
            $productsBody .= $this->getSpacerHtml(++$count);
        }

        $id = $this->options['block_id'];

        $boxCss = $this->getBoxStyle();
        $title = $this->renderTitle($this->options);
        $pager = $this->renderPager($this->options);
        $logo  = $this->renderLogo($this->options);

        return sprintf($html, $logo, $id, $boxCss, $title, $productsBody, $pager);
    }

    public function renderTitle(array $options)
    {
        if (! isset($options['title']) || empty($options['title'])) {
            return '';
        }
        return sprintf('<h4>%s</h4>', $options['title']);
    }

    public function renderPager(array $options)
    {
        if (! isset($options['totalResults']) || $options['limit'] >= $options['totalResults']) {
            return '';
        }

        $pages = (int) ceil($options['totalResults'] / $options['limit']);
        $offset = (int) isset($options['offset']) ? $options['offset'] : 1;

        $page = (int) floor($offset / $options['limit']) + 1;
        $page = $page < 1 ? 1 : $page;

        $pagerRenderer = new PagerRenderer($pages, $page, $this->options);
        return $pagerRenderer->render();
    }

    public function renderLogo(array $options)
    {


        if ( $options['show_bol_logo'] == 1 ){

            $url = BOL_PARTNER_PLUGIN_PATH .  '/resources/images/logo_black.png';
            return sprintf('<img class="BolWidgetLogo" src="%s" alt="Bol.com"/>', $url);

        } else {
            return '';

        }

    }

    public function __toString()
    {
        return $this->render();
    }

    protected function getCss($cssOptions)
    {
        $css = '';

        foreach ($cssOptions as $id => $cssElem) {
            if (! isset($this->options[$id])) {
                continue;
            }
            $css .= sprintf($cssElem, $this->options[$id]);
        }
        return $css;

    }

    protected function getBoxStyle()
    {
        $cssOptions = array(
            'width'             => 'width: %spx;',
            'background_color'  => 'background-color: #%s;',
            'text_color'        => 'color: #%s;',
            'border_color'      => 'border: 1px solid #%s',
            'box-sizing'        => 'border-box',
        );

        return $this->getCss($cssOptions);
    }

    protected function getSpacerHtml($count)
    {
        $cols = isset($this->options['cols']) ? $this->options['cols'] : 1;
        $spacer = ($cols < 2) || ! ($count % $cols);
        return $spacer ? '<div class="clearer spacer"></div>' : '';
    }

    protected function getHtmlBody()
    {
        return '%s<div class="bolLinks bol_pml_box" id="S%s" style="%s">' .
            '    %s' .
            '    <div class="bol_pml_box_inner">' .
            '            %s' .
            '        <div class="clearer"></div>' .
            '       %s' .
            '    </div>' .
            '</div>';
    }

}
