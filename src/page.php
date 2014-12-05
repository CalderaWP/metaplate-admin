<?php
/**
 * @TODO What this does.
 *
 * @package   @TODO
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 */

namespace calderawp\metaplate\admin;


use calderawp\metaplate\core\init;

class page extends init {

	/**
	 * Start up
	 */
	public function __construct(){

		// add admin page
		add_action( 'admin_menu', array( $this, 'add_settings_pages' ), 25 );
		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_stylescripts' )
		);

	}


	/**
	 * Add options page
	 */
	public function add_settings_pages(){
		// This page will be under "Settings"


		$this->plugin_screen_hook_suffix['metaplate'] =  add_submenu_page( 'themes.php', __( 'Metaplate', $this->plugin_slug ), __( 'Metaplate', $this->plugin_slug ), 'manage_options', 'metaplate', array( $this, 'create_admin_page' ) );
		add_action( 'admin_print_styles-' . $this->plugin_screen_hook_suffix['metaplate'], array( $this, 'enqueue_admin_stylescripts' ) );


	}

	/**
	 * Options page callback
	 */
	public function create_admin_page(){
		// Set class property
		$screen = get_current_screen();
		$base = array_search($screen->id, $this->plugin_screen_hook_suffix);

		$path = dirname( __FILE__ ) .'/includes/';

		// include main template
		if( empty( $_GET['edit'] ) ){
			include $path .'/admin.php';
		}else{
			include $path .'/edit.php';
		}


		// php based script include
		if( file_exists( $path .'assets/js/inline-scripts.php' ) ){
			echo "<script type=\"text/javascript\">\r\n";
			include $path .'assets/js/inline-scripts.php';
			echo "</script>\r\n";
		}

	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 *
	 * @return    null
	 */
	public function enqueue_admin_stylescripts() {

		$screen = get_current_screen();


		if( false !== strpos( $screen->base, 'metaplate' ) ){

			wp_enqueue_style( 'metaplate-core-style', MTPT_URL . '/assets/css/styles.css' );
			wp_enqueue_style( 'metaplate-baldrick-modals', MTPT_URL . '/assets/css/modals.css' );
			wp_enqueue_script( 'metaplate-wp-baldrick', MTPT_URL . '/assets/js/wp-baldrick-full.min.js', array( 'jquery' ) , false, true );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			if( !empty( $_GET['edit'] ) ){
				wp_enqueue_style( 'metaplate-codemirror-style', MTPT_URL . '/assets/css/codemirror.css' );
				wp_enqueue_script( 'metaplate-codemirror-script', MTPT_URL . '/assets/js/codemirror.js', array( 'jquery' ) , false );
			}

			wp_enqueue_script( 'metaplate-core-script', MTPT_URL . '/assets/js/scripts.min.js', array( 'metaplate-wp-baldrick' ) , false );


		}


	}


} 
