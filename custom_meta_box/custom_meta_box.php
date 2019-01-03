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


define('ASSETS_DIR', plugin_dir_url(__FILE__)."assets");
define('ASSETS_PUBLIC_DIR', plugin_dir_url(__FILE__)."assets/public");
define('ASSETS_ADMIN_DIR', plugin_dir_url(__FILE__)."assets/admin");

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

		# save meta during the post save
		add_action( $tag = 'save_post', $function_to_add = array($this, 'cmb_save_book_info'));

		# add scripts to admin panel
		add_action( $tag = 'admin_enqueue_scripts', $function_to_add = array($this, 'cmb_admin_assets'));
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


	public function cmb_admin_assets($hook)
	{
		# code...
		wp_enqueue_style('cmb_admin_style', $src = ASSETS_ADMIN_DIR . '/css/style.css', $dep = null, $version = time(), $in_footer =false);
	}

	/**
	 * [cmb_add_metabox description]
	 * @return [null] [callback function from action action hook admin_menu]
	 */
	public function cmb_add_metabox()
	{
		# function to add meta_box...
		add_meta_box( $id = 'cmb_post_location', $title = __('Post Location','custom_meta_box'), $callback = array($this,'cmb_display_post_location'), $screen = 'post', $context = 'advanced', 'default','post');

		# function to add meta box...
		add_meta_box( $id = 'cmb_book_info', $title = __('Book Info','custom_meta_box'), $callback = array($this,'cmb_display_book_info'), $screen = 'book', $context = 'advanced', 'default', $args = 'post' );
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
		$label2 = __('Colors','custom_meta_box');
		$label3 = __('Select Colors','custom_meta_box');
		$location = get_post_meta( $post_id = $post->ID, $key = 'cmb_location', $single = true);

		$country = get_post_meta( $post_id = $post->ID, $key = 'cmb_country', $singe = true );

		$colors = array('red','green','blue','yellow','pink','black');

		$saved_colors = get_post_meta( $post_id = $post->ID, $key = 'cmb_clr', $single = true);
		$clr_selected = get_post_meta( $post_id = $post->ID, $key = 'cmb_clr_select', $single = true );
		# Add a hidden input with secret token to verify user input with correct permission
		wp_nonce_field( $action = 'cmb_location', $name = 'cmb_location_field', $referer = true, $echo = true );

		$metabox_html = <<<EOD
		<div class="location-meta-box">
		<label for="cmb_location">{$label}</label>
		<input type="text" name="cmb_location" id="cmb_location" value={$location}>

		<label for="cmb_country">{$label1}</label>
		<input type="text" name="cmb_country" id="cmb_country" value={$country}>
		</div>
		<label>{$label2}</label>
EOD;
		foreach ($colors as $key => $value) {
			# code...
			$checked = '';
			if ($saved_colors && in_array($value, $saved_colors)) {
				# code...
				$checked = 'checked';
			}
			$metabox_html .= <<<EOD
			<label for="cmb_clr_{$value}">{$value}</label>
			<input type="checkbox" name="cmb_clr[]" id="cmb_clr_{$value}" value="{$value}" {$checked}>
EOD;
		}

		$metabox_html .= '<br><label>'.$label3.'</label><select name="cmb_clr_select" id="cmb_clr_select">';
		foreach ($colors as $key => $value) {
			# code...
			$selected = '';
			if ($value == $clr_selected) {
				# code...
				$selected = 'selected';
			}
			$metabox_html .= <<<EOD
			<option value="{$value}" {$selected}>$value</option>
EOD;
		}
		$metabox_html .= '</select>';
		echo $metabox_html;
	}

	/**
	 * [cmb_display_book_info custom meta box for custom post type Book]
	 * @param  [object] $post [description]
	 * @return [type]       [description]
	 */
	public function cmb_display_book_info($post)
	{
		# code...
		wp_nonce_field( $action = 'cmb_book_info', $name = 'cmb_book_info_field', $referer = true, $echo = true );

		$metabox_html = <<<EOD
		<div class="fields">
		<div class="label_c" for="book-author">Book Author</div>
		<div class="input_c"><input id = "book-author" type="text"></div>
		</div>

		<div class="fields">
		<div class="label_c" for="book-isbn">Book ISBN</div>
		<div class="input_c"><input id = "book-isbn"type="text"></div>
		</div>
		<div class="clearfix"></div>
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
		$colors = isset($_POST['cmb_clr'])?$_POST['cmb_clr']:[];
		$clr_selected = isset($_POST['cmb_clr_select'])?$_POST['cmb_clr_select']:'';
		update_post_meta( $post_id = $post_id, $meta_key = 'cmb_location', $meta_value = $location);
		update_post_meta( $post_id = $post_id, $meta_key = 'cmb_country', $meta_value = $country);
		update_post_meta( $post_id = $post_id, $meta_key = 'cmb_clr', $meta_value = $colors);
		update_post_meta( $post_id = $post_id, $meta_key = 'cmb_clr_select', $meta_value = $clr_selected);

	}

	public function cmb_save_book_info($post_id)
	{
		# code...
		if ($this->isSecure()) {
			# code...
		}
	}
}new ourMetaBox;