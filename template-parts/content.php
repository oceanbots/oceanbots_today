	<div class="post-header">
		<?php the_post_thumbnail('post-header'); ?>
	</div>
<article>
	<h1><?php the_title(); ?></h1>
	<?php
		the_content( sprintf(
			/* translators: %s: Name of current post. */
			wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', '_s' ), array( 'span' => array( 'class' => array() ) ) ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		) );

?>
</article>