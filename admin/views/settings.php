<?php
if (!defined('ABSPATH')) exit;

$delete_on_uninstall = (bool) get_option('codeweave_delete_on_uninstall', 0);
?>
<div class="wrap codeweave-codeweave-wrap">
    <form method="post" action="" class="codeweave-settings-form">
        <?php wp_nonce_field('codeweave_settings_nonce'); ?>

        <div id="poststuff" style="margin-top: 20px;">
            <div id="post-body" class="metabox-holder">
                <div class="postbox codeweave-postbox" style="max-width: 600px;">
                    <h2 class="hndle"><span>Uninstall Behaviour</span></h2>
                    <div class="inside">
                        <div class="codeweave-setting-row">
                            <label class="codeweave-inline-label">
                                <span class="setting-title">Delete all snippets when the plugin is uninstalled</span>
                                <div class="codeweave-switch-wrapper">
                                    <label class="codeweave-switch">
                                        <input type="checkbox" name="codeweave_delete_on_uninstall" id="codeweave_delete_on_uninstall"
                                            <?php checked($delete_on_uninstall, true); ?>>
                                        <span class="codeweave-slider round"></span>
                                    </label>
                                    <span class="status-text" id="codeweave_delete_status_text">
                                        <?php echo $delete_on_uninstall ? 'Yes, delete everything' : 'No, keep my data'; ?>
                                    </span>
                                </div>
                            </label>
                            <p class="description" style="margin-top: 10px; color: #50575e;">
                                When <strong>off</strong> (default): deactivating or deleting the plugin keeps all your snippets in the database. Re-installing and activating the plugin will restore them instantly.<br><br>
                                When <strong>on</strong>: deleting the plugin will permanently remove the snippets table from the database. This cannot be undone.
                            </p>
                        </div>

                        <div class="codeweave-publish-actions">
                            <button type="submit" name="codeweave_save_settings" class="button button-primary button-hero codeweave-save-btn">
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
document.getElementById('codeweave_delete_on_uninstall').addEventListener('change', function () {
    document.getElementById('codeweave_delete_status_text').textContent = this.checked ? 'Yes, delete everything' : 'No, keep my data';
});
</script>
