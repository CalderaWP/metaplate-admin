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


class post_types {

	public  function get_post_types() {
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		if ( function_exists( 'pods_api' ) ) {
			$pods = pods_api()->load_pods(
						array(
							'type' => array( 'pod' )
						)
			);

		}

		if ( ! empty( $pods ) ) {
			foreach( $pods as $pod ) {
				$formatted = $this->convert_pod_info( $pod );
				$post_types[ pods_v( 'name', $pod ) ] = $formatted;

			}
		}

		return $post_types;

	}

	private function convert_pod_info( $pod ) {
		$formatted = new \stdClass();
		$labels[ 'name' ] = pods_v( 'label', $pod );
		$formatted->label = pods_v( 'label_singular', $pod[ 'options'] );
		$formatted->name  = pods_v( 'name', 'pods' );

		return $formatted;

	}

}
