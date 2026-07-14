<?php
if (!defined('ABSPATH')) exit;
$is_new = empty($snippet);
?>
<div class="wrap codeweave-wrap">
    <div class="codeweave-edit-header">
        <h1 class="wp-heading-inline"><?php echo $is_new ? 'Add New Snippet' : 'Edit Snippet'; ?></h1>
        <a href="?page=codeweave" class="page-title-action">Back to All</a>
    </div>

    <form method="post" action="" class="codeweave-snippet-form">
        <?php wp_nonce_field('codeweave_save_snippet'); ?>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content" class="codeweave-main-content">
                    <div class="codeweave-title-wrapper">
                        <input type="text" name="snippet_name" class="codeweave-title-input"
                            value="<?php echo $is_new ? '' : esc_attr($snippet->name); ?>" autocomplete="off"
                            placeholder="Enter snippet name here..." required>
                    </div>

                    <div class="codeweave-editor-wrapper">
                        <div class="codeweave-editor-header">

                            <span class="editor-title">Code Editor</span>
                        </div>
                        <div class="codeweave-editor-container">
                            <div id="php_syntax_hint" class="codeweave-php-hint" style="display: none;">
                                <span class="codeweave-hint-code">&lt;?php</span>
                            </div>
                            <textarea id="snippet_code"
                                name="snippet_code"><?php echo $is_new ? '' : esc_textarea($snippet->code); ?></textarea>
                        </div>
                    </div>

                    <div class="codeweave-description-wrapper">
                        <label class="codeweave-label">Description</label>
                        <textarea name="snippet_description" class="codeweave-textarea" rows="3"
                            placeholder="What does this snippet do?"><?php echo $is_new ? '' : esc_textarea($snippet->description); ?></textarea>
                    </div>
                </div>

                <div id="postbox-container-1" class="postbox-container codeweave-sidebar">
                    <div class="postbox codeweave-postbox">
                        <h2 class="hndle"><span>Snippet Settings</span></h2>
                        <div class="inside">
                            <div class="codeweave-setting-row">
                                <label for="snippet_active" class="codeweave-inline-label">
                                    <span class="setting-title">Status</span>
                                    <div class="codeweave-switch-wrapper">
                                        <label class="codeweave-switch">
                                            <input type="checkbox" name="snippet_active" id="snippet_active"
                                                <?php echo $is_new ? 'checked' : checked($snippet->active, 1, false); ?>>
                                            <span class="codeweave-slider round"></span>
                                        </label>
                                        <span
                                            class="status-text"><?php echo ($is_new || $snippet->active) ? 'Active' : 'Inactive'; ?></span>
                                    </div>
                                </label>
                            </div>

                            <div class="codeweave-setting-row">
                                <label class="setting-title">Type</label>
                                <select name="snippet_type" id="snippet_type" class="codeweave-select">
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

                            <div class="codeweave-setting-row">
                                <label class="setting-title">Scope</label>
                                <select name="snippet_scope" class="codeweave-select">
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

                            <div class="codeweave-setting-row">
                                <label class="setting-title">Priority</label>
                                <input type="number" name="snippet_priority"
                                    value="<?php echo $is_new ? 10 : esc_attr($snippet->priority); ?>"
                                    class="codeweave-input">
                            </div>

                            <div class="codeweave-setting-row">
                                <label class="setting-title">Tags</label>
                                <input type="text" name="snippet_tags"
                                    value="<?php echo $is_new ? '' : esc_attr($snippet->tags); ?>" class="codeweave-input"
                                    placeholder="e.g. tracking, css, fixes">
                            </div>

                            <div class="codeweave-publish-actions">
                                <button type="submit" name="codeweave_save"
                                    class="button button-primary button-hero codeweave-save-btn">
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