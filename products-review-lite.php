<?php
/**
* Plugin Name: Products review lite
* Plugin URI: http://smartrabbit.co
* Author: @cesarlarsson
* Author URI: smartrabbit.co
* Version: 0.1
* License: GPLv2
* Text Domain: srpr-plugin-i18n
* Domain Path: /languages/
**/

//Exit if accessed directly
if (! defined("ABSPATH")){
    exit;
}


$dir = plugin_dir_path(__FILE__);


require_once($dir ."srpr-product-reviews-custom-post-type.php");
require_once($dir ."srpr-product-review-fields.php");
require_once($dir ."srpr-product-review-render-admin.php");
require_once($dir ."srpr-image-upload-metabox.php");
require_once($dir ."srpr-widget-definition.php");
require_once($dir ."functions.php");
require_once($dir ."srpr-frontend-display.php");




//Scripts
function srwp_admin_enqueue_scripts(){
    global $pagenow, $typenow;

    if ($typenow  == 'product_review'){
		//Plugin Main CSS File.
		wp_enqueue_style( 'srwp-admin-css', plugins_url( 'css/admin-review.css', __FILE__ ) );
    }


    //var_dump($typenow);
    if ( ($pagenow == 'post.php' || $pagenow == 'post-new.php' ) && $typenow == 'product_review' ) {
        wp_enqueue_media();

        wp_enqueue_script( 'srwp-rateyo', plugins_url( 'scripts/jquery.rateyo.min.js', __FILE__ ), array( 'jquery' ), '20150204', true );     
        wp_enqueue_script( 'srwp-admin-review', plugins_url( 'scripts/admin-review.js', __FILE__ ), array('jquery','wp-color-picker'), '20180110', true );        
		wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
        wp_enqueue_style( 'jquery-style-rateyo', plugins_url( 'css/jquery.rateyo.min.css', __FILE__ ) );
        wp_enqueue_script( 'srwp_img_upload', plugins_url( 'scripts/image-upload.js', __FILE__ ), array('jquery', 'media-upload'), '0.0.2', true );
        
        wp_localize_script( 'srwp_img_upload', 'customUploads', array( 'imageData' => get_post_meta( get_the_ID(), 'custom_image_data', true ) ) );
    } else if ( $pagenow == 'edit.php' && $typenow == 'product_review'){
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
        wp_enqueue_script( 'srwp-admin-review', plugins_url( 'scripts/setting.js', __FILE__ ), array('jquery','wp-color-picker'), '20180110', true );
    }
}


//Scripts
function srwp_wp_enqueue_scripts(){

    wp_enqueue_style('custom-style', plugins_url( '/css/srwp-widget-styles.css', __FILE__ ));

}

function load_plugin_textdomain2() {
    load_plugin_textdomain( 'srpr_plugin', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
  }

add_action('admin_enqueue_scripts','srwp_admin_enqueue_scripts');
add_action('wp_enqueue_scripts','srwp_wp_enqueue_scripts');
add_action( 'plugins_loaded', 'load_plugin_textdomain2' );