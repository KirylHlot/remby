<?php

if (!count($data)) {
    return;
}

?>

<div class="woocommerce premerce-comparision-frame">
    <table class="premerce-comparision-table">
        <thead>
            <td></td>
            <?php foreach ($data['products'] as $product): ?>
                <td>
                    <div class="pc-compare-product">
                        <div class="pc-compare-product__left">
                            <?php
                            if ($product->get_type() == 'variation') {
                                $imgId = $product->get_parent_data()['image_id'];
                            } else {
                                $imgId = $product->get_image_id();
                            }
                            ?>
                            <a href="<?php echo get_permalink($product->get_ID()); ?>">
                                <img height="100" width="100" src="<?php echo wp_get_attachment_url($imgId) ?>">
                            </a>
                        </div>
                        <div class="pc-compare-product__right">
                            <div class="pc-compare-product__row">
                                <a href="<?php echo get_permalink($product->get_ID()); ?>">
                                    <?php echo $product->get_name(); ?>
                                </a>
                            </div>
                            <div class="pc-compare-product__row">
                                <?php echo $product->get_price_html(); ?>
                            </div>
                            <div class="pc-compare-product__row">
                                <a class="pc-compare-product__delete-link" href="<?php echo wp_nonce_url($urlDelete . $product->get_id(),'wp_rest') . '&products=' . implode(',',array_keys($data['products'])); ?>">
                                    Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
            <?php endforeach; ?>
        </thead>
        <tbody>
            <?php foreach ($data['attributes'] as $attr): ?>
                <tr>
                    <td><?= $attr['title']; ?></td>

                    <?php foreach ($attr['values'] as $value): ?>
                        <td><?= $value ? $value : '-'; ?></td>
                    <?php endforeach; ?>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
