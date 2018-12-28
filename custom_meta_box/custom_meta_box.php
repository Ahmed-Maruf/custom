<?php 

/**
 * Plugin Name: Custom Meta Box
 * Description: Custom meta box in wordpress
 * Plugin URI: http://
 * Author: Author
 * Author URI: http://
 * Version: 1.0.0
 * License: GPL2
 * Text Domain: custom_meta_box
 * Domain Path: /languages
 */

/**
 * 
 */
class ourMetaBox
{
	/**
	 * Default constructor 
	 */
	function __construct()
	{
		# add load plugin textdomain action during plugin load...
		add_action( $tag = 'plugins_loaded', $function_to_add = array($this,'cmb_load_textdomain'));

		# add meta during the rendering of admin menu...
		add_action( $tag = 'admin_menu', $function_to_add = array($this,'cmb_add_metabox'));

		# save meta during the post save
		add_action( $tag = 'save_post', $function_to_add = array($this,'cmb_save_location'));
	}


	private function isSecure($action, $nonce_field, $post_id)
	{
		# code...
		$nonce = isset($_POST[$nonce_field])?$_POST[$nonce_field]:'';
		if ('' == $nonce) {
			# code...
			return false;
		}
		if (!wp_verify_nonce( $nonce, $action)) {
			# code...
			return false;
		}

		if (!current_user_can( $capability = 'edit_post', $post_id)) {
			# code...
			return false;
		}

		if (wp_is_post_autosave( $post = $post_id)) {
			# code...
			return false;
		}

		if (wp_is_post_revision( $post = $post_id)) {
			# code...
			return false;
		}
		return true;
	}

	/**
	 * [cmb_load_textdomain description]
	 * @return [null] [callback function from action hook plugins_loaded]
	 */
	public function cmb_load_textdomain()
	{
		# Hook for loading the plugin textdomain...
		load_plugin_textdomain( $domain = 'custom_meta_box', $deprecated = false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * [cmb_add_metabox description]
	 * @return [null] [callback function from action action hook admin_menu]
	 */
	public function cmb_add_metabox()
	{
		# function to add meta_box...
		add_meta_box( $id = 'cmb_post_location', $title = __('Post Location','custom_meta_box'), $callback = array($this,'cmb_display_post_location'), $screen = 'post', $context = 'advanced', 'default','post');
	}

	/**
	 * [cmb_display_post_location description]
	 * @return [null] [callback function from wordpress add_meta_box to render the HTML]
	 */
	public function cmb_display_post_location($post)
	{
		# code...
		$label = __('Location','custom_meta_box');
		$label1 = __('Country','custom_meta_box');
		$location = get_post_meta( $post_id = $post->ID, $key = 'cmb_location', $single = true);

		$country = get_post_meta( $post_id = $post->ID, $key = 'cmb_country', $singe = true );

		# Add a hidden input with secret token to verify user input with correct permission
		wp_nonce_field( $action = 'cmb_location', $name = 'cmb_location_field', $referer = true, $echo = true );

		$metabox_html = <<<EOD
		<div class="location-meta-box">
		<label for="cmb_location">{$label}</label>
		<input type="text" name="cmb_location" id="cmb_location" value={$location}>

		<label for="cmb_country">{$label1}</label>
		<input type="text" name="cmb_country" id="cmb_country" value={$country}>
		</div>
EOD;
		echo $metabox_html;
	}

	/**
	 * [cmb_save_location callback function from action hook save_post to save location meta information]
	 * @param  [type] $post_id [description]
	 * @return [$post_id]          [description]
	 */
	public function cmb_save_location($post_id)
	{
		# code...
		if ($this->isSecure('cmb_location_field','cmb_location',$post_id)) {
			# code...
			return $post_id;
		}
		$location = sanitize_text_field(isset($_POST['cmb_location'])?$_POST['cmb_location']:'');
		$country = sanitize_text_field(isset($_POST['cmb_country'])?$_POST['cmb_country']:'');
		update_post_meta( $post_id = $post_id, $meta_key = 'cmb_location', $meta_value = $location, $prev_value );
		update_post_meta( $post_id = $post_id, $meta_key = 'cmb_country', $meta_value = $country);

	}
}new ourMetaBox;