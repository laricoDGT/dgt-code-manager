<?php
/**
 * Plugin Name: CodeWeave 
 * Description: An easy, clean and simple way to enhance your site with code snippets.
 * Version: 1.0.1
 * Author: Larico
 * Text Domain: codeweave
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CODEWEAVE_VERSION', '1.0.1');
define('CODEWEAVE_FILE', __FILE__);
define('CODEWEAVE_PATH', plugin_dir_path(__FILE__));
define('CODEWEAVE_URL', plugin_dir_url(__FILE__));
define('CODEWEAVE_TABLE', 'codeweave_snippets');

function codeweave_init() {
    require_once CODEWEAVE_PATH . 'includes/class-db.php';
    require_once CODEWEAVE_PATH . 'includes/class-executor.php';
    require_once CODEWEAVE_PATH . 'includes/class-shortcodes.php';
    require_once CODEWEAVE_PATH . 'includes/class-ajax.php';

    if (is_admin()) {
        require_once CODEWEAVE_PATH . 'admin/class-admin.php';
        CODEWEAVE_Admin::init();
    }

    CODEWEAVE_Executor::init();
    CODEWEAVE_Shortcodes::init();
    CODEWEAVE_Ajax::init();
}
add_action('plugins_loaded', 'codeweave_init');

function codeweave_activate() {
    require_once CODEWEAVE_PATH . 'includes/class-db.php';
    CODEWEAVE_DB::create_table();
}
register_activation_hook(__FILE__, 'codeweave_activate');