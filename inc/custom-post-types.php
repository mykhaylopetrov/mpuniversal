<?php
/**
 * CPT Portfolio
 * 
 * https://site.com/portfolio/
 * https://site.com/portfolio/portfolio-work-1/
 * https://site.com/portfolio/portfolio-work-2/
 * ...
 * https://site.com/portfolio/portfolio-work-n/
 */
if ( ! function_exists( 'mpuniversal_cpt_portfolio_register_post_type' ) ) {  
	add_action( 'init', 'mpuniversal_cpt_portfolio_register_post_type' );
	function mpuniversal_cpt_portfolio_register_post_type() {
		/* Register taxonomy */
		$taxonomyLabels = array(
			'name'              => esc_html__( 'Portfolio Categories', 'mpuniversal' ),
			'singular_name'     => esc_html__( 'Portfolio Category', 'mpuniversal' ),
			'search_items'      => esc_html__( 'Search Portfolio Categories', 'mpuniversal' ),
			'all_items'         => esc_html__( 'All Portfolio Categories', 'mpuniversal' ),
			'edit_item'         => esc_html__( 'Edit Portfolio Category', 'mpuniversal' ),
			'update_item'       => esc_html__( 'Update Portfolio Category', 'mpuniversal' ),
			'add_new_item'      => esc_html__( 'Add New Portfolio Category', 'mpuniversal' ),
			'new_item_name'     => esc_html__( 'New Portfolio Category', 'mpuniversal' ),
			'menu_name'         => esc_html__( 'Portfolio Categories', 'mpuniversal' )
		);

		register_taxonomy( 'portfoliocat', 'portfolio', 
			array(
				'hierarchical' 		=> true,
				'labels' 			=> $taxonomyLabels,
				'query_var' 		=> true,
				'show_admin_column' => true,
				'show_in_rest'      => true, // Needed for tax to appear in Gutenberg editor.
			) 
		);

		/* Register custom post type */
	
		$cptLabels = array(
			'name'               => esc_html__( 'Portfolio', 'mpuniversal' ),
			'singular_name'      => esc_html__( 'Portfolio', 'mpuniversal' ),
			'add_new'            => esc_html__( 'Add New Portfolio Work', 'mpuniversal' ),
			'add_new_item'       => esc_html__( 'Add New Portfolio Work', 'mpuniversal' ),
			'edit_item'          => esc_html__( 'Edit Portfolio Work', 'mpuniversal' ),
			'new_item'           => esc_html__( 'New Portfolio Work', 'mpuniversal' ),
			'all_items'          => esc_html__( 'All Portfolio Works', 'mpuniversal' ),
			'view_item'          => esc_html__( 'View Portfolio Work', 'mpuniversal' ),
			'search_items'       => esc_html__( 'Search Portfolio Works', 'mpuniversal' ),
			'not_found'          => esc_html__( 'No Portfolio Works Found', 'mpuniversal' ),
			'not_found_in_trash' => esc_html__( 'No Portfolio Works found in Trash', 'mpuniversal' ), 
			'parent_item_colon'  => esc_html__( 'Parent Portfolio:', 'mpuniversal' ),
			'menu_name'          => esc_html__( 'Portfolio', 'mpuniversal' ),
		);

		register_post_type( 'portfolio', 
			array(
				'labels'              => $cptLabels,
				'public'              => true,
				'supports'            => array( 'title', 'editor', 'excerpt', 'comments', 'custom-fields', 'thumbnail', 'page-attributes' ),
				// 'taxonomies'          => array( 'post_tag', 'category' ), // Стандартні таксономії Тег і Категорія
				'taxonomies'          => array( 'portfoliocat' ),	
				'exclude_from_search' => false,
				'capability_type'     => 'post',
				'menu_icon'           => 'dashicons-portfolio',
				'rewrite'             => array( 'slug' => 'portfolio' ),
				'has_archive'         => true,
				'show_in_rest' 		  => true, // Gutenberg support
			)
		);
	}
}

/**
  * Шукаємо темплейти нашого CPT "portfolio"
  * Шукаємо архівні файли-темплейти наших CPT і одиничні пости в каталозі теми
  */
if ( ! function_exists( 'mpuniversal_cpt_portfolio_include_templates' ) ) {  
	add_filter( 'template_include', 'mpuniversal_cpt_portfolio_include_templates' );
	function mpuniversal_cpt_portfolio_include_templates( $template ) {
		// шукаємо спочатку архівний темплейт для CPT "portfolio" в каталозі теми
		if ( is_post_type_archive( 'portfolio' ) ) {
			// або в кореневій папці теми, або у підпапках теми
			$theme_files = array( 'archive-portfolio.php', 'templates/archive-portfolio.php', 'tpl/archive-portfolio.php' );
			$exist = locate_template( $theme_files, false );
	
			if ( $exist != '' ) {
				// якщо знайшли файл-шаблон, повертаємо його назву
				return $exist;
			} else {
				// якщо файлів-шаблонів у темі не створено, застосовуємо темплейт архіву теми за замовчуванням
				return MPUNIVERSAL_PATH . '/archive.php';
			}    
		} elseif (  is_singular( 'portfolio' ) ) { 
			// точно якщо знайдено single-portfolio.php для CPT "portfolio"
			// або в кореневій папці теми, або у підпапці теми
			$theme_files = array( 'single-portfolio.php', 'templates/single-portfolio.php', 'tpl/single-portfolio.php' );
			$exist = locate_template( $theme_files, false );
	
			if ( $exist != '' ) {
				// якщо знайшли файл-шаблон, повретаємо його назву
				return $exist;
			} else {
				// якщо файлів-шаблонів у темі не створено, застосовуємо темплейт архіву теми за замовчуванням
				return MPUNIVERSAL_PATH . '/single.php';
			}
		}
	
		return $template;
	}
}

/**
 * Allow comments in CPT "Portfolio"
 * https://wordpress.stackexchange.com/questions/296103/automatically-check-allow-comments-for-custom-post-type
 */
if ( ! function_exists( 'mpuniversal_cpt_portfolio_allow_comments' ) ) {
	add_filter( 'comments_open', 'mpuniversal_cpt_portfolio_allow_comments', 10, 2 );
	function mpuniversal_cpt_portfolio_allow_comments( $open, $post_id ) {
		if ( get_post_type( $post_id ) === 'portfolio' ) {
			$open = true;
		}

		return $open;
	}
}

/**
 * Add CSS classes to Portfolio Archive Page & Portfolio Single Post
 */
if ( ! function_exists( 'mpuniversal_cpt_portfolio_add_css_classes' ) ) {
	add_filter( 'body_class', 'mpuniversal_cpt_portfolio_add_css_classes' );
	function mpuniversal_cpt_portfolio_add_css_classes( $classes ) {
		if ( is_post_type_archive( 'portfolio' ) ) {
			$classes[] = 'portfolio-archive';
		}

		if ( is_singular( 'portfolio' ) ) {
            $classes[] = 'portfolio-single';
        }

		return $classes;
	}
}


