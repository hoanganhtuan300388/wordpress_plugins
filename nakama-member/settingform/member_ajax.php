<?php
add_action( 'wp_ajax_load_setting_display', 'load_setting_display' );
add_action( 'wp_ajax_load_setting_display', 'load_setting_display' );
function load_setting_display() {
  $result = MemberCrSet::Member_GetWhereLists($_POST['tg_id'],$_POST['post_id'])->allItem;
  $rs = '';
  $rs .= "<option value=>----</option>";
  $GetSettingLists = (isset($result))?$result:"";
  if(!empty($GetSettingLists)) {
    foreach ($GetSettingLists as $key => $item) {
      $rs .= "<option value=".$item->ITEM_ID.">".$item->ITEM_NAME."</option>";
    }
  }
  wp_send_json_success($rs);
  die();
}
add_action( 'wp_ajax_load_setting_view', 'load_setting_view' );
add_action( 'wp_ajax_load_setting_view', 'load_setting_view' );
function load_setting_view() {
  $result = MemberCrSet::Member_GetSettingLists($_POST['tg_id'],$_POST['post_id']);
  $NotUsed = $result->NotUsed;
  $Used = $result->Used;
  $rsNotUsed = '';
  if(!empty($NotUsed)) {
    foreach ($NotUsed as $key => $item) {
      $rsNotUsed .= "<option value=".$item->ITEM_ID.">".$item->ITEM_NAME."</option>";
    }
  }
  $rsUsed = '';
  if(!empty($Used)) {
    foreach ($Used as $key => $item) {
      $rsUsed .= "<option value=".$item->ITEM_ID.">".$item->ITEM_NAME."</option>";
    }
  }
  wp_send_json_success(array($rsNotUsed,$rsUsed));
  die();
}
add_action( 'wp_ajax_load_setting_sort', 'load_setting_sort' );
add_action( 'wp_ajax_load_setting_sort', 'load_setting_sort' );
function load_setting_sort() {
  $result = MemberCrSet::Member_GetSettingSort($_POST['tg_id'],$_POST['post_id']);
  $rs = '';
  $rs = '<option value=""></option>';
  if(!empty($result)) {
    foreach ($result->allItem as $key => $item) {
      $rs .= "<option value=".$item->ITEM_ID.">".$item->ITEM_NAME."</option>";
    }
  }
  wp_send_json_success($rs);
  die();
}

add_action( 'wp_ajax_member_check_LG_ID', 'member_check_LG_ID' );
function member_check_LG_ID() {
  $result = MemberCrSet::member_check_LG_ID($_POST['post_id'],$_POST['TG_ID'],$_POST['LG_ID']);
  wp_send_json_success($result);
  die();
}

/* END SHORTCODE COLUMN */
add_action( 'wp_ajax_member_showElement', 'member_showElement' );
function member_showElement() {
  $type_setting = isset($_REQUEST['type_setting'])?$_REQUEST['type_setting']:'';
  switch ($type_setting) {
    case 'list_member':
      include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_list_member.php');
      die();
      break;
    case 'div_login':
      echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
      include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
      die();
      break;
    case 'div_regist':
      echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
      include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
      die();
      break;
    case 'div_confirm':
      echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
      include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
      die();
      break;
    case 'div_inquery':
      echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
      include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
      die();
      break;
    case 'div_mail':
      echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
      include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
      die();
      break;
    case 'div_card':
      echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
      include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
      die();
      break;
    case 'setting_magazine':
      include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_magazine.php');
      die();
      break;
    case 'copy_member':
      echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
      include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
      die();
      break;
    case 'multiple_update':
      echo "<input type='hidden' name='type_setting_logined' value='".$type_setting."'>";
      include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_member_logined.php');
      die();
      break;

    default:
      break;
  }
}
add_action( 'wp_ajax_member_include_file', 'member_include_file' );
function member_include_file() {
  $tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'';
  $postid = isset($_REQUEST['postid'])?$_REQUEST['postid']:'';
  $input_tg_id = isset($_REQUEST['tg_id'])?$_REQUEST['tg_id']:'';
  switch ($tab) {
    case 'second':
      include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_display.php');
      die();
      break;
    case 'third':
      include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_view.php');
      die();
      break;
    case 'four':
      include(PLUGIN_MEMBER_PATH_SETTING . 'admin/settings/setting_sort.php');
      die();
      break;
    default:
      break;
  }
}
?>
