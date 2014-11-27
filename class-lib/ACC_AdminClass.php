<?php

/**
 *
 * Class Recent Post Widget Admin
 *
 * @ A5 Recent Post Widget
 *
 * building admin page
 *
 */
class ACC_Admin extends A5_OptionPage {
	
	const language_file = 'advanced-cc';
	
	static $options;
	
	function __construct() {
	
		add_action('admin_init', array($this, 'initialize_settings'));
		add_action('admin_menu', array($this, 'add_admin_menu'));
		if (WP_DEBUG == true) add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
		
		self::$options = get_option('acc_options');
		
	}
	
	/**
	 *
	 * Add options-page for single site
	 *
	 */
	function add_admin_menu() {
		
		add_options_page('Advanced CC '.__('Settings', self::language_file), '<img alt="" src="'.plugins_url('advanced-category-column/img/a5-icon-11.png').'"> Advanced Category Column', 'administrator', 'advanced-cc-settings', array($this, 'build_options_page'));
		
	}
	
	/**
	 *
	 * Make debug info collapsable
	 *
	 */
	function enqueue_scripts($hook){
		
		if ($hook != 'settings_page_advanced-cc-settings') return;
		
		wp_enqueue_script('dashboard');
		
		if (wp_is_mobile()) wp_enqueue_script('jquery-touch-punch');
		
	}
	
	/**
	 *
	 * Actually build the option pages
	 *
	 */
	function build_options_page() {
		
		$eol = "\r\n";
		
		self::open_page('Advanced Category Column', __('http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/advanced-category-column-plugin', self::language_file), 'advanced-category-column', __('Plugin Support', self::language_file));
		
		_e('Style the links of the widget. If you leave this empty, your theme will style the hyperlinks.', self::language_file);
		
        self::tag_it(__('Just input something like,', self::language_file), 'p', false, false, true);
		
		self::tag_it(self::tag_it('font-weight: bold;<br />'.$eol.'color: #0000ff;<br />text-decoration: underline;', 'strong', 1), 'p', false, false, true);
		
		self::tag_it(__('to get fat, blue, underlined links.', self::language_file), 'p', false, false, true);
		
		self::tag_it(self::tag_it(__('You most probably have to use &#34;!important&#34; at the end of each line, to make it work.', self::language_file), 'strong', 1), 'p', false, false, true);
		
		self::open_form('options.php');
		
		settings_fields('acc_options');
		do_settings_sections('acc_styles');
		
		submit_button();
		
		if (WP_DEBUG === true) :
		
			self::open_tab();
			
			self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', self::language_file)));
		
			self::close_tab();
		
		endif;
		
		self::close_page();
		
	}
	
	/**
	 *
	 * Initialize the admin screen of the plugin
	 *
	 */
	function initialize_settings() {
		
		register_setting( 'acc_options', 'acc_options', array($this, 'validate') );
		
		add_settings_section('acc_settings', __('Styling of the links', self::language_file), array($this, 'acc_display_section'), 'acc_styles');
		
		add_settings_field('acc_link', __('Link style:', self::language_file), array($this, 'acc_link_input'), 'acc_styles', 'acc_settings');
		
		add_settings_field('acc_hover', __('Hover style:', self::language_file), array($this, 'acc_hover_input'), 'acc_styles', 'acc_settings');
		
		add_settings_field('acc_css', __('Widget container:', self::language_file), array($this, 'acc_css_input'), 'acc_styles', 'acc_settings', array(__('You can enter your own style for the widgets here. This will overwrite the styles of your theme.', self::language_file), __('If you leave this empty, you can still style every instance of the widget individually.', self::language_file)));
		
		add_settings_field('acc_compress', __('Compress Style Sheet:', self::language_file), array($this, 'acc_compress_input'), 'acc_styles', 'acc_settings', array(__('Click here to compress the style sheet.', self::language_file)));
		
		add_settings_field('acc_inline', __('Debug:', self::language_file), array($this, 'acc_inline_input'), 'acc_styles', 'acc_settings', array(__('If you can&#39;t reach the dynamical style sheet, you&#39;ll have to display the styles inline. By clicking here you can do so.', self::language_file)));
		
		$cachesize = count(self::$options['cache']);
		
		$entry = ($cachesize > 1) ? __('entries', self::language_file) : __('entry', self::language_file);
		
		if ($cachesize > 0) add_settings_field('acc_reset', sprintf(__('Empty cache (%d %s):', self::language_file), $cachesize, $entry), array($this, 'acc_reset_input'), 'acc_styles', 'acc_settings', array(__('You can empty the plugin&#39;s cache here, if necessary.', self::language_file)));
		
		add_settings_field('acc_resize', false, array($this, 'resize_field'), 'acc_styles', 'acc_settings');
	
	}
	
	function acc_display_section() {
		
		echo '<p>'.__('Just put some css code here.', self::language_file).'</p>';
	
	}
	
	function acc_link_input() {
		
		a5_textarea('link', 'acc_options[link]', @self::$options['link'], false, array('cols' => 35, 'rows' => 3));
		
	}
	
	function acc_hover_input() {
		
		a5_textarea('hover', 'acc_options[hover]', @self::$options['hover'], false, array('cols' => 35, 'rows' => 3));
		
	}
	
	function acc_css_input($labels) {
		
		echo $labels[0].'</br>'.$labels[1].'</br>';
		
		a5_textarea('css', 'acc_options[css]', @self::$options['css'], false, array('rows' => 7, 'cols' => 35));
		
	}
	
	function acc_compress_input($labels) {
		
		a5_checkbox('compress', 'acc_options[compress]', @self::$options['compress'], $labels[0]);
		
	}
	
	function acc_inline_input($labels) {
		
		a5_checkbox('inline', 'acc_options[inline]', @self::$options['inline'], $labels[0]);
		
	}
	
	function acc_reset_input($labels) {
		
		a5_checkbox('reset_options', 'acc_options[reset_options]', @self::$options['reset_options'], $labels[0]);
		
	}
	
	function resize_field() {
		
		a5_resize_textarea(array('link', 'hover', 'css'), true);
		
	}
		
	function validate($input) {
		
		self::$options['link']=trim($input['link']);
		self::$options['hover']=trim($input['hover']);
		self::$options['css']=trim($input['css']);
		self::$options['compress'] = isset($input['compress']) ? true : false;
		self::$options['inline'] = isset($input['inline']) ? true : false;
		
		if (isset($input['reset_options'])) :
		
			self::$options['cache'] = array();
			
			add_settings_error('acc_options', 'empty-cache', __('Cache emptied.', self::language_file), 'updated');
			
		endif;
		
		return self::$options;
	
	}

} // end of class

?>