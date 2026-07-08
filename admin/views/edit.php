<?php
if (!defined('ABSPATH')) exit;
$is_new = empty($snippet);
?>
<div class="wrap dgt-cm-wrap">
    <h1 class="wp-heading-inline"><?php echo $is_new ? 'Add New Snippet' : 'Edit Snippet'; ?></h1>
    <a href="?page=dgt-cm" class="page-title-action">Back to All</a>

    <form method="post" action="">
        <?php wp_nonce_field('dgt_cm_save_snippet'); ?>
        
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
                    <div id="titlediv">
                        <div id="titlewrap">
                            <input type="text" name="snippet_name" size="30" value="<?php echo $is_new ? '' : esc_attr($snippet->name); ?>" id="title" spellcheck="true" autocomplete="off" placeholder="Enter snippet name here" required>
                        </div>
                    </div>

                    <div class="dgt-editor-container">
                        <textarea id="snippet_code" name="snippet_code"><?php echo $is_new ? '' : esc_textarea($snippet->code); ?></textarea>
                    </div>

                    <div class="dgt-description-container">
                        <h3>Description</h3>
                        <textarea name="snippet_description" rows="3" style="width:100%"><?php echo $is_new ? '' : esc_textarea($snippet->description); ?></textarea>
                    </div>
                </div>

                <div id="postbox-container-1" class="postbox-container">
                    <div class="postbox">
                        <h2 class="hndle"><span>Publish</span></h2>
                        <div class="inside">
                            <div class="misc-pub-section">
                                <label for="snippet_active">
                                    <input type="checkbox" name="snippet_active" id="snippet_active" <?php echo $is_new ? 'checked' : checked($snippet->active, 1, false); ?>> Active
                                </label>
                            </div>
                            <div class="misc-pub-section">
                                <label>Type:</label>
                                <select name="snippet_type" id="snippet_type" style="width:100%; margin-top:5px;">
                                    <option value="php" <?php echo !$is_new ? selected($snippet->type, 'php', false) : ''; ?>>PHP</option>
                                    <option value="html" <?php echo !$is_new ? selected($snippet->type, 'html', false) : ''; ?>>HTML</option>
                                    <option value="css" <?php echo !$is_new ? selected($snippet->type, 'css', false) : ''; ?>>CSS</option>
                                    <option value="javascript" <?php echo !$is_new ? selected($snippet->type, 'javascript', false) : ''; ?>>JavaScript</option>
                                </select>
                            </div>
                            <div class="misc-pub-section">
                                <label>Scope:</label>
                                <select name="snippet_scope" style="width:100%; margin-top:5px;">
                                    <option value="global" <?php echo !$is_new ? selected($snippet->scope, 'global', false) : ''; ?>>Run everywhere</option>
                                    <option value="admin" <?php echo !$is_new ? selected($snippet->scope, 'admin', false) : ''; ?>>Only in admin area</option>
                                    <option value="frontend" <?php echo !$is_new ? selected($snippet->scope, 'frontend', false) : ''; ?>>Only on site front-end</option>
                                </select>
                            </div>
                            <div class="misc-pub-section">
                                <label>Priority:</label>
                                <input type="number" name="snippet_priority" value="<?php echo $is_new ? 10 : esc_attr($snippet->priority); ?>" style="width:100%; margin-top:5px;">
                            </div>
                            <div class="misc-pub-section">
                                <label>Tags:</label>
                                <input type="text" name="snippet_tags" value="<?php echo $is_new ? '' : esc_attr($snippet->tags); ?>" style="width:100%; margin-top:5px;">
                            </div>
                            
                            <div id="major-publishing-actions">
                                <div id="publishing-action">
                                    <input type="submit" name="dgt_cm_save" id="publish" class="button button-primary button-large" value="Save Changes">
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
