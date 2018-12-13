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
		$imageDimension = apply_filters('pqc_image_dimension','185x185');
		$imageSrc = sprintf('https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s',$imageDimension,$current_post_url);
		$content .= sprintf('<div class="qr-code"><img src="%s" alt="%s"></div>',$imageSrc,$current_post_title);
		return $content;
	}add_filter('the_content','pqc_display_qr_code');