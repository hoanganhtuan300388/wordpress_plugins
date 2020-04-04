<?php
/*
 * Plugin Name: NAKAMA Member
 * Author: DynaX
 * Description: なかま２会員管理プラグイン
 */
require('common.php');
require('settings/setting_api.php');
require('config/constant.php');
require('controller/memberController.php');
require('settings/class_page_template.php');
// setting form
require('settingform/index.php');

add_action("admin_menu", "add_member_management_plugin_menu_item");
function add_member_management_plugin_menu_item(){
    add_menu_page("会員管理 ", "会員管理 ", "manage_options",
        "member-management-plugin", "member_management_plugin_settings_api", 'dashicons-groups', 99);
    add_submenu_page('member-management-plugin','API設定', 'API設定','manage_options','member-management-plugin');
    add_submenu_page('member-management-plugin','フォーム設定','フォーム設定','manage_options','edit.php?post_type=setting_member', false);
    add_action( 'admin_init', 'register_nakama_member_plugin_settings' );
}
function register_nakama_member_plugin_settings() {
  register_setting( 'nakama-member-plugin-settings-group', 'nakama-member-group-id' );
  register_setting( 'nakama-member-plugin-settings-group', 'nakama-member-personal-id' );
  register_setting( 'nakama-member-plugin-settings-group', 'nakama-member-api-key' );
  register_setting( 'nakama-member-plugin-settings-group', 'nakama-member-general-per-page' );
  register_setting( 'nakama-member-plugin-settings-view-list', 'nakama-member-list-per-page');
  register_setting( 'nakama-member-plugin-settings-view-list', 'nak-member-key-list-show');
  register_setting( 'nakama-member-plugin-settings-view-list', 'nak-member-key-list-all');
  register_setting( 'nakama-member-plugin-settings-view-list', 'nak-member-pattern-no');
  register_setting( 'nakama-member-plugin-settings-list', 'nak-member-list-tg-id');
  register_setting( 'nakama-member-plugin-settings-list', 'nak-member-list-g-id');
  register_setting( 'nakama-member-plugin-settings-list', 'nak-member-list-top-type-visible');
  register_setting( 'nakama-member-plugin-settings-list', 'nak-member-list-top-type');
  register_setting( 'nakama-member-plugin-settings-list', 'nak-member-list-p-id');
  register_setting( 'nakama-member-plugin-settings-list', 'nak-member-list-site-id');
  register_setting( 'nakama-member-plugin-settings-list', 'nak-member-list-page-no');
  register_setting( 'nakama-member-plugin-settings-list', 'nak-member-list-pattern-no');
  register_setting( 'nakama-member-plugin-settings-sort', 'nak-member-sort-column1');
  register_setting( 'nakama-member-plugin-settings-sort', 'nak-member-sort-column2');
  register_setting( 'nakama-member-plugin-settings-sort', 'nak-member-sort-column3');
  register_setting( 'nakama-member-plugin-settings-sort', 'nak-member-sort-column1-orderby');
  register_setting( 'nakama-member-plugin-settings-sort', 'nak-member-sort-column2-orderby');
  register_setting( 'nakama-member-plugin-settings-sort', 'nak-member-sort-column3-orderby');
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-w-first' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-w-second' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-w-third' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-w-four' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-w-five' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-w-six' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-equa-first' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-equa-second' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-equa-third' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-equa-four' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-equa-five' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-equa-six' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-add-second' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-add-third' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-add-four' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-add-five' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-add-six' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-input-first' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-input-second' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-input-third' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-input-four' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-input-five' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-input-six' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-input-first-2' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-input-second-2' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-input-third-2' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-input-four-2' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-input-five-2' );
  register_setting( 'nakama-member-plugin-settings-display', 'nakama-member-input-six-2' );

  register_setting( 'nakama-member-plugin-settings-general', 'nak-member-general-tg-id');
  register_setting( 'nakama-member-plugin-settings-general', 'nak-member-general-g-id');
  register_setting( 'nakama-member-plugin-settings-general', 'nak-member-general-lg-id');
  register_setting( 'nakama-member-plugin-settings-general', 'nak-member-general-p-id');
  register_setting( 'nakama-member-plugin-settings-general', 'nak-member-general-status');
  register_setting( 'nakama-member-plugin-settings-general', 'nak-member-general-lg-type');
  register_setting( 'nakama-member-plugin-settings-general', 'nak-member-general-site-id');
  register_setting( 'nakama-member-plugin-settings-general', 'nak-member-general-page-no');
  register_setting( 'nakama-member-plugin-settings-general', 'nak-member-general-pattern-no');
  register_setting( 'nakama-member-plugin-settings-general', 'nakama-member-general-per-page');

  register_setting( 'nakama-member-plugin-settings-regist', 'nak-member-regist-tg-id');
  register_setting( 'nakama-member-plugin-settings-regist', 'nak-member-regist-status');
  register_setting( 'nakama-member-plugin-settings-regist', 'nak-member-regist-lg-id');
  register_setting( "nakama-member-plugin-settings-regist", "nak-member-regist-file");

  register_setting( 'nakama-member-plugin-settings-magazine', 'nak-member-magazine-tg-id');
  register_setting( 'nakama-member-plugin-settings-magazine', 'nak-member-magazine-g-id');
  register_setting( 'nakama-member-plugin-settings-magazine', 'nak-member-magazine-nakama-url');
  register_setting( 'nakama-member-plugin-settings-magazine', 'nak-member-magazine-mail-address');
  register_setting( 'nakama-member-plugin-settings-magazine', 'nak-member-magazine-disp-header-file-reg');
  register_setting( 'nakama-member-plugin-settings-magazine', 'nak-member-magazine-disp-header-file-del');

  register_setting( 'nakama-member-plugin-settings-addmail', 'nak-member-addmail-tg-id');

  register_setting( 'nakama-member-plugin-settings-list-logined', 'nakama-member-list-logined-per-page');
  // member-setting-member-logined
  register_setting( 'nakama-member-plugin-settings-member-logined', 'nakama_url');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'top_g_id');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'set_lg_g_id');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'set_g_id');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'mail_address');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'linkage_type');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'lg_login');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'lg_disp');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'disp_menu');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'login_name');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'login_caption');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'group_leader');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'top_type_visible');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'top_type');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'marketing_visible');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'marketing_mail');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'category_visible');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'regist_name');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'regist_caption');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'disp_header_file_reg');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'disp_header_file_reg_end');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'merumaga_flg');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'disp_merumaga_file');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'disp_merumaga_file_end');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'disp_entry_file');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'input_open');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'input_open_end');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'entry_setting2');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'entry_setting2_end');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'entry_setting3');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'entry_setting3_end');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'entry_setting4');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'entry_setting4_end');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'entry_setting5');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'entry_setting5_end');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'entry_setting1');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'entry_setting1_end');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'mail_subject');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'mail_subject_end');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'mail_body');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'mail_body_end');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'confirm_name');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'confirm_caption');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'ReleaseDisp');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'ReleaseDisp_end');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'query_name');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'query_caption');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'mail_name');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'mail_caption');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'mail_auto_update');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'auto_reg_flg');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'member_card_name');
  register_setting( 'nakama-member-plugin-settings-member-logined', 'member_card_caption');
}

function set_default_option_member(){
  $member_key_list_show = array(
    "TEMP_URL",
    "TEMP_1",
    "ORG_REPRESENTATIVE_NM",
    "ORG_G_NAME",
    "TEMP_2",
    "TEMP_3",
    "TEMP_4",
    "ORG_REPRESENTATIVE_OP",
    "ORG_G_TEL",
    "ORG_G_STA",
    "ORG_G_POST",
    "ORG_G_ADR",
    "ORG_G_ADR2"
  );
  if(empty(get_option('nak-member-key-list-show')))
    if(!is_serialized( $member_key_list_show )){
      $member_key_list_show = maybe_serialize($member_key_list_show);
      update_option('nak-member-key-list-show', $member_key_list_show);
    }
  if(empty(get_option('nakama-member-list-per-page')))
    update_option('nakama-member-list-per-page', 100);
  if(empty(get_option('nakama-member-list-logined-per-page')))
    update_option('nakama-member-list-logined-per-page', 100);
  if(empty(get_option('nak-member-regist-status')))
    update_option('nak-member-regist-status', 0);
}
add_action( 'activated_plugin', 'set_default_option_member');
// Custom css admin
function member_admin_style() {
  wp_enqueue_style('admin-member-styles', plugin_dir_url( __FILE__ ).'assets/css/admin.css');
  wp_enqueue_script('admin-member-js',plugins_url('assets/js/admin_member.js',__FILE__),'','',true);
}
add_action('admin_enqueue_scripts', 'member_admin_style');

function deactivation_plugin() {
  wp_delete_post(get_page_by_path('nakama-list-member')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-add-email')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-edit')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-edit-kojin')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-edit-kojin-confirm')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-edit-kojin-complete')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-edit-pr')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-edit-pr-confirm')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-edit-pr-complete')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-regist')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-setting-public-item')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-detail-member')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-magazine')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-remove-magazine')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-card')->ID,true);
  wp_delete_post(get_page_by_path('issue-certification')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-list-group')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-zipcode')->ID,true);
  wp_delete_post(get_page_by_path('nakama-select-dic')->ID,true);
  wp_delete_post(get_page_by_path('nakama-select-dictionary')->ID,true);
  wp_delete_post(get_page_by_path('nakama-select-dictionary-list')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-category')->ID,true);
  wp_delete_post(get_page_by_path('nakama-search-bank')->ID,true);
  wp_delete_post(get_page_by_path('nakama-search-lg')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-confirm')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-complete')->ID,true);
  wp_delete_post(get_page_by_path('nakama-update-confirm')->ID,true);
  wp_delete_post(get_page_by_path('nakama-update-complete')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-edit-organization')->ID,true);
  wp_delete_post(get_page_by_path('nakama-update-organization-confirm')->ID,true);
  wp_delete_post(get_page_by_path('nakama-update-organization-complete')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-profile')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-check-magazine')->ID,true);
  wp_delete_post(get_page_by_path('nakama-logined-setting-view')->ID,true);
  wp_delete_post(get_page_by_path('nakama-logined-setting-sort')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-feestatus')->ID,true);
  wp_delete_post(get_page_by_path('nakama-member-imgview')->ID,true);
}
register_deactivation_hook( __FILE__, 'deactivation_plugin' );

// NAKAMA list group - nguyentc
$list_group = member_create_new_page('nakama_setting_member_list_group', 'nakama-member-list-group');
add_filter( 'page_template', 'list_member_format_list_group_page_template' );
function list_member_format_list_group_page_template( $page_template )
{
    if ( is_page( 'nakama-member-list-group' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/list_group.php';
    }
    return $page_template;
}

// NAKAMA member zipcode
$membership_card = member_create_new_page('nakama_setting_member_zipcode', 'nakama-member-zipcode');
add_filter( 'page_template', 'list_member_format_member_zipcode_page_template' );
function list_member_format_member_zipcode_page_template( $page_template )
{
    if ( is_page( 'nakama-member-zipcode' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/zipcode.php';
    }
    return $page_template;
}

// NAKAMA member get category
$member_get_category = member_create_new_page('nakama_setting_get_category', 'nakama-member-category');
add_filter( 'page_template', 'list_member_format_member_category_page_template' );
function list_member_format_member_category_page_template( $page_template )
{
    if ( is_page( 'nakama-member-category' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/list_category.php';
    }
    return $page_template;
}

// NAKAMA member get FeeStatus
$get_FeeStatus = member_create_new_page('nakama_setting_get_feestatus', 'nakama-member-feestatus');
add_filter( 'page_template', 'list_member_format_feestatus_page_template' );
function list_member_format_feestatus_page_template( $page_template )
{
    if ( is_page( 'nakama-member-feestatus' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/feestatus.php';
    }
    return $page_template;
}

// NAKAMA member img view
$member_img_view = member_create_new_page('nakama_setting_get_imgview', 'nakama-member-imgview');
add_filter( 'page_template', 'list_member_format_imgview_page_template' );
function list_member_format_imgview_page_template( $page_template )
{
    if ( is_page( 'nakama-member-imgview' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/imgview.php';
    }
    return $page_template;
}

// NAKAMA member get search brank
$search_brank = member_create_new_page('nakama_setting_search_bank', 'nakama-search-bank');
add_filter( 'page_template', 'list_member_format_search_bank_page_template' );
function list_member_format_search_bank_page_template( $page_template )
{
    if ( is_page( 'nakama-search-bank' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/search_bank.php';
    }
    return $page_template;
}

// NAKAMA member select dictionary
$select_dictionary = member_create_new_page('nakama_setting_select_dictionary', 'nakama-select-dictionary');
add_filter( 'page_template', 'list_member_format_select_dictionary_page_template' );
function list_member_format_select_dictionary_page_template( $page_template )
{
    if ( is_page( 'nakama-select-dictionary' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/select_dic.php';
    }
    return $page_template;
}
// NAKAMA member select dictionary - list
$select_dictionary_list = member_create_new_page('nakama_setting_select_dictionary_list', 'nakama-select-dictionary-list');
add_filter( 'page_template', 'list_member_format_select_dictionary_list_page_template' );
function list_member_format_select_dictionary_list_page_template( $page_template )
{
    if ( is_page( 'nakama-select-dictionary-list' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/search_dictionary_list.php';
    }
    return $page_template;
}

// NAKAMA member get search LG
$search_lg= member_create_new_page('nakama_setting_member_search_lg', 'nakama-search-lg');
add_filter( 'page_template', 'list_member_format_search_lg_page_template' );
function list_member_format_search_lg_page_template( $page_template )
{
    if ( is_page( 'nakama-search-lg' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/search_lg.php';
    }
    return $page_template;
}

// NAKAMA check_magazine
$check_magazine = member_create_new_page('nakama_setting_check_magazine', 'nakama-member-check-magazine');
add_filter( 'page_template', 'list_member_format_member_check_magazine_page_template' );
function list_member_format_member_check_magazine_page_template( $page_template )
{
    if ( is_page( 'nakama-member-check-magazine' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/check_magazine.php';
    }
    return $page_template;
}


add_action( 'wp_ajax_check_email', 'check_email_api' );
add_action( 'wp_ajax_nopriv_check_email', 'check_email_api' );
function check_email_api() {
    $urlverifyEmail = URL_API."Member/verifyEmail";
    $post_id = $_POST['post_id'];
    $arrBody = array(
        "C_EMAIL"=> $_POST['C_EMAIL'],
        "TG_ID"=> get_post_meta($post_id, "member_meta_group_id", true)
    );
    $verifyEmail = get_api_common($post_id, $urlverifyEmail, $arrBody, "POST");
    wp_send_json_success($verifyEmail);
    die();
}
add_action( 'wp_ajax_moveGroup', 'moveGroup' );
add_action( 'wp_ajax_nopriv_moveGroup', 'moveGroup' );
function moveGroup() {
    $urlmoveGroup = URL_API."Member/MoveGroup";
    $post_id = $_POST['post_id'];
    $arrBody = array(
        "TG_ID"=> ($_SESSION['arrSession']->TG_ID)?$_SESSION['arrSession']->TG_ID:get_option('nakama-member-group-id'),
        "LG_ID"=> $_POST['LG_ID'],
        "M_ID"=> $_SESSION['arrSession']->M_ID,
        "U_USER"=> $_SESSION['arrSession']->USER_P_ID,
    );
    // $moveGroup = get_api_common($post_id, $urlmoveGroup, $arrBody, "POST");
    // if($moveGroup->status == "Success") {
    //     $_SESSION['flag_Group'] = 1;
    //     $_SESSION['LG_NAME'] = $_POST['LG_NAME'];
    //     $_SESSION['arrSession']->LG_ID = $arrBody['LG_ID'];
    // }
    // wp_send_json_success($moveGroup);
    $_SESSION['flag_Group'] = 1;
    $_SESSION['LG_NAME'] = $_POST['LG_NAME'];
    $_SESSION['arrSession']->LG_ID = $_POST['LG_ID'];
    $_SESSION['arrSession']->M_ID = $_POST['M_ID'];
    $_SESSION['post_id'] = $post_id;
    wp_send_json_success(['status' => true]);
    die();
}
// NAKAMA confirm member
$confirm_member = member_create_new_page('nakama_setting_confirm', 'nakama-member-confirm');
add_filter( 'page_template', 'list_member_format_member_confirm_page_template' );
function list_member_format_member_confirm_page_template( $page_template )
{
    if ( is_page( 'nakama-member-confirm' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/confirm.php';
    }
    return $page_template;
}

// NAKAMA complete member
$complete_member = member_create_new_page('nakama_setting_complete', 'nakama-member-complete');
add_filter( 'page_template', 'list_member_format_member_complete_page_template' );
function list_member_format_member_complete_page_template( $page_template )
{
    if ( is_page( 'nakama-member-complete' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/complete.php';
    }
    return $page_template;
}

// NAKAMA update confirm
$update_confirm = member_create_new_page('nakama_setting_update_confirm', 'nakama-update-confirm');
add_filter( 'page_template', 'list_member_format_update_confirm_page_template' );
function list_member_format_update_confirm_page_template( $page_template )
{
    if ( is_page( 'nakama-update-confirm' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/update_confirm.php';
    }
    return $page_template;
}

// NAKAMA update complete
$update_complete = member_create_new_page('nakama_setting_update_complete', 'nakama-update-complete');
add_filter( 'page_template', 'list_member_format_update_complete_page_template' );
function list_member_format_update_complete_page_template( $page_template )
{
    if ( is_page( 'nakama-update-complete' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/update_complete.php';
    }
    return $page_template;
}

// Logined setting view
$Logined_setting_view = member_create_new_page('nakama_setting_view', 'nakama-logined-setting-view');
add_filter( 'page_template', 'list_logined_setting_view_page_template' );
function list_logined_setting_view_page_template( $page_template )
{
    if ( is_page( 'nakama-logined-setting-view' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/list_logined_setting_view.php';
    }
    return $page_template;
}
// Logined setting sort
$Logined_setting_sort = member_create_new_page('nakama_setting_login_sort', 'nakama-logined-setting-sort');
add_filter( 'page_template', 'list_logined_setting_sort_page_template' );
function list_logined_setting_sort_page_template( $page_template )
{
    if ( is_page( 'nakama-logined-setting-sort' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/list_logined_setting_sort.php';
    }
    return $page_template;
}

// NAKAMA detail member page
$detail_member = member_create_new_page('nakama_setting_member_detail_2', 'nakama-member-detail-member');
add_filter( 'page_template', 'list_member_format_detail_member_page_template' );
function list_member_format_detail_member_page_template( $page_template )
{
    if ( is_page( 'nakama-member-detail-member' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/detail.php';
    }
    return $page_template;
}

// NAKAMA detail member card
$detail_member_card = member_create_new_page('nakama_setting_member_card', 'nakama-member-card');
add_filter( 'page_template', 'member_card_detail_page_template' );
function member_card_detail_page_template( $page_template )
{
    if ( is_page( 'nakama-member-card' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/detail_member_card.php';
    }
    return $page_template;
}

//Ajax get List Category
add_action( 'wp_ajax_rs_get_branch_list', 'rs_get_branch_list' );
add_action( 'wp_ajax_nopriv_rs_get_branch_list', 'rs_get_branch_list' );
function rs_get_branch_list() {
    $tg_id = isset($_REQUEST['tg_id'])?$_REQUEST['tg_id']:'';
    $brank_code = isset($_REQUEST['bankCode'])?$_REQUEST['bankCode']:'';
    $post_id = isset($_REQUEST['post_id'])?$_REQUEST['post_id']:'';
    $url = URL_API."Setting/GetBranchInfo?tg_id=".$tg_id."&bank_nm=&bank_cd=".$brank_code;
    $rs = get_api_common($post_id, $url, "", 'GET');
    $rs = $rs->DATA;
    echo json_encode($rs, JSON_UNESCAPED_UNICODE);
    die();
}
function myscript_jquery_member() {
    wp_enqueue_script( 'jquery' );
}
add_action( 'admin_head' , 'myscript_jquery_member' );
function myscript_member() {
?>
<script type="text/javascript">
  (function($) {
    var arrHidden = [
      'nakama_setting_member_zipcode', 
      'nakama_setting_get_category',
      'nakama_setting_get_feestatus',
      'nakama_setting_get_imgview',
      'nakama_setting_search_bank',
      'nakama_setting_select_dictionary',
      'nakama_setting_select_dictionary_list',
      'nakama_setting_member_search_lg',
      'nakama_setting_check_magazine',
      'nakama_setting_confirm',
      'nakama_setting_complete',
      'nakama_setting_update_confirm',
      'nakama_setting_update_complete',
      'nakama_setting_view',
      'nakama_setting_login_sort',
      'nakama_setting_member_detail_2',
      'nakama_setting_login',
      'nakama_setting_member_list_group',
      'nakama_setting_member_card'
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
    if(post_type == "setting_member"){
      $("#toplevel_page_member-management-plugin").addClass("wp-has-current-submenu");
      $("#toplevel_page_member-management-plugin > a").addClass("wp-has-current-submenu");
    }
  })(jQuery);
</script>
<?php
}
add_action( 'admin_footer', 'myscript_member' );
function member_exclude_pages_from_menu ($items, $args) {
  $arrSlug = [
    'nakama-list-member',
    'nakama-member-add-email',
    'nakama-member-edit',
    'nakama-member-edit-kojin',
    'nakama-member-edit-kojin-confirm',
    'nakama-member-edit-kojin-complete',
    'nakama-member-edit-pr',
    'nakama-member-edit-pr-confirm',
    'nakama-member-edit-pr-complete',
    'nakama-member-regist',
    'nakama-member-setting-public-item',
    'nakama-member-detail-member',
    'nakama-member-magazine',
    'nakama-member-remove-magazine',
    'nakama-member-card',
    'issue-certification',
    'nakama-member-list-group',
    'nakama-member-zipcode',
    'nakama-select-dic',
    'nakama-select-dictionary',
    'nakama-select-dictionary-list',
    'nakama-member-category',
    'nakama-search-bank',
    'nakama-search-lg',
    'nakama-member-confirm',
    'nakama-member-complete',
    'nakama-update-confirm',
    'nakama-update-complete',
    'nakama-member-edit-organization',
    'nakama-update-organization-confirm',
    'nakama-update-organization-complete',
    'nakama-member-profile',
    'nakama-member-check-magazine',
    'nakama-logined-setting-view',
    'nakama-logined-setting-sort',
    'nakama-member-feestatus',
    'nakama-member-imgview',
    'nakama_setting_member_card'
  ];
  $arrExistSlug = array();
  foreach ($arrSlug as $key => $item) {
    if(get_page_by_path($item)){
      $get_page = get_page_by_path($item)->ID;
      array_push($arrExistSlug, $get_page);
    }
  }
  foreach ($items as $ix => $obj) {
    if (in_array($obj->object_id, $arrExistSlug)) {
        unset ($items[$ix]);
    }
  }
  return $items;
}
add_filter ('wp_nav_menu_objects', 'member_exclude_pages_from_menu', 10, 2);

//HIDE PAGE IN ADMIN
$arrSlug = get_pages(array('post_status' => 'publish'));
$arrExistSlug = array();
foreach ($arrSlug as $key => $item) {
  if (strpos($item->post_title, 'nakama_setting_') !== false) {
    $get_page = get_page_by_path($item->post_name)->ID;
    array_push($arrExistSlug, $get_page);
  }
}
add_filter( 'parse_query', 'hidden_pages_from_admin_member' );
function hidden_pages_from_admin_member($query) {
    global $pagenow, $post_type;
    GLOBAL $arrExistSlug;
    if (is_admin() && $pagenow=='edit.php' && $post_type =='page') {
        $query->query_vars['post__not_in'] = $arrExistSlug;
    }
}

add_filter( 'wp_link_query_args', 'member_custom_link_query' );
function member_custom_link_query( $query ){
  GLOBAL $arrExistSlug;
  $query['post__not_in'] = $arrExistSlug;  
  return $query; 
}

add_filter( 'page_attributes_dropdown_pages_args', 'member_hide_attr_page_parents' );
add_filter( 'quick_edit_dropdown_pages_args', 'member_hide_attr_page_parents' );
function member_hide_attr_page_parents( $args )
{   
  GLOBAL $arrExistSlug;
  $args['exclude_tree'] = $arrExistSlug;
  return $args;
}