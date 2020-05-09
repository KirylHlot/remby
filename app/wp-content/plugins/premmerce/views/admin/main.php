<?php
if(!defined('WPINC')){
	die;
}
?>

<div class="wrap">
    <div data-error-container></div>
    <h1 class="wp-heading-inline"><?php _e('Premmerce', 'premmerce') ?></h1>

    <div class="premmerce-tab">
        <nav class="premmerce-tab__list">
			<?php foreach($tabs as $tab => $name): ?>
				<?php $class = ($tab == $current)? 'premmerce-tab__link--active' : '' ?>
                <a class='premmerce-tab__link <?php echo $class ?>'
                   href='<?php echo add_query_arg('tab', $tab, $url) ?>'><?php echo $name ?></a>
			<?php endforeach; ?>
        </nav>
    </div>
	<?php $file = __DIR__ . "/tabs/{$current}.php" ?>
	<?php if(file_exists($file)): ?>
		<?php include $file ?>
	<?php endif; ?>
</div>
