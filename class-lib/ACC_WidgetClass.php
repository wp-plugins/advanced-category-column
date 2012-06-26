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
	
	static $language_file = 'advanced-cc';
 
function Advanced_Category_Column_Widget() {

	$widget_opts = array( 'description' => __('Configure the output and looks of the widget. Then display thumbnails and excerpts of posts in your sidebars and define, on what kind of pages they will show.', self::$language_file) );
	$control_opts = array( 'width' => 400 );
	
	parent::WP_Widget(false, $name = 'Advanced Category Column', $widget_opts, $control_opts);

}
 
function form($instance) {
	
	// setup some default settings
    
	$defaults = array( 'postcount' => 5, 'offset' => 3, 'home' => 1, 'wordcount' => 3, 'line' => 1, 'line_color' => '#dddddd', 'homepage' => 1, 'category' => 1 );
    
	$instance = wp_parse_args( (array) $instance, $defaults );
	
	$title = esc_attr($instance['title']);
	$postcount = esc_attr($instance['postcount']);
	$offset = esc_attr($instance['offset']);
	$home = esc_attr($instance['home']);
	$list = esc_attr($instance['list']);
	$wordcount = esc_attr($instance['wordcount']);
	$linespace = esc_attr($instance['linespace']);
	$words = esc_attr($instance['words']);
	$adsense = esc_attr($instance['adsense']);
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
	
	$base_id = 'widget-'.$this->id_base.'-'.$this->number.'-';
	$base_name = 'widget-'.$this->id_base.'['.$this->number.']';
	
	$options = array (array('homepage', $homepage, __('Homepage', self::$language_file)), array('frontpage', $frontpage, __('Frontpage (e.g. a static page as homepage)', self::$language_file)), array('page', $page, __('&#34;Page&#34; pages', self::$language_file)), array('category', $category, __('Category pages', self::$language_file)), array('single', $single, __('Single post pages', self::$language_file)), array('date', $date, __('Archive pages', self::$language_file)), array('tag', $tag, __('Tag pages', self::$language_file)), array('attachment', $attachment, __('Attachments', self::$language_file)), array('taxonomy', $taxonomy, __('Custom Taxonomy pages (only available, if having a plugin)', self::$language_file)), array('author', $author, __('Author pages', self::$language_file)), array('search', $search, __('Search Results', self::$language_file)), array('not_found', $not_found, __('&#34;Not Found&#34;', self::$language_file)));	
	
	$field[] = array ('type' => 'text', 'id_base' => $base_id, 'name_base' => $base_name, 'field_name' => 'title', 'label' => __('Title:', self::$language_file), 'value' => $title, 'class' => 'widefat', 'space' => 1);
	$field[] = array ('type' => 'text', 'id_base' => $base_id, 'name_base' => $base_name, 'field_name' => 'list', 'label' => sprintf(__('To exclude certain categories or to show just a special category, simply write their ID&#39;s separated by comma (e.g. %s-5, 2, 4%s will show categories 2 and 4 and will exclude category 5):', self::$language_file), '<strong>', '</strong>'), 'value' => $list, 'space' => 1);
	$field[] = array ('type' => 'number', 'id_base' => $base_id, 'name_base' => $base_name, 'field_name' => 'postcount', 'label' => __('How many posts will be displayed in the sidebar:', self::$language_file), 'value' => $postcount, 'size' => 4, 'step' => 1, 'space' => 1);
	$field[] = array ('type' => 'number', 'id_base' => $base_id, 'name_base' => $base_name, 'field_name' => 'offset', 'label' => __('Offset (how many posts are spared out in the beginning):', self::$language_file), 'value' => $offset, 'size' => 4, 'step' => 1, 'space' => 1);
	$field[] = array ('type' => 'checkbox', 'id_base' => $base_id, 'name_base' => $base_name, 'field_name' => 'home', 'label' => __('Check to have the offset only on your homepage.', self::$language_file), 'value' => $home, 'space' => 1);	
	$field[] = array ('type' => 'number', 'id_base' => $base_id, 'name_base' => $base_name, 'field_name' => 'wordcount', 'label' => __('In case there is no excerpt defined, how many sentences are displayed:', self::$language_file), 'value' => $wordcount, 'size' => 4, 'step' => 1, 'space' => 1);
	$field[] = array ('type' => 'checkbox', 'id_base' => $base_id, 'name_base' => $base_name, 'field_name' => 'linespace', 'label' => __('Check to have each sentence in a new line.', self::$language_file), 'value' => $linespace, 'space' => 1);	
	$field[] = array ('type' => 'checkbox', 'id_base' => $base_id, 'name_base' => $base_name, 'field_name' => 'words', 'label' => __('Check to display words instead of sentences.', self::$language_file), 'value' => $words, 'space' => 1);	
	$field[] = array ('type' => 'number', 'id_base' => $base_id, 'name_base' => $base_name, 'field_name' => 'line', 'label' => __('If you want a line between the posts, this is the height in px (if not wanting a line, leave emtpy):', self::$language_file), 'value' => $line, 'size' => 4, 'step' => 1, 'space' => 1);
	$field[] = array ('type' => 'color', 'id_base' => $base_id, 'name_base' => $base_name, 'field_name' => 'line_color', 'label' => __('The color of the line (e.g. #cccccc):', self::$language_file), 'value' => $line_color, 'size' => 13, 'space' => 1);	

	if (defined('AE_AD_TAGS') && AE_AD_TAGS==1) :
	
	$field[] = array ('type' => 'checkbox', 'id_base' => $base_id, 'name_base' => $base_name, 'field_name' => 'adsense', 'label' => __('Check if you want to invert the Google AdSense Tags that are defined with the Ads Easy Plugin. E.g. when they are turned off for the sidebar, they will appear in the widget.', self::$language_file), 'value' => $adsense, 'space' => 1);
	
	endif;
	
	$field[] = array ('type' => 'checkgroup', 'id_base' => $base_id, 'name_base' => $base_name, 'label' => __('Check, where you want to show the widget. By default, it is showing on the homepage and the category pages:', self::$language_file), 'options' => $options, 'checkall' => __('Check all', self::$language_file));	
	$field[] = array ('type' => 'textarea', 'id_base' => $base_id, 'name_base' => $base_name, 'field_name' => 'style', 'class' => 'widefat', 'label' => sprintf(__('Here you can finally style the widget. Simply type something like%1$s%2$sborder-left: 1px dashed;%2$sborder-color: #000000;%3$s%2$sto get just a dashed black line on the left. If you leave that section empty, your theme will style the widget.', self::$language_file), '<strong>', '<br />', '</strong>'), 'value' => $style, 'space' => 1);
	$field[] = array ('type' => 'resize', 'id_base' => $base_id, 'field_name' => array('style'));

	foreach ($field as $args) $menu_item = new A5_WidgetControlClass($args);

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
	$instance['adsense'] = strip_tags($new_instance['adsense']);
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
	
	// hooking into ads easy for the google tags
	
	if (AE_AD_TAGS == 1 && $instance['adsense']) :
		
		$ae_options = get_option('ae_options');
		
		do_action('google_end_tag');
		
		if ($ae_options['ae_sidebar']==1) do_action('google_ignore_tag');
	
		else do_action('google_start_tag');
		
	endif;
	
	echo $acc_before_widget;
	
	if ( $title ) echo $before_title . $title . $after_title;
 
/* This is the actual function of the widget, it fills the sidebar with the customized posts */

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
	
	$acc_tags = $imagetags->get_tags($post, 'acc_options', self::$language_file);
	
	$acc_image_alt = $acc_tags['image_alt'];
	$acc_image_title = $acc_tags['image_title'];
	$acc_title_tag = $acc_tags['title_tag'];
	
	$eol = "\r\n";
	$acc_headline = '<p>'.$eol.'<a href="'.get_permalink().'"'.$acc_class.' title="'.$acc_title_tag.'">'.get_the_title().'</a>'.$eol.'</p>';
	
	if (function_exists('has_post_thumbnail') && has_post_thumbnail()) :
	   
	/* If there is a thumbnail, show thumbnail and headline */
	   
	echo '<a href="'.get_permalink().'">'.get_the_post_thumbnail().'</a>'.$eol.'<div style="clear: both;"></div>'.$eol.$acc_headline;

	else :
	
		$args = array (
		'content' => $post->post_content,
		'width' => get_option('thumbnail_size_w'),
		'height' => get_option('thumbnail_size_h'),
		'option' => 'acc_options'
		);
		   
		$acc_image = new A5_Thumbnail;
	
	   	$acc_image_info = $acc_image->get_thumbnail($args);
		
		$acc_thumb = $acc_image_info['thumb'];
		
		$acc_width = $acc_image_info['thumb_width'];

		$acc_height = $acc_image_info['thumb_height'];

		if (!empty($acc_thumb)) :
		
			if ($acc_width) $acc_img = '<img title="'.$acc_image_title.'" src="'.$acc_thumb.'" alt="'.$acc_image_alt.'" width="'.$acc_width.'" height="'.$acc_height.'" />';
				
			else $acc_img = '<img title="'.$acc_image_title.'" src="'.$acc_thumb.'" alt="'.$acc_image_alt.'" style="maxwidth: '.get_option('thumbnail_size_w').'; maxheight: '.get_option('thumbnail_size_h').';" />';
			
			?>
			<a href="<?php the_permalink(); ?>"> <?php echo $acc_img; ?></a>
            <div style="clear:both;"></div>
			<p><a href="<?php the_permalink(); ?>"<?php echo $acc_class; ?> title="<?php echo $acc_title_tag ?>"><?php the_title(); ?></a></p>
			<?php
			
		else : 
			
			/* If there is no picture, show headline and excerpt of the post */
			
			echo $acc_headline;
			
			/* in case the excerpt is not definded by theme or anything else, the first x sentences of the content are given */
			
			$type = (empty($instance['words'])) ? 'sentences' : 'words';
				
			$args = array(
			'excerpt' => $post->post_excerpt,
			'content' => $post->post_content,
			'type' => $type,
			'count' => $instance['wordcount'],
			'linespace' => $instance['linespace']
			);
	
			$acc_text = A5_Excerpt::get_excerpt($args);

			echo '<p>'.$acc_text.'</p>';
		
		endif;
		
	endif;
	   
	if (!empty($instance['line']) && $i <  $instance['postcount']) :
		
		echo '<hr style="color: '.$instance['line_color'].'; background-color: '.$instance['line_color'].'; height: '.$instance['line'].'px;" />';
		
		$i++;
		
	endif;
	
endforeach;

echo $acc_after_widget;

	// hooking into ads easy for the google tags
	
	if (AE_AD_TAGS == 1 && $instance['adsense']) :
		
		do_action('google_end_tag');
		
		if ($ae_options['ae_sidebar']==1) do_action('google_start_tag');
	
		else do_action('google_ignore_tag');
		
	endif;
 
endif;

} // widget
 
} // class

add_action('widgets_init', create_function('', 'return register_widget("Advanced_Category_Column_Widget");'));

?>