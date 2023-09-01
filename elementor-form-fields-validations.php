<?php
/**
* Plugin Name: Elixir Elementor-Form  Validator
* Description: Elementor Form Fields Validator.
* Version: 0.2
* Author: Elixir Technologies Pvt Ltd.
* Author URI: https://elixirinfo.com
**/

if( ! class_exists( 'Elixir_Updater' ) ){
	include_once( plugin_dir_path( __FILE__ ) . 'updater.php' );
}

$updater = new Smashing_Updater( __FILE__ );
$updater->set_username( 'cnishtha' );
$updater->set_repository( 'elixir-elementor-form-validation' );

$updater->authorize( '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 ' ); // Your auth code goes here for private repos
$updater->initialize();
 
// ----------------


function enqueue_custom_stylesheet() {
    wp_enqueue_style( 'vStyle', plugin_dir_url( __FILE__ ) . 'vStyle.css', array(), '1.0.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_stylesheet' );

// wp_enqueue_style( 'vStyle', '/elementor-form-validations/vStyle.css', false);
register_activation_hook( __FILE__, 'activate_plug_in' );
function activate_plug_in(){
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  (!is_plugin_active( 'elementor/elementor.php' ) ||  !is_plugin_active( 'elementor-pro/elementor-pro.php' )) ) {
        die('Sorry, but this plugin requires the Elementor and the Elementor-pro plugin to be installed and active.');
    }
}

// Validation Code  
add_action( 'elementor_pro/forms/validation/textarea', function( $field, $record, $ajax_handler ) {

    if ( preg_match( '/((www\.|(http|https|ftp|news|file)+\:\/\/)[_.a-z0-9-]+\.[a-z0-9\/_:@=.+?,##%&~-]*[^.|\'|\# |!|\(|?|,| |>|<|;|\)])/', $field['value'] ) || preg_match( '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', $field['value'] ) || preg_match( '/[a-z0-9]+\.(com|in|en|net|org|co|edu|us|me|es|cc)/i', $field['value'] ) ) {
        $ajax_handler->add_error( $field['id'], 'Please enter valid text.' );
    }
    
    if ( preg_match( '/[^a-zA-Z0-9.,"\-()[\]{}?!&=’:\'\s]/i', $field['value'] ) ) {
        $ajax_handler->add_error( $field['id'], 'Please enter valid text.' );
    }
}, 10, 3 );

add_action( 'elementor_pro/forms/validation/tel', function( $field, $record, $ajax_handler ) {

    if ( strlen((string)$field['value']) !== 10 ) {
       $ajax_handler->add_error( $field['id'], 'Please enter a valid 10 digit mobile number.' );
   }
  
   if ( preg_match( '/[^0-9]/i', $field['value'] ) ) {
       $ajax_handler->add_error( $field['id'], 'Please enter a valid 10 digit mobile number.' );
   }

}, 10, 3 );


add_action( 'elementor_pro/forms/validation/text', function( $field, $record, $ajax_handler ) {

    if ( preg_match( '/((www\.|(http|https|ftp|news|file)+\:\/\/)[_.a-z0-9-]+\.[a-z0-9\/_:@=.+?,##%&~-]*[^.|\'|\# |!|\(|?|,| |>|<|;|\)])/', $field['value'] ) || preg_match( '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', $field['value'] ) || preg_match( '/[a-z0-9]+\.(com|in|en|net|org|co|edu|us|me|es|cc)/i', $field['value'] ) ) {
        $ajax_handler->add_error( $field['id'], 'Please enter valid text.' );
    }
 
    if ( preg_match( '/[^a-zA-Z\s]/i', $field['value'] ) ) {
        $ajax_handler->add_error( $field['id'], 'Please enter valid text.' );
    }
}, 10, 3 );
