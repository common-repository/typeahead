<?php
class typeahead{
public function __construct() {
				 add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ));
				 add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
				 add_action( 'wp_footer', array( $this, 'echo_config' ),999 );
}

//加载脚本
public function enqueue_scripts() {
  $settings = (array) get_option( 'typeahead' );
  $jquery = (bool) esc_attr( $settings['jquery'] );
  $number = (int) esc_attr( $settings['number'] );

	wp_register_script( 'typeahead', plugins_url('/libs/typeahead.min.js' , TYPEAHEAD_FILE ), $jquery?array('jquery-core'):false, '0.10.4', true );
	wp_enqueue_script( 'typeahead' );

	//wp_register_script( 'typeahead_config', plugins_url('config.js' , TYPEAHEAD_FILE), array('typeahead'), TYPEAHEAD_SCRIPTS_VERSION, true );
	//wp_enqueue_script( 'typeahead_config' );

	wp_localize_script( 'typeahead', 'typeahead_settings', array(
		'ajaxurl'=> admin_url( 'admin-ajax.php' ),
		'number'=>$number,
		'nonce' => wp_create_nonce( 'typeahead' ),
		)
	);

}


//加载样式
public function enqueue_styles() {
	wp_register_style( 'typeahead_styles',plugins_url('styles.css' ,TYPEAHEAD_FILE), false, TYPEAHEAD_SCRIPTS_VERSION );
	wp_enqueue_style( 'typeahead_styles' );

}

public function echo_config(){
	echo '<script type="text/javascript">(function(e){e(document).ready(function(){var t=new Bloodhound({datumTokenizer:Bloodhound.tokenizers.obj.whitespace("post_title"),queryTokenizer:Bloodhound.tokenizers.whitespace,limit:typeahead_settings.number,remote:typeahead_settings.ajaxurl+"?action=typeahead&s=%QUERY&nonce="+typeahead_settings.nonce});t.initialize(),e(\'input[name="s"]\').typeahead({hint:!1},{name:"typeahead",displayKey:"post_title",source:t.ttAdapter()})})})(jQuery)</script>';
}

}
?>