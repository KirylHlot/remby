<?php use Premmerce\ProductComparison\ProductComparisonPlugin; ?>

<?php echo $args['before_widget']; ?>

<?php if ($title) : ?>
    <?php echo $args['before_title'] . $title . $args['after_title']; ?>
<?php endif ?>

<div>
    <a href="<?php echo get_permalink(get_post(get_option(ProductComparisonPlugin::OPTION_PAGE))); ?>">
        <?php _e('Comparison',ProductComparisonPlugin::DOMAIN); ?>
        (<?php echo premmerce_comparison()->totalProducts(); ?>)
    </a>
</div>

<?php echo $args['after_widget']; ?>
