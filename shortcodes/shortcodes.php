<?php
	/*
	Plugin Name:  ShortCodes
	Plugin URI:   https://developer.wordpress.org/plugins/word-count/
	Description:  Basic WordPress Plugin for word count in a post
	Version:      1.0
	Author:       Ahmed Maruf
	Author URI:
	License:      GPL2
	License URI:  https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain:  short-codes
	Domain Path:  /languages/
	*/
	
	/*Function + logic to call during activation hook*/
	function shortcodes_register_activation_hook(){
	
	}register_activation_hook($file = __FILE__, $callback = 'shortcodes_register_activation_hook');
	
	function shortcodes_register_deactivation_hook(){
	
	}register_deactivation_hook($file = __FILE__, $callback = 'shortcodes_register_deactivation_hook');
	
	function shortcodes_load_text_domain(){
		load_plugin_textdomain($domain = 'short-codes',$deprecated = false, $rel_path = dirname($path = __FILE__)."/languages");
	}add_action($handle = "plugins_loaded",$callback = "shortcodes_load_text_domain");
	
	function shortcodes_button($attributes){
		
		$default = array(
			'title' => 'Default Title'
		);
		
		$button_attributes = shortcode_atts($default,$attributes);
		
		return sprintf('<a class="btn btn-%s" href="%s">%s</a>',$button_attributes['type'],$button_attributes['url'],$button_attributes['title']);
	}
	add_shortcode($tag = 'button',$callback = 'shortcodes_button');
	
	function shortcodes_button2($attributes,$content){
		return sprintf('<a class="btn btn-%s" href="%s">%s</a>',$attributes['type'],$attributes['url'],do_shortcode($content));
	}
	add_shortcode($tag = 'button2',$callback = 'shortcodes_button2');
	
	function shortcodes_uppercase($attributes){
		$default = array(
			'content' => 'default text'
		);
		
		$shortcodes_attributes = shortcode_atts($default,$attributes);
		
		return sprintf('Converted to Uppercase: %s',strtoupper($shortcodes_attributes['content']));
	}
	add_shortcode($tag = 'uc',$callback = 'shortcodes_uppercase');
	
	function shortcodes_google_map($attributes){
		$default = array(
			'place' => 'Dhaka Museum'
		);
		
		$shortcodes_attributes = shortcode_atts($default,$attributes);
		
		$map = <<<EOD
		<div>
		    <div>
		        <iframe width="800" height="500"
		                src="https://maps.google.com/maps?q={$shortcodes_attributes['place']}&t=&z=13&ie=UTF8&iwloc=&output=embed"
		                frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
		        </iframe>
		    </div>
		</div>
EOD;
		
		return $map;
	}
	add_shortcode('gmap','shortcodes_google_map');