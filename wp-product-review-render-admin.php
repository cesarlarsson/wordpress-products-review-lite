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
    register_setting( 'previewoption', 'wporg_options' );
    register_setting( 'previewoption', 'color_options' );


    // register a new section in the "wporg" page
    add_settings_section(
        'wporg_section_developers',
        __( 'The Matrix has you.', 'previewoption' ),
        'wporg_section_developers_cb',
        'previewoption'
    );
    
    // register a new field in the "wporg_section_developers" section, inside the "wporg" page
    add_settings_field(
        'wporg_field_pill', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'Pill', 'previewoption' ),
        'wporg_field_pill_cb',
        'previewoption',
        'wporg_section_developers',
        [
        'label_for' => 'wporg_field_pill',
        'class' => 'wporg_row',
        'wporg_custom_data' => 'custom',
        ]
    );

    add_settings_field(
        'previewoption_field_color_options', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'Star Color', 'previewoption' ),
        'previewoption_field_color_cb',
        'previewoption',
        'wporg_section_developers',
        [
        'label_for' => 'previewoption_field_color',
        'class' => 'wporg_row',
        'wporg_custom_data' => 'custom',
        ]
    );
    

        

   }

   /**
    * register the product review settings
    */
   add_action( 'admin_init', 'product_review_settings_init' );
    
   /**
    * custom option and settings:
    * callback functions
    */
    
   // developers section cb
    
   // section callbacks can accept an $args parameter, which is an array.
   // $args have the following keys defined: title, id, callback.
   // the values are defined at the add_settings_section() function.
   function wporg_section_developers_cb( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'wporg' ); ?></p>
    <?php
   }

    function previewoption_field_color_cb($args){
        
        $options = get_option( 'color_options' );
         // output the field
         ?>        
         
       <input type="text" name="color_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?= $options['previewoption_field_color'] ?>" class="cpa-color-picker" >
   
         
         <?php

    }
   // pill field cb
    
   // field callbacks can accept an $args parameter, which is an array.
   // $args is defined at the add_settings_field() function.
   // wordpress has magic interaction with the following keys: label_for, class.
   // the "label_for" key value is used for the "for" attribute of the <label>.
   // the "class" key value is used for the "class" attribute of the <tr> containing the field.
   // you can add custom key value pairs to be used inside your callbacks.
   function wporg_field_pill_cb( $args ) {
    // get the value of the setting we've registered with register_setting()
    $options = get_option( 'wporg_options' );
   
    // output the field
    ?>
    <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
    data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>"
    name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
    >
    <option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
    <?php esc_html_e( 'red pill', 'wporg' ); ?>
    </option>
    <option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
    <?php esc_html_e( 'blue pill', 'wporg' ); ?>
    </option>
    <option value="green" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'green', false ) ) : ( '' ); ?>>
    <?php esc_html_e( 'green pill', 'wporg' ); ?>
    </option>
    </select>
    <p class="description">
    <?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg' ); ?>
    </p>
    <p class="description">
    <?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg' ); ?>
    </p>
    
    <?php
   }
    
   /**
    * top level menu
    */
   function wporg_options_page() {
    // add top level menu page
/*      add_menu_page(
    'WPOrg',
    'WPOrg Options',
    'manage_options',
    'wporg',
    'wporg_options_page_html'
    ); 
 */
      add_submenu_page( 
        'edit.php?post_type=product_review',
         'Settings',
         'Settings', 
         'manage_options', 
         'product_review_settings', 
         'wporg_options_page_html'); 
         
/*          add_options_page( 
            'Reorder reviews', 
            'Reorder reviews', 
            'manage_options', 
            'reoder_reviews', 
           'wporg_options_page_html'); */
   } 

   /**
    * register our wporg_options_page to the admin_menu action hook
    */
   add_action( 'admin_menu', 'wporg_options_page' );
    
   /**
    * top level menu:
    * callback functions
    */
   function wporg_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
    return;
    }
    
    // add error/update messages
    
    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
    // add settings saved message with the class of "updated"
    add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
    }
    
    // show error/update messages
    settings_errors( 'wporg_messages' );
    ?>
    <div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
    <?php
    // output security fields for the registered setting "wporg"
    settings_fields( 'previewoption' );
    // output setting sections and their fields
    // (sections are registered for "wporg", each field is registered to a specific section)
    do_settings_sections( 'previewoption' );
    // output save settings button
    submit_button( 'Save Settings' );
    ?>
    </form>
    </div>
    <?php
   }