<?php namespace Premmerce\ProductComparison\Integration;

use Premmerce\ProductComparison\Frontend\Frontend;

class OceanWpIntegration
{

    /**
     * OceanWpIntegration constructor.
     *
     * @param Frontend $frontend
     */
    public function __construct($frontend)
    {
        // Show button in quick view
        if (wp_doing_ajax()) {
            add_action('ocean_after_single_product_quantity-button', [$frontend, 'renderComparisonBtn'], 10);
        }

        // Show button on loop product
        remove_action('woocommerce_after_shop_loop_item', [$frontend, 'renderComparisonBtn'], 5);
        add_action('ocean_after_archive_product_inner', [$frontend, 'renderComparisonBtn'], 5);


    }
}