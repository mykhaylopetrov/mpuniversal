<?php
/**
 * https://chat.openai.com/c/6e1e9c8f-6d72-4658-a407-5d838dae299f
 * 
 * Clear meta info in BD:
 * 
 * https://petrov.net.ua/cleaning-wordpress-database/#vidaliti-vsi-metapolya-za-vkazanim-klyuchem
 * 
 */
add_action( 'wp_ajax_increase_page_views', 'increase_page_views' );
add_action( 'wp_ajax_nopriv_increase_page_views', 'increase_page_views' );
function increase_page_views() {
    if( isset( $_POST['post_id'] ) ) {
        $post_id = intval( $_POST['post_id'] );
        $current_views = get_post_meta( $post_id, 'page_views', true );
        
        if ( ! $current_views || ! is_numeric( $current_views ) ) {
            $current_views = 0;
        }

        $new_views = intval( $current_views ) + 1;
        update_post_meta( $post_id, 'page_views', $new_views );
        echo $new_views;
        die(); // Зупиняє подальше виконання PHP-коду
    }
    
    wp_die();
}
