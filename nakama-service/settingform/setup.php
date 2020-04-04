<?php
add_action( 'init', 'create_setting_service_post_type' );
function create_setting_service_post_type() {
	register_post_type( 'setting_service',
			array(
					'labels' => array(
							'name' => __( '会員PRサービス設定' ),
								'singular_name' => __( '会員PRサービス設定' ),
								'add_new_item' => 'フォーム新規追加',
								'add_new'			=> 'フォーム新規追加',
								'edit_item'		=> '会員PRサービス設定'
					),
					'supports' => array(
							'title',
					),
					'public' => true,
					'publicly_queryable' => true,
					'exclude_from_search' => true,
						'register_meta_box_cb' => 'edit_service_button_fields',
						'show_in_menu' => 'edit.php?post_type=setting_service',
			)
	);
}
function edit_service_button_fields () {
		add_meta_box( 'service_meta_box_type', '', 'service_meta_box_type');
}

add_action('edit_form_after_title', 'change_template_setting_service_page');
function change_template_setting_service_page ($post_id) {
		if (get_post_type() == 'setting_service') {
				setting_service_page();
		}
}
function setting_service_page(){
		$post = get_post();
		$type_setting = get_post_meta($post->ID, 'service_meta_box_type', true);
		$nakama_service_param_tg_id = get_post_meta($post->ID, 'nakama_service_param_tg_id', true);
		$group_id = get_post_meta($post->ID, 'service_meta_group_id', true);
		$p_id = get_post_meta($post->ID, 'service_meta_p_id', true);
		$api_key = get_post_meta($post->ID, 'service_meta_api_key', true);
		$dis_id = get_post_meta($post->ID, 'nakama_service_param_m_bbsid', true);
		$nakama_service_per_page = get_post_meta($post->ID, 'nakama_service_per_page', true);
		$pattern_no_post_type = get_post_meta($post->ID, 'pattern_no_post_type', true);

		$nakama_service_param_lg_g_is_root = get_post_meta($post->ID, 'nakama_service_param_lg_g_is_root', true);
		$nakama_service_param_service_info = get_post_meta($post->ID, 'nakama_service_param_service_info', true);
		$nakama_service_param_contact_mail = get_post_meta($post->ID, 'nakama_service_param_contact_mail', true);
		$nakama_service_param_list_type = get_post_meta($post->ID, 'nakama_service_param_list_type', true);
		$nakama_service_param_lg_g_id = get_post_meta($post->ID, 'nakama_service_param_lg_g_id', true);
		$nakama_service_param_lg_write = get_post_meta($post->ID, 'nakama_service_param_lg_write', true);

		require_once(PLUGIN_SERVICE_PATH_SETTING . 'admin/settings/regist.php');
}

//保存時
add_action('save_post_setting_service', 'update_api_setting_service_meta');
function update_api_setting_service_meta ($post_id) {
		if (get_post_type() == 'setting_service') {
			if(isset($_POST['service_meta_box_type']))
				update_post_meta($post_id, 'service_meta_box_type',$_POST['service_meta_box_type']);
			if(isset($_POST['nakama_service_param_tg_id']))
				update_post_meta($post_id, 'nakama_service_param_tg_id',$_POST['nakama_service_param_tg_id']);
			if(isset($_POST['nakama_service_param_m_bbsid']))
				update_post_meta($post_id, 'nakama_service_param_m_bbsid',$_POST['nakama_service_param_m_bbsid']);
			if(isset($_POST['nakama_service_per_page']))
				update_post_meta($post_id, 'nakama_service_per_page',$_POST['nakama_service_per_page']);
			if(isset($_POST['nakama_service_param_url']))
				update_post_meta($post_id, 'nakama_service_param_url',$_POST['nakama_service_param_url']);

			//サービス情報
			if(isset($_POST['nakama_service_param_service_info']))
				update_post_meta($post_id, 'nakama_service_param_service_info',$_POST['nakama_service_param_service_info']);
			//問い合わせ先メールアドレス
			if(isset($_POST['nakama_service_param_contact_mail']))
				update_post_meta($post_id, 'nakama_service_param_contact_mail',$_POST['nakama_service_param_contact_mail']);
			//一覧種類
			if(isset($_POST['nakama_service_param_list_type']))
				update_post_meta($post_id, 'nakama_service_param_list_type',$_POST['nakama_service_param_list_type']);
			if(isset($_POST['nakama_service_param_lg_g_id']))
				update_post_meta($post_id, 'set_lg_g_id',$_POST['nakama_service_param_lg_g_id']);
			if(isset($_POST['nakama_service_param_lg_g_is_root']))
				update_post_meta($post_id, 'nakama_service_param_lg_g_is_root',$_POST['nakama_service_param_lg_g_is_root']);
			if(isset($_POST['nakama_service_param_lg_write']))
				update_post_meta($post_id, 'lg_login',$_POST['nakama_service_param_lg_write']);
			update_post_meta($post_id, 'pattern_no_post_type', isset($_POST['pattern_no_post_type'])?$_POST['pattern_no_post_type']:"");
			//API連携設定情報
			update_post_meta($post_id, 'service_meta_group_id', isset($_POST['service_meta_group_id'])?$_POST['service_meta_group_id']:"");
			update_post_meta($post_id, 'service_meta_p_id', isset($_POST['service_meta_p_id'])?$_POST['service_meta_p_id']:"");
			update_post_meta($post_id, 'service_meta_api_key', isset($_POST['service_meta_api_key'])?$_POST['service_meta_api_key']:"");
		}
}

function setting_service_admin_style() {
	wp_enqueue_style('cpt-service-styles', plugin_dir_url( __FILE__ ).'admin/assets/css/style.css');
}
add_action('admin_enqueue_scripts', 'setting_service_admin_style');

/* SHORT CODE COLUMN */
add_filter('manage_setting_service_posts_columns', 'add_setting_service_shortcode_column');
function add_setting_service_shortcode_column($cols)
{
		unset($cols['date']);
		$cols['tg_id'] = '団体ID';
		$cols['shortcode'] = 'ショートコード';
		$cols['date'] = 'Date';
		return $cols;
}

add_action('manage_setting_service_posts_custom_column', 'display_setting_service_shortcode_column', 10, 2);
function display_setting_service_shortcode_column( $column, $post_id)
{
		$tg_id = get_post_meta( $post_id, "nakama_service_param_tg_id",true );
		switch ( $column ) {
				case 'shortcode' :
				echo '<div class="input-group">
								<input class="shortcode-input form-control" data-id="'.$post_id.'" id="shortcode-<?php echo $post_id ?>" value="[service-setting id='.$post_id.']" readonly>
								<span class="input-group-addon copy-button"><i class="glyphicon glyphicon-copy"></i></span>
						</div>';
						break;

				case 'tg_id' :
						echo $tg_id;
		}
}
/* END SHORTCODE COLUMN */
?>
