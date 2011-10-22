<?php
/*
Plugin Name: Advanced Category Column
Plugin URI: http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/advanced-category-column-plugin
Description: The Advanced Category Column does, what my Category Column Plugin does; it creates a widget, which you can drag to your sidebar and it will show excerpts of the posts of other categories than showed in the center-column. It just has more options than the the Category Column Plugin. It is tested with WP up to version 3.3 and it might work with versions down to 2.7, but that will never be explicitly supported for those. The 'Advanced' means, that you have a couple of more options than in the 'Category Column Plugin'. 
Version: 2.0
Author: Waldemar Stoffel
Author URI: http://www.waldemarstoffel.com
License: GPL3
*/

/*  Copyright 2011  Waldemar Stoffel  (email : w-stoffel@gmx.net)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/


/* Stop direct call */

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die("Sorry, you don't have direct access to this page."); }

/* attach JavaScript file for textarea resizing */

function acc_js_sheet() {
	
	wp_enqueue_script('ta-expander-script', plugins_url('ta-expander.js', __FILE__), array('jquery'), '2.0', true);

}

add_action('admin_print_scripts-widgets.php', 'acc_js_sheet');

//Additional links on the plugin page

add_filter('plugin_row_meta', 'acc_register_links',10,2);

function acc_register_links($links, $file) {
	
	$base = plugin_basename(__FILE__);
	if ($file == $base) {
		$links[] = '<a href="options-general.php?page=advanced-cc-settings">'.__('Settings', 'advanced-cc').'</a>';
		$links[] = '<a href="http://wordpress.org/extend/plugins/advanced-category-column/faq/" target="_blank">'.__('FAQ', 'advanced-cc').'</a>';
		$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BC9QUKBEZFZFY" target="_blank">'.__('Donate', 'advanced-cc').'</a>';
	}
	
	return $links;

}

/**
 *
 * Adds Settings to the adminbar
 *
 */
function acc_admin_bar_menu() {
	global $wp_admin_bar;
	
	if ( !is_super_admin() || !is_admin_bar_showing() ) {
	return; 
	
	}
	
	$wp_admin_bar->add_menu( array( 'parent' => 'menu-settings', 'id' => 'acc', 'title' => 'Advanced Category Column', 'href' => admin_url( 'options-general.php?page=advanced-cc-settings' ) ) );
	
	
}
add_action( 'wp_before_admin_bar_render', 'acc_admin_bar_menu');
		
		
// extending the widget class
 
class Advanced_Category_Column_Widget extends WP_Widget {
 
 function Advanced_Category_Column_Widget() {
	 
	 $widget_opts = array( 'description' => __('Configure the output and looks of the widget. Then display thumbnails and excerpts of posts in your sidebars and define, on what kind of pages they will show.', 'advanced-cc') );
	 $control_opts = array( 'width' => 400 );
	 
	 parent::WP_Widget(false, $name = 'Advanced Category Column', $widget_opts, $control_opts);
 }
 
function form($instance) {
	
	// setup some default settings
    
	$defaults = array( 'postcount' => 5, 'offset' => 3, 'home' => true, 'wordcount' => 3, 'line' => 1, 'line_color' => '#dddddd', 'homepage' => true, 'category' => true );
    
	$instance = wp_parse_args( (array) $instance, $defaults );
	
	$title = esc_attr($instance['title']);
	$postcount = esc_attr($instance['postcount']);
	$offset = esc_attr($instance['offset']);
	$home = esc_attr($instance['home']);
	$list = esc_attr($instance['list']);
	$wordcount = esc_attr($instance['wordcount']);
	$words = esc_attr($instance['words']);
	$line=esc_attr($instance['line']);
	$line_color=esc_attr($instance['line_color']);
	$style=esc_attr($instance['style']);
	$homepage=esc_attr($instance['homepage']);
	$frontpage=esc_attr($instance['frontpage']);
	$page=esc_attr($instance['page']);
	$category=esc_attr($instance['category']);
	$single=esc_attr($instance['single']);
	$date=esc_attr($instance['date']);
	$tag=esc_attr($instance['tag']);
	$attachment=esc_attr($instance['attachment']);
	$taxonomy=esc_attr($instance['taxonomy']);
	$author=esc_attr($instance['author']);
	$search=esc_attr($instance['search']);
	$not_found=esc_attr($instance['not_found']);
 
 ?>

<p>
 <label for="<?php echo $this->get_field_id('title'); ?>">
 <?php _e('Title:', 'advanced-cc'); ?>
 <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
 </label>
</p>
<p>
 <label for="<?php echo $this->get_field_id('list'); ?>">
 <?php _e('To exclude certain categories or to show just a special category, simply write their ID&#39;s separated by comma (e.g. <strong>-5,2,4</strong> will show categories 2 and 4 and will exclude category 5):', 'advanced-cc'); ?>
 <input size="20" id="<?php echo $this->get_field_id('list'); ?>" name="<?php echo $this->get_field_name('list'); ?>" type="text" value="<?php echo $list; ?>" />
 </label>
</p>
<p>
 <label for="<?php echo $this->get_field_id('postcount'); ?>">
 <?php _e('How many posts will be displayed in the sidebar:', 'advanced-cc'); ?>
 <input size="4" id="<?php echo $this->get_field_id('postcount'); ?>" name="<?php echo $this->get_field_name('postcount'); ?>" type="text" value="<?php echo $postcount; ?>" />
 </label>
</p>
<p>
 <label for="<?php echo $this->get_field_id('offset'); ?>">
 <?php _e('Offset (how many posts are spared out in the beginning):', 'advanced-cc'); ?>
 <input size="4" id="<?php echo $this->get_field_id('offset'); ?>" name="<?php echo $this->get_field_name('offset'); ?>" type="text" value="<?php echo $offset; ?>" />
 </label>
</p>
<p>
 <label for="<?php echo $this->get_field_id('home'); ?>">
 <input id="<?php echo $this->get_field_id('home'); ?>" name="<?php echo $this->get_field_name('home'); ?>" <?php if(!empty($home)) {echo "checked=\"checked\""; } ?> type="checkbox" />&nbsp;<?php _e('Check to have the offset only on your homepage:', 'advanced-cc'); ?>
 </label>
</p>
<p>
 <label for="<?php echo $this->get_field_id('wordcount'); ?>">
 <?php _e('In case there is no excerpt defined, how many sentences are displayed:', 'advanced-cc'); ?>
 <input size="4" id="<?php echo $this->get_field_id('wordcount'); ?>" name="<?php echo $this->get_field_name('wordcount'); ?>" type="text" value="<?php echo $wordcount; ?>" />
 </label>
</p>
<p>
 <label for="<?php echo $this->get_field_id('words'); ?>">
 <input id="<?php echo $this->get_field_id('words'); ?>" name="<?php echo $this->get_field_name('words'); ?>" <?php if(!empty($words)) {echo "checked=\"checked\""; } ?> type="checkbox" />&nbsp;<?php _e('Check to display words instead of sentences:', 'advanced-cc'); ?>
 </label>
</p>
<p>
 <label for="<?php echo $this->get_field_id('line'); ?>">
 <?php _e('If you want a line between the posts, this is the height in px (if not wanting a line, leave emtpy):', 'advanced-cc'); ?>
 <input size="4" id="<?php echo $this->get_field_id('line'); ?>" name="<?php echo $this->get_field_name('line'); ?>" type="text" value="<?php echo $line; ?>" />
 </label>
</p>
<p>
 <label for="<?php echo $this->get_field_id('line_color'); ?>">
 <?php _e('The color of the line (e.g. #cccccc):', 'advanced-cc'); ?>
 <input size="13" id="<?php echo $this->get_field_id('line_color'); ?>" name="<?php echo $this->get_field_name('line_color'); ?>" type="text" value="<?php echo $line_color; ?>" />
 </label>
</p>
<p>
  <?php _e('Check, where you want to show the widget. By default, it is showing on the homepage and the category pages:', 'advanced-cc'); ?>
</p>
<fieldset>
<p>
  <label for="<?php echo $this->get_field_id('homepage'); ?>">
    <input id="<?php echo $this->get_field_id('homepage'); ?>" name="<?php echo $this->get_field_name('homepage'); ?>" <?php if(!empty($homepage)) {echo "checked=\"checked\""; } ?> type="checkbox" />
    &nbsp;
    <?php _e('Homepage', 'advanced-cc'); ?>
  </label>
  <br />
  <label for="<?php echo $this->get_field_id('frontpage'); ?>">
    <input id="<?php echo $this->get_field_id('frontpage'); ?>" name="<?php echo $this->get_field_name('frontpage'); ?>" <?php if(!empty($frontpage)) {echo "checked=\"checked\""; } ?> type="checkbox" />
    &nbsp;
    <?php _e('Frontpage (e.g. a static page as homepage)', 'advanced-cc'); ?>
  </label>
  <br />
  <label for="<?php echo $this->get_field_id('page'); ?>">
    <input id="<?php echo $this->get_field_id('page'); ?>" name="<?php echo $this->get_field_name('page'); ?>" <?php if(!empty($page)) {echo "checked=\"checked\""; } ?> type="checkbox" />
    &nbsp;
    <?php _e('&#34;Page&#34; pages', 'advanced-cc'); ?>
  </label>
  <br />
  <label for="<?php echo $this->get_field_id('category'); ?>">
    <input id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" <?php if(!empty($category)) {echo "checked=\"checked\""; } ?> type="checkbox" />
    &nbsp;
    <?php _e('Category pages', 'advanced-cc'); ?>
  </label>
  <br />
  <label for="<?php echo $this->get_field_id('single'); ?>">
    <input id="<?php echo $this->get_field_id('single'); ?>" name="<?php echo $this->get_field_name('single'); ?>" <?php if(!empty($single)) {echo "checked=\"checked\""; } ?> type="checkbox" />
    &nbsp;
    <?php _e('Single post pages', 'advanced-cc'); ?>
  </label>
  <br />
  <label for="<?php echo $this->get_field_id('date'); ?>">
    <input id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" <?php if(!empty($date)) {echo "checked=\"checked\""; } ?> type="checkbox" />
    &nbsp;
    <?php _e('Archive pages', 'advanced-cc'); ?>
  </label>
  <br />
  <label for="<?php echo $this->get_field_id('tag'); ?>">
    <input id="<?php echo $this->get_field_id('tag'); ?>" name="<?php echo $this->get_field_name('tag'); ?>" <?php if(!empty($tag)) {echo "checked=\"checked\""; } ?> type="checkbox" />
    &nbsp;
    <?php _e('Tag pages', 'advanced-cc'); ?>
  </label>
  <br />
  <label for="<?php echo $this->get_field_id('attachment'); ?>">
    <input id="<?php echo $this->get_field_id('attachment'); ?>" name="<?php echo $this->get_field_name('attachment'); ?>" <?php if(!empty($attachment)) {echo "checked=\"checked\""; } ?> type="checkbox" />
    &nbsp;
    <?php _e('Attachments', 'advanced-cc'); ?>
  </label>
  <br />
  <label for="<?php echo $this->get_field_id('taxonomy'); ?>">
    <input id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>" <?php if(!empty($taxonomy)) {echo "checked=\"checked\""; } ?> type="checkbox" />
    &nbsp;
    <?php _e('Custom Taxonomy pages (only available, if having a plugin)', 'advanced-cc'); ?>
  </label>
  <br />
  <label for="<?php echo $this->get_field_id('author'); ?>">
    <input id="<?php echo $this->get_field_id('author'); ?>" name="<?php echo $this->get_field_name('author'); ?>" <?php if(!empty($author)) {echo "checked=\"checked\""; } ?> type="checkbox" />
    &nbsp;
    <?php _e('Author pages', 'advanced-cc'); ?>
  </label>
  <br />
  <label for="<?php echo $this->get_field_id('search'); ?>">
    <input id="<?php echo $this->get_field_id('search'); ?>" name="<?php echo $this->get_field_name('search'); ?>" <?php if(!empty($search)) {echo "checked=\"checked\""; } ?> type="checkbox" />
    &nbsp;
    <?php _e('Search Results', 'advanced-cc'); ?>
  </label>
  <br />
  <label for="<?php echo $this->get_field_id('not_found'); ?>">
    <input id="<?php echo $this->get_field_id('not_found'); ?>" name="<?php echo $this->get_field_name('not_found'); ?>" <?php if(!empty($not_found)) {echo "checked=\"checked\""; } ?> type="checkbox" />
    &nbsp;
    <?php _e('&#34;Not Found&#34;', 'advanced-cc'); ?>
  </label>
  <br />
</p>
<p>
  <label for="checkall">
    <input id="checkall" name="checkall" type="checkbox" />
    &nbsp;
    <?php _e('Check all', 'advanced-cc'); ?>
  </label>
</p>    
</fieldset>
<p>
 <label for="<?php echo $this->get_field_id('style'); ?>">
 <?php _e('Here you can finally style the widget. Simply type something like<br /><strong>border-left: 1px dashed;<br />border-color: #000000;</strong><br />to get just a dashed black line on the left. If you leave that section empty, your theme will style the widget.', 'advanced-cc'); ?>
 <textarea class="widefat expand<?php echo $style_height; ?>-1000" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>"><?php echo $style; ?></textarea>
 </label>
</p>
<script type="text/javascript"><!--
jQuery(document).ready(function() {
	jQuery("#<?php echo $this->get_field_id('style'); ?>").autoResize();
});
--></script>
<?php
 }
 

function update($new_instance, $old_instance) {
	 
	 $instance = $old_instance;
	 
	 $instance['title'] = strip_tags($new_instance['title']);
	 $instance['postcount'] = strip_tags($new_instance['postcount']);
	 $instance['offset'] = strip_tags($new_instance['offset']);
	 $instance['home'] = strip_tags($new_instance['home']);
	 $instance['list'] = strip_tags($new_instance['list']); 
	 $instance['wordcount'] = strip_tags($new_instance['wordcount']);
	 $instance['words'] = strip_tags($new_instance['words']);
	 $instance['line'] = strip_tags($new_instance['line']);
	 $instance['line_color'] = strip_tags($new_instance['line_color']);
	 $instance['style'] = strip_tags($new_instance['style']);
	 $instance['homepage'] = strip_tags($new_instance['homepage']);
	 $instance['frontpage'] = strip_tags($new_instance['frontpage']);
	 $instance['page'] = strip_tags($new_instance['page']);
	 $instance['category'] = strip_tags($new_instance['category']);
	 $instance['single'] = strip_tags($new_instance['single']);
	 $instance['date'] = strip_tags($new_instance['date']); 
	 $instance['tag'] = strip_tags($new_instance['tag']);
	 $instance['attachment'] = strip_tags($new_instance['attachment']);
	 $instance['taxonomy'] = strip_tags($new_instance['taxonomy']);
	 $instance['author'] = strip_tags($new_instance['author']);
	 $instance['search'] = strip_tags($new_instance['search']);
	 $instance['not_found'] = strip_tags($new_instance['not_found']);
	 
	 return $instance;
}
 
function widget($args, $instance) {
	
// get the type of page, we're actually on

if (is_front_page()) { $acc_pagetype='frontpage'; }
if (is_home()) { $acc_pagetype='homepage'; }
if (is_page()) { $acc_pagetype='page'; }
if (is_category()) { $acc_pagetype='category'; }
if (is_single()) { $acc_pagetype='single'; }
if (is_date()) { $acc_pagetype='date'; }
if (is_tag()) { $acc_pagetype='tag'; }
if (is_attachment()) { $acc_pagetype='attachment'; }
if (is_tax()) { $acc_pagetype='taxonomy'; }
if (is_author()) { $acc_pagetype='author'; }
if (is_search()) { $acc_pagetype='search'; }
if (is_404()) { $acc_pagetype='not_found'; }

// display only, if said so in the settings of the widget

if ($instance[$acc_pagetype]) {
	
	// the widget is displayed
	
	extract( $args );
	
	$title = apply_filters('widget_title', $instance['title']);	
	
	
	if (empty($instance['style'])) {
		
		$acc_before_widget=$before_widget;
		$acc_after_widget=$after_widget;
		
	}
	
	else {
		
		$acc_style=str_replace(array("\r\n", "\n", "\r"), '', $instance['style']);
		
		$acc_before_widget="<div id=\"".$widget_id."\" style=\"".$acc_style."\">";
		$acc_after_widget="</div>";
		
	}
	
	echo $acc_before_widget;
	
	if ( $title ) {
		
		echo $before_title . $title . $after_title;
		
	}
 
/* This is the actual function of the plugin, it fills the sidebar with the customized excerpts */

$i=1;

$acc_options = get_option('acc_options');

if (count ($acc_options)!=0 && !empty ($acc_options)) {
	
	$acc_class=" class=\"acclink\"";
		
	$acc_stylesheet = WP_PLUGIN_DIR . '/advanced-category-column/advanced-cc.css';	
	$acc_css_content = file_get_contents($acc_stylesheet);
	
	if (empty($acc_css_content)) {
		
		acc_write_stylesheet($acc_options); }
	
}

$acc_setup="numberposts=".$instance['postcount'];

if (is_home() || empty($instance['home'])) {
	
	global $wp_query;
	
	$acc_page = $wp_query->get( 'paged' );
	$acc_numberposts = $wp_query->get( 'posts_per_page' );
	
	if ($acc_page) {
		$acc_offset=(($acc_page-1)*$acc_numberposts)+$instance['offset']; }
		
	else {
		$acc_offset=$instance['offset']; }
	
	$acc_setup.='&offset='.$acc_offset;
}

if (is_category() && !$instance['list']) {
	$acc_cat=get_query_var('cat');
}

if ($instance['list'] || $acc_cat) {
	$acc_setup.='&cat='.$instance['list'].',-'.$acc_cat;
}

if (is_single()) {
	
	global $wp_query;
	
	$acc_setup.='&exclude='.$wp_query->get_queried_object_id(); 

	
}


 global $post;
 $acc_posts = get_posts($acc_setup);
 foreach($acc_posts as $post) :
 
   setup_postdata($post);
   
   if (function_exists('has_post_thumbnail') && has_post_thumbnail()) {
	   
/* If there is a thumbnail, show thumbnail and headline */
	   
	   ?>
<a href="<?php the_permalink(); ?>">
<?php the_post_thumbnail(); ?>
</a>
<p><a href="<?php the_permalink(); ?>"<?php echo $acc_class; ?>>
  <?php the_title(); ?>
  </a></p>
<?php 

}
	   
	   else {
		   
	   
	   $acc_thumb = '';
	   
	   $acc_image = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	   $acc_thumb = $matches [1] [0];
	   
	    if (empty($acc_thumb)) {	   
		   
		   
/* If there is no picture, show headline and excerpt of the post */
		   
	
	?>
<p><a href="<?php the_permalink(); ?>"<?php echo $acc_class; ?>>
  <?php the_title(); ?>
  </a></p>
<?php


	$acc_excerpt=$post->post_excerpt;
	
/* in case the excerpt is not definded by theme or anything else, the first x sentences of the content are given */
	
	if (empty($acc_excerpt)) {
		
		$acc_text=preg_replace('/\[caption(.*?)\[\/caption\]/', '', get_the_content());
		
		if ($instance['words']) {
			
			$acc_short=array_slice(explode(" ", $acc_text), 0, $instance['wordcount']);
			
			$acc_excerpt=implode(" ", $acc_short)." [...]";
			
		}
		
		else {
			
			$acc_short=array_slice(preg_split("/([\t.!?]+)/", $acc_text, -1, PREG_SPLIT_DELIM_CAPTURE), 0, $instance['wordcount']*2);
			
			$acc_excerpt=implode($acc_short);
			
		}
	
	}
	
	echo "<p>".$acc_excerpt."</p>";
	
	   }
	   
	else {
	
		$acc_image_title=$post->post_title;
		$acc_size=getimagesize($acc_thumb);
		
		if (($acc_size[0]/$acc_size[1])>1) {
							   
		$acc_x=150;
		$acc_y=intval($acc_size[1]/($acc_size[0]/$acc_x));
		
		}
		
		else {
										   
		$acc_y=150;
		$acc_x=intval($acc_size[0]/($acc_size[1]/$acc_y));
		
		}
		
		
	   ?>
<a href="<?php the_permalink(); ?>"> <?php echo "<img title=\"".$acc_image_title."\" src=\"".$acc_thumb."\" alt=\"".$acc_image_title."\" width=\"".$acc_x."\" height=\"".$acc_y."\" />"; ?> </a>
<p><a href="<?php the_permalink(); ?>"<?php echo $acc_class; ?>>
  <?php the_title(); ?>
  </a></p>
<?php
	   
	}}
	
	   
	if (!empty($instance['line']) && $i <  $instance['postcount']) {
		
		echo "<hr style=\"color: ".$instance['line_color']."; background-color: ".$instance['line_color']."; height: ".$instance['line']."px;\" />";
		
		$i++;
		
		}
	
	endforeach;

 
 echo $acc_after_widget;
 
 }}
 
}

// writing css to file
	
function acc_write_stylesheet($acc_styles) {
	
	$acc_link=str_replace(array("\r\n", "\n", "\r"), '', $acc_styles['link']);
	$acc_hover=str_replace(array("\r\n", "\n", "\r"), '', $acc_styles['hover']);
	
	$acc_stylesheet = WP_PLUGIN_DIR . '/advanced-category-column/advanced-cc.css';
	
	$acc_link_style=".acclink {".$acc_link."}\r\n.acclink:hover {".$acc_hover."}";
	
	$fp = fopen( $acc_stylesheet, "w" );
	fwrite ($fp, $acc_link_style);
	fclose ($fp);
	
}

add_action('widgets_init', create_function('', 'return register_widget("Advanced_Category_Column_Widget");'));


// import laguage files

load_plugin_textdomain('advanced-cc', false , basename(dirname(__FILE__)).'/languages');

// init

add_action('admin_init', 'acc_init');

function acc_init() {
	
	register_setting( 'acc_options', 'acc_options', 'acc_validate' );
	
	add_settings_section('acc_settings', __('Styling of the links', 'advanced-cc'), 'acc_display_section', 'acc_styles');
	
	add_settings_field('acc_link_style', __('Link style:', 'advanced-cc'), 'acc_link_field', 'acc_styles', 'acc_settings');
	
	add_settings_field('acc_hover_style', __('Hover style:', 'advanced-cc'), 'acc_hover_field', 'acc_styles', 'acc_settings');

}

function acc_display_section() {
	
	echo '<p>'.__('Just put some css code here.', 'advanced-cc').'</p>';

}

function acc_link_field() {
	
	$acc_options = get_option('acc_options');
	
	echo "<textarea id=\"acc_link\" name=\"acc_options[link]\" rows=\"5\" cols=\"35\"  />".$acc_options['link']."</textarea>";
	
}

function acc_hover_field() {
	
	$acc_options = get_option('acc_options');
	
	echo "<textarea id=\"acc_hover\" name=\"acc_options[hover]\" rows=\"5\" cols=\"35\"  />".$acc_options['hover']."</textarea>";
	
}

// Cleaning on deactivation

register_deactivation_hook(  __FILE__, 'unset_acc' );

function unset_acc() {
	
	delete_option('acc_options');
	
}

// Installing options page

add_action('admin_menu', 'acc_admin_menu');

function acc_admin_menu() {
	
	$pages=add_options_page(__('Advanced CC Settings', 'advanced-cc'), 'Advanced Category Column', 'administrator', 'advanced-cc-settings', 'advanced_cc_options_page');
	
	add_action('admin_print_scripts-'.$pages, 'acc_js_sheet');
	
}

// Calling the options page

function advanced_cc_options_page() {
	
	?>
<div>
  <h2><?php _e('Advanced Category Column Settings', 'advanced-cc'); ?></h2>
  <?php _e('Style the links of the widget. If you leave this empty, your theme will style the hyperlinks.', 'advanced-cc'); ?>
  <p>
    <?php _e('Just input something like,', 'advanced-cc'); ?>
  <p><strong>font-weight: bold;<br />
    color: #0000ff;<br />
    text-decoration: underline;</strong></p>
  <?php _e('to get fat, blue, underlined links.', 'advanced-cc'); ?>
  </p>
  <p><strong>
    <?php _e('You most probably have to use &#34;!important&#34; at the end of each line, to make it work.', 'advanced-cc'); ?>
    </strong></p>
  <form action="options.php" method="post">
    <?php
    
	settings_fields('acc_options');
	do_settings_sections('acc_styles');
	
	?>
    <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
  </form>
</div>
<script type="text/javascript"><!--
jQuery(document).ready(function() {
	jQuery("textarea").autoResize();
});
--></script>
<?php
}

function acc_validate($input) {
	
	$newinput['link']=trim($input['link']);
	$newinput['hover']=trim($input['hover']);
	
	acc_write_stylesheet($newinput);
	
	return $newinput;

}

add_action ('wp_print_styles', 'acc_css');

function acc_css () {
	
	$acc_options = get_option('acc_options');
	
	if (count ($acc_options)!=0 && !empty ($acc_options)) {
		
        $acc_css_Url = WP_PLUGIN_URL . '/advanced-category-column/advanced-cc.css';
        $acc_css_File = WP_PLUGIN_DIR . '/advanced-category-column/advanced-cc.css';
        if ( file_exists($acc_css_File) ) {
            wp_register_style('advanced-cc', $acc_css_Url);
            wp_enqueue_style( 'advanced-cc');
			
		}
		
	}
	
}

?>
