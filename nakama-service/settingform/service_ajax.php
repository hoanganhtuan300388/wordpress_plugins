<?php
add_action( 'wp_ajax_service_showElement', 'service_showElement' );
function service_showElement() {
	$type_setting = isset($_REQUEST['type_setting'])?$_REQUEST['type_setting']:'';
	switch ($type_setting) {
		case 'list_category':
			include(PLUGIN_SERVICE_PATH_SETTING . 'admin/settings/setting_list.php');
			die();
			break;
		default:
			break;
	}
}
add_action( 'wp_ajax_service_include_file', 'service_include_file' );
function service_include_file() {
	$tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'';
	$postid = isset($_REQUEST['postid'])?$_REQUEST['postid']:'';
	$input_tg_id = isset($_REQUEST['tg_id'])?$_REQUEST['tg_id']:'';
	switch ($tab) {
		case 'service-second':
			include(PLUGIN_SERVICE_PATH_SETTING . 'admin/settings/setting_category.php');
			die();
			break;
		case 'service-third':
			include(PLUGIN_SERVICE_PATH_SETTING . 'admin/settings/add_category.php');
			die();
			break;
		default:
			break;
	}
}
add_action( 'wp_ajax_service_get_theme_data_by_tgid', 'service_get_theme_data_by_tgid' );
function service_get_theme_data_by_tgid() {
	$tg_id = isset($_REQUEST['tg_id'])?$_REQUEST['tg_id']:'';
	$post_id = isset($_REQUEST['post_id'])?$_REQUEST['post_id']:'';
	$lg_type = (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->LG_TYPE : '0';
	$service = new serviceController();
	$getGroupTree = $service->getGroupTree($post_id, $tg_id, $lg_type);
	$rsLgid = '<option value="">--------</option>';
	if(!empty($getGroupTree->data)){
		foreach ($getGroupTree->data as $key => $item){
			$rsLgid .= '<option value="'.$item->LG_ID.'">'.$item->LG_NAME.'</option>';
		}
	}
	wp_send_json_success( $rsLgid);
	die();
}
add_action( 'wp_ajax_service_get_data_setting', 'service_get_data_setting' );
function service_get_data_setting() {
	$tg_id = isset($_REQUEST['tg_id'])?$_REQUEST['tg_id']:'';
	$dis_id = isset($_REQUEST['dis_id'])?$_REQUEST['dis_id']:'';
	$post_id = isset($_REQUEST['post_id'])?$_REQUEST['post_id']:'';
	$service = new serviceController();
	$GetSettingThemeService = $service->GetDetailDataSettingCategory($post_id, $tg_id, $dis_id);
	wp_send_json_success($GetSettingThemeService);
	die();
}
add_action( 'wp_ajax_service_upload_file', 'service_upload_file' );
add_action( 'wp_ajax_nopriv_service_upload_file', 'service_upload_file' );
function service_upload_file() {
	$post_id = isset( $_REQUEST['service_file_upload_post_id'] ) ? $_REQUEST['service_file_upload_post_id'] : '';
	$file_upload = isset( $_FILES['service_file_upload'] ) ? $_FILES['service_file_upload'] : '';
	$service = new serviceController();
	$uploadFileResponse = $service->uploadTopImage( $post_id, $file_upload );
	wp_send_json_success( $uploadFileResponse );
	die();
}
add_action( 'wp_ajax_service_rotation_file', 'service_rotation_file' );
add_action( 'wp_ajax_nopriv_service_rotation_file', 'service_rotation_file' );
function service_rotation_file() {
	$post_id = isset( $_REQUEST['post_id'] ) ? $_REQUEST['post_id'] : '';
	$file_rotation = isset( $_REQUEST['file_rotation'] ) ? $_REQUEST['file_rotation'] : '';
	$type = isset( $_REQUEST['type'] ) ? $_REQUEST['type'] : '';
	$file_dir = isset( $_REQUEST['file_dir'] ) ? $_REQUEST['file_dir'] : '';
	$is_not_resize = isset( $_REQUEST['is_not_resize'] ) ? $_REQUEST['is_not_resize'] : '';
	$service = new serviceController();
	$rotationFileResponse = $service->rotationTopImage( $post_id, $file_rotation, $type, $file_dir, $is_not_resize );
	wp_send_json_success( $rotationFileResponse );
	die();
}
add_action( 'wp_ajax_service_resize_file', 'service_resize_file' );
add_action( 'wp_ajax_nopriv_service_resize_file', 'service_resize_file' );
function service_resize_file() {
	$post_id = isset( $_REQUEST['post_id'] ) ? $_REQUEST['post_id'] : '';
	$width = isset( $_REQUEST['width'] ) ? $_REQUEST['width'] : '';
	$height = isset( $_REQUEST['height'] ) ? $_REQUEST['height'] : '';
	$file_resize = isset( $_REQUEST['file_resize'] ) ? $_REQUEST['file_resize'] : '';
    $file_dir = isset( $_REQUEST['file_dir'] ) ? $_REQUEST['file_dir'] : '';
    $is_not_resize = isset( $_REQUEST['is_not_resize'] ) ? $_REQUEST['is_not_resize'] : '';
	$service = new serviceController();
	$resizeFileResponse = $service->resizeTopImage( $post_id, $file_resize, $width, $height, $file_dir, $is_not_resize );
	wp_send_json_success( $resizeFileResponse );
	die();
}
add_action( 'wp_ajax_service_reset_file', 'service_reset_file' );
add_action( 'wp_ajax_nopriv_service_reset_file', 'service_reset_file' );
function service_reset_file() {
	$post_id = isset( $_REQUEST['post_id'] ) ? $_REQUEST['post_id'] : '';
	$file_reset = isset( $_REQUEST['file_reset'] ) ? $_REQUEST['file_reset'] : '';
    $file_dir = isset( $_REQUEST['file_dir'] ) ? $_REQUEST['file_dir'] : '';
	$service = new serviceController();
	$resetFileResponse = $service->resetTopImage( $post_id, $file_reset, $file_dir );
	wp_send_json_success( $resetFileResponse );
	die();
}
?>
