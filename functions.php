<?php
/**
 * oceanbots functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package oceanbots
 */

if ( ! function_exists( 'oceanbots_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function oceanbots_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on oceanbots, use a find and replace
	 * to change 'oceanbots' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'oceanbots', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'oceanbots' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	require_once( 'library/post-ship.php' );
}
endif;
add_action( 'after_setup_theme', 'oceanbots_setup' );

/**
 * Enqueue scripts and styles.
 */
function oceanbots_scripts() {
	wp_enqueue_style( 'oceanbots-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'oceanbots_scripts' );

add_image_size( 'grid-thumb', 500, 280, true ); // Hard Crop Mode
add_image_size( 'post-header', 1200, 9999 ); // Limit to max page width