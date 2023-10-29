<?php

/* Create shortcode */

function mole_events_shortcode( $atts = array() ) {
	
	$atts = array_change_key_case( (array) $atts, CASE_LOWER );
  		
	$me_atts = shortcode_atts(
		array(
			'category' => 'all',
		), $atts
	);
  
	if( $me_atts['category'] == 'all' ) {
		$args = array(
			'post_type' => 'mole_event',
			'post_status' => 'publish',
			'orderby' => 'meta_value', 
			'meta_key' => '_me_date',
			'order' => 'ASC',
			'posts_per_page' => -1
		);
	} else {
        $args = array(
			'post_type' => 'mole_event',
			'mole_events_category' => $me_atts['category'],
			'post_status' => 'publish',
			'orderby' => 'meta_value', 
			'meta_key' => '_me_date',
			'order' => 'ASC',
			'posts_per_page' => -1,
		);
	}

	$content = '<div class="mole-events">';
	$loop = new WP_Query($args);
	
	if( $loop->have_posts() ) {
		while( $loop->have_posts() ) { 
			$loop->the_post();
             
			$title = get_the_title();
			$date_format = get_option('date_format');
			$time_format = get_option( 'time_format' );
			$raw_date = get_post_meta( get_the_ID(), '_me_date', true );
			$raw_time = get_post_meta( get_the_ID(), '_me_time', true );
			$raw_time_to = get_post_meta( get_the_ID(), '_me_time_to', true );
			$date = !strtotime( $raw_date ) == 0 ? date( $date_format, strtotime( $raw_date ) ) : null;
			$time_from = $raw_time ? date($time_format, strtotime($date . $raw_time ) ) : null ;
			$time_to = $raw_time_to ? date($time_format, strtotime($date . $raw_time_to ) ) : null;
			$recurring_toggle = get_post_meta( get_the_ID(), '_me_recurring_toggle', true );
			$recurring = get_post_meta( get_the_ID(), '_me_recurring', true );
			$url = get_permalink( get_the_ID() );
			$location = get_post_meta( get_the_ID(), '_me_location', true );
			$price = get_post_meta( get_the_ID(), '_me_price', true );
			$currency = get_post_meta( get_the_ID(), '_me_price_currency', true ) ? get_post_meta( get_the_ID(), '_me_price_currency', true ) : get_option( 'me_currency' );    
			$excerpt = get_the_excerpt();
			$thumbnail = get_the_post_thumbnail(get_the_ID(),'large');
			$post_category = get_the_term_list($post->ID, 'mole_events_category', '', ', ');
                    
			$content .= '<div class="me-single-shortcode">';
			$content .= $thumbnail ? '<div class="me-img-small">' . $thumbnail . '</div>' : null;
			$content .= '<h3 class="me-shortcode-title"><a href="' . $url . '">';
			$content .= $title ? $title . '</a></h3>' : __( 'Untitled Event', 'mole-events' ) . '</a></h3>';              
			$content .= '<div class="me-meta-wrap">';
                        
			if( $recurring_toggle ) {
				$content .= $recurring ? '<p>' . __( 'Every ', 'mole-events' ) . '<span class="me-meta">' . $recurring . '</span>' : 
				'<p><span class="me-meta">' . __( 'Every day ', 'mole-events' ) . '</span>';
				$content .= $time_from ? __( ' at ', 'mole-events' ) . '<span class="me-meta">' . $time_from . '</span>' : null;
				$content .= $time_from && $time_to ? ' - ' . '<span class="me-meta">' . $time_to . '</span></p>' : '</p>';
				$content .= $date ? '<p>' . __( 'Starting from: ', 'mole-events' ) . '<span class="me-meta">' . $date . '</span></p>' :
				'<p>' . __( 'Starting from: ', 'mole-events' ) . '<span class="me-meta">' . __( 'TBA', 'mole-events' ) . '</span></p>';
			} else {
				$content .= $date ? '<p><span class="me-meta">' . $date . '</span>' : '<p><span class="me-meta">' . __( 'TBA', 'mole-events' ) . '</span>';
				$content .= $date && $time_from ? __( ' at ', 'mole-events' ) . '<span class="me-meta">' . $time_from . '</span>' : null;
				$content .= $date && $time_from && $time_to ? ' - ' . '<span class="me-meta">' . $time_to . '</span></p>' : '</p>';
			}
			$content .= $location ? '<p>' . __( 'Location: ', 'mole-events' ) . '<span class="me-meta">' . $location . '</span></p>' : null;
			$content .= $price > 0 && $currency > 0 ? '<p>' . __( 'Price: ', 'mole-events' ) . '<span class="me-meta">' . $price . ' ' . $currency . '</span></p>' : null;
			$content .= $post_category && !$hide_category ? '<p>' . __( 'Category: ', 'mole-events' ) . '<span class="me-meta">' . $post_category . '</span></p>' : null;
			$content .= '</div>';
			$content .= $excerpt ? '<p class="me-excerpt">' . $excerpt . '</p>' : null;
			$content .= '<a class="me-read-more" href="' . $url . '">' . __( 'Go to event &raquo;', 'mole-events' ) . '</a>';
			$content .= '</div>';  
		}
	} else {
		$content .= '<p>' . __( 'Sorry mole, no events found! :(', 'mole-events' ) . '</p>';
	}

	$content .= '<div>';

	wp_reset_postdata();

	return $content;
}
add_shortcode('mole-events', 'mole_events_shortcode');

?>