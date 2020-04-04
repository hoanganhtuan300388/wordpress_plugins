<?php
	$group_id_check = get_post_meta($post->ID, 'service_meta_group_id', true);
	if(empty($group_id_check)){
		update_post_meta($post->ID, "service_meta_group_id", get_option('nakama-service-group-id'));
		update_post_meta($post->ID, "service_meta_p_id", get_option('nakama-service-personal-id'));
		update_post_meta($post->ID, "service_meta_api_key", get_option('nakama-service-api-key'));
	}
	$service_meta_group_id = get_post_meta($post->ID, 'service_meta_group_id', true);
	update_post_meta($post->ID, "nakama_service_param_tg_id", $service_meta_group_id);
?>
<div class="wrap cpt-service">
	<div class="text_shortcode">
		<div id="shortcode">
				<p style="font-weight: bold;color: #0f6ab4;">ショートコード</p>
				<input id="html" value='[service-setting id="<?php echo get_the_ID(); ?>"]' readonly="" name="service_meta_short_code">
		</div>
	</div>
	<div class="setting_view_list setting_params_call_api">
		<h1 class="setting_title">API 連携設定</h1>
		<table class="setting-table-param w-100">
					<tr>
						<td>団体ID</td>
						<td>
							<?php $group_id_custom = ($group_id == '')?esc_attr( get_option('nakama-service-group-id') ):$group_id; ?>
							<input type="text" name="service_meta_group_id" id="service_meta_group_id" value="<?php echo $group_id_custom; ?>" />
						</td>
					</tr>
					<?php $p_id_custom = ($p_id == '')?esc_attr( get_option('nakama-service-personal-id') ):$p_id; ?>
					<input type="hidden" name="service_meta_p_id" id="service_meta_p_id" value="<?php echo $p_id_custom; ?>" />
					<tr>
						<td>APIキー</td>
						<td>
							<?php $api_key_custom = ($api_key == '')?esc_attr( get_option('nakama-service-api-key') ):$api_key; ?>
							<textarea rows="3" cols="80" name="service_meta_api_key" ><?php echo $api_key_custom; ?></textarea>
						</td>
					</tr>
			</table>
	</div>
	<div class="panel_event">
		<div class="col-md-12 col-sm-12">
			<input type="hidden" name="postID" value="<?php echo $post->ID; ?>">
			<input type="hidden" name="pattern_no_post_type" value="<?php echo serviceController::getPatternNoPosttype($post->ID);?>">
			<span id="img_loading_content" style="display: none;">
				<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) .'assets/img/loadingAnimation.gif' ?>" alt="">
			</span>
			<div id="content_option">
				<?php
					include(PLUGIN_SERVICE_PATH_SETTING . 'admin/settings/setting_list.php');
				?>
			</div>
		</div>
	</div>
</div>
