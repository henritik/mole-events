<?php
/*
Register new post type: Event
*/

function mole_events_register_post_type() {

    add_theme_support('post-thumbnails');

    $labels = array(
		'name' 					=> __( 'Event','post type general name', 'mole-events' ),
		'singular_name' 		=> __( 'Event', 'post type singular name', 'mole-events' ),
		'name_admin_bar'		=> __( 'Events', 'mole-events' ),
		'add_new' 				=> __( 'New Event', 'mole-events' ),
		'add_new_item' 			=> __( 'Add new Event', 'mole-events' ),
		'edit_item' 			=> __( 'Edit Event', 'mole-events' ),
		'new_item' 				=> __( 'New Event', 'mole-events' ),
		'view_item' 			=> __( 'View Event', 'mole-events' ),
		'search_items' 			=> __( 'Search Events', 'mole-events' ),
		'not_found' 			=> __( 'Events not found', 'mole-events' ),
		'not_found_in_trash' 	=> __( 'Events not found in trash', 'mole-events' )
	);

	$args = array (
		'labels' 				=> $labels,
		'has_archive'   		=> true,
		'hierarchical'  		=> false,
		'public' 				=> true,
		'show_in_nav_menus' 	=> false,
		'description'			=> 'Mole Events description',
		'show_in_rest' 			=> true,
		'rewrite' 				=> array('slug' => 'event'),
		'supports' 				=> array (
			'title',
			'editor',
			'thumbnail',
			'custom-fields',
			'page-attributes',
			'excerpt'
		)    
	);
	register_post_type( 'mole_event', $args );
}
add_action( 'init', 'mole_events_register_post_type' );

// Register custom post meta
add_action( 'init', function() {
	register_post_meta( 'mole_event', '_me_recurring_toggle', [
		'show_in_rest' => true,
		'single' => true,
		'type' => 'boolean',
	] );

    register_post_meta( 'mole_event', '_me_recurring', [
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
	] );

	register_post_meta( 'mole_event', '_me_date', [
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
	] );
 
	register_post_meta( 'mole_event', '_me_time', [
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
	] );

    register_post_meta( 'mole_event', '_me_time_to', [
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
	] );

	register_post_meta( 'mole_event', '_me_location', [
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
	] );

    register_post_meta( 'mole_event', '_me_price', [
		'show_in_rest' => true,
		'single' => true,
		'type' => 'number',
	] );

    register_post_meta( 'mole_event', '_me_price_currency', [
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
	] );
} );

function enable_show_in_rest($args, $defaults, $object_type, $meta_key) {
	if ( ($meta_key === '_me_recurring_toggle' && is_array($args)) || 
		($meta_key === '_me_recurring' && is_array($args)) ||     
		($meta_key === '_me_date' && is_array($args)) || 
		($meta_key === '_me_time' && is_array($args)) || 
		($meta_key === '_me_time_to' && is_array($args)) || 
		($meta_key === '_me_location' && is_array($args)) || 
		($meta_key === '_me_price' && is_array($args)) || 
		($meta_key === '_me_price_currency' && is_array($args)) ) {
			$args['auth_callback'] = 'current_user_can_edit_posts';
    }
	return $args;
}
add_filter('register_meta_args', 'enable_show_in_rest', 10, 4);

function current_user_can_edit_posts() {
	return current_user_can('edit_posts');
}

// Register custom taxonomy for 'Mole Events'
function mole_events_register_taxonomies() {

	$labels = array(
		'name' 			        	=> __( 'Event Categories', 'taxonomy general name', 'mole-events' ),
		'singular_name' 		    => __( 'Event Category', 'taxonomy singular name', 'mole-events' ),
		'search_items' 		    	=> __( 'Search Event Categories', 'mole-events' ),
		'all_items' 			    => __( 'All Event Categories', 'mole-events' ),
		'edit_item'  			    => __( 'Edit Event Category', 'mole-events' ),
		'update_item' 			    => __( 'Update Event Category', 'mole-events' ),
		'add_new_item' 		    	=> __( 'Add New Event Category', 'mole-events' ),
		'new_item_name' 		    => __( 'New Event Category', 'mole-events' ),
		'popular_items' 		    => __( 'Popular Event Categories', 'mole-events' ),
		'menu_name' 			    => __( 'Event Categories', 'mole-events' ),
		'choose_from_most_used'  	=> __( 'Choose from the most used Event Categories', 'mole-events' ),
		'not_found' 			    => __( 'No Event Categories found', 'mole-events' )
	);

    $args = array(
		'labels' 		        	=> $labels,
		'hierarchical'         		=> true,
		'sort'                 		=> true,
		'args'                 		=> array( 'orderby' => 'term_order'),
		'show_admin_column'    		=> true,
		'show_in_rest' 	    		=> true,
	);

	register_taxonomy( 'mole_events_category', array('mole_event'), $args );
}
add_action( 'init', 'mole_events_register_taxonomies' );

// Display post meta on single event
    
function me_display_event_meta( $content ) {

	if( is_admin() ) {
		return;
	}

	global $post;

	$date_format = get_option( 'date_format' );
	$time_format = get_option( 'time_format' );
	$raw_date = get_post_meta( get_the_ID(), '_me_date', true );
	$raw_time = get_post_meta( get_the_ID(), '_me_time', true );
	$raw_time_to = get_post_meta( get_the_ID(), '_me_time_to', true );
	$date = !strtotime( $raw_date ) == 0 ? date( $date_format, strtotime( $raw_date ) ) : null;
	$time_from = $raw_time ? date($time_format, strtotime($date . $raw_time ) ) : null ;
	$time_to = $raw_time_to ? date($time_format, strtotime($date . $raw_time_to ) ) : null;
	$recurring_toggle = get_post_meta( get_the_ID(), '_me_recurring_toggle', true );
	$recurring = get_post_meta( get_the_ID(), '_me_recurring', true );
	$location = get_post_meta( get_the_ID(), '_me_location', true );
	$price = get_post_meta( get_the_ID(), '_me_price', true );
	$currency = get_post_meta( get_the_ID(), '_me_price_currency', true ) ? get_post_meta( get_the_ID(), '_me_price_currency', true ) : get_option( 'me_currency' );

	if( is_singular('mole_event') ) {         
		$event_meta = '<div class="me-meta-wrap">';
		
		if( $recurring_toggle ) {
			$event_meta  .= $recurring ? '<p>' . __( 'Every ', 'mole-events' ) . '<span class="me-meta">' . $recurring . '</span>' : 
			'<p><span class="me-meta">' . __( 'Every day ', 'mole-events' ) . '</span>';
			$event_meta  .= $time_from ? __( ' at ', 'mole-events' ) . '<span class="me-meta">' . $time_from . '</span>' : null;
			$event_meta  .= $time_from && $time_to ? ' - ' . '<span class="me-meta">' . $time_to . '</span></p>' : '</p>';
			$event_meta  .= $date ? '<p>' . __( 'Starting from: ', 'mole-events' ) . '<span class="me-meta">' . $date . '</span></p>' :
			'<p>' . __( 'Starting from: ', 'mole-events' ) . '<span class="me-meta">' . __( 'TBA', 'mole-events' ) . '</span></p>';

		} else {
			$event_meta  .= $date ? '<p><span class="me-meta">' . $date . '</span>' : '<p><span class="me-meta">' . __( 'TBA', 'mole-events' ) . '</span>';
			$event_meta  .= $date && $time_from ? __( ' at ', 'mole-events' ) . '<span class="me-meta">' . $time_from . '</span>' : null;
			$event_meta  .= $date && $time_from && $time_to ? ' - ' . '<span class="me-meta">' . $time_to . '</span></p>' : '</p>';
   		}
       	
		$event_meta .= $location ? '<p>' . __( 'Location: ', 'mole-events' ) . '<span class="me-meta">' . $location . '</span></p>' : null;
		$event_meta .= $price > 0 && $currency > 0 ? '<p>' . __( 'Price: ', 'mole-events' ) . '<span class="me-meta">' . $price . ' ' . $currency . '</span></p>' : null;
		$event_meta .= '</div>';
		$event_meta .= $content;
		return $event_meta;          
	} else {
		return $content;
	}     
}
add_filter( 'the_content', 'me_display_event_meta' );

?>