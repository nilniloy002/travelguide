=== WP Member Login by SPIRAL ===
Contributors: pipedbits
Donate link: 
Tags: member, login, authentication, security, user
Requires at least: 3.7.1
Tested up to: 3.9.1
Stable tag: 1.0.4
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add membership management and secure authentication by SPIRAL&reg; into your WordPress site.

== Description ==

"WP Member Login by SPIRAL" provieds secure authentication by SPIRAL&reg; with a membership management SPIRAL-Application.

= Features: =
* A login form widget to show in your theme's sidebar
* You can use the following shortcodes
  - `[sml-is-logged-in]contents for members[/sml-is-logged-in]` : checks if the current user is logged in
  - `[sml-user-prop key="*"]` : show the member's information that are stored in the SPIRAL-DB
* You can create members-only pages by using the custom field called "sml-member-page"
* SPIRAL-API client implementation included in this plugin

== Installation ==

Install "WP Member Login by SPIRAL" automatically from your WordPress Dashboard by selecting "Plugins" and then "Add New" from the sidebar menu. Search for "WP Member Login by SPIRAL", and then choose "Install Now".

Or:

1. Upload `member-login-by-spiral.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the "Plugins" menu in WordPress

= Getting Start =

SPIRAL&reg; account is required to use this plugin. If you have not already registered for an account, [register a trial account on our site](https://www.pi-pe.co.jp/regist/is?SMPFORM=man-nasao-b6f7c53cb1ae2b6cf70b778b1ed3fbcb).

To enable plugin and see "WP Member Login by SPIRAL" menu added to the "Settings" menu below.  And then open this settings page, enter your SPIRAL-API token, authentication-form url and link settings.

For more information see the [Plugin Manual for Japanese](http://developer.pi-pe.co.jp/trial-navi/wpmls-manual.html) (or [English version](http://www.microsofttranslator.com/bv.aspx?from=&to=en&a=http://developer.pi-pe.co.jp/trial-navi/wpmls-manual.html) by Microsoft Translator).

== Frequently Asked Questions ==

= What is SPIRAL&reg;? =

SPIRAL&reg; is a cloud platform as a service (PaaS) from PIPED BITS Co.,Ltd. that developers use to build web applications using a combination of web components based on database.

== Screenshots ==

1. plugin settings
2. widget settings
3. login widget

== Changelog ==

= 1.0.4 =
* Remove test code

= 1.0.3 =
* Update SPIRAL's area API parameter
* Fix for PHP5.2 compatibility

= 1.0.2 =
* Fixed minor bug

= 1.0.1 =
* Fixed behavior of shortcode

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
* Initial release

