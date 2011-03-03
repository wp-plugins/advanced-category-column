=== Category Column ===
Contributors: tepelstreel
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BC9QUKBEZFZFY
Tags: column, sidebar, widget, category, newspaper, image, multi widget
Requires at least: 2.7
Tested up to: 3.2
Stable tag: 1.0

The Advanced Category Column is a very customizable multiwidget for your sidebar.

== Description ==

The Advanced Category Column is mainly designed to give your blog a bit more of a newspaper behaviour. E.g. The plugin shows the latest posts from all categories with an offset of three posts on your homepage.

If there is a post thumbnail, it will be displayed above the headline of the post. No further text will appear. If there is no thumbnail, only the headline and the excerpt of the post will be shown. When the plugin can detect neither the thumbnail nor the excerpt of a post, it will display just the first couple of sentences (or words) of a post.

So far that is the same as my Category Column Plugin does also. Not every theme has the possibility to hide certain sidebars on different pages. That's where the advanced of our plugin comes in. In the ACC you can determine, where excactly the widget is showing and in the settings you can customize the links of your widget(s).

The ACC was tested up to WP 3.2. It should work with versions down to 2.7 but was never tested on those.

== Installation ==

1. Upload the `advanced-category-column` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place and customize your widgets
4. Customize your links in the settings

== Frequently Asked Questions ==

= I styled the widget container myself and it looks bad. What do I do? =

The styling of the widget requires some knowledge of css. If you are not familiar with that, try adding

`padding: 10px;
margin-bottom: 10px;`
 
to the style section.

= My widget should have rounded corners, how do I do that? =

Add something like

`-webkit-border-top-left-radius: 5px;
-webkit-border-top-right-radius: 5px;
-moz-border-radius-topleft: 5px;
-moz-border-radius-topright: 5px;
border-top-left-radius: 5px;
border-top-right-radius: 5px;`
 
to the widget style. This is not supported by all browsers yet, but should work in almost all of them.

= My widget should have a shadow, how do I do that? =

Add something like

`-moz-box-shadow: 10px 10px 5px #888888;
-webkit-box-shadow: 10px 10px 5px #888888;
box-shadow: 10px 10px 5px #888888;`
 
to the widget style to get a nice shadow down right of the container. This is not supported by all browsers yet, but should work in almost all of them.

= I styled the links of the widget differently, but the changes don't show, what now? =

Most of the time you will have to use the styles like that:

'font-weight: bold !important;
color: #0000dd !important;'

Since the stylesheet of the theme will have highest priority, you will have to make your styles even more important in the hirarchy.

== Screenshots ==

1. The plugin's work on our homepage
2. The widget's settings section
3. The plugin's settings section

== Changelog ==

= 1.0 =
* Stable version with Dutch and German language files

= 0.5 =
* Public Beta and initial release

= 0.3 =
* Open Beta

== Upgrade Notice ==

= 1.0 =
Stable and clean version