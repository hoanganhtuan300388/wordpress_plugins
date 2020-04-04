<?php
function member_create_shortcode_setting($args) {
   ob_start();
   $postid = $args['id'];
   $dataSetting = get_post_meta( $args['id'] );
   $tg_id = $dataSetting['top_g_id'][0];
   $group_leader = isset($dataSetting['group_leader']) ? $dataSetting['group_leader'][0] : '';
   $set_lg_g_id = isset($dataSetting['set_lg_g_id']) ? $dataSetting['set_lg_g_id'][0] : '';
   $type_setting = $dataSetting['member_meta_box_type'][0];
   $PattenNo =  $dataSetting['pattern_no_post_type'][0];
   $_SESSION['url_org'] = get_page_link(wp_get_post_parent_id(get_the_ID()));
   switch ($type_setting) {
      case 'list_member':
         include('shortcode_list_member.php');
         break;
      case 'div_regist':
         include('shortcode_regist.php');
         break;
      case 'div_confirm':
         include('shortcode_update_member.php');
         break;
      case 'setting_magazine':
         include('shortcode_magazine.php');
         break;
      case 'div_mail':
         include('shortcode_add_mail.php');
         break;
      case 'div_card':
         include('shortcode_member_card.php');
         break;
      case 'copy_member':
         include('shortcode_copy_member.php');
         break;
      case 'multiple_update':
         include('shortcode_multiple_update.php');
         break;
      default:
         break;
   }
   $html = ob_get_contents();
   ob_end_clean();
   return $html;
}
add_shortcode('member-setting', 'member_create_shortcode_setting');
?>
