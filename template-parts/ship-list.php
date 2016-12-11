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
	$link_url = get_post_meta( $post->ID, 'ship_link', true );
	if ( $link_url ) : ?>
		<a href="<?php echo ($link_url); ?>" title="Live Stream Site" target="_blank">
			<?php echo ( preg_replace('#^https?://#', '', rtrim($link_url,'/') ) ); ?>
		</a>
	<?php endif;
	$ship_status = get_post_meta( $post->ID, 'ship_status', true );
	if ( $ship_status ) :
		$live_statuses = array('Live', 'Diving');
		if ( in_array ( $ship_status, $live_statuses ) ) :
			$status_class = '"status live"';
		else :
			$status_class = '"status"';
		endif; ?>
		<div class="stream-status">
			Status: 
			<span class=<?php echo ( $status_class ); ?>>
				<?php echo ( $ship_status ); ?>
			</span>
		</div>
	<?php endif; ?>
</section>