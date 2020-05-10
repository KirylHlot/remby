<?php
/**
 * starter_pack functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package starter_pack
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'starter_pack_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function starter_pack_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on starter_pack, use a find and replace
		 * to change 'starter_pack' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'starter_pack', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'main_menu' => esc_html__( 'main_menu', 'starter_pack' ),
				'second_menu' => esc_html__( 'second_menu', 'starter_pack' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'starter_pack_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'starter_pack_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function starter_pack_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'starter_pack_content_width', 640 );
}
add_action( 'after_setup_theme', 'starter_pack_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function starter_pack_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'starter_pack' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'starter_pack' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'starter_pack_widgets_init' );



function starter_pack_scripts() {

//	if(!is_admin()){
//		wp_deregister_script('jquery');
//	};

	wp_enqueue_style( 'starter_pack-style', get_stylesheet_uri() );
	//custom styles
	wp_enqueue_style( 'custom_style', get_template_directory_uri()  . '/assets/css/styles.min.css', false, '1.1.1' );
	wp_enqueue_script( 'custom_js', get_template_directory_uri() . '/assets/js/scripts.min.js', array(), '1.1.1', true );

	wp_localize_script('custom_js', 'myAjax', array(
	  'ajaxurl' => admin_url('admin-ajax.php')
  ));

	wp_localize_script('custom_js', 'wpcf7', array(
	  'loaderUrl' => plugins_url().'/contact-form-7/images/ajax-loader.gif',
    "sending" => "Sending ...",
    "apiSettings" => [
	    "root" => get_site_url()."/wp-json/contact-form-7/v1",
      "namespace" => "contact-form-7/v1"],
  ));



}
add_action( 'wp_enqueue_scripts', 'starter_pack_scripts' );



/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

//page acf options
if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' => 'Данные сайта',
		'menu_title' => 'Общие поля сайта',
		'menu_slug' => 'site-settings',
		'capability' => 'edit_posts',
		'redirect' => false
	));

}

function add_stati_post_types(){
  register_post_type('stati', array(
    'labels'             => array(
      'name'               => 'Статьи', // Основное название типа записи
      'singular_name'      => 'Статьи', // отдельное название записи типа Book
      'add_new'            => 'Добавить новую',
      'add_new_item'       => 'Добавить статью',
      'edit_item'          => 'Редактировать статью',
      'new_item'           => 'Добавить статью',
      'view_item'          => 'Посмотреть статью',
      'search_items'       => 'Найти статью',
      'not_found'          => 'Статей не найдено',
      'not_found_in_trash' => 'В корзине статей не найдено',
      'parent_item_colon'  => '',
      'menu_name'          => 'Статьи'

    ),
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => true,
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'menu_icon'          => 'dashicons-layout',
    'supports'           => array('title','editor', 'comments'),
    'taxonomies' =>       array('category', 'post_tag')
  ) );
}
add_action('init', 'add_stati_post_types');

include_once ('templates/function_optimized.php');
include_once ('templates/icons_pack.php');
include_once ('templates/theme_menus.php');
include_once ('templates/theme_parts.php');
include_once ('templates/product_carousel_item.php');
include_once ('templates/ajax_functions.php');

if( wp_doing_ajax() ){
  add_action('wp_ajax_mp_do_filter', 'show_filtered_products');
  add_action('wp_ajax_nopriv_mp_do_filter', 'show_filtered_products');

  add_action('wp_ajax_refresh_cart_count', 'get_cart_count');
  add_action('wp_ajax_nopriv_refresh_cart_count', 'get_cart_count');
}


add_filter( 'woocommerce_add_to_cart_fragments', 'add_to_cart_refresh' );
function add_to_cart_refresh( $fragments ) {
  global $woocommerce;
  $fragments['.cart_count'] = '<div class="cart_count">' . $woocommerce->cart->cart_contents_count . '</div>';
  return $fragments;
}

add_filter( 'woocommerce_enqueue_styles', '__return_false' );
