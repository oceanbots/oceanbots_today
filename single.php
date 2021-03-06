<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package _s
 */

get_header(); ?>
<main>

<?php
while ( have_posts() ) : the_post();

	get_template_part( 'template-parts/content', get_post_format() );

	// TODO: put back in once we can make it look pretty
	// the_post_navigation(); 

	// If comments are open or we have at least one comment, load up the comment template.
	/* Comments disabled until we have time to implement them */
	// if ( comments_open() || get_comments_number() ) :
	// 	comments_template();
	// endif;

endwhile; // End of the loop.
?>

</main><!-- #main -->
<?php
get_footer();
