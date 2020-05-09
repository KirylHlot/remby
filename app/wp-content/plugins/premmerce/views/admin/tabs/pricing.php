<?php

if ( ! defined('WPINC')) {
    die;
}

if (function_exists('premmerce_pwk_fs')) {
	premmerce_pwk_fs()->_pricing_page_render();
}
