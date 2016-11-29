<?php
// Proper form might be to include this in main post file, and use
// is_single() checks, but I might want excerpts of other posts on
// a post page, and idk how that's handled
?>
<section class="grid-elem">
	<a href="<?php the_permalink(); ?>">
	    <?php if ( has_post_thumbnail() ) {
	    	the_post_thumbnail();
	    } ?>
		<?php the_title( '<h3 class="entry-title">', '</h3>' ); ?>
		<span class="category">Article  </span>
		<?php the_excerpt(); ?>
		<div class="text-fade"></div>
	</a>
</section>