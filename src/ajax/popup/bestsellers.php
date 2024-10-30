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

$popupPage = 'bestsellers';
$subTitle = __('Insert bestsellers', 'bolcom-partnerprogramma-wordpress-plugin');
include_once 'includes/header.inc.php';

$bolSearch = BOL_PARTNER_PLUGIN_PATH . '/src/ajax/bol-search.php';
?>
<div class="wrap">
<h2><?php _e('Insert bestsellers', 'bolcom-partnerprogramma-wordpress-plugin'); ?></h2><br />
<?php include 'includes/add.inc.php' ?>


<table width="100%" border="0" cellpadding="3">
    <tr>
        <td width="50%" valign=>


            <table class="form-table">
            <tr>
                <th>
                    <label for="ddlBolCategory"><span class="label"><?php _e('Select group', 'bolcom-partnerprogramma-wordpress-plugin'); ?>:</span></label>
                </th>
                <td>
                    <select name="ddlBolCategory" id="ddlBolCategory" style="width: 180px">
                        <option value="0">- <?php _e('Select category', 'bolcom-partnerprogramma-wordpress-plugin'); ?> -</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label id="labelBolSubCategory" for="ddlBolSubCategory"><span class="label"><?php _e('Select category', 'bolcom-partnerprogramma-wordpress-plugin'); ?>:</span></label>
                </th>
                <td>
                    <select class="triggerPreview" name="ddlBolSubCategory" id="ddlBolSubCategory" disabled="disabled" style="width: 180px">
                        <option value="0">- <?php _e('Select subcategory', 'bolcom-partnerprogramma-wordpress-plugin'); ?> -</option>
                    </select><span class="loader hideElement"></span>
                </td>
            </tr>
            <tr>
                <th>
                    <label id="labelBolSub2Category" for="ddlBolSub2Category"><span class="label"><?php _e('Select subcategory', 'bolcom-partnerprogramma-wordpress-plugin'); ?>:</span></label>
                </th>
                <td>
                    <select class="triggerPreview" name="ddlBolSub2Category" id="ddlBolSub2Category" disabled="disabled" style="width: 180px">
                        <option value="0">- <?php _e('Select subcategory', 'bolcom-partnerprogramma-wordpress-plugin'); ?> -</option>
                    </select><span class="loader hideElement"></span>
                </td>
            </tr>
            <tr>
                <th>
                    <label id="labelBolSub3Category" for="ddlBolSub3Category"><span class="label"><?php _e('Select subcategory', 'bolcom-partnerprogramma-wordpress-plugin'); ?>:</span></label>
                </th>
                <td>
                    <select class="triggerPreview" name="ddlBolSub3Category" id="ddlBolSub3Category" disabled="disabled" style="width: 180px">
                        <option value="0">- <?php _e('Select subcategory', 'bolcom-partnerprogramma-wordpress-plugin'); ?> -</option>
                    </select><span class="loader hideElement"></span>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="priceRangeList"><span class="label"><?php _e('Select price', 'bolcom-partnerprogramma-wordpress-plugin'); ?>:</span></label>
                </th>
                <td>
                    <select id="priceRangeList" name="priceRangeList" >
                        <option value="0"><?php _e('Select price', 'bolcom-partnerprogramma-wordpress-plugin'); ?></option>
                        <option value="7143">Tot &euro; 10</option>
                        <option value="4854">Tot &euro; 20</option>
                        <option value="4855">Tot &euro; 30</option>
                        <option value="4856">Tot &euro; 40</option>
                        <option value="4857">Tot &euro; 50</option>
                        <option value="4858">Tot &euro; 100</option>
                        <option value="5014">Tot &euro; 200</option>
                        <option value="4860">Tot &euro; 300</option>
                        <option value="4861">Tot &euro; 400</option>
                        <option value="4862">Tot &euro; 500</option>
                        <option value="4863">Tot &euro; 750</option>
                        <option value="4864">Tot &euro; 1000</option>
                        <option value="4865">Tot &euro; 1500</option>
                        <option value="4866">Tot &euro; 2000</option>
                        <option value="7346">Tot &euro; 2500</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="txtLimit"><span class="label"><?php _e('Limit', 'bolcom-partnerprogramma-wordpress-plugin'); ?>:</span></label>
                </th>
                <td>
                    <input type="text" id="txtLimit" name="txtLimit" value="5" style="width: 50px;"><small>max. 25</small>
                </td>
            </tr>
            <?php
                $properties = array('header', 'title', 'width', 'cols');
                $defaults = \BolPartnerPlugin\Widgets\Bestsellers::getDefaultAttributes();

                include 'includes/properties.inc.php'
            ?>
            </table>
            <div class="actionPanel">
                <input type="hidden" name="blockId" id="blockId" value="bol_<?php echo uniqid(); ?>_bestsellers" />
                <?php if(isset($_REQUEST['widget'])):?>
                    <input type="hidden" name="widget" id="widget" value="<?php echo strip_tags($_REQUEST['widget'])?>" />
                    <input type="button" id="save-button" name="save" class="updateButton button-primary" value="<?php  _e('Save', 'bolcom-partnerprogramma-wordpress-plugin'); ?>" onclick="BolBestsellersDialog.insert(<?php echo !empty($_REQUEST['widget'])?>)" />
                    <span id="save-result"></span>
                <?php else: ?>
                    <input type="button" class="button-primary" name="insert" value="<?php _e('Insert', 'bolcom-partnerprogramma-wordpress-plugin'); ?>" onclick="BolBestsellersDialog.insert(<?php echo !empty($_REQUEST['widget'])?>);" />
                    <input type="button" class="button" name="cancel" value="<?php _e('Cancel', 'bolcom-partnerprogramma-wordpress-plugin'); ?>" onclick="tinyMCEPopup.close();" />
                <?php endif;?>
            </div>
        </td>
        <td style="width: 5%;"></td>
        <td style="width: 45%; vertical-align: top"><?php include 'includes/preview.inc.php' ?></td>
    </tr>
</table>



</div>
    </body>
</html>
