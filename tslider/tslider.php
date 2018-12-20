<?php
	/**
	 * Plugin Name: tslider
	 * Description: A slider with shortcodes
	 * Plugin URI: http://
	 * Author: Ahmed Maruf
	 * Author URI:
	 * Version: 1.0.0
	 * License: GPL2
	 * Text Domain: t_slider
	 * Domain Path: /languages/
	 */
	
	/*
	    Copyright (C) Year  Author  Email
	
	    This program is free software; you can redistribute it and/or modify
	    it under the terms of the GNU General Public License, version 2, as
	    published by the Free Software Foundation.
	
	    This program is distributed in the hope that it will be useful,
	    but WITHOUT ANY WARRANTY; without even the implied warranty of
	    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	    GNU General Public License for more details.
	
	    You should have received a copy of the GNU General Public License
	    along with this program; if not, write to the Free Software
	    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/
	
	function t_slider_register_activation_hook()
	{
	
	}
	
	register_activation_hook($file = __FILE__, $callback = 't_slider_register_activation_hook');
	
	function t_slider_register_deactivation_hook()
	{
	
	}
	
	register_deactivation_hook($file = __FILE__, $callback = 't_slider_register_deactivation_hook');
	
	function t_slider_load_plugin_text_domain()
	{
		load_plugin_textdomain($domain = 't_slider', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}
	
	add_action($tag = 'plugins_loaded', $callback = 't_slider_load_plugin_text_domain');
	
	function tiny_init(){
		add_image_size('tiny-slider',800,600,true);
	}add_action('init','tiny_init');
	
	function t_slider_enqueue_assets(){
		/*Enqueue all the style*/
		wp_enqueue_style($handle = 't-slider-css',$src = '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.1/tiny-slider.css', $deps = null, $version = '2.9.1');
		/*Enqueue all the scripts*/
		wp_enqueue_script($handle = 't-slider-js',$src = '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.1/min/tiny-slider.js',$deps = null, $version = '2.9.1', $footer = true);
		
		wp_enqueue_script($handle = 't-slider-main-js',$src = plugin_dir_url(__FILE__) . "assets/js/main.js", $deps = array('jquery'),$version = '1.0', $footer = true);
		
	}
	add_action('wp_enqueue_scripts','t_slider_enqueue_assets');
	
	function t_slider_container_shortcodes($attributes, $content)
	{
		$default = array(
			'width' => 800,
			'height' => 600,
			'id' => ''
		);
		$content = do_shortcode($content);
		$shortcodes_attributes = shortcode_atts($default,$attributes);
		
		$shortcode_output = <<<EOD
		<div id="{$shortcodes_attributes['id']}" class="t-slider" style="width: {$shortcodes_attributes['width']}; height: {$shortcodes_attributes['height']};">
			{$content}
		</div>
EOD;
		return $shortcode_output;
	}
	add_shortcode($tag = 'tslider', $callback = 't_slider_container_shortcodes');
	
	function t_slider_body_shortcodes($attributes)
	{
		$default = array(
			'id' => '',
			'caption' => '',
			'size' => 'tiny-slider'
		);
		$shortcodes_attributes = shortcode_atts($default,$attributes);
		$img_src = wp_get_attachment_image_src($shortcodes_attributes['id'],$shortcodes_attributes['size']);
		
		$shortcodes_output = <<<EOD
		<div class="slide">
			<p><img src="{$img_src[0]}" alt="{$attributes['caption']}"></p>
			<p>{$attributes['caption']}</p>
		</div>
EOD;
		
		return $shortcodes_output;

	}
	add_shortcode($tag = 'tslide', $callback = 't_slider_body_shortcodes');
	    


	    