<?php

namespace Premmerce\Premmerce\Data;

class Plugins
{
    /**
     * @return array
     */
    public static function getAll()
    {
        $plugins = [];
        $plugins['woocommerce'] = [
            'name'   => 'WooCommerce',
            'plugin' => 'woocommerce.php',
        ];
        $plugins['wordpress-seo'] = [
            'name'   => 'Yoast SEO',
            'plugin' => 'wp-seo.php',
        ];
        $plugins['contact-form-7'] = [
            'name'   => 'Contact Form 7',
            'plugin' => 'wp-contact-form-7.php',
        ];
        $plugins['premmerce-search'] = [
            'name'   => 'Premmerce Search',
            'plugin' => 'premmerce-search.php',
        ];
        $plugins['premmerce-user-roles'] = [
            'name'   => 'Premmerce User Roles',
            'plugin' => 'premmerce-users-roles.php',
        ];
        $plugins['premmerce-woocommerce-brands'] = [
            'name'   => 'Premmerce WooCommerce Brands',
            'plugin' => 'premmerce-brands.php',
        ];
        $plugins['premmerce-woocommerce-product-filter'] = [
            'name'   => 'Premmerce WooCommerce Product Filter',
            'plugin' => 'premmerce-filter.php',
        ];
        $plugins['woo-customers-manager'] = [
            'name'   => 'WooCommerce Customers Manager',
            'plugin' => 'premmerce-extended-users.php',
        ];
        $plugins['woo-permalink-manager'] = [
            'name'   => 'WooCommerce Permalink Manager',
            'plugin' => 'premmerce-url-manager.php',
        ];
        $plugins['woo-seo-addon'] = [
            'name'   => 'WooCommerce SEO Addon',
            'plugin' => 'premmerce-seo-addon.php',
        ];
        $plugins['premmerce-woocommerce-wishlist'] = [
            'name'   => 'Premmerce WooCommerce Wishlist',
            'plugin' => 'premmerce-wishlist.php',
        ];
        $plugins['premmerce-redirect-manager'] = [
            'name'   => 'Premmerce Redirect Manager',
            'plugin' => 'premmerce-redirect.php',
        ];
        $plugins['premmerce-woocommerce-wholesale-pricing'] = [
            'name'   => 'Premmerce Woocommerce Wholesale Pricing',
            'plugin' => 'premmerce-price-types.php',
        ];
        $plugins['premmerce-woocommerce-product-bundles'] = [
            'name'   => 'Woocommerce Frequently Bought Together',
            'plugin' => 'premmerce-product-bundles.php',
        ];
        $plugins['google-analytics-dashboard-for-wp'] = [
            'name'   => 'Google Analytics Dashboard',
            'plugin' => 'gadwp.php.php',
        ];
        $plugins['woocommerce-google-analytics-integration'] = [
            'name'   => 'WooCommerce Google Analytics Integration',
            'plugin' => 'woocommerce-google-analytics-integration.php',
        ];
        $plugins['ml-slider'] = [
            'name'   => 'MetaSlider',
            'plugin' => 'ml-slider.php',
        ];
        return $plugins;
    }
    
    /**
     * @param $slug
     *
     * @return bool
     */
    public static function exists( $slug )
    {
        return array_key_exists( $slug, self::getAll() );
    }
    
    /**
     * @param $slug
     *
     * @return mixed
     */
    public static function get( $slug )
    {
        if ( self::exists( $slug ) ) {
            return self::getAll()[$slug];
        }
    }
    
    /**
     * @param $slug
     *
     * @return null|string
     */
    public static function getPath( $slug )
    {
        if ( $plugin = self::get( $slug ) ) {
            return ( isset( $plugin['link'] ) ? 'extra/' . $plugin['link'] : null );
        }
    }

}