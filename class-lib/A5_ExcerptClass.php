<?php

/**
 *
 * Class A5 Excerpt
 *
 * @ A5 Plugin Framework
 *
 * Gets the excerpt of a post accoring to some parameters
 *
 */

class A5_Excerpt {
	
	var $output;
	
	function get_excerpt($args) {
		
		extract($args);
		
		if ($excerpt) :
		
			$this->output = $excerpt;
			
		else :
		
			$text = trim(preg_replace('/\s\s+/', ' ', str_replace(array("\r\n", "\n", "\r", "&nbsp;"), ' ', strip_tags(strip_shortcodes($content)))));
			
			$length = (!empty($count)) ? $count : 3;
			
			$style = (!empty($type)) ? $type : 'sentenses';
			
			$implode = (!empty($linespace)) ? '<br /><br />' : '';
			
			if ($style == 'words') :
				
				$short=array_slice(explode(' ', $text), 0, $length);
				
				$this->output=trim(implode(' ', $short));
				
			else :
			
				if ($style == 'sentenses') :
				
					$short=array_slice(preg_split("/([\t.!?]+)/", $text, -1, PREG_SPLIT_DELIM_CAPTURE), 0, $length*2);
					
					foreach ($short as $key => $val) :
					
						if (($key+1)/2 != intval(($key+1)/2)) :
												  
							$key2 = $key+1;
												  
							$tmpex[] = implode(array($short[$key], $short[$key2]));
							
						endif;
						
					endforeach;
					
					$this->output=trim(implode($implode, $tmpex));
					
				else :
					
					$this->output=substr($text, 0, $length+1);
					
				endif;
				
			endif;
			
		endif;
		
		if ($readmore) $this->output.=' <a href="'.$link.'" title="'.$title.'">'.$rmtext.'</a>';
		
		return $this->output;
		
	
	} // __construct
	
} // A5_Excerpt


?>