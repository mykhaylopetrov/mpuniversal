<?php
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div class="article__comments">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2>
			<?php
			$mpuniversal_comment_count = get_comments_number();
			if ( '1' === $mpuniversal_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'mpuniversal' ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			} else {
				printf( 
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $mpuniversal_comment_count, 'comments title', 'mpuniversal' ) ),
					number_format_i18n( $mpuniversal_comment_count ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			}
			?>
		</h2>

		<?php the_comments_navigation(); ?>

		<ol>
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation();

		// If comments are closed
		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'mpuniversal' ); ?></p>
			<?php
		endif;

	endif;

	if ( mpuniversal_change_standard_wp_comment_form_comment_field() ) {
		comment_form( mpuniversal_change_standard_wp_comment_form_comment_field() );
	} else {
		comment_form();
	}
	?>

</div>
