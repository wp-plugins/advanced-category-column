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
class Advanced_Category_Column_Widget extends A5_Widget {
	
	private static $options;
 
	function __construct() {
	
		$widget_opts = array( 'description' => __('Configure the output and looks of the widget. Then display thumbnails and excerpts of posts in your sidebars and define, on what kind of pages they will show.', 'advanced-cc') );
		$control_opts = array( 'width' => 400 );
		
		parent::__construct(false, $name = 'Advanced Category Column', $widget_opts, $control_opts);
		
		self::$options = get_option('acc_options');
	
	}
	 
	function form($instance) {
		
		// setup some default settings
		
		$defaults = array(
			'title' => NULL,
			'postcount' => 5,
			'offset' => 3,
			'home' => 1,
			'list' => NULL,
			'showcat' => NULL,
			'showcat_txt' => NULL,
			'wordcount' => 3,
			'linespace' => NULL,
			'width' => get_option('thumbnail_size_w'),
			'words' => NULL,
			'line' => 1,
			'line_color' => '#dddddd',
			'style' => NULL,
			'homepage' => 1,
			'frontpage' => NULL,
			'page' => NULL,
			'category' => 1,
			'single' => NULL,
			'date' => NULL,
			'archive' => NULL,
			'tag' => NULL,
			'attachment' => NULL,
			'taxonomy' => NULL,
			'author' => NULL,
			'search' => NULL,
			'not_found' => NULL,
			'login_page' => NULL,
			'h' => 3,
			'imgborder' => NULL,
			'filter' => NULL
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = esc_attr($instance['title']);
		$postcount = esc_attr($instance['postcount']);
		$offset = esc_attr($instance['offset']);
		$home = esc_attr($instance['home']);
		$list = esc_attr($instance['list']);
		$showcat = esc_attr($instance['showcat']);
		$showcat_txt = esc_attr($instance['showcat_txt']);
		$wordcount = esc_attr($instance['wordcount']);
		$linespace = esc_attr($instance['linespace']);
		$width = esc_attr($instance['width']);
		$words = esc_attr($instance['words']);
		$line=esc_attr($instance['line']);
		$line_color=esc_attr($instance['line_color']);
		$style=esc_attr($instance['style']);
		$homepage = $instance['homepage'];
		$frontpage = $instance['frontpage'];
		$page = $instance['page'];
		$category = $instance['category'];
		$single = $instance['single'];
		$date = $instance['date'];
		$archive = $instance['archive'];
		$tag = $instance['tag'];
		$attachment = $instance['attachment'];
		$taxonomy = $instance['taxonomy'];
		$author = $instance['author'];
		$search = $instance['search'];
		$not_found = $instance['not_found'];
		$login_page = $instance['login_page'];
		$h = esc_attr($instance['h']);
		$filter = esc_attr($instance['filter']);
		$imgborder=esc_attr($instance['imgborder']);
		
		$base_id = 'widget-'.$this->id_base.'-'.$this->number.'-';
		$base_name = 'widget-'.$this->id_base.'['.$this->number.']';
		
		a5_text_field($base_id.'title', $base_name.'[title]', $title, __('Title:', 'advanced-cc'), array('space' => true, 'class' => 'widefat'));
		a5_text_field($base_id.'list', $base_name.'[list]', $list, sprintf(__('To exclude certain categories or to show just a special category, simply write their ID&#39;s separated by comma (e.g. %s-5, 2, 4%s will show categories 2 and 4 and will exclude category 5):', 'advanced-cc'), '<strong>', '</strong>'), array('space' => true, 'class' => 'widefat'));
		a5_checkbox($base_id.'showcat', $base_name.'[showcat]', $showcat, __('Check to show the categories in which the post is filed.', 'advanced-cc'), array('space' => true));
		a5_text_field($base_id.'showcat_txt', $base_name.'[showcat_txt]', $showcat_txt, __('Give some text that you want in front of the post&#39;s categtories (i.e &#39;filed under&#39;:', 'advanced-cc'), array('space' => true, 'class' => 'widefat'));
		a5_number_field($base_id.'postcount', $base_name.'[postcount]', $postcount, __('How many posts will be displayed in the sidebar:', 'advanced-cc'), array('space' => true, 'size' => 4, 'step' => 1));
		a5_number_field($base_id.'offset', $base_name.'[offset]', $offset, __('Offset (how many posts are spared out in the beginning):', 'advanced-cc'), array('space' => true, 'size' => 4, 'step' => 1));
		a5_checkbox($base_id.'home', $base_name.'[home]', $home, __('Check to have the offset only on your homepage.', 'advanced-cc'), array('space' => true));
		a5_number_field($base_id.'width', $base_name.'[width]', $width, __('Width of the thumbnail (in px):', 'advanced-cc'), array('space' => true, 'size' => 4, 'step' => 1));
		a5_text_field($base_id.'imgborder', $base_name.'[imgborder]', $imgborder, sprintf(__('If wanting a border around the image, write the style here. %s would make it a black border, 1px wide.', 'advanced-cc'), '<strong>1px solid #000000</strong>'), array('space' => true, 'class' => 'widefat'));
		parent::select_heading($instance);
		a5_number_field($base_id.'wordcount', $base_name.'[wordcount]', $wordcount, __('In case there is no excerpt defined, how many sentences are displayed:', 'advanced-cc'), array('space' => true, 'size' => 4, 'step' => 1));
		a5_checkbox($base_id.'words', $base_name.'[words]', $words, __('Check to display words instead of sentences.', 'advanced-cc'), array('space' => true));
		a5_checkbox($base_id.'linespace', $base_name.'[linespace]', $linespace, __('Check to have each sentense in a new line.', 'advanced-cc'), array('space' => true));
		a5_checkbox($base_id.'filter', $base_name.'[filter]', $filter, __('Check to return the excerpt unfiltered (might avoid interferences with other plugins).', 'advanced-cc'), array('space' => true));
		a5_number_field($base_id.'line', $base_name.'[line]', $line, __('If you want a line between the posts, this is the height in px (if not wanting a line, leave emtpy):', 'advanced-cc'), array('space' => true, 'size' => 4, 'step' => 1));
		a5_color_field($base_id.'line_color', $base_name.'[line_color]', $line_color, __('The color of the line (e.g. #cccccc):', 'advanced-cc'), array('space' => true, 'size' => 13));
		parent::page_checkgroup($instance);
		a5_textarea($base_id.'style', $base_name.'[style]', $style, sprintf(__('Here you can finally style the widget. Simply type something like%sto get just a gray outline and a padding of 10 px. If you leave that section empty, your theme will style the widget.', 'a5-recent-posts'), '<br /><strong>border: 2px solid;<br />border-color: #cccccc;<br />padding: 10px;</strong><br />'), array('space' => true, 'class' => 'widefat', 'style' => 'height: 60px;'));
		a5_resize_textarea($base_id.'style');
	
	} // form
	 
	
	function update($new_instance, $old_instance) {
		 
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['postcount'] = strip_tags($new_instance['postcount']);
		$instance['offset'] = strip_tags($new_instance['offset']);
		$instance['home'] = @$new_instance['home'];
		$instance['list'] = strip_tags($new_instance['list']);
		$instance['showcat'] = @$new_instance['showcat'];
		$instance['showcat_txt'] = strip_tags($new_instance['showcat_txt']); 	
		$instance['wordcount'] = strip_tags($new_instance['wordcount']);
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['words'] = @$new_instance['words'];
		$instance['linespace'] = @$new_instance['linespace'];
		$instance['line'] = strip_tags($new_instance['line']);
		$instance['line_color'] = strip_tags($new_instance['line_color']);
		$instance['style'] = strip_tags($new_instance['style']);
		$instance['homepage'] = @$new_instance['homepage'];
		$instance['frontpage'] = @$new_instance['frontpage'];
		$instance['page'] = @$new_instance['page'];
		$instance['category'] = @$new_instance['category'];
		$instance['single'] = @$new_instance['single'];
		$instance['date'] = @$new_instance['date'];
		$instance['archive'] = @$new_instance['archive'];
		$instance['tag'] = @$new_instance['tag'];
		$instance['attachment'] = @$new_instance['attachment'];
		$instance['taxonomy'] = @$new_instance['taxonomy'];
		$instance['author'] = @$new_instance['author'];
		$instance['search'] = @$new_instance['search'];
		$instance['not_found'] = @$new_instance['not_found'];
		$instance['login_page'] = @$new_instance['login_page'];
		$instance['h'] = strip_tags($new_instance['h']);
		$instance['filter'] = @$new_instance['filter'];
		$instance['imgborder'] = strip_tags($new_instance['imgborder']);
		
		return $instance;
		
	} // update
	 
	function widget($args, $instance) {
		
	$eol = "\n";
		
	$show_widget = parent::check_output($instance);

	if ($show_widget) :
	
		rewind_posts();
		
		// the widget is displayed
		
		extract( $args );
		
		$title = apply_filters('widget_title', $instance['title']);	
		
		if (!empty($instance['style'])) :
			
			$style=str_replace(array("\r\n", "\n", "\r"), '', $instance['style']);
			
			$before_widget = str_replace('>', 'style="'.$style.'">', $before_widget);
			
		endif;
		
		echo $before_widget;
		
		if ( $title ) echo $before_title . $title . $after_title;
	 
		/* This is the actual function of the widget, it fills the sidebar with the customized posts */
		
		$i=1;
		
		global $wp_query;
		
		$acc_setup['posts_per_page'] = $instance['postcount'];
		
		if (is_category() || is_home() || empty($instance['home'])) :
			
			$acc_page = $wp_query->get( 'paged' );
			
			$acc_numberposts = $wp_query->get( 'posts_per_page' );
			
			$acc_offset = (empty($acc_page)) ? $acc_offset=$instance['offset'] : $acc_offset=(($acc_page-1)*$acc_numberposts)+$instance['offset'];
			
			$acc_setup['offset'] = $acc_offset;
		
		endif;
		
		$acc_cat = (is_category()) ? ',-'.get_query_var('cat') : '';
		
		if ($instance['list'] || !empty($acc_cat)) $acc_setup['cat'] = $instance['list'].$acc_cat;
		
		if (is_single()) :
			
			$acc_setup['post__not_in'] = array($wp_query->get_queried_object_id()); 
		
		endif;
		
		global $post;
		
		rewind_posts();
		
		$acc_posts = new WP_Query($acc_setup);
		
		$count = 0;
		
		while($acc_posts->have_posts()) :
		
			$acc_posts->the_post();
		
			if ($instance['showcat']) :
			
				$post_byline = ($instance['showcat_txt']) ? $eol.'<p id="acc_byline-'.$widget_id.'-'.$count.'">'.$eol.$instance['showcat_txt'].' ' : $eol.'<p id="acc_byline-'.$widget_id.'-'.$count.'">';
				
				echo $post_byline;
			
				the_category(', ');
				
				echo $eol.'</p>'.$eol;
			
			endif;
		 
			$acc_tags = A5_Image::tags();
			
			$acc_image_alt = $acc_tags['image_alt'];
			$acc_image_title = $acc_tags['image_title'];
			$acc_title_tag = $acc_tags['title_tag'];
			
			$acc_headline = '<h'.$instance['h'].'>'.$eol.'<a href="'.get_permalink().'" title="'.$acc_title_tag.'">'.get_the_title().'</a>'.$eol.'</h'.$instance['h'].'>';
			
			// get thumbnail
			
			$acc_imgborder = (!empty($instance['imgborder'])) ? ' style="border: '.$instance['imgborder'].';"' : '';
				
			$id = get_the_ID();
					
			$args = array (
				'id' => $id,
				'option' => 'acc_options',
				'width' => $instance['width']
			);
			   
			$acc_image_info = A5_Image::thumbnail($args);
			
			$acc_thumb = $acc_image_info[0];
			
			$acc_width = $acc_image_info[1];
	
			$acc_height = ($acc_image_info[2]) ? ' height="'.$acc_image_info[2].'"' : '';
			
			if ($acc_thumb) if ($acc_width) $acc_img = '<img title="'.$acc_image_title.'" src="'.$acc_thumb.'" alt="'.$acc_image_alt.'" class="wp-post-image" width="'.$acc_width.'"'.$acc_height.$acc_imgborder.' />';
			
			if (isset($acc_img)) :
			
				echo '<a href="'.get_permalink().'">'.$acc_img.'</a>'.$eol.'<div style="clear: both;"></div>'.$eol.$acc_headline;
		
			else :
					
				/* If there is no picture, show headline and excerpt of the post */
				
				echo $acc_headline;
				
				/* in case the excerpt is not definded by theme or anything else, the first x sentences of the content are given */
				
				$type = (empty($instance['words'])) ? 'sentences' : 'words';
				$filter = ($instance['filter']) ? false : true;
				$linespace = ($instance['linespace']) ? true : false;
					
				$args = array(
					'excerpt' => $post->post_excerpt,
					'content' => $post->post_content,
					'type' => $type,
					'count' => $instance['wordcount'],
					'linespace' => $linespace,
					'filter' => $filter
				);
				
				echo A5_Excerpt::text($args);
				
			endif;
			
			if (!empty($instance['line']) && $i < $instance['postcount']) :
				
				echo '<hr style="color: '.$instance['line_color'].'; background-color: '.$instance['line_color'].'; height: '.$instance['line'].'px;" />';
				
				$i++;
				
			endif;
			
			unset($acc_img, $source);
			
			$count++;
			
		endwhile;
		
		// Restore original Query & Post Data
		wp_reset_query();
		wp_reset_postdata();
		
		echo $after_widget;
		
	else:
	
		echo "<!-- Advanced Category Column Widget is not setup for this view. -->";
	 
	endif;
	
	} // widget
 
} // class

add_action('widgets_init', create_function('', 'return register_widget("Advanced_Category_Column_Widget");'));

?>