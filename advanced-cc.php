<?php
/*
Plugin Name: Advanced Category Column
Plugin URI: http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/advanced-category-column-plugin
Description: The Advanced Category Column does, what the Category Column Plugin does; it creates a widget, which you can drag to your sidebar and it will show excerpts of the posts of other categories than showed in the center-column. It just has more options than the the Category Column Plugin. The 'Advanced' means, that you have a couple of more options than in the 'Category Column Plugin'.
Version: 3.4.2
Author: Waldemar Stoffel
Author URI: http://www.waldemarstoffel.com
License: GPL3
Text Domain: advanced-cc
Domain Path: /languages
*/

/*  Copyright 2011 - 2015 Waldemar Stoffel  (email : stoffel@atelier-fuenf.de)

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
if (!class_exists('A5_Widget')) require_once ACC_PATH.'class-lib/A5_WidgetClass.php';

#loading plugin specific classes
if (!class_exists('ACC_Admin')) require_once ACC_PATH.'class-lib/ACC_AdminClass.php';
if (!class_exists('ACC_DynamicCSS')) require_once ACC_PATH.'class-lib/ACC_DynamicCSSClass.php';
if (!class_exists('Advanced_Category_Column_Widget')) require_once ACC_PATH.'class-lib/ACC_WidgetClass.php';

class AdvancedCategoryColumn {
	
	const version = 3.4;
	
	private static $options;
	
	function __construct() {
		
		self::$options = get_option('acc_options');
		
		if (self::version != self::$options['version']) $this->_update_options();
		
		// Load language files
	
		load_plugin_textdomain('advanced-cc', false , basename(dirname(__FILE__)).'/languages');
		
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
		
		add_filter('plugin_row_meta', array($this, 'register_links'), 10, 2);	
		add_filter( 'plugin_action_links', array($this, 'plugin_action_links'), 10, 2 );
		
		register_activation_hook(  __FILE__, array($this, '_install') );
		register_deactivation_hook(  __FILE__, array($this, '_uninstall') );
		
		$ACC_DynamicCSS = new ACC_DynamicCSS;
		$ACC_Admin = new ACC_Admin;
		
	}
	
	// attach JavaScript file for textarea resizing
	
	function enqueue_scripts($hook) {
		
		if ($hook != 'post.php' && $hook != 'widgets.php' && $hook != 'settings_page_advanced-cc-settings') return;
		
		$min = (WP_DEBUG == false) ? '.min.' : '.';
		
		wp_register_script('ta-expander-script', plugins_url('ta-expander'.$min.'js', __FILE__), array('jquery'), '3.0', true);
		wp_enqueue_script('ta-expander-script');
	
	}
	
	//Additional links on the plugin page
	
	function register_links($links, $file) {
		
		if ($file == ACC_BASE) :
		
			$links[] = '<a href="http://wordpress.org/extend/plugins/advanced-category-column/faq/" target="_blank">'.__('FAQ', 'advanced-cc').'</a>';
			$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BC9QUKBEZFZFY" target="_blank">'.__('Donate', 'advanced-cc').'</a>';
		
		endif;
		
		return $links;
	
	}
	
	function plugin_action_links( $links, $file ) {
		
		if ($file == ACC_BASE) array_unshift($links, '<a href="'.admin_url( 'options-general.php?page=advanced-cc-settings' ).'">'.__('Settings', 'advanced-cc').'</a>');
	
		return $links;
	
	}
	
	// Creating default options on activation
	
	function _install() {
		
		$default = array(
			'version' => self::version,
			'cache' => array(),
			'inline' => false,
			'compress' => false,
			'css' => "-moz-hyphens: auto;\n-o-hyphens: auto;\n-webkit-hyphens: auto;\n-ms-hyphens: auto;\nhyphens: auto;"
		);
		
		add_option('acc_options', $default);
		
	}
	
	// Cleaning on deactivation
	
	function _uninstall() {
		
		delete_option('acc_options');
		
	}
	
	// updating options in case they are outdated
	
	function _update_options() {
		
		$options_old = get_option('acc_options');
		
		$options_new['css'] = (isset($options_old['acc_css'])) ? $options_old['acc_css'] : @$options_old['css'];
		
		$options_new['cache'] = array();
		
		$options_new['inline'] = (isset($options_old['inline'])) ? $options_old['inline'] : false;
		
		$options_new['compress'] = (isset($options_old['compress'])) ? $options_old['compress'] : false;
		
		$options_new['version'] = self::version;
		
		if (!strstr($options_new['css'], 'hyphens')) $options_new['css'] .= "-moz-hyphens: auto;\n-o-hyphens: auto;\n-webkit-hyphens: auto;\n-ms-hyphens: auto;\nhyphens: auto;".$options_old['css'];
		
		update_option('acc_options', $options_new);
	
	}

}

$AdvancedCategoryColumn = new AdvancedCategoryColumn;

?>