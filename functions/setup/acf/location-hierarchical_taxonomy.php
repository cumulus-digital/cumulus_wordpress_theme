<?php
/**
 * ACF custom location rule to match hierarchical post types.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

if ( \class_exists( 'ACF_Location' ) ) {
	class ACF_HierarchicalTaxLocation extends \ACF_Location {
		public function initialize() {
			$this->name        = 'hierarchical_taxonomy';
			$this->label       = 'Hierarchical Taxonomy';
			$this->category    = 'forms';
			$this->object_type = 'term';
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
			if ( isset( $screen['taxonomy'] ) ) {
				return \is_taxonomy_hierarchical( $screen['taxonomy'] );
			}

			return false;
		}
	}

	\add_action( 'acf/init', function () {
		if ( \function_exists( 'acf_register_location_type' ) ) {
			\acf_register_location_rule( ns( 'ACF_HierarchicalTaxLocation' ) );
		}
	} );
}
