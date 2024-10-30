<?php
namespace BolPartnerPlugin\AdminPage;

use BolPartnerPlugin\AdminPage\AdminPage;
use BolPartnerPlugin\ApiClientFactory;

/**
 * Display, validate and store the plugin configuration
 *
 * This file is part of the Bol-Partner-Plugin for Wordpress.
 *
 * (c) Netvlies Internetdiensten
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Config implements AdminPage
{
    protected $params = array();

    protected $message = '';

    protected static $configFields = array(
        'link_color'            => '003399',
        'subtitle_color'        => '000000',
        'pricetype_color'       => '000000',
        'price_color'           => 'CC3300',
        'deliverytime_color'    => '009900',
        'background_color'      => 'FFFFFF',
        'border_color'          => 'D2D2D2',
        'show_bol_logo'         => 'on',
        'show_rating'           => 'on',
        'show_price'            => 'on',
        'show_deliverytime'     => 'on'
    );

    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    public function init()
    {
        // Callback will be used as soon as we call the update_option() function
        register_setting( 'bol_partner_settings', 'bol_partner_settings', array($this, 'validatePartnerSettings'));
        register_setting( 'bol_partner_settings', 'bol_openapi_settings', array($this, 'validateOpenApiSettings'));
        register_setting( 'bol_partner_settings', 'bol_default_settings', array($this, 'validateDefaultSettings'));

        // Define the Partner programm settings section
        add_settings_section(
        // id                   title                        display callback              page
            'bol_partner_settings', __('Partner program settings', 'bolcom-partnerprogramma-wordpress-plugin'), array($this, 'displayTitle'), 'bol_partner_settings'
        );
        // Define the OpenApi settings section
        add_settings_section(
            'bol_openapi_settings', __('Open API settings', 'bolcom-partnerprogramma-wordpress-plugin'), array($this, 'displayTitle'), 'bol_partner_settings'
        );

        // Define the standard settings section
        add_settings_section(
            'bol_default_settings', __('Standard widget settings', 'bolcom-partnerprogramma-wordpress-plugin'), array($this, 'displayHelpTextDefaultSettings'), 'bol_partner_settings'
        );

        // Partner programm settings fields (bol_partner_plugin_page::bol_partner_settings)
        add_settings_field(
        // id                  title
            'bol_partner_site_id', __('Partner SiteId', 'bolcom-partnerprogramma-wordpress-plugin'),
            // display callback
            array($this, 'inputText'),
            // page                 section
            'bol_partner_settings', 'bol_partner_settings',
            // arguments
            array(
                'name' => 'bol_partner_settings',
                'attrib' => 'site_id',
                'description' => __('Your partner site id. You can find this by logging in on <a href="http://partnerprogramma.bol.com" target="_blank">http://partnerprogramma.bol.com</a>', 'bolcom-partnerprogramma-wordpress-plugin')
            )
        );

        add_settings_field(
        // id                  title
            'bol_partner_access_key', __('API Access Key', 'bolcom-partnerprogramma-wordpress-plugin'),
            array($this, 'inputText'),
            'bol_partner_settings', 'bol_partner_settings',
            array(
                'name' => 'bol_partner_settings',
                'attrib' => 'access_key',
                'description' => __('Your API access key. You can find this by logging in on <a href="http://developers.bol.com" target="_blank">http://developers.bol.com</a>', 'bolcom-partnerprogramma-wordpress-plugin')
    )
        );

        // OpenApi settings fields (bol_partner_plugin_page::bol_openapi_settings)
        add_settings_field(
            'bol_partner_openapi_key', __('API Secret Key', 'bolcom-partnerprogramma-wordpress-plugin'),
            array($this, 'inputText'),
            'bol_partner_settings', 'bol_openapi_settings',
            array(
                'name' => 'bol_openapi_settings',
                'attrib' => 'access_key',
                'id' => 'bol_partner_openapi_key',
                'description' => __('Your API secret key. You can find this by logging in on <a href="http://developers.bol.com" target="_blank">http://developers.bol.com</a>', 'bolcom-partnerprogramma-wordpress-plugin')
            )
        );

        // Default settings fields (bol_partner_plugin_page::bol_default_settings)
        add_settings_field(
            'bol_partner_default_titlecolor', __('Title', 'bolcom-partnerprogramma-wordpress-plugin'),
            array($this, 'inputColor'),
            'bol_partner_settings', 'bol_default_settings',
            array(
                'name'      => 'bol_default_settings',
                'attrib'    => 'link_color',
                'id'        => 'bol_partner_default_titlecolor',
            )
        );

        add_settings_field(
            'bol_partner_default_subtitlecolor', __('Subtitle', 'bolcom-partnerprogramma-wordpress-plugin'),
            array($this, 'inputColor'),
            'bol_partner_settings', 'bol_default_settings',
            array(
                'name'      => 'bol_default_settings',
                'attrib'    => 'subtitle_color',
                'id'        => 'bol_partner_default_subtitlecolor',
            )
        );

        add_settings_field(
            'bol_partner_default_pricetypecolor', __('Price type', 'bolcom-partnerprogramma-wordpress-plugin'),
            array($this, 'inputColor'),
            'bol_partner_settings', 'bol_default_settings',
            array(
                'name'      => 'bol_default_settings',
                'attrib'    => 'pricetype_color',
                'id'        => 'bol_partner_default_pricetypecolor',
            )
        );

        add_settings_field(
            'bol_partner_default_pricecolor', __('Price', 'bolcom-partnerprogramma-wordpress-plugin'),
            array($this, 'inputColor'),
            'bol_partner_settings', 'bol_default_settings',
            array(
                'name'      => 'bol_default_settings',
                'attrib'    => 'price_color',
                'id'        => 'bol_partner_default_pricecolor',
            )
        );

        add_settings_field(
            'bol_partner_default_deliverytimecolor', __('Delivery time', 'bolcom-partnerprogramma-wordpress-plugin'),
            array($this, 'inputColor'),
            'bol_partner_settings', 'bol_default_settings',
            array(
                'name'      => 'bol_default_settings',
                'attrib'    => 'deliverytime_color',
                'id'        => 'bol_partner_default_deliverytimecolor',
            )
        );

        add_settings_field(
            'bol_partner_default_backgroundcolor', __('Background', 'bolcom-partnerprogramma-wordpress-plugin'),
            array($this, 'inputColor'),
            'bol_partner_settings', 'bol_default_settings',
            array(
                'name'      => 'bol_default_settings',
                'attrib'    => 'background_color',
                'id'        => 'bol_partner_default_backgroundcolor',
            )
        );

        add_settings_field(
            'bol_partner_default_bordercolor', __('Border', 'bolcom-partnerprogramma-wordpress-plugin'),
            array($this, 'inputColor'),
            'bol_partner_settings', 'bol_default_settings',
            array(
                'name'      => 'bol_default_settings',
                'attrib'    => 'border_color',
                'id'        => 'bol_partner_default_bordercolor',
            )
        );

        add_settings_field(
            'bol_partner_default_show_bol_logo', __('Show bol.com logo', 'bolcom-partnerprogramma-wordpress-plugin'),
            array($this, 'inputCheckBox'),
            'bol_partner_settings', 'bol_default_settings',
            array(
                'name'      => 'bol_default_settings',
                'attrib'    => 'show_bol_logo',
                'id'        => 'bol_partner_default_show_bol_logo',
            )
        );

        add_settings_field(
            'bol_partner_default_show_rating', __('Show rating', 'bolcom-partnerprogramma-wordpress-plugin'),
            array($this, 'inputCheckBox'),
            'bol_partner_settings', 'bol_default_settings',
            array(
                'name'      => 'bol_default_settings',
                'attrib'    => 'show_rating',
                'id'        => 'bol_partner_default_show_rating',
            )
        );

        add_settings_field(
            'bol_partner_default_show_price', __('Show price', 'bolcom-partnerprogramma-wordpress-plugin'),
            array($this, 'inputCheckBox'),
            'bol_partner_settings', 'bol_default_settings',
            array(
                'name'      => 'bol_default_settings',
                'attrib'    => 'show_price',
                'id'        => 'bol_partner_default_show_price',
            )
        );

        add_settings_field(
            'bol_partner_default_show_deliverytime', __('Show delivery time', 'bolcom-partnerprogramma-wordpress-plugin'),
            array($this, 'inputCheckBox'),
            'bol_partner_settings', 'bol_default_settings',
            array(
                'name'      => 'bol_default_settings',
                'attrib'    => 'show_deliverytime',
                'id'        => 'bol_partner_default_show_deliverytime',
            )
        );
    }

    public static function activate() {
        update_option('bol_default_settings', self::$configFields);
    }

    public function process()
    {
        if (isset($_POST['option_page']) && ($_POST['option_page'] == 'bol_partner_settings')) {
            $partnerSettings = isset($_POST['bol_partner_settings']) ? $_POST['bol_partner_settings'] : array();
            $apiSettings = isset($_POST['bol_openapi_settings']) ? $_POST['bol_openapi_settings'] : array();
            $defaultSettings = isset($_POST['bol_default_settings']) ? $_POST['bol_default_settings'] : array();

            foreach ($partnerSettings as $key => $partnerSetting) {
                $partnerSettings[$key] = preg_replace('/\s+/', '', $partnerSetting);
            }

            foreach ($apiSettings as $key => $apiSetting) {
                $apiSettings[$key] = preg_replace('/\s+/', '', $apiSetting);
            }

            $this->storeSettings('bol_partner_settings', $partnerSettings);
            $this->storeSettings('bol_openapi_settings', $apiSettings);
            $this->storeSettings('bol_default_settings', $defaultSettings);
        }
    }

    public function validatePartnerSettings(array $input)
    {
        // @TODO: implement validator / filter
        return $input;
    }

    public function validateOpenApiSettings(array $input)
    {
        // @TODO: implement validator / filter
        return $input;
    }

    public function validateDefaultSettings(array $input)
    {
        // @TODO: implement validator / filter
        return $input;
    }

    public function storeSettings($name, $value)
    {
        update_option($name, $value);
    }

    public function checkIfCurlIsEnabled(){

        if  (in_array  ('curl', get_loaded_extensions())) {
            return true;
        }
        else {
            return false;
        }
    }

    public function displayMessageIfCurlNotEnabled(){
        if ( !$this->checkIfCurlIsEnabled()) {
            echo "<p style=\"color:red\">cURL is NOT installed on this server, enable this service or ask your hosting provider.</p>";
        }
    }

    public function display()
    {
        ?>
        <div class="wrap">
            <h2><img src="<?php echo BOL_PARTNER_PLUGIN_PATH ?>/resources/icon-bol-50x50.png" style="margin-right: 10px; position: relative; top: 7px;" width="32" height="32" alt="bol.com"/><?php _e('Bol.com partner plugin settings', 'bolcom-partnerprogramma-wordpress-plugin') ?>:</h2>
            <p><?php _e('You can get API keys by registering on <a href="http://developers.bol.com" target="_blank">http://developers.bol.com</a> and then filling in the subscription form for the keys on your profile page. We ask you to use the same email address and website to register, that you have used inside the Partner Pogram. It is your own responsibility for what is performed through your identity key. Be careful it stays yours and is not used in any other way.', 'bolcom-partnerprogramma-wordpress-plugin') ?></p>

            <?php $this->displayMessageIfCurlNotEnabled();

            // Check or the API settings are correctly and a connection can be made
            // Do a test call if settings are set
            if (get_option('bol_partner_settings') && get_option('bol_openapi_settings')) {
                echo '<h3>' . __('Bol.com Open API connection test', 'bolcom-partnerprogramma-wordpress-plugin') . '</h3>';

                $openApiSettings = get_option('bol_openapi_settings');
                $partnerSettings = get_option('bol_partner_settings');
                $accessKey = $partnerSettings['access_key'];
                $privateKey = $openApiSettings['access_key'];

                if (!empty($accessKey) && !empty($privateKey)) {
                    $client = ApiClientFactory::getCreateClient($accessKey, $privateKey);

                    try {
                        // Do a test call
                        $response = $client->ping();

                        _e('Status returned by the Bol.com Open API:<br />', 'bolcom-partnerprogramma-wordpress-plugin');

                        if ($response->getStatusCode() == 200) {
                            echo '<strong style="color:#80e631">' .__('Successful', 'bolcom-partnerprogramma-wordpress-plugin') . '</strong><br /><br />';
                            _e('Your Bol.com Open API settings are set correctly', 'bolcom-partnerprogramma-wordpress-plugin');
                        } else {
                            // Unsuccessful call
                            echo '<strong style="color:#d51e2c">' . $response->getReasonPhrase() . '<br /> ' . htmlentities($response->getContent()) . '</strong>';

                            echo '<br /><br />';

                            _e('If you are sure your API keys are correct check the API status on <a href="http://developers.bol.com" target="_blank">http://developers.bol.com</a>', 'bolcom-partnerprogramma-wordpress-plugin');
                        }
                    } catch (\BolOpenApi\Exception $e) {
                        echo __('Error: Connection with Bol.com cannot be established', 'bolcom-partnerprogramma-wordpress-plugin') . '<br />';
                        echo $e->getCode() . ' ' . $e->getMessage() . '<br /><br />';
                        _e('If you are sure your API keys are correct check the API status on <a href="http://developers.bol.com" target="_blank">http://developers.bol.com</a>', 'bolcom-partnerprogramma-wordpress-plugin');

                    }  catch (\RuntimeException $e) {
                        echo __('Error: Connection with Bol.com cannot be established', 'bolcom-partnerprogramma-wordpress-plugin') . '<br /><br />';
                        echo $e->getCode() . ' ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() .'<br /><br />';
                        _e('If you are sure your API keys are correct check the API status on <a href="http://developers.bol.com" target="_blank">http://developers.bol.com</a>', 'bolcom-partnerprogramma-wordpress-plugin');
                    }

                } else {
                    if (empty($accessKey)) {
                        echo '<strong style="color:#d51e2c">' .__('Please set your API access key', 'bolcom-partnerprogramma-wordpress-plugin'). '</strong>';
                        echo '<br />';
                    }
                    if (empty($privateKey)) {
                        echo '<strong style="color:#d51e2c">' .__('Please set your API secret key', 'bolcom-partnerprogramma-wordpress-plugin'). '</strong>';
                        echo '<br />';
                    }
                }
            }

            ?>

            <form action="<?php echo admin_url('admin.php?page=' . BOL_PARTNER_CONFIG_MENU_SLUG) ?>" method="post">
                <input value="bol_config_edit" type="hidden" name="bol_partner_config_edit" />
                <?php settings_fields( 'bol_partner_settings' ) ?>
                <?php do_settings_sections('bol_partner_settings') ?>
                <p><input class="button button-primary" name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" /></p>
            </form>
        </div>
        <?php
    }

    public function displayTitle()
    {
    }

    public function displayHelpTextDefaultSettings()
    {
        echo '<p>' .  __('Change the default settings of your new widgets. Settings of previous inserted widgets won\'t be changed.', 'bolcom-partnerprogramma-wordpress-plugin'). '</p>';
    }

    public function inputText($params)
    {
        $name = sprintf('%s[%s]', $params['name'], $params['attrib']);

        $options = get_option($params['name']);
        $value = $options[$params['attrib']];

        $input = '<input id="%s" name="%s" size="40" type="text" value="%s" />';

        if (!empty($params['description'])) {
            $input = $input . $this->showDescription($params['description']);
        }
		if(!isset($params['id']) || empty($params['id'])) echo sprintf($input, "id_".rand(1000, 9999), $name, $value);
		else echo sprintf($input, $params['id'], $name, $value);
    }

    public function inputColor($params)
    {
        // Retrieve the values from the database
        $options = get_option($params['name']);

        if ($options) {
            $value = $options[$params['attrib']];
        } else {
            // No database value found, load default
            $value = self::$configFields[$params['attrib']];
        }

        $name = sprintf('%s[%s]', $params['name'], $params['attrib']);

        $input = '<input id="%s" name="%s" type="text" class="color property" value="%s"/>';

        if (!empty($params['description'])) {
            $input = $input . $this->showDescription($params['description']);
        }

        echo sprintf($input, $params['id'], $name, $value);
    }

    public function inputCheckBox($params)
    {
        // Retrieve the values from the database
        $options = get_option($params['name']);

        if (isset($options[$params['attrib']])) {
            $value = "checked";
        } else {
            $value = "";
        }

        $name = sprintf('%s[%s]', $params['name'], $params['attrib']);

        $input = '<input type="checkbox" id="%s" name="%s" %s />';

        if (!empty($params['description'])) {
            $input = $input . $this->showDescription($params['description']);
        }

        echo sprintf($input, $params['id'], $name, $value);
    }

    public function showDescription($description)
    {
        $html = '<p class="description">%s</p>';

        return sprintf($html, $description);
    }

    public static function getConfigFieldValue($key)
    {
        if (array_key_exists($key, self::$configFields)) {
            return self::$configFields[$key];
        }

        return '';
    }
}
