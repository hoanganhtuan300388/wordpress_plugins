<?php 
/**
 * Plugin Name: Setting member
 * Description: Setting member
 * Version:     1.1
 * Author:      Runsystem
 */
define('PLUGIN_MEMBER_PATH_SETTING', plugin_dir_path(__FILE__));
require_once(PLUGIN_MEMBER_PATH_SETTING . 'config/constant.php');
require_once(PLUGIN_MEMBER_PATH_SETTING . 'setup.php');
require_once(PLUGIN_MEMBER_PATH_SETTING . 'function.php');
require_once(PLUGIN_MEMBER_PATH_SETTING . 'api/common.php');
require_once(PLUGIN_MEMBER_PATH_SETTING . 'member_ajax.php');
require_once(PLUGIN_MEMBER_PATH_SETTING . 'shortcode/init_shortcode.php');
?>