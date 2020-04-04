<?php
//初期表示ページ設定
function service_create_shortcode_setting($args)
{
    ob_start();
    $post_id = $args['id'];
    $dataSetting = get_post_meta($args['id']);
    $tg_id = $dataSetting['top_g_id'][0];
    //$PattenNo =  $dataSetting['pattern_no_post_type'][0];
    include('shortcode_service_list.php');
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

add_shortcode('service-setting', 'service_create_shortcode_setting');
?>
