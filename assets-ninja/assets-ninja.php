<?php
/* Plugin Name: Asset Ninja
	 * Plugin URI: http://example.com
	 * Description: Asset management in wordpress
	 * Version: 1.0
	 * Author: Me
	 * Author URI: http://example.com
	 * Text Domain: asset_ninja
	 * Domain Path: /languages/
	 */


define("ASSETS_DIR", plugin_dir_url($file = __FILE__)."assets");
define("ASSETS_PUBLIC_DIR",plugin_dir_url($file = __FILE__)."assets/public");
define("ASSETS_ADMIN_DIR", plugin_dir_url($file = __FILE__)."assets/admin");

class AssetsNinja
{
    private $version;
    public function __construct()
    {
        $this->version = time();
        add_action(
            $tag = 'plugins_loaded',
            $function_to_add = array($this, 'textDomain')
        );
        add_action(
            $tag = 'wp_enqueue_scripts',
            $function_to_add = array($this, 'load_front_assets')
        );
    }

    public function textDomain()
    {
        # code...
        load_plugin_textdomain(
            $domain = 'assetninja',
            $deprecated = false,
            $plugin_rel_path = plugin_dir_url($file = __FILE__) . "/languages"
        );
    }

    public function load_front_assets()
    {
        # code...
        wp_enqueue_script(
            $handle = 'assetninja-main',
            $src = ASSETS_PUBLIC_DIR . "/js/main.js",
            $deps = array('jquery'),
            $ver = $this->version,
            $in_footer = true
        );

        wp_enqueue_script(
            $handle = 'assetninja-another',
            $src = ASSETS_PUBLIC_DIR . "/js/another.js",
 
			$deps = array('assetninja-main'),
            $ver = $this->version,
            $in_footer = true
		);
		
		$data = array(
			1
		);
        wp_localize_script(
            $handle = 'assetninja-another',
            $name = 'sitedata',
            $data = $data
        );
    }
}new AssetsNinja;
