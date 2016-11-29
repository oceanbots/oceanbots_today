<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package oceanbots
 */

get_header(); ?>

<main>
<!-- map area is hardcoded until we get the cutom post type in place -->
<div class=map-area>
	<h1>Active Ocean Bots</h1>
	<div class="map">
		<img src="<?php echo ( get_template_directory_uri() . '/images/world_basemap_cropped.png'); ?>" title="Map of current expeditions" alt="Map showing locations of current expeditions">

		<?php /* Ship markers */
		$args = array('post_type' => 'ship');
		$query_ship_locs = new WP_Query( $args );
		if ( $query_ship_locs->have_posts() ) :
			while ( $query_ship_locs->have_posts() ) :
				$query_ship_locs->the_post();
				if ( get_post_meta( $post->ID, 'x_pos', true ) 
				&& get_post_meta( $post->ID, 'x_pos', true ) ) :
					get_template_part( 'template-parts/ship', 'marker' );
				endif;
			endwhile;	
		endif; ?>
	</div>

	<?php /* Ship list */
	$query_ship_list = new WP_Query( $args );
	if ( $query_ship_list->have_posts() ) :
		while ( $query_ship_list->have_posts() ) :
			$query_ship_list->the_post();
			get_template_part( 'template-parts/ship', 'list' );
		endwhile;
	endif; ?>

</div>

<section class="grid">
				
	<h2>Other Stuff</h2>

	<?php
	if ( have_posts() ) :
		/* Start the Loop */
		while ( have_posts() ) : the_post();

			/*
			 * Include the Post-Format-specific template for the content.
			 * If you want to override this in a child theme, then include a file
			 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
			 */
			get_template_part( 'template-parts/excerpt', get_post_format() );

		endwhile;
		the_posts_navigation();

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif; ?>
</section>
	
<? // hardcoded for now, instead of using get_sidebar();
   // because I don't think this counts as a proper wordpress sidebar ?>
<div class="grid-sidebar">
	<div class="twitter-container">
		<a class="twitter-timeline" data-width="1000" data-height="902" href="https://twitter.com/oceanbots">Tweets by oceanbots</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
	</div>
</div>

</main>

<?php get_footer(); ?>