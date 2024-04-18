<?php
/**
 * Чи плагін ACF Pro активований
 */
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );	
if ( ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
	return;
}

/** 
 * Site Locale 
 */
if ( ! defined( 'MPUNIVERSAL_SITE_LOCALE' ) ) {
	define( 'MPUNIVERSAL_SITE_LOCALE', mpuniversal_get_page_locale() );
}

/**
 * Заборона деактиваціїї ACF Pro в адмін-частині
 *
 * https://wp-kama.ru/id_9069/deactivating-important-plugins.html
 */
if ( ! function_exists( 'mpuniversal_disable_acf_plugin_deactivation' ) ) {
	add_filter( 'plugin_action_links', 'mpuniversal_disable_acf_plugin_deactivation', 10, 2 );
	function mpuniversal_disable_acf_plugin_deactivation( $actions, $plugin_file ) {
		$important_plugins = array(
			'advanced-custom-fields-pro/acf.php',
		);
		if ( in_array( $plugin_file, $important_plugins ) ) {
			unset( $actions['deactivate'] );
			$actions['info'] = '<b class="musthave_js">Обов\'язковий для роботи сайту!</b>';
		}

		return $actions;
	}
}

/**
 * Сховати адмін-меню ACF
 * 
 * Потрібно, якщо поля створюються лише кодом
 * 
 * https://www.advancedcustomfields.com/resources/how-to-hide-acf-menu-from-clients/
 * 
 */
add_filter( 'acf/settings/show_admin', '__return_false' );

/**
 * Apply Shortcodes in ACF fields
 * 
 * https://www.advancedcustomfields.com/resources/acf-format_value/
 * 
 */
if ( ! function_exists( 'mpuniversal_acf_format_value' ) ) {
	// Apply to all fields.
	add_filter( 'acf/format_value', 'mpuniversal_acf_format_value', 10, 3 );
	// Apply to textarea fields.
	// add_filter( 'acf/format_value/type=textarea', 'my_acf_format_value', 10, 3 );
	function mpuniversal_acf_format_value( $value, $post_id, $field ) {
		return do_shortcode( $value );
	}
}

/**
 * Create Global Options Page in main Admin Menu
 */
if ( ! function_exists( 'mpuniversal_create_acf_global_options_page' ) ) {
	add_action( 'acf/init', 'mpuniversal_create_acf_global_options_page' );
	function mpuniversal_create_acf_global_options_page() {

	}
}

/**
 * Create Options Section in Single Post Page 
 */
if ( ! function_exists( 'mpuniversal_create_single_post_acf_fields' ) ) {
	add_action( 'acf/init', 'mpuniversal_create_single_post_acf_fields' );
	function mpuniversal_create_single_post_acf_fields() {
		// acf_add_local_field_group( array(
        //     'key' => 'single_posts_options_page',
        //     'title' => esc_html__( 'Post edition section', 'mpuniversal' ),
        //     'fields' => array(),
        //     'location' => array(
        //         array(
        //             array(
        //                 'param' => 'post_type',
        //                 'operator' => '==',
        //                 'value' => 'post',
        //             ),
        //         ),
        //     ),
        // ) );

		// acf_add_local_field( array(
        //     'key' => 'single_post_description_field',
        //     'label' => esc_html__( 'Description of the post after the title', 'mpuniversal' ),
        //     'name' => 'single_post_description',
        //     'type' => 'textarea',
        //     'parent' => 'single_posts_options_page',
        //     'placeholder' => esc_html__( 'Add description of the post after the title...', 'mpuniversal' ),
        // ) );
	}
}