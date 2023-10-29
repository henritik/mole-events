# Mole Events

Plugin Name: Mole Events<br>
Description: A simple and straightforward events plugin with customizable widgets and shortcodes.<br>
Version: 0.1.0<br>
Requires PHP: 7.4<br>
Author: Henri Tikkanen<br>
Author URI: https://github.com/henritik/<br>
License: License: GPLv2<br>
Tested up to: WordPress 6.3.1<br>
<br>

### Description

Mole Events is a simple and straightforward events plugin with customizable widgets and shortcodes to show your events anywhere on WordPress site. It's fully Gutenberg compatible and created with minimal amount of additional styles, without fancy JavaScript effects or other unnecessary stuff - the widest possible theme support and the minimal impact on your current setup in mind. It just does what it's supposed to do and does it well! Put your mole hands in the ground and let people know what is happening over there!

### Installation

1. Download zipped plugin files.
2. Visit **Plugins > Add New > Upload Plugin**, search the zip file from your computer and click **Install Now**.
3. Activate the plugin.

### Upgrade Notice
In order to update the plugin form an earlier version, please do the installation steps 1-2 and allow WordPress to replace existing files.

### How to create a new event
Go to Events menu on the left bar, choose New Event, choose the title and add some content if you wish, fill all the needed meta information and publish, that's it!

### Using shortcodes
Use the shortcode [mole-events] to show the full event list everywhere in your content. To show only events in a specific category, use category-attribute with a category-slug, for example [mole-events category='test-cat']

### Good to know about widgets and shortcodes
Following basic rules are good the keep in mind when showing your events by using widgets or shortcodes:

1. There are no required fields in the Events post type but you have to do at least some modifications to publish a new event.
2. When event title is not set, _Untitled Event_ will be shown instead.
3. When time is not set at all or beginning time is empty, _TBA_ will be shown instead.
4. When it's recurring event and **recurring every** field is not set, every day will be shown instead.
5. You can add as many event categories as you wish and all of them will be shown in widgets by default and all categories will be taken into account when filtering events based on the category.
6. Optional post excerpt is shown in widgets and shortcodes under the even't meta section.
7. Events with **date** or **starting date** filled in will be sorted in ascending order by the date information and others will be shown first by the order of created.
8. Date and time format is based directly on the WordPress general settings.
9. The price is shown when value is more than 0 and also the currency is set under the event or in default settings on the plugin settings page.
10. The default currency can be set on the plugin settings page. However, if the currency is set under the single event the default currency will be overridden.
11. In the widgets up to 10 events are shown at once and in the shortcodes all the events are shown in the same page.

### Changelog

#### 0.1.0
- Initial release

