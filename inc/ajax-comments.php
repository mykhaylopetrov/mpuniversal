<?php
/**
 * AJAX Comments
 * 
 * https://misha.agency/course/ajax-comments
 * 
 */
if ( ! function_exists( 'mpuniversal_ajax_comments' ) ) {
	add_action( 'wp_ajax_sendcomment', 'mpuniversal_ajax_comments' );
	add_action( 'wp_ajax_nopriv_sendcomment', 'mpuniversal_ajax_comments' );
	function mpuniversal_ajax_comments() {
		// код із файлу wp-comments-post.php
		$comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
		if ( is_wp_error( $comment ) ) {
			$data = (int) $comment->get_error_data();
			if ( ! empty( $data ) ) {
				wp_die(
					'<p class="comment-error">' . $comment->get_error_message() . '</p>',
					esc_html__( 'Comment Submission Failure', 'mpuniversal' ),
					array(
						'response'  => $data,
						'back_link' => true,
					)
				);
			} else {
				exit;
			}
		}
	
		$user            = wp_get_current_user();
		$cookies_consent = ( isset( $_POST['wp-comment-cookies-consent'] ) );
	
		do_action( 'set_comment_cookies', $comment, $user, $cookies_consent );
	
		// код із файлу comments.php поточної активної теми
		wp_list_comments(
			array(
				'avatar_size' => 60,
				'style'       => 'ol',
				'short_ping'  => true,
			),
			array( $comment )
		);
	
		die;
	}
}