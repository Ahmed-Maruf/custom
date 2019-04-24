<?php
/**
 * Plugin Name: Word Count
 * Plugin URI:  https://example.com/plugins/the-basics/
 * Description: Basic WordPress Plugin Header Comment
 * Version:     20160911
 * Author:      Ahmed Maruf
 * Author URI:  https://author.example.com/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wordCount
 * Domain Path: /languages/
 */

function wordcount_load_textdomain()
{
    load_plugin_textdomain($domain = "word-count", $deprecated = false, $plugin_rel_path = dirname(__FILE__) . "/languages");
}
add_action($tag = "plugins_loaded", $function_to_add = "wordcount_load_textdomain");

function wordCount_count_words($content)
{
    $strip_content = strip_tags($content);
    $words = str_word_count($strip_content);
    $label = __('Total Number of Words', 'wordCount');
    $label = apply_filters($tag = "wordcount_heading", $value = $label);
    $tag = apply_filters($tag = "wordcount_tag", $value = 'h2');
    $content .= sprintf('<%s>%s: %s</%s>', $tag, $label, $words, $tag);
    return $content;
}
add_filter($tag = "the_content", $function_to_add = "wordCount_count_words");
