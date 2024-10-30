=== Bol.com Partner Program Plugin ===
Contributors: Netvlies Internetdiensten
Tags: bol.com, affiliate, partner program, Netherlands stores
Requires at least: 3.9.0
Tested up to: 4.7.2
Stable tag: 1.4.15

Licence: MIT
Bol.com Affiliate Partner program to sell goods on your own website.

== Description ==

Bol.com Affiliate Partner Program Plugin allows you include bol.com products on your own website and earn affiliate
commission from the bol.com Partner Program.

With this plugin you can add specific bol.com products. This includes three types of views: Product links, Search widgets
and Bestseller lists. You can include these in your posts, pages and sidebar.

Bol.com is the number one online retailer in the Netherlands, with more than three million customers and 10% of the online market,
and has a growing presence in Belgium. It offers non-food products in a wide range of categories, delivered direct to people’s homes.

== Minimal requirements ==

* Wordpress 3.9.0 or higher
* PHP version 5.3+ with the cURL extension installed and enabled and the PHP server setting 'short_open_tag' set to value On,
mbstring and parse_ini_file() functions enabled
* No interfering with other plugins (f.e. [Page Builder Plugin](http://siteorigin.com/page-builder))

== Step by step installation guide ==

1. Sign up for a partner program account with your website at [http://partnerprogramma.bol.com](http://partnerprogramma.bol.com) and get a siteid.
2. Sign up for a developer account with your website at [http://developers.bol.com](http://developers.bol.com) and request a API-key.
3. Install the plugin within your Wordpress site. You can upload the plugin to the `/wp-content/plugins/` directory or use the plugin option in the admin.
4. A new entry in the left menu will appear ('Bol.com').
5. Click on the 'Bol.com' menu entry. A settings page will appear. Insert your Partner Program SiteId, API Access Key and API Secret Key.
6. Click on the 'save changes' button and check the 'Bol.com Open API connection test' to see or a connection with the API can be made. If it says status successful
you can start using the widgets. If you get an error check your API Access Key and API Secret Key they may be incorrect. You can also check the FAQ section on this page.
7. Go to the “Widgets” section or add a “Post” or “Page” and select from the toolbar what you want to include.

A more detailed installation guide can be found at: [https://www.bol.com/nl/cms/images/ftp/partnerprogramma/Handleiding%20-%20WordPress-Plugin.pdf](https://www.bol.com/nl/cms/images/ftp/partnerprogramma/Handleiding%20-%20WordPress-Plugin.pdf)

== Frequently Asked Questions ==

No solution to your problem in this section? Go to the [support forum](https://developers.bol.com/forum/partnerprogramma-wordpress-plugin/)

= AdBlock =

Since the Bol Partner Program plugin is considered an advertising plugin, AdBlock is known to block the Bol plugin.
Please turn off AdBlock while using the Bol plugin.


= Fatal error: Call to undefined function BolPartnerPlugin\Widgets\Renderer\mb_strlen() =

There is a problem with your hosting environment. Be sure to run on PHP version 5.3 and check or mb_strlen() is supported.


= When inserting a widget by 'Selecting category' I cannot make a choice =

Check or your page contains javascript errors. This will cause the plugin not to work. If you make sure all the javascript
errors are gone the plugin should work again.


= Only blank images appear of products wit bol name in it =

Everything seems right except blank images appear. With the value Off in the admin a widget could be added successfully
(categories could be loaded, so the API-key was set correctly) but the inserted short code in the WYSIWYG editor was incomplete.

This could be caused by the PHP server setting 'short_open_tag'. If this is set set to value Off (must be set to value On)

Wrong example:
[bol_bestsellers limit="5" block_id="bol__bestsellers" cat_id="8299" name="jkkj" sub_id="" title="" background_color="FFFFFF" text_color="0000FF" link_color="000000" border_color="D2D2D2" width="250" cols="1" show_bol_logo="1" show_price="1" show_rating="1" link_target="1" image_size="1"]

Good example:
[bol_bestsellers limit="5" block_id="bol_521c4fbb57e2b_bestsellers" cat_id="8299" name="jkkj" sub_id="" title="" background_color="FFFFFF" text_color="0000FF" link_color="000000" border_color="D2D2D2" width="250" cols="1" show_bol_logo="1" show_price="1" show_rating="1" link_target="1" image_size="1"]

The block_id missed an unique id. This caused another problem in the frontend of the website. The javascript missed also an unique id:

wrong example:
<script type="text/javascript">BolPartner_SelectedProducts = {"bol_<!--?= uniqid() ?-->_selected-products":

good example:
<script type="text/javascript">BolPartner_SelectedProducts = {"bol_521c4f9e4944f_selected-products":

When you notice you can retrieve categories, but you have one of the following problems please review your PHP server settings.


= Can I add the short code by directly in the content without using the WYSIWYG editor? =

The short code can be inserted via the WYSIWYG editor by clicking on the Bol.com code. This will generate an unique code with it's own number.

You can insert the short codes by yourself by changing the number but using the editor is much easier and provides you with more options.


= Can I install the plugin in Wordpress MU? =

We have not tried the plugin on a MU WordPress installation yet and it is currently not supported. When you install the
plugin on multiple websites you need to have different API-keys and partner id's for the different domains.


= The link of a product redirects to http://www.bol.com/nl/index.html and not to the product =

Check the source code and check the url. There should be a &s=[your partner id number] in the url.

If the s= field is empty you should check your config settings. A partner id should be provided.

http://partnerprogramma.bol.com/click/click?p=1&t=url&s=[your partner id number]&url=http%3A%2F%2Fwww.bol.com%2Fnl%2Fp%2Fhet-voedselzandloper-kookboek%2F9200000016778638%2F&f=WP_BSL&subid=&name=Test

= Plugin can't connect with the Bol.com Open API. Fatal error: 'SSL certificate problem' =

The connection test fails and gives the following error:
Fatal error: Uncaught exception 'RuntimeException' with message 'SSL certificate problem, verify that the CA cert is OK.
Details: error:14090086:SSL routines:SSL3_GET_SERVER_CERTIFICATE:certificate verify failed'

The problem is caused due to your web server settings. To retrieve information from the Bol.com API a secure https connection
is used. Retrieving information via the secured https connection causes a problem on your server. When you correct this
the plugin should work.

Probably the CA certificates are out of date.

A "CA" is shorthand for a "certificate authority," a third-party group responsible for handling secure connections around the web.
They establish digital "certificates," which are a way of ensuring that there are valid connections between two machines
(like your computer and https://openapi.bol.com). Without a certificate, the security risk between two machines is greater.

When you receive this error, it likely means that your CA is out-of-date and needs to be updated. Generally, updating your
OS will also update your CA and solve the problem. (https://help.github.com/articles/error-ssl-certificate-problem-verify-that-the-ca-cert-is-ok)

Your provider should check the settings of the certificate authority. More information can be found here: [http://curl.haxx.se/docs/sslcerts.html](http://curl.haxx.se/docs/sslcerts.html)

== Support ==

Please check the Frequently Asked Questions first. If that doesn't solve your problem of give an answer to your questions.
Go to the support section on this page [http://wordpress.org/support/plugin/bolcom-partnerprogramma-wordpress-plugin](http://wordpress.org/support/plugin/bolcom-partnerprogramma-wordpress-plugin) to ask a question or see what others have asked.

== Development ==

We (Netvlies Internetdiensten and bol.com partner program) are always interested in your opinion on the plugin. Please leave a review on this page.

== Screenshots ==

1. Productlink preview.
2. Bestsellerlist preview.
3. Searchwidget preview.
4. Add productlink settings preview.
5. Widget settings preview.
6. Add to post or page from toolbar preview.
7. General plugin settings preview.

== Changelog ==

= 1.4.15 =

* Fixed the TinyMCE integration inside the editor popups (a version problem)

= 1.4.14 =

* Enabled the ability to turn on and off/delivery times, price and ratings.
* Added an extra check to the plugin configuration page to check if cUrl is enabled on the server.

= 1.4.13 =

* Disabled a deprecated function that caused issues with some clients. Function file has been renamed to add.inc.deprecated.php

= 1.4.12 =

* Fixed a small issue where the logo would still be displayed while being turned off.

= 1.4.11 =

* Fixed a small issue where old logo settings would not be compatible with the last update.

= 1.4.10 =

* Fixed an compatability issue in buzzfeed on clients running php 5.3
* Restored the functionality to close bol.com modal windows at the widgets page. Compability issue was caused by recent Wordpress updates.

= 1.4.9 =

* Fixed an issue where the logo option would not be loaded on older php versions.

= 1.4.8 =

* Fixed an issue where the color of the title / link would not properly change during live preview

= 1.4.7 =

* Added missing </div> closure tag at search-form.php.
* Added the option to show or hide the logo to the product-links widget.
* Fixed an issue where the bol.com logo would not be hidden when the user had chosen to hide the logo.
* Fixed the preview for ratings. You are now able to see a live preview of the rating stars.
* Changed misleading label from Title/Link color to Link. The option only changes the color of the links.

= 1.4.6 =
* Fixed jQuery conflicts with some plugins (backend not showing tabs and categories)

= 1.4.5 =
* Add rel=nofollow to links

= 1.4.4 =
* Fixed jQuery loading problems with multiple versions
* Fixed rating stars
* Fixed problems with checkboxes handling (settings in backend)

= 1.4.3 =
* Fixed compatebility to Wordpress 4.5.2.
* Fixed notices in backend

= 1.4.2 =
* Mixed content warnings were still showing for some users. This should not happen anymore now.

= 1.4.1 =
* Fixed Yoast conflict.

= 1.4.0 =
* Removed h1, h2, h3, h4, h5 styles from css.

= 1.3.9 =
* Added support for SSL.
* Fixed wp_enqueue_scripts error.

= 1.3.8 =
* Fixed a bug where multiple colums didnt work correctly.

= 1.3.7 =
* Fixed a bug where product links didn't show when adding more than one product link on a page.

= 1.3.6 =
* Rollback to prevent the AdBlock warnings.

= 1.3.4 =
* Added a warning for AdBlock users.

= 1.3.3 =
* Removed shortcodes in sourcecode. Shortcodes do not have to be enabled anymore from now on.

= 1.3.2 =
* Fixed 'mixed content warnings' and changed the size of the widget for smaller resolutions.

= 1.3.0 =
* Fixed plugin for tinymce4 in Wordpress 3.9. Warning: < Wordpress 3.9 is no longer supported!

= 1.2.1 =
* Fixed styling issue with Wordpress version 3.8.1
* Improved technical retrieval of promotions (now using cURL)
* Added extra error information on the configuration page
* Set cURL timeout to 10 seconds instead of 5 seconds

= 1.2.0 =
* Added extra admin options to manage default color settings and other shown elements like stars, bol.com logo, etc.
* Added plaza and second hand offers
* Added validation for API-keys and added feedback when key is not valid
* Updated visual style of the plugin
* Added promotion tabs so current Bol.com promotions can be viewed when creating new widgets
* Added Bol.com promotion links
* Added implementation for translation
* Added languages Dutch and English
* Improved error handling

= 1.1.1 =
* Small style fix, setting the titles to fontsize of 100%. It seems some templates use a general .title with large fontsizes.

= 1.1.0 =
* Added filled placeholder rendering. Placeholders now have dummy content
* Small style changes to have default product link dimensions
* Maded column and width input available for manual input
* Added default for widget width and widget columns (for searchForm)
* Added preview triggers on several fields for bestsellers and search-form
* Updated the admin css, better displaying of plugin
* Added auto search on enter keypress in the searchbox for the products widget
* Altered the price collection to bol api v3
* Added auto preview to tab switch in product widget
* Fixed path issue for loading the tinymce popup js. Did not work in a subdir install

= 1.0.5 =
* Removing old code concerning css files broke the build. The block_id could not be generated anymore. Fixed with new block_id generation.

= 1.0.4 =
* Changed wp-load include reference from absolute (from document-root) to relative to support subdir wp-installs

= 1.0.3 =
* Removed short open tags from the code
* Added php version check
* Removed obsolete old code which created css files on disk

= 1.0.2 =
* Textual changes in the plugin
* Improved the installation instructions
* Upgraded the class autoloader which caused error messages
* Fixed the path for the icon for the wordpress menu

= 1.0.1 =
* Fixed jQuery loading issues
* Disabled redirects in the OpenAPI curl client

= 1.0 =
* This is first version
