<?php
/**
 * Handles the rendering of a single product
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

class ProductRenderer
{
    /**
     * @var array
     */
    protected $standardOptions = array(
        'show_sub_title' => true,
    );

    /**
     * @var BolOpenApi\Model\Product
     */
    protected $product;
    /**
     * @var array
     */
    protected $options;
    /**
     * @var string
     */
    protected $standardImage;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @param array $options
     * @return ProductRenderer
     */
    public function setOptions($options)
    {
        $this->options = array_merge($this->standardOptions, $options);
        return $this;
    }

    /**
     * @param \BolOpenApi\Model\Product $product
     * @return ProductRenderer
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @param string $standardImage
     * @return ProductRenderer
     */
    public function setStandardImage($standardImage)
    {
        $this->standardImage = $standardImage;
        return $this;
    }

    public function __construct(Product $product = null, array $options = null)
    {


        if ($product) {
            $this->setProduct($product);
        }

        if ($options) {
            $this->setOptions($options);
        }
        $this->prefix = is_ssl() ? 'https' : 'http';
        $this->standardImage = $this->prefix . '://www.bol.com/nl/static/images/main/noimage_48x48default.gif';
    }

    public function render(){


        $html = $this->getHtmlBody();
        $link = $this->getLink();
        $target = isset($this->options['link_target']) && $this->options['link_target']
            ? '_blank' : '_self';

        $title = $this->getTitle();

        $factor = 3.6;
        $width  = $this->options['element_width'] - $this->options['image_width'] - 20;
        $chars  = floor($width / $factor);

        $abbrTitle = mb_strlen($title) > $chars ? substr($title, 0, $chars - 3) . '...' : $title;

        return sprintf(
            $html,

            $this->getElementStyle(),
            $this->product->getId(),

            $target, $link, $this->getTitle(),
            $this->getImageHtml(),

            $this->getDetailsStyle(),

            $target, $link, $this->getTitle(), $this->getLinkCss(), $abbrTitle,
            $this->getSubtitle(),
            $this->getPrice(),
            $this->getRatingHtml(),
            $this->getImpressionImage(),
            $this->getAvailabilityDescription()
        );
    }

    public function __toString()
    {
        return $this->render();
    }

    protected function getHtmlBody()
    {
        return '<div class="bol_pml_element" style="%s" rel="%s">' .
            '   <div class="imgwrap_mini">' .
            '    <a rel="nofollow" target="%s" href="%s" title="%s" class="imgwrap_mini">%s</a>' .
            '   </div>' .
            '    <div class="product_details_mini" style="%s">' .
            '       <span>' .
            '        <a rel="nofollow" class="title" target="%s" href="%s" title="%s" style="%s">%s</a>' .
            '        %s' .
            '       </span>' .
            '        %s' .
            '        %s' .
            '        %s' .
            '        %s' .
            '    </div>' .
            '</div>';
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

    protected function getElementStyle()
    {
        $cssOptions = array(
            'element_width'             => 'width: %spx;'
        );

        return $this->getCss($cssOptions);
    }

    protected function getDetailsStyle()
    {
        $this->options['details_width'] = $this->options['element_width'] - $this->options['image_width'] - 20;

        $cssOptions = array(
            'details_width' => 'width: %spx',
        );

        return $this->getCss($cssOptions);
    }

    protected function getLinkCss()
    {
        $cssOptions = array(
            'link_color' => 'color: #%s;',
        );

        return $this->getCss($cssOptions);

    }

    protected function getSubTitleCss()
    {
        $cssOptions = array(
            'subtitle_color' => 'color: #%s;',
        );

        return $this->getCss($cssOptions);
    }

    protected function getPriceTypeCss()
    {
        $cssOptions = array(
            'pricetype_color' => 'color: #%s;',
        );

        return $this->getCss($cssOptions);
    }

    protected function getPriceCss()
    {
        $cssOptions = array(
            'price_color' => 'color: #%s;',
        );

        return $this->getCss($cssOptions);
    }

    protected function getDeliveryTimeCss()
    {
        $cssOptions = array(
            'deliverytime_color' => 'color: #%s',
        );

        return $this->getCss($cssOptions);
    }

    protected function getBestPrice()
    {
        if (! isset($this->options['show_price']) || ! $this->options['show_price']) {
            return '';
        }

        $offers = $this->product->getOffers()->getOffers();

        uasort($offers, array($this, 'sortOffers'));

        /* @var $bestOffer \BolOpenApi\Model\Offer */
        $bestOffer  = reset($offers);

        if (! is_object($bestOffer) || ! $bestOffer->getSecondHand()) {
            return '';
        }

        $price = number_format((double) $bestOffer->getPrice(), 2, '.', '');

        $priceText = __('Best price', 'bolcom-partnerprogramma-wordpress-plugin');
        return $price > 0 ? sprintf('<span class="price"> %s: &euro; %s</span>', $priceText, $$price) : '';
    }

    protected function getPrice()
    {
        if ( $this->options['show_price'] == 0 ) {
            return '';
        }

        $offers = $this->product->getOffers();
        $offers = $offers ? $offers->getOffers() : array();

        if (!count($offers)) {
            return '';
        }

        $bolOffers = $this->getBolOffers($offers);
        $resellerOffers = $this->getResellerOffers($offers);
        $secondHand = $this->getSecondHandOffers($offers);

        uasort($offers, array($this, 'sortOffers'));

        $normal = count($bolOffers) ? reset($bolOffers) : null;
        $hasResellerOffers = count($resellerOffers) ? reset($resellerOffers) : false;
        $hasSecondHand = count($secondHand) ? reset($secondHand) : false;

        // Standard show the normal offer
        $offer = $normal;

        // When no normal offers are found then show another offer
        if (is_null($offer) || ($offer->getAvailabilityDescription() == 'Niet leverbaar.')) {
            // When a valid resellerOffer is found show this one
            if ($hasResellerOffers !== false) {

                $offer = $hasResellerOffers;

            } else if ($hasSecondHand !== false) {
                // When no normal offers or resellerOffers are found then show secondHand offer
                $offer = $hasSecondHand;
            }
        }

        if (! is_object($offer) || (! $offer->getPrice() && ! $offer->getListPrice())) {
            return '';
        }

        $price = $offer->getPrice() == '' ? $offer->getListPrice() : $offer->getPrice();

        /* @var $offer \BolOpenApi\Model\Offer */
        $price = number_format((double) $price, 2, '.', '');

        if ($offer == $hasResellerOffers || $offer == $hasSecondHand) {
            $priceTitle = __('From', 'bolcom-partnerprogramma-wordpress-plugin');
        } else {
            $priceTitle = __('Price', 'bolcom-partnerprogramma-wordpress-plugin');
        }

        $priceTitle = sprintf('<span class="bol_pml_price_type" style="%s">%s</span>', $this->getPriceTypeCss() , $priceTitle);

        $html = '<span class="bol_pml_price" style="%s">' . $priceTitle . ' &euro; %s</span>';
        return ($price > 0) ? sprintf($html, $this->getPriceCss(), $price) : '';
    }

    protected function getAvailabilityDescription()
    {
        if ( $this->options['show_deliverytime'] == 0 ) {
            return '';
        }

        $offers = $this->product->getOffers();
        $offers = $offers ? $offers->getOffers() : array();

        if (!count($offers)) {
            return '';
        }

        // Get the different offers
        $bolOffers = $this->getBolOffers($offers);
        $resellerOffers = $this->getResellerOffers($offers);
        $secondHand = $this->getSecondHandOffers($offers);

        // Sort the offers
        uasort($offers, array($this, 'sortOffers'));

        $normal = count($bolOffers) ? reset($bolOffers) : null;
        $hasResellerOffers = count($resellerOffers) ? reset($resellerOffers) : false;
        $hasSecondHand = count($secondHand) ? reset($secondHand) : false;

        // Standard show the normal offer
        $offer = $normal;

        // When no normal offers are found then show another offer
        if (is_null($offer) || ($offer->getAvailabilityDescription() == 'Niet leverbaar.')) {

            // When a valid resellerOffer is found show this one
            if ($hasResellerOffers !== false) {
                $availability = $hasResellerOffers->getAvailabilityDescription();

            } else if ($hasSecondHand !== false) {
                // When no normal offers or resellerOffers are found then show secondHand offer
                $availability = '2<super>e</super> hands beschikbaar';
            }
        } else {
            // Initialize the availability description
            $availability = $offer->getAvailabilityDescription();
        }

        return sprintf('<span class="bol_available" style="%s">%s</span>', $this->getDeliveryTimeCss(), $availability);
    }

    protected function getBolOffers(array $offers)
    {
        $bolOffers = array();
        foreach ($offers as $offer) {
            if ($offer->getSeller()->getId() === '0') {
                $bolOffers[] = $offer;
            }
        }

        if (count($bolOffers) > 1) {
            uasort($bolOffers, array($this, 'sortOffers'));
        }

        return $bolOffers;
    }

    protected function getResellerOffers(array $offers)
    {
        $resellerOffers = array();
        foreach ($offers as $offer) {
            if ($offer->getSeller()->getId() !== '0' && ($offer->getSecondHand() === 'false' || $offer->getSecondHand() == '')) {
                $resellerOffers[] = $offer;
            }
        }

        if (count($resellerOffers) > 1) {
            uasort($resellerOffers, array($this, 'sortOffers'));
        }

        return $resellerOffers;
    }

    protected function getSecondHandOffers(array $offers)
    {
        $secondHandOffers = array();
        foreach ($offers as $offer) {
            if ($offer->getSecondHand() === 'true') {
                $secondHandOffers[] = $offer;
            }
        }

        if (count($secondHandOffers) > 1) {
            uasort($secondHandOffers, array($this, 'sortOffers'));
        }

        return $secondHandOffers;
    }

    protected function sortOffers($a, $b)
    {
        $priceA = $a->getPrice();
        $priceB = $b->getPrice();
        if ($priceA == $priceB) {
            return 0;
        }

        return $priceA < $priceB ? -1 : 1;
    }

    protected function getTitle()
    {
        return $this->product->getTitle();
    }

    protected function getSubTitle()
    {

        if ( $this->options['show_sub_title'] == 0 ) {
            return '';
        }

        $typeArray = explode('\\', get_class($this->product));
        $type = end($typeArray);

        $method = 'get' . ucfirst($type) . 'SubTitle';

        $subTitle = method_exists($this, $method) ? $this->$method() : $this->product->getSubtitle();
        return empty($subTitle) ? '' : sprintf('<span class="subTitle" style="%s">%s</span>', $this->getSubTitleCss(), $subTitle);
    }

    protected function getBookSubTitle()
    {
        $result = $this->product->getAuthors();

        if (empty($result)) {
            return '';
        }

        $authors = array();

        foreach ($result as $author) {
            $authors[] = $author->getName();
        }

        return implode(' / ', $authors);
    }

    protected function getMusicSubTitle()
    {
        $result = $this->product->getArtists();
        if (empty($result)) {
            return '';
        }

        $artists = array();

        foreach ($result as $artist) {
            $artists[] = $artist->getName();
        }

        return implode(' / ', $artists);
    }

    protected function getDvdSubTitle()
    {
        return $this->product->getPublisher();
    }

    protected function getGameSubTitle()
    {
        return $this->product->getPublisher();
    }

    protected function getToySubTitle()
    {
        return $this->product->getPublisher();
    }

    protected function getElectronicDeviceSubTitle()
    {
        return $this->product->getPublisher();
    }

    protected function getComputerSubTitle()
    {
        return $this->product->getPublisher();
    }

    protected function getProductSubTitle()
    {
        return $this->product->getPublisher();
    }

    protected function getLink()
    {
        $link = $this->getUrl();

        return $this->prefix. '://partnerprogramma.bol.com/click/click?' . $link;
    }

    protected function getImageHtml()
    {
        $str = '<img src="%s" alt="%s" style="width: %spx" class="productImage"/>';

        $images = $this->product->getImages();

        $image = $images ? $images->getMedium() : null;
        $image = $image ? $image : $this->standardImage;

        if (is_ssl() && substr($image, 0, 5) !== 'https' && substr($image, 0, 4) === 'http') {
            $image = preg_replace("/^http:/i", "https:", $image);
        }

        return sprintf($str, $image, $this->product->getTitle(), $this->options['image_width']);
    }

    protected function getRatingHtml()
    {
        if (! isset($this->options['show_rating']) || ! $this->options['show_rating']) {
            return '';
        }

        $html = '';
        if ($this->product->getRating()) {
            $nicerating = substr($this->product->getRating(), 0, 1);
            $nicerating .= '_'.substr($this->product->getRating(), -1);
            $altrating = str_replace("_", ".", $nicerating);
			
			$str = '<div class="rating-stars" title="Klantbeoordeling: %d van de 5 sterren">';
			for($i=0; $i<$nicerating; $i++) $str .='<span class="dashicons dashicons-star-filled"></span>';
			for($j=$i; $j<5; $j++) $str .='<span class="dashicons dashicons-star-empty"></span>';
			$str .= '</div>';
				
			$html = sprintf($str, $nicerating, $nicerating ); // (($altrating/5)*100)
        }

        return empty($html) ? '' : $html;
    }

    public function getImpressionImage()
    {
        $link = $this->getUrl();

        return '<img width="1" height="1" src="'.$this->prefix.'://partnerprogramma.bol.com/click/impression?' . $link . '" alt="">';
    }

    private function getUrl()
    {
        // Determine the type of widget
        switch($this->options['widget_type']) {
            case 'search-form':
                $type = 'WP_SBX';

                break;
            case 'product-link':
                $type = 'WP_PDL';

                break;
            case 'bestellers':
                $type = 'WP_BSL';

                break;
            default:
                $type = 'API';
        }

        $str = 'p=1&amp;t=url&amp;s=%s&amp;url=%s&amp;f=' . $type . '&amp;subid=%s&amp;name=%s';

        $urls = $this->product->getUrls();
		
		//prevent unneeded notices
		if(!isset($this->options['partnerId']) || empty($this->options['partnerId'])) $this->options['partnerId'] = "";

        $link = sprintf(
            $str,
            $this->options['partnerId'],
            $urls ? urlencode($this->product->getUrls()->getMain()) : '#',
            $this->options['sub_id'],
            urlencode($this->options['name'])
        );

        return $link;
    }
}
