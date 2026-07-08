<?php
/**
 * Plugin Name: DGT Code Manager 
 * Description: An easy, clean and simple way to enhance your site with code snippets.
 * Version: 1.0.0
 * Author: C.L.
 * Text Domain: dgt-code-manager
 */

if (!defined('ABSPATH')) {
    exit;
}

define('DGT_CM_VERSION', '1.0.0');
define('DGT_CM_FILE', __FILE__);
define('DGT_CM_PATH', plugin_dir_path(__FILE__));
define('DGT_CM_URL', plugin_dir_url(__FILE__));
define('DGT_CM_TABLE', 'dgt_snippets');

function dgt_cm_init() {
    require_once DGT_CM_PATH . 'includes/class-db.php';
    require_once DGT_CM_PATH . 'includes/class-executor.php';
    require_once DGT_CM_PATH . 'includes/class-shortcodes.php';
    require_once DGT_CM_PATH . 'includes/class-ajax.php';

    if (is_admin()) {
        require_once DGT_CM_PATH . 'admin/class-admin.php';
        DGT_CM_Admin::init();
    }

    DGT_CM_Executor::init();
    DGT_CM_Shortcodes::init();
    DGT_CM_Ajax::init();
}
add_action('plugins_loaded', 'dgt_cm_init');

function dgt_cm_activate() {
    require_once DGT_CM_PATH . 'includes/class-db.php';
    DGT_CM_DB::create_table();
}
register_activation_hook(__FILE__, 'dgt_cm_activate');