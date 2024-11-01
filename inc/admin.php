<?php
class typeahead{
public function __construct() {
				 register_activation_hook( TYPEAHEAD_FILE, array( $this, 'activate' ) );//安装插件时添加设置
				 register_uninstall_hook(TYPEAHEAD_FILE, array( $this, 'delete_options' ) );//删除插件时删除设置
				 //register_deactivation_hook( TYPEAHEAD_FILE, array( $this, 'delete_options' ) ); 
				 add_filter('plugin_action_links',array( $this, 'settings_link' ),10,2);
				 add_action( 'admin_menu', array( $this, 'menu' ) );
				 add_action( 'admin_init', array( $this, 'admin_init' ) );
                 add_action('plugins_loaded', array( $this, 'i18n' ));
}

public function activate(){
  $option_defaults=array('number'=>10 ,'jquery'=>true,'cdn'=>false); //默认设置
	add_option('typeahead', $option_defaults);
}

public function delete_options() {
        delete_option('typeahead');
}

public function settings_link($action_links,$plugin_file){
	if($plugin_file==plugin_basename(TYPEAHEAD_FILE)){
		$settings_link = '<a href="options-general.php?page=typeahead">'.__("Settings").'</a>';
		array_unshift($action_links,$settings_link);
	}
	return $action_links;
}

public function i18n() {
  load_plugin_textdomain( 'typeahead',false,dirname( plugin_basename( TYPEAHEAD_FILE) ) . '/languages/' );
}



//管理界面

public function menu() {
    add_options_page( 'Typeahead '.__("Settings"),'Typeahead '.__("Settings"), 'manage_options', 'typeahead', array( $this, 'options_page' ));
}

public function options_page() {
    ?>
    <div class="wrap">
        <h2><?php echo 'Typeahead '.__("Settings"); ?></h2>
        <form action="options.php" method="POST">
            <?php settings_fields( 'typeahead-group' ); ?>
            <?php do_settings_sections( 'typeahead' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}


public function admin_init() {
    register_setting( 'typeahead-group', 'typeahead' );
    add_settings_section( 'typeahead-basic', __('General Settings'), array( $this, 'basic_callback' ), 'typeahead' );
	add_settings_section( 'typeahead-suggest', typeahead_i18n('Suggestion'), array( $this, 'suggest_callback' ), 'typeahead' );
    add_settings_field( 'typeahead-item-number', typeahead_i18n('Max number of suggestions'), array( $this, 'item_number_callback' ), 'typeahead', 'typeahead-basic' );
	add_settings_field( 'typeahead-jquery', typeahead_i18n('Load jQuery'),array( $this, 'jquery_callback' ) , 'typeahead', 'typeahead-basic' );
}

public function suggest_callback() {
    echo typeahead_i18n('Any suggestions，issues please comment on plugin home page <a href="http://binaryoung.com/typeahead">check out</a>.');
}

public function basic_callback() {
    echo '';
}

public function item_number_callback() {
    $settings = (array) get_option( 'typeahead' );
    $number = (int) esc_attr( $settings['number'] );
    echo '<input type="text" name="typeahead[number]" value="'.$number.'" />';
}

public function jquery_callback() {
    $settings = (array) get_option( 'typeahead' );
    $jquery = (bool) esc_attr( $settings['jquery'] );
    echo '<input type="checkbox" name="typeahead[jquery]" value="1"'.checked($jquery,true,false).'/><br/><br/>'.typeahead_i18n('Whether load jQuery,if your site have already included jquery,please do not check it.');
}

}
?>