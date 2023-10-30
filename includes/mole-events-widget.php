<?php

/* Create widget */

class mole_events_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'mole_event_widget',
			'Mole Events Widget',
			array (
				'customize_selective_refresh' => true
			)
		);
	}

	public function form($instance) {
		$defaults = array(
			'title' => '',
			 'category' => 'all',
			'past' => false,
			'hide-featured-img' => false,
		);

		extract(wp_parse_args( (array) $instance, $defaults));
			?>
			<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e( 'Widget Title', 'mole-events' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
			name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>">

			<label for="<?php echo esc_attr($this->get_field_id('past')); ?>"><?php esc_html_e( 'Show past events?', 'mole-events' ); ?></label>
			<input type="checkbox" <?php checked( $instance[ 'past' ], 'on' ); ?> id="<?php echo esc_attr($this->get_field_id('past')); ?>"
			name="<?php echo esc_attr($this->get_field_name('past')); ?>">

			<label for="<?php echo esc_attr($this->get_field_id('hide-featured-img')); ?>"><?php esc_html_e( 'Hide featured image?', 'mole-events' ); ?></label>
			<input type="checkbox" <?php checked( $instance[ 'hide-featured-img' ], 'on' ); ?> id="<?php echo esc_attr($this->get_field_id('featured-img')); ?>"
			name="<?php echo esc_attr($this->get_field_name('hide-featured-img')); ?>">

			<label for="<?php echo esc_attr($this->get_field_id('hide-category')); ?>"><?php esc_html_e( 'Hide category?', 'mole-events' ); ?></label>
			<input type="checkbox" <?php checked( $instance[ 'hide-category' ], 'on' ); ?> id="<?php echo esc_attr($this->get_field_id('hide-category')); ?>"
			name="<?php echo esc_attr($this->get_field_name('hide-category')); ?>">       
			</p>
			<p>
			<label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php esc_html_e( 'Event Category', 'mole-events' ); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('category')); ?>" name="<?php echo esc_attr($this->get_field_name('category')); ?>" class="widefat">
				<?php
				$terms = get_terms( 
					array(
						'taxonomy' => 'mole_events_category',
						'hide_empty' => false
					)
				);
				$options = array(
					'all' => __( 'All categories', 'mole-events' )
				);
				foreach( $terms as $term ) {
					$options[$term->slug] = $term->name;
				}
				foreach( $options as $key => $name ) {
					echo '<option value="' . esc_attr($key) . '"' . selected($category, $key, false) . '>' . $name . '</option>';
				}
				?>
			</select>
            		</p>
    			<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = isset($new_instance['title']) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['category'] = isset($new_instance['category']) ? wp_strip_all_tags( $new_instance['category'] ) : '';
		$instance['past'] = $new_instance['past'];
		$instance['hide-featured-img'] = $new_instance['hide-featured-img'];
		$instance['hide-category'] = $new_instance['hide-category'];

		return $instance;
	}

	public function widget($args, $instance) {
		extract($args);

		$title = isset($instance['title']) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$category = isset($instance['category']) ? $instance['category'] : 'all';
		$past = $instance['past'] ? true : false;
		$hide_featured_img = $instance['hide-featured-img'] ? true : false;
		$hide_category = $instance[ 'hide-category' ] ? true : false;

		echo $before_widget;

		echo '<div class="wp-widget-mole-events">';

		if( $title ) {
			echo '<h3 class="mole-events-widget-title">' . $title . '</h3>';
		}
		if( $category == 'all' ) {
			$args = array(
				'post_type' => 'mole_event',
				'post_status' => 'publish',
				'orderby' => 'meta_value', 
				'meta_key' => '_me_date',
				'posts_per_page' => 10,
				'order' => 'ASC'
			);
		} else {
			$args = array(
				'post_type' => 'mole_event',
				'mole_events_category' => $category,
				'post_status' => 'publish',
				'orderby' => 'meta_value', 
				'meta_key' => '_me_date',
				'posts_per_page' => 10,
				'order' => 'ASC'
			);
		}
    
		$content = '';
		$loop = new WP_Query($args);
        
		if( $loop->have_posts() ) {
			while( $loop->have_posts() ) { 
				$loop->the_post();
				global $post;

				$title = get_the_title();
				$date_format = get_option('date_format');
				$time_format = get_option( 'time_format' );
				$current_time = current_time('timestamp');
				$raw_date = get_post_meta( get_the_ID(), '_me_date', true );
				$raw_time = get_post_meta( get_the_ID(), '_me_time', true );
				$raw_time_to = get_post_meta( get_the_ID(), '_me_time_to', true );
				$date = !strtotime( $raw_date ) == 0 ? date( $date_format, strtotime( $raw_date ) ) : null;
				$time_from = $raw_time ? date($time_format, strtotime($date . $raw_time ) ) : null ;
				$time_to = $raw_time_to ? date($time_format, strtotime($date . $raw_time_to ) ) : null;
				$date_time = strtotime( $raw_date . $time_to );
				$recurring_toggle = get_post_meta( get_the_ID(), '_me_recurring_toggle', true );
				$recurring = get_post_meta( get_the_ID(), '_me_recurring', true );
				$url = get_permalink( get_the_ID() );
				$location = get_post_meta( get_the_ID(), '_me_location', true );
				$price = get_post_meta( get_the_ID(), '_me_price', true );
				$currency = get_post_meta( get_the_ID(), '_me_price_currency', true ) ? get_post_meta( get_the_ID(), '_me_price_currency', true ) : get_option( 'me_currency' );
				$excerpt = $post->post_excerpt;
				$thumbnail = get_the_post_thumbnail(get_the_ID(),'medium');
				$post_category = get_the_term_list($post->ID, 'mole_events_category', '', ', ');
				            
				if( ( $current_time <= $date_time && !$past ) || ($past) ) {
					
					$content .= '<div class="me-single-widget"><h3 class="widget-title cb-widget-title"><a href="' . $url . '">';
					$content .= $title ? $title . '</a></h3>' : __( 'Untitled Event', 'mole-events' ) . '</a></h3>';
					$content .= $thumbnail && !$hide_featured_img ? '<div class="me-img-small">' . $thumbnail . '</div>' : null;
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
			}
			wp_reset_postdata();
		} else {
			$content .= '<p class="me-location">' . __( 'Sorry mole, no events found! :(', 'mole-events' ) . '</p>';
		}

		echo $content;
		echo '</div>';
		echo $after_widget;
	}
}

function mole_events_register_widget() {
	register_widget('Mole_Events_Widget');
}
add_action( 'widgets_init', 'mole_events_register_widget');

?>
