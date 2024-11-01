<?php
class typeahead{
public function __construct() {
				 add_action( 'wp_ajax_nopriv_typeahead',  array( $this, 'ajax' ));//ajax
				 add_action( 'wp_ajax_typeahead', array( $this, 'ajax' ));
				 add_filter('typeahead_post', array( $this, 'sanitize_post' ),10, 2);
}

public function ajax() {
	if ( !wp_verify_nonce($_REQUEST['nonce'], 'typeahead' ) ){
		wp_die( '无效请求' );
	}
	if(!function_exists('wp_send_json')){
		function wp_send_json( $response ) {
	        @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
	        echo json_encode( $response );
	        if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
	                wp_die();
	        else
	                die;
		}
	}
	$search = $_REQUEST['s'];
	$settings = (array) get_option( 'typeahead' );
  $number = (int) esc_attr( $settings['number'] );
	$args = array (
	'post_type' => 'any',
	's' => $search ,
	'cache_results' => true,
	'posts_per_page' => $number,
	);
	$args=array_merge($args,apply_filters( 'typeahead_query_args',array(),$search )); 
	$posts = new WP_Query( $args );
	foreach ($posts->posts as $key => $val){
    $post=get_object_vars($val);
		$post=apply_filters( 'typeahead_post', $post,$key );
		$json_posts[$key]=$post;
	} 
 	wp_send_json($json_posts);
}


public function sanitize_post($post,$id){
  $post['post_content']=mb_strimwidth(strip_tags($post['post_content']), 0, 150,'...');
	$typeahead_unset_key=array('post_author','post_date_gmt','post_status','comment_status','ping_status','post_password','to_ping','post_modified','post_modified_gmt','post_content_filtered','post_parent','guid','menu_order','post_mime_type','filter','pinged','ID','post_excerpt','post_name','post_type','comment_count','post_date','post_content');
	foreach(apply_filters('typeahead_unset_key',$typeahead_unset_key) as $key => $val ){
	unset($post[$val]);
	}
	return $post;
}

}
?>