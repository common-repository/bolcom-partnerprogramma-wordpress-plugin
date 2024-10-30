<?php
/**
 * SelectedProducts Widget
 * Handles the widget display and wp shortcodes
 *
 * This file is part of the Bol-Partner-Plugin for Wordpress.
 *
 * (c) Netvlies Internetdiensten
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BolPartnerPlugin\Widgets;

use BolPartnerPlugin\Widgets\Renderer\ProductLinksRenderer;

class SelectedProducts extends Widget
{
    /**
     * @var array
     */
    protected static $defaultAttributes = array(
        'title' => '',
        'block_id' => null,
        'products' => '',
        'name' => '',
        'sub_id' => '',
        'width' => '250',
        'cols' => '1',
        'link_target' => '1',
        'image_size' => '1',
        'custom_css' => false,
        'custom_css_style' => '',
        'text_color' => '', // Kept for backwards compatibility
    );

    /**
     * @var array
     */
    protected $placeHolders = array();

    /**
     * @return array
     */
    public static function getDefaultAttributes()
    {
        $default_settings = get_option('bol_default_settings');

        self::$defaultAttributes['link_color'] = $default_settings['link_color'];
        self::$defaultAttributes['subtitle_color'] = $default_settings['subtitle_color'];
        self::$defaultAttributes['pricetype_color'] = $default_settings['pricetype_color'];
        self::$defaultAttributes['price_color'] = $default_settings['price_color'];
        self::$defaultAttributes['deliverytime_color'] = $default_settings['deliverytime_color'];
        self::$defaultAttributes['background_color'] = $default_settings['background_color'];
        self::$defaultAttributes['border_color'] = $default_settings['border_color'];

        self::$defaultAttributes['show_bol_logo'] = isset($default_settings['show_bol_logo']) ? $default_settings['show_bol_logo'] : 'off';
        self::$defaultAttributes['show_price'] = isset($default_settings['show_price']) ? $default_settings['show_price'] : 'off';
        self::$defaultAttributes['show_rating'] = isset($default_settings['show_rating']) ? $default_settings['show_rating'] : 'off';
        self::$defaultAttributes['show_deliverytime'] = isset($default_settings['show_deliverytime']) ? $default_settings['show_deliverytime'] : 'off';

        return self::$defaultAttributes;
    }

    /**
     * Constructor
     */
    public function __construct() {

        parent::__construct('bol_partner_selected_products', __('Bol.com Products Widget', 'bolcom-partnerprogramma-wordpress-plugin'));
        add_shortcode('bol_product_links', array($this, 'handleContentCodes'));
        add_action('admin_head', array($this, 'getAdminHead'));
        add_action('wp_footer', array($this, 'outputContentCodesJson'));
    }

    /**
     * @see WP_Widget::widget\
     */
    public function widget($args, $instance) {
        echo $this->handleContentCodes($instance);
    }

    /**
     * @see WP_Widget::form
     */
    public function form($instance) {
        parent::form($instance);

        $id = $this->option_name . '-' . $this->number;

        $html = '<a href="#%s" id="%s" onclick="bol_openPopupSelected(); return false;">' . __('Widget settings...', 'bolcom-partnerprogramma-wordpress-plugin') . '</a><br/><br/>
            <div id="bol-selected-products-widget-popup"></div>';

        echo sprintf($html, $id, $id);
    }

    /**
     * Creates the javascript for forming the placeholders to real Bol.com
     * product lists
     *
     * @return string
     */
    public function outputContentCodesJson()
    {
        if (empty($this->placeHolders)) {
            return '';
        }

        $jsonStrings = json_encode($this->placeHolders);

        $html = '<script type="text/javascript">' .
            'BolPartner_SelectedProducts = %s;' .
            'BolPartner.SelectedProducts.fillPlaceHolders(BolPartner_SelectedProducts);' .
            '</script>' . PHP_EOL;
        echo sprintf($html, $jsonStrings);
    }

    /**
     * @param $attributes
     * @param null $content
     * @param string $code
     * @return bool|string
     */
    public function handleContentCodes($attributes, $content=null, $code="")
    {
        $attributes = $this->filterShortCodes($attributes);


        if (! isset($attributes['block_id'])) {
            return false;
        }

        $this->addPlaceHolder($attributes);

        $attributes['widget_type'] = 'product-link';

        $productCount = explode(',', $attributes['products']);

        $productRenderer = new ProductLinksRenderer($this->getEmptyProducts(count($productCount)), $attributes);

        return sprintf(
            '<div class="BolPartner_SelectedProducts_PlaceHolder" id="%s">%s</div>',
            $attributes['block_id'], $productRenderer
        );
    }

    protected function getEmptyProducts($count)
    {
        $products = array();
        for ($i = 0; $i < $count; ++$i) {
            $products[] = new \BolOpenApi\Model\Product();
        }
        return $products;
    }

    /**
     * @param array $attributes
     * @return array
     */
    protected function filterShortCodes(array $attributes)
    {
        return shortcode_atts(self::getDefaultAttributes(), $attributes);
    }

    /**
     * @param array $attributes
     * @return SelectedProducts
     */
    protected function addPlaceHolder(array $attributes)
    {
        $this->placeHolders[$attributes['block_id']] = $attributes;
        return $this;
    }

    /**
     * Creates the javascript for the admin head to display
     * the config dialog on the Widgets page.
     */
    public function getAdminHead()
    {
        $fileUrl = explode('/', $_SERVER["PHP_SELF"]);
        $fileName = end($fileUrl);

        $id = $this->option_name . '-' . $this->number;
        $url = BOL_PARTNER_PLUGIN_PATH . '/src/ajax/popup/product-link.php?widget=' . $id;

        if($fileName == 'widgets.php'){ ?>
        <script type="text/javascript">
            function bol_openPopupSelected() {

                jQuery("#bol-selected-products-widget-popup").html(
                    '<div id="dvPopupDialog" class="bol-popup-dialog">'
                        + '<iframe src="<?php echo $url ?>"></iframe>'
                        + '</div>'
                );
                jQuery("#dvPopupDialog").dialog({
                    title: "<?php _e('Bol.com Products widget settings', 'bolcom-partnerprogramma-wordpress-plugin'); ?>",
                    autoOpen: true,
                    modal: true,
                    resizable: false,
                    width: "auto",
                    close: function() {
                        jQuery("#dvPopupDialog").dialog('destroy').remove();
                    }
                });

            }
        </script>
        <?php }
    }

}
