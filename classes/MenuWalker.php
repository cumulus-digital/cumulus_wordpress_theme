<?php
namespace CumulusTheme;

class MenuWalker extends \Walker_Nav_Menu
{
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
	{
		$indent = ($depth) ? str_repeat( "\t", $depth ) : '';

		$class_names = '';

		$classes = empty($item->classes) ? array() : (array) $item->classes;

		$styles = array();

		/*
		if ($item->thumbnail_id) {
			$image_src = null;
			$classes[] = 'menu-image';
			$image_src = \wp_get_attachment_image_src(
				$item->thumbnail_id,
				"full",
				false
			);
			$styles[] = "background-image:url('${image_src[0]}')";
		}
		*/

		$class_names = join(
			' ',
			\apply_filters(
				'nav_menu_css_class',
				array_filter($classes),
				$item
			)
		);
		$class_names = ' class="'. \esc_attr($class_names) . '"';

		$output .= $indent . 
			'<li ' . 
				'itemprop="name" ' . 
				'id="menu-item-'. $item->ID . '" ' . 
				'class="' . \esc_attr($class_names) . '" ' .
				'style="' . \esc_attr(implode(' ', $styles)) . '"' .
			'>';

		$attributes  = ! empty($item->attr_title) ?
			' title="' . \esc_attr($item->attr_title) . '"' : '';
		$attributes .= ! empty($item->target)     ?
			' target="' . \esc_attr($item->target) . '"' : '';
		$attributes .= ! empty($item->xfn)        ?
			' rel="' . \esc_attr($item->xfn) . '"' : '';
		$attributes .= ! empty($item->url)        ?
			' href="' . \esc_attr($item->url) . '"' : '';
		$attributes .= ' itemprop="url" ';
		$attributes .= ! empty($image)            ?
			' style="' . \esc_attr($image) . '"' : 'style=""';

		if (is_array($args)) {
			$args = (object) $args;
		}
		$item_output = $args->before;
		$item_output .= $item->url !== '#' ? '<a'. $attributes .'>' : '<span>';
		$item_output .= $args->link_before . \apply_filters( 'the_title', $item->title, $item->ID );
		$item_output .= $args->link_after;
		$item_output .= $item->url !== '#' ? '</a>' : '</span>';
		$item_output .= $args->after;

		$output .= \apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}