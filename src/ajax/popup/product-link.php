<?php
/**
 * This file is part of the Bol-Partner-Plugin for Wordpress.
 *
 * (c) Netvlies Internetdiensten
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
include_once 'includes/config.inc.php';

$subTitle = __('Insert product link', 'bolcom-partnerprogramma-wordpress-plugin');
$popupPage = 'product-link';
include_once 'includes/header.inc.php';
$bolSearch = BOL_PARTNER_PLUGIN_PATH . '/src/ajax/bol-search.php';
?>

<div class="wrap">
<h2><?php _e('Insert product link', 'bolcom-partnerprogramma-wordpress-plugin'); ?></h2>
<div id="tabs-container">

    <ul id="tabs">
        <li><a href="#tab-search">1. <?php _e('Select product', 'bolcom-partnerprogramma-wordpress-plugin'); ?></a></li>
        <li><a href="#tab-widget">2. <?php _e('Configure product link', 'bolcom-partnerprogramma-wordpress-plugin'); ?></a></li>
    </ul>

    <div id="tab-search">
        <table width="100%" border="0" cellpadding="0" style="margin-bottom:15px">
            <tr>
                <td valign="top">
                    <input type="text" id="txtBolSearch" name="txtBolSearch" value=""/><span class="infix"> in </span><select class="triggerPreview" name="ddlBolCategory" id="ddlBolCategory" style="width: 160px;" ><option value="0">- <?php _e('Select category', 'bolcom-partnerprogramma-wordpress-plugin'); ?> - </option></select>
                
                    <input type="button" class="button" value="<?php _e('Search', 'bolcom-partnerprogramma-wordpress-plugin'); ?>" id="apply-search">
                    <input type="button" class="button" value="<?php _e('To step 2', 'bolcom-partnerprogramma-wordpress-plugin'); ?>" id="next-step">
                </td>
            </tr>
        </table>


    <?php include 'includes/add.inc.php' ?>

        <h4><?php _e('Select products to add', 'bolcom-partnerprogramma-wordpress-plugin'); ?></h4>
        <table width="100%" border="0" cellpadding="3">
            <tr>
                <td>
                    <div id="dvResults" class="searchResults"></div>
                </td>
                <td>
                    <div class="selectedProducts" id="dvSelectedProducts">
                        <input type="hidden" name="hdnBolProducts" id="hdnBolProducts" value=""/>
                        <div class="productlist bol_pml_box">
                            <div class="bol_pml_box_inner"></div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div id="tab-widget">
        <table width="100%" border="0" cellpadding="3">
            <tr>
                <td width="45%" valign="top">

                <table class="form-table">
                <?php
                    $properties = array('header', 'width', 'cols');
                    $defaults = \BolPartnerPlugin\Widgets\SelectedProducts::getDefaultAttributes();

                    include 'includes/properties.inc.php'
                ?>
                </table>

                <div class="actionPanel">

                <input type="hidden" name="blockId" id="blockId" value="bol_<?php echo uniqid(); ?>_selected-products" />

                <?php if (isset($_REQUEST['widget'])):?>
                    <input type="hidden" name="widget" id="widget" value="<?php echo strip_tags($_REQUEST['widget'])?>" />
                    <input type="button" name="save" class="button button-primary" value="<?php  _e('Save', 'bolcom-partnerprogramma-wordpress-plugin'); ?>" onclick="BolProductDialog.insert(<?php echo !empty($_REQUEST['widget'])?>)" />
                    <span id="save-result"></span>
                    <?php else: ?>
                    <input type="button" name="insert" class="button button-primary" value="<?php _e('Insert', 'bolcom-partnerprogramma-wordpress-plugin'); ?>" onclick="BolProductDialog.insert(<?php echo !empty($_REQUEST['widget'])?>);" />
                    <input type="button" name="cancel" class="button" value="<?php _e('Cancel', 'bolcom-partnerprogramma-wordpress-plugin'); ?>" onclick="tinyMCEPopup.close();" />
                    <?php endif;?>
                </div>
                </td>
                <td width="5%"></td>
                <td width="50%"><?php include 'includes/preview.inc.php' ?></td>
            </tr>
        </table>
        <p>
        <!--/form-->
        </p>

    </div>

</div>

</div>
    </body>
</html>
