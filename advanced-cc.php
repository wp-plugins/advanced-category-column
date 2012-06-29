<?php
/*
Plugin Name: Advanced Category Column
Plugin URI: http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/advanced-category-column-plugin
Description: The Advanced Category Column does, what my Category Column Plugin does; it creates a widget, which you can drag to your sidebar and it will show excerpts of the posts of other categories than showed in the center-column. It just has more options than the the Category Column Plugin. It is tested with WP up to version 3.4 and it might work with versions down to 2.7, but that will never be explicitly supported for those. The 'Advanced' means, that you have a couple of more options than in the 'Category Column Plugin'.
Version: 2.5.1
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

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) die('Sorry, you don&#39;t have direct access to this page.');

define( 'ACC_PATH', plugin_dir_path(__FILE__) );

if (!class_exists('A5_Thumbnail')) require_once ACC_PATH.'class-lib/A5_ImageClasses.php';
if (!class_exists('A5_Excerpt')) require_once ACC_PATH.'class-lib/A5_ExcerptClass.php';
if (!class_exists('A5_WidgetControlClass')) require_once ACC_PATH.'class-lib/A5_WidgetControlClass.php';
if (!class_exists('Advanced_Category_Column_Widget')) require_once ACC_PATH.'class-lib/ACC_WidgetClass.php';

class AdvancedCCPlugin {
	
	static $language_file = 'advanced-cc';
	
	function AdvancedCCPlugin() {
		
		// Load language files
	
		load_plugin_textdomain(self::$language_file, false , basename(dirname(__FILE__)).'/languages');
		
		add_action('admin_enqueue_scripts', array($this, 'acc_js_sheet'));
		add_filter('plugin_row_meta', array($this, 'acc_register_links'),10,2);	
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
		if ($file == $base) {
			$links[] = '<a href="http://wordpress.org/extend/plugins/advanced-category-column/faq/" target="_blank">'.__('FAQ', self::$language_file).'</a>';
			$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BC9QUKBEZFZFY" target="_blank">'.__('Donate', self::$language_file).'</a>';
		}
		
		return $links;
	
	}
	
	function acc_plugin_action_links( $links, $file ) {
		
		$base = plugin_basename(__FILE__);
		
		if ($file == $base) array_unshift($links, '<a href="'.admin_url( 'options-general.php?page=advanced-cc-settings' ).'">'.__('Settings', self::$language_file).'</a>');
	
		return $links;
	
	}
	
	// init
	
	function acc_init() {
		
		register_setting( 'acc_options', 'acc_options', array($this, 'acc_validate') );
		
		add_settings_section('acc_settings', __('Styling of the links', self::$language_file), array($this, 'acc_display_section'), 'acc_styles');
		
		add_settings_field('acc_link_style', __('Link style:', self::$language_file), array($this, 'acc_link_field'), 'acc_styles', 'acc_settings');
		
		add_settings_field('acc_hover_style', __('Hover style:', self::$language_file), array($this, 'acc_hover_field'), 'acc_styles', 'acc_settings');
	
	}
	
	function acc_display_section() {
		
		echo '<p>'.__('Just put some css code here.', self::$language_file).'</p>';
	
	}
	
	function acc_link_field() {
		
		$acc_options = get_option('acc_options');
		
		echo '<textarea id="acc_link" name="acc_options[link]" cols="35">'.$acc_options['link'].'</textarea>';
		
	}
	
	function acc_hover_field() {
		
		$acc_options = get_option('acc_options');
		
		echo '<textarea id="acc_hover" name="acc_options[hover]" cols="35">'.$acc_options['hover'].'</textarea>';
		
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
		
		add_options_page(__('Advanced CC Settings', self::$language_file), 'Advanced Category Column', 'administrator', 'advanced-cc-settings', array($this, 'advanced_cc_options_page'));
		
	}
	
	// Calling the options page
	
	function advanced_cc_options_page() {
		
		?>
	<div>
	  <h2><?php _e('Advanced Category Column Settings', self::$language_file); ?></h2>
	  <?php settings_errors(); ?>
	  <?php _e('Style the links of the widget. If you leave this empty, your theme will style the hyperlinks.', self::$language_file); ?>
	  <p>
		<?php _e('Just input something like,', self::$language_file); ?>
	  <p><strong>font-weight: bold;<br />
		color: #0000ff;<br />
		text-decoration: underline;</strong></p>
	  <?php _e('to get fat, blue, underlined links.', self::$language_file); ?>
	  </p>
	  <p><strong>
		<?php _e('You most probably have to use &#34;!important&#34; at the end of each line, to make it work.', self::$language_file); ?>
		</strong></p>
	  <form action="options.php" method="post">
		<?php
		
		settings_fields('acc_options');
		do_settings_sections('acc_styles');
		
		?>
		<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
	  </form>
	</div>
	<script type="text/javascript"><!--
	jQuery(document).ready(function() {
		jQuery("textarea").autoResize();
	});
	--></script>
	<?php
	}
	
	function acc_validate($input) {
		
		$acc_options = get_option('acc_options');
		
		$newinput['link']=trim($input['link']);
		$newinput['hover']=trim($input['hover']);
		$newinput['tags']=$acc_options['tags'];
		$newinput['sizes']=$acc_options['sizes'];
		
		return $newinput;
	
	}
	
	function acc_add_rewrite() {
		   global $wp;
		   $wp->add_query_var('accfile');
	}
	
	function acc_css_template() {
		   if (get_query_var('accfile') == 'css') {
				   
				   header('Content-type: text/css');
				   echo $this->acc_write_css();
				   
				   exit;
		   }
	}

	function acc_css () {
		
		$acc_options = get_option('acc_options');
		
		if (!empty ($acc_options['link']) || !empty ($acc_options['hover'])) {
			
			$acc_css_file=get_bloginfo('url').'/?accfile=css';
			
			wp_register_style('advanced-cc', $acc_css_file, false, '2.5.1', 'all');
			wp_enqueue_style( 'advanced-cc');
				
		}
		
	}
	
	// writing css file
		
	function acc_write_css() {
		
		$eol = "\r\n";
		$tab = "\t";
		
		$acc_styles=get_option('acc_options');
		
		$acc_link=str_replace(array("\r\n", "\n", "\r"), ' ', $acc_styles['link']);
		$acc_hover=str_replace(array("\r\n", "\n", "\r"), ' ', $acc_styles['hover']);
		
		$css_text='@charset "UTF-8";'.$eol.'/* CSS Document */'.$eol.$eol;
		
		$css_text.='.acclink {'.$eol.$tab.$acc_link.$eol.'}'.$eol.'.acclink:hover {'.$eol.$tab.$acc_hover.$eol.'}';
		
		return $css_text;
		
	}
}

$advancedccplugin = new AdvancedCCPlugin;

?>
