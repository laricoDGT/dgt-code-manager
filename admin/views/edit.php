<?php
if (!defined('ABSPATH')) exit;
$is_new = empty($snippet);
?>
<div class="wrap dgt-cm-wrap">
    <div class="dgt-edit-header">
        <h1 class="wp-heading-inline"><?php echo $is_new ? 'Add New Snippet' : 'Edit Snippet'; ?></h1>
        <a href="?page=dgt-cm" class="page-title-action">Back to All</a>
    </div>

    <form method="post" action="" class="dgt-snippet-form">
        <?php wp_nonce_field('dgt_cm_save_snippet'); ?>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content" class="dgt-main-content">
                    <div class="dgt-title-wrapper">
                        <input type="text" name="snippet_name" class="dgt-title-input"
                            value="<?php echo $is_new ? '' : esc_attr($snippet->name); ?>" autocomplete="off"
                            placeholder="Enter snippet name here..." required>
                    </div>

                    <div class="dgt-editor-wrapper">
                        <div class="dgt-editor-header">

                            <span class="editor-title">Code Editor</span>
                        </div>
                        <div class="dgt-editor-container">
                            <div id="php_syntax_hint" class="dgt-php-hint" style="display: none;">
                                <span class="dgt-hint-code">&lt;?php</span>
                            </div>
                            <textarea id="snippet_code"
                                name="snippet_code"><?php echo $is_new ? '' : esc_textarea($snippet->code); ?></textarea>
                        </div>
                    </div>

                    <div class="dgt-description-wrapper">
                        <label class="dgt-label">Description</label>
                        <textarea name="snippet_description" class="dgt-textarea" rows="3"
                            placeholder="What does this snippet do?"><?php echo $is_new ? '' : esc_textarea($snippet->description); ?></textarea>
                    </div>
                </div>

                <div id="postbox-container-1" class="postbox-container dgt-sidebar">
                    <div class="postbox dgt-postbox">
                        <h2 class="hndle"><span>Snippet Settings</span></h2>
                        <div class="inside">
                            <div class="dgt-setting-row">
                                <label for="snippet_active" class="dgt-inline-label">
                                    <span class="setting-title">Status</span>
                                    <div class="dgt-switch-wrapper">
                                        <label class="dgt-switch">
                                            <input type="checkbox" name="snippet_active" id="snippet_active"
                                                <?php echo $is_new ? 'checked' : checked($snippet->active, 1, false); ?>>
                                            <span class="dgt-slider round"></span>
                                        </label>
                                        <span
                                            class="status-text"><?php echo ($is_new || $snippet->active) ? 'Active' : 'Inactive'; ?></span>
                                    </div>
                                </label>
                            </div>

                            <div class="dgt-setting-row">
                                <label class="setting-title">Type</label>
                                <select name="snippet_type" id="snippet_type" class="dgt-select">
                                    <option value="php"
                                        <?php echo !$is_new ? selected($snippet->type, 'php', false) : ''; ?>>PHP
                                        Snippet</option>
                                    <option value="html"
                                        <?php echo !$is_new ? selected($snippet->type, 'html', false) : ''; ?>>HTML
                                        Snippet</option>
                                    <option value="css"
                                        <?php echo !$is_new ? selected($snippet->type, 'css', false) : ''; ?>>CSS
                                        Snippet</option>
                                    <option value="javascript"
                                        <?php echo !$is_new ? selected($snippet->type, 'javascript', false) : ''; ?>>
                                        JavaScript Snippet</option>
                                </select>
                            </div>

                            <div class="dgt-setting-row">
                                <label class="setting-title">Scope</label>
                                <select name="snippet_scope" class="dgt-select">
                                    <option value="global"
                                        <?php echo !$is_new ? selected($snippet->scope, 'global', false) : ''; ?>>Run
                                        everywhere</option>
                                    <option value="admin"
                                        <?php echo !$is_new ? selected($snippet->scope, 'admin', false) : ''; ?>>Only in
                                        admin area</option>
                                    <option value="frontend"
                                        <?php echo !$is_new ? selected($snippet->scope, 'frontend', false) : 'selected="selected"'; ?>>Only
                                        on site front-end</option>
                                    <option value="shortcode"
                                        <?php echo !$is_new ? selected($snippet->scope, 'shortcode', false) : ''; ?>>Only
                                        via shortcode</option>
                                </select>
                            </div>

                            <div class="dgt-setting-row">
                                <label class="setting-title">Priority</label>
                                <input type="number" name="snippet_priority"
                                    value="<?php echo $is_new ? 10 : esc_attr($snippet->priority); ?>"
                                    class="dgt-input">
                            </div>

                            <div class="dgt-setting-row">
                                <label class="setting-title">Tags</label>
                                <input type="text" name="snippet_tags"
                                    value="<?php echo $is_new ? '' : esc_attr($snippet->tags); ?>" class="dgt-input"
                                    placeholder="e.g. tracking, css, fixes">
                            </div>

                            <div class="dgt-publish-actions">
                                <button type="submit" name="dgt_cm_save"
                                    class="button button-primary button-hero dgt-save-btn">
                                    Save Snippet
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>