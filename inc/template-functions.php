<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 */

/**
 * Adds custom classes to the array of body classes.
 */
if ( ! function_exists( 'mpuniversal_add_custom_body_classes' ) ) {
	add_filter( 'body_class', 'mpuniversal_add_custom_body_classes' );
	function mpuniversal_add_custom_body_classes( $classes ) {
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Adds a class of no-sidebar when there is no sidebar present.
		if ( ! is_active_sidebar( 'sidebar-1' ) ) {
			$classes[] = 'no-sidebar';
		}

		return $classes;
	}
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
if ( ! function_exists( 'mpuniversal_pingback_header' ) ) {
	add_action( 'wp_head', 'mpuniversal_pingback_header' );
	function mpuniversal_pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}
}

/**
 * Pagination form search results, archive pages
 */
if ( ! function_exists( 'mpuniversal_pagination' ) ) {
	function mpuniversal_pagination() {
		global $wp_query;

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		if ( $wp_query->max_num_pages > 1 ) { ?>
			<nav class="posts-navigation">
				<?php
					echo paginate_links( 
						array(
							'total' => $wp_query->max_num_pages,
							'current' => $paged,
							'prev_text' => esc_html__( 'Prev', MPUNIVERSAL_TEXT_DOMAIN ),
							'next_text' => esc_html__( 'Next', MPUNIVERSAL_TEXT_DOMAIN ),
						) 
					);
				?>
			</nav>
		<?php 
		}
	}
}

/**
 * Always show Next/Previous buttons in pagination
 * 
 * https://stackoverflow.com/questions/51495432/always-show-next-previous-using-the-post-pagination-in-wordpress
 * 
 */
if ( ! function_exists( 'mpuniversal_always_show_next_previous_buttons_pagination' ) ) {
	add_filter( 'paginate_links_output', 'mpuniversal_always_show_next_previous_buttons_pagination', 10, 2 );
	function mpuniversal_always_show_next_previous_buttons_pagination( $output, $args ) {
		if ( $args['current'] == 1 ) {
			$prev = '<span class="prev page-numbers inactive">'.$args['prev_text'].'</span>';
			$output = $prev . "\n" . $output;
		}

		if ( $args['total'] == $args['current'] ) {
			$next = '<span class="next page-numbers inactive">'.$args['next_text'].'</span>';
			$output .= "\n" . $next;
		}

		return $output;
	}
}

/**
 * Get the current Page ID
 */
if ( ! function_exists( 'mpuniversal_get_the_current_page_id' ) ) {
    function mpuniversal_get_the_current_page_id() {
        return get_the_ID();
    }
}

/**
 * Get the Page Slug by ID
 * 
 * На сторінці 404 результат роботи get_post(lc_get_the_current_page_id())->post_name видає порожнє значення. 
 * Тому роблю перевірку чи існує це значення. 
 * Пояснення помилки - https://wordpress.stackexchange.com/questions/220567/only-on-404-page-get-notice-trying-to-get-property-of-non-object-on-nonce
 * 
 */
if ( ! function_exists( 'mpuniversal_get_the_page_slug_by_id' ) ) {
    function mpuniversal_get_the_page_slug_by_id() {
        if ( isset( get_post( mpuniversal_get_the_current_page_id() )->post_name ) ) {
            return get_post( mpuniversal_get_the_current_page_id() )->post_name;
        }
    }
}

/**
 * Include custom template to Sample Page (created in admin-menu Pages)
 */
if ( ! function_exists( 'mpuniversal_templates_include' ) ) {
    // add_filter( 'template_include', 'mpuniversal_templates_include' ); 
    function mpuniversal_templates_include( $template ) {    
        if ( is_page( 'home' ) ) {
            return wp_normalize_path( MPUNIVERSAL_PATH ) . '/templates/template-home.php';
        }    
        
        return $template;
    };
}

/**
 * Substitute the appropriate template by the Page Slug
 */
// add_filter( 'template_include', function( $template ) {    
// 	switch ( mpuniversal_get_the_page_slug_by_id() ) {
//         case 'mypage':
//             return wp_normalize_path( get_stylesheet_directory() ) . '/templates/template-mypage.php';
//             break;
// 	}
	
// 	return $template;
// } );

/**
 * Add custom classes in <body> tag for our pages 
 */
// add_filter( 'body_class', function( $classes ) {  
//     switch ( mpuniversal_get_the_page_slug_by_id() ) {
//         case 'mypage':
//             $classes[] = 'mypage-page';
//             break;       
// 	}
    
//     return $classes;
// } );

/** 
 * Transliteration
 * 
 * echo mpuniversal_get_translit( 'Польща' ); // polshha
 * 
 */
if ( ! function_exists( 'mpuniversal_get_translit' ) ) {
	function mpuniversal_get_translit( $text ) {
		$translitArray = array(
			"Є"=>"EH","є"=>"eh","І"=>"I","і"=>"i","Ї"=>"i","ї"=>"i",
			"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
			"Е"=>"E","Ё"=>"JO","Ж"=>"ZH",
			"З"=>"Z","И"=>"I","Й"=>"JJ","К"=>"K","Л"=>"L",
			"М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
			"С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"KH",
			"Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
			"Ы"=>"Y","Ь"=>"","Э"=>"EH","Ю"=>"YU","Я"=>"YA",
			"а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
			"е"=>"e","ё"=>"jo","ж"=>"zh",
			"з"=>"z","и"=>"i","й"=>"jj","к"=>"k","л"=>"l",
			"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
			"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"kh",
			"ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
			"ы"=>"y","ь"=>"","э"=>"eh","ю"=>"yu","я"=>"ya",
			"—"=>"-","«"=>"","»"=>"","…"=>"","№"=>"#"
		);
		if ( ! empty( $text ) ) {
			return strtolower( strtr( $text, $translitArray ) );
		}
	}
}

/**
 * Визначимо, який плагін багатомовності активований на сайті: Polylang або WPML
 */
if ( ! function_exists( 'mpuniversal_get_active_multilingual_plugin' ) ) { 
	function mpuniversal_get_active_multilingual_plugin() {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
		if ( is_plugin_active( 'polylang/polylang.php' ) ) {
			return 'polylang';
		} elseif ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			return 'wpml';
		}
	}
}
if ( ! function_exists( 'mpuniversal_get_page_locale_polylang' ) ) {
	function mpuniversal_get_page_locale_polylang() {
		/*
		 * Тут є проблема. Якщо у WPML при зміні мови в адмінці через перемикач самого плагіну в адмін-барі працює константа ICL_LANGUAGE_CODE, то Polylang завжди показує локаль, задану в Налаштування > Загальне. Але нам потрібно бачити локаль, коли ми перемикаємо мову в адмінці (наприклад, у нашому випадку щоб задати різний контент для різних мов сайту). Тому беремо локаль із адресного рядка. Якщо там його нема, то ставимо локаль за замовчуванням (у Налаштування > Загальне). 
		 */
	
		// Визначаємо локаль в адмінці
		if ( is_admin() && current_user_can( 'manage_options' ) && function_exists( 'pll_current_language' ) ) {
			
			// Даний спосіб працює некоректно, бо при переключенні посту на відповідну мову тут значення локалі повертається за замовчуванням.

			// if ( $_REQUEST['lang'] == 'uk' ) { // $_GET['lang']
			// 	return '_uk';
			// } elseif ( $_REQUEST['lang'] == 'en' ) { // $_GET['lang']
			// 	return '_en';
			// } else {
			// 	return '_' . get_bloginfo( 'language' );
			// }

            // АБО
            
			/**
			 * Якщо в адмін-частині (Polylang) у виборі мов вказано "Показати всі мови", то функція pll_current_language() поверне порожній результат. І опції можна задавати, але ні для якої мови вони працювати не будуть. Тому роблю перевірку: якщо вона порожня, задаю їй значення - локаль адмінки за замовчуванням.   
			 */	
			if ( function_exists( 'pll_current_language' ) ) {
				if ( pll_current_language() == '' ) {
					return '_' . get_bloginfo( 'language' );
				} else {
					// Цей спосіб працює ідеально
					return '_' . pll_current_language();
				}
			}
			
		} else { // Визначаємо локаль у користувацькій частині сайту 
			if ( get_locale() === 'uk' ) {
				return '_uk';
			} elseif ( get_locale() === 'en_US' || get_locale() === 'en-US' ) {
				return '_en';
			} elseif ( get_locale() === 'de-DE' || get_locale() === 'de_DE' ) {
				return '_de';
			} elseif ( get_locale() === 'sk-SK' || get_locale() === 'sk_SK' ) {
				return '_sk';
			} elseif ( get_locale() === 'ru-RU' || get_locale() === 'ru_RU' ) {
				return '_ru';
			}
		}
	}
}
if ( ! function_exists( 'mpuniversal_get_page_locale_wpml' ) ) { 
	function mpuniversal_get_page_locale_wpml() {
		$prefix = '';

		if ( ! defined( 'ICL_LANGUAGE_CODE' ) ) {
			return $prefix;
		}
		
		$prefix = '_' . ICL_LANGUAGE_CODE;
		return $prefix;
	}
}
/**
 * Результат роботи функції:
 * 
 * uk
 * ru
 * en
 */
if ( ! function_exists( 'mpuniversal_get_page_locale' ) ) { 
	function mpuniversal_get_page_locale() {
		if ( mpuniversal_get_active_multilingual_plugin() == 'polylang' ) {
			return mpuniversal_get_page_locale_polylang();
		} elseif ( mpuniversal_get_active_multilingual_plugin() == 'wpml' ) {
			return mpuniversal_get_page_locale_wpml();
		} else {
			// Якщо не встановлений плагін багатомовності
			if ( get_locale() === 'uk' ) {
				return '_uk';
			} elseif ( get_locale() === 'en_US' || get_locale() === 'en-US' ) {
				return '_en';
			} elseif ( get_locale() === 'de-DE' || get_locale() === 'de_DE' ) {
				return '_de';
			} elseif ( get_locale() === 'sk-SK' || get_locale() === 'sk_SK' ) {
				return '_sk';
			} elseif ( get_locale() === 'ru-RU' || get_locale() === 'ru_RU' ) {
				return '_ru';
			}
		}
	}
}

/**
 * Set Language Attributes
 */
if ( ! function_exists( 'mpuniversal_set_language_attributes' ) ) {
	// add_filter( 'language_attributes', 'mpuniversal_set_language_attributes' );
	function mpuniversal_set_language_attributes( $output ) {
		if ( ! is_admin() ) {
			if ( get_locale() === 'en_US' || get_locale() === 'en-US' ) {
				return 'lang="en"';
			} elseif ( get_locale() === 'ru-RU' || get_locale() === 'ru_RU' ) {
				return 'lang="ru"';
			} else {
				return $output;
			}
		}
	}
}


/**
 * Enable SVG Support
 */
if ( ! function_exists( 'mpuniversal_svg_upload_allow' ) ) { 
	add_filter( 'upload_mimes', 'mpuniversal_svg_upload_allow' );
	function mpuniversal_svg_upload_allow( $mimes ) {
		$mimes['svg']  = 'image/svg+xml';
		return $mimes;
	}
}

if ( ! function_exists( 'mpuniversal_fix_svg_mime_type' ) ) {
	add_filter( 'wp_check_filetype_and_ext', 'mpuniversal_fix_svg_mime_type', 10, 5 );
	function mpuniversal_fix_svg_mime_type( $data, $file, $filename, $mimes, $real_mime = '' ) {
		if ( version_compare( $GLOBALS['wp_version'], '5.1.0', '>=' ) )
			$dosvg = in_array( $real_mime, [ 'image/svg', 'image/svg+xml' ] );
		else
			$dosvg = ( '.svg' === strtolower( substr( $filename, -4 ) ) );

		if ( $dosvg ) {
			if ( current_user_can( 'manage_options' ) ) {
				$data['ext']  = 'svg';
				$data['type'] = 'image/svg+xml';
			}
			// заборонимо
			else {
				$data['ext'] = $type_and_ext['type'] = false;
			}
		}

		return $data;
	}
}

if ( ! function_exists( 'mpuniversal_show_svg_in_media_library' ) ) {
	add_filter( 'wp_prepare_attachment_for_js', 'mpuniversal_show_svg_in_media_library' );
	function mpuniversal_show_svg_in_media_library( $response ) {
		if ( $response['mime'] === 'image/svg+xml' ) {
			$response['image'] = [
				'src' => $response['url'],
			];
		}

		return $response;
	}
}

/**
 * Забираємо порожнє посилання в меню з адресою #
 * 
 * https://qastack.ru/wordpress/30303/how-make-top-level-menu-item-not-have-link-but-have-sub-menus-that-are-linked
 * 
 */
if ( ! function_exists( 'mpuniversal_remove_empty_menu_link' ) ) {
	add_filter( 'wp_nav_menu_items', 'mpuniversal_remove_empty_menu_link' );
	function mpuniversal_remove_empty_menu_link( $menu ) {
		return str_replace( '<a href="#"', '<a', $menu );
	}
}

/**
 * Add custom CSS class for parent menu items [<li class="menu__item">]
 * 
 * https://wp-kama.ru/function/wp_nav_menu#example_35776
 * 
 */
if ( ! function_exists( 'mpuniversal_add_custom_class_for_parent_menu_items' ) ) {
	add_filter( 'nav_menu_css_class', 'mpuniversal_add_custom_class_for_parent_menu_items', 10, 3 );
	function mpuniversal_add_custom_class_for_parent_menu_items( $classes, $item, $args ) {
		if ( $args->theme_location == 'header-menu' ) {
			$classes[] = "menu__item";
		}

		return $classes;
	}
}

/**
 * Add custom CSS class for <a> header menu items [<a href="#" class="menu__link">]
 * 
 * https://stackoverflow.com/questions/20752318/wordpress-add-a-class-to-menu-link
 * 
 */
if ( ! function_exists( 'mpuniversal_add_custom_class_for_a_menu_items' ) ) {
	add_filter( 'nav_menu_link_attributes', 'mpuniversal_add_custom_class_for_a_menu_items', 10, 3 );
	function mpuniversal_add_custom_class_for_a_menu_items( $atts, $item, $args ) {
		// check item is in the menu location
		if ( $args->theme_location == 'header-menu' ) {
		// add the desired attributes:
		$atts['class'] = 'menu__link';
		}
		return $atts;
	}
}

/**
 * Add to header menu custom items
 */
if ( ! function_exists( 'mpuniversal_add_header_menu_custom_items' ) ) {
	// add_filter( 'wp_nav_menu_items', 'mpuniversal_add_header_menu_custom_items', 10, 2 );
	function mpuniversal_add_header_menu_custom_items( $items, $args ) {
		if ( $args->theme_location == 'header-menu' ) {
			$langSwitcher = do_shortcode( '[name_shortcode]' );
			$items .= $langSwitcher;
		}
		return $items;
	}
}

/**
 * Add Post link in the Read more
 */
if ( ! function_exists( 'mpuniversal_new_excerpt_more' ) ) {
	add_filter( 'excerpt_more', 'mpuniversal_new_excerpt_more' );
	function mpuniversal_new_excerpt_more( $more ) {
		global $post;
		return '<p><a href="'. get_permalink( $post ) . '" class="read-more">'. esc_html__( 'Read more...', 'mpuniversal' ) .'</a></p>';
	}
}

/**
 * Get current year
 */
if ( ! function_exists( 'mpuniversal_get_current_year' ) ) {
	function mpuniversal_get_current_year() {
		return date( 'Y' );
	}
}

/**
 * Get current year shortcode
 */
if ( ! function_exists( 'mpuniversal_get_current_year_shortcode' ) ) {
	add_shortcode( 'mpuniversal_get_current_year_shortcode', 'mpuniversal_get_current_year_shortcode' );
	function mpuniversal_get_current_year_shortcode() {
		ob_start();

		echo mpuniversal_get_current_year();

		return ob_get_clean();
	}
}

/**
 * Change standard WordPress comment form Comment field
 * 
 * https://wp-kama.ru/function/comment_form
 * 
 */
if ( ! function_exists( 'mpuniversal_change_standard_wp_comment_form_comment_field' ) ) {
	function mpuniversal_change_standard_wp_comment_form_comment_field() {
		$commentsArgs = array(
			'comment_field' => '
				<p class="comment-form-comment">
					<label for="comment"></label>
					<textarea id="comment" name="comment" aria-required="true" placeholder="' . esc_html__( 'Your comment...', 'mpuniversal' ) . ' *"></textarea>
				</p>',
		);

		return $commentsArgs;
	}
}

/**
 * Change standard WordPress comment form fields
 * 
 * https://misha.agency/wordpress/comment_form.html
 * 
 */
if ( ! function_exists( 'mpuniversal_change_standard_wp_comment_form_fields' ) ) {
	add_filter( 'comment_form_default_fields', 'mpuniversal_change_standard_wp_comment_form_fields', 25 );
	function mpuniversal_change_standard_wp_comment_form_fields( $fields ) {
		$fields['author'] = 
			'<p class="comment-form-author">
				<label for="author"></label> 
				<input id="author" name="author" type="text" autocomplete="name" required="" placeholder="' . esc_html__( 'Your Name...', 'mpuniversal' ) . ' *">
			</p>';
		$fields['email'] = 
			'<p class="comment-form-email">
				<label for="email"></label>
				<input id="email" name="email" type="email" aria-describedby="email-notes" autocomplete="email" required="" placeholder="' . esc_html__( 'Your Email...', 'mpuniversal' ) . ' *">
			</p>';
		$fields['url'] = 
			'<p class="comment-form-url">
				<label for="url"></label>
				<input id="url" name="url" type="url" autocomplete="url" placeholder="' . esc_html__( 'Your WebSite...', 'mpuniversal' ) . '">
			</p>';
		// Save my name, email, and website in this browser for the next time I comment.
		$fields['cookies'] = 
			'<p class="comment-form-cookies-consent">
				<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes">
				<label for="wp-comment-cookies-consent">' . esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'mpuniversal' ) . '</label>
			</p>';

		return $fields;
	}
}

/**
 * Reorder standard WordPress comment form fields
 * 
 * https://wp-kama.ru/function/comment_form
 * 
 */
if ( ! function_exists( 'mpuniversal_reorder_standard_wp_comment_form_fields' ) ) {
	add_filter( 'comment_form_fields', 'mpuniversal_reorder_standard_wp_comment_form_fields' );
	function mpuniversal_reorder_standard_wp_comment_form_fields( $fields ) {
		$newFields = array();
		
		// потрібний порядок
		$newOrder = array(
			'author',
			'email',
			'url',
			'comment',
			'cookies'
		);

		foreach( $newOrder as $key ) {
			$newFields[ $key ] = $fields[ $key ];
			unset( $fields[ $key ] );
		}

		// якщо залишилися ще якісь поля, додамо їх у кінець
		if( $fields )
			foreach( $fields as $key => $val )
				$newFields[ $key ] = $val;

		return $newFields;
	}
}

/**
 * Change standard WordPress comment form button text
 * 
 * https://wp-kama.ru/function/comment_form
 * 
 */
if ( ! function_exists( 'mpuniversal_change_standard_wp_comment_form_button_text' ) ) {
	add_filter( 'comment_form_defaults', 'mpuniversal_change_standard_wp_comment_form_button_text', 25 );
	function mpuniversal_change_standard_wp_comment_form_button_text( $args ) {
		$args['label_submit'] = esc_html__( 'Send comment', 'mpuniversal' );
		// Form title
		$args['title_reply'] = esc_html__( 'Leave a comment', 'mpuniversal' );
		// "Your email address will not be published. Required fields are marked *" text
		$args['comment_notes_before'] = '';
		
		return $args;
	}
}

/**
 * Disable comments completely
 * 
 * Activation:
 * 
 * add to functions.php:
 * 
 * mpuniversal_disable_comments_completely();
 * 
 */
if ( ! function_exists( 'mpuniversal_disable_comments_completely' ) ) {
	function mpuniversal_disable_comments_completely() {
		add_action( 'admin_init', function() {
			// Редирект будь-якого користувача, який намагається отримати доступ до сторінки коментарів
			global $pagenow;
			 
			if ( $pagenow === 'edit-comments.php' ) {
				wp_safe_redirect( admin_url() );
				exit;
			}
		 
			// Видалити метабокс коментарів з інформаційної панелі
			remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
		 
			// Вимкнути підтримку коментарів і трекбеків у типах записів
			foreach( get_post_types() as $post_type ) {
				if ( post_type_supports( $post_type, 'comments' ) ) {
					remove_post_type_support( $post_type, 'comments' );
					remove_post_type_support( $post_type, 'trackbacks' );
				}
			}
		} );
		 
		// Вимкнути коментарі у користувацькій частині сайту
		add_filter( 'comments_open', '__return_false', 20, 2 );
		add_filter( 'pings_open', '__return_false', 20, 2 );
		 
		// Приховати існуючі коментарі
		add_filter( 'comments_array', '__return_empty_array', 10, 2 );
		 
		// Сховати сторінку коментарів у головному адмін-меню
		add_action( 'admin_menu', function() {
			remove_menu_page( 'edit-comments.php' );
		} );
		 
		// Сховати посилання в адмін-барі
		add_action( 'init', function() {
			if ( is_admin_bar_showing() ) {
				remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
			}
		} );
	}
}

/**
 * Add excerpt ellipsis ("...")
 */
if ( ! function_exists( 'mpuniversal_add_excerpt_ellipsis' ) ) {
add_filter( 'excerpt_more', 'mpuniversal_add_excerpt_ellipsis' );
	function mpuniversal_add_excerpt_ellipsis ( $more ) {
		return '...';
	}
}