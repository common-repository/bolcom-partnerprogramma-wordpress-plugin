<?php
/**
 * This file is part of the Bol-Partner-Plugin for Wordpress.
 *
 * (c) Netvlies Internetdiensten
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
?>
<div style="float:left"><?php _e('Preview', 'bolcom-partnerprogramma-wordpress-plugin') ?>:</div>
<div style="float:right"><input type="button" class="button" id="apply-preview" name="preview" value="<?php _e('Refresh', 'bolcom-partnerprogramma-wordpress-plugin') ?>" /></div>
<div id="previewDiv"></div>
<div id="preview-box"></div>
<div id="bol_previewParent"><div id="<?php if(isset($filename)) echo str_replace('.css', '', $filename)?>"></div></div>
<div id="previewCssDiv" class="hideElement"></div>
