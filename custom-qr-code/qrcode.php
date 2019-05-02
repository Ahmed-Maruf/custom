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

function qr_load_textdomain(){
	load_plugin_textdomain($domain = 'custom-qr-code', $deprecated = false, $plugin_rel_path = dirname(__FILE__)."/languages");
}add_action($tag = 'plugins_loaded', $function_to_add = 'qr_load_textdomain');


function pqrc_display_qr_code($content){
	$current_post_id = get_the_ID();
	$current_post_title = get_the_title($post = $current_post_id);
	$current_post_url = urlencode( get_the_permalink($post = $current_post_id) );
	$currnet_post_type = get_post_type($post = $current_post_id);
	
	/*POST TYPE CHECK*/
	$excluded_post_types = apply_filters($tag = 'pqrc_excluded_post_types', $value = array());
	if(in_array($needle = $currnet_post_type, $excluded_post_types)){
		return $content;
	}
	
	/*Image Diemention*/
	$height = get_option('pqrc_display_height')?get_option('pqrc_display_height'):180;
	$width = get_option('pqrc_display_width')?get_option('pqrc_display_width'):180;
	$image_dimention = apply_filters($tag = 'pqrc_image_dimention', $dimention = '{$height}x{$width}');

	$image_src = sprintf('http://api.qrserver.com/v1/create-qr-code/?color=000000&bgcolor=FFFFFF&data=%s&qzone=1&margin=0&size=%s&ecc=L',$current_post_url,$image_dimention);
	return $content .= sprintf("<div class='qrcode'><img src = '%s' alt='%s'/></div>",$image_src,$current_post_title);	
}
add_filter($tag = 'the_content', $function_to_add = 'pqrc_display_qr_code');

function pqrc_settings_init(){

	add_settings_section($id = 'pqrc_section', $title = __('Post to QR Code'), $callback = 'pqrc_section_callback', $page = 'general');
	add_settings_field($id = 'pqrc_display_height', $title = __('QR Code Height','custom-qr-code'), $callback = 'pqrc_display_height', $page = 'general', $section = 'pqrc_section');
	add_settings_field($id = 'pqrc_display_width', $title = __('QR Code Width','custom-qr-code'), $callback = 'pqrc_display_width', $page = 'general', $section = 'pqrc_section');

	register_setting($option_group = 'general', $option_name = 'pqrc_display_height', $sanitize_callback = array('sanitize_callback' => 'esc_attr'));
	register_setting($option_group = 'general', $option_name = 'pqrc_display_width', $sanitize_callback = array('sanitize_callback' => 'esc_attr'));

}add_action($tag = 'admin_init', $function_to_add = 'pqrc_settings_init');

function pqrc_section_callback(){
	echo "<p>".__("Settings for Qr Code","post-to-qr-code")."</p>";
}
function pqrc_display_height(){
	$height = get_option($option = 'pqrc_display_height');
	printf("<input type='text' id='%s' name='%s' value='%s'/>",'pqrc_height','pqrc_display_height',$height);
}

function pqrc_display_width(){
	$width = get_option($option = 'pqrc_display_width');
	printf("<input type='text' id='%s' name='%s' value='%s'/>",'pqrc_width','pqrc_display_width',$width);
}