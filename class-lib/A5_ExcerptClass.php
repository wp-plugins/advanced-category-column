<?php

/**
 *
 * Class A5 Excerpt
 *
 * @ A5 Plugin Framework
 *
 * Gets the excerpt of a post according to some parameters
 *
 * standard parameters: offset(=0), usertext, excerpt, excerpt_length
 * additional parameters: class(classname), filter(true/false), shortcode(true/false), readmore_link, readmore_text
 *
 */

class A5_Excerpt {
	
	public static function text($args) {
		
		extract($args);
		
		$offset = (isset($offset)) ? $offset : 0;
		
		$class = (isset($class)) ? ' class ="'.$class.'"' : '';
		
		if (!empty($usertext)) :
		
			$output = $usertext;
		
		else: 
		
			if (!empty($excerpt)) :
			
				$output = $excerpt;
				
			else :
			
				$excerpt_base = (!empty($shortcode)) ? strip_tags(preg_replace('/\[caption(.*?)\[\/caption\]/', '', $content)) : strip_tags(strip_shortcodes($content));
			
				$text = trim(preg_replace('/\s\s+/', ' ', str_replace(array("\r\n", "\n", "\r", "&nbsp;"), ' ', $excerpt_base)));
				
				$length = (isset($count)) ? $count : 3;
				
				$style = (isset($type)) ? $type : 'sentences';
				
				if ($style == 'words') :
					
					$short=array_slice(explode(' ', $text), $offset, $length);
					
					$output=trim(implode(' ', $short));
					
				else :
				
					if ($style == 'sentences') :
					
						$short=array_slice(preg_split("/([\t.!?]+)/", $text, -1, PREG_SPLIT_DELIM_CAPTURE), $offset*2, $length*2);
						
						$output=trim(implode($short));
						
					else :
						
						$output=substr($text, $offset, $length);
						
					endif;
					
				endif;
				
			endif;
			
		endif;
		
		if (!empty($linespace)) :
		
			$short=preg_split("/([\t.!?]+)/", $output, -1, PREG_SPLIT_DELIM_CAPTURE);
			
			foreach ($short as $key => $pieces) :
			
				if (!($key % 2)) :
				
					$key2 = ($key < (count($short)-1)) ? $key+1 : $key;
												  
					$tmpex[] = implode(array($short[$key], $short[$key2]));
					
				endif;
			
			endforeach;
			
			$output=trim(implode('<br /><br />', $tmpex));
		
		endif;
		
		if (!empty($readmore)) $output.=' <a href="'.$link.'" title="'.$title.'"'.$class.'>'.$rmtext.'</a>';
		
		$output = ($filter === true) ? apply_filters('the_excerpt', $output) : $output;
		
		return $output;
	
	}
	
} // A5_Excerpt

?>