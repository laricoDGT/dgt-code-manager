<?php
if (!defined('ABSPATH')) exit;

$snippets = DGT_CM_DB::get_snippets();
?>
<div class="wrap dgt-cm-wrap">
    <h1 class="wp-heading-inline">Snippets</h1>
    <a href="?page=dgt-cm&action=edit" class="page-title-action">Add New</a>
    
    <table class="wp-list-table widefat fixed striped table-view-list">
        <thead>
            <tr>
                <td class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" class="manage-column column-name">Name</th>
                <th scope="col" class="manage-column column-type">Type</th>
                <th scope="col" class="manage-column column-scope">Scope</th>
                <th scope="col" class="manage-column column-shortcode">Shortcode</th>
                <th scope="col" class="manage-column column-status">Status</th>
            </tr>
        </thead>
        <tbody id="the-list">
            <?php if (empty($snippets)): ?>
                <tr><td colspan="6">No snippets found.</td></tr>
            <?php else: ?>
                <?php foreach ($snippets as $snippet): ?>
                    <tr id="snippet-<?php echo $snippet->id; ?>">
                        <th scope="row" class="check-column"><input type="checkbox" name="snippet[]" value="<?php echo $snippet->id; ?>"></th>
                        <td class="name column-name has-row-actions column-primary">
                            <strong><a class="row-title" href="?page=dgt-cm&action=edit&id=<?php echo $snippet->id; ?>"><?php echo esc_html($snippet->name); ?></a></strong>
                            <div class="row-actions">
                                <span class="edit"><a href="?page=dgt-cm&action=edit&id=<?php echo $snippet->id; ?>">Edit</a> | </span>
                                <span class="delete"><a class="submitdelete" href="<?php echo wp_nonce_url("?page=dgt-cm&action=delete&id={$snippet->id}", 'dgt_delete_' . $snippet->id); ?>">Delete</a></span>
                            </div>
                        </td>
                        <td class="type column-type"><span class="dgt-badge badge-<?php echo esc_attr($snippet->type); ?>"><?php echo esc_html(strtoupper($snippet->type)); ?></span></td>
                        <td class="scope column-scope"><?php echo esc_html(ucfirst($snippet->scope)); ?></td>
                        <td class="shortcode column-shortcode"><code>[dgt_snippet id="<?php echo $snippet->id; ?>"]</code></td>
                        <td class="status column-status">
                            <label class="dgt-switch">
                                <input type="checkbox" class="dgt-toggle" data-id="<?php echo $snippet->id; ?>" <?php checked($snippet->active, 1); ?>>
                                <span class="dgt-slider round"></span>
                            </label>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
