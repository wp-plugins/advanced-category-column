<?php
/*
Plugin Name: Advanced Category Column
Plugin URI: http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/advanced-category-column-plugin
Description: The Advanced Category Column does, what the Category Column Plugin does; it creates a widget, which you can drag to your sidebar and it will show excerpts of the posts of other categories than showed in the center-column. It just has more options than the the Category Column Plugin. It is tested with WP up to version 3.9 and it might work with versions down to 2.9, but will never be explicitly supported for those. The 'Advanced' means, that you have a couple of more options than in the 'Category Column Plugin'.
Version: 2.8.2
Author: Waldemar Stoffel
Author URI: http://www.waldemarstoffel.com
License: GPL3
Text Domain: advanced-cc 
*/

/*  Copyright 2011 - 2014 Waldemar Stoffel  (email : stoffel@atelier-fuenf.de)

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

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) die('Sorry, you don\'t have direct access to this page.');

define( 'ACC_PATH', plugin_dir_path(__FILE__) );

if (!class_exists('A5_Image')) require_once ACC_PATH.'class-lib/A5_ImageClass.php';
if (!class_exists('A5_Excerpt')) require_once ACC_PATH.'class-lib/A5_ExcerptClass.php';
if (!class_exists('Advanced_Category_Column_Widget')) require_once ACC_PATH.'class-lib/ACC_WidgetClass.php';
if (!class_exists('A5_FormField')) require_once ACC_PATH.'class-lib/A5_FormFieldClass.php';
if (!class_exists('A5_OptionPage')) require_once ACC_PATH.'class-lib/A5_OptionPageClass.php';
if (!class_exists('A5_DynamicCSS')) :

	require_once ACC_PATH.'class-lib/A5_DynamicCSSClass.php';
	
	$dynamic_css = new A5_DynamicCSS;
	
endif;

class AdvancedCategoryColumn {
	
	const language_file = 'advanced-cc';
	
	private static $options;
	
	function __construct() {
		
		self::$options = get_option('acc_options');
		
		// Load language files
	
		load_plugin_textdomain(self::language_file, false , basename(dirname(__FILE__)).'/languages');
		
		add_action('admin_enqueue_scripts', array($this, 'acc_js_sheet'));
		add_filter('plugin_row_meta', array($this, 'acc_register_links'), 10, 2);	
		add_filter( 'plugin_action_links', array($this, 'acc_plugin_action_links'), 10, 2 );
		add_action('admin_init', array($this, 'acc_init'));
		register_activation_hook(  __FILE__, array($this, 'install') );
		register_deactivation_hook(  __FILE__, array($this, 'uninstall') );
		add_action('admin_menu', array ($this, 'acc_admin_menu'));
		
		$eol = "\r\n";
		$tab = "\t";
		
		A5_DynamicCSS::$styles .= $eol.'/* CSS portion of Advanced Category Column */'.$eol.$eol;
		
		A5_DynamicCSS::$styles .= 'p[id^="acc_byline"] {'.$eol.$tab.'font-size: 0.9em;'.$eol.'}'.$eol;
		
		A5_DynamicCSS::$styles .= 'p[id^="acc_byline"] a {'.$eol.$tab.'text-decoration: none !important;'.$eol.$tab.'font-weight: normal !important;'.$eol.'}'.$eol;
		
		A5_DynamicCSS::$styles .= 'div[id^="advanced_category_column_widget"].widget_advanced_category_column_widget img {'.$eol.$tab.'height: auto;'.$eol.$tab.'max-width: 100%;'.$eol.'}'.$eol;
		
		A5_DynamicCSS::$styles .= 'div[id^="advanced_category_column_widget"].widget_advanced_category_column_widget {'.$eol.$tab.'-moz-hyphens: auto;'.$eol.$tab.'-o-hyphens: auto;'.$eol.$tab.'-webkit-hyphens: auto;'.$eol.$tab.'-ms-hyphens: auto;'.$eol.$tab.'hyphens: auto; '.$eol.'}'.$eol;
		
		if (!empty (self::$options['link']) || !empty (self::$options['hover'])) :
		
			$acc_link=str_replace(array("\r\n", "\n", "\r"), ' ', self::$options['link']);
			$acc_hover=str_replace(array("\r\n", "\n", "\r"), ' ', self::$options['hover']);
			
			$acc_link=str_replace('; ', ';'.$eol.$tab, $acc_link);
			$acc_hover=str_replace('; ', ';'.$eol.$tab, $acc_hover);
			
			A5_DynamicCSS::$styles .= 'div[id^="advanced_category_column_widget"].widget_advanced_category_column_widget a {'.$eol.$tab.$acc_link.$eol.'}'.$eol.'div[id^="advanced_category_column_widget"].widget_advanced_category_column_widget a:hover {'.$eol.$tab.$acc_hover.$eol.'}'.$eol;
			
		endif;
		
		if (!empty(self::$options['acc_css'])) :
			
			$style=str_replace('; ', ';'.$eol.$tab, str_replace(array("\r\n", "\n", "\r"), ' ', self::$options['acc_css']));
	
			A5_DynamicCSS::$styles.='div[id^="advanced_category_column_widget"].widget_advanced_category_column_widget {'.$eol.$tab.$style.$eol.'}'.$eol;
		
		endif;
		
	}
	
	// attach JavaScript file for textarea resizing
	
	function acc_js_sheet($hook) {
		
		if ($hook != 'widgets.php' && $hook != 'settings_page_advanced-cc-settings') return;
		
		wp_register_script('ta-expander-script', plugins_url('ta-expander.js', __FILE__), array('jquery'), '3.0', true);
		wp_enqueue_script('ta-expander-script');
	
	}
	
	//Additional links on the plugin page
	
	function acc_register_links($links, $file) {
		
		$base = plugin_basename(__FILE__);
		
		if ($file == $base) :
		
			$links[] = '<a href="http://wordpress.org/extend/plugins/advanced-category-column/faq/" target="_blank">'.__('FAQ', self::language_file).'</a>';
			$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BC9QUKBEZFZFY" target="_blank">'.__('Donate', self::language_file).'</a>';
		
		endif;
		
		return $links;
	
	}
	
	function acc_plugin_action_links( $links, $file ) {
		
		$base = plugin_basename(__FILE__);
		
		if ($file == $base) array_unshift($links, '<a href="'.admin_url( 'options-general.php?page=advanced-cc-settings' ).'">'.__('Settings', self::language_file).'</a>');
	
		return $links;
	
	}
	
	// init
	
	function acc_init() {
		
		register_setting( 'acc_options', 'acc_options', array($this, 'acc_validate') );
		
		add_settings_section('acc_settings', __('Styling of the links', self::language_file), array($this, 'acc_display_section'), 'acc_styles');
		
		add_settings_field('acc_link_style', __('Link style:', self::language_file), array($this, 'acc_link_field'), 'acc_styles', 'acc_settings');
		
		add_settings_field('acc_hover_style', __('Hover style:', self::language_file), array($this, 'acc_hover_field'), 'acc_styles', 'acc_settings');
		
		add_settings_field('use_own_css', __('Widget container:', self::language_file), array($this, 'acc_display_css'), 'acc_styles', 'acc_settings', array(__('You can enter your own style for the widgets here. This will overwrite the styles of your theme.', self::language_file), __('If you leave this empty, you can still style every instance of the widget individually.', self::language_file)));
		
		add_settings_field('acc_resize', false, array($this, 'acc_resize_field'), 'acc_styles', 'acc_settings');
	
	}
	
	function acc_display_section() {
		
		echo '<p>'.__('Just put some css code here.', self::language_file).'</p>';
	
	}
	
	function acc_link_field() {
		
		a5_textarea('link', 'acc_options[link]', @self::$options['link'], false, array('cols' => 35, 'rows' => 3));
		
	}
	
	function acc_hover_field() {
		
		a5_textarea('hover', 'acc_options[hover]', @self::$options['hover'], false, array('cols' => 35, 'rows' => 3));
		
	}
	
	function acc_display_css($labels) {
		
		echo $labels[0].'</br>'.$labels[1].'</br>';
		
		a5_textarea('acc_css', 'acc_options[acc_css]', @self::$options['acc_css'], false, array('rows' => 10, 'cols' => 35));
		
	}
	
	function acc_resize_field() {
		
		a5_resize_textarea(array('link', 'hover', 'acc_css'), true);
		
	}
	
	// Creating default options on activation
	
	function install() {
		
		$default = array(
			'tags' => array(),
			'sizes' => array()
		);
		
		add_option('acc_options', $default);
		
	}
	
	// Cleaning on deactivation
	
	function uninstall() {
		
		delete_option('acc_options');
		
	}
	
	// Installing options page
	
	function acc_admin_menu() {
		
		add_options_page('Advanced CC '.__('Settings', self::language_file), '<img alt="" src="'.plugins_url('advanced-category-column/img/a5-icon-11.png').'"> Advanced Category Column', 'administrator', 'advanced-cc-settings', array($this, 'advanced_cc_options_page'));
		
	}
	
	// Calling the options page
	
	function advanced_cc_options_page() {
		
		A5_OptionPage::open_page('Advanced Category Column', __('http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/advanced-category-column-plugin', self::language_file), 'advanced-category-column', __('Plugin Support', self::language_file));
		
		_e('Style the links of the widget. If you leave this empty, your theme will style the hyperlinks.', self::language_file); ?>
	  <p>
		<?php _e('Just input something like,', self::language_file); ?>
	  <p><strong>font-weight: bold;<br />
		color: #0000ff;<br />
		text-decoration: underline;</strong></p>
	  <?php _e('to get fat, blue, underlined links.', self::language_file); ?>
	  </p>
	  <p><strong>
		<?php _e('You most probably have to use &#34;!important&#34; at the end of each line, to make it work.', self::language_file); ?>
		</strong></p>
		<?php
		
		A5_OptionPage::open_form('options.php');
		
		settings_fields('acc_options');
		do_settings_sections('acc_styles');
		
		submit_button();
		
		A5_OptionPage::close_page();
	
	}
	
	function acc_validate($input) {
		
		self::$options['link'] = trim($input['link']);
		self::$options['hover'] = trim($input['hover']);
		self::$options['acc_css'] = trim($input['acc_css']);
		
		return self::$options;
	
	}

}

$advanced_cc_plugin = new AdvancedCategoryColumn;

?>