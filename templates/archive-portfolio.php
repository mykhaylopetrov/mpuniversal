<?php
/** 
 * Archive Portfolio CPT 
 */

get_header();
?>

<main class="main">
	<?php if ( have_posts() ) : ?>	
		<?php
		the_archive_title( '<div class="archive__title"><h1>', '</h1></div>' );
		the_archive_description( '<div class="archive__description">', '</div>' );
		?>

		<?php
		while ( have_posts() ) :
			the_post();

			/*
			* Include the Post-Type-specific template for the content.
			* If you want to override this in a child theme, then include a file
			* called content-___.php (where ___ is the Post Type name) and that will be used instead.
			*/
			get_template_part( 'template-parts/content', get_post_type() );
		endwhile;

		// the_posts_navigation();
		mpuniversal_pagination();
	else :
		get_template_part( 'template-parts/content', 'none' );

	endif;
	?>
</main>

<?php
get_sidebar();
get_footer();
