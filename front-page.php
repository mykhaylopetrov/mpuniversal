<?php

/**
 * The Posts page template file if set static Page as homepage OR latest posts in home page 
 */

get_header();
?>

<main class="main">
	<?php
	if ( have_posts() ) :
		if ( is_home() && ! is_front_page() ) :
	?>
			<div class="main__title">
				<h1><?php single_post_title(); ?></h1>
			</div>
	<?php
		endif;

		while ( have_posts() ) :
			the_post();
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
