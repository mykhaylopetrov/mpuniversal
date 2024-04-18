<?php

/**
 * The main template file
 */

get_header();
?>

<main class="main">
	<?php
	if ( have_posts() ) :
		if ( is_home() && ! is_front_page() ) :
	?>
			<h1><?php single_post_title(); ?></h1>
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
