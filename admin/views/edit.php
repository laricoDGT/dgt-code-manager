<?php
if (!defined('ABSPATH')) exit;
$is_new = empty($snippet);
?>
<div class="wrap cm-wrap">
    <div class="cm-edit-header">
        <h1 class="wp-heading-inline"><?php echo $is_new ? 'Add New Snippet' : 'Edit Snippet'; ?></h1>
        <a href="?page=code-manager" class="page-title-action">Back to All</a>
    </div>

    <form method="post" action="" class="cm-snippet-form">
        <?php wp_nonce_field('cm_save_snippet'); ?>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content" class="cm-main-content">
                    <div class="cm-title-wrapper">
                        <input type="text" name="snippet_name" class="cm-title-input"
                            value="<?php echo $is_new ? '' : esc_attr($snippet->name); ?>" autocomplete="off"
                            placeholder="Enter snippet name here..." required>
                    </div>

                    <div class="cm-editor-wrapper">
                        <div class="cm-editor-header">

                            <span class="editor-title">Code Editor</span>
                        </div>
                        <div class="cm-editor-container">
                            <div id="php_syntax_hint" class="cm-php-hint" style="display: none;">
                                <span class="cm-hint-code">&lt;?php</span>
                            </div>
                            <textarea id="snippet_code"
                                name="snippet_code"><?php echo $is_new ? '' : esc_textarea($snippet->code); ?></textarea>
                        </div>
                    </div>

                    <div class="cm-description-wrapper">
                        <label class="cm-label">Description</label>
                        <textarea name="snippet_description" class="cm-textarea" rows="3"
                            placeholder="What does this snippet do?"><?php echo $is_new ? '' : esc_textarea($snippet->description); ?></textarea>
                    </div>
                </div>

                <div id="postbox-container-1" class="postbox-container cm-sidebar">
                    <div class="postbox cm-postbox">
                        <h2 class="hndle"><span>Snippet Settings</span></h2>
                        <div class="inside">
                            <div class="cm-setting-row">
                                <label for="snippet_active" class="cm-inline-label">
                                    <span class="setting-title">Status</span>
                                    <div class="cm-switch-wrapper">
                                        <label class="cm-switch">
                                            <input type="checkbox" name="snippet_active" id="snippet_active"
                                                <?php echo $is_new ? 'checked' : checked($snippet->active, 1, false); ?>>
                                            <span class="cm-slider round"></span>
                                        </label>
                                        <span
                                            class="status-text"><?php echo ($is_new || $snippet->active) ? 'Active' : 'Inactive'; ?></span>
                                    </div>
                                </label>
                            </div>

                            <div class="cm-setting-row">
                                <label class="setting-title">Type</label>
                                <select name="snippet_type" id="snippet_type" class="cm-select">
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

                            <div class="cm-setting-row">
                                <label class="setting-title">Scope</label>
                                <select name="snippet_scope" class="cm-select">
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

                            <div class="cm-setting-row">
                                <label class="setting-title">Priority</label>
                                <input type="number" name="snippet_priority"
                                    value="<?php echo $is_new ? 10 : esc_attr($snippet->priority); ?>"
                                    class="cm-input">
                            </div>

                            <div class="cm-setting-row">
                                <label class="setting-title">Tags</label>
                                <input type="text" name="snippet_tags"
                                    value="<?php echo $is_new ? '' : esc_attr($snippet->tags); ?>" class="cm-input"
                                    placeholder="e.g. tracking, css, fixes">
                            </div>

                            <div class="cm-publish-actions">
                                <button type="submit" name="cm_save"
                                    class="button button-primary button-hero cm-save-btn">
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