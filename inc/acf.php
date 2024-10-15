<?php
/**
 * Чи плагін ACF Pro активований
 */
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );	
if ( ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
	return;
}

/**
 * Disable ACF Pro update notifications
 */
if ( ! function_exists( 'mpuniversal_disable_acfpro_plugin_updates_notify' ) ) {
	add_filter( 'site_transient_update_plugins', 'mpuniversal_disable_acfpro_plugin_updates_notify' );
	function mpuniversal_disable_acfpro_plugin_updates_notify( $value ) {
		if ( isset( $value ) && is_object( $value ) ) {
			unset( $value->response['advanced-custom-fields-pro/acf.php'] );
		}
		
		return $value;
	}
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
 * Hide ACF admin notices
 */
add_action( 'admin_head', function() {
?>
	<style>
		.acf-admin-notice {
			display: none !important;
		}
	</style>
<?php
} );

/**
 * Apply Shortcodes in ACF fields
 * 
 * https://www.advancedcustomfields.com/resources/acf-format_value/
 * 
 */
if ( ! function_exists( 'mpuniversal_acf_format_value' ) ) {
	// Apply to all fields.
	// add_filter( 'acf/format_value', 'mpuniversal_acf_format_value', 10, 3 );
	// Apply to textarea fields.
	// add_filter( 'acf/format_value/type=textarea', 'my_acf_format_value', 10, 3 );
	function mpuniversal_acf_format_value( $value, $post_id, $field ) {
		return do_shortcode( $value );
	}
}

/**
 * Get ACF field from Options Page
 */
if ( ! function_exists( 'mpuniversal_get_acf_field_from_page_options' ) ) {
	function mpuniversal_get_acf_field_from_page_options( $acfFieldKey ) {
		if ( $acfFieldKey && ! empty( $acfFieldKey ) ) {
			return get_field( $acfFieldKey, 'option' );
		}
	}
}

/**
 * Get ACF field from Single Post
 */
if ( ! function_exists( 'mpuniversal_get_acf_field_from_single_post' ) ) {
	function mpuniversal_get_acf_field_from_single_post( $acfFieldKey ) {
		if ( $acfFieldKey && ! empty( $acfFieldKey ) ) {
			return get_field( $acfFieldKey );
		}
	}
}

/**
 * Add to Wysiwyg Editor change font size
 */
add_filter( 'mce_buttons', 'mpuniversal_mce_buttons' );
function mpuniversal_mce_buttons( $buttons ) {
    if ( ! in_array( 'fontsizeselect', $buttons ) ) {
        array_unshift( $buttons, 'fontsizeselect' );
    }
    return $buttons;
}
// add_filter( 'acf/fields/wysiwyg/toolbars', 'mpuniversal_custom_wysiwyg_toolbar' );
function mpuniversal_custom_wysiwyg_toolbar( $toolbars ) {
    // Створюємо нову панель інструментів, якщо її ще немає
    if ( ! isset( $toolbars['Custom Toolbar'] ) ) {
        $toolbars['Custom Toolbar'] = array();
    }

    // Перевіряємо наявність кнопки 'fontsizeselect'
    if ( ! in_array( 'fontsizeselect', $toolbars['Custom Toolbar'][1] ) ) {
        $toolbars['Custom Toolbar'][1][] = 'fontsizeselect';
    }

    return $toolbars;
}
add_filter( 'tiny_mce_before_init', 'mpuniversal_customize_tiny_mce' );
function mpuniversal_customize_tiny_mce( $initArray ) {
    $initArray['fontsize_formats'] = '8px 10px 12px 14px 16px 18px 24px 28px 30px 32px 36px 48px';
    
    return $initArray;
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