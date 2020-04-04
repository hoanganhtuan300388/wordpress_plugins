<?php
function research_create_shortcode_setting($args) {
  ob_start();
  $postid = $args['id'];
  $dataSetting = get_post_meta( $args['id'] );
  $tg_id = $dataSetting['top_g_id'][0];
  $PattenNo =  $dataSetting['pattern_no_post_type'][0];
  
  include('shortcode_list_research.php');
  $html = ob_get_contents();
  ob_end_clean();
  return $html;
}
add_shortcode('research-setting', 'research_create_shortcode_setting');
?>
