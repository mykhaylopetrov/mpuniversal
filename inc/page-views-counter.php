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
    // if ( is_single() ) {
        if ( isset( $_POST['post_id'] ) ) {
            $post_id = intval( $_POST['post_id'] );
            $current_views = get_post_meta( $post_id, 'page_views', true );
            
            if ( ! $current_views || empty( $current_views ) || ! is_numeric( $current_views ) ) {
                $current_views = (int)0;
                // update_post_meta( $post_id, 'page_views', 0 );
            }

            $new_views = intval( $current_views ) + 1;
            
            // if ( is_single() ) {
                update_post_meta( $post_id, 'page_views', $new_views );
            // }

            echo $new_views;
            die(); // Зупиняє подальше виконання PHP-коду
        }
    // }
    
    wp_die();
}

//Вывод в админке к-ва записей
function getPostViews( $postID, $is_single = true ) {
	// global $post;
	if ( ! $postID ) { 
        $postID = get_the_ID();
    }
	$count_key = 'page_views';
	$count = get_post_meta( $postID, $count_key, true );
	
    return $count;
}

function posts_column_views( $defaults ) {
	$defaults['page_views'] = __( 'Views' , '' );
	return $defaults;
}
function posts_custom_column_views( $column_name, $id ) {
	if( $column_name === 'page_views' ) {
		echo getPostViews( get_the_ID(), false );
	}
}
add_filter( 'manage_posts_columns', 'posts_column_views' );
add_action( 'manage_posts_custom_column', 'posts_custom_column_views', 5, 2 );

/**
 * AJAX Post Views Counter Shortcode
 */
if ( ! function_exists( 'mpuniversal_ajax_post_views_counter_shortcode' ) ) {
	add_shortcode( 'mpuniversal_ajax_post_views_counter_shortcode', 'mpuniversal_ajax_post_views_counter_shortcode' );
	function mpuniversal_ajax_post_views_counter_shortcode() {
		ob_start();
        // if ( is_singular() ) {
		?>
            <!-- Post View Counter -->
            <input type="hidden" id="post_id" value="<?php echo get_the_ID(); ?>">
            <div class="page_views">
                <div class="page_views_icon">
                    <!-- https://remixicon.com/ -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.0003 3C17.3924 3 21.8784 6.87976 22.8189 12C21.8784 17.1202 17.3924 21 12.0003 21C6.60812 21 2.12215 17.1202 1.18164 12C2.12215 6.87976 6.60812 3 12.0003 3ZM12.0003 19C16.2359 19 19.8603 16.052 20.7777 12C19.8603 7.94803 16.2359 5 12.0003 5C7.7646 5 4.14022 7.94803 3.22278 12C4.14022 16.052 7.7646 19 12.0003 19ZM12.0003 16.5C9.51498 16.5 7.50026 14.4853 7.50026 12C7.50026 9.51472 9.51498 7.5 12.0003 7.5C14.4855 7.5 16.5003 9.51472 16.5003 12C16.5003 14.4853 14.4855 16.5 12.0003 16.5ZM12.0003 14.5C13.381 14.5 14.5003 13.3807 14.5003 12C14.5003 10.6193 13.381 9.5 12.0003 9.5C10.6196 9.5 9.50026 10.6193 9.50026 12C9.50026 13.3807 10.6196 14.5 12.0003 14.5Z"></path></svg>
                </div>
                <div id="page_views_count" class="page_views_count">
                    <?php 
                    $post_id = get_the_ID();
                    $page_views = get_post_meta( $post_id, 'page_views', true );
                    echo $page_views; 
                    ?>
                </div>
            </div>
            <!-- ./Post View Counter -->
		<?php
        // }
		return ob_get_clean();
	}
}