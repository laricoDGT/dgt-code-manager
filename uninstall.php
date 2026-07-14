<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Only delete data if the user explicitly opted in via the plugin settings.
// Default behaviour is to keep all snippets safe.
if (get_option('codeweave_delete_on_uninstall', 0)) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'codeweave_snippets';
    $wpdb->query("DROP TABLE IF EXISTS $table_name"); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
    delete_option('codeweave_delete_on_uninstall');
}
