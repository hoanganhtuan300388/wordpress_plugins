<?php
$service = new serviceController();
$post = get_post();
$tg_id_check = get_post_meta($post->ID, 'nakama_service_param_tg_id', true);
$dis_id_check = get_post_meta($post->ID, 'nakama_service_param_m_bbsid', true);
$style = '';
if(empty($tg_id_check)){
	$style = 'style="pointer-events: none;"';
}
?>
<span id="img_loading_tab" style="display: none;">
	<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) .'assets/img/loadingAnimation.gif' ?>" alt="">
</span>
<div class="setting_view_list window_close_class content-in content-out-service-first">
	<?php include(PLUGIN_SERVICE_PATH_SETTING . 'admin/settings/setting_params.php'); ?>
</div>
<script>
	function reloadPage(){
		location.reload();
	}
</script>
