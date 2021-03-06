<?php
/**
 * Generate rest route for posts list
 *
 * Route location: /wp-content/plugins/decoupled-json-content/page/rest-routes/list.php?post_type=post_type&posts_per_page=posts_per_page
 *
 * @since   1.0.0
 * @package Decoupled_Json_Content
 */

namespace Decoupled_Json_Content\Data_List\Rest_Routes;

use Decoupled_Json_Content\Helpers as General_Helpers;
use Decoupled_Json_Content\Data_List as Data_List;

require_once( '../../wp-config-simple.php' );
require_once( '../class-data-list.php' );
require_once( '../../page/class-page.php' );
require_once( '../../helpers/class-general-helper.php' );

header( 'Access-Control-Allow-Origin: *' );

$data_list = new Data_List\Data_List();
$general_helper = new General_Helpers\General_Helper();

// Check post type.
if ( isset( $_GET['post-type'] ) && ! empty( $_GET['post-type'] ) ) { // WPCS: input var ok; CSRF ok.
  $post_type = sanitize_text_field( wp_unslash( $_GET['post-type'] ) ); // WPCS: input var ok; CSRF ok.
} else {
  wp_send_json( $general_helper->set_msg_array( 'error', 'Error, type is missing!' ) );
}

// Check Filter.
if ( isset( $_GET['filter'] ) && ! empty( $_GET['filter'] ) ) { // WPCS: input var ok; CSRF ok.
  $filter = sanitize_text_field( wp_unslash( $_GET['filter'] ) ); // WPCS: input var ok; CSRF ok.
} else {
  wp_send_json( $general_helper->set_msg_array( 'error', 'Error, filter is missing!' ) );
}

$cache = $data_list->get_data_list( $post_type, $filter );

if ( $cache === false ) {
  wp_send_json( $general_helper->set_msg_array( 'error', 'Error, there is a problem with your configuration or pages/posts are it is not cached correctly. Please check your configuration, rebuilding cache and try again!' ) );
}

wp_send_json( $cache );
