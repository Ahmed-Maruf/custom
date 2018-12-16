<?php
	/*
	Plugin Name:  custom-qr-code
	Plugin URI:   https://developer.wordpress.org/plugins/
	Description:  Basic WordPress Plugin for qr-code of post and pages
	Version:      1.0
	Author:       Ahmed Maruf
	Author URI:
	Text Domain:  custom-qr-code
	Domain Path:  /languages/
	*/
	
	/*GLOBAL VARIABLES*/

	$pqc_countries = array(
		'None',
		'Afganistan',
		'Butan',
		'Bangladesh',
		'India',
		'Maldives',
		'Nepal',
		'Pakistan',
		'Srilanka'
	);
	/*Function to run during the initialization of the plugin*/
	function pqc_run_at_init(){
		global $pqc_countries;
		$pqc_countries = apply_filters('pqc_countries',$pqc_countries);	
	}add_action("init","pqc_run_at_init"); 
	
	/*Hook to run during activation of a plugin*/
	function pqc_activation_hook(){
		
	}register_activation_hook(__FILE__,'pqc_activation_hook');
	
	/*Hook to run during deactivation of a plugin*/
	function pqc_deactivation_hook(){
		
	}register_deactivation_hook(__FILE__,'pqc_deactivation_hook');
	
	
	/*Add text domain for the plugin on load*/
	function qrcode_load_textdomain(){
		load_plugin_textdomain('custom-qr-code',false,dirname(__FILE__).'/languages');
	}add_action('plugins_loaded','qrcode_load_textdomain');
	
	
	function pqc_display_qr_code($content){

		$current_post_id = get_the_ID();
		$current_post_title = get_the_title($current_post_id);
		$current_post_url = urlencode_deep(get_the_permalink($current_post_id));
		$current_post_type = get_post_type($current_post_id);
		
		$excluded_post = apply_filters('pqc_excluded_post_types',array());
		if (in_array($current_post_type,$excluded_post)){
			return $content;
		}

		/*Dimension Hook*/
		$height = get_option('pqc_height')?get_option('pqc_height'):180;
		$width = get_option('pqc_width')?get_option('pqc_width'):180;

		$imageDimension = apply_filters('pqc_image_dimension',"{$height}x{$width}");
		$imageSrc = sprintf('https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s',$imageDimension,$current_post_url);
		$content .= sprintf('<div class="qr-code"><img src="%s" alt="%s"></div>',$imageSrc,$current_post_title);
		return $content;
	}add_filter('the_content','pqc_display_qr_code');

	/*ADD SETTINGS FIELD*/
	function pqc_settings_init(){

		update_option('admin_email','test@dev.com');
		/*Add settings section to wp-admin*/
		add_settings_section('pqc_section',__( 'Posts to QR Code', $domain = 'custom-qr-code' ),'pqc_section_callback','general');

		/*Add a toggle filed from minitoggle.js*/

		add_settings_field( 'pqc_toggle',__('Toggle Field',$domain = 'custom-qr-code'), 'pqc_toggle_field', $page = 'general', $section = 'pqc_section', array( '' ) );
		/*Add a checkbox field to wp-admin*/

		add_settings_field( 'pqc_checkbox', __('Select Countries',$domain = 'custom-qr-code'), 'pqc_checkbox_field', $page = 'general', $section = 'pqc_section', array( '' ) );


		/*Add a text field for height to wp-admin*/
		add_settings_field( 'pqc_height', __('QR CODE HEIGHT','custom-qr-code'), 'pqc_display_field', 'general', $section = 'pqc_section', $args = array('pqc_height') );

		/*Add a text field for width to wp-admin*/
		add_settings_field( 'pqc_width', __('QR CODE HEIGHT','custom-qr-code'), 'pqc_display_field', 'general', $section = 'pqc_section', $args = array('pqc_width') );

		/*Add a select field including the sarc listed countries*/
		add_settings_field( 'pqc_select', __('QR Code Select Country',$domain = 'custom-qr-code'), $callback = 'pqc_display_select', $page = 'general', $section = 'pqc_section', $args = array() );


		/*Register the toggle field to save to options table*/

		register_setting( 'general', 'pqc_toggle', array( '' ) );
		/*Register the checkbox field for countries to save to optiosn table*/

		register_setting( 'general', 'pqc_checkbox', array( '' ) );


		/*Register the added select field for sarc countries to save to options table*/

		register_setting( 'general', 'pqc_select', $args = array('sanitize_callback' => 'esc_attr'));
		/*Register the added text field for height to save to options table*/
		register_setting('general','pqc_height',array('sanitize_callback' => 'esc_attr'));

		/*Register the added text filed for width to save to options table*/
		register_setting('general','pqc_width',array('sanitize_callback' => 'esc_attr'));
	}add_action("admin_init","pqc_settings_init");


	/*Callback function for toggle field*/

	function pqc_toggle_field()
	{
		$option = get_option('pqc_toggle');
		echo '<div id="toggle1"></div>';
		echo '<input type="hidden" name="pqc_toggle" id="pqc_toggle" value='.$option.'>';
	}
	/*Callback funtion for checkbox field*/
	function pqc_checkbox_field(){
		global $pqc_countries;
		$option = get_option('pqc_checkbox');
		foreach ($pqc_countries as $key => $value) {
			$selected = '';
			if (is_array($option) AND in_array($value,$option)) {
				$selected = 'checked';
			}
			printf('<input type="checkbox" value="%s" name="pqc_checkbox[]" %s> %s </br>',$value,$selected,$value);
		}
	}


	/*Callback function for select field*/
	function pqc_display_select(){
		global $pqc_countries;
		$option = get_option('pqc_select');
		printf('<select name="%s" id="%s">','pqc_select','pqc_select');
		foreach ($pqc_countries as $key => $value) {
			$selected = '';
			if ($option == $value) {
				$selected = 'selected';
			}
			printf('<option value="%s" %s>%s</option>',$value,$selected,$value);
		}
		echo '</select>';
	}


	/*Section callback to place all the registered field with placeholder*/
	function pqc_section_callback(){
		/*Do whatever you like*/

		echo "<p>".__('Settings for Posts to QR Plugin', $domain = 'custom-qr-code')."</p>";
	}

	/*Generic Callback function for text display field*/
	function pqc_display_field($args){
		$option = get_option($args[0]);
		printf('<input type="text" id="%s" name="%s" value="%s">',$args[0],$args[0],$option);
	}


	/*Enque Assets*/

	/**
	 * Enqueue scripts
	 *
	 * @param string $handle Script name
	 * @param string $src Script url
	 * @param array $deps (optional) Array of script names on which this script depends
	 * @param string|bool $ver (optional) Script version (used for cache busting), set to null to disable
	 * @param bool $in_footer (optional) Whether to enqueue the script before </head> or before </body>
	 */
	function pqc_assets($hook) {
		if ('options-general.php' == $hook) {

			wp_enqueue_style( 'minitoggle-css', plugin_dir_url(__FILE__)."assets/css/minitoggle.css", time());

			wp_enqueue_script( 'minitoggle-js', plugin_dir_url(__FILE__)."assets/js/minitoggle.js", array( 'jquery' ), "1.0", true ); 

			wp_enqueue_script( 'pqc-main-js', plugin_dir_url(__FILE__)."assets/js/pqc-main.js", array( 'jquery' ), time(), true );
			
		}
	}
	add_action( 'admin_enqueue_scripts', 'pqc_assets' );
	