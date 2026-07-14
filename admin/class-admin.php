<?php
if (!defined('ABSPATH')) exit;

class CM_Admin {
    public static function init() {
        add_action('admin_menu',            [__CLASS__, 'add_menu_pages']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_delete_modal']);
        add_action('admin_init',            [__CLASS__, 'handle_actions']);
        add_filter('plugin_action_links_' . plugin_basename(CM_FILE), [__CLASS__, 'add_action_links']);
    }

    public static function add_action_links($links) {
        $snippets_link = '<a href="' . admin_url('tools.php?page=code-manager') . '">Snippets</a>';
        array_unshift($links, $snippets_link);
        return $links;
    }

    public static function handle_actions() {
        if (!isset($_GET['page']) || $_GET['page'] !== 'code-manager') {
            return;
        }

        // Handle Delete
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            check_admin_referer('cm_delete_' . $_GET['id']);
            CM_DB::delete_snippet(intval($_GET['id']));
            wp_safe_redirect(admin_url('tools.php?page=code-manager&message=deleted'));
            exit;
        }

        // Handle Settings Save
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cm_save_settings'])) {
            check_admin_referer('cm_settings_nonce');
            update_option('cm_delete_on_uninstall', isset($_POST['cm_delete_on_uninstall']) ? 1 : 0);
            wp_safe_redirect(admin_url('tools.php?page=code-manager&tab=settings&message=settings_saved'));
            exit;
        }

        // Handle Save
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cm_save'])) {
            check_admin_referer('cm_save_snippet');
            
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
                CM_DB::update_snippet($id, $data);
                $redirect_url = admin_url("tools.php?page=code-manager&action=edit&id=$id&message=updated");
            } else {
                $id = CM_DB::insert_snippet($data);
                $redirect_url = admin_url("tools.php?page=code-manager&action=edit&id=$id&message=created");
            }
            
            wp_safe_redirect($redirect_url);
            exit;
        }
    }

    public static function add_menu_pages() {
        add_management_page(
            'Code Manager',
            'Code Manager',
            'manage_options',
            'code-manager',
            [__CLASS__, 'render_list_page']
        );
    }

    public static function enqueue_assets($hook) {
        if (strpos($hook, 'code-manager') === false) return;

        wp_enqueue_style('cm-admin-css', CM_URL . 'admin/assets/admin.css', [], CM_VERSION);
        wp_enqueue_script('cm-admin-js', CM_URL . 'admin/assets/admin.js', ['jquery'], CM_VERSION, true);
        
        wp_localize_script('cm-admin-js', 'cm', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cm_nonce')
        ]);

        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
            $settings = wp_enqueue_code_editor(['type' => 'application/x-httpd-php']);
            if ($settings !== false) {
                wp_add_inline_script(
                    'cm-admin-js',
                    'var cm_editor_settings = ' . wp_json_encode($settings) . ';'
                );
            }
        }
    }

    public static function render_list_page() {
        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
            self::render_edit_page();
            return;
        }

        $current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'snippets';

        // Notices
        if (isset($_GET['message'])) {
            if ($_GET['message'] === 'deleted') {
                echo '<div class="notice notice-success is-dismissible"><p>Snippet deleted.</p></div>';
            } elseif ($_GET['message'] === 'settings_saved') {
                echo '<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>';
            }
        }

        // Tab navigation
        echo '<div class="wrap">';
        echo '<nav class="nav-tab-wrapper" style="margin-bottom:0">';
        echo '<a href="' . esc_url(admin_url('tools.php?page=code-manager')) . '" class="nav-tab' . ($current_tab === 'snippets' ? ' nav-tab-active' : '') . '">Snippets</a>';
        echo '<a href="' . esc_url(admin_url('tools.php?page=code-manager&tab=settings')) . '" class="nav-tab' . ($current_tab === 'settings' ? ' nav-tab-active' : '') . '">Settings</a>';
        echo '</nav>';
        echo '</div>';

        if ($current_tab === 'settings') {
            self::render_settings_page();
        } else {
            require_once CM_PATH . 'admin/views/list.php';
        }
    }

    public static function render_settings_page() {
        require_once CM_PATH . 'admin/views/settings.php';
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
            $snippet = CM_DB::get_snippet($id);
        }

        require_once CM_PATH . 'admin/views/edit.php';
    }

    public static function enqueue_delete_modal($hook) {
        if ($hook !== 'plugins.php') return;

        wp_enqueue_script(
            'cm-delete-modal',
            CM_URL . 'admin/assets/delete-modal.js',
            ['jquery'],
            CM_VERSION,
            true
        );

        wp_localize_script('cm-delete-modal', 'cmDeleteModal', [
            'plugin_file' => plugin_basename(CM_FILE),
            'ajax_url'    => admin_url('admin-ajax.php'),
            'nonce'       => wp_create_nonce('cm_delete_modal_nonce'),
        ]);
    }
}
