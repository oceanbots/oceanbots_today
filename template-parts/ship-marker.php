<?php 
/**
 * Template part for ship markers
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package oceanbots
 */
$x_pos = get_post_meta( $post->ID, 'x_pos', true);
$y_pos = get_post_meta( $post->ID, 'y_pos', true); 
?>
<div class="marker" style="left: <?php echo( $x_pos ); ?>%; bottom: <?php echo( $y_pos ); ?>%;">
	<img src="<?php echo ( get_template_directory_uri() . '/images/marker.png'); ?>" alt="Marker">
	<span><?php the_title(); ?></span>
</div>