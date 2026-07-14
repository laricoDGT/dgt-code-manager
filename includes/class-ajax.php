<?php
if (!defined('ABSPATH')) exit;

class CODEWEAVE_Ajax {
    public static function init() {
        add_action('wp_ajax_codeweave_toggle',                [__CLASS__, 'toggle_snippet']);
        add_action('wp_ajax_codeweave_set_delete_preference', [__CLASS__, 'set_delete_preference']);
    }

    public static function toggle_snippet() {
        check_ajax_referer('codeweave_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $status = isset($_POST['status']) ? intval($_POST['status']) : 0;

        if ($id > 0) {
            $updated = CODEWEAVE_DB::toggle_snippet($id, $status);
            if ($updated !== false) {
                wp_send_json_success();
            }
        }
        
        wp_send_json_error('Failed to update');
    }

    public static function set_delete_preference() {
        check_ajax_referer('codeweave_delete_modal_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $delete_data = isset($_POST['delete_data']) ? intval($_POST['delete_data']) : 0;
        update_option('codeweave_delete_on_uninstall', $delete_data ? 1 : 0);
        wp_send_json_success();
    }
}
