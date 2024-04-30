<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'MPUNIVERSAL_THEME_VERSION' ) ) {
	define( 'MPUNIVERSAL_THEME_VERSION', '1.0.0' );
}

if ( ! defined( 'MPUNIVERSAL_PATH' ) ) {
	define( 'MPUNIVERSAL_PATH', get_template_directory() );
}

if ( ! defined( 'MPUNIVERSAL_URL' ) ) {
	define( 'MPUNIVERSAL_URL', get_template_directory_uri() );
}

if ( ! defined( 'MPUNIVERSAL_TEXT_DOMAIN' ) ) {
	define( 'MPUNIVERSAL_TEXT_DOMAIN', 'mpuniversal' );
}

add_action( 'after_setup_theme', 'mpuniversal_setup' );
function mpuniversal_setup() {
	load_theme_textdomain( 'mpuniversal', get_template_directory() . '/languages' );
	
	add_theme_support( 'automatic-feed-links' );
	
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	register_nav_menus(
		array(
			'header-menu' => esc_html__( 'Header Navigation', 'mpuniversal' ),
			'footer-menu' => esc_html__( 'Footer Navigation', 'mpuniversal' ),
		)
	);

	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', ) );

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'mpuniversal_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for core custom logo.
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

/**
 * Register new image sizes.
 */
//add_image_size( 'thumbnail-name', width-size, height-size, false|true );

/**
 * Register widget area.
 */
add_action( 'widgets_init', 'mpuniversal_widgets_init' );
function mpuniversal_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'mpuniversal' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'mpuniversal' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}

/**
 * Enqueue scripts and styles.
 */
add_action( 'wp_enqueue_scripts', 'mpuniversal_scripts' );
function mpuniversal_scripts() {
	// CSS
	wp_enqueue_style( MPUNIVERSAL_TEXT_DOMAIN . '-style', get_stylesheet_uri(), array(), MPUNIVERSAL_THEME_VERSION );
	wp_enqueue_style( MPUNIVERSAL_TEXT_DOMAIN . '-reset', MPUNIVERSAL_URL . '/assets/css/reset.css', array(), MPUNIVERSAL_THEME_VERSION );

	// wp_enqueue_style( 'swiper', MPUNIVERSAL_URL . '/assets/css/swiper-bundle.min.css', array(), '11.1.1', 'all' );
	wp_enqueue_style( MPUNIVERSAL_TEXT_DOMAIN . '-fonts', MPUNIVERSAL_URL . '/assets/css/fonts.css', array(), MPUNIVERSAL_THEME_VERSION );
	wp_enqueue_style( MPUNIVERSAL_TEXT_DOMAIN . '-main', MPUNIVERSAL_URL . '/assets/css/main.css', array(), MPUNIVERSAL_THEME_VERSION );

	// JavaScript
	wp_enqueue_script( 'jquery' );

	// wp_enqueue_script( 'swiper', MPUNIVERSAL_URL . '/assets/js/swiper-bundle.min.js', array (), '11.1.1', true );

	wp_enqueue_script( MPUNIVERSAL_TEXT_DOMAIN . '-main', MPUNIVERSAL_URL . '/assets/js/main.js', array(), MPUNIVERSAL_THEME_VERSION, true );

	// Get value:
	// MPUNIVERSALMAINSCRIPT.ajaxUrl;
	wp_add_inline_script( MPUNIVERSAL_TEXT_DOMAIN . '-main', 'const MPUNIVERSALMAINSCRIPT = ' . json_encode( 
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'submitProcessText' => esc_html__( 'Sending...', 'mpuniversal' ),
			'sendCommentText' => esc_html__( 'Send comment', 'mpuniversal' ),
			'errorAddingCommentText' => esc_html__( 'Error adding comment', 'mpuniversal' ),
			'serverNotRespondingText' => esc_html__( 'server is not responding, try again.', 'mpuniversal' ),
		) 
	), 'before' );

	// Page Views Counter
	wp_enqueue_style( MPUNIVERSAL_TEXT_DOMAIN . '-page-views-counter', MPUNIVERSAL_URL . '/assets/css/page-views-counter.css', array(), MPUNIVERSAL_THEME_VERSION );
	wp_enqueue_script( MPUNIVERSAL_TEXT_DOMAIN . '-page-views-counter', MPUNIVERSAL_URL  . '/assets/js/page-views-counter.js', array('jquery'), '1.0', true );
    wp_localize_script( MPUNIVERSAL_TEXT_DOMAIN . '-page-views-counter', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

/**
 * Custom Post Types
 */
require MPUNIVERSAL_PATH . '/inc/custom-post-types.php';

/**
 * Custom template tags for this theme.
 */
require MPUNIVERSAL_PATH . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require MPUNIVERSAL_PATH . '/inc/template-functions.php';

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require MPUNIVERSAL_PATH . '/inc/woocommerce.php';
}

/* TGM Plugin Activation */
// require MPUNIVERSAL_PATH . '/inc/tgm-plugin-activation/class-tgm-plugin-activation.php';
// require MPUNIVERSAL_PATH . '/inc/tgm-plugin-activation/tgm-plugin-activation.php';

/* Optimize WordPress functionality */
require MPUNIVERSAL_PATH . '/inc/optimize.php';

/* Live Search with AJAX */
// require MPUNIVERSAL_PATH . '/inc/live-ajax-search.php';

/* ACF functions */
require MPUNIVERSAL_PATH . '/inc/acf.php';

/* Contact Form 7 functions */
require MPUNIVERSAL_PATH . '/inc/cf7.php';

/* Polylang functions */
require MPUNIVERSAL_PATH . '/inc/polylang.php';

/* AJAX Comments */
require MPUNIVERSAL_PATH . '/inc/ajax-comments.php';

/* AJAX Post Views Counter */
require MPUNIVERSAL_PATH . '/inc/page-views-counter.php';

