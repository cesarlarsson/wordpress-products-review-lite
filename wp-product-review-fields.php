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
    <div>
    <div class="meta-row">
			<div class="meta-th">
				<label for="job-id" class="dwwp-row-title"><?php _e( 'Job Id', 'wp-job-listing' ); ?></label>
			</div>
			<div class="meta-td">
				<input type="text" class="dwwp-row-content" name="job_id" id="job-id"
				value="<?php if ( ! empty ( $dwwp_stored_meta['job_id'] ) ) {
					echo esc_attr( $dwwp_stored_meta['job_id'][0] );
				} ?>"/>
			</div>
		</div>
        <div class="meta-row">
            <div class="meta-th">
                <label for="job-id" class="dwwp-row-title">Score</label>
            </div>
            <div class="meta-td">
                <input type="text" name="job-id" id="score" class="datepicker" value="<?php if ( ! empty ( $dwwp_stored_meta['job_id'] ) ) {
					echo esc_attr( $dwwp_stored_meta['score'][0] );
				} ?>" />
            </div>
        </div>
        <div class="meta-row">
			<div class="meta-th">
				<label for="application_deadline" class="dwwp-row-title">Application Deadline</label>
			</div>
			<div class="meta-td">
				<input type="text" name="application_deadline" id="application_deadline" value="<?php if ( ! empty ( $dwwp_stored_meta['application_deadline'] ) ) {
					echo esc_attr( $dwwp_stored_meta['application_deadline'][0] );
				} ?>"/>
			</div>
		</div>
		<div class="meta">
			<div class="meta-th">
				<span>Principle Duties</span>
			</div>
		</div>

		<div id="testRater" class="stat">
		<label for="rating">Rating</label>
		<div class="statVal">
			<span class="ui-rater">
				<span class="ui-rater-starsOff" style="width:90px;"><span class="ui-rater-starsOn" style="width:63px"></span></span>
				<span class="ui-rater-rating">3.5</span>&#160;(<span class="ui-rater-rateCount">2</span>)
			</span>
        </div>
    </div>
		<div class="meta-editor"></div>
		<?php
		$content = get_post_meta( $post->ID, 'principle_duties', true );
		$editor = 'principle_duties';
		$settings = array(
			'textarea_rows' => 8,
			'media_buttons' => false,
		);
		wp_editor( $content, $editor, $settings);
        ?>
    </div>
	<div class="meta-row">
        	<div class="meta-th">
	          <label for="preferred-requirements" class="dwwp-row-title"><?php _e( 'Preferred Requirements', 'wp-job-listing' ) ?></label>
	        </div>
	        <div class="meta-td">
	          <textarea name="preferred_requirements" class="dwwp-textarea" id="preferred-requirements"><?php
			          if ( ! empty ( $dwwp_stored_meta['preferred_requirements'] ) ) {
			            echo esc_attr( $dwwp_stored_meta['preferred_requirements'][0] );
			          }
		          ?>
	          </textarea>
	        </div>
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

    if ( isset( $_POST[ 'job_id' ] ) ) {
    	update_post_meta( $post_id, 'job_id', sanitize_text_field( $_POST[ 'job_id' ] ) );
	}
	if ( isset( $_POST[ 'application_deadline' ] ) ) {
    	update_post_meta( $post_id, 'application_deadline', sanitize_text_field( $_POST[ 'application_deadline' ] ) );
	}
	if ( isset( $_POST[ 'principle_duties' ] ) ) {
    	update_post_meta( $post_id, 'principle_duties', sanitize_text_field( $_POST[ 'principle_duties' ] ) );
    }
	if ( isset( $_POST[ 'preferred_requirements' ] ) ) {
		update_post_meta( $post_id, 'preferred_requirements', wp_kses_post( $_POST[ 'preferred_requirements' ] ) );
	}
}

add_action( 'save_post', 'srwp_meta_save' );