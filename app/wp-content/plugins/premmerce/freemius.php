<?php

// Create a helper function for easy SDK access.
function premmerce_pwk_fs()
{
    global  $premmerce_pwk_fs ;
    
    if ( !isset( $premmerce_pwk_fs ) ) {
        // Include Freemius SDK.
        require_once dirname( __FILE__ ) . '/freemius/start.php';
        $premmerce_pwk_fs = fs_dynamic_init( array(
            'id'             => '1527',
            'slug'           => 'premmerce',
            'type'           => 'plugin',
            'public_key'     => 'pk_75ce3208e2d74bfed236c81fe557e',
            'is_premium'     => false,
            'has_addons'     => false,
            'has_paid_plans' => true,
            'trial'          => array(
            'days'               => 7,
            'is_require_payment' => true,
        ),
            'menu'           => array(
            'slug'    => 'premmerce',
            'support' => false,
        ),
            'is_live'        => true,
        ) );
    }
    
    return $premmerce_pwk_fs;
}

// Init Freemius.
premmerce_pwk_fs();
// Signal that SDK was initiated.
do_action( 'premmerce_pwk_fs_loaded' );