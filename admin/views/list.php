<?php
if (!defined('ABSPATH')) exit;

$snippets = CM_DB::get_snippets();
?>
<div class="wrap cm-wrap">
    <h1 class="wp-heading-inline">Snippets</h1>
    <a href="?page=code-manager&action=edit" class="page-title-action">Add New</a>
    
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
                            <strong><a class="row-title" href="?page=code-manager&action=edit&id=<?php echo $snippet->id; ?>"><?php echo esc_html($snippet->name); ?></a></strong>
                            <div class="row-actions">
                                <span class="edit"><a href="?page=code-manager&action=edit&id=<?php echo $snippet->id; ?>">Edit</a> | </span>
                                <span class="delete"><a class="submitdelete" href="<?php echo wp_nonce_url("?page=code-manager&action=delete&id={$snippet->id}", 'cm_delete_' . $snippet->id); ?>">Delete</a></span>
                            </div>
                        </td>
                        <td class="type column-type"><span class="cm-badge badge-<?php echo esc_attr($snippet->type); ?>"><?php echo esc_html(strtoupper($snippet->type)); ?></span></td>
                        <td class="scope column-scope"><?php echo esc_html(ucfirst($snippet->scope)); ?></td>
                        <td class="shortcode column-shortcode">
                            <?php if ($snippet->scope === 'shortcode'): ?>
                                <code>[cm_snippet id="<?php echo $snippet->id; ?>"]</code>
                            <?php else: ?>
                                <span style="color: #999;">&mdash;</span>
                            <?php endif; ?>
                        </td>
                        <td class="status column-status">
                            <label class="cm-switch">
                                <input type="checkbox" class="cm-toggle" data-id="<?php echo $snippet->id; ?>" <?php checked($snippet->active, 1); ?>>
                                <span class="cm-slider round"></span>
                            </label>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
