<?php

/**
 * Plugin Name: Our Metabox
 * Plugin URI:  https://example.com/plugins/the-basics/
 * Description: Basic WordPress custom meta example
 * Version:     1.0
 * Author:      WordPress.org
 * Author URI:  https://author.example.com/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: our-metabox
 * Domain Path: /languages
 */

class OurMetabox{
    public function __construct()
    {
        # code...
        add_action('plugins_loaded',array($this,'omb_load_textdomain'));

        add_action(
            $tag = 'admin_menu', 
            $function_to_add = array($this, 'omb_add_metabox') 
        );

        add_action(
            $tag = 'save_post', 
            $function_to_add = array($this,'omb_save_location')
        );
    }

    public function omb_save_location($post_id)
    {
        # code...
        $location = $_POST['omb_location']??'';
        if ('' == $location) {
            # code...
            return $post_id;
        }
        add_post_meta(
            $post_id = $post_id, 
            $meta_key = 'omb_location', 
            $meta_value = $location
        );

    }

    public function omb_add_metabox()
    {
        # code...
        add_meta_box(
            $id = 'omb_post_location', 
            $title = __('Location Info','our-metabox'), 
            $callback = array($this,'omb_display_post_location'), 
            $screen = 'post', 
            $context = 'advanced'
        );
    }

    public function omb_display_post_location()
    {
        # code...
        $label = __('omb_location','out-metabox');
        $metabox_html = <<<EOD

        <p>
            <label for="omb_location">{$label}</label>
            <input type="text" name="omb_location" id="omb_location">
        </p>
EOD;
        $metabox_html;
    }
    public function omb_load_textdomain()
    {
        # code...
        load_plugin_textdomain(
            $domain = 'out-metabox', 
            $deprecated = false, 
            $plugin_rel_path = dirname(__FILE__)."/languages"
        );
    }
}new OurMetabox;