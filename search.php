<?php
/**
 * The template for displaying search results pages
 */

get_header();
?>

<main class="main">
	<?php if ( have_posts() ) : ?>
		<div class="search__title">
			<h1>
				<?php
				/* translators: %s: search query. */
				printf( esc_html__( 'Search Results for: %s', 'mpuniversal' ), '<span>' . get_search_query() . '</span>' );
				?>
			</h1>
		</div>
		
		<?php	
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', 'search' );
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
