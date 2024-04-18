<?php
/**
 * The template for displaying 404 pages
 */

get_header();
?>

<main class="main">
	<section class="error-404">
		<h1><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'mpuniversal' ); ?></h1>
			
		<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'mpuniversal' ); ?></p>

		<?php get_search_form(); ?>
	</section>
</main>

<?php
get_footer();
