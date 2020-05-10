<?php
remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' ); // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10); // prev link
remove_action( 'wp_head', 'start_post_rel_link', 10); // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10); // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

function my_deregister_styles() {
	wp_deregister_style( 'wp-pagenavi' );
}
add_action( 'wp_print_styles', 'my_deregister_styles', 100 );

add_action( 'template_redirect', function () {
//Disable gutenberg style in Front
	function wps_deregister_styles() {
		wp_dequeue_style( 'wp-block-library' );
	}
	add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );
	remove_action('template_redirect', 'rest_output_link_header', 11);
	remove_action('wp_head', 'rest_output_link_wp_head', 10);
	remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
	add_filter( 'show_recent_comments_widget_style', function() { return false; });
	if ( ! class_exists( 'WPSEO_Frontend' ) ) {
		return;
	}
	$instance = WPSEO_Frontend::get_instance();
// make sure, future version of the plugin does not break our site.
	if ( ! method_exists( $instance, 'debug_mark') ) {
		return ;
	}
	// ok, let us remove the love letter.
	remove_action( 'wpseo_head', array( $instance, 'debug_mark' ), 2 );
}, 9999 );

//EMBED LINK
add_action( 'init', function() { // Remove the REST API endpoint.
	remove_action('rest_api_init', 'wp_oembed_register_route'); // Turn off oEmbed auto discovery. // Don't filter oEmbed results.
	remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10); // Remove oEmbed discovery links.
	remove_action('wp_head', 'wp_oembed_add_discovery_links'); // Remove oEmbed-specific JavaScript from the front-end and back-end.
	remove_action('wp_head', 'wp_oembed_add_host_js');
}, PHP_INT_MAX - 1 );

function disable_yoast_schema_data($data){
	$data = array();
	return $data;
}
add_filter('wpseo_json_ld_output', 'disable_yoast_schema_data', 10, 1);

// Каноническая ссылка для страницы каталога
function yoast_seo_canonical_change_woocom_shop( $canonical ) {
	return false;
}
add_filter( 'wpseo_canonical', 'yoast_seo_canonical_change_woocom_shop', 10, 1 );

//ACF7 p tag
add_filter('wpcf7_autop_or_not', '__return_false');

remove_action( 'woocommerce_register_form', 'wc_registration_privacy_policy_text', 20 );