<?php
/**
 * Renders the product search form and the products result
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

class ProductSearchFormRenderer extends ProductLinksRenderer
{
    /**
     * @var array
     */
    protected $categories = array();

    /**
     * @return array
     */
    public function getCategories()
    {
        $options = array();
        foreach ($this->categories as $category) {
            $options[$category->getId()] = $category->getName();
        }
        return $options;
    }

    /**
     * @param $categories
     *
     * @return ProductSearchFormRenderer
     */public function setCategories($categories)
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @param array $result
     * @param array $options
     */
    public function __construct($result, array $options)
    {
        if (! is_null($result)) {
            $products = $result->getProducts();
            $this->categories = $result->getCategories();
        } else {
            $products = array();
            $this->categories = array();
        }
        parent::__construct($products, $options);
    }

    /**
     * @return string
     */
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

        $title = $this->renderTitle($this->options);
        $searchBox = $this->renderSearchBox($this->options);
        $categorySelect = $this->renderCategorySelect($this->options);
        $pager = $this->renderPager($this->options);
        $logo  = $this->renderLogo($this->options);
        $boxCss = $this->getBoxStyle();

        return sprintf(
            $html, $logo, $id, $boxCss, $title, $searchBox, $categorySelect, $productsBody, $pager
        );
    }

    /**
     * @param array $options
     *
     * @return string
     */
    public function renderSearchBox(array $options)
    {
        $id = $this->options['block_id'] . '_button_search';

        $default = $this->options['default_search'];

        $html = '<div class="searchBox">' .
            '    <input type="text" name="search" value="%s">' .
            '    <button class="searchButton" name="widget_search" id="%s">' . __('Search', 'bolcom-partnerprogramma-wordpress-plugin') . '</button>' .
            '</div>';

        return sprintf($html, $default, $id);
    }

    /**
     * @param array $options
     * @return string
     */public function renderPreferences(array $options)
    {
        $id = $this->options['block_id'] . '_preferences';
        $values = str_replace('"', "'", json_encode($options));
        return sprintf('<input type="hidden" name="preferences" id="%s" value="%s"/>', $id, $values);
    }

    /**
     * @param array $options
     * @return string
     */public function renderCategorySelect(array $options)
    {
        if (! isset($options['cat_select']) || ! $options['cat_select']) {
            return '';
        }

        $catSelect = $options['cat_id'];
        $selectHtml = '<select class="catSelect">%s</select>';
        $options = array(sprintf('<option value="%s">%s</option>', 0, '- ' . __('Select category', 'bolcom-partnerprogramma-wordpress-plugin') . ' -'));
        foreach ($this->getCategories() as $id => $category) {
            $selected = $catSelect == $id ? 'selected="selected"' : null;
            $options[] = sprintf('<option value="%s" %s>%s</option>', $id, $selected, $category);
        }
        return sprintf($selectHtml, implode("\n    ", $options));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return string
     */
    protected function getHtmlBody()
    {
        return '%s<div class="bolLinks bol_pml_box" id="S%s" style="%s">' .
            '    %s' . // title
            '    %s' . // searchbox
            '    %s' . // select
            '    <div class="bol_pml_box_inner">' .
            '            %s' .
            '        <div class="clearer"></div>' .
            '       %s' .
            '    </div>' .
            '</div>';
    }

}
