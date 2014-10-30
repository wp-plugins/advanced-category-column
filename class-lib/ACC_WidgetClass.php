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
	
	const language_file = 'advanced-cc';
	
	private static $options;
 
	function __construct() {
	
		$widget_opts = array( 'description' => __('Configure the output and looks of the widget. Then display thumbnails and excerpts of posts in your sidebars and define, on what kind of pages they will show.', self::language_file) );
		$control_opts = array( 'width' => 400 );
		
		parent::WP_Widget(false, $name = 'Advanced Category Column', $widget_opts, $control_opts);
		
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
		$homepage=esc_attr($instance['homepage']);
		$frontpage=esc_attr($instance['frontpage']);
		$page=esc_attr($instance['page']);
		$category=esc_attr($instance['category']);
		$single=esc_attr($instance['single']);
		$date=esc_attr($instance['date']);
		$archive=esc_attr($instance['archive']);
		$tag=esc_attr($instance['tag']);
		$attachment=esc_attr($instance['attachment']);
		$taxonomy=esc_attr($instance['taxonomy']);
		$author=esc_attr($instance['author']);
		$search=esc_attr($instance['search']);
		$not_found=esc_attr($instance['not_found']);
		$login_page=esc_attr($instance['login_page']);
		$h = esc_attr($instance['h']);
		$filter = esc_attr($instance['filter']);
		$imgborder=esc_attr($instance['imgborder']);
		
		$base_id = 'widget-'.$this->id_base.'-'.$this->number.'-';
		$base_name = 'widget-'.$this->id_base.'['.$this->number.']';
		
		$options = array (
			array($base_id.'homepage', $base_name.'[homepage]', $homepage, __('Homepage', self::language_file)),
			array($base_id.'frontpage', $base_name.'[frontpage]', $frontpage, __('Frontpage (e.g. a static page as homepage)', self::language_file)),
			array($base_id.'page', $base_name.'[page]', $page, __('&#34;Page&#34; pages', self::language_file)),
			array($base_id.'category', $base_name.'[category]', $category, __('Category pages', self::language_file)),
			array($base_id.'single', $base_name.'[single]', $single, __('Single post pages', self::language_file)),
			array($base_id.'date', $base_name.'[date]', $date, __('Archive pages', self::language_file)),
			array($base_id.'archive', $base_name.'[archive]', $archive, __('Post type archives', self::language_file)),
			array($base_id.'tag', $base_name.'[tag]', $tag, __('Tag pages', self::language_file)),
			array($base_id.'attachment', $base_name.'[attachment]', $attachment, __('Attachments', self::language_file)),
			array($base_id.'taxonomy', $base_name.'[taxonomy]', $taxonomy, __('Custom Taxonomy pages (only available, if having a plugin)', self::language_file)),
			array($base_id.'author', $base_name.'[author]', $author, __('Author pages', self::language_file)),
			array($base_id.'search', $base_name.'[search]', $search, __('Search Results', self::language_file)),
			array($base_id.'not_found', $base_name.'[not_found]', $not_found, __('&#34;Not Found&#34;', self::language_file)),
			array($base_id.'login_page', $base_name.'[login_page]', $login_page, __('Login page (only available, if having a plugin)', self::language_file))
		);
			
		$checkall = array($base_id.'checkall', $base_name.'[checkall]', __('Check all', self::language_file));
		
		$headings = array(array('1', 'h1'), array('2', 'h2'), array('3', 'h3'), array('4', 'h4'), array('5', 'h5'), array('6', 'h6'));
		
		a5_text_field($base_id.'title', $base_name.'[title]', $title, __('Title:', self::language_file), array('space' => true, 'class' => 'widefat'));
		a5_text_field($base_id.'list', $base_name.'[list]', $list, sprintf(__('To exclude certain categories or to show just a special category, simply write their ID&#39;s separated by comma (e.g. %s-5, 2, 4%s will show categories 2 and 4 and will exclude category 5):', self::language_file), '<strong>', '</strong>'), array('space' => true, 'class' => 'widefat'));
		a5_checkbox($base_id.'showcat', $base_name.'[showcat]', $showcat, __('Check to show the categories in which the post is filed.', self::language_file), array('space' => true));
		a5_text_field($base_id.'showcat_txt', $base_name.'[showcat_txt]', $showcat_txt, __('Give some text that you want in front of the post&#39;s categtories (i.e &#39;filed under&#39;:', self::language_file), array('space' => true, 'class' => 'widefat'));
		a5_number_field($base_id.'postcount', $base_name.'[postcount]', $postcount, __('How many posts will be displayed in the sidebar:', self::language_file), array('space' => true, 'size' => 4, 'step' => 1));
		a5_number_field($base_id.'offset', $base_name.'[offset]', $offset, __('Offset (how many posts are spared out in the beginning):', self::language_file), array('space' => true, 'size' => 4, 'step' => 1));
		a5_checkbox($base_id.'home', $base_name.'[home]', $home, __('Check to have the offset only on your homepage.', self::language_file), array('space' => true));
		a5_number_field($base_id.'width', $base_name.'[width]', $width, __('Width of the thumbnail (in px):', self::language_file), array('space' => true, 'size' => 4, 'step' => 1));
		a5_text_field($base_id.'imgborder', $base_name.'[imgborder]', $imgborder, sprintf(__('If wanting a border around the image, write the style here. %s would make it a black border, 1px wide.', self::language_file), '<strong>1px solid #000000</strong>'), array('space' => true, 'class' => 'widefat'));
		a5_select($base_id.'h', $base_name.'[h]', $headings, $h, __('Weight of the Post Title:', self::language_file), false, array('space' => true));
		a5_number_field($base_id.'wordcount', $base_name.'[wordcount]', $wordcount, __('In case there is no excerpt defined, how many sentences are displayed:', self::language_file), array('space' => true, 'size' => 4, 'step' => 1));
		a5_checkbox($base_id.'words', $base_name.'[words]', $words, __('Check to display words instead of sentences.', self::language_file), array('space' => true));
		a5_checkbox($base_id.'linespace', $base_name.'[linespace]', $linespace, __('Check to have each sentense in a new line.', self::language_file), array('space' => true));
		a5_checkbox($base_id.'filter', $base_name.'[filter]', $filter, __('Check to return the excerpt unfiltered (might avoid interferences with other plugins).', self::language_file), array('space' => true));
		a5_number_field($base_id.'line', $base_name.'[line]', $line, __('If you want a line between the posts, this is the height in px (if not wanting a line, leave emtpy):', self::language_file), array('space' => true, 'size' => 4, 'step' => 1));
		a5_color_field($base_id.'line_color', $base_name.'[line_color]', $line_color, __('The color of the line (e.g. #cccccc):', self::language_file), array('space' => true, 'size' => 13));
		a5_checkgroup(false, false, $options, __('Check, where you want to show the widget. By default, it is showing on the homepage and the category pages:', self::language_file), $checkall);
		if (empty(self::$options['acc_css'])) a5_textarea($base_id.'style', $base_name.'[style]', $style, sprintf(__('Here you can finally style the widget. Simply type something like%1$s%2$sborder-left: 1px dashed;%2$sborder-color: #000000;%3$s%2$sto get just a dashed black line on the left. If you leave that section empty, your theme will style the widget.', self::language_file), '<strong>', '<br />', '</strong>'), array('space' => true, 'class' => 'widefat', 'style' => 'height: 60px;'));
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
		
	$eol = "\r\n";
		
	// get the type of page, we're actually on
	
	if (is_front_page()) $pagetype='frontpage';
	if (is_home()) $pagetype='homepage';
	if (is_page()) $pagetype='page';
	if (is_category()) $pagetype='category';
	if (is_single()) $pagetype='single';
	if (is_date()) $pagetype='date';
	if (is_archive()) $pagetype='archive';
	if (is_tag()) $pagetype='tag';
	if (is_attachment()) $pagetype='attachment';
	if (is_tax()) $pagetype='taxonomy';
	if (is_author()) $pagetype='author';
	if (is_search()) $pagetype='search';
	if (is_404()) $pagetype='not_found';
	if (!isset($pagetype)) $pagetype='login_page';
	
	// display only, if said so in the settings of the widget
	
	if ($instance[$pagetype]) :
		
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
		 
			$acc_tags = A5_Image::tags(self::language_file);
			
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