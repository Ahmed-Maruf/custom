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
	
	define('ASSETS_DIR', plugin_dir_url(__FILE__)."assets");
	define('ASSETS_PUBLIC_DIR', plugin_dir_url(__FILE__)."assets/public");
	define('ASSETS_ADMIN_DIR', plugin_dir_url(__FILE__)."assets/admin");

	if (!class_exists('assetNinja')) {
		class assetNinja {
			private $version;
			private $data;
			public function __construct() {
				$this->version = time();
				add_action( $tag = 'plugins_loaded', $callback = array($this,'asset_ninja_load_text_domain'));
				add_action( $tag = 'wp_enqueue_scripts', $function_to_add = array($this,'asset_ninja_front_assets'));

				add_action( $tag = 'admin_enqueue_scripts', $function_to_add = array($this,'asset_ninja_admin_assets'));
			}

			public function asset_ninja_admin_assets($hook)
			{
				# code...
			}

			public function asset_ninja_load_text_domain($hook)
			{
				load_plugin_textdomain( $domain = 'asset-ninja-text_domain', $deprecated = false, $plugin_rel_path = plugin_dir_url(__FILE__).'/languages');
			}

			public function asset_ninja_front_assets($hook)
			{
				# code...
				wp_enqueue_script($handle_name = 'asset_ninja_main_js', $file_src = ASSETS_PUBLIC_DIR . "/js/main.js", $deps = array('jquery','asset_ninja_another_js'), $version = null, $in_footer = true);

				wp_enqueue_script($handle_name = 'asset_ninja_another_js', $file_src = ASSETS_PUBLIC_DIR . "/js/another.js", $deps = array('jquery'), $version = null, $in_footer = true);

				$this->data = array(
					'name' => 'Ahmed',
					'ocupation' => 'Developer',
					'pasion' => 'Exploring' 
				);
				wp_localize_script( $handle = 'asset_ninja_another_js', $object_name = 'phpvar', $this->data);

			}

		}

		new assetNinja;
	}