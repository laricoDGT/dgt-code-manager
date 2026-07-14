<?php
if (!defined('ABSPATH')) exit;

$delete_on_uninstall = (bool) get_option('cm_delete_on_uninstall', 0);
?>
<div class="wrap cm-cm-wrap">
    <form method="post" action="" class="cm-settings-form">
        <?php wp_nonce_field('cm_settings_nonce'); ?>

        <div id="poststuff" style="margin-top: 20px;">
            <div id="post-body" class="metabox-holder">
                <div class="postbox cm-postbox" style="max-width: 600px;">
                    <h2 class="hndle"><span>Uninstall Behaviour</span></h2>
                    <div class="inside">
                        <div class="cm-setting-row">
                            <label class="cm-inline-label">
                                <span class="setting-title">Delete all snippets when the plugin is uninstalled</span>
                                <div class="cm-switch-wrapper">
                                    <label class="cm-switch">
                                        <input type="checkbox" name="cm_delete_on_uninstall" id="cm_delete_on_uninstall"
                                            <?php checked($delete_on_uninstall, true); ?>>
                                        <span class="cm-slider round"></span>
                                    </label>
                                    <span class="status-text" id="cm_delete_status_text">
                                        <?php echo $delete_on_uninstall ? 'Yes, delete everything' : 'No, keep my data'; ?>
                                    </span>
                                </div>
                            </label>
                            <p class="description" style="margin-top: 10px; color: #50575e;">
                                When <strong>off</strong> (default): deactivating or deleting the plugin keeps all your snippets in the database. Re-installing and activating the plugin will restore them instantly.<br><br>
                                When <strong>on</strong>: deleting the plugin will permanently remove the snippets table from the database. This cannot be undone.
                            </p>
                        </div>

                        <div class="cm-publish-actions">
                            <button type="submit" name="cm_save_settings" class="button button-primary button-hero cm-save-btn">
                                Save Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('cm_delete_on_uninstall').addEventListener('change', function () {
    document.getElementById('cm_delete_status_text').textContent = this.checked ? 'Yes, delete everything' : 'No, keep my data';
});
</script>
