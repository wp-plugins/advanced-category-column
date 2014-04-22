<?php

/**
 *
 * Class ACC Dynamic CSS
 *
 * Extending A5 Dynamic Files
 *
 * Presses the dynamical CSS of the Advanceed Category Column Widget into a virtual style sheet
 *
 */

class ACC_DynamicCSS extends A5_DynamicFiles {
	
	private static $options;
	
	function __construct() {
		
		self::$options =  get_option('acc_options');
		
		if (!isset(self::$options['inline'])) self::$options['inline'] = false;
		
		if (!array_key_exists('compress', self::$options)) self::$options['compress'] = false;
		
		parent::A5_DynamicFiles('wp', 'css', false, self::$options['inline']);
		
		$eol = (self::$options['compress']) ? '' : "\r\n";
		$tab = (self::$options['compress']) ? '' : "\t";
		
		$css_selector = '.widget_advanced_category_column_widget[id^="advanced_category_column_widget"]';
		
		parent::$wp_styles .= (!self::$options['compress']) ? $eol.'/* CSS portion of Advanced Category Column */'.$eol.$eol : '';
		
		$style = '-moz-hyphens: auto;'.$eol.$tab.'-o-hyphens: auto;'.$eol.$tab.'-webkit-hyphens: auto;'.$eol.$tab.'-ms-hyphens: auto;'.$eol.$tab.'hyphens: auto;';
		
		if (!empty(self::$options['css'])) $style.=$eol.$tab.str_replace('; ', ';'.$eol.$tab, str_replace(array("\r\n", "\n", "\r"), ' ', self::$options['css']));
		
			parent::$wp_styles.='div'.$css_selector.','.$eol.'li'.$css_selector.','.$eol.'aside'.$css_selector.' {'.$eol.$tab.$style.$eol.'}'.$eol;
			
			parent::$wp_styles.='div'.$css_selector.' img,'.$eol.'li'.$css_selector.' img,'.$eol.'aside'.$css_selector.' img {'.$eol.$tab.'height: auto;'.$eol.$tab.'max-width: 100%;'.$eol.'}'.$eol;
		
		if (!empty (self::$options['link'])) :
		
			$style=str_replace('; ', ';'.$eol.$tab, str_replace(array("\r\n", "\n", "\r"), ' ', self::$options['link']));
		
			parent::$wp_styles.='div'.$css_selector.' a,'.$eol.'li'.$css_selector.' a,'.$eol.'aside'.$css_selector.' a {'.$eol.$tab.$style.$eol.'}'.$eol;
			
		endif;
		
		if (!empty (self::$options['hover'])) :
		
			$style=str_replace('; ', ';'.$eol.$tab, str_replace(array("\r\n", "\n", "\r"), ' ', self::$options['hover']));
		
			parent::$wp_styles.='div'.$css_selector.' a:hover,'.$eol.'li'.$css_selector.' a:hover,'.$eol.'aside'.$css_selector.' a:hover {'.$eol.$tab.$style.$eol.'}'.$eol;
			
		endif;

	}
	
} // ACC_Dynamic CSS

?>