=== CodeWeave ===
Contributors: laricoDGT
Donate link: https://github.com/laricoDGT/codeweave
Tags: code snippets, custom php, css, javascript, shortcode
Requires at least: 5.5
Tested up to: 6.5
Stable tag: 1.0.1
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

An easy, clean and simple way to enhance your site with custom code snippets. Add PHP, HTML, CSS, and JS code directly from the admin panel.

== Description ==

CodeWeave is a lightweight WordPress plugin that provides a clean and secure way to add custom code snippets to your website. Instead of editing your theme's `functions.php` file, you can manage all your custom code snippets (PHP, HTML, CSS, JavaScript) directly from your WordPress dashboard.

=== Key Features ===
* **Unified Editor:** Add PHP, CSS, HTML, and JavaScript snippets within a syntax-highlighted editor.
* **Granular Scope Selection:** Run code globally, front-end only, admin area only, or execute it dynamically using shortcodes.
* **Easy Activation Toggle:** Turn snippets on or off instantly from the snippet listing screen.
* **Clean Uninstallation:** Option to automatically delete all database tables when the plugin is deleted, or keep them for future reinstalls.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/codeweave` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Access CodeWeave under **Tools** > **CodeWeave** in your WordPress dashboard to start adding snippets.

== Frequently Asked Questions ==

= Can I use this to add Google Analytics tracking codes? =
Yes. You can create a JavaScript or HTML snippet, choose the front-end scope, and place the code.

= Can I run PHP code with this plugin? =
Yes, you can write custom PHP snippets. These snippets run securely when activated.

== Changelog ==

= 1.0.1 =
* Added "Settings" shortcut link on the Plugins page.
* Escaped view variables and added database safety checks.

= 1.0.0 =
* Initial release.
