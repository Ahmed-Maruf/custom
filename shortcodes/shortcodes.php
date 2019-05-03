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
function shortcodes_register_activation_hook()
{ }
register_activation_hook($file = __FILE__, $callback = 'shortcodes_register_activation_hook');

function shortcodes_register_deactivation_hook()
{ }
register_deactivation_hook($file = __FILE__, $callback = 'shortcodes_register_deactivation_hook');

function shortcodes_load_text_domain()
{
	load_plugin_textdomain($domain = 'short-codes', $deprecated = false, $rel_path = dirname($path = __FILE__) . "/languages");
}
add_action($handle = "plugins_loaded", $callback = "shortcodes_load_text_domain");



function button($attr){
	return sprintf('<a class="btn btn-%s" href="%s">%s</a>',$attr['type'],$attr['url'],$attr['title']);

}
add_shortcode(
	$tag = 'button', 
	$func = 'button'
);