<?php
function cwp_pac_before_content2( $content ) {
	global $post;
	$srpr_review_stored_meta = get_post_meta( $post->ID );
	$return_string = srpr_show_product_review();
	$postType = get_post_type( $post->ID);
	global $page;
	
 
	if ($postType == "product_review") {
		return $return_string;
	} else{  
		return $content;
	}

}

$currentTheme = wp_get_theme();
//if ( $currentTheme->get( 'Name' ) !== 'Bookrev' && $currentTheme->get( 'Name' ) !== 'Book Rev Lite' ) {

	add_filter( 'the_content', 'cwp_pac_before_content2' );
//}
