<?php
/**
 * Plugin Name: Elementor Test
 * Plugin URI:  https://example.com/plugins/ElementorTest/
 * Description: Basic Elementor Extension
 * Version:     1.1
 * Author:      Ahmed Maruf
 * Author URI:  https://author.example.com/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: elementortestplugin
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}



final class Elementor_Test_Extension {
	const VERSION = "1.1"; //Your plugin version
    const MINIMUM_ELEMENTOR_VERSION = "2.0.0"; //Minimum Elementor Version Required
    const MINIMUM_PHP_VERSION = "7.0"; //Minimum PHP version required to run your plugin

    private static $_instance = null;
    /*The plugin class should use a singleton design pattern to make sure it loads only once*/
    public static function instance() {
    	if ( is_null( self::$_instance ) ) {
    		self::$_instance = new self();
    	}return self::$_instance;

    }
     /*

      The constructor should initiate the plugin. The init process should check for basic requirements and then then run the plugin logic. Note that If one of the basic plugin requirements fails the plugin logic won’t run.
      */
      public function __construct() {
      	add_action( 'plugins_loaded', [ $this, 'init' ] );
      }
      /*Initialize all the basic requirements to run the plugin logic*/
      public function init() {
      	load_plugin_textdomain( 'elementortestplugin' );

        // Check if Elementor installed and activated
      	if ( ! did_action( 'elementor/loaded' ) ) {
      		add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
      		return;
      	}

        // Check for required Elementor version
      	if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
      		add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
      		return;
      	}

        // Check for required PHP version
      	if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
      		add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
      		return;
      	}

        // Add Plugin actions when rest requirements are passed
      	add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );  

      	//Add action for registering widgets categories
      	add_action("elementor/elements/categories_registered",[ $this, "register_new_category"]);   

      }


      function register_new_category( $elements_manager ) {

      	$elements_manager->add_category(
      		'test-category',
      		[
      			'title' => __( 'Test Category', 'elementortestplugin' ),
      			'icon' => 'fa fa-plug',
      		]
      	);

      }
      /*Callback function for the action hook admin notices*/
      public function admin_notice_missing_main_plugin() {

      	if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

      	$message = sprintf(
      		/* translators: 1: Plugin name 2: Elementor */
      		esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementortestplugin' ),
      		'<strong>' . esc_html__( 'Elementor Test Extension', 'elementortestplugin' ) . '</strong>',
      		'<strong>' . esc_html__( 'Elementor', 'elementortestplugin' ) . '</strong>'
      	);

      	printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

      }

      /*Callback function for action hook admin notices upon elementor version not matching*/
      public function admin_notice_minimum_elementor_version() {

      	if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

      	$message = sprintf(
      		/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
      		esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementortestplugin' ),
      		'<strong>' . esc_html__( 'Elementor Test Extension', 'elementortestplugin' ) . '</strong>',
      		'<strong>' . esc_html__( 'Elementor', 'elementortestplugin' ) . '</strong>',
      		self::MINIMUM_ELEMENTOR_VERSION
      	);

      	printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

      }
      /*Callback function for action hood admin notices upon php version not matched*/
      public function admin_notice_minimum_php_version() {

      	if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

      	$message = sprintf(
      		/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
      		esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementortestplugin' ),
      		'<strong>' . esc_html__( 'Elementor Test Extension', 'elementortestplugin' ) . '</strong>',
      		'<strong>' . esc_html__( 'PHP', 'elementortestplugin' ) . '</strong>',
      		self::MINIMUM_PHP_VERSION
      	);

      	printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

      }

    /*
    @Callback function for the action hook elementor/widgets/widgets_registered
    @Create the folder widgets and the file under you custom plugin /widgets/test-widget.php
    */
    public function init_widgets() {

        // Include Widget files
    	require_once( __DIR__ . '/widgets/test-widget.php' );

        // Register widget by creating the class in the file you have created naming as test-widget.php
    	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_Test_Widget() );

    }

    public function includes() {}

}
Elementor_Test_Extension::instance();