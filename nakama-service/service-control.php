<?php
/*
 * Plugin Name: NAKAMA Service
 * Description: なかま２会員PRサービスプラグイン
 * Author: DynaX
 */
require('common.php');
require('setting/setting.php');
require_once('config/constant.php');
require('controller/serviceController.php');
require('settingform/index.php');

//管理画面設定
/*
* 管理画面メニュー追加
*/
add_action("admin_menu", "add_service_management_plugin_menu_item");
function add_service_management_plugin_menu_item(){
	add_menu_page("Setting API", "会員PRサービス", "manage_options", "service-management-setting", "service_management_setting", plugin_dir_url( __FILE__ ) . 'assets/img/group.png', 99);

	add_submenu_page('service-management-setting','API設定', 'API設定','manage_options','service-management-setting');

	add_submenu_page('service-management-setting','フォーム設定','フォーム設定','manage_options','edit.php?post_type=setting_service', false);

	add_action( 'admin_init', 'register_nakama_service_plugin_settings' );
}
/**
* 管理画面生成？
*/
function register_nakama_service_plugin_settings() {
	register_setting( 'nakama-service-plugin-settings-group', 'nakama-service-group-id' );
	register_setting( 'nakama-service-plugin-settings-group', 'nakama-service-personal-id' );
	register_setting( 'nakama-service-plugin-settings-group', 'nakama-service-api-key' );
    register_setting( 'nakama-service-plugin-settings-group', 'nakama-service-general-per-page' );
}
/**
* CSS,Scriptの設定
*/
function service_admin_style() {
	wp_enqueue_style('admin-service-styles', plugin_dir_url( __FILE__ ).'assets/css/admin.css');
	wp_enqueue_script('admin-service-js',plugins_url('assets/js/admin_service.js',__FILE__),'','',true);
	wp_enqueue_script('admin-shortcode-service-js',plugins_url('settingform/admin/assets/js/admin_service.js',__FILE__),'','',true);
	wp_enqueue_script('admin-thickbox-js',plugins_url('assets/js/thickbox.js',__FILE__),'','',true);
}

//掲載一覧表示
$service_list = service_create_new_page('nakama_setting_Service List', 'nakama-service-list');
add_filter( 'page_template', 'service_list_page_template' );
function service_list_page_template( $page_template )
{
	if ( is_page( 'nakama-service-list' ) ) {
			$page_template = dirname( __FILE__ ) . '/templates/service_list.php';
	}
	return $page_template;
}

//ログイン画面遷移

//申請中サービス一覧
$request_service_list = service_create_new_page('nakama_setting_Request Service List', 'nakama-request-service-list');
add_filter( 'page_template', 'request_service_list_page_template' );
function request_service_list_page_template( $page_template )
{
	if ( is_page( 'nakama-request-service-list' ) ) {
		$page_template = dirname( __FILE__ ) . '/templates/request_service_list.php';
	}
	return $page_template;
}

//申請サービス選択
$request_service_select = service_create_new_page('nakama_setting_Request Service Select', 'nakama-request-service-select');
$request_service_input_dateinput = service_create_new_page('nakama_setting_Request Service InputDate', 'nakama-request-service-dateinput');
add_filter( 'page_template', 'request_service_dateinput_page_template' );
function request_service_dateinput_page_template( $page_template )
{
    if ( is_page( 'nakama-request-service-dateinput' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/service_dateinput_help.php';
    }
    return $page_template;
}

add_filter( 'page_template', 'request_service_select_page_template' );
function request_service_select_page_template( $page_template )
{
	if ( is_page( 'nakama-request-service-select' ) ) {
		$page_template = dirname( __FILE__ ) . '/templates/request_service_select.php';
	}
	return $page_template;
}

// //申請内容入力
$request_service_input = service_create_new_page('nakama_setting_Request Service Input', 'nakama-request-service-input');
add_filter( 'page_template', 'request_service_input_page_template' );
function request_service_input_page_template( $page_template )
{
	if ( is_page( 'nakama-request-service-input' ) ) {
		$page_template = dirname( __FILE__ ) . '/templates/request_service_input.php';
	}
	return $page_template;
}
//入力内容確認
$request_service_confirm = service_create_new_page('nakama_setting_Request Service Confirm', 'nakama-request-service-confirm');
add_filter( 'page_template', 'request_service_confirm_page_template' );
function request_service_confirm_page_template( $page_template )
{
	 if ( is_page( 'nakama-request-service-confirm' ) ) {
		 $page_template = dirname( __FILE__ ) . '/templates/request_service_confirm.php';
	 }
	 return $page_template;
}

//申請完了
$request_service_complete = service_create_new_page('nakama_setting_Request Service Complete', 'nakama-request-service-complete');
add_filter( 'page_template', 'request_service_complete_page_template' );
function request_service_complete_page_template( $page_template )
{
	 if ( is_page( 'nakama-request-service-complete' ) ) {
		 $page_template = dirname( __FILE__ ) . '/templates/request_service_complete.php';
	 }
	 return $page_template;
}

//ファイルアップロード

//画像登録
$request_service_item_img = service_create_new_page('nakama_setting_Request Service Item Img', 'nakama-request-service-item-img');
add_filter( 'page_template', 'request_service_item_img_page_template' );
function request_service_item_img_page_template( $page_template )
{
	if ( is_page( 'nakama-request-service-item-img' ) ) {
		$page_template = dirname( __FILE__ ) . '/templates/request_service_item_img.php';
	}
	return $page_template;
}

$request_service_edit_text = service_create_new_page('nakama_setting_Request Service Edit Text', 'nakama-request-service-text-edit');
add_filter( 'page_template', 'request_service_text_edit_page_template' );
function request_service_text_edit_page_template( $page_template )
{
    if ( is_page( 'nakama-request-service-text-edit' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/service_text_edit.php';
    }
    return $page_template;
}

//HIDE PAGE IN MENU
function myscript_service() {
?>
	<script type="text/javascript">
	(function($) {
        var arrHidden = [
            'nakama_setting_Service List',
            'nakama_setting_Request Service List',
            'nakama_setting_Request Service InputDate',
            'nakama_setting_Request Service Complete',
            'nakama_setting_Request Service Confirm',
            'nakama_setting_Request Service Edit Text',
            'nakama_setting_Request Service Item Img',
            'nakama_setting_Request Service Select',
            'nakama_setting_Request Service Detail',
            'nakama_setting_Request Service Input',
            'nakama_setting_Service Member detail',
            'nakama_setting_Service_Category_List',
            'nakama_setting_Service Detail Category'
        ];
		$("#pagechecklist-most-recent").css('display', 'none');
        hideItem($("#pagechecklist-most-recent li"), arrHidden);

        $("#pagechecklist-most-recent").css('display', 'block');
        hideItem($("#pagechecklist li label"), arrHidden);

        $( '#nav-menu-meta' ).on( 'click', 'a.page-numbers', function() {
            setTimeout(function() {
                hideItem($("#pagechecklist-most-recent li"), arrHidden);
                hideItem($("#pagechecklist li label"), arrHidden);
            }, 1500);
        });

		$("#page-service-checklist").css('display', 'none');
		$("#quick-service-posttype-page").change(function(){
			$("#page-service-checklist").trigger("updatecomplete");
		});
		$("#page-service-checklist").bind("updatecomplete", function() {
            hideItem($("#page-service-checklist li"), arrHidden);
			$("#page-service-checklist").css('display', 'block');
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
		if(post_type == "setting_service"){
			$("#toplevel_page_service-management-plugin").addClass("wp-has-current-submenu");
			$("#toplevel_page_service-management-plugin > a").addClass("wp-has-current-submenu");
		}
	})(jQuery);

	function hideItem(obj, arrHidden) {
        obj.each(function(index){
            var parent = jQuery(this);
            var text = parent.text().toLowerCase();
            arrHidden.forEach(function(e){
                if(text.indexOf(e.toLowerCase()) != -1){
                    parent.remove();
                }
            });
        });
    }
</script>
<?php
}
add_action( 'admin_footer', 'myscript_service' );

add_action('admin_enqueue_scripts', 'service_admin_style');
/*function custom_admin_js() {
		$url = plugin_dir_url(__FILE__) .'assets/img/loadingAnimation.gif';
		echo "<script> var tb_pathToImage = '".$url."';</script>";
}
add_action('admin_footer', 'custom_admin_js');
*/
//Category List
$category_list = service_create_new_page('nakama_setting_Service_Category_List', 'nakama-service-list-category');
add_filter( 'page_template', 'service_category_list_page_template' );
function service_category_list_page_template( $page_template )
{
		if ( is_page( 'nakama-service-list-category' ) ) {
				$page_template = dirname( __FILE__ ) . '/templates/list_category.php';
		}
		return $page_template;
}
//Detail Category
$category_detail = service_create_new_page('nakama_setting_Service Detail Category', 'nakama-service-detail-category');
add_filter( 'page_template', 'service_category_detail_page_template' );
function service_category_detail_page_template( $page_template )
{
		if ( is_page( 'nakama-service-detail-category' ) ) {
				$page_template = dirname( __FILE__ ) . '/templates/detail_category.php';
		}
		return $page_template;
}
//List Article
//$list_article = service_create_new_page('nakama_setting_Service List Article', 'nakama-service-list-forum');
//add_filter( 'page_template', 'service_list_article_page_template' );
//function service_list_article_page_template( $page_template )
//{
//		if ( is_page( 'nakama-service-list-forum' ) ) {
//				$page_template = dirname( __FILE__ ) . '/templates/list_article.php';
//		}
//		return $page_template;
//}

//Detail service
$service_detail = service_create_new_page('nakama_setting_Service Member detail', 'nakama-service-detail');
add_filter( 'page_template', 'service_detail_page_template' );
function service_detail_page_template( $page_template )
{
		if ( is_page( 'nakama-service-detail' ) ) {
				$page_template = dirname( __FILE__ ) . '/templates/service_detail.php';
		}
		return $page_template;
}
//Detail member img view
//$service_imgview = service_create_new_page('nakama_setting_Service Member Img View', 'nakama-service-img-view');
//add_filter( 'page_template', 'service_member_article_imgview_page_template' );
//function service_member_article_imgview_page_template( $page_template )
//{
//		if ( is_page( 'nakama-service-img-view' ) ) {
//				$page_template = dirname( __FILE__ ) . '/templates/img_view.php';
//		}
//		return $page_template;
//}

//Admin upload file
//$admin_upload_file = service_create_new_page('nakama_setting_Admin upload file', 'nakama-service-admin-upload-file');
//add_filter( 'page_template', 'service_admin_upload_file_page_template' );
//function service_admin_upload_file_page_template( $page_template )
//{
//		if ( is_page( 'nakama-service-admin-upload-file' ) ) {
//				$page_template = dirname( __FILE__ ) . '/templates/admin_upload_file.php';
//		}
//		return $page_template;
//}

//Post Article Page
//$post_article = service_create_new_page('nakama_setting_Service Post Article', 'nakama-service-thread-input');
//add_filter( 'page_template', 'service_post_article_page_template' );
//function service_post_article_page_template( $page_template )
//{
//		if ( is_page( 'nakama-service-thread-input' ) ) {
//				$page_template = dirname( __FILE__ ) . '/templates/post_article.php';
//		}
//		return $page_template;
//}

//Detail Article Page
//$detail_article = service_create_new_page('nakama_setting_Service Detail Article', 'nakama-service-thread');
//add_filter( 'page_template', 'service_detail_article_page_template' );
//function service_detail_article_page_template( $page_template )
//{
//		if ( is_page( 'nakama-service-thread' ) ) {
//				$page_template = dirname( __FILE__ ) . '/templates/detail_article.php';
//		}
//		return $page_template;
//}
//Input password Page
//$input_password = service_create_new_page('nakama_setting_Input password Page', 'nakama-service-input-password');
//add_filter( 'page_template', 'nakama_service_input_password_page_template' );
//function nakama_service_input_password_page_template( $page_template )
//{
//		if ( is_page( 'nakama-service-input-password' ) ) {
//				$page_template = dirname( __FILE__ ) . '/templates/InputPassword.php';
//		}
//		return $page_template;
//}

//Post Comment Page
//$post_comment = service_create_new_page('nakama_setting_Service Post Comment', 'nakama-service-res-input');
//add_filter( 'page_template', 'service_post_comment_page_template' );
//function service_post_comment_page_template( $page_template )
//{
//		if ( is_page( 'nakama-service-res-input' ) ) {
//				$page_template = dirname( __FILE__ ) . '/templates/post_comment.php';
//		}
//		return $page_template;
//}

//Post Comment Confirm Page
//$post_comment_confirm = service_create_new_page('nakama_setting_Service Confirm Post Comment', 'nakama-service-res-confirm');
//add_filter( 'page_template', 'service_post_comment_confirm_page_template' );
//function service_post_comment_confirm_page_template( $page_template )
//{
//		if ( is_page( 'nakama-service-res-confirm' ) ) {
//				$page_template = dirname( __FILE__ ) . '/templates/post_comment_confirm.php';
//		}
//		return $page_template;
//}

//Front upload file
//$upload_file = service_create_new_page('nakama_setting_Front upload file', 'nakama-service-upload-file');
//add_filter( 'page_template', 'service_upload_file_page_template' );
//function service_upload_file_page_template( $page_template )
//{
//		if ( is_page( 'nakama-service-upload-file' ) ) {
//				$page_template = dirname( __FILE__ ) . '/templates/upload_file.php';
//		}
//		return $page_template;
//}

//Confirm Article Page
//$confirm_post_article = service_create_new_page('nakama_setting_Service Confirm Post Article', 'nakama-service-thread-confirm');
//add_filter( 'page_template', 'service_confirm_post_article_page_template' );
//function service_confirm_post_article_page_template( $page_template )
//{
//		if ( is_page( 'nakama-service-thread-confirm' ) ) {
//				$page_template = dirname( __FILE__ ) . '/templates/post_article_confirm.php';
//		}
//		return $page_template;
//}

//Delete Article/Message Page
//$funcDelete = service_create_new_page('nakama_setting_Delete Article/Message Page', 'nakama-service-func-delete');
//add_filter( 'page_template', 'service_funcDelete_page_template' );
//function service_funcDelete_page_template( $page_template )
//{
//		if ( is_page( 'nakama-service-func-delete' ) ) {
//				$page_template = dirname( __FILE__ ) . '/templates/funcDelete.php';
//		}
//		return $page_template;
//}
//page popup add category
//$AddCategory = service_create_new_page('nakama_setting_addCategory_page', 'nakama-service-add-category');
//add_filter( 'page_template', 'service_addcategory_page_template' );
//function service_addcategory_page_template( $page_template )
//{
//		if ( is_page( 'nakama-service-add-category' ) ) {
//				$page_template = dirname( __FILE__ ) . '/templates/add_category.php';
//		}
//		return $page_template;
//}

/*function deactivation_plugin_service() {
	wp_delete_post(get_page_by_path('nakama-service-list')->ID,true);
	/*wp_delete_post(get_page_by_path('nakama-service-admin-upload-file')->ID,true);
	wp_delete_post(get_page_by_path('nakama-service-thread-input')->ID,true);
	wp_delete_post(get_page_by_path('nakama-service-thread')->ID,true);
	wp_delete_post(get_page_by_path('nakama-service-res-input')->ID,true);
	wp_delete_post(get_page_by_path('nakama-service-upload-file')->ID,true);
	wp_delete_post(get_page_by_path('nakama-service-thread-confirm')->ID,true);
	wp_delete_post(get_page_by_path('nakama-service-res-confirm')->ID,true);
	wp_delete_post(get_page_by_path('nakama-service-func-delete')->ID,true);
	wp_delete_post(get_page_by_path('nakama-service-add-category')->ID,true);
	wp_delete_post(get_page_by_path('nakama-service-input-password')->ID,true);
	wp_delete_post(get_page_by_path('nakama-service-img-view')->ID,true);
	wp_delete_post(get_page_by_path('nakama-service-detail-member')->ID,true);*/
/*}
register_deactivation_hook( __FILE__, 'deactivation_plugin_service' );

function tasks_admin_sessions() {
		if(!session_id()) {
				header('P3P:CP="NOI NID"');
				session_start();
		}
}
add_action( 'admin_init', 'tasks_admin_sessions', 1 );

/*function load_service_setting_template($template) {
		global $post;
		if ($post->post_type == "service_setting"){
				return plugin_dir_path( __FILE__ ) . "templates/single-setting-service.php";
		}
		return $template;
}
add_filter('single_template', 'load_service_setting_template');*/

/*function myscript_service() {
}*/
//add_action( 'admin_footer', 'myscript_service' );

/*function service_exclude_pages_from_menu ($items, $args) {
	$arrSlug = [
		'nakama-service-list'
	];
	$arrExistSlug = array();
	foreach ($arrSlug as $key => $item) {
		$get_page = get_page_by_path($item)->ID;
		array_push($arrExistSlug, $get_page);
	}
	foreach ($items as $ix => $obj) {
		if (in_array($obj->object_id, $arrExistSlug)) {
				unset ($items[$ix]);
		}
	}
	return $items;
}
add_filter ('wp_nav_menu_objects', 'service_exclude_pages_from_menu', 10, 2);

add_action( 'wp_head', 'serviceCustomJs');
function serviceCustomJs(){ ?>
	<script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)) ?>nakama-service/assets/js/front_common.js"></script>
	<?php
}
*/
//HIDE PAGE IN ADMIN
$arrSlug = get_pages(array('post_status' => 'publish'));
$arrExistSlug = array();
foreach ($arrSlug as $key => $item) {
	if (strpos($item->post_title, 'nakama_setting_') !== false) {
		$get_page = get_page_by_path($item->post_name)->ID;
		array_push($arrExistSlug, $get_page);
	}
}
add_filter( 'parse_query', 'hidden_pages_from_admin_service' );
function hidden_pages_from_admin_service($query) {
		global $pagenow, $post_type;
		GLOBAL $arrExistSlug;
		if (is_admin() && $pagenow=='edit.php' && $post_type =='page') {
				$query->query_vars['post__not_in'] = $arrExistSlug;
		}
}

add_filter( 'wp_link_query_args', 'service_custom_link_query' );
function service_custom_link_query( $query ){
	GLOBAL $arrExistSlug;
	$query['post__not_in'] = $arrExistSlug;
	return $query;
}

add_filter( 'page_attributes_dropdown_pages_args', 'service_hide_attr_page_parents' );
add_filter( 'quick_edit_dropdown_pages_args', 'service_hide_attr_page_parents' );
function service_hide_attr_page_parents( $args )
{
	GLOBAL $arrExistSlug;
	$args['exclude_tree'] = $arrExistSlug;
	return $args;
}
