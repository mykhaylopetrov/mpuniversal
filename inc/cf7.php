<?php
/**
 * Чи плагін CF7 активований
 */
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );	
if ( ! is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
	return;
}

/**
 * Заборона деактиваціїї CF7 в адмін-частині
 *
 * https://wp-kama.ru/id_9069/deactivating-important-plugins.html
 */
if ( ! function_exists( 'mpuniversal_disable_cf7_plugin_deactivation' ) ) {
	add_filter( 'plugin_action_links', 'mpuniversal_disable_cf7_plugin_deactivation', 10, 2 );
	function mpuniversal_disable_cf7_plugin_deactivation( $actions, $plugin_file ) {
		$important_plugins = array(
			'contact-form-7/wp-contact-form-7.php',
		);
		if ( in_array( $plugin_file, $important_plugins ) ) {
			unset( $actions['deactivate'] );
			$actions['info'] = '<b class="musthave_js">Обов\'язковий для роботи сайту!</b>';
		}

		return $actions;
	}
}