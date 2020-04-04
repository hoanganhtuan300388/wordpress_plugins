<?php
/*
 * Plugin Name: NAKAMA Research
 * Author: DynaX
 * Description: なかま2アンケートプラグイン
 */
date_default_timezone_set('Asia/Tokyo');
require('common.php');
require('settings/setting_view.php');
require('settings/setting_api.php');
require_once('config/constant.php');
require('settings/class_page_template.php');
// setting form
require('settingform/index.php');
add_action("admin_menu", "add_research_management_plugin_menu_item");
function add_research_management_plugin_menu_item(){
    add_menu_page("アンケート", "アンケート", "manage_options",
        "research-management-plugin", "research_management_plugin_settings_page", 'dashicons-search', 99);
    add_submenu_page('research-management-plugin','API設定', 'API設定','manage_options','research-management-plugin');
    add_submenu_page('research-management-plugin','フォーム設定','フォーム設定','manage_options','edit.php?post_type=setting_research', false);
    add_action( 'admin_init', 'register_nakama_research_plugin_settings' );
}
function register_nakama_research_plugin_settings() {
  register_setting( 'nakama-research-plugin-settings-group', 'nakama-research-group-id' );
  register_setting( 'nakama-research-plugin-settings-group', 'nakama-research-personal-id' );
  register_setting( 'nakama-research-plugin-settings-group', 'nakama-research-api-key' );
  register_setting( 'nakama-research-plugin-settings-group', 'nakama-research-general-per-page' );
  register_setting( 'nakama-research-plugin-settings-list', 'nakama-research-list-per-page' );
}

// Custom css admin
function research_admin_style() {
  wp_enqueue_style('admin_research_styles', plugin_dir_url( __FILE__ ).'assets/css/admin.css');
  wp_enqueue_script( 'admin_script', plugin_dir_url( __FILE__ ) . 'assets/admin/dual-list-box.js' );
}
add_action('admin_enqueue_scripts', 'research_admin_style');

// NAKAMA research Detail
$research_detail = research_create_new_page('nakama_setting_research Detail', 'nakama-detail-research');
add_filter( 'page_template', 'detail_research_page_template' );
function detail_research_page_template( $page_template )
{
    if ( is_page( 'nakama-detail-research' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/detail.php';
    }
    return $page_template;
}

// NAKAMA research agreement
$research_agreement = research_create_new_page('nakama_setting_research agreement', 'nakama-research-agreement');
add_filter( 'page_template', 'agreement_research_page_template' );
function agreement_research_page_template( $page_template )
{
    if ( is_page( 'nakama-research-agreement' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/agreement.php';
    }
    return $page_template;
}

// NAKAMA research complete
$research_complete = research_create_new_page('nakama_setting_research_complete', 'nakama-research-ans-research-complete');
add_filter( 'page_template', 'research_complete_research_page_template' );
function research_complete_research_page_template( $page_template )
{
    if ( is_page( 'nakama-research-ans-research-complete' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/ans_research_complete.php';
    }
    return $page_template;
}

// NAKAMA research ans_research
$research_agreement = research_create_new_page('nakama_setting_research ans_research', 'nakama-research-ans-research');
add_filter( 'page_template', 'ans_research_research_page_template' );
function ans_research_research_page_template( $page_template )
{
    if ( is_page( 'nakama-research-ans-research' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/ans_research_kaizen.php';
    }
    return $page_template;
}

// NAKAMA research ans_research_target
$research_research_target = research_create_new_page('nakama_setting_research ans_research_target', 'nakama-research-ans-research-target');
add_filter( 'page_template', 'ans_research_target_research_page_template' );
function ans_research_target_research_page_template( $page_template )
{
    if ( is_page( 'nakama-research-ans-research-target' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/ans_research_target.php';
    }
    return $page_template;
}

// NAKAMA research ans_research_disp_detail
$research_research_disp_detail = research_create_new_page('nakama_setting_research ans_research_disp_detail', 'nakama-research-ans-research-disp-detail');
add_filter( 'page_template', 'ans_research_disp_detail_research_page_template' );
function ans_research_disp_detail_research_page_template( $page_template )
{
    if ( is_page( 'nakama-research-ans-research-disp-detail' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/ans_disp_detail.php';
    }
    return $page_template;
}

// Deactivation plugin
function research_deactivation_plugin() {
  wp_delete_post(get_page_by_path('nakama-detail-research')->ID,true);
  wp_delete_post(get_page_by_path('nakama-research-agreement')->ID,true);
  wp_delete_post(get_page_by_path('nakama-research-ans-research-complete')->ID,true);
  wp_delete_post(get_page_by_path('nakama-research-ans-research')->ID,true);
  wp_delete_post(get_page_by_path('nakama-research-ans-research-target')->ID,true);
  wp_delete_post(get_page_by_path('nakama-research-ans-research-disp-detail')->ID,true);
}
register_deactivation_hook( __FILE__, 'research_deactivation_plugin' );

function research_exclude_pages_from_menu ($items, $args) {
  $arrSlug = [
    'nakama-detail-research',
    'nakama-research-agreement',
    'nakama-research-ans-research-complete',
    'nakama-research-ans-research',
    'nakama-research-ans-research-target',
    'nakama-research-ans-research-disp-detail',
  ];
  $arrExistSlug = array();
  foreach ($arrSlug as $key => $item) {
      $get_page = get_page_by_path($item)->ID;
      array_push($arrExistSlug, $get_page);
  }
  foreach ($items as $ix => $obj) {
    if (in_array($obj->object_id, $arrExistSlug)) {
        unset ($items[$ix]);
    }
  }
  return $items;
}
add_filter ('wp_nav_menu_objects', 'research_exclude_pages_from_menu', 10, 2);

function myscript_research() {
  ?>
  <script type="text/javascript">
    (function($) {
      var arrHidden = [
        'nakama_setting_research Detail',
        'nakama_setting_research agreement',
        'nakama_setting_research_complete',
        'nakama_setting_research ans_research',
        'nakama_setting_research ans_research_target',
        'nakama_setting_research ans_research_disp_detail',
      ];
      $("#pagechecklist-most-recent").css('display', 'none');
      $("#pagechecklist-most-recent li").each(function(index){
        var parent = $(this);
        var text = parent.text();
        arrHidden.forEach(function(e){
          if(text.indexOf(e) != -1){
            parent.remove();
          }
        });
      });
      $("#pagechecklist-most-recent").css('display', 'block');
  
      $("#pagechecklist li label").each(function(index){
        var parent = $(this);
        var text = parent.text();
        arrHidden.forEach(function(e){
          if(text.indexOf(e) != -1){
            parent.remove();
          }
        });
      });
      $("#page-search-checklist").css('display', 'none');
      $("#quick-search-posttype-page").change(function(){
        $("#page-search-checklist").trigger("updatecomplete");
      });
      $("#page-search-checklist").bind("updatecomplete", function() {
        $("#page-search-checklist li").each(function(index){
          var parent = $(this);
          var text = parent.text();
          arrHidden.forEach(function(e){
            if(text.indexOf(e) != -1){
              parent.remove();
            }
          });
        });
        $("#page-search-checklist").css('display', 'block');
      });
      $("#menu-to-edit").css('display', 'none');
      $("#menu-to-edit li").each(function(i){
        var parent = $(this);
        var text = parent.find(".menu-item-title").text();
        arrHidden.forEach(function(e){
          if(text.indexOf(e) != -1){
            parent.remove();
          }
        });
      });
      $("#menu-to-edit").css('display', 'block');
      var post_type = $("input[name=post_type]").val();
      if(post_type == "setting_research"){
        $("#toplevel_page_research-management-plugin").addClass("wp-has-current-submenu");
        $("#toplevel_page_research-management-plugin > a").addClass("wp-has-current-submenu");
      }
    })(jQuery);
  </script>
  <?php
  }
  add_action( 'admin_footer', 'myscript_research' );

//HIDE PAGE IN ADMIN
$arrSlug = get_pages(array('post_status' => 'publish'));
$arrExistSlug = array();
foreach ($arrSlug as $key => $item) {
  if (strpos($item->post_title, 'nakama_setting_') !== false) {
    $get_page = get_page_by_path($item->post_name)->ID;
    array_push($arrExistSlug, $get_page);
  }
}
add_filter( 'parse_query', 'hidden_pages_from_admin_research' );
function hidden_pages_from_admin_research($query) {
    global $pagenow, $post_type;
    GLOBAL $arrExistSlug;
    if (is_admin() && $pagenow=='edit.php' && $post_type =='page') {
        $query->query_vars['post__not_in'] = $arrExistSlug;
    }
}

add_filter( 'wp_link_query_args', 'research_custom_link_query' );
function research_custom_link_query( $query ){
  GLOBAL $arrExistSlug;
  $query['post__not_in'] = $arrExistSlug;  
  return $query; 
}

add_filter( 'page_attributes_dropdown_pages_args', 'research_hide_attr_page_parents' );
add_filter( 'quick_edit_dropdown_pages_args', 'research_hide_attr_page_parents' );
function research_hide_attr_page_parents( $args )
{   
  GLOBAL $arrExistSlug;
  $args['exclude_tree'] = $arrExistSlug;
  return $args;
}
