<?php
if (!defined('ABSPATH')) exit;

class DGT_CM_Admin {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_menu_pages']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
    }

    public static function add_menu_pages() {
        add_menu_page(
            'DGT Code Manager',
            'Code Manager',
            'manage_options',
            'dgt-cm',
            [__CLASS__, 'render_list_page'],
            'dashicons-editor-code',
            20
        );

        add_submenu_page(
            'dgt-cm',
            'All Snippets',
            'All Snippets',
            'manage_options',
            'dgt-cm',
            [__CLASS__, 'render_list_page']
        );

        add_submenu_page(
            'dgt-cm',
            'Add New',
            'Add New',
            'manage_options',
            'dgt-cm-add',
            [__CLASS__, 'render_edit_page']
        );
    }

    public static function enqueue_assets($hook) {
        if (strpos($hook, 'dgt-cm') === false) return;

        wp_enqueue_style('dgt-cm-admin-css', DGT_CM_URL . 'admin/assets/admin.css', [], DGT_CM_VERSION);
        wp_enqueue_script('dgt-cm-admin-js', DGT_CM_URL . 'admin/assets/admin.js', ['jquery'], DGT_CM_VERSION, true);
        
        wp_localize_script('dgt-cm-admin-js', 'dgt_cm', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('dgt_cm_nonce')
        ]);

        if ($hook === 'dgt-code-manager_page_dgt-cm-add' || (isset($_GET['action']) && $_GET['action'] === 'edit')) {
            wp_enqueue_script('wp-theme-plugin-editor');
            wp_enqueue_style('wp-codemirror');
        }
    }

    public static function render_list_page() {
        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
            self::render_edit_page();
            return;
        }
        
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            check_admin_referer('dgt_delete_' . $_GET['id']);
            DGT_CM_DB::delete_snippet(intval($_GET['id']));
            echo '<div class="notice notice-success is-dismissible"><p>Snippet deleted.</p></div>';
        }

        require_once DGT_CM_PATH . 'admin/views/list.php';
    }

    public static function render_edit_page() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $snippet = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dgt_cm_save'])) {
            check_admin_referer('dgt_cm_save_snippet');
            
            $data = [
                'name' => sanitize_text_field($_POST['snippet_name']),
                'code' => $_POST['snippet_code'],
                'description' => sanitize_textarea_field($_POST['snippet_description']),
                'type' => sanitize_text_field($_POST['snippet_type']),
                'scope' => sanitize_text_field($_POST['snippet_scope']),
                'priority' => intval($_POST['snippet_priority']),
                'tags' => sanitize_text_field($_POST['snippet_tags']),
                'active' => isset($_POST['snippet_active']) ? 1 : 0
            ];

            if ($id > 0) {
                DGT_CM_DB::update_snippet($id, $data);
                echo '<div class="notice notice-success is-dismissible"><p>Snippet updated.</p></div>';
            } else {
                $id = DGT_CM_DB::insert_snippet($data);
                echo '<div class="notice notice-success is-dismissible"><p>Snippet created.</p></div>';
                // redirect to edit to avoid double post
                echo "<script>window.location.href='admin.php?page=dgt-cm&action=edit&id=$id';</script>";
            }
        }

        if ($id > 0) {
            $snippet = DGT_CM_DB::get_snippet($id);
        }

        require_once DGT_CM_PATH . 'admin/views/edit.php';
    }
}
