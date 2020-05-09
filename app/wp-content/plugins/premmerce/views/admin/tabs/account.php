<?php

if ( ! defined('WPINC')) {
    die;
}

if (function_exists('premmerce_pwk_fs') && premmerce_pwk_fs()->is_registered()) {
	premmerce_pwk_fs()->add_filter('hide_account_tabs', '__return_true');
	premmerce_pwk_fs()->_account_page_load();
	premmerce_pwk_fs()->_account_page_render();
}
