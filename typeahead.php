<?php
/*
Plugin Name: Typeahead
Plugin URI: http://binaryoung.com/typeahead
Description: Provide autocomplete for search box by typeahead.js.为您的网站搜索框添加搜索下拉词条建议功能。
Author: young
Version: 0.2.1
Author URI: http://binaryoung.com/
*/

//CODE IS POETRY

define('TYPEAHEAD_PLUGIN_VERSION', '0.2.1');
define('TYPEAHEAD_SCRIPTS_VERSION', '0.1');
define('TYPEAHEAD_FILE', __FILE__);
define('TYPEAHEAD_DIR', dirname(__FILE__));


function typeahead_get_controller(){
if(defined('DOING_AJAX') && DOING_AJAX){
return 'ajax';
}else 
if(is_admin()){
return 'admin';
}else{
return 'front';
}
}

function typeahead_i18n($text){
  return __($text,'typeahead');
}

if (version_compare(PHP_VERSION, '5.1.2', '>=')) {
    if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
        spl_autoload_register('typeahead_load_class', true, true);
    } else {
        spl_autoload_register('typeahead_load_class');
    }
} else {

    function __autoload($classname)
    {
        typeahead_load_class($classname);
    }
}

function typeahead_load_class($classname){
if($classname=='typeahead'){
 include_once(TYPEAHEAD_DIR.'/inc/'.typeahead_get_controller().'.php');
}
}

$typeahead = new typeahead();













?>