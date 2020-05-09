<?php

use Premmerce\ProductComparison\ProductComparisonPlugin;

?>

<div class="product-comparison" data-pc-add-to-compare>

    <!-- Item already is in compare list -->
    <a class="product-comparison__link <?php echo premmerce_comparison()->checkInComparison($productId) ? '':'hidden'; ?>"
       href="<?php echo esc_url(premmerce_comparison()->getUrlComparison()); ?>"
       data-pc-add-to-compare-btn="open"
    >
        <div class="product-comparison__icon">
            <svg class="svg-icon" id="svg-icon__compare" viewBox="0 0 13 15" width="100%" height="100%"><path d="M0 13v2h13v-2H0zM5 0a1.08 1.08 0 0 0-1 1v11h2V1a1.08 1.08 0 0 0-1-1zm6 2a1.08 1.08 0 0 0-1 1v9h2V3a1.08 1.08 0 0 0-1-1zM8 6a1.08 1.08 0 0 0-1 1v5h2V7a1.08 1.08 0 0 0-1-1zM2 4a1.08 1.08 0 0 0-1 1v7h2V5a1.08 1.08 0 0 0-1-1z"></path></svg>
        </div>
        <?php esc_html_e('Compare', ProductComparisonPlugin::DOMAIN); ?>
    </a>

    <!-- Item isn't in compare list -->
    <a class="product-comparison__link <?php echo premmerce_comparison()->checkInComparison($productId) ? 'hidden':''; ?>" type="button"
       href="#"
       data-pc-add-to-compare-btn="add"
       data-pc-add-to-compare-btn-type="link"
       data-pc-add-to-compare-data="<?php echo esc_attr(wp_json_encode(array(
           'url' => esc_url($url),
           'comparison_product_id' => $productId,
           '_wpnonce' => wp_create_nonce('wp_rest')
       ))); ?>"
       data-loader="<?php esc_attr_e('Loading...', ProductComparisonPlugin::DOMAIN); ?>"
    >
        <div class="product-comparison__icon">
            <svg class="svg-icon" id="svg-icon__compare" viewBox="0 0 13 15" width="100%" height="100%"><path d="M0 13v2h13v-2H0zM5 0a1.08 1.08 0 0 0-1 1v11h2V1a1.08 1.08 0 0 0-1-1zm6 2a1.08 1.08 0 0 0-1 1v9h2V3a1.08 1.08 0 0 0-1-1zM8 6a1.08 1.08 0 0 0-1 1v5h2V7a1.08 1.08 0 0 0-1-1zM2 4a1.08 1.08 0 0 0-1 1v7h2V5a1.08 1.08 0 0 0-1-1z"></path></svg>
        </div>
        <?php esc_html_e('Add to Comparison', ProductComparisonPlugin::DOMAIN); ?>
    </a>


</div>
