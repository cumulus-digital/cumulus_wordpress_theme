<?php

// Don't allow changes to our metabox order

namespace CumulusTheme;

function acfRestoreOrder( $group, $order ) {
	if ( \is_array( $order ) ) {
		$mbox = $group;

		if ( ! \mb_strstr( $group, 'acf-' ) ) {
			$mbox = 'acf-' . $group;
		}

		$field_group = \acf_get_field_group( $group );

		if ( ! $field_group ) {
			return $order;
		}
		$position   = $field_group['position'];
		$menu_order = $field_group['menu_order'];

		\array_walk( $order, function ( &$pos ) use ( $mbox ) {
			$pos = \str_replace( $mbox, '', $pos );
			// $order = \str_replace( ',,', '', $order );
			$pos = \preg_replace( '/(^,|,,+|,$)/', '', $pos );
		} );

		if ( \array_key_exists( $position, $order ) ) {
			$ppos = \explode( ',', $order[$position] );
			\array_splice( $ppos, $menu_order, 0, $mbox );
			$order[$position] = \implode( ',', \array_filter( $ppos ) );
		}
	}

	return $order;
}

function acfResetMetaboxesForCPT( $cpt, $group ) {
	// Prevent loading existing positions
	\add_filter( "get_user_option_meta-box-order_{$cpt}", function ( $order ) use ( $group ) {
		return acfRestoreOrder( $group, $order );
	}, 10, 1 );

	// Force order on save
	\add_action( 'check_ajax_referer', function ( $action ) use ( $cpt, $group ) {
		global $post;

		if ( $action !== 'meta-box-order' || ! isset( $_POST['page'] ) || $_POST['page'] !== $cpt || ! isset( $_POST['order'] ) ) {
			return;
		}

		$_POST['order'] = acfRestoreOrder( $group, $_POST['order'] );
	}, 10, 1 );

	// Stop metaboxes from being moved in the UI
	\add_action( 'enqueue_block_editor_assets', function () use ( $cpt, $group ) {
		if ( ! \is_admin() || \get_post_type() !== $cpt ) {
			return;
		}

		\wp_register_style( PREFIX . '-lock_metaboxes', '' );
		\wp_enqueue_style( PREFIX . '-lock_metaboxes', '' );
		\wp_register_script( PREFIX . '-lock_metaboxes', '', array( ), false, true );
		\wp_enqueue_script( PREFIX . '-lock_metaboxes', '', array( ), false, true );

		\wp_add_inline_style(
			PREFIX . '-lock_metaboxes',
			"#acf-{$group} .handle-order-higher, #acf-{$group} .handle-order-lower { display: none !important; } #acf-{$group} .postbox-header h2 { padding: 0 16px }"
		);
		\wp_add_inline_script(
			PREFIX . '-lock_metaboxes',
			"jQuery(function() { jQuery('#acf-{$group}').find('.hndle').removeClass('hndle ui-sortable-handle'); })"
		);
	} );
}
