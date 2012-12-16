<?php
/*
Plugin Name: Advanced Category Column
Plugin URI: http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/advanced-category-column-plugin
Description: The Advanced Category Column does, what the Category Column Plugin does; it creates a widget, which you can drag to your sidebar and it will show excerpts of the posts of other categories than showed in the center-column. It just has more options than the the Category Column Plugin. It is tested with WP up to version 3.6 and it might work with versions down to 2.9, but will never be explicitly supported for those. The 'Advanced' means, that you have a couple of more options than in the 'Category Column Plugin'.
Version: 2.7
Author: Waldemar Stoffel
Author URI: http://www.waldemarstoffel.com
License: GPL3
Text Domain: advanced-cc 
*/

/*  Copyright 2011  Waldemar Stoffel  (email : w-stoffel@gmx.net)

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
if (!function_exists('a5_textarea')) require_once ACC_PATH.'includes/A5_field-functions.php';

class AdvancedCCPlugin {
	
	const language_file = 'advanced-cc';
	
	private static $options;
	
	function AdvancedCCPlugin() {
		
		self::$options = get_option('acc_options');
		
		// Load language files
	
		load_plugin_textdomain(self::language_file, false , basename(dirname(__FILE__)).'/languages');
		
		add_action('admin_enqueue_scripts', array($this, 'acc_js_sheet'));
		add_filter('plugin_row_meta', array($this, 'acc_register_links'), 10, 2);	
		add_filter( 'plugin_action_links', array($this, 'acc_plugin_action_links'), 10, 2 );
		add_action('admin_init', array($this, 'acc_init'));
		register_activation_hook(  __FILE__, array($this, 'install_acc') );
		register_deactivation_hook(  __FILE__, array($this, 'unset_acc') );
		add_action('admin_menu', array ($this, 'acc_admin_menu'));
		add_action('init', array ($this, 'acc_add_rewrite'));
		add_action('template_redirect', array ($this, 'acc_css_template'));
		add_action ('wp_enqueue_scripts', array ($this, 'acc_css'));
		
	}
	
	// attach JavaScript file for textarea resizing
	
	function acc_js_sheet($hook) {
		
		if ($hook != 'widgets.php' && $hook != 'settings_page_advanced-cc-settings') return;
		
		wp_register_script('ta-expander-script', plugins_url('ta-expander.js', __FILE__), array('jquery'), '2.0', true);
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
		
		add_settings_field('acc_resize', false, array($this, 'acc_resize_field'), 'acc_styles', 'acc_settings');
	
	}
	
	function acc_display_section() {
		
		echo '<p>'.__('Just put some css code here.', self::language_file).'</p>';
	
	}
	
	function acc_link_field() {
		
		a5_textarea('link', 'acc_options[link]', self::$options['link'], false, array('cols' => 35, 'rows' => 3));
		
	}
	
	function acc_hover_field() {
		
		a5_textarea('hover', 'acc_options[hover]', self::$options['hover'], false, array('cols' => 35, 'rows' => 3));
		
	}
	
	function acc_resize_field() {
		
		a5_resize_textarea(array('link', 'hover'), true);
		
	}
	
	// Creating default options on activation
	
	function install_acc() {
		
		$default = array(
			'tags' => array(),
			'sizes' => array()
		);
		
		add_option('acc_options', $default);
		
	}
	
	// Cleaning on deactivation
	
	function unset_acc() {
		
		delete_option('acc_options');
		
	}
	
	// Installing options page
	
	function acc_admin_menu() {
		
		add_options_page('Advanced CC '.__('Settings', self::language_file), '<img alt="" src="'.plugins_url('advanced-category-column/img/a5-icon-11.png').'"> Advanced Category Column', 'administrator', 'advanced-cc-settings', array($this, 'advanced_cc_options_page'));
		
	}
	
	// Calling the options page
	
	function advanced_cc_options_page() {
		
		?>
	<div class="wrap">
    <a href="<?php _e('http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/advanced-category-column-plugin', self::language_file); ?>"><div id="a5-logo" class="icon32" style="background: url('<?php echo plugins_url('advanced-category-column/img/a5-icon-34.png');?>');"></div></a>
	  <h2>Advanced Category Column <?php _e('Settings', self::language_file); ?></h2>
	  <?php settings_errors(); ?>
	  <?php _e('Style the links of the widget. If you leave this empty, your theme will style the hyperlinks.', self::language_file); ?>
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
	  <form action="options.php" method="post">
		<?php
		
		settings_fields('acc_options');
		do_settings_sections('acc_styles');
		
		submit_button();
		
		?>
	  </form>
	</div>
	<?php
	
	}
	
	function acc_validate($input) {
		
		self::$options['link']=trim($input['link']);
		self::$options['hover']=trim($input['hover']);
		
		return self::$options;
	
	}
	
	function acc_add_rewrite() {
		
		   global $wp;
		   $wp->add_query_var('accfile');
	
	}
	
	function acc_css_template() {
		
		   if (get_query_var('accfile') == 'css') {
				   
				   header('Content-type: text/css');
				   echo $this->acc_dss();
				   
				   exit;
		   }
	}

	function acc_css () {
		
		$acc_css_file=get_bloginfo('url').'/?accfile=css';
			
		wp_register_style('advanced-cc', $acc_css_file, false, '2.6', 'all');
		wp_enqueue_style('advanced-cc');
		
	}
	
	// writing dss file
		
	function acc_dss() {
		
		self::$options = get_option('acc_options');
		
		$eol = "\r\n";
		$tab = "\t";
		
		$css_text='@charset "UTF-8";'.$eol.'/* CSS Document */'.$eol.$eol;
		
		$css_text.='p[id^="acc_byline"] {'.$eol.'font-size: 0.9em;'.$eol.'}'.$eol;
		
		$css_text.='p[id^="acc_byline"] a {'.$eol.'text-decoration: none !important;'.$eol.'font-weight: normal !important;'.$eol.'}'.$eol;
		
		if (!empty (self::$options['link']) || !empty (self::$options['hover'])) :
		
			$acc_link=str_replace(array("\r\n", "\n", "\r"), ' ', self::$options['link']);
			$acc_hover=str_replace(array("\r\n", "\n", "\r"), ' ', self::$options['hover']);
			
			$css_text.='div[id^="advanced_category_column_widget"].widget_advanced_category_column_widget a {'.$eol.$tab.self::$options['link'].$eol.'}'.$eol.'div[id^="advanced_category_column_widget"].widget_advanced_category_column_widget a:hover {'.$eol.$tab.self::$options['hover'].$eol.'}';
			
		endif;
		
		return $css_text;
		
	}
}

$advanced_cc_plugin = new AdvancedCCPlugin;

?>
