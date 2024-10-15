<?php
/**
 * Template part for displaying posts
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'article' ) ); ?>>
	<div class="article__title">
		<?php
		if ( is_singular() ) :
			the_title( '<h1>', '</h1>' );
		else :
			the_title( '<h2><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>
	</div>

	<div class="article__content">
		<div class="article__thumb">
			<?php mpuniversal_post_thumbnail(); ?>
		</div>
		<div class="article__text">
			<?php
			if ( 
				( is_home() && ! is_front_page() ) || // Blog arhive static page
				( is_front_page() ) || // Latest posts in homepage
				( is_archive() ) // Category, tag, author, date, custom post type, and custom taxonomy based archive
				) {
				the_excerpt();
			} else {
				// Single Post, Page
				the_content(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							esc_html__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'mpuniversal' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						wp_kses_post( get_the_title() )
					)
				);
			}

			wp_link_pages(
				array(
					'before' => '<div>' . esc_html__( 'Pages:', 'mpuniversal' ),
					'after'  => '</div>',
				)
			);
			?>
		</div>
	</div>

	<div class="article__meta">
		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="posted-date-autor">
				<?php
				mpuniversal_posted_on();
				mpuniversal_posted_by();
				?>
			</div>
		<?php endif; ?>

		<?php mpuniversal_entry_meta(); ?>
	</div>

	<?php
	// if ( shortcode_exists( 'mpuniversal_ajax_post_views_counter_shortcode' ) ) {
	// 	echo do_shortcode( '[mpuniversal_ajax_post_views_counter_shortcode]' ); 
	// }
	?>

	<?php
	if ( shortcode_exists( 'mpviews_counter' ) ) {
		echo do_shortcode( '[mpviews_counter]' ); 
	}
	?>

	<?php if ( is_singular() ) { ?>
		<div class="article__navigation">
			<?php mpuniversal_article_navigation(); ?>
		</div>
	<?php } ?>
	
	<?php
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
	?>

</article>
