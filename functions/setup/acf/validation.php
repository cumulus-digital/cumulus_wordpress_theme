<?php
/**
 * ACF field validation.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

function acfValidation_CSSClasses( $valid, $value, $field, $name ) {
	if ( $valid !== true ) {
		return $valid;
	}

	if (
		\array_key_exists( 'class', $field ) && \mb_strpos( $field['class'], 'acfvalidate-css_classes' ) !== false
	) {
		if ( ! \preg_match( '/^(-?[_a-zA-Z]+[\-_a-zA-Z0-9]*\s?)+$/', $value ) ) {
			return 'Custom title CSS classes contains invalid characters.';
		}
	}

	return $valid;
}
\add_filter( 'acf/validate_value', ns( 'acfValidation_CSSClasses' ), 10, 4 );
