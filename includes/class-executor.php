<?php
if (!defined('ABSPATH')) exit;

class DGT_CM_Executor {
    public static function init() {
        add_action('plugins_loaded', [__CLASS__, 'execute_php_snippets'], 0);
        add_action('wp_head', [__CLASS__, 'execute_css_snippets'], 100);
        add_action('wp_head', [__CLASS__, 'execute_js_head_snippets'], 100);
        add_action('wp_footer', [__CLASS__, 'execute_js_footer_snippets'], 100);
    }

    private static function check_scope($scope) {
        if ($scope === 'global') return true;
        if ($scope === 'admin' && is_admin()) return true;
        if ($scope === 'frontend' && !is_admin()) return true;
        return false;
    }

    public static function execute_php_snippets() {
        $snippets = DGT_CM_DB::get_snippets(['active' => 1, 'type' => 'php']);
        foreach ($snippets as $snippet) {
            if (self::check_scope($snippet->scope)) {
                try {
                    eval('?>' . $snippet->code);
                } catch (Throwable $e) {
                    error_log('DGT Code Manager Error in snippet #' . $snippet->id . ': ' . $e->getMessage());
                }
            }
        }
    }

    public static function execute_css_snippets() {
        $snippets = DGT_CM_DB::get_snippets(['active' => 1, 'type' => 'css']);
        if (empty($snippets)) return;
        
        $css = '';
        foreach ($snippets as $snippet) {
            if (self::check_scope($snippet->scope)) {
                $css .= "/* Snippet: {$snippet->name} */\n" . $snippet->code . "\n";
            }
        }
        
        if (!empty($css)) {
            echo "<style id='dgt-cm-css'>\n" . $css . "</style>\n";
        }
    }

    public static function execute_js_head_snippets() {
        $snippets = DGT_CM_DB::get_snippets(['active' => 1, 'type' => 'javascript']);
        if (empty($snippets)) return;
        
        $js = '';
        foreach ($snippets as $snippet) {
            if (self::check_scope($snippet->scope)) {
                $js .= "/* Snippet: {$snippet->name} */\n" . $snippet->code . "\n";
            }
        }
        
        if (!empty($js)) {
            echo "<script id='dgt-cm-js-head'>\n" . $js . "</script>\n";
        }
    }

    public static function execute_js_footer_snippets() {
        // Option to add JS to footer could be added later based on scope or type variant
    }
}
