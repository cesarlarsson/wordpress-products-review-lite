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
require_once($dir ."wp-image-upload-metabox.php");



//Scripts
function srwp_admin_enqueue_scripts(){
    global $pagenow, $typenow;

    if ($typenow  == 'product_review'){
		//Plugin Main CSS File.
		wp_enqueue_style( 'srwp-admin-css', plugins_url( 'css/admin-review.css', __FILE__ ) );
    }


    //var_dump($typenow);
    if ( ($pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow == 'product_review' ) {
        //wp_enqueue_script( 'srwp-job-js', plugins_url( 'scripts/jquery.rater-1.1.js', __FILE__ ), array( 'jquery' ), '20150204', true );
        wp_enqueue_script( 'srwp-rateyo', plugins_url( 'scripts/jquery.rateyo.min.js', __FILE__ ), array( 'jquery' ), '20150204', true );
        //wp_enqueue_script( 'srwp-job-js', plugins_url( 'scripts/admin-review22.js', __FILE__ ), array( 'jquery', 'jquery-ui-datepicker' ), '2015032423', true );
        wp_enqueue_script( 'srwp-admin-review', plugins_url( 'scripts/admin-review.js', __FILE__ ), array('jquery'), '2015032423', true );
		wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
        wp_enqueue_style( 'jquery-style-rateyo', plugins_url( 'css/jquery.rateyo.min.css', __FILE__ ) );
        wp_enqueue_script( 'srwp_img_upload', plugins_url( 'scripts/image-upload.js', __FILE__ ), array('jquery', 'media-upload'), '0.0.2', true );
        
        wp_localize_script( 'srwp_img_upload', 'customUploads', array( 'imageData' => get_post_meta( get_the_ID(), 'custom_image_data', true ) ) );
    }
}

add_action('admin_enqueue_scripts','srwp_admin_enqueue_scripts');

