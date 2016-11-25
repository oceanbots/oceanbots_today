<?php
/**
 * Template part for ship listing
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package oceanbots
 */
 ?>

<section class="ship">
	<h2><?php the_title(); ?></h2>
	<?php the_content();
	if ( get_post_meta( $post->ID, 'ship_link', true )) :
		$link_url = get_post_meta( $post->ID, 'ship_link', true ); ?>
		<a href="<?php echo ($link_url); ?>" title="Live Stream Site">
			<?php echo ($link_url); ?>
		</a>
	<?php endif;
	if ( get_post_meta( $post->ID, 'ship_status', true )) : ?>
		<div class="stream-status">
			Status: 
			<span class="status">
				<?php echo ( get_post_meta( $post->ID, 'ship_status', true ) ); ?>
			</span>
		</div>
	<?php endif; ?>
</section>