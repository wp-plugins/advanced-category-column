<?php
/*
Plugin Name: Advanced Category Column
Plugin URI: http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/advanced-category-column-plugin
Description: The Advanced Category Column does, what my Category Column Plugin does; it creates a widget, which you can drag to your sidebar and it will show excerpts of the posts of other categories than showed in the center-column. It just has more options than the the Category Column Plugin. It is tested with WP up to version 3.4 and it might work with versions down to 2.7, but that will never be explicitly supported for those. The 'Advanced' means, that you have a couple of more options than in the 'Category Column Plugin'.
Version: 2.4
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

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) die(__('Sorry, you don&#39;t have direct access to this page.'));

/* attach JavaScript file for textarea resizing */

function acc_js_sheet() {
	
	wp_enqueue_script('ta-expander-script', plugins_url('ta-expander.js', __FILE__), array('jquery'), '2.0', true);

}

add_action('admin_print_scripts-widgets.php', 'acc_js_sheet');

//Additional links on the plugin page

add_filter('plugin_row_meta', 'acc_register_links',10,2);

function acc_register_links($links, $file) {
	
	$base = plugin_basename(__FILE__);
	if ($file == $base) {
		$links[] = '<a href="http://wordpress.org/extend/plugins/advanced-category-column/faq/" target="_blank">'.__('FAQ', $acc_language_file).'</a>';
		$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BC9QUKBEZFZFY" target="_blank">'.__('Donate', $acc_language_file).'</a>';
	}
	
	return $links;

}

add_filter( 'plugin_action_links', 'acc_plugin_action_links', 10, 2 );

function acc_plugin_action_links( $links, $file ) {
	
	$base = plugin_basename(__FILE__);
	
	if ($file == $base) array_unshift($links, '<a href="'.admin_url( 'options-general.php?page=advanced-cc-settings' ).'">'.__('Settings', $acc_language_file).'</a>');

	return $links;

}

define( 'ACC_PATH', plugin_dir_path(__FILE__) );

if (!class_exists('A5_Thumbnail')) require_once ACC_PATH.'class-lib/A5_ImageClasses.php';
if (!class_exists('A5_Excerpt')) require_once ACC_PATH.'class-lib/A5_ExcerptClass.php';
if (!class_exists('Advanced_Category_Column_Widget')) require_once ACC_PATH.'class-lib/ACC_WidgetClass.php';



// import laguage files

$acc_language_file = 'advanced-cc';

load_plugin_textdomain($acc_language_file, false , basename(dirname(__FILE__)).'/languages');

// init

add_action('admin_init', 'acc_init');

function acc_init() {
	
	global $acc_language_file;
	
	register_setting( 'acc_options', 'acc_options', 'acc_validate' );
	
	add_settings_section('acc_settings', __('Styling of the links', $acc_language_file), 'acc_display_section', 'acc_styles');
	
	add_settings_field('acc_link_style', __('Link style:', $acc_language_file), 'acc_link_field', 'acc_styles', 'acc_settings');
	
	add_settings_field('acc_hover_style', __('Hover style:', $acc_language_file), 'acc_hover_field', 'acc_styles', 'acc_settings');

}

function acc_display_section() {
	
	global $acc_language_file;
	
	echo '<p>'.__('Just put some css code here.', $acc_language_file).'</p>';

}

function acc_link_field() {
	
	global $acc_language_file;
	
	$acc_options = get_option('acc_options');
	
	echo '<textarea id="acc_link" name="acc_options[link]" cols="35">'.$acc_options['link'].'</textarea>';
	
}

function acc_hover_field() {
	
	global $acc_language_file;
	
	$acc_options = get_option('acc_options');
	
	echo '<textarea id="acc_hover" name="acc_options[hover]" cols="35">'.$acc_options['hover'].'</textarea>';
	
}

// Cleaning on deactivation

register_deactivation_hook(  __FILE__, 'unset_acc' );

function unset_acc() {
	
	delete_option('acc_options');
	
}

// Installing options page

add_action('admin_menu', 'acc_admin_menu');

function acc_admin_menu() {
	
	global $acc_language_file;
	
	$pages=add_options_page(__('Advanced CC Settings', $acc_language_file), 'Advanced Category Column', 'administrator', 'advanced-cc-settings', 'advanced_cc_options_page');
	
	add_action('admin_print_scripts-'.$pages, 'acc_js_sheet');
	
}

// Calling the options page

function advanced_cc_options_page() {
	
	global $acc_language_file;
	
	?>
<div>
  <h2><?php _e('Advanced Category Column Settings', $acc_language_file); ?></h2>
  <?php _e('Style the links of the widget. If you leave this empty, your theme will style the hyperlinks.', $acc_language_file); ?>
  <p>
    <?php _e('Just input something like,', $acc_language_file); ?>
  <p><strong>font-weight: bold;<br />
    color: #0000ff;<br />
    text-decoration: underline;</strong></p>
  <?php _e('to get fat, blue, underlined links.', $acc_language_file); ?>
  </p>
  <p><strong>
    <?php _e('You most probably have to use &#34;!important&#34; at the end of each line, to make it work.', $acc_language_file); ?>
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
	
	$newinput['link']=trim($input['link']);
	$newinput['hover']=trim($input['hover']);
	
	return $newinput;

}

add_action('init','acc_add_rewrite');
function acc_add_rewrite() {
       global $wp;
       $wp->add_query_var('accfile');
}

add_action('template_redirect','acc_css_template');
function acc_css_template() {
       if (get_query_var('accfile') == 'css') {
               
			   header('Content-type: text/css');
			   echo acc_write_css();
			   
               exit;
       }
}

add_action ('wp_print_styles', 'acc_css');
function acc_css () {
	
	$acc_options = get_option('acc_options');
	
	if (count ($acc_options)!=0 && !empty ($acc_options)) {
		
		$acc_css_file=get_bloginfo('url')."/?accfile=css";
		
		wp_register_style($acc_language_file, $acc_css_file, false, '2.2', 'all');
		wp_enqueue_style( $acc_language_file);
			
	}
	
}

// writing css file
	
function acc_write_css() {
	
	$acc_styles=get_option('acc_options');
	
	$acc_link=str_replace(array("\r\n", "\n", "\r"), ' ', $acc_styles['link']);
	$acc_hover=str_replace(array("\r\n", "\n", "\r"), ' ', $acc_styles['hover']);
	
	$css_text="@charset \"UTF-8\";\r\n/* CSS Document */\r\n\r\n";
	
	$css_text.=".acclink {".$acc_link."}\r\n.acclink:hover {".$acc_hover."}";
	
	return $css_text;
	
}

?>
