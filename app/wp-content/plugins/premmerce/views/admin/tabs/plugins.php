<?php
if(!defined('WPINC')){
	die;
}

use Premmerce\Premmerce\Api\PluginApi;

/**
 * @var $dep PluginApi
 */

require_once __DIR__ . "/../functions.php";

?>

<table class="widefat fixed striped">
    <thead>
    <tr>
        <th>
			<?php _e('Plugin', 'premmerce') ?>
        </th>
        <th>
			<?php _e('Installed version', 'premmerce') ?>
        </th>
        <th>
			<?php _e('Active', 'premmerce') ?>
        </th>
    </tr>
    </thead>
    <tbody>
	<?php foreach($dep->getExtra() as $slug => $data): ?>
		<?php
		$installed = $dep->isInstalledPlugin($slug);
		$active    = $dep->isActivePlugin($slug);
		$state     = ($installed? ($active? 'active' : 'installed') : 'none');
		?>
        <tr class="premmerce-plugin-row" data-plugin-row="<?=$state?>">
            <td>
				<?php echo $data['name'] ?>
            </td>
            <td>
				<?php echo $dep->isInstalledPlugin($slug)? $dep->getInstalledPlugin($slug)['Version'] : '-' ?>
            </td>
            <td>
                <button class="button premmerce-plugin-row__button-install"
                        data-success-state="installed"
                        data-action="<?=arrayToDataAttr($data['install_action'])?>"><?php _e('Install') ?>
                    <div class="premmerce-wp-spinner"></div>
                </button>
                <button class="button premmerce-plugin-row__button-activate"
                        data-success-state="active"
                        data-action="<?=arrayToDataAttr($data['activate_action'])?>"><?php _e('Activate') ?>
                    <div class="premmerce-wp-spinner"></div>
                </button>
                <p class="premmerce-plugin-row__text-active">
					<?php _e('Active', 'premmerce') ?>
                </p>
            </td>
        </tr>
	<?php endforeach; ?>
    </tbody>
</table>