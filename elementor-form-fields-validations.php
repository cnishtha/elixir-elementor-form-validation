<?php
/**
* Plugin Name: Elixir Elementor-Form  Validator
* Description: Elementor Form Fields Validator.
* Version: 0.1
* Author: Elixir Technologies Pvt Ltd.
* Author URI: https://elixirinfo.com
**/


 
// ----------------
// 1: Plugin Description When People Click On View Version Details
// Note: use the plugin slug, path, name 
 
add_filter( 'plugins_api', 'elixir_plugin_view_version_details', 9999, 3 );
 
function elixir_plugin_view_version_details( $res, $action, $args ) {
   if ( 'plugin_information' !== $action ) return $res;
   if ( $args->slug !== 'Elixir elementor form validation' ) return $res;
   $res = new stdClass();
   $res->name = 'Elixir elementor form validation';
   $res->slug = 'elixir-elementor-form-validation';
   $res->path = 'Elixir elementor form validation/elementor-form-fields-validations.php';
   $res->sections = array(
      'description' => 'The plugin description',
   );
   $changelog = elixir_elementor_form_validation_request();
   $res->version = $changelog->latest_version;
   $res->download_link = $changelog->download_url; 
   return $res;
}
 
// ----------------
// 2: Plugin Update
// Note: use the plugin {$hostname}, slug & path 
 
add_filter( 'update_plugins_https://elixirinfo.com/', function( $update, array $plugin_data, string $plugin_file, $locales ) {
    if ( $plugin_file !== 'Elixir elementor form validation/elementor-form-fields-validations.php' ) return $update;
    if ( ! empty( $update ) ) return $update;
    $changelog = elixir_elementor_form_validation_request();
    if ( ! version_compare( $plugin_data['Version'], $changelog->latest_version, '<' ) ) return $update;
    return [
        'slug' => 'elixir-elementor-form-validation',
        'version' => $changelog->latest_version,
        'url' => $plugin_data['PluginURI'],
        'package' => $changelog->download_url,
    ];   
}, 9999, 4 );
 
// ----------------
// 3: Retrieve Plugin Changelog
// Note: use the public JSON file address
 
function elixir_elementor_form_validation_request() {
    $access = wp_remote_get( 'https://elixirinfo.com/updates/plugin-updates.json', array( 'timeout' => 10,   'headers' => array( 'Accept' => 'application/json' )  ) );
    if ( ! is_wp_error( $access ) && 200 === wp_remote_retrieve_response_code( $access ) ) {
         $result = json_decode( wp_remote_retrieve_body( $access ) );
         return $result;      
    }
}


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
