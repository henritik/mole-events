<?php

/* Settings page init */

function me_settings_init() {

	register_setting('mole-events', 'me_currency');

	add_settings_section(
		'me_settings_section',
		__( 'About', 'mole-events' ), 'me_readme_section_callback',
		'mole-events'
	);

	add_settings_field(
		'me_settings_field',
		'Default currency: ', 
		'me_settings_field_callback',
		'mole-events',
		'me_settings_section'
	);
}
add_action('admin_init', 'me_settings_init');

/* Settings page callbacks */

function me_readme_section_callback() {
	echo 
	"<p>Mole Events is a simple and straightforward events plugin with customizable widgets and shortcodes to show your events anywhere on WordPress site.
	It's fully Gutenberg compatible and created with minimal amount of additional styles, without fancy JavaScript effects or other unnecessary stuff - 
	the widest possible theme support and the minimal impact on your current setup in mind. It just does what it's supposed to do and does it well!
	Put your mole hands in the ground and let people know what is happening over there!</p>
	<h2>How to create a new event</h2>
	<p>Go to <b>Events</b> menu on the left bar, choose <b>New Event</b>, choose the title and add some content if you wish, fill all the needed meta information and publish, that's it!</p>
	<h2>Using shortcodes</h2>
	<p>Use the shortcode <b>[mole-events]</b> to show the full event list everywhere in your content. To show only events in a specific category, use category-attribute with a category-slug, for example <b>[mole-events category='test-cat']</b></p>
	<h2>Good to know about widgets and shortcodes</h2>
	<p>Following basic rules are good the keep in mind when showing your events by using widgets or shortcodes:</p>
	<ol>
		<li>There are no required fields in the Events post type but you have to do at least some modifications to publish a new event.</li>
		<li>When event title is not set, <i>Untitled Event</i> will be shown instead.
		<li>When time is not set at all, beginning time is empty or time is set but date is empty, <i>TBA</i> will be shown instead.</li>
		<li>When it's recurring event and <b>recurring every</b> field is not set, <i>every day</i> will be shown instead.</li>
		<li>You can add as many event categories as you wish and all of them will be shown in widgets by default and all categories will be taken into account when filtering events based on the category.</li>
		<li>Optional post excerpt is shown in widgets and shortcodes under the even't meta section.</li>
		<li>Events with <b>date</b> or <b>starting date</b> filled in will be sorted in ascending order by the date information and others will be shown first by the order of created.</li>
		<li>Date and time format is based directly on the WordPress general settings.</li>
		<li>The price is shown when value is more than 0 and also the currency is set under the event or in default settings below.</li>
		<li>The default currency can be set below. However, if the currency is set under the single event the default currency will be overridden.</li>
		<li>In the widgets up to 10 events are shown at once and in the shortcodes all the events are shown in the same page.</li>
	</ol>";
}

function me_settings_field_callback() {
	
	$setting = get_option('me_currency');

	?>
	<input type="text" name="me_currency" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
    <?php
}

/* Add top level menu and callback with error handling */

function me_options_page() {
	add_menu_page(
		'Mole Events',
		'Mole Events',
		'manage_options',
		'mole-events',
		'me_options_page_html',
		'dashicons-smiley'
	);
}
add_action( 'admin_menu', 'me_options_page' );

function me_options_page_html() {
	
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error( 'me_messages', 'me_message', __( 'Settings Saved', 'mole-events' ), 'updated' );
	}

	settings_errors( 'me_messages' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'mole-events' );
			do_settings_sections( 'mole-events' );
			submit_button( 'Save Settings' );
			?>
		</form>
	</div>
	<?php
}
