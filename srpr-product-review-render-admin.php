<?php

/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */
 
/**
 * custom option and settings
 */
 function product_review_settings_init()  {
    // register a new setting for "wporg" page
    register_setting( 'previewoption', 'srpr_number_of_stars' );
    register_setting( 'previewoption', 'srpr_color_options' );
    register_setting( 'previewoption', 'srpr_second_color_options' );

    // register a new section for the setting of the product review plugin
    add_settings_section(
        'previewoption_section_developers',
        __( 'Custom the Product  Lite Plugin', 'previewoption' ),
        'previewoption_section_developers_cb',
        'previewoption'
    );
    
    // register a fields
    add_settings_field(
        'previewoption_field_number', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'Number of Stars', 'previewoption' ),
        'previewoption_field_number_cb',
        'previewoption',
        'previewoption_section_developers',
        [
        'label_for' => 'previewoption_field_number',
        'class' => 'wporg_row',
        'wporg_custom_data' => 'custom',
        ]
    );

    add_settings_field(
        'previewoption_field_color_options', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'Star Main Color', 'previewoption' ),
        'previewoption_field_color_cb',
        'previewoption',
        'previewoption_section_developers',
        [
        'label_for' => 'previewoption_field_color',
        'class' => 'wporg_row',
        'wporg_custom_data' => 'custom',
        ]
    );
    
    add_settings_field(
        'previewoption_field_second_color_options', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'Star Second Color', 'previewoption' ),
        'previewoption_field_second_color_cb',
        'previewoption',
        'previewoption_section_developers',
        [
        'label_for' => 'previewoption_field_second_color',
        'class' => 'wporg_row',
        'wporg_custom_data' => 'custom',
        ]
    );
   }

   /**
    * register the product review settings
    */
   add_action( 'admin_init', 'product_review_settings_init' );
    
   //callback for the section
   function previewoption_section_developers_cb( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Set the number of stars and the color of it.', 'previewoption' ); ?></p>
    <?php
   }

    function previewoption_field_color_cb($args){
        
        $options = get_option( 'srpr_color_options' );
         // output the field
         ?>        
         
       <input type="text" name="srpr_color_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?= $options['previewoption_field_color'] ?>" class="cpa-color-picker" >
   

        <?php

    }

    function previewoption_field_second_color_cb($args){
        
        $options = get_option( 'srpr_second_color_options' );
        
         // output the field
         ?>        
         
       <input type="text" name="srpr_second_color_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?= $options['previewoption_field_second_color'] ?>" class="cpa-color-picker" >
   

        <?php

    }
   // Number of start field cb
   function previewoption_field_number_cb( $args ) {
    // get the value of the setting we've registered with register_setting()
    $options = get_option( 'srpr_number_of_stars' );
    // output the field
    ?>
    <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
    data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>"
    name="srpr_number_of_stars[<?php echo esc_attr( $args['label_for'] ); ?>]"
    >
        <option value="5" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 5, false ) ) : ( '' ); ?>>
        <?php esc_html_e( '5 Stars', 'previewoption' ); ?>
        </option>

        <option value="10" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 10, false ) ) : ( '' ); ?>>
        <?php esc_html_e( '10 Stars', 'previewoption' ); ?>
        </option>

    </select>
    
    <?php
   }
    
   /**
    * top level menu
    */
   function previewoption_options_page() {
    // add top level menu page
      add_submenu_page( 
        'edit.php?post_type=product_review',
         'Settings',
         'Settings', 
         'manage_options', 
         'product_review_settings', 
         'previewoption_options_page_html'); 
        
   } 

   /**
    * register our previewoption_options_page to the admin_menu action hook
    */
   add_action( 'admin_menu', 'previewoption_options_page' );
    
   /**
    * top level menu:
    * callback functions
    */
   function previewoption_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
    return;
    }
    
    // add error/update messages
    
    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
    // add settings saved message with the class of "updated"
    add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'previewoption' ), 'updated' );
    }
    
    // show error/update messages
    settings_errors( 'wporg_messages' );
    ?>
        <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
        <?php
        settings_fields( 'previewoption' );
        // output setting sections and their fields
        do_settings_sections( 'previewoption' );
        // output save settings button
        submit_button( 'Save Settings' );
        ?>
        </form>
        </div>
    <?php
   }