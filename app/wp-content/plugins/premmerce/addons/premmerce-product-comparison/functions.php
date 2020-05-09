<?php

use Premmerce\ProductComparison\Frontend\ComparisonFunctions;

if ( ! function_exists( 'premmerce_comparison' ) ) {

    function premmerce_comparison() {

        return ComparisonFunctions::getInstance();

    }

}
