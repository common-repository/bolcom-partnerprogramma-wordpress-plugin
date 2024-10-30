/*
 * The Wordpress tinymce include (in wp-includes) has a file named 
 * tiny_mce_popup.js, which has the following code:
 *   self.dom = self.editor.windowManager.createInstance('tinymce.plugins.dom.DOMUtils', document, {
 * The object tinymce however does not have a "plugins" key anymore. The
 * "dom" functionality has moved to a direct child of the tinymce object.
 * Therefore, this script detects if tinymce.plugins exists, and if not,
 * it creates it and copies the entire tinymce.dom to tinymce.plugins.dom.
 * After this, the tiny_mce_popup.js script runs successfully again.
 */

(function() {
    if (!tinymce.plugins && tinymce.dom) {
        tinymce.plugins = {};
        tinymce.plugins.dom = tinymce.dom;
    }
})();
