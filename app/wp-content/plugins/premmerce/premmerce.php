<?php

use Premmerce\Premmerce\PremmercePlugin;


/**
 * Premmerce Premium plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Premmerce
 * Plugin URI:        https://premmerce.com/premmerce-main-plugin-woocommerce-plugins-bundle/
 * Description:       Premmerce is a must-have toolkit for WooCommerce with a detailed Setup Wizard for your store.
 * Version:           1.3.14
 * Author:            premmerce
 * Author URI:        https://premmerce.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       premmerce
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 3.9
 *
  */

// If this file is called directly, abort.
if(!defined('WPINC')){
	die;
}


if(!function_exists('premmerce_pwk_fs')){

	call_user_func(function(){

		require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

		require_once plugin_dir_path(__FILE__) . 'freemius.php';

		$main = new PremmercePlugin(__FILE__);

		register_activation_hook(__FILE__, [$main, 'activate']);

		register_deactivation_hook(__FILE__, [$main, 'deactivate']);

		register_uninstall_hook(__FILE__, [PremmercePlugin::class, 'uninstall']);

		$main->run();

	});

}
