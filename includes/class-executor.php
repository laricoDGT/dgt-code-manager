<?php
if (!defined('ABSPATH')) exit;

class CM_Executor {
    public static function init() {
        self::execute_php_snippets();
        
        // Frontend hooks
        add_action('wp_head', [__CLASS__, 'execute_css_snippets'], 100);
        add_action('wp_head', [__CLASS__, 'execute_js_head_snippets'], 100);
        add_action('wp_footer', [__CLASS__, 'execute_js_footer_snippets'], 100);

        // Admin hooks
        add_action('admin_head', [__CLASS__, 'execute_css_snippets'], 100);
        add_action('admin_head', [__CLASS__, 'execute_js_head_snippets'], 100);
        add_action('admin_print_footer_scripts', [__CLASS__, 'execute_js_footer_snippets'], 100);
    }

    private static function check_scope($scope) {
        if ($scope === 'global') return true;
        if ($scope === 'admin' && is_admin()) return true;
        if ($scope === 'frontend' && !is_admin()) return true;
        return false;
    }

    public static function execute_php_snippets() {
        $all_snippets = CM_DB::get_active_snippets();
        $snippets = isset($all_snippets['php']) ? $all_snippets['php'] : [];
        
        foreach ($snippets as $snippet) {
            if (self::check_scope($snippet->scope)) {
                try {
                    $code = preg_replace('/^\s*<\?php\s*/i', '', $snippet->code);
                    eval($code);
                } catch (Throwable $e) {
                    error_log('Code Manager Error in snippet #' . $snippet->id . ': ' . $e->getMessage());
                }
            }
        }
    }

    public static function execute_css_snippets() {
        $all_snippets = CM_DB::get_active_snippets();
        $snippets = isset($all_snippets['css']) ? $all_snippets['css'] : [];
        if (empty($snippets)) return;
        
        $css = '';
        foreach ($snippets as $snippet) {
            if (self::check_scope($snippet->scope)) {
                $css .= $snippet->code . "\n";
            }
        }
        
        if (!empty($css)) {
            echo "<style id='cm-css'>\n" . $css . "</style>\n";
        }
    }

    public static function execute_js_head_snippets() {
        $all_snippets = CM_DB::get_active_snippets();
        $snippets = isset($all_snippets['javascript']) ? $all_snippets['javascript'] : [];
        if (empty($snippets)) return;
        
        $js = '';
        foreach ($snippets as $snippet) {
            if (self::check_scope($snippet->scope)) {
                $js .= $snippet->code . "\n";
            }
        }
        
        if (!empty($js)) {
            echo "<script id='cm-js-head'>\n" . $js . "</script>\n";
        }
    }

    public static function execute_js_footer_snippets() {
        // Option to add JS to footer could be added later based on scope or type variant
    }
}
