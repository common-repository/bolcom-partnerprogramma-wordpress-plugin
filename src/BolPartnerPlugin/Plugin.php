<?php
/*
 * The backend plugin for configuring the partner program and registering the widgets
 * and the tinyMCE plugin. Also functions as a primitive frontcontroller for the backend
 * page(s).
 *
 * This file is part of the Bol-Partner-Plugin for Wordpress.
 *
 * (c) Netvlies Internetdiensten
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BolPartnerPlugin;

use BolPartnerPlugin\AdminPage\Config;
use BolPartnerPlugin\Widgets\SelectedProducts;

class Plugin
{
   /**
    * The default options for the plugin
    * @var array
    */
    protected $defaultOptions = array(
        'config_id' => '',
        'pageTitle' => '',
    );

    /**
     * @var array
     */
    protected $options = array();

    /**
     * Constructor, expecting the options to be set
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->setOptions($options);
    }

    /**
     * @param array $options
     * @return Bol_Partner_Plugin
     */
    public function setOptions(array $options)
    {
        $this->options = array_intersect_key($options, $this->defaultOptions);
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Initializes the plugin, setting the hooks for actions to define the different
     * functionalities of the plugin
     */
    public function init()
    {
        is_admin() ? $this->initAdmin() : $this->initFrontEnd();
        add_action('wp_enqueue_scripts', array($this, 'initFrontEndScripts'));
        add_action('admin_enqueue_scripts', array($this, 'initAdminScripts'));

        add_action('widgets_init', array($this, 'initWidgets'));
        add_action('wp_head', function() {
            echo '<script type="text/javascript">'
                . '    var bol_partner_plugin_base = "' . BOL_PARTNER_PLUGIN_PATH . '";'
                . '</script>';
        });
    }

    /**
     * Enqueue scripts for the admin (backend)
     */
    public function initAdminScripts()
    {
        //wp_enqueue_script('jquery', BOL_PARTNER_PLUGIN_PATH . '/resources/js/jquery-1.4.2.min.js', '1.4.2' );
        wp_enqueue_style('bol.css', BOL_PARTNER_PLUGIN_PATH . '/resources/css/bol.css', array('dashicons'));

    }


    /**
     * All init settings for the admin (backend)
     */
    public function initAdmin()
    {
        add_action('admin_menu', array($this, 'initAdminMenu'));
        add_action('admin_init', array($this, 'initAdminPlugins'));

        if (!get_option('bol_partner_settings') || ! get_option('bol_openapi_settings')) {
            // attach the config message to the wordpress action
            add_action('admin_notices', array($this, 'displayConfigErrorMessage'));
        }
    }

    /**
     * Enqueue scripts for the frontend
     */
    public function initFrontEndScripts()
    {
        //wp_enqueue_script('jquery', BOL_PARTNER_PLUGIN_PATH . '/resources/js/jquery-1.4.2.min.js', '1.4.2' );
        wp_enqueue_script('bol-frontend-script', BOL_PARTNER_PLUGIN_PATH . '/resources/js/bol-partner-frontend.js', array('jquery'));
        wp_enqueue_style("bol.css", BOL_PARTNER_PLUGIN_PATH . '/resources/css/bol.css');
    }

    /**
     * All init settings for the frontend
     */
    public function initFrontEnd()
    {
        add_action('wp_head', array($this, 'jsAddResourceRoot'));
    }

    /**
     * Creates the entry in the wp-admin menu
     */
    public function initAdminMenu()
    {
        global $menu, $submenu;
        $exists = false;
        foreach ($menu as $item) {
            if ($item[2] == 'bol_dot_com') {
                $exists = true;
            }
        }

        if (! $exists) {
            add_menu_page(
                '',
                'Bol.com',
                'manage_options',
                'bol_dot_com',
                '',
                BOL_PARTNER_PLUGIN_PATH . '/resources/icon-bol-16x16.png'
            );
        }

        add_submenu_page(
            'bol_dot_com',
            'Partner Plugin',
            'Partner Plugin',
            'manage_options',
            'boldotcom_partnerplugin',
            array($this, 'showConfigPage')
        );

        if (! $exists) {
            // remove the automatically added submenu for bol_dot_com
            remove_submenu_page('bol_dot_com', 'bol_dot_com');
        }
    }

    public function jsAddResourceRoot()
    {
        echo '<script type="text/javascript">var bol_partner_plugin_base = "' . BOL_PARTNER_PLUGIN_PATH . '";</script>';
    }


    /**
     * Initializes the (tinymce) backend plugins
     */
    public function initAdminPlugins()
    {
        add_action('admin_head', array($this, 'jsAddResourceRoot'));

        //wp_enqueue_script('jquery', BOL_PARTNER_PLUGIN_PATH . '/resources/js/jquery-1.4.2.min.js', '1.4.2' );
        wp_enqueue_script('jquery-ui', BOL_PARTNER_PLUGIN_PATH . '/resources/js/jquery-ui-1.8.13.custom.min.js', array('jquery'), '1.8.13', true );
        wp_enqueue_script('jscolor', BOL_PARTNER_PLUGIN_PATH . '/resources/js/jscolor.js');
        wp_enqueue_script('jquery-ui-dialog', false, array('jquery'), false, false);
        wp_enqueue_script('jquery-ui-tabs', false, array('jquery'), false, false);
		wp_enqueue_script('jquery-ui-slider', false, array('jquery'), false, false);
        // wp_enqueue_script('colorpicker', false, array('jquery'), false, false);
        wp_enqueue_style("jquery-ui", BOL_PARTNER_PLUGIN_PATH . '/resources/css/jquery-ui-1.8.13.custom.css');

        add_action(
            'wp_print_scripts',
            function()
            {
                if ( !is_page('events') ) {
                    wp_deregister_script( 'jquery-ui' );
                    wp_deregister_script( 'jquery.css' );
                }
            },
            100
        );

        add_filter(
            'mce_external_plugins',
            function($plugin_array)
            {
                $plugin_array['bolpartnerplugin'] = BOL_PARTNER_PLUGIN_PATH . '/resources/js/tinymce/bol-tinymce-button.js';
                // The following fixes a bug in tiny_mce_popup.js (a Wordpress core file) (v1.4.15):
                $plugin_array['tinymce-legacy-fix'] = BOL_PARTNER_PLUGIN_PATH . '/resources/js/tinymce/tinymce-legacy-fix.js';
                return $plugin_array;
            }
        );

        add_filter(
            'mce_buttons',
            function($buttons)
            {
                array_push($buttons, "separator", "bolpartnerplugin");
                return $buttons;
            }
        );

    }

    public function initWidgets()
    {
        register_widget('BolPartnerPlugin\Widgets\SelectedProducts');
        register_widget('BolPartnerPlugin\Widgets\Bestsellers');
        register_widget('BolPartnerPlugin\Widgets\SearchForm');
    }

    public function bolProductHandler($atts, $content=null, $code="" ) {
        $plugin = new SelectedProducts();

        return $plugin->handleContentShortcodes($atts, $content, $code);
    }

    /**
     * Display's a message in wordpress notifying the user that the plugin has not yet
     * been configured.
     */
    public function displayConfigErrorMessage()
    {
        $configUrl = admin_url('admin.php?page=' . $this->options['config_id']);
        $msg = sprintf(__('%s: Plugin is not configured! Please correct in the <a href="%s" target="_self">settings page</a>', 'bolcom-partnerprogramma-wordpress-plugin')
            , $this->options['pageTitle'], $configUrl
        );
        echo '<div class="updated fade"><p>' . $msg . '</p></div>';
    }

    /**
     * Passes everything to the config page and displays the output
     */
    public function showConfigPage()
    {
        $params = array_merge($_GET, $_POST);
        $configPage = new Config();
        $configPage->init();
        $configPage->setParams($params);

        $configPage->process();

        echo $configPage->display();
    }


}
