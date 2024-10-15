<?php
/** 
 * Template Name: Home Page Template
 */

get_header(); 
?>

<main class="main">
	<?php
	while ( have_posts() ) {
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'article' ) ); ?>>
			<div class="article__title">	
				<?php the_title( '<h1>', '</h1>' ); ?>
			</div>
			<div class="article__content">
				<div class="article__thumb">
					<?php mpuniversal_post_thumbnail(); ?>
				</div>
				<div class="article__text">
					<?php the_content(); ?>
				</div>
			</div>
		</article>
	<?php
	}
	?>
</main>

<?php
get_sidebar();
get_footer();