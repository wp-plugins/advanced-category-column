<?php

/**
 *
 * Class ACC Widget
 *
 * @ Advanced Category Column
 *
 * building the actual widget
 *
 */
class Advanced_Category_Column_Widget extends WP_Widget {
 
function Advanced_Category_Column_Widget() {

	global $acc_language_file;
	
	$widget_opts = array( 'description' => __('Configure the output and looks of the widget. Then display thumbnails and excerpts of posts in your sidebars and define, on what kind of pages they will show.', $acc_language_file) );
	$control_opts = array( 'width' => 400 );
	
	parent::WP_Widget(false, $name = 'Advanced Category Column', $widget_opts, $control_opts);

}
 
function form($instance) {
	
	global $acc_language_file;
	
	// setup some default settings
    
	$defaults = array( 'postcount' => 5, 'offset' => 3, 'home' => true, 'wordcount' => 3, 'line' => 1, 'line_color' => '#dddddd', 'homepage' => true, 'category' => true );
    
	$instance = wp_parse_args( (array) $instance, $defaults );
	
	$title = esc_attr($instance['title']);
	$postcount = esc_attr($instance['postcount']);
	$offset = esc_attr($instance['offset']);
	$home = esc_attr($instance['home']);
	$list = esc_attr($instance['list']);
	$wordcount = esc_attr($instance['wordcount']);
	$linespace = esc_attr($instance['linespace']);
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
 <?php _e('Title:', $acc_language_file); ?>
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
 <?php _e('How many posts will be displayed in the sidebar:', $acc_language_file); ?>
 <input size="4" id="<?php echo $this->get_field_id('postcount'); ?>" name="<?php echo $this->get_field_name('postcount'); ?>" type="text" value="<?php echo $postcount; ?>" />
 </label>
</p>
<p>
 <label for="<?php echo $this->get_field_id('offset'); ?>">
 <?php _e('Offset (how many posts are spared out in the beginning):', $acc_language_file); ?>
 <input size="4" id="<?php echo $this->get_field_id('offset'); ?>" name="<?php echo $this->get_field_name('offset'); ?>" type="text" value="<?php echo $offset; ?>" />
 </label>
</p>
<p>
 <label for="<?php echo $this->get_field_id('home'); ?>">
 <input id="<?php echo $this->get_field_id('home'); ?>" name="<?php echo $this->get_field_name('home'); ?>" <?php if(!empty($home)) echo 'checked="checked"'; ?> type="checkbox" />&nbsp;<?php _e('Check to have the offset only on your homepage.', $acc_language_file); ?>
 </label>
</p>
<p>
 <label for="<?php echo $this->get_field_id('wordcount'); ?>">
 <?php _e('In case there is no excerpt defined, how many sentences are displayed:', $acc_language_file); ?>
 <input size="4" id="<?php echo $this->get_field_id('wordcount'); ?>" name="<?php echo $this->get_field_name('wordcount'); ?>" type="text" value="<?php echo $wordcount; ?>" />
 </label>
</p>
<p>
 <label for="<?php echo $this->get_field_id('linespace'); ?>">
 <input id="<?php echo $this->get_field_id('linespace'); ?>" name="<?php echo $this->get_field_name('linespace'); ?>" <?php if(!empty($linespace)) echo 'checked="checked"'; ?> type="checkbox" />&nbsp;<?php _e('Check to have each sentense in a new line.', $acc_language_file); ?>
 </label>
</p>
<p>
 <label for="<?php echo $this->get_field_id('words'); ?>">
 <input id="<?php echo $this->get_field_id('words'); ?>" name="<?php echo $this->get_field_name('words'); ?>" <?php if(!empty($words)) echo 'checked="checked"'; ?> type="checkbox" />&nbsp;<?php _e('Check to display words instead of sentenses.', $acc_language_file); ?>
 </label>
</p>
<p>
 <label for="<?php echo $this->get_field_id('line'); ?>">
 <?php _e('If you want a line between the posts, this is the height in px (if not wanting a line, leave emtpy):', $acc_language_file); ?>
 <input size="4" id="<?php echo $this->get_field_id('line'); ?>" name="<?php echo $this->get_field_name('line'); ?>" type="text" value="<?php echo $line; ?>" />
 </label>
</p>
<p>
 <label for="<?php echo $this->get_field_id('line_color'); ?>">
 <?php _e('The color of the line (e.g. #cccccc):', $acc_language_file); ?>
 <input size="13" id="<?php echo $this->get_field_id('line_color'); ?>" name="<?php echo $this->get_field_name('line_color'); ?>" type="text" value="<?php echo $line_color; ?>" />
 </label>
</p>
<p>
  <?php _e('Check, where you want to show the widget. By default, it is showing on the homepage and the category pages:', $acc_language_file); ?>
</p>
<fieldset>
<p>
  <label for="<?php echo $this->get_field_id('homepage'); ?>">
    <input id="<?php echo $this->get_field_id('homepage'); ?>" name="<?php echo $this->get_field_name('homepage'); ?>" <?php if(!empty($homepage)) echo 'checked="checked"'; ?> type="checkbox" />&nbsp;<?php _e('Homepage', $acc_language_file); ?>
  </label><br />
  <label for="<?php echo $this->get_field_id('frontpage'); ?>">
    <input id="<?php echo $this->get_field_id('frontpage'); ?>" name="<?php echo $this->get_field_name('frontpage'); ?>" <?php if(!empty($frontpage)) echo 'checked="checked"'; ?> type="checkbox" />&nbsp;<?php _e('Frontpage (e.g. a static page as homepage)', $acc_language_file); ?>
  </label><br />
  <label for="<?php echo $this->get_field_id('page'); ?>">
    <input id="<?php echo $this->get_field_id('page'); ?>" name="<?php echo $this->get_field_name('page'); ?>" <?php if(!empty($page)) echo 'checked="checked"'; ?> type="checkbox" />&nbsp;<?php _e('&#34;Page&#34; pages', $acc_language_file); ?>
  </label><br />
  <label for="<?php echo $this->get_field_id('category'); ?>">
    <input id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" <?php if(!empty($category)) echo 'checked="checked"'; ?> type="checkbox" />&nbsp;<?php _e('Category pages', $acc_language_file); ?>
  </label><br />
  <label for="<?php echo $this->get_field_id('single'); ?>">
    <input id="<?php echo $this->get_field_id('single'); ?>" name="<?php echo $this->get_field_name('single'); ?>" <?php if(!empty($single)) echo 'checked="checked"'; ?> type="checkbox" />&nbsp;<?php _e('Single post pages', $acc_language_file); ?>
  </label><br />
  <label for="<?php echo $this->get_field_id('date'); ?>">
    <input id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" <?php if(!empty($date)) echo 'checked="checked"'; ?> type="checkbox" />&nbsp;<?php _e('Archive pages', $acc_language_file); ?>
  </label><br />
  <label for="<?php echo $this->get_field_id('tag'); ?>">
    <input id="<?php echo $this->get_field_id('tag'); ?>" name="<?php echo $this->get_field_name('tag'); ?>" <?php if(!empty($tag)) echo 'checked="checked"'; ?> type="checkbox" />&nbsp;<?php _e('Tag pages', $acc_language_file); ?>
  </label><br />
  <label for="<?php echo $this->get_field_id('attachment'); ?>">
    <input id="<?php echo $this->get_field_id('attachment'); ?>" name="<?php echo $this->get_field_name('attachment'); ?>" <?php if(!empty($attachment)) echo 'checked="checked"'; ?> type="checkbox" />&nbsp;<?php _e('Attachments', $acc_language_file); ?>
  </label><br />
  <label for="<?php echo $this->get_field_id('taxonomy'); ?>">
    <input id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>" <?php if(!empty($taxonomy)) echo 'checked="checked"'; ?> type="checkbox" />&nbsp;<?php _e('Custom Taxonomy pages (only available, if having a plugin)', $acc_language_file); ?>
  </label><br />
  <label for="<?php echo $this->get_field_id('author'); ?>">
    <input id="<?php echo $this->get_field_id('author'); ?>" name="<?php echo $this->get_field_name('author'); ?>" <?php if(!empty($author)) echo 'checked="checked"'; ?> type="checkbox" />&nbsp;<?php _e('Author pages', $acc_language_file); ?>
  </label><br />
  <label for="<?php echo $this->get_field_id('search'); ?>">
    <input id="<?php echo $this->get_field_id('search'); ?>" name="<?php echo $this->get_field_name('search'); ?>" <?php if(!empty($search)) echo 'checked="checked"'; ?> type="checkbox" />&nbsp;<?php _e('Search Results', $acc_language_file); ?>
  </label><br />
  <label for="<?php echo $this->get_field_id('not_found'); ?>">
    <input id="<?php echo $this->get_field_id('not_found'); ?>" name="<?php echo $this->get_field_name('not_found'); ?>" <?php if(!empty($not_found)) echo 'checked="checked"'; ?> type="checkbox" />&nbsp;<?php _e('&#34;Not Found&#34;', $acc_language_file); ?>
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('checkall'); ?>">
    <input id="<?php echo $this->get_field_id('checkall'); ?>" name="checkall" type="checkbox" />&nbsp;<?php _e('Check all', $acc_language_file); ?>
  </label>
</p>    
</fieldset>
<p>
 <label for="<?php echo $this->get_field_id('style'); ?>">
 <?php _e('Here you can finally style the widget. Simply type something like<br /><strong>border-left: 1px dashed;<br />border-color: #000000;</strong><br />to get just a dashed black line on the left. If you leave that section empty, your theme will style the widget.', $acc_language_file); ?>
 <textarea class="widefat expand<?php echo $style_height; ?>-1000" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>"><?php echo $style; ?></textarea>
 </label>
</p>
<script type="text/javascript"><!--
jQuery(document).ready(function() {
	jQuery("#<?php echo $this->get_field_id('style'); ?>").autoResize();
});
--></script>
<?php
} // form
 

function update($new_instance, $old_instance) {
	 
	 $instance = $old_instance;
	 
	 $instance['title'] = strip_tags($new_instance['title']);
	 $instance['postcount'] = strip_tags($new_instance['postcount']);
	 $instance['offset'] = strip_tags($new_instance['offset']);
	 $instance['home'] = strip_tags($new_instance['home']);
	 $instance['list'] = strip_tags($new_instance['list']); 
	 $instance['wordcount'] = strip_tags($new_instance['wordcount']);
	 $instance['words'] = strip_tags($new_instance['words']);
	 $instance['linespace'] = strip_tags($new_instance['linespace']);
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
} // update
 
function widget($args, $instance) {
	
global $acc_language_file;
	
// get the type of page, we're actually on

if (is_front_page()) $acc_pagetype='frontpage';
if (is_home()) $acc_pagetype='homepage';
if (is_page()) $acc_pagetype='page';
if (is_category()) $acc_pagetype='category';
if (is_single()) $acc_pagetype='single';
if (is_date()) $acc_pagetype='date';
if (is_tag()) $acc_pagetype='tag';
if (is_attachment()) $acc_pagetype='attachment';
if (is_tax()) $acc_pagetype='taxonomy';
if (is_author()) $acc_pagetype='author';
if (is_search()) $acc_pagetype='search';
if (is_404()) $acc_pagetype='not_found';

// display only, if said so in the settings of the widget

if ($instance[$acc_pagetype]) :
	
	// the widget is displayed
	
	extract( $args );
	
	$title = apply_filters('widget_title', $instance['title']);	
	
	
	if (empty($instance['style'])) :
		
		$acc_before_widget=$before_widget;
		$acc_after_widget=$after_widget;
	
	else :
		
		$acc_style=str_replace(array("\r\n", "\n", "\r"), '', $instance['style']);
		
		$acc_before_widget='<div id="'.$widget_id.'" style="'.$acc_style.'">';
		$acc_after_widget='</div>';
		
	endif;
	
	echo $acc_before_widget;
	
	if ( $title ) echo $before_title . $title . $after_title;
 
/* This is the actual function of the plugin, it fills the sidebar with the customized excerpts */

$i=1;

$acc_options = get_option('acc_options');

if (count ($acc_options)!=0 && !empty ($acc_options)) $acc_class=' class="acclink"';

$acc_setup='numberposts='.$instance['postcount'];

if (is_home() || empty($instance['home'])) :
	
	global $wp_query;
	
	$acc_page = $wp_query->get( 'paged' );
	
	$acc_numberposts = $wp_query->get( 'posts_per_page' );
	
	$acc_offset = (empty($acc_page)) ? $acc_offset=$instance['offset'] : $acc_offset=(($acc_page-1)*$acc_numberposts)+$instance['offset'];
	
	$acc_setup.='&offset='.$acc_offset;

endif;

if (is_category() && !$instance['list']) $acc_cat=get_query_var('cat');

if ($instance['list'] || $acc_cat) $acc_setup.='&cat='.$instance['list'].',-'.$acc_cat;

if (is_single()) :
	
	global $wp_query;
	
	$acc_setup.='&exclude='.$wp_query->get_queried_object_id(); 

endif;

global $post;

$acc_posts = get_posts($acc_setup);

foreach($acc_posts as $post) :
 
 	$imagetags = new A5_ImageTags;
	
	$acc_tags = $imagetags->get_tags($post, $acc_language_file);
	
	$acc_image_alt = $acc_tags['image_alt'];
	$acc_image_title = $acc_tags['image_title'];
	$acc_title_tag = $acc_tags['title_tag'];
	
	if (function_exists('has_post_thumbnail') && has_post_thumbnail()) :
	   
	/* If there is a thumbnail, show thumbnail and headline */
	   
	?>
	<a href="<?php the_permalink(); ?>">
	<?php the_post_thumbnail(); ?>
	</a><br />
	<p><a href="<?php the_permalink(); ?>"<?php echo $acc_class; ?> title="<?php echo $acc_title_tag ?>">
	<?php the_title(); ?>
	</a></p>
	<?php

	else :
	
		$args = array (
		'content' => $post->post_content,
		'width' => get_option('thumbnail_size_w'),
		'height' => get_option('thumbnail_size_h')
		);
		   
		$acc_image = new A5_Thumbnail;
	
	   	$acc_image_info = $acc_image->get_thumbnail($args);
		
		$acc_thumb = esc_attr($acc_image_info['thumb']);
		
		$acc_width = $acc_image_info['thumb_width'];

		$acc_height = $acc_image_info['thumb_height'];

		if (!empty($acc_thumb)) :
		
			if ($acc_width) $acc_img = '<img title="'.$acc_image_title.'" src="'.$acc_thumb.'" alt="'.$acc_image_alt.'" width="'.$acc_width.'" height="'.$acc_height.'" />';
				
			else $acc_img = '<img title="'.$acc_image_title.'" src="'.$acc_thumb.'" alt="'.$acc_image_alt.'" style="maxwidth: '.get_option('thumbnail_size_w').'; maxheight: '.get_option('thumbnail_size_h').';" />'
			
			?>
			<a href="<?php the_permalink(); ?>"> <?php echo $acc_img; ?></a>
            <div style="clear:both;"></div>
			<p><a href="<?php the_permalink(); ?>"<?php echo $acc_class; ?> title="<?php echo $acc_title_tag ?>"><?php the_title(); ?></a></p>
			<?php
			
		else : 
			
			/* If there is no picture, show headline and excerpt of the post */
			
			?>
			<p><a href="<?php the_permalink(); ?>"<?php echo $acc_class; ?> title="<?php echo $acc_title_tag ?>">
			<?php the_title(); ?>
			</a></p>
			<?php
			
			/* in case the excerpt is not definded by theme or anything else, the first x sentences of the content are given */
			
			$type = (empty($instance['words'])) ? 'sentenses' : 'words';
				
			$args = array(
			'excerpt' => $post->post_excerpt,
			'content' => $post->post_content,
			'type' => $type,
			'count' => $instance['wordcount'],
			'linespace' => $instance['linespace']
			);
	
			$acc_excerpt = new A5_Excerpt;
			
			$acc_text = $acc_excerpt->get_excerpt($args);

			echo '<p>'.$acc_text.'</p>';
		
		endif;
		
	endif;
	   
	if (!empty($instance['line']) && $i <  $instance['postcount']) :
		
		echo '<hr style="color: '.$instance['line_color'].'; background-color: '.$instance['line_color'].'; height: '.$instance['line'].'px;" />';
		
		$i++;
		
	endif;
	
endforeach;

 
echo $acc_after_widget;
 
endif;

} // widget
 
} // class

add_action('widgets_init', create_function('', 'return register_widget("Advanced_Category_Column_Widget");'));

?>