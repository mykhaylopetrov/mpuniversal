<?php
/**
 * Template part for displaying results in search pages
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'article' ) ); ?>>
	<div class="article__title">
		<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</div>

	<div class="article__content">
		<div class="article__thumb">
			<?php mpuniversal_post_thumbnail(); ?>
		</div>
		
		<div class="article__text">
			<?php the_excerpt(); ?>
		</div>
	</div>

	<div class="article__meta">
		<?php if ( 'post' === get_post_type() ) : ?>
			<div>
				<?php
				mpuniversal_posted_on();
				mpuniversal_posted_by();
				?>
			</div>
		<?php endif; ?>

		<?php mpuniversal_entry_meta(); ?>
	</div>
</article>
