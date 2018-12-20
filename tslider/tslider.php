<?php
	/**
	 * Plugin Name: tslider
	 * Description: A slider with shortcodes
	 * Plugin URI: http://
	 * Author: Ahmed Maruf
	 * Author URI: 
	 * Version: 1.0.0
	 * License: GPL2
	 * Text Domain: t-slider
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

	    function tslider_register_activation_hook(){

	    }register_activation_hook( $file = __FILE__,$callback = 'tslider_register_activation_hook');

	    function tslider_register_deactivation_hook(){

	    }register_deactivation_hook( $file = __FILE__, $callback = 'tslider_register_deactivation_hook' );

	    function tslider_load_plugin_text_domain(){
	    	load_plugin_textdomain( $domain = 't-slider', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	    }add_action( $tag = 'plugins_loaded', $callback = 'tslider_load_plugin_text_domain');


	    