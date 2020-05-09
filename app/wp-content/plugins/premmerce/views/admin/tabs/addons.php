<?php
if(!defined('WPINC')){
	die;
}

/** @var \Premmerce\Premmerce\Addons\AddonsManager $addons */
?>
<table class="widefat">
    <thead>
    <tr>
        <th>
			<?php _e('Addon', 'premmerce') ?>
        </th>
        <th>
			<?php _e('Version', 'premmerce') ?>
        </th>
        <th>
			<?php _e('Activate', 'premmerce') ?>
        </th>
    </tr>
    </thead>
    <tbody>
	<?php foreach($addons->getAddons() as $class => $file): ?>
		<?php $data = $addons->getPluginData($file); ?>
        <tr>
            <td>
				<?php echo $data['Name'] ?>
            </td>
            <td>
				<?php echo $data['Version'] ?>
            </td>
            <td>
				<?php if($addons->isActive($class)): ?>
                    <form action="<?php echo admin_url('admin-post.php') ?>" method="post">
                        <input type="hidden" name="action" value="premmerce_addon_action">
                        <input type="hidden" name="slug" value="<?=urlencode($class)?>">
                        <input type="hidden" name="addon_action" value="deactivate">
                        <button type="submit" class="button"><?php _e('Deactivate', 'premmerce') ?></button>
                    </form>
				<?php else: ?>
                    <form action="<?php echo admin_url('admin-post.php') ?>" method="post">
                        <input type="hidden" name="action" value="premmerce_addon_action">
                        <input type="hidden" name="slug" value="<?=urlencode($class)?>">
                        <input type="hidden" name="addon_action" value="activate">
						<?php if(is_plugin_active($file)): ?>
                            <p> <?php printf(__('Please %s plugin %s before activating this addon', 'premmerce'),
									'<a href="' . wp_nonce_url('plugins.php?action=deactivate&amp;plugin=' . urlencode($file), 'deactivate-plugin_' . $file) . '">' . __('deactivate', 'premmerce') . '</a>'
									, $data['Name']) ?></p>
						<?php else: ?>
                            <button type="submit" class="button"><?php _e('Activate', 'premmerce') ?></button>
						<?php endif; ?>
                    </form>
				<?php endif; ?>
            </td>
        </tr>
	<?php endforeach; ?>
    </tbody>
</table>