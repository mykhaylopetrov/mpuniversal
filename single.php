<?php
/**
 * The template for displaying all single posts
 */

get_header();
?>

<main class="main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/content', get_post_type() );

		// the_post_navigation(
		// 	array(
		// 		'prev_text' => '<span>' . esc_html__( 'Previous:', 'mpuniversal' ) . '</span> <span>%title</span>',
		// 		'next_text' => '<span>' . esc_html__( 'Next:', 'mpuniversal' ) . '</span> <span>%title</span>',
		// 	)
		// );

		// // If comments are open or we have at least one comment, load up the comment template.
		// if ( comments_open() || get_comments_number() ) :
		// 	comments_template();
		// endif;
	endwhile;
	?>
</main>

<?php
get_sidebar();
get_footer();
