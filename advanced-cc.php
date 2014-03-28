<?php
/*
Plugin Name: Advanced Category Column
Plugin URI: http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/advanced-category-column-plugin
Description: The Advanced Category Column does, what the Category Column Plugin does; it creates a widget, which you can drag to your sidebar and it will show excerpts of the posts of other categories than showed in the center-column. It just has more options than the the Category Column Plugin. It is tested with WP up to version 3.9 and it might work with versions down to 2.9, but will never be explicitly supported for those. The 'Advanced' means, that you have a couple of more options than in the 'Category Column Plugin'.
Version: 2.9
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

defined('ABSPATH') OR exit;

if (!defined('ACC_PATH')) define( 'ACC_PATH', plugin_dir_path(__FILE__) );
if (!defined('ACC_BASE')) define( 'ACC_BASE', plugin_basename(__FILE__) );

# loading the framework
if (!class_exists('A5_Image')) require_once ACC_PATH.'class-lib/A5_ImageClass.php';
if (!class_exists('A5_Excerpt')) require_once ACC_PATH.'class-lib/A5_ExcerptClass.php';
if (!class_exists('A5_FormField')) require_once ACC_PATH.'class-lib/A5_FormFieldClass.php';
if (!class_exists('A5_OptionPage')) require_once ACC_PATH.'class-lib/A5_OptionPageClass.php';
if (!class_exists('A5_DynamicFiles')) require_once ACC_PATH.'class-lib/A5_DynamicFileClass.php';

#loading plugin specific classes
if (!class_exists('ACC_Admin')) require_once ACC_PATH.'class-lib/ACC_AdminClass.php';
if (!class_exists('ACC_DynamicCSS')) require_once ACC_PATH.'class-lib/ACC_DynamicCSSClass.php';
if (!class_exists('Advanced_Category_Column_Widget')) require_once ACC_PATH.'class-lib/ACC_WidgetClass.php';

class AdvancedCategoryColumn {
	
	const language_file = 'advanced-cc';
	
	private static $options;
	
	function __construct() {
		
		self::$options = get_option('acc_options');
		
		if (isset(self::$options['tags'])) $this->update_plugin_options();
		
		// Load language files
	
		load_plugin_textdomain(self::language_file, false , basename(dirname(__FILE__)).'/languages');
		
		add_action('admin_enqueue_scripts', array(&$this, 'register_js_sheet'));
		
		add_filter('plugin_row_meta', array(&$this, 'register_links'), 10, 2);	
		add_filter( 'plugin_action_links', array(&$this, 'plugin_action_links'), 10, 2 );
		
		register_activation_hook(  __FILE__, array(&$this, '_install') );
		register_deactivation_hook(  __FILE__, array(&$this, '_uninstall') );
		
		$ACC_DynamicCSS = new ACC_DynamicCSS;
		$ACC_Admin = new ACC_Admin;
		
	}
	
	// attach JavaScript file for textarea resizing
	
	function register_js_sheet($hook) {
		
		if ($hook != 'widgets.php' && $hook != 'settings_page_advanced-cc-settings') return;
		
		wp_register_script('ta-expander-script', plugins_url('ta-expander.js', __FILE__), array('jquery'), '3.0', true);
		wp_enqueue_script('ta-expander-script');
	
	}
	
	//Additional links on the plugin page
	
	function register_links($links, $file) {
		
		if ($file == ACC_BASE) :
		
			$links[] = '<a href="http://wordpress.org/extend/plugins/advanced-category-column/faq/" target="_blank">'.__('FAQ', self::language_file).'</a>';
			$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BC9QUKBEZFZFY" target="_blank">'.__('Donate', self::language_file).'</a>';
		
		endif;
		
		return $links;
	
	}
	
	function plugin_action_links( $links, $file ) {
		
		if ($file == ACC_BASE) array_unshift($links, '<a href="'.admin_url( 'options-general.php?page=advanced-cc-settings' ).'">'.__('Settings', self::language_file).'</a>');
	
		return $links;
	
	}
	
	// Creating default options on activation
	
	function _install() {
		
		$default = array(
			'cache' => array(), 
			'inline' => NULL
		);
		
		add_option('acc_options', $default);
		
	}
	
	// Cleaning on deactivation
	
	function _uninstall() {
		
		delete_option('acc_options');
		
	}
	
	// updating options in case they are outdated
	
	function update_plugin_options() {	
		
			if (isset(self::$options['acc_css'])) self::$options['css'] = self::$options['acc_css'];
			
			self::$options['cache'] = array();
			
			self::$options['inline'] = NULL;
			
			unset(self::$options['tags'], self::$options['sizes'], self::$options['acc_css']);
			
			update_option('acc_options', self::$options);
	
	}

}

$AdvancedCategoryColumn = new AdvancedCategoryColumn;

?>