<?php

// ------------------------------------------
// FACETS : remove count from dropdown
// ------------------------------------------
add_filter( 'facetwp_facet_dropdown_show_counts', '__return_false' );


// ------------------------------------------
// FACET : date as year
// ------------------------------------------
add_filter( 'facetwp_index_row', function( $params, $class ) {
	if ( 'year' == $params['facet_name'] ) {
		$raw_value = $params['facet_value'];
		$params['facet_value'] = date( 'Y', strtotime( $raw_value ) );
		$params['facet_display_value'] = $params['facet_value'];
	}
	return $params;
}, 10, 2 );