<?php
if (!defined('ABSPATH')) exit;

class DGT_CM_Shortcodes {
    public static function init() {
        add_shortcode('dgt_snippet', [__CLASS__, 'render_snippet']);
    }

    public static function render_snippet($atts) {
        $atts = shortcode_atts(['id' => 0], $atts, 'dgt_snippet');
        $id = intval($atts['id']);
        
        if (!$id) return '';
        
        $snippet = DGT_CM_DB::get_snippet($id);
        
        if (!$snippet || !$snippet->active) return '';
        
        ob_start();
        if ($snippet->type === 'php') {
            try {
                $code = preg_replace('/^\s*<\?php\s*/i', '', $snippet->code);
                eval($code);
            } catch (Throwable $e) {
                // error handling
            }
        } elseif ($snippet->type === 'html') {
            echo $snippet->code;
        } elseif ($snippet->type === 'css') {
            echo "<style>{$snippet->code}</style>";
        } elseif ($snippet->type === 'javascript') {
            echo "<script>{$snippet->code}</script>";
        }
        return ob_get_clean();
    }
}
