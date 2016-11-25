<?php
/* 
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
	
	/* this adds your post categories to your custom post type */
	//register_taxonomy_for_object_type( 'category', 'custom_type' );
	/* this adds your post tags to your custom post type */
	//register_taxonomy_for_object_type( 'post_tag', 'custom_type' );
	
}

	// adding the function to the Wordpress init
	add_action( 'init', 'create_ship');
	
	/*
	for more information on taxonomies, go here:
	http://codex.wordpress.org/Function_Reference/register_taxonomy
	*/
	
	
	/*
		looking for custom meta boxes?
		check out this fantastic tool:
		https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
	*/
	
/**
* 
 * Adds metabox for subtitle
 * Code modified from https://codex.wordpress.org/Function_Reference/add_meta_box
 */
function ship_add_meta_box() {

	# screens defines where box appears
	$screens = array( 'ship' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'ship_link',
			__( 'Link', 'ship_textdomain' ),
			'link_meta_box_callback',
			$screen,
			'normal',
			'high'
		);

		add_meta_box(
			'ship_status',
			__( 'Status', 'ship_textdomain' ),
			'status_meta_box_callback',
			$screen,
			'side',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'ship_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function link_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'ship_save_meta_box_data', 'link_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, 'ship_link', true );

	echo '<input type="text" id="link_new_field" name="link_new_field" value="' . esc_attr( $value ) . '" size="80" />';
}

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function status_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'ship_save_meta_box_data', 'status_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, 'ship_status', true );

	echo '<input type="text" id="status_new_field" name="status_new_field" value="' . esc_attr( $value ) . '" size="20" />';
}


/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 *
 * I don't think there's any reason to keep this as one function, 
 * instead of having a different one for each box
 */
function ship_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonces are set.
	if ( ! isset( $_POST['link_meta_box_nonce'] ) ) {
		return;
	}
	if ( ! isset( $_POST['status_meta_box_nonce'] ) ) {
		return;
	}


	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['link_meta_box_nonce'], 'ship_save_meta_box_data' ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['status_meta_box_nonce'], 'ship_save_meta_box_data' ) ) {
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
	if ( isset( $_POST['link_new_field'] ) ) {
		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['link_new_field'] );

		// Update the meta field in the database.
		update_post_meta( $post_id, 'ship_link', $my_data );	
	}

	// Make sure that it is set.
	if ( isset( $_POST['status_new_field'] ) ) {
		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['status_new_field'] );

		// Update the meta field in the database.
		update_post_meta( $post_id, 'ship_status', $my_data );	
	}
}
add_action( 'save_post', 'ship_save_meta_box_data' );
	

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 *
 * I don't think there's any reason to keep this as one function, 
 * instead of having a different one for each box
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

	// WE ARE HERE

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( isset( $_POST['link_new_field'] ) ) {
		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['link_new_field'] );

		// Update the meta field in the database.
		update_post_meta( $post_id, 'ship_link', $my_data );	
	}
}



/*
 * Place metabox above editor
 * 
 * Code from http://wordpress.stackexchange.com/a/88103
 */

// Move all "advanced" metaboxes above the default editor
add_action('edit_form_after_title', function() {
    global $post, $wp_meta_boxes;
    do_meta_boxes(get_current_screen(), 'advanced', $post);
    unset($wp_meta_boxes[get_post_type($post)]['advanced']);
});
?>
