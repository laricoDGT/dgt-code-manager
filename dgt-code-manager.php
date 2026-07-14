<?php
/**
 * Plugin Name: Code Manager 
 * Description: An easy, clean and simple way to enhance your site with code snippets.
 * Version: 1.0.1
 * Author: Larico
 * Text Domain: code-manager
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CM_VERSION', '1.0.1');
define('CM_FILE', __FILE__);
define('CM_PATH', plugin_dir_path(__FILE__));
define('CM_URL', plugin_dir_url(__FILE__));
define('CM_TABLE', 'cm_snippets');

function cm_init() {
    require_once CM_PATH . 'includes/class-db.php';
    require_once CM_PATH . 'includes/class-executor.php';
    require_once CM_PATH . 'includes/class-shortcodes.php';
    require_once CM_PATH . 'includes/class-ajax.php';

    if (is_admin()) {
        require_once CM_PATH . 'admin/class-admin.php';
        CM_Admin::init();
    }

    CM_Executor::init();
    CM_Shortcodes::init();
    CM_Ajax::init();
}
add_action('plugins_loaded', 'cm_init');

function cm_activate() {
    require_once CM_PATH . 'includes/class-db.php';
    CM_DB::create_table();
}
register_activation_hook(__FILE__, 'cm_activate');