<?php
add_action( 'wp_ajax_research_include_file', 'research_include_file' );
function research_include_file() {
  $tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'';
  $postid = isset($_REQUEST['postid'])?$_REQUEST['postid']:'';
  $input_tg_id = isset($_REQUEST['tg_id'])?$_REQUEST['tg_id']:'';
  switch ($tab) {
    case 'second':
      include(PLUGIN_research_PATH_SETTING . 'admin/settings/setting_display.php');
      die();
      break;
    case 'third':
      include(PLUGIN_research_PATH_SETTING . 'admin/settings/setting_view.php');
      die();
      break;
    case 'four':
      include(PLUGIN_research_PATH_SETTING . 'admin/settings/setting_sort.php');
      die();
      break;
    default:
      break;
  }
}
?>
