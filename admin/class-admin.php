<?php
if (!defined('ABSPATH')) exit;

class DGT_CM_Admin {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_menu_pages']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
        add_action('admin_init', [__CLASS__, 'handle_actions']);
        add_filter('plugin_action_links_' . plugin_basename(DGT_CM_FILE), [__CLASS__, 'add_action_links']);
    }

    public static function add_action_links($links) {
        $snippets_link = '<a href="' . admin_url('tools.php?page=dgt-cm') . '">Snippets</a>';
        array_unshift($links, $snippets_link);
        return $links;
    }

    public static function handle_actions() {
        if (!isset($_GET['page']) || $_GET['page'] !== 'dgt-cm') {
            return;
        }

        // Handle Delete
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            check_admin_referer('dgt_delete_' . $_GET['id']);
            DGT_CM_DB::delete_snippet(intval($_GET['id']));
            wp_safe_redirect(admin_url('tools.php?page=dgt-cm&message=deleted'));
            exit;
        }

        // Handle Save
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dgt_cm_save'])) {
            check_admin_referer('dgt_cm_save_snippet');
            
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $data = [
                'name' => sanitize_text_field(wp_unslash($_POST['snippet_name'])),
                'code' => wp_unslash($_POST['snippet_code']),
                'description' => sanitize_textarea_field(wp_unslash($_POST['snippet_description'])),
                'type' => sanitize_text_field(wp_unslash($_POST['snippet_type'])),
                'scope' => sanitize_text_field(wp_unslash($_POST['snippet_scope'])),
                'priority' => intval($_POST['snippet_priority']),
                'tags' => sanitize_text_field(wp_unslash($_POST['snippet_tags'])),
                'active' => isset($_POST['snippet_active']) ? 1 : 0
            ];

            if ($id > 0) {
                DGT_CM_DB::update_snippet($id, $data);
                $redirect_url = admin_url("tools.php?page=dgt-cm&action=edit&id=$id&message=updated");
            } else {
                $id = DGT_CM_DB::insert_snippet($data);
                $redirect_url = admin_url("tools.php?page=dgt-cm&action=edit&id=$id&message=created");
            }
            
            wp_safe_redirect($redirect_url);
            exit;
        }
    }

    public static function add_menu_pages() {
        add_management_page(
            'DGT Code Manager',
            'Code Manager',
            'manage_options',
            'dgt-cm',
            [__CLASS__, 'render_list_page']
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

        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
            $settings = wp_enqueue_code_editor(['type' => 'application/x-httpd-php']);
            if ($settings !== false) {
                wp_add_inline_script(
                    'dgt-cm-admin-js',
                    'var dgt_cm_editor_settings = ' . wp_json_encode($settings) . ';'
                );
            }
        }
    }

    public static function render_list_page() {
        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
            self::render_edit_page();
            return;
        }
        
        if (isset($_GET['message']) && $_GET['message'] === 'deleted') {
            echo '<div class="notice notice-success is-dismissible"><p>Snippet deleted.</p></div>';
        }

        require_once DGT_CM_PATH . 'admin/views/list.php';
    }

    public static function render_edit_page() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $snippet = null;
        
        if (isset($_GET['message'])) {
            if ($_GET['message'] === 'created') {
                echo '<div class="notice notice-success is-dismissible"><p>Snippet created.</p></div>';
            } elseif ($_GET['message'] === 'updated') {
                echo '<div class="notice notice-success is-dismissible"><p>Snippet updated.</p></div>';
            }
        }

        if ($id > 0) {
            $snippet = DGT_CM_DB::get_snippet($id);
        }

        require_once DGT_CM_PATH . 'admin/views/edit.php';
    }
}
