<?php
define('__ROOT__', dirname(dirname(dirname(__FILE__))));
require_once(__ROOT__ . '/config/constant.php');
require_once(__ROOT__ . '/controller/serviceController.php');

$service = new serviceController();
$post = get_post();
$post_meta = $dataSetting;
foreach ($post_meta as $key => $value) {
	if(is_null($post_meta[$key])){
		$post_meta[$key] = "";
	}
}
$post_id = $post->ID;
$tg_id = $post_meta['nakama_service_param_tg_id'][0];	//団体ID
//$tg_id = isset($_REQUEST['top_g_id']) ? $_REQUEST['top_g_id'] : "";
//$dataSetting = get_post_meta($post_id);


//カテゴリ（サービス情報名）のプルダウンデータを取得
$params = array();
$params["TG_ID"] = $tg_id;
$service_category_list = $service->getServiceNameList($post_id, $params);

//ログインURL

$login_page_link = serviceCrSet::getPageSlug('nakama-login')."page_request=request_service_select&page_redirect=request_service_select&post_id=".$post_id;
$service_list_page_link = serviceCrSet::getPageSlug('nakama-service-list');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
	<title><?php echo get_the_title($args['id']); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname(dirname(__FILE__)) ); ?>assets/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/css/smart.css">
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/css/list.css">
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/css/h_menu.css">
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/css/f_menu.css">
	<script type="text/javascript" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/js/common.js"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/js/list/list.js"></script>
	<style type="text/css">
	<!--
	  @media print {
	    .PrintDispNone{
	      display:none;
	    }
	  }
	}
	-->
	</style>
</head>

<body>
	<h2>会員からのお知らせ</h2>

	<h2>掲載一覧</h2>
	<?php foreach ($service_category_list as $category) { ?>
		<a href="javascript:showServiceList('<?= $post_id; ?>','<?= $tg_id; ?>','<?= $category->key; ?>','<?= $service_list_page_link; ?>');"><?= $category->value ?></a>
	<?php } ?>
	<h2><a href="<?= $login_page_link ?>">掲載申し込み</a></h2>
	<?php $page_link = serviceCrSet::getPageSlug('nakama-request-service-list'); ?>
	<a class="LRLink" href="javascript:showRequestServiceList('<?= $post_id; ?>','<?= $tg_id; ?>','<?php echo $page_link; ?>');">申請中サービス一覧画面へ遷移</a>
</body>
</html>
