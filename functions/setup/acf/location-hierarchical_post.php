<?php
/**
 * ACF custom location rule to match hierarchical post types.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

if ( \class_exists( 'ACF_Location' ) ) {
	class ACF_HierarchicalPostLocation extends \ACF_Location {
		public function initialize() {
			$this->name        = 'hierarchical_post_type';
			$this->label       = 'Hierarchical';
			$this->category    = 'page';
			$this->object_type = 'post';
		}

		public static function get_operators( $rule ) {
			return array(
				'==' => 'is equal to',
				'!=' => 'is not equal to',
			);
		}

		public function get_values( $rule ) {
			return array(
				'true'  => 'True',
				'false' => 'False',
			);
		}

		public function match( $rule, $screen, $field ) {
			if ( isset( $screen['post_type'] ) ) {
				return \is_post_type_hierarchical( $screen['post_type'] );
			}

			return false;
		}
	}

	\add_action( 'acf/init', function () {
		if ( \function_exists( 'acf_register_location_type' ) ) {
			\acf_register_location_rule( ns( 'ACF_HierarchicalPostLocation' ) );
		}
	} );
}
