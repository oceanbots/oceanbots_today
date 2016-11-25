<?php
/**
 * Custom post type for ships
*/

// Flush rewrite rules for custom post types
add_action( 'after_switch_theme', 'ship_flush_rewrite_rules' );

// Flush your rewrite rules
function ship_flush_rewrite_rules() {
	flush_rewrite_rules();
}

// let's create the function for the custom type
function create_ship() { 
	// creating (registering) the custom type 
	register_post_type( 'ship', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
		array( 'labels' => array(
			'name' => __( 'Ships', 'oceanbots' ), /* This is the Title of the Group */
			'menu_name' => __( 'Ships', 'oceanbots' ),
			'singular_name' => __( 'Ship', 'oceanbots' ), /* This is the individual type */
			'all_items' => __( 'ALL THE SHIPS', 'oceanbots' ), /* the all items menu item */
			'add_new' => __( 'Commandeer', 'oceanbots' ), /* The add new menu item */
			'add_new_item' => __( 'Commandeer a ship', 'oceanbots' ), /* Add New Display Title */
			'edit' => __( 'Edit', 'oceanbots' ), /* Edit Dialog */
			'edit_item' => __( 'Edit ship', 'oceanbots' ), /* Edit Display Title */
			'new_item' => __( 'New ship', 'oceanbots' ), /* New Display Title */
			'view_item' => __( 'View ship', 'oceanbots' ), /* View Display Title */
			'search_items' => __( 'Search ships', 'oceanbots' ), /* Search Custom Type Title */ 
			'not_found' =>  __( 'Nothing found in the Database.', 'oceanbots' ), /* This displays if there are no entries yet */ 
			'not_found_in_trash' => __( 'Nothing found in Trash', 'oceanbots' ), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'Ship entries', 'oceanbots' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => 'dashicons-admin-site', /* the icon for the custom post type menu */
			'rewrite'	=> array( 'slug' => 'ship', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => "ship", /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'thumbnail', 'revisions', 'sticky')
		) /* end of options */
	); /* end of register post type */
	
}

add_action( 'init', 'create_ship');


/*
 * Custom Meta Boxes
 */

/**
 * Save data for the given meta box
 * Helper for ship_save_meta_boxes()
 * 
 * @param string $item_name Name of form value and of database entry
 * @param string $nonce_name Name of nonce used to verify submitted data
 * @param int $post_id The ID of the post being saved.
 */
function ship_save_meta_box_data( $item_name, $nonce_name, $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonces are set.
	if ( ! isset( $_POST[$nonce_name] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST[$nonce_name], 'ship_save_meta_box_data' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( isset( $_POST[$item_name] ) ) {
		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST[$item_name] );

		// Update the meta field in the database.
		update_post_meta( $post_id, $item_name, $my_data );	
	}
}


/**
* 
 * Adds metabox for subtitle
 * Code modified from https://codex.wordpress.org/Function_Reference/add_meta_box
 */
function ship_add_meta_box() {

	add_meta_box(
		'ship_link',                     // html id
		__( 'Link', 'ship_textdomain' ), // title
		'text_meta_box_callback',        // callback function
		'ship',                          // screen where box appears
		'normal',                        // location where box appears
		'high',                          // location priority
		array(                           // arguments passed to callback
			'item_name' => 'ship_link',
			'nonce' => 'link_meta_box_nonce',
			'size' => '80'
			)
	);

	add_meta_box(
		'ship_status',
		__( 'Status', 'ship_textdomain' ),
		'text_meta_box_callback',
		'ship',
		'side',
		'high',
		array(
			'item_name' => 'ship_status',
			'nonce' => 'status_meta_box_nonce',
			'size' => '20'
			)
	);

	add_meta_box(
		'x_pos',
		__( 'X Position', 'ship_textdomain' ),
		'text_meta_box_callback',
		'ship',
		'side',
		'low',
		array(
			'item_name' => 'x_pos',
			'nonce' => 'x_pos_meta_box_nonce',
			'size' => '20'
			)
	);

	add_meta_box(
		'y_pos',
		__( 'Y Position', 'ship_textdomain' ),
		'text_meta_box_callback',
		'ship',
		'side',
		'low',
		array(
			'item_name' => 'y_pos',
			'nonce' => 'y_pos_meta_box_nonce',
			'size' => '20'
			)
	);
}
add_action( 'add_meta_boxes', 'ship_add_meta_box' );


/**
 * Prints a text field for a meta box
 * 
 * @param WP_Post $post The object for the current post/page.
 * @param array $args Additional arguments ['args'['item_name', 'nonce', 'size']]
 */
function text_meta_box_callback( $post, $args ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'ship_save_meta_box_data', $args['args']['nonce'] );

	$value = get_post_meta( $post->ID, $args['args']['item_name'], true );

	echo '<input type="text" id="' . $args['args']['item_name'] . '" name="' . $args['args']['item_name'] . '" value="' . esc_attr( $value ) . '" size="' . $args['args']['size'] . '" />';
}
	

/**
 * When post is saved, save data from each of the metaboxes 
 *
 * @see ship_save_meta_box_data
 * @param int $post_id The ID of the post being saved.
 */
function ship_save_meta_boxes( $post_id ) {
	ship_save_meta_box_data('ship_link', 'link_meta_box_nonce', $post_id );
	ship_save_meta_box_data('ship_status', 'status_meta_box_nonce', $post_id );
	ship_save_meta_box_data('x_pos', 'x_pos_meta_box_nonce', $post_id );
	ship_save_meta_box_data('y_pos', 'y_pos_meta_box_nonce', $post_id );
}

add_action( 'save_post', 'ship_save_meta_boxes' );


/*
 * Move all "advanced" metaboxes above the default editor
 * 
 * Code from http://wordpress.stackexchange.com/a/88103
 */

// add_action('edit_form_after_title', function() {
//     global $post, $wp_meta_boxes;
//     do_meta_boxes(get_current_screen(), 'advanced', $post);
//     unset($wp_meta_boxes[get_post_type($post)]['advanced']);
// });
?>
