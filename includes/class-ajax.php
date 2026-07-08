<?php
if (!defined('ABSPATH')) exit;

class DGT_CM_Ajax {
    public static function init() {
        add_action('wp_ajax_dgt_cm_toggle', [__CLASS__, 'toggle_snippet']);
    }

    public static function toggle_snippet() {
        check_ajax_referer('dgt_cm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $status = isset($_POST['status']) ? intval($_POST['status']) : 0;

        if ($id > 0) {
            $updated = DGT_CM_DB::toggle_snippet($id, $status);
            if ($updated !== false) {
                wp_send_json_success();
            }
        }
        
        wp_send_json_error('Failed to update');
    }
}
