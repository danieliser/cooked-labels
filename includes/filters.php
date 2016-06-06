<?php
/**
 * Helper Functions
 *
 * @package     Cooked\CustomLabels\Filters
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


add_filter( 'cooked_recipe_times_labels', 'cooked_custom_labels_recipe_time_labels' );
function cooked_custom_labels_recipe_time_labels( $labels ) {
	return $labels;
}