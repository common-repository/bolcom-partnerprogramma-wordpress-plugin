<?php
/**
 * Renders the most common properties in the widget configuration
 * Currently the following properties can be optionally selected:
 * 'width'  = The width of the box
 * 'header' = Wether to include the bol.com logo
 * 'cols'   = The number of columns to show the results in
 *
 *
 * This file is part of the Bol-Partner-Plugin for Wordpress.
 *
 * (c) Netvlies Internetdiensten
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
?>
<?php
    $properties = isset($properties) ? $properties : array();
    $defaults = isset($defaults) ? $defaults : array();
?>
    <tr>
        <th>
            <label for="txtName"><span class="label"><?php _e('Name', 'bolcom-partnerprogramma-wordpress-plugin') ?><span class="subLabel"> (<?php _e('Required', 'bolcom-partnerprogramma-wordpress-plugin') ?>)</span>:</span></label>
        </th>
        <td><input class="property" type="text" id="txtName" name="txtName" value=""></td>
    </tr>
    <tr>
        <th>
            <label for="txtSubid"><span class="label"><?php _e('SubId', 'bolcom-partnerprogramma-wordpress-plugin') ?><span class="subLabel"> (<?php _e('Optional', 'bolcom-partnerprogramma-wordpress-plugin') ?>)</span>:</span></label>
        </th>
        <td>
            <input class="property" type="text" id="txtSubid" name="txtSubid" value="">
        </td>
    </tr>
    <?php if (in_array('title', $properties)) : ?>
    <tr>
        <th>
            <label for="txtTitle"><span class="label"><?php _e('Title', 'bolcom-partnerprogramma-wordpress-plugin') ?>:</span></label>
        </th>
        <td><input type="text" class="property" name="txtTitle" id="txtTitle" /></td>
    </tr>
    <?php endif ?>

<?php
    use BolPartnerPlugin\AdminPage\Config;
?>
    
    <tr>
        <th>
            <label for="txtTitleColor"><span class="label"><?php _e('Title', 'bolcom-partnerprogramma-wordpress-plugin') ?> / <?php _e('Link', 'bolcom-partnerprogramma-wordpress-plugin') ?>:</span></label>
        </th>
        <td>    
            <input type="text" class="color property" name="txtTitleColor" id="txtTitleColor" value="<?php echo (isset($defaults['link_color']) ? $defaults['link_color'] : Config::getConfigFieldValue('link_color')) ?>"/>
        </td>
    </tr>
    <tr>
        <th>
            <label for="txtSubtitleColor"><span class="label"><?php _e('Subtitle', 'bolcom-partnerprogramma-wordpress-plugin') ?>:</span></label>
        </th>
        <td>
            <input type="text" class="color property" name="txtSubtitleColor" id="txtSubtitleColor" value="<?php echo (isset($defaults['subtitle_color']) ? $defaults['subtitle_color'] : Config::getConfigFieldValue('subtitle_color')) ?>"/>
        </td>
    </tr>
    <tr>
        <th>
            <label for="txtPriceTypeColor"><span class="label"><?php _e('Price type', 'bolcom-partnerprogramma-wordpress-plugin') ?>:</span></label>
        </th>
        <td>
            <input type="text" class="color property" name="txtPriceTypeColor" id="txtPriceTypeColor" value="<?php echo (isset($defaults['pricetype_color']) ? $defaults['pricetype_color'] : Config::getConfigFieldValue('pricetype_color')) ?>"/>
        </td>
    </tr>
    <tr>
        <th>
            <label for="txtPriceColor"><span class="label"><?php _e('Price', 'bolcom-partnerprogramma-wordpress-plugin') ?>:</span></label>
        </th>
        <td>
            <input type="text" class="color property" name="txtPriceColor" id="txtPriceColor" value="<?php echo (isset($defaults['price_color']) ? $defaults['price_color'] : Config::getConfigFieldValue('price_color')) ?>"/>
        </td>
    </tr>
    <tr>
        <th>
            <label for="txtDeliveryTimeColor"><span class="label"><?php _e('Delivery time', 'bolcom-partnerprogramma-wordpress-plugin') ?>:</span></label>
        </th>
        <td>
            <input type="text" class="color property" name="txtDeliveryTimeColor" id="txtDeliveryTimeColor" value="<?php echo (isset($defaults['deliverytime_color']) ? $defaults['deliverytime_color'] : Config::getConfigFieldValue('deliverytime_color')) ?>"/>
        </td>
    </tr>
    <tr>
        <th>
            <label for="txtBackgroundColor"><span class="label"><?php _e('Background', 'bolcom-partnerprogramma-wordpress-plugin') ?>:</span></label>
        </th>
        <td>
            <input type="text" class="color property" name="txtBackgroundColor" id="txtBackgroundColor" value="<?php echo (isset($defaults['background_color']) ? $defaults['background_color'] : Config::getConfigFieldValue('background_color')) ?>"/>
        </td>
    </tr>
    <tr>
        <th>
            <label for="txtBorderColor"><span class="label"><?php _e('Border', 'bolcom-partnerprogramma-wordpress-plugin') ?>:</span></label>
        </th>
        <td>
            <input type="text" class="color property" name="txtBorderColor" id="txtBorderColor" value="<?php echo (isset($defaults['border_color']) ? $defaults['border_color'] : Config::getConfigFieldValue('border_color')) ?>"/>
        </td>
    </tr>
<?php if (in_array('width', $properties) || in_array('cols', $properties)) : ?>

    <?php if (in_array('width', $properties)) : ?>
        <tr>
            <th>
                <span class="label"><?php _e('Width', 'bolcom-partnerprogramma-wordpress-plugin') ?>:</span>
            </th>
            <td>
                <input class="property" type="text" id="txtWidth" name="txtWidth" value="<?php echo (int) (isset($defaults['width']) ? $defaults['width'] : 370) ?>" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="slider" id="widthSlider"></div>
            </td>
        </tr>
    <?php endif ?>

    <?php if (in_array('cols', $properties)) : ?>
        <tr>
            <th>
                <span class="label"><?php _e('Columns', 'bolcom-partnerprogramma-wordpress-plugin') ?>:</span>
            </th>
            <td>
                <input class="property" type="text" id="txtCols" name="txtCols" value="<?php echo (int) (isset($defaults['cols']) ? $defaults['cols'] : 1) ?>" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="slider" id="colsSlider"></div>
            </td>
        </tr>
    <?php endif ?>
<?php endif ?>
<!--    <label for="txtCols"><span class="label">Kolom:</span></label><input class="property" type="text" id="txtCols" name="txtCols" value="1" style="width: 50px">-->

    <tr>
        <td colspan=2>
            <?php if (in_array('header', $properties)) : ?>
                <input class="property" type="checkbox" name="chkBolheader" id="chkBolheader" <?php echo ((isset($defaults['show_bol_logo']) && $defaults['show_bol_logo'] == 'on') ? 'checked' : '') ?> />&nbsp;<label for="chkBolheader"><span class="label"><?php _e('Show bol.com logo', 'bolcom-partnerprogramma-wordpress-plugin') ?></span></label><br/>
            <?php endif ?>
            <input class="property" type="checkbox" name="chkRating" id="chkRating" <?php echo ((isset($defaults['show_rating']) && $defaults['show_rating'] == 'on') ? 'checked' : '') ?>/>&nbsp;<label for="chkRating"><span class="label"><?php _e('Show rating', 'bolcom-partnerprogramma-wordpress-plugin') ?></span></label><br/>
            <input class="property" type="checkbox" name="chkPrice" id="chkPrice" <?php echo ((isset($defaults['show_price']) && $defaults['show_price'] == 'on') ? 'checked' : '') ?>/>&nbsp;<label for="chkPrice"><span class="label"><?php _e('Show price', 'bolcom-partnerprogramma-wordpress-plugin') ?></span></label><br/>
            <input class="property" type="checkbox" name="chkDeliveryTime" id="chkDeliveryTime" <?php echo ((isset($defaults['show_deliverytime']) && $defaults['show_deliverytime'] == 'on') ? 'checked' : '') ?>/>&nbsp;<label for="chkPrice"><span class="label"><?php _e('Show delivery time', 'bolcom-partnerprogramma-wordpress-plugin') ?></span></label><br/>
        </td>
    </tr>
    <tr>
        <th>
            <span class="label labelOption"><?php _e('Link opens in', 'bolcom-partnerprogramma-wordpress-plugin') ?>:</span>
        </th>
        <td>
            <input class="property" type="radio" name="rbLinkTarget" id="rbLinkTarget1" value="1" checked/><label for="rbLinkTarget1" style="display: inline-block; width:100px;"><?php _e('New window', 'bolcom-partnerprogramma-wordpress-plugin') ?></label><br/>
            <input class="property" type="radio" name="rbLinkTarget" id="rbLinkTarget2" value="0"/><label for="rbLinkTarget2"><?php _e('Same window', 'bolcom-partnerprogramma-wordpress-plugin') ?></label>
        </td>
    </tr>
    <tr>
        <th>
            <span class="label labelOption"><?php _e('Image size', 'bolcom-partnerprogramma-wordpress-plugin') ?></span>
        </th>
        <td>
            <input class="property" type="radio" name="rbImageSize" id="rbImageSize1" value="1" checked/><label for="rbImageSize1" style="display: inline-block; width:100px;"><?php _e('Big', 'bolcom-partnerprogramma-wordpress-plugin') ?></label><br />
            <input class="property" type="radio" name="rbImageSize" id="rbImageSize2" value="0"/><label for="rbImageSize2"><?php _e('Small', 'bolcom-partnerprogramma-wordpress-plugin') ?></label>
        </td>
    </tr>

<!--    <input class="property" type="checkbox" name="chkCustomCss" id="chkCustomCss"/><label for="chkCustomCss" ><span class="label" for="chkCustomCss">Toevoegen css</span></label><br />-->
<!--    <textarea name="txtCustomCss" id="txtCustomCss" cols="60" rows="5" class="hideElement property"></textarea>-->
</td></tr>
