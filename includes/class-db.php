<?php
if (!defined('ABSPATH')) exit;

class DGT_CM_DB {
    public static function get_table_name() {
        global $wpdb;
        return $wpdb->prefix . DGT_CM_TABLE;
    }

    public static function create_table() {
        global $wpdb;
        $table_name = self::get_table_name();
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            description text,
            code longtext NOT NULL,
            type varchar(50) NOT NULL DEFAULT 'php',
            scope varchar(50) NOT NULL DEFAULT 'global',
            priority int(11) NOT NULL DEFAULT 10,
            active tinyint(1) NOT NULL DEFAULT 0,
            tags varchar(255),
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function get_snippets($args = []) {
        global $wpdb;
        $table = self::get_table_name();
        $where = "1=1";

        if (isset($args['active'])) {
            $where .= $wpdb->prepare(" AND active = %d", $args['active']);
        }
        if (isset($args['type'])) {
            $where .= $wpdb->prepare(" AND type = %s", $args['type']);
        }

        $order_by = isset($args['orderby']) ? esc_sql($args['orderby']) : 'priority';
        $order = isset($args['order']) ? esc_sql($args['order']) : 'ASC';

        return $wpdb->get_results("SELECT * FROM $table WHERE $where ORDER BY $order_by $order");
    }

    public static function get_snippet($id) {
        global $wpdb;
        $table = self::get_table_name();
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));
    }

    public static function clear_cache() {
        delete_transient('dgt_cm_active_snippets');
    }

    public static function get_active_snippets() {
        $snippets = get_transient('dgt_cm_active_snippets');
        
        if (false === $snippets) {
            $raw_snippets = self::get_snippets(['active' => 1]);
            $snippets = [
                'php' => [],
                'css' => [],
                'javascript' => [],
                'html' => []
            ];
            
            foreach ($raw_snippets as $snippet) {
                if (isset($snippets[$snippet->type])) {
                    $snippets[$snippet->type][] = $snippet;
                }
            }
            
            set_transient('dgt_cm_active_snippets', $snippets, 30 * DAY_IN_SECONDS);
        }
        
        return $snippets;
    }

    public static function insert_snippet($data) {
        global $wpdb;
        $table = self::get_table_name();
        $wpdb->insert($table, $data);
        self::clear_cache();
        return $wpdb->insert_id;
    }

    public static function update_snippet($id, $data) {
        global $wpdb;
        $table = self::get_table_name();
        $result = $wpdb->update($table, $data, ['id' => $id]);
        self::clear_cache();
        return $result;
    }

    public static function delete_snippet($id) {
        global $wpdb;
        $table = self::get_table_name();
        $result = $wpdb->delete($table, ['id' => $id]);
        self::clear_cache();
        return $result;
    }

    public static function toggle_snippet($id, $status) {
        global $wpdb;
        $table = self::get_table_name();
        $result = $wpdb->update($table, ['active' => $status], ['id' => $id]);
        self::clear_cache();
        return $result;
    }
}
