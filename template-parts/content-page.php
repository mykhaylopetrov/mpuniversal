<?php
/**
 * Template part for displaying page content in page.php
 */
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
