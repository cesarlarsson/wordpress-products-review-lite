<?php
/**
* Plugin Name: Products review lite
* Plugin URI: http://smartrabbit.co
* Author: @cesarlarsson
* Author URI: smartrabbit.co
* Version: 0.1
* License: GPLv2
**/

//Exit if accessed directly
if (! defined("ABSPATH")){
    exit;
}


$dir = plugin_dir_path(__FILE__);


require_once($dir ."sr-product-reviews-custom-post-type.php");
require_once($dir ."wp-product-review-fields.php");
require_once($dir ."wp-product-review-render-admin.php");

//Scripts
function srwp_admin_enqueue_scripts(){
    global $pagenow, $typenow;

    if ($typenow  == 'product_review'){
		//Plugin Main CSS File.
		wp_enqueue_style( 'srwp-admin-css', plugins_url( 'css/admin-review.css', __FILE__ ) );
    }


    //var_dump($typenow);
    if ( ($pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow == 'product_review' ) {

		//Plugin Main js File.
	
        wp_enqueue_script( 'srwp-job-js', plugins_url( 'scripts/jquery.rater-1.1.js', __FILE__ ), array( 'jquery' ), '20150204', true );
        wp_enqueue_script( 'srwp-job-js', plugins_url( 'scripts/admin-review.js', __FILE__ ), array( 'jquery', 'jquery-ui-datepicker' ), '20150204', true );
        // 
        /* 		//Quicktags js file.
		wp_enqueue_script( 'srwp-custom-quicktags', plugins_url( 'js/srwp-quicktags.js', __FILE__ ), array( 'quicktags' ), '20150206', true ); */
		//Datepicker Styles
		wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
    }
    
/*     if ( $pagenow == 'edit.php' && $typenow == 'product_review') {
        wp_enqueue_script( 'srwp-reorder-js', plugins_url( 'js/reorder.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ), '20170204', true );
        wp_localize_script( 'srwp-reorder-js', 'WP_JOB_LISTING', array(
            'security' => wp_create_nonce( 'wp-review-order' ),
            'siteUrl' => get_bloginfo('url' ),
            'success'=> 'Product Review sort order has been saved.',
            'failure'=>'There was an error saving the sort order, or you do not have proper permissions.'

        ) );
    } */
}

add_action('admin_enqueue_scripts','srwp_admin_enqueue_scripts');

