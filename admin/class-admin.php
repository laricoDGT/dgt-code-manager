<?php
if (!defined('ABSPATH')) exit;

class CODEWEAVE_Admin {
    public static function init() {
        add_action('admin_menu',            [__CLASS__, 'add_menu_pages']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_delete_modal']);
        add_action('admin_init',            [__CLASS__, 'handle_actions']);
        add_filter('plugin_action_links_' . plugin_basename(CODEWEAVE_FILE), [__CLASS__, 'add_action_links']);
    }

    public static function add_action_links($links) {
        $snippets_link = '<a href="' . admin_url('tools.php?page=codeweave') . '">Snippets</a>';
        array_unshift($links, $snippets_link);
        return $links;
    }

    public static function handle_actions() {
        if (!isset($_GET['page']) || $_GET['page'] !== 'codeweave') {
            return;
        }

        // Handle Delete
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            check_admin_referer('codeweave_delete_' . $_GET['id']);
            CODEWEAVE_DB::delete_snippet(intval($_GET['id']));
            wp_safe_redirect(admin_url('tools.php?page=codeweave&message=deleted'));
            exit;
        }

        // Handle Settings Save
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codeweave_save_settings'])) {
            check_admin_referer('codeweave_settings_nonce');
            update_option('codeweave_delete_on_uninstall', isset($_POST['codeweave_delete_on_uninstall']) ? 1 : 0);
            wp_safe_redirect(admin_url('tools.php?page=codeweave&tab=settings&message=settings_saved'));
            exit;
        }

        // Handle Save
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codeweave_save'])) {
            check_admin_referer('codeweave_save_snippet');
            
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
                CODEWEAVE_DB::update_snippet($id, $data);
                $redirect_url = admin_url("tools.php?page=codeweave&action=edit&id=$id&message=updated");
            } else {
                $id = CODEWEAVE_DB::insert_snippet($data);
                $redirect_url = admin_url("tools.php?page=codeweave&action=edit&id=$id&message=created");
            }
            
            wp_safe_redirect($redirect_url);
            exit;
        }
    }

    public static function add_menu_pages() {
        add_management_page(
            'CodeWeave',
            'CodeWeave',
            'manage_options',
            'codeweave',
            [__CLASS__, 'render_list_page']
        );
    }

    public static function enqueue_assets($hook) {
        if (strpos($hook, 'codeweave') === false) return;

        wp_enqueue_style('codeweave-admin-css', CODEWEAVE_URL . 'admin/assets/admin.css', [], CODEWEAVE_VERSION);
        wp_enqueue_script('codeweave-admin-js', CODEWEAVE_URL . 'admin/assets/admin.js', ['jquery'], CODEWEAVE_VERSION, true);
        
        wp_localize_script('codeweave-admin-js', 'codeweave', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('codeweave_nonce')
        ]);

        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
            $settings = wp_enqueue_code_editor(['type' => 'application/x-httpd-php']);
            if ($settings !== false) {
                wp_add_inline_script(
                    'codeweave-admin-js',
                    'var codeweave_editor_settings = ' . wp_json_encode($settings) . ';'
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
        echo '<a href="' . esc_url(admin_url('tools.php?page=codeweave')) . '" class="nav-tab' . ($current_tab === 'snippets' ? ' nav-tab-active' : '') . '">Snippets</a>';
        echo '<a href="' . esc_url(admin_url('tools.php?page=codeweave&tab=settings')) . '" class="nav-tab' . ($current_tab === 'settings' ? ' nav-tab-active' : '') . '">Settings</a>';
        echo '</nav>';
        echo '</div>';

        if ($current_tab === 'settings') {
            self::render_settings_page();
        } else {
            require_once CODEWEAVE_PATH . 'admin/views/list.php';
        }
    }

    public static function render_settings_page() {
        require_once CODEWEAVE_PATH . 'admin/views/settings.php';
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
            $snippet = CODEWEAVE_DB::get_snippet($id);
        }

        require_once CODEWEAVE_PATH . 'admin/views/edit.php';
    }

    public static function enqueue_delete_modal($hook) {
        if ($hook !== 'plugins.php') return;

        wp_enqueue_script(
            'codeweave-delete-modal',
            CODEWEAVE_URL . 'admin/assets/delete-modal.js',
            ['jquery'],
            CODEWEAVE_VERSION,
            true
        );

        wp_localize_script('codeweave-delete-modal', 'codeweaveDeleteModal', [
            'plugin_file' => plugin_basename(CODEWEAVE_FILE),
            'ajax_url'    => admin_url('admin-ajax.php'),
            'nonce'       => wp_create_nonce('codeweave_delete_modal_nonce'),
        ]);
    }
}
