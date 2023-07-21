=== Ads for Genesis ===
Contributors: advancedads, webzunft
Tags: genesis, adsense, ads, ad, amazon, adverts, advertisement, banners, rotation, studiopress, genesis framework
Requires at least: 4.2
Tested up to: 6.2
Stable tag: 1.0.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Ads for Genesis allows you to display any kind of ads without coding in your Genesis theme.

== Description ==

[Genesis Ads](https://wpadvancedads.com/add-ons/genesis-ads/) enables you to easily display any kind of advertisement – including Google AdSense and Amazon – in various positions in your Genesis based theme.

In order to use Ads for Genesis, you also need

* a theme based on the Genesis Framework built by StudioPress,
* the free ad management plugin [Advanced Ads](https://wordpress.org/plugins/advanced-ads/).

This combination with Advanced Ads allows you to inject adverts like Google AdSense, Amazon, images banners, etc. managed by Advanced Ads into the predefined positions (called “action hooks“ many Genesis based themes have included in the frontend) and to make use from features like ad rotation.
Please be aware that your theme author might have left some of them out. Also, the styling of the content added by these hooks depends on your theme. Please contact your theme developer about styling issues.

= Positions for ads =

There are currently 51 positions you can target with Genesis Ads. Among them are:

* a couple of positions around and within the header – the part where the site’s title and description and maybe also a navigation and a search form are located
* around the main content and sidebar wrappers
* before, within and after the main and the alternative sidebar
* around the loop – the part where the posts and pages gets loaded, but not within the actual content itself
* plenty of positions around the content. You can even specify positions around the content title here
* around the comments, pings (websites that link to your post) and the comment form
* before, within and after your site’s footer

Keep in mind that content positions might be repeated on overview pages like home or category pages and the ad might be injected multiple times per page.

= Usage =

Make sure that you have your Genesis-based theme and the basic version of Advanced Ads activated.

Go to your dashboard and navigate to _Advanced Ads > Placements_.

When creating a new placement, select the one with the G in the symbol.

After saving the new placement you find it with additional options in the placement list. Here you can specify or change the ad and select the position (see below) from the list.

There are currently 51 placements in 7 logical groups. You can find extended information on them in the [Genesis Hook Reference](https://my.studiopress.com/docs/hook-reference) (Genesis login needed).

The positions are mostly self-explanatory. When you check the reference against the positions in the Genesis placement then you should keep in mind that I removed the genesis_  prefix.

== Installation ==

The Ads for Genesis plugin extends the free Advanced Ads plugin, a simple and powerful ad management solution for WordPress. Before using this plugin download, install and activate Advanced Ads for free from https://wordpress.org/plugins/advanced-ads/.
You can use Advanced Ads along any other ad management plugins like Ad Inserter or WP QUADS and don’t need to switch completely.

== Screenshots ==

1. Genesis placement to place a certain ad or ad group
2. Choose the position based on hooks which your Genesis theme provides

== Changelog ==

= 1.0.8 =

* Fix: update plugin description

= 1.0.7 =

- updated placement UI according to the current Advanced Ads layout
- updated backend links to use HTTPS

= 1.0.6 =

- added compatibility with the new placement order in Advanced Ads

= 1.0.5 =

* updated link

= 1.0.4 =

* fixed more text domains

= 1.0.3 =

* fixed text domain
* updated translation files

= 1.0.2 =

* added Spanish translation

= 1.0.1 =

* fixed broken hooks

= 1.0.0 =

* first plugin version
