<?php

/**
 *
 * Class A5 Excerpt
 *
 * @ A5 Plugin Framework
 *
 * Gets the excerpt of a post according to some parameters
 *
 */

class A5_Excerpt {
	
	const version = '1.0';
	
	var $output;
	
	function get_excerpt($args) {
		
		extract($args);
		
		if (!$offset) $offset = 0;
		
		if ($usertext) :
		
			$this->output = $usertext;
		
		else: 
		
			if ($excerpt) :
			
				$this->output = $excerpt;
				
			else :
			
				$excerpt_base = ($shortcode) ? strip_tags(preg_replace('/\[caption(.*?)\[\/caption\]/', '', $content)) : strip_tags(strip_shortcodes($content));
			
				$text = trim(preg_replace('/\s\s+/', ' ', str_replace(array("\r\n", "\n", "\r", "&nbsp;"), ' ', $excerpt_base)));
				
				$length = ($count) ? $count : 3;
				
				$style = ($type) ? $type : 'sentences';
				
				if ($style == 'words') :
					
					$short=array_slice(explode(' ', $text), $offset, $length);
					
					$this->output=trim(implode(' ', $short));
					
				else :
				
					if ($style == 'sentences') :
					
						$short=array_slice(preg_split("/([\t.!?]+)/", $text, -1, PREG_SPLIT_DELIM_CAPTURE), $offset*2, $length*2);
						
						$this->output=trim(implode($short));
						
					else :
						
						$this->output=substr($text, $offset, $length);
						
					endif;
					
				endif;
				
			endif;
			
		endif;
		
		if ($linespace) :
		
			$short=preg_split("/([\t.!?]+)/", $this->output, -1, PREG_SPLIT_DELIM_CAPTURE);
			
			foreach ($short as $key => $pieces) :
			
				if (!($key % 2)) :
				
					$key2 = $key+1;
												  
					$tmpex[] = implode(array($short[$key], $short[$key2]));
					
				endif;
			
			endforeach;
			
			$this->output=trim(implode('<br /><br />', $tmpex));
		
		endif;
		
		if ($readmore) $this->output.=' <a href="'.$link.'" title="'.$title.'">'.$rmtext.'</a>';
		
		$return = ($filter) ? $this->output : apply_filters('the_excerpt', $this->output);
		
		return $return;
	
	} // get_excerpt
	
} // A5_Excerpt


?>