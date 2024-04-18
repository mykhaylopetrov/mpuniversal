<?php

/**
 * Live Search with AJAX
 * 
 * https://only-to-top.ru/blog/programming/2020-12-13-ajax-search-wordpress.html
 * 
 */
if ( ! function_exists( 'mptheme_ajax_search' ) ) {
    add_action( 'wp_ajax_nopriv_ajax_search', 'mptheme_ajax_search' );
    add_action( 'wp_ajax_ajax_search', 'mptheme_ajax_search' );
    function mptheme_ajax_search() {
        $args = array(
            // поиск по типам записей: any (по всем), post, page, 
            // page + кастомный тип записи - "post_type" => array( "page", "production" ),
            "post_type"      => "any", 
            "post_status"    => "publish",
            "order"          => "DESC",
            "orderby"        => "date",
            "s"              => $_POST["term"],
            "posts_per_page" => -1
        );
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) : $query->the_post();
                ?>
                <li class="ajax-search__item">
                    <a href="<?php the_permalink(); ?>" class="ajax-search__link"><?php the_title(); ?></a>
                    <div class="ajax-search__excerpt"><?php the_excerpt(); ?></div>
                </li>
                <?php
            endwhile;
        } else {
            esc_html_e( 'Nothing found', MPTHEME_TEXT_DOMAIN );
        }
        exit;
    }
}