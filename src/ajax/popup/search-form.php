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

$subTitle = __('Insert search functionallity', 'bolcom-partnerprogramma-wordpress-plugin');
$popupPage = 'search';
include_once 'includes/header.inc.php';
$bolSearch = BOL_PARTNER_PLUGIN_PATH . '/src/ajax/bol-search.php';
?>

<div class="wrap">
<h2><?php _e('Insert search functionallity', 'bolcom-partnerprogramma-wordpress-plugin'); ?></h2>
<br />
<?php include 'includes/add.inc.php' ?>

<table width="100%" border="0" cellpadding="3">
    <tr>
        <td width="50%" style="vertical-align: top">

            <table class="form-table">
                <tr>
                    <th>
                        <label for="ddlBolCategory"><span class="label"><?php _e('Select group', 'bolcom-partnerprogramma-wordpress-plugin'); ?>:</span></label>
                    </th>
                    <td>
                        <div>
                        <input type="radio" name="rbShowCat" id="rbShowCat1" value="0" checked="checked"/>&nbsp;<select style="width: 165px;" class="triggerPreview" name="ddlBolCategory" id="ddlBolCategory">
                            <option value="0">- <?php _e('Select category', 'bolcom-partnerprogramma-wordpress-plugin'); ?> -</option>
                        </select>
                        </div>
                        <div>
                            <input type="radio" name="rbShowCat" id="rbShowCat2" value="1"/>&nbsp;<label for="rbShowCat2"><?php _e('Show select field on website', 'bolcom-partnerprogramma-wordpress-plugin'); ?></label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="txtSearch"><span class="label"><?php _e('Default search value', 'bolcom-partnerprogramma-wordpress-plugin'); ?>:</span></label>
                    </th>
                    <td>
                        <input class="triggerPreview" type="text" name="txtSearch" id="txtSearch" class="property">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="txtLimit"><span class="label"><?php _e('Number of products shown', 'bolcom-partnerprogramma-wordpress-plugin'); ?>:</span></label>
                    </th>
                    <td>
                        <input class="triggerPreview" type="text" name="txtLimit" id="txtLimit" value="4" style="width: 50px">
                    </td>
                </tr>
            </table>


            <table class="form-table">
            <?php
                $properties = array('header', 'width', 'cols');
                $defaults = \BolPartnerPlugin\Widgets\SearchForm::getDefaultAttributes();

                include 'includes/properties.inc.php'
            ?>
            </table>

            <div class="actionPanel">
                
                <input type="hidden" name="blockId" id="blockId" value="bol_<?php echo uniqid(); ?>_search-form" />

                <?php if (isset($_REQUEST['widget'])):?>
                    <input type="hidden" name="widget" id="widget" value="<?php echo strip_tags($_REQUEST['widget'])?>" />
                    <input type="button" id="save-button" name="save" class="updateButton button-primary" value="<?php  _e('Save', 'bolcom-partnerprogramma-wordpress-plugin'); ?>" onclick="BolSearchDialog.insert(<?php echo !empty($_REQUEST['widget'])?>)" />
                    <span id="save-result"></span>
                <?php else: ?>
                <input type="button" class="button button-primary" name="insert" value="<?php _e('Insert', 'bolcom-partnerprogramma-wordpress-plugin'); ?>" onclick="BolSearchDialog.insert();" />
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
