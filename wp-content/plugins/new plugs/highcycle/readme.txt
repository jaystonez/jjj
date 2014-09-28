=== HighCycle ===
Contributors: madjax
Tags: gallery, lightbox, slideshow, highslide, cycle
Tested up to: 3.3.1
Stable tag: 1.3.3
Requires at least: 3.2.1

HighCycle is a gallery replacement plugin for WordPress. It adds Highslide for image enlargements, and slideshows using the Cycle plugin for jQuery.

== Description ==

HighCycle is a gallery replacement plugin for WordPress. It adds Highslide for image enlargements, and creates slideshows of attached images using the Cycle plugin for jQuery. The [gallery] shortcode is replaced with a Highslide powered gallery. An additional shortcode [slideshow] is provided. 

NOTE: You must download Highslide from http://highslide.com/ - for this plugin to work properly. See installation intstructions.

Highslide JS is licensed under a Creative Commons Attribution-NonCommercial 2.5 License. This means you need the author's permission to use Highslide JS on commercial websites. http://highslide.com/

== Changelog ==
= 1.3.3 =
* Remove Highslide from plugin package to conform to repository GPL rules

= 1.3.2 =
* Bugfix - linkto=url in slideshow now displaying specified URL

= 1.3.1 =
* Fix IE 7 error with highcycle.js
* Corrections to readme.txt
* Move localization of highcycle.js to allow override by shortcode attributes

= 1.3 =
* Initial Release

== Upgrade Notice ==
= 1.3.3 =
* Highslide JS removed - MUST BE REINSTALLED - see install instructions.

= 1.3.2 =
* Bugfix - linkto=url in slideshow now displaying specified URL

= 1.3.1 =
* Fixes IE 7 error

= 1.3 =
Initial Release

== Installation ==

1. Use automatic installer
1. Activate plugin
1. Download Highslide ZIP package from http://highslide.com/download.php
1. Unpack Highslide, and FTP the 'highslide' directory to the new directory in your plugins folder 'highcycle'
1. Your linked images and galleries will now use Highslide for enlargements.

== Frequently Asked Questions ==

= It's not working? =
Be sure to download Highslide from http://highslide.com/download.php and then upload the 'highslide' directory to the plugins directory.

You should have a directory inside wp-content/plugins/ called 'highcycle', which then contains your 'highslide' directory.

= Do I need to purchase a license? =
If you're using HighCycle on a commercial website, you must purchase a license from the author of Highslide JS http://highslide.com/

= What shortcodes are available? =

[gallery]

`
'order'      => 'ASC',
'orderby'    => 'menu_order ID',
'id'         => $post->ID,
'itemtag'    => 'dl',
'icontag'    => 'dt',
'captiontag' => 'dd',
'columns'    => 3,
'size'       => 'thumbnail'
`

[slideshow] - Available attributes and defaults:

`
'id' => $post->ID,
'size' => 'large',
'show' => 'selected',
'showthumbs' => false,
'linkto' => 'large',
'pager' => false,
'per_page' => 12,
'speed' => 2000,
'pause' => 0,
'delay' => 1000
`
= No images are showing in my slideshow? =

By default, only images which have been selected by using the drop down in the media details 'Show in Slideshow' are included. Alternatively, include the attribute 'show=all' in your shortcode to show all attached images.

= How can I add a slideshow in my template? =
Here's an example:
`
if( class_exists( 'HighCycle' ) ) {
	$show = array(
		'size' => 'home-slide',
		'pager' => false,
		'linkto' => 'url',
		'speed' => 3000,
		'timeout' => 1000
	);
	echo $HighCycle->highcycle_show( $show );
}
`
= It doesn't look the way I want, how can I style the galleries and slideshows? =
Look at the markup and apply CSS as needed. Future release will include more included styling.