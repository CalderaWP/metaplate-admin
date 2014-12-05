<?php
/**
 * Main admin class.
 *
 * @package calderawp\metaplate\admin
 * @author    David Cramer <david@digilab.co.za>
 * @license   GPL-2.0+
 * @link
 * @copyright 2014 David Cramer
 */

namespace calderawp\metaplate\admin;


use calderawp\metaplate\core\init;

/**
 * Class settings
 * @package calderawp\metaplate\admin
 */
class settings extends init {


	/**
	 * Start up
	 */
	public function __construct(){

		// save config
		add_action( 'wp_ajax_mtpt_save_config', array( $this, 'save_config') );
		// creat new
		add_action( 'wp_ajax_mtpt_create_metaplate', array( $this, 'create_new_metaplate') );
		// delete
		add_action( 'wp_ajax_mtpt_delete_metaplate', array( $this, 'delete_metaplate') );

	}


	/**
	 * saves a config
	 */
	private function update_settings($config){

		if( isset( $config['metaplate-setup'] ) && !wp_verify_nonce( $config['metaplate-setup'], 'metaplate' ) ){
			wp_send_json_error( $config );
		}

		$metaplates = get_option( '_metaplates_registry' );
		if( isset( $config['id'] ) && !empty( $metaplates[ $config['id'] ] ) ){
			$updated_registery = array(
				'id'	=>	$config['id'],
				'name'	=>	$config['name'],
				'slug'	=>	$config['slug']
			);
			// add search form to registery
			if( !empty( $config['search_form'] ) ){
				$updated_registery['search_form'] = $config['search_form'];
			}

			$metaplates[$config['id']] = $updated_registery;
			update_option( '_metaplates_registry', $metaplates );
		}
		update_option( $config['id'], $config );

	}

	/**
	 * saves a config
	 */
	public function save_config(){

		if( empty( $_POST['metaplate-setup'] ) || !wp_verify_nonce( $_POST['metaplate-setup'], 'metaplate' ) ){
			if( empty( $_POST['config'] ) ){
				return;
			}
		}
		// define default
		$config = array();
		if( !empty( $_POST['metaplate-setup'] ) && empty( $_POST['config'] ) ){
			$config = stripslashes_deep( $_POST );

			self::update_settings( $config );

			wp_redirect( '?page=metaplate&updated=true' );
			exit;
		}

		if( !empty( $_POST['config'] ) ){
			$config = json_decode( stripslashes_deep( $_POST['config'] ), true );
			self::update_settings( $config );
			wp_send_json_success( $config );

		}

		// nope
		wp_send_json_error( $config );

	}

	/**
	 * Deletes a block
	 */
	public function delete_metaplate(){

		$search_blocks = get_option( '_metaplates_registry' );
		if( isset( $search_blocks[ $_POST['block'] ] ) ){
			delete_option( $search_blocks[$_POST['block']]['id'] );

			unset( $search_blocks[ $_POST['block'] ] );
			update_option( '_metaplates_registry', $search_blocks );

			wp_send_json_success( $_POST );
		}

		wp_send_json_error( $_POST );

	}
	/**
	 * create new metaplate
	 */
	public function create_new_metaplate(){

		$metaplates = get_option('_metaplates_registry');
		if( empty( $metaplates ) ){
			$metaplates = array();
		}

		$metaplate_id = uniqid('MTPT').rand(100,999);
		if( !isset( $metaplates[ $metaplate_id ] ) ){
			$new_metaplate = array(
				'id'		=>	$metaplate_id,
				'name'		=>	$_POST['name'],
				'slug'		=>	$_POST['slug'],
				'_current_tab' => '#metaplate-panel-general'
			);
			update_option( $metaplate_id, $new_metaplate );
			$metaplates[ $metaplate_id ] = $new_metaplate;
			update_option( '_metaplates_registry', $metaplates );

			// end
			wp_send_json_success( $new_metaplate );
		}
	}


} 
