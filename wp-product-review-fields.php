<?php

function dwwp_add_custom_meta() {
    add_meta_box(
      'srwp_meta',
      'Product Calification',
      'srwp_meta_callback',
      'product_review',
      'normal',
      'core'
    );
}
add_action( 'add_meta_boxes', 'dwwp_add_custom_meta' );

function srwp_meta_callback($post){

    wp_nonce_field( basename( __FILE__ ), 'dwwp_jobs_nonce' );
	$dwwp_stored_meta = get_post_meta( $post->ID );
	//var_dump($dwwp_stored_meta);
	//die();
    ?>
    <div>option <?php $t= get_option( 'wporg_options' ); print_r($t); ?>
    <div class="meta-row">
			<div class="meta-th">
				<label for="product-name" class="dwwp-row-title"><?php _e( 'Product Name', 'wp-job-listing' ); ?></label>
			</div>
			<div class="meta-td">
				<input type="text" class="dwwp-row-content" name="product-name" id="product-name"
				value="<?php if ( ! empty ( $dwwp_stored_meta['product-name'] ) ) {
					echo esc_attr( $dwwp_stored_meta['product-name'][0] );
				} ?>"/>
			</div>
	</div>
        <div class="meta-row">
            <div class="meta-th">
                <label for="release-date" class="dwwp-row-release-date"><?php _e( 'Release date', 'wp-job-listing' ); ?></label>
            </div>
            <div class="meta-td">
                <input type="text" name="release-date" id="release-date" class="datepicker" value="<?php if ( ! empty ( $dwwp_stored_meta['release-date'] ) ) {
					echo esc_attr( $dwwp_stored_meta['release-date'][0] );
				} ?>" />
            </div>
        </div>

        <div class="meta-row">
			<div class="meta-th">
				<label for="price" class="dwwp-row-title"><?php _e( 'Price', 'wp-job-listing' ); ?></label>
			</div>
			<div class="meta-td">
				<input type="text" name="price" id="price" value="<?php if ( ! empty ( $dwwp_stored_meta['price'] ) ) {
					echo esc_attr( $dwwp_stored_meta['price'][0] );
				} ?>"/>
			</div>
		</div>
		<div class="meta-row">
			<div class="meta-th">
				<span>Rating</span>
			</div>
			<div class="meta-th">
				<div id="rateYo" data-color="<?=$t['wporg_field_pill']?>"></div>
			<input type="text" name="rating_value" id="rating_value" value="<?php if ( ! empty ( $dwwp_stored_meta['rating_value'] ) ) {
						echo esc_attr( $dwwp_stored_meta['rating_value'][0] );
					} ?>"/>
			</div>
		</div>
		
		<div class="meta-editor"></div>
		<?php
		$content = get_post_meta( $post->ID, 'product_information', true );
		$editor = 'product_information';
		$settings = array(
			'textarea_rows' => 8,
			'media_buttons' => false,
		);
		wp_editor( $content, $editor, $settings);
        ?>
    </div>

    <?php

}

function srwp_meta_save( $post_id ) {
	// Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'dwwp_jobs_nonce' ] ) && wp_verify_nonce( $_POST[ 'dwwp_jobs_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    if ( isset( $_POST[ 'product-name' ] ) ) {
    	update_post_meta( $post_id, 'product-name', sanitize_text_field( $_POST[ 'product-name' ] ) );
	}
	if ( isset( $_POST[ 'release-date' ] ) ) {
    	update_post_meta( $post_id, 'release-date', sanitize_text_field( $_POST[ 'release-date' ] ) );
	}
	if ( isset( $_POST[ 'price' ] ) ) {
    	update_post_meta( $post_id, 'price', sanitize_text_field( $_POST[ 'price' ] ) );
	}
	if ( isset( $_POST[ 'rating_value' ] ) ) {
		update_post_meta( $post_id, 'rating_value', wp_kses_post( $_POST[ 'rating_value' ] ) );
	}
	if ( isset( $_POST[ 'product_information' ] ) ) {
		update_post_meta( $post_id, 'product_information', wp_kses_post( $_POST[ 'product_information' ] ) );
	}
	
	if ( isset( $_POST[ 'preferred_requirements' ] ) ) {
		update_post_meta( $post_id, 'preferred_requirements', wp_kses_post( $_POST[ 'preferred_requirements' ] ) );
	}

	
}

add_action( 'save_post', 'srwp_meta_save' );