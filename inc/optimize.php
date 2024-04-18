<?php

/**
 * Перенести виклик вбудованого jQuery до підвалу
 * 
 * https://themesharbor.com/move-jquery-to-footer-in-wordpress/
 * 
 */
if ( ! function_exists( 'mptheme_move_jquery_to_footer' ) ) {
	// add_action( 'wp_enqueue_scripts', 'mptheme_move_jquery_to_footer' );
	function mptheme_move_jquery_to_footer() {
		wp_scripts()->add_data( 'jquery', 'group', 1 );
		wp_scripts()->add_data( 'jquery-core', 'group', 1 );
		wp_scripts()->add_data( 'jquery-migrate', 'group', 1 );
	}
}

/**
 * Перенести усі JS-скрипти в підвал
 * 
 * https://www.kevinleary.net/move-javascript-bottom-wordpress/
 * 
 */
if ( ! function_exists( 'mptheme_enqueue_scripts_move_footer' ) ) {
	// add_action( 'after_setup_theme', 'mptheme_enqueue_scripts_move_footer' );
	function mptheme_enqueue_scripts_move_footer() {
		remove_action( 'wp_head', 'wp_print_scripts' );
		remove_action( 'wp_head', 'wp_print_head_scripts',9 );
		remove_action( 'wp_head', 'wp_enqueue_scripts',1 );
		add_action( 'wp_footer', 'wp_enqueue_scripts',5 );
		add_action( 'wp_footer', 'wp_print_head_scripts',5 );
		add_action( 'wp_footer', 'wp_print_scripts',5 );
	}
}

/**
 * Вимкнути всі ревізії
 * 
 * https://wpmag.ru/2013/udalit-vse-redakcii-wordpress/
 * 
 * Видалити вже існуючі ревізії:
 * DELETE FROM wp_postmeta WHERE post_id IN (SELECT ID FROM wp_posts WHERE post_type = 'revision' AND post_name LIKE '%revision%');
 * DELETE FROM wp_term_relationships WHERE object_id IN (SELECT ID FROM wp_posts WHERE post_type = 'revision' AND post_name LIKE '%revision%');
 * DELETE FROM wp_posts WHERE post_type = 'revision' AND post_name LIKE '%revision%';
 * 
 */
if ( ! function_exists( 'mptheme_revisions_to_keep' ) ) {
	add_filter( 'wp_revisions_to_keep', 'mptheme_revisions_to_keep' );
	function mptheme_revisions_to_keep( $revisions ) {
		return 0;
	}
}
// Отключить создание ревизий
// define( 'WP_POST_REVISIONS', false );

/**
 * Відключаємо автозбереження постів у браузері під час редагування
 * 
 * https://m.habr.com/ru/post/67680/
 * https://wp-kama.ru/question/otklyuchit-avtosohranenie-v-brauzer-kotore-poyavilos-v-versii-4-6
 * 
 */
if ( ! function_exists( 'mptheme_disable_autosave' ) ) {
	add_action( 'wp_print_scripts', 'mptheme_disable_autosave' );
	function mptheme_disable_autosave() {
		wp_deregister_script( 'autosave' );
	}
}

/** 
 * Отключить автосохранение WordPress - увеличиваем интервал автосохранения на 1 год 
 */
// define( 'AUTOSAVE_INTERVAL', 60*60*60*24*365 );

/**
 * Відключаємо автозбереження постів у Gutenberg
 * 
 * Просто збільшуємо інтервал збереження до 1 тижня - 604800 секунд
 *
 * Перевірка значення в масиві - тільки коли відкрито редактор:
 *
 * var_dump($editor_settings['autosaveInterval']); 
 * 
 */
if ( ! function_exists( 'mptheme_change_gutenberg_autosave_interval' ) ) {
	add_filter( 'block_editor_settings_all', 'mptheme_change_gutenberg_autosave_interval', 10, 2 );
	function mptheme_change_gutenberg_autosave_interval( $editor_settings, $post ) {
		$editor_settings['autosaveInterval'] = 604800; 
		return $editor_settings;
	}
}

/**
 * Видалення віджетів з Консолі WordPress
 * 
 * https://wp-kama.ru/id_153/udalenie-vidzhetov-wordpress.html
 * 
 */
if ( ! function_exists( 'mptheme_clear_wp_dash' ) ) {
	add_action( 'wp_dashboard_setup', 'mptheme_clear_wp_dash' );
	function mptheme_clear_wp_dash(){
		$dash_side   = & $GLOBALS['wp_meta_boxes']['dashboard']['side']['core'];
		$dash_normal = & $GLOBALS['wp_meta_boxes']['dashboard']['normal']['core'];

		unset( $dash_side['dashboard_quick_press'] );       // Швидкий чорновик
		unset( $dash_side['dashboard_primary'] );           // Новини та заходи WordPress
		//unset( $dash_side['dashboard_secondary'] );         // Інші Новини WordPress
		//unset( $dash_side['dashboard_recent_drafts'] );     // Останні чорновики
		unset( $dash_normal['dashboard_right_now'] );       // На виду
		unset( $dash_normal['dashboard_activity'] );        // Активність
		//unset( $dash_normal['dashboard_incoming_links'] );  // Вхідні посилання
		//unset( $dash_normal['dashboard_recent_comments'] ); // Останні комментарі
		//unset( $dash_normal['dashboard_plugins'] );         // Останні Плагіни
	}
}

/** 
 * Приховати віджет Ласкаво просимо до WordPress
 */
remove_action( 'welcome_panel', 'wp_welcome_panel' );

/**
 * Вимикаємо emoji
 * 
 * https://wp-kama.ru/question/kak-otplyuchit-emoji-v-wordpress
 * 
 */
if ( ! function_exists( 'mptheme_disable_emojis_tinymce' ) ) {
	add_filter( 'tiny_mce_plugins', 'mptheme_disable_emojis_tinymce' );
	function mptheme_disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}
}
if ( ! function_exists( 'mptheme_disable_emojis_remove_dns_prefetch' ) ) {
	add_filter( 'wp_resource_hints', 'mptheme_disable_emojis_remove_dns_prefetch', 10, 2 );
	function mptheme_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
		//https://ru.wordpress.org/plugins/disable-emojis/
		if ( 'dns-prefetch' == $relation_type ) {
			// Strip out any URLs referencing the WordPress.org emoji location
			$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
			foreach ( $urls as $key => $url ) {
				if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
					unset( $urls[$key] );
				}
			}
		}
		return $urls;
	}
}
add_filter('emoji_svg_url', '__return_empty_string');
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

/**
 * Забираємо посилання на X-Pingback + відключити XML-RPC (site.ru/xmlrpc.php)
 * 
 * https://wpplus.ru/delete-xpingback-link/
 * 
 * !!! Після цих дій все одно файл site.ru/xmlrpc.php доступний і відкриватиметься. Закривати доступ до нього через .htaccess, щоб віддавав 404.
 * 
 */
 
// Disable X-Pingback HTTP Header. 
if ( ! function_exists( 'mptheme_remove_pingback_header' ) ) {
	add_filter( 'wp_headers', 'mptheme_remove_pingback_header' );
	function mptheme_remove_pingback_header( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}
}
if ( ! function_exists( 'mptheme_remove_x_pingback_headers' ) ) {
	add_filter( 'template_redirect', 'mptheme_remove_x_pingback_headers' );
	function mptheme_remove_x_pingback_headers( $headers ) {
		if ( function_exists( 'header_remove' ) ) {
			header_remove( 'X-Pingback' );
			header_remove( 'Server' );
		}
	}
}
if ( ! function_exists( 'mptheme_block_xmlrpc_attacks' ) ) {
	add_filter( 'xmlrpc_methods', 'mptheme_block_xmlrpc_attacks' );
	function mptheme_block_xmlrpc_attacks( $methods ) {
		unset( $methods['system.multicall'] );
		unset( $methods['system.listMethods'] );
		unset( $methods['system.getCapabilities'] );
		unset( $methods['pingback.extensions.getPingbacks'] );
		unset( $methods['pingback.ping'] );
		unset( $methods['wp.getUsersBlogs'] );
		return $methods;
	}
}
if ( ! function_exists( 'mptheme_disable_pingback' ) ) {
	add_action( 'pre_ping', 'mptheme_disable_pingback' );
	function mptheme_disable_pingback( &$links ) {
		foreach ( $links as $l => $link )
		if ( 0 === strpos( $link, get_option( 'home' ) ) )
		unset($links[$l]);
	}
}
add_filter( 'pre_update_option_enable_xmlrpc', '__return_false' );
add_filter( 'pre_option_enable_xmlrpc', '__return_zero' );
// закриємо можливість публікації через xmlrpc.php
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
* Видаляємо стилі .recentcomments
*
* https://wpplus.ru/delete-recentcomments-style/
*
*/
if ( ! function_exists( 'mptheme_remove_recent_comments_style' ) ) {
	add_action( 'widgets_init', 'mptheme_remove_recent_comments_style' );
	function mptheme_remove_recent_comments_style() {
		global $wp_widget_factory;
		remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
	}
}

/**
 * Прибрати посилання на сайт wordpress.org з адмін-бару
 * 
 * https://wpplus.ru/disable-wordpressorg-links/
 * 
 */
if ( ! function_exists( 'mptheme_remove_admin_bar_links' ) ) {
	add_action( 'wp_before_admin_bar_render', 'mptheme_remove_admin_bar_links' );
	function mptheme_remove_admin_bar_links() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'wp-logo' );
		$wp_admin_bar->remove_menu( 'about' );
		$wp_admin_bar->remove_menu( 'wporg' );
		$wp_admin_bar->remove_menu( 'documentation' );
		$wp_admin_bar->remove_menu( 'support-forums' );
		$wp_admin_bar->remove_menu( 'feedback' );
		$wp_admin_bar->remove_menu( 'view-site' );
	}
}

/**
 * Видаляємо коротке посилання /?p=
 * 
 * https://wpplus.ru/delete-p-link/
 * 
 */
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

/**
 * Видаляємо посилання на попередній, наступний запис, відключення посилань у <head>
 * 
 * <link rel='prev' .../>
 * <link rel='next' .../>
 * 
 */
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
add_filter( 'wpseo_next_rel_link', '__return_false' );
add_filter( 'wpseo_prev_rel_link', '__return_false' );

/**
 * Видаляємо WLW Manifest-посилання
 * 
 * https://wpplus.ru/delete-manifest-link/
 * 
 */
remove_action( 'wp_head', 'wlwmanifest_link' );

/**
 * Видаляємо RSD-посилання
 * 
 * https://wpplus.ru/delete-rsd-link/
 * 
 */
remove_action( 'wp_head', 'rsd_link' );

/**
 * Видалити посилання dns-prefetch
 * 
 * https://wpplus.ru/disable-dns-prefetch/
 * 
 */
remove_action( 'wp_head', 'wp_resource_hints', 2 );

/**
 * Видалити jquery-migrate.min.js
 * 
 * https://www.infophilic.com/remove-jquery-migrate-wordpress/
 * 
 * !!! Бачу, що Elementor насильно у своєму коді (/elementor/includes/preview.php) під'єднує цю бібліотеку.
 * 
 */
if ( ! function_exists( 'mptheme_remove_jquery_migrate' ) ) {
	add_action( 'wp_default_scripts', 'mptheme_remove_jquery_migrate' );
	function mptheme_remove_jquery_migrate( $scripts ) {
		if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
			$script = $scripts->registered['jquery'];
			if ( $script->deps ) {
					$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
			}
		}
	}
}
if ( ! function_exists( 'mptheme_remove_jquery_migrate_2' ) ) {
	add_action( 'wp_print_scripts', 'mptheme_remove_jquery_migrate_2', 100 );
	function mptheme_remove_jquery_migrate_2() {
		wp_dequeue_script( 'jquery-migrate' );
	}
}

/**
 * Видалити meta name generator та приховати версію WordPress
 * 
 * https://wpplus.ru/remove-generator/
 * 
 */
if ( ! function_exists( 'mptheme_rem_wp_ver_css_js' ) ) {
	add_filter( 'style_loader_src', 'mptheme_rem_wp_ver_css_js', 9999 );
	add_filter( 'script_loader_src', 'mptheme_rem_wp_ver_css_js', 9999 );
	function mptheme_rem_wp_ver_css_js( $src ) {
		if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
		return $src;
	}
}
add_filter( 'the_generator', '__return_empty_string' );
remove_action( 'wp_head', 'wp_generator' );

/**
 * Вимкнення посилання у <head>
 * 
 * <link rel='https://api.w.org/' href='http://x-files.loc/wp-json/' />
 * 
 */
remove_action( 'wp_head', 'rest_output_link_wp_head', 10, 0 );
remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );

/**
 * Вимкнення посилання на завантаження файлу wp-includes/js/wp-embed.min.js
 */
if ( ! function_exists( 'mptheme_speed_stop_loading_wp_embed' ) ) {
	add_action( 'init', 'mptheme_speed_stop_loading_wp_embed' );
	function mptheme_speed_stop_loading_wp_embed() {
		if ( ! is_admin() ) {
			wp_deregister_script( 'wp-embed' );
		}
	}
}
if ( ! function_exists( 'mptheme_disable_embed' ) ) {
	add_action( 'wp_footer', 'mptheme_disable_embed' );
	function mptheme_disable_embed() {
		wp_dequeue_script( 'wp-embed' );
	}
}
remove_action( 'rest_api_init', 'wp_oembed_register_route' );
remove_filter( 'rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );



/**
 * Вимкнути стандартні віджети (OLD)
 * 
 * https://misha.blog/wordpress/widgets.html
 * 
 */
if ( ! function_exists( 'mptheme_remove_default_widgets' ) ) {
	add_action( 'widgets_init', 'mptheme_remove_default_widgets', 20 );
	function mptheme_remove_default_widgets() {
		unregister_widget( 'WP_Widget_Archives' ); // Архивы
		unregister_widget( 'WP_Widget_Calendar' ); // Календарь
		unregister_widget( 'WP_Widget_RSS' ); // RSS
		unregister_widget( 'WP_Widget_Meta' ); // Мета
		unregister_widget( 'WP_Widget_Media_Audio' ); // Аудио
		unregister_widget( 'WP_Widget_Media_Video' ); // Видео
		unregister_widget( 'WP_Widget_Media_Image' ); // Изображение
		unregister_widget( 'WP_Widget_Media_Gallery' ); // Галерея
		//unregister_widget( 'WP_Widget_Tag_Cloud' ); // Облако меток
		//unregister_widget( 'WP_Widget_Categories' ); // Рубрики
		//unregister_widget( 'WP_Widget_Pages' ); // Страницы
		//unregister_widget( 'WP_Widget_Recent_Comments' ); // Свежие комментарии
		//unregister_widget( 'WP_Widget_Recent_Posts' ); // Свежие записи
		//unregister_widget( 'WP_Widget_Search' ); // Поиск
		//unregister_widget( 'WP_Widget_Text' ); // Текст
		//unregister_widget( 'WP_Nav_Menu_Widget' ); // Произвольное меню
	}
}

/**
 * Видалення сторінок вкладень
 */
// варіант 1 - https://derzky.ru/kak-udalit-stranicy-vlozhenij-v-wordpress/
if ( ! function_exists( 'mptheme_redirect_attachment_page' ) ) {
	//add_action( 'template_redirect', 'mptheme_redirect_attachment_page' );
	function mptheme_redirect_attachment_page() {
		if ( is_attachment() ) {
			global $post;
			if ( $post && $post->post_parent ) {
				wp_redirect( esc_url( get_permalink( $post->post_parent ) ), 301 );
				exit;
			} else {
				wp_redirect( esc_url( home_url( '/' ) ), 301 );
				exit;
			}
		}
	}
}
// варіант 2 - https://www.kobzarev.com/wordpress/disable-image-attachment-pages-in-wordpress/
if ( ! function_exists( 'mptheme_attachment_redirect' ) ) {
	//add_action( 'template_redirect', 'mptheme_attachment_redirect' );
	function mptheme_attachment_redirect() {
		global $post;
		
		if ( is_attachment() && 0 !== $post->post_parent ) {
			wp_redirect( get_permalink( $post->post_parent ), 301 );
			exit();
		}
	}
}

/**
 * Вимкнути Heartbeat
 * 
 * https://wp-kama.ru/id_9759/heartbeat-api.html
 * 
 */
if ( ! function_exists( 'mptheme_stop_heartbeat' ) ) {
	//add_action( 'init', 'mptheme_stop_heartbeat', 1 );
	function mptheme_stop_heartbeat() {
		wp_deregister_script( 'heartbeat' );
	}
}

/**
 * Відключення Dashicons в частині користувача сайту (файл dashicons.min.css)
 */
if ( ! function_exists( 'mptheme_dequeue_dashicon' ) ) {
	//add_action( 'wp_enqueue_scripts', 'mptheme_dequeue_dashicon' );
	function mptheme_dequeue_dashicon() {
		if ( current_user_can( 'update_core' ) ) {
			return;
		}
		wp_deregister_style( 'dashicons' );
	}
}

/**
 * Асинхронне завантаження всіх JS-скриптів (за винятком jquery, який в ядрі), підключених через wp_enqueue_script()
 * 
 * https://workinnet.ru/async-defer-wp/
 * https://www.kobzarev.com/wordpress/defer-async-wordpress-scripts/
 * 
 */
if ( ! function_exists( 'mptheme_add_async_attribute' ) ) {
	add_filter( 'script_loader_tag', 'mptheme_add_async_attribute', 10, 3 );
	function mptheme_add_async_attribute( $tag, $handle ) {
		if( ! is_admin() ) {
			if ( 'jquery-core' == $handle ) {
				return $tag;
			}
			return str_replace( ' src', ' defer src', $tag );
		} else {
			return $tag;
		}
	}
}

/**
 * HTML-минификация
 * https://wordpress.transformnews.com/code-snippets/pagespeed-insights-minify-html-function-for-wordpress-1075
 *
 * !!! Конфликтирует с плагином SyntaxHighlighter Evolved - https://wordpress.org/plugins/syntaxhighlighter/
 * !!! Ошибка, когда открыт админ-бар: SyntaxError: missing } after function body[Подробнее] sitename.com:1:336 note: { opened at line 1, column 13. Поэтому нужно делать проверку: if ( ! is_admin_bar_showing() && ! current_user_can('manage_options') ) {
 */
class WP_HTML_Compression {
    protected $compress_css = true;
    protected $compress_js = true;
    protected $info_comment = true;
    protected $remove_comments = true;

    protected $html;
    public function __construct($html) {
      if (!empty($html)) {
		    $this->parseHTML($html);
	    }
    }
    public function __toString() {
	    return $this->html;
    }
    protected function minifyHTML($html) {
	    $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
	    preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
	    $overriding = false;
	    $raw_tag = false;
	    $html = '';
	    foreach ($matches as $token) {
		    $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;
		    $content = $token[0];
		    if (is_null($tag)) {
			    if ( !empty($token['script']) ) {
				    $strip = $this->compress_js;
			    }
			    else if ( !empty($token['style']) ) {
				    $strip = $this->compress_css;
			    }
			    else if ($content == '<!--wp-html-compression no compression-->') {
				    $overriding = !$overriding;
				    continue;
			    }
			    else if ($this->remove_comments) {
				    if (!$overriding && $raw_tag != 'textarea') {
					    $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
				    }
			    }
		    }
		    else {
			    if ($tag == 'pre' || $tag == 'textarea') {
				    $raw_tag = $tag;
			    }
			    else if ($tag == '/pre' || $tag == '/textarea') {
				    $raw_tag = false;
			    }
			    else {
				    if ($raw_tag || $overriding) {
					    $strip = false;
				    }
				    else {
					    $strip = true;
					    $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
					    $content = str_replace(' />', '/>', $content);
				    }
			    }
		    }
		    if ($strip) {
			    $content = $this->removeWhiteSpace($content);
		    }
		    $html .= $content;
	    }
	    return $html;
    }
    public function parseHTML($html) {
	    $this->html = $this->minifyHTML($html);
    }
    protected function removeWhiteSpace($str) {
	    $str = str_replace("\t", ' ', $str);
	    $str = str_replace("\n",  '', $str);
	    $str = str_replace("\r",  '', $str);
	    while (stristr($str, '  ')) {
		    $str = str_replace('  ', ' ', $str);
	    }
	    return $str;
    }
}
function wp_html_compression_finish($html) {
    return new WP_HTML_Compression($html);
}
function wp_html_compression_start() {
    if ( ! is_admin_bar_showing() && ! current_user_can('manage_options') ) {
		ob_start('wp_html_compression_finish');
	}
}
add_action( 'get_header', 'wp_html_compression_start' );

/**
 * Установка HTTP заголовка Last-Modified
 * Проверка - https://last-modified.com/ru/
 * При активации плагина, у всех незапароленных постов в HTTP-заголовках появится Last-Modified
 * https://sheensay.ru/last-modified-v-wordpress-dobavlenie-i-nastroyka
 * !!! Будет работать только при отсутствии кеширующего плагина на сайте, например, WP Super Cache.
 * Однако, я настоятельно рекомендую использовать последний, так как страничный кеш никогда
 * не помешает любому хорошему проекту. Я рекомендую попробовать настроить WP Super Cache.
 * В этом случае заголовки Last-Modified будут прописываться самим сервером (автоматом), без какого-либо кода.
 *
 * !!! Может не работать, если включена SSI. Это в настройках WWW-домена в панели управления хостинга 
 */
if ( ! function_exists( 'Sheensay_HTTP_Headers_Last_Modified' ) ) {
	//add_action( 'template_redirect', 'Sheensay_HTTP_Headers_Last_Modified' );
	function Sheensay_HTTP_Headers_Last_Modified() {
		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( is_admin() ) ) {
			return;
		}
		$last_modified = '';

		// Для страниц и записей
		if ( is_singular() ) {
			global $post;

			// Если пост запаролен - пропускаем его
			if ( post_password_required( $post ) )
				return;

			if ( !isset( $post -> post_modified_gmt ) ) {
				return;
			}

			$post_time = strtotime( $post -> post_modified_gmt );
			$modified_time = $post_time;

			// Если есть комментарий, обновляем дату
			if ( ( int ) $post -> comment_count > 0 ) {
				$comments = get_comments( array(
					'post_id' => $post -> ID,
					'number' => '1',
					'status' => 'approve',
					'orderby' => 'comment_date_gmt',
						) );
				if ( !empty( $comments ) && isset( $comments[0] ) ) {
					$comment_time = strtotime( $comments[0] -> comment_date_gmt );
					if ( $comment_time > $post_time ) {
						$modified_time = $comment_time;
					}
				}
			}

			$last_modified = str_replace( '+0000', 'GMT', gmdate( 'r', $modified_time ) );
		}

		// Cтраницы архивов: рубрики, метки, даты и тому подобное
		if ( is_archive() || is_home() ) {
			global $posts;

			if ( empty( $posts ) ) {
				return;
			}

			$post = $posts[0];

			if ( !isset( $post -> post_modified_gmt ) ) {
				return;
			}

			$post_time = strtotime( $post -> post_modified_gmt );
			$modified_time = $post_time;

			$last_modified = str_replace( '+0000', 'GMT', gmdate( 'r', $modified_time ) );
		}

		// Если заголовки уже отправлены - ничего не делаем
		if ( headers_sent() ) {
			return;
		}

		if ( !empty( $last_modified ) ) {
			header( 'Last-Modified: ' . $last_modified );

			if ( !is_user_logged_in() ) {
				if ( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) && strtotime( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) >= $modified_time ) {
					$protocol = (isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1');
					header( $protocol . ' 304 Not Modified' );
				}
			}
		}
	}
}

/**
 * Отключаем создание копий картинок, генерируемых самим WordPress
 * 
 * https://www.internet-technologies.ru/articles/kak-otklyuchit-avto-gener-razmery-image-v-wp.html
 */
// отключение генерируемых размеров изображений
if ( ! function_exists( 'mptheme_disable_image_sizes' ) ) {
	add_action( 'intermediate_image_sizes_advanced', 'mptheme_disable_image_sizes' );
	function mptheme_disable_image_sizes( $sizes ) {
		//unset($sizes['thumbnail']);    // отключение миниатюр
		unset($sizes['medium']);       // отключение среднего размера
		unset($sizes['large']);        // отключение большого размера
		unset($sizes['medium_large']); // отключение среднего большого размера
		unset($sizes['1536x1536']);    // отключение 2x среднего большого размера 
		unset($sizes['2048x2048']);    // отключение 2x большого размера	
		return $sizes;
	}
}
// отключение масштабируемого размера изображений
add_filter( 'big_image_size_threshold', '__return_false' );
// отключение других размеров изображений
if ( ! function_exists( 'mptheme_disable_image_sizes' ) ) {
	// add_action( 'init', 'mptheme_disable_image_sizes' );
	function mptheme_disable_image_sizes() {
		remove_image_size( 'post-thumbnail' ); // отключение изображений, добавляемых через set_post_thumbnail_size() 
		remove_image_size( 'another-size' );   // отключение других добавляемых размеров изображений, которые задаются через add_image_size() в темах
	}
}

/**
 * Удаление дублей постов 
 *
 * По ум. каждая запись может быть открыта по адресам:
 * 
 * http://site.com/post_name/1
 * http://site.com/post_name/1/
 * http://site.com/post_name/2
 * http://site.com/post_name/2/
 * ...
 * http://site.com/post_name/nx
 * http://site.com/post_name/nx/ 
 * Это дубли.
 *
 * Код ставит редирект на саму запись.
 *
 * !!! Работает также и для post/page/1, post/page/nx...
 */
if ( ! function_exists( 'mptheme_remove_single_pagination_duplicate' ) ) {
	add_action( 'template_redirect', 'mptheme_remove_single_pagination_duplicate' );
	function mptheme_remove_single_pagination_duplicate() {
		if ( is_singular() && ! is_front_page() ) {
			global $post, $page;
			if ( ! empty( $num_pages ) ) {
				$num_pages = substr_count( $post->post_content, '<!--nextpage-->' ) + 1;
				if ( $page > $num_pages ) {
					wp_redirect( get_permalink( $post->ID ) );
					exit;      
				}
			}
		}
	}
}

/**
 * Исправляем ошибку OpenGraph «префикс article неизвестен валидатору»
 * 
 * https://wpruse.ru/mikrorazmetka/ispravlyaem-oshibku-opengraph-prefiks-article/
 */
if ( ! function_exists( 'mptheme_opengraph_fix' ) ) {
	add_filter( 'language_attributes', 'mptheme_opengraph_fix', 20, 2 );
	function mptheme_opengraph_fix( $output, $doctype ) {
		$lang_prefix = 'prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article#  profile: http://ogp.me/ns/profile# fb: http://ogp.me/ns/fb#"';
		
		return $output . ' ' . $lang_prefix;
	}
}

/**
 * Если подставить в пагинацию нулевой номер страницы, 
 * то открывается страница категории. Таким образом появляется огромное 
 * количество дублей:

 * https://domain.ru/category-name
 * https://domain.ru/category-name/page/0
 * https://domain.ru/category-name/page/00
 * https://domain.ru/category-name/page/000
 * ... и так далее
 *
 * https://arutyunov.me/blog/wordpress/zero-page-wordpress-pagination/
 */
if ( ! function_exists( 'mptheme_redirects_from_zero_number_pages' ) ) {
	add_action( 'template_redirect', 'mptheme_redirects_from_zero_number_pages', 1 );
	function mptheme_redirects_from_zero_number_pages() {
		global $wp_query;

		// Если в параметрах передан номер страницы, и он равен 0,
		// то «насильно» меняем query-параметр пагинации на 1,
		// в таком случае дефолтная Вордпрес-функция `redirect_canonical`
		// подумает, что пользователь открывает /page/1 и перенаправит
		// на страницу категории без page-параметра
		if( isset($wp_query->query['paged']) && intval($wp_query->query['paged']) === 0 ) {
			$wp_query->query_vars['paged'] = 1;
		}
	}
}

/**
 * Відключення та заміна французьких лапок "ялинок" на машинописні подвійні лапки 
 * 
 * https://misha.blog/wordpress/disable-wptexturize.html
 * https://elims.org.ua/blog/wordpress-kak-ubrat-figurnye-kavychki/
 * http://gnatkovsky.com.ua/pravilnye-dvojnye-kavychki-na-wordpress.html
 * https://pwharton.co.uk/blog/how-to-disable-automatic-smart-quotes-for-specific-html-elements/
 */
add_filter( 'run_wptexturize', '__return_false' ); 
remove_filter( 'the_title', 'wptexturize' );
remove_filter( 'the_excerpt', 'wptexturize' );
remove_filter( 'comment_text', 'wptexturize' );
remove_filter( 'list_cats', 'wptexturize' );
remove_filter( 'single_post_title', 'wptexturize' );
remove_filter( 'comment_author', 'wptexturize' );
remove_filter( 'term_name', 'wptexturize' );
remove_filter( 'link_name', 'wptexturize' );
remove_filter( 'link_description', 'wptexturize' );
remove_filter( 'link_notes', 'wptexturize' );
remove_filter( 'bloginfo', 'wptexturize' );
remove_filter( 'wp_title', 'wptexturize' );
remove_filter( 'widget_title', 'wptexturize' );
remove_filter( 'the_content', 'wptexturize' );

/**
 * Відключаємо вбудовані в 5.5 модулі Lazy Load та Sitemap
 */
//if ( $wp_version == '5.5') {
	//disable imbedded Sitemap
	// add_filter( 'wp_sitemaps_enabled', '__return_false' );
	//disable imbedded Lazy Load
	//add_filter( 'wp_lazy_loading_enabled', '__return_false' );
//}

/**
 * Відключаємо примусову перевірку нових версій WP, плагінів та теми в адмінці,
 * щоб вона не гальмувала, коли довго не заходив і зайшов...
 * Усі перевірки відбуватимуться непомітно через крон або під час заходу на сторінку: "Консоль > Оновлення".
 * 
 * https://wp-kama.ru/id_8514/uskoryaem-adminku-wordpress-otklyuchaem-proverki-obnovlenij.html
 * 
 */
if ( is_admin() ) {
	// отключим проверку обновлений при любом заходе в админку...
	remove_action( 'admin_init', '_maybe_update_core' );
	remove_action( 'admin_init', '_maybe_update_plugins' );
	remove_action( 'admin_init', '_maybe_update_themes' );

	// отключим проверку обновлений при заходе на специальную страницу в админке...
	remove_action( 'load-plugins.php', 'wp_update_plugins' );
	remove_action( 'load-themes.php', 'wp_update_themes' );

	// оставим принудительную проверку при заходе на страницу обновлений...
	//remove_action( 'load-update-core.php', 'wp_update_plugins' );
	//remove_action( 'load-update-core.php', 'wp_update_themes' );

	// внутренняя страница админки "Update/Install Plugin" или "Update/Install Theme" - оставим не мешает...
	//remove_action( 'load-update.php', 'wp_update_plugins' );
	//remove_action( 'load-update.php', 'wp_update_themes' );

	// событие крона не трогаем, через него будет проверяться наличие обновлений - тут все отлично!
	//remove_action( 'wp_version_check', 'wp_version_check' );
	//remove_action( 'wp_update_plugins', 'wp_update_plugins' );
	//remove_action( 'wp_update_themes', 'wp_update_themes' );

	/**
	 * отключим проверку необходимости обновить браузер в консоли - мы всегда юзаем топовые браузеры!
	 * эта проверка происходит раз в неделю...
	 * @see https://wp-kama.ru/function/wp_check_browser_version
	 */
	add_filter( 'pre_site_transient_browser_'. md5( $_SERVER['HTTP_USER_AGENT'] ), '__return_empty_array' );
}

/**
 * Видалення файлів license.txt та readme.html для захисту
 * 
 * https://wp-kama.ru/id_7627/adminka-15-hukov-dlya-functions-php.html#avto-udalenie-license.txt-i-readme.html
 * 
 */
if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
	$license_file = ABSPATH .'/license.txt';
	$readme_file = ABSPATH .'/readme.html';

	if ( file_exists( $license_file ) && is_admin() ) {
		$deleted = unlink( $license_file ) && unlink( $readme_file );

		if ( ! $deleted  )
			$GLOBALS['readmedel'] = 'Failed to delete files: license.txt and readme.html from folder `'. ABSPATH .'`. Delete them manually!';
		else
			$GLOBALS['readmedel'] = 'Files: license.txt and readme.html removed from folder `'. ABSPATH .'`.';

		add_action( 'admin_notices', function() { echo '<div class="error is-dismissible"><p>'. $GLOBALS['readmedel'] .'</p></div>'; } );
	}
}

/**
 * Відключимо можливість редагувати файли в адмінці для тем, плагінів
 * 
 * https://wp-kama.ru/id_7584/25-hukov-functions-php-dlya-temy.html#otklyuchim-vozmozhnost-pravit-fajly-v-adminke-dlya-tem-i-plaginov
 * 
 */
define( 'DISALLOW_FILE_EDIT', true );

/**
 * Вирішення помилки PageSpeed:
 *  
 * Пасивні прослуховувачі подій не використовуються для покращення продуктивності під час прокручування 
 *
 * Відповідь - https://overcoder.net/q/1377027/wordpress-%D0%B8-%D0%BB%D1%83%D1%87%D1%88%D0%B0%D1%8F-%D0%BF%D1%80%D0%B0%D0%BA%D1%82%D0%B8%D0%BA%D0%B0-%D1%81-%D0%BF%D0%B0%D1%81%D1%81%D0%B8%D0%B2%D0%BD%D1%8B%D0%BC%D0%B8-%D1%81%D0%BB%D1%83%D1%88%D0%B0%D1%82%D0%B5%D0%BB%D1%8F%D0%BC%D0%B8-%D1%81%D0%BE%D0%B1%D1%8B%D1%82%D0%B8%D0%B9
 *
 */
if ( ! function_exists( 'mptheme_event_listener_options_supported' ) ) {
	add_action( 'wp_footer', 'mptheme_event_listener_options_supported', 100 ); 
	function mptheme_event_listener_options_supported() {
		?>
		<script>
			(function () {
				var supportsPassive = eventListenerOptionsSupported();

				if (supportsPassive) {
					var addEvent = EventTarget.prototype.addEventListener;
					overwriteAddEvent(addEvent);
				}

				function overwriteAddEvent(superMethod) {
					var defaultOptions = {
						passive: true,
						capture: false
					};

					EventTarget.prototype.addEventListener = function (type, listener, options) {
						var usesListenerOptions = typeof options === 'object';
						var useCapture = usesListenerOptions ? options.capture : options;

						options = usesListenerOptions ? options : {};

						if (type == 'touchstart' || type == 'scroll' || type == 'wheel') {
							options.passive = options.passive !== undefined ? options.passive : defaultOptions.passive;
						}
						
						options.capture = useCapture !== undefined ? useCapture : defaultOptions.capture;

						superMethod.call(this, type, listener, options);
					};
				}

				function eventListenerOptionsSupported() {
					var supported = false;
					try {
						var opts = Object.defineProperty({}, 'passive', {
							get: function () {
								supported = true;
							}
						});
						window.addEventListener("test", null, opts);
					} catch (e) { }

					return supported;
				}
			})();
		</script>
		<?php
	}
}

/**
 * Вимикаємо Gutenberg у віджетах
 */
// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' ); 