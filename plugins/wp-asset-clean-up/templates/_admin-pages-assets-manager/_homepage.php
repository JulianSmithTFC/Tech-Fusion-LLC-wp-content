<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
    exit;
}
?>
<div style="margin: 30px 0 0;" class="cleafix"></div>

<?php
do_action('wpacu_admin_notices');

if ($data['wpacu_settings']['dashboard_show'] != 1) {
	?>
    <div class="error" style="padding: 10px;"><?php echo sprintf(__('As "Manage in the Dashboard?" is not enabled in "%sSettings%s", you can not manage the assets from the Dashboard.', 'wp-asset-clean-up'), '<a href="admin.php?page=wpassetcleanup_settings">', '</a>'); ?></div>
	<?php
	return;
}

if ($data['show_on_front'] === 'page') {
    $anyMetaBoxHidden = $data['wpacu_settings']['hide_assets_meta_box'] || $data['wpacu_settings']['hide_options_meta_box'];

    if ($anyMetaBoxHidden) {
        ?>
        <div class="wpacu-warning" style="width: 95%; margin: -10px 0 15px; padding: 10px; font-size: inherit;">
            <strong><span class="dashicons dashicons-warning" style="color: orange;"></span> Important Reminder:</strong> The following meta boxes were marked to be hidden in plugin's "Settings" &#187; "Plugin Usage Preferences":
            <ul style="margin-bottom: 0; list-style: circle; padding-left: 25px;">
                <?php if ($data['wpacu_settings']['hide_assets_meta_box']) { ?>
                    <li><strong><?php echo WPACU_PLUGIN_TITLE; ?>: CSS &amp; JavaScript Manager</strong> * <em>to see the CSS/JS list, you need to make the meta box visible again</em></li>
                <?php } ?>

	            <?php if ($data['wpacu_settings']['hide_options_meta_box']) { ?>
                    <li><strong><?php echo WPACU_PLUGIN_TITLE; ?>: Options</strong> * <em>to prevent minify/combine/unload settings per page, you need to make the meta box visible again</em></li>
	            <?php } ?>
            </ul>
        </div>
        <?php
    }
	?>
    <div class="wpacu-notice-info" style="width: 95%;">
        <p><?php _e('In "Settings" &#187; "Reading" you have selected a static page for "Front page displays" setting.', 'wp-asset-clean-up'); ?></p>
        <p><?php _e('To manage the assets (.CSS &amp; .JS), use the button(s) below:', 'wp-asset-clean-up'); ?></p>

        <table class="wp-list-table widefat fixed striped pages" style="margin-bottom: 4px;">
		    <?php
		    if ($data['page_on_front']) {
            ?>
                <tr>
				    <td style="width: 80px; vertical-align: middle;"><?php _e('Front page:', 'wp-asset-clean-up'); ?></td>
                    <td><a class="button button-secondary button-large" href="<?php echo admin_url('post.php?post='.$data['page_on_front'].'&action=edit#'.WPACU_PLUGIN_ID.'_asset_list'); ?>"><span class="dashicons dashicons-admin-page" style="vertical-align: middle;"></span> <strong><?php echo $data['page_on_front_title']; ?></strong></a></td>
                </tr>
            <?php
		    }
            ?>
            <?php
		    if ($data['page_for_posts']) {
            ?>
                <tr>
                    <td style="width: 80px; vertical-align: middle;"><?php _e('Posts page:', 'wp-asset-clean-up'); ?></td>
                    <td><a class="button button-secondary button-large" href="<?php echo admin_url('post.php?post='.$data['page_for_posts'].'&action=edit#'.WPACU_PLUGIN_ID.'_asset_list'); ?>"><span class="dashicons dashicons-admin-page" style="vertical-align: middle;"></span> <strong><?php echo $data['page_for_posts_title']; ?></strong></a></td>
                </tr>
            <?php
		    }
		    ?>
        </table>
    </div>
    <p><?php echo sprintf(__('To read more about creating a static front page in WordPress, %scheck the Codex%s.', 'wp-asset-clean-up'), '<a target="_blank" href="https://codex.wordpress.org/Creating_a_Static_Front_Page">', '</a>'); ?></p>
	<?php
} else {
	?>
    <form id="wpacu_home_page_form" method="post" action="">
        <input type="hidden" name="wpacu_manage_home_page_assets" value="1" />

        <input type="hidden"
               id="wpacu_ajax_fetch_assets_list_dashboard_view"
               name="wpacu_ajax_fetch_assets_list_dashboard_view"
               value="1" />

        <p><span class="dashicons dashicons-admin-home"></span> <?php _e('Here you can unload files loaded on the home page. "Front page displays" (from "Settings" &#187; "Reading") is set to either "Your latest posts" (in "Settings" &#187; "Reading") OR a special layout (from a theme or plugin) was enabled.', 'wp-asset-clean-up'); ?> <?php echo sprintf(__('Changes will also apply to pages such as %s etc. in case the latest blog posts are paginated.', 'wp-asset-clean-up'), '<code>/page/2</code> <code>page/3</code>'); ?></p>

        <div id="wpacu_meta_box_content">
            <img src="<?php echo admin_url(); ?>/images/spinner.gif" align="top" width="20" height="20" alt="" />&nbsp;

			<?php _e('Retrieving the loaded scripts and styles for the home page. Please wait...', 'wp-asset-clean-up'); ?>

            <p><?php echo sprintf(
					__('If you believe fetching the page takes too long and the assets should have loaded by now, I suggest you go to "Settings", make sure "Manage in front-end" is checked and then %smanage the assets in the front-end%s.', 'wp-asset-clean-up'),
					'<a href="'.$data['site_url'].'#wpacu_wrap_assets">',
					'</a>'
				); ?></p>
        </div>

		<?php
		wp_nonce_field($data['nonce_name']);
		?>
        <div id="wpacu-update-button-area" class="no-left-margin">
            <p class="submit"><input type="submit" name="submit" id="submit" class="hidden button button-primary" value="<?php esc_attr_e('Update', 'wp-asset-clean-up'); ?>"></p>
            <div id="wpacu-updating-settings" style="margin-left: 100px;">
                <img src="<?php echo admin_url(); ?>/images/spinner.gif" align="top" width="20" height="20" alt="" />
            </div>
        </div>
    </form>
	<?php
}