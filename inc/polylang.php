<?php
/**
 * Чи плагін Polylang активований
 */
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );	
if ( ! is_plugin_active( 'polylang/polylang.php' ) ) {
	return;
}

/**
 * Заборона деактиваціїї Polylang в адмін-частині
 *
 * https://wp-kama.ru/id_9069/deactivating-important-plugins.html
 */
if ( ! function_exists( 'mpuniversal_disable_polylang_plugin_deactivation' ) ) {
	add_filter( 'plugin_action_links', 'mpuniversal_disable_polylang_plugin_deactivation', 10, 2 );
	function mpuniversal_disable_polylang_plugin_deactivation( $actions, $plugin_file ) {
		$important_plugins = array(
			'polylang/polylang.php',
		);
		if ( in_array( $plugin_file, $important_plugins ) ) {
			unset( $actions['deactivate'] );
			$actions['info'] = '<b class="musthave_js">Обов\'язковий для роботи сайту!</b>';
		}

		return $actions;
	}
}

/**
 * Для языковой версии по-умолчанию необходимо указать атрибут "x-default":
 * <link rel="alternate" href="https://sitename.ua/" hreflang="x-default">
 *
 * https://github.com/polylang/polylang/issues/826
 */
if ( ! function_exists( 'mpuniversal_polylang_add_xdefault' ) ) {
	add_filter( 'pll_rel_hreflang_attributes', 'mpuniversal_polylang_add_xdefault', 10, 1 );
	function mpuniversal_polylang_add_xdefault( $hreflangs ) {
		$default = array(
			'x-default' => reset( $hreflangs ) // Fetch the first language URL in the list as x-default
		);

		return $hreflangs = $default + $hreflangs;
	}
}

/**
 * для русской версии: hreflang="ru-RU"
 * для украинской: hreflang="uk-UA"
 * для английской: hreflang="en-EN"
 *
 * https://wordpress.org/support/topic/hreflang-tags-not-correct/
 */
// Define the pll_rel_hreflang_attributes callback.
if ( ! function_exists( 'mpuniversal_filter_pll_rel_hreflang_attributes' ) ) {
	add_filter( 'pll_rel_hreflang_attributes', 'mpuniversal_filter_pll_rel_hreflang_attributes', 10, 1 );
	function mpuniversal_filter_pll_rel_hreflang_attributes( $hreflangs ) {

		foreach ( $hreflangs as $lang => $url ) {
			if ( $lang === 'en' ) {
				printf( '<link rel="alternate" href="%s" hreflang="%s" />' . "\n", esc_url( $url ), esc_attr( 'en-EN' ) );
			}
			if ( $lang === 'uk' ) {
				printf( '<link rel="alternate" href="%s" hreflang="%s" />' . "\n", esc_url( $url ), esc_attr( 'uk-UA' ) );
			}
			if ( $lang === 'ru' ) {
				printf( '<link rel="alternate" href="%s" hreflang="%s" />' . "\n", esc_url( $url ), esc_attr( 'ru-RU' ) );
			}
		}

		return $hreflangs;
	}
}

/**
 * Polylang language switcher
 */
/**
 * Polylang Shortcode - https://wordpress.org/plugins/polylang/
 * Add this code in your functions.php
 * Put shortcode [polylang_langswitcher] to post/page for display flags
 * https://gist.github.com/nicomollet/47ba9808f3187c9f1568d8f7c4355b54
 *
 * @return string
 */
add_shortcode( 'cityhouse_polylang_langswitcher_shortcode', 'cityhouse_polylang_langswitcher' );
function cityhouse_polylang_langswitcher() {
    ob_start();
	
    $output = '';
    $dropdown = '';

    // if ( wp_is_mobile() === false ) {    
        $dropdown = 1;
    // } elseif ( wp_is_mobile() === true ) {
        // $dropdown = 0;
    // }

    if ( function_exists( 'pll_the_languages' ) ) {
        $args  = [
            'dropdown'         => $dropdown,
            'show_flags'       => 0,
            'show_names'       => 0,
            'echo'             => 0,
            'hide_current'     => 1,
            'display_names_as' => 'slug',
        ];
        $output = '<li class="menu__item menu__item--language">' . pll_the_languages( $args ) . '</li>';
    }
    
    ob_end_clean();
    
    return $output;
}

/**
 * Polylang Strings
 * 
 * Не обов'язково функції перекладу вішати на хук "init". Можна просто без хуку та без функції.
 */
if ( ! function_exists( 'mpuniversal_polylang_strings' ) ) {
	add_action( 'init', 'mpuniversal_polylang_strings' );
	function mpuniversal_polylang_strings() {
		/**
		 * Проверка, доступны ли функции перевода строк Polylang
		 *
		 * https://misha.agency/wordpress/kak-perevodit-temu-s-polylang.html
		 *
		 * Если вдруг функции перестанут существовать, то ничего не случится, будет всего лишь выводиться английская версия перевода!
		 */
		if ( function_exists( 'pll_register_string' ) && function_exists( 'pll__' ) && function_exists( 'pll_e' ) ) {
			return;
		}

		// pll_register_string(
		// 	'mpuniversal_order_id', // название строки
		// 	'Order ID', // сама строка
		// 	'Order check', // категория для удобства
		// 	false // будут ли тут переносы строк в тексте или нет
		// );
	}
}