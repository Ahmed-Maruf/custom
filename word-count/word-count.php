<?php
	/*
	Plugin Name:  Word Count
	Plugin URI:   https://developer.wordpress.org/plugins/word-count/
	Description:  Basic WordPress Plugin for word count in a post
	Version:      1.0
	Author:       Ahmed Maruf
	Author URI:
	License:      GPL2
	License URI:  https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain:  word-count
	Domain Path:  /languages/
	*/
	
	
	/*Function to run during the activation of a plugin*/
	function wordcount_activation_hook(){
	
	}register_activation_hook(__FILE__,'wordcount_activation_hook');
	
	/*Function to run during the deactivation of a plugin*/
	function wordcount_deactivation_hook(){
	
	}register_deactivation_hook(__FILE__,'wordcount_deactivation_hook');
	
	/*For translation hard coded string*/
	function wordcount_load_textdomain(){
		load_plugin_textdomain('word-count',false,dirname(__FILE__).'/languages');
	}add_action('plugins_loaded','wordcount_load_textdomain');
	
	/*Filter the content of a post by calling wp api*/
	function wordcount_count_words($content){
		
		$stripped_content = strip_tags($content);
		$totalWords = str_word_count($stripped_content);
		$label = __('Total Number of Words','word-count');
		$label = apply_filters('wordcount_label',$label);
		$tag = apply_filters('wordcount_tag','h2');
		$content.= sprintf('<%s>%s: %s</%s>',$tag,$label,$totalWords,$tag);
		return $content;
		
	}add_filter('the_content','wordcount_count_words');