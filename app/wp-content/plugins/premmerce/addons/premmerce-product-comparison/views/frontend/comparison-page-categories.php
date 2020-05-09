<?php use Premmerce\ProductComparison\ProductComparisonPlugin; ?>

<?php if (!count($data)): ?>
    <p>
        <?php _e('There are no products to compare',ProductComparisonPlugin::DOMAIN); ?>
    </p>
<?php else: ?>
    <div class="site-main woocommerce">
            <?php woocommerce_product_loop_start(); ?>
                <?php foreach ($data as $category):
                    $keys = array_keys($category['products']);
                    $first_item_key = $keys[0];
                    $first_product = $category['products'][$first_item_key];

                    if($first_product->get_type() == 'variation'){
                        $parent_data = $first_product->get_parent_data();
                        $image_id = $parent_data['image_id'];
                    } else {
                        $image_id = $first_product->get_image_id();
                    }

                    ?>
                    <li <?php wc_product_cat_class(); ?>>
                        <a href="<?php echo esc_url($category['url']); ?>">
                            <?php echo wp_get_attachment_image($image_id,'woocommerce_thumbnail',false); ?>
                            <h2 class="woocommerce-loop-category__title">
                                <?php
                                /* translators: %s: Category name */
                                echo esc_html($category['name']); ?>
                                <mark class="count">(<?php echo count($category['products']); ?>)</mark>
                            </h2>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php woocommerce_product_loop_end(); ?>
    </div>
<?php endif; ?>
