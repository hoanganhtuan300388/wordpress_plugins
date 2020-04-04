<?php
define('__ROOT__', dirname(dirname(dirname(__FILE__))));
require_once(__ROOT__ . '/config/constant.php');
require_once(__ROOT__ . '/controller/serviceController.php');

$service = new serviceController();
$post = get_post();

$post_meta = $dataSetting;
foreach ($post_meta as $key => $value) {
    if (is_null($post_meta[$key])) {
        $post_meta[$key] = "";
    }
}

//$tg_id = !empty(get_post_meta($post_id, 'service_meta_group_id', true)) ? get_post_meta($post_id, 'service_meta_group_id', true) : get_option('nakama-service-group-id');
//$post_id = isset($_REQUEST['post_id']) ? $_REQUEST['post_id'] : "";
// $tg_id = "nakama2";	//団体ID
// $tg_id = isset($_REQUEST['top_g_id']) ? $_REQUEST['top_g_id'] : "";
//$dataSetting = get_post_meta($post_id);

$arrBody = array();

//カテゴリ（サービス情報名）のプルダウンデータを取得
$params = array();
$params["TG_ID"] = $post_meta['nakama_service_param_tg_id'];
$category_list = $service->getServiceNameList($post->ID, $params);

$arrBody['TG_ID'] = (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->TG_ID : $dataSetting['nakama_service_param_tg_id'][0];
$tg_id = $arrBody["TG_ID"];

//サービス一覧情報取得
$service_list = $service->getServiceList($post_id, $arrBody);

//サービス詳細画面表示データを取得
$service_category_list = array("セミナー・イベント情報", "製品・リリース情報", "ビジネス提携情報");
$service_category_list = $service->getServiceNameList($post_id, $tg_id);

$keyword = isset($_POST['search_select']) ? $_POST['search_select'] : "";
$columnSort = isset($_POST['sort']) ? $_POST['sort'] : "";
$orderBy = isset($_POST['order']) ? $_POST['order'] : "";
$current_page = (get_query_var('page') == 0) ? 1 : get_query_var('page');
$page_no = $current_page - 1;
$per_page = !empty(get_post_meta($post_id, 'nakama-service-general-per-page', true)) ? get_post_meta($post_id, 'nakama-service-general-per-page', true) : get_option('nakama-service-general-per-page');
$word_back_color = get_post_meta($post_id, 'word_back_color', true);
$pagination = $service->paginates($service_list, $per_page, $current_page);
$tyle_list = get_post_meta($post_id, 'nakama_service_param_list_type', true);
$type_svc = get_post_meta($post_id, 'nakama_service_param_service_info', true);
if($_REQUEST['svc_id']){
    $arrBody['SVC_ID'] =$_REQUEST['svc_id'];
    $MService = $service->getMService($post_id, $arrBody)->data;
    $type_svc = $MService[0]->SVC_NM;

}
$post_date = [];
$post_start_time = [];
$post_end_time = [];
foreach ($service_list->data as $key => $value) {
    $post_date[] = substr($value->POST_START_DATE, 0, 4);
    $post_start_time[$key] = ($value->POST_START_TIME) . ':00';
    if (strlen($value->POST_START_TIME) < 5) {
        $post_start_time[$key] = ($value->POST_START_TIME) . ':00:00';
    }
    $post_end_time[$key] = ($value->POST_END_TIME) . ':00';
    if (strlen($value->POST_END_TIME) < 5) {
        $post_END_time[$key] = ($value->POST_END_TIME) . ':00:00';
    }
}

$post_date = array_unique($post_date, 1);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
    <title><?php echo get_the_title($args['id']); ?></title>
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/css/base.css">
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/css/req_service.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/css/h_menu.css">
    <script type="text/javascript"
            src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/js/common.js"></script>
    <script type="text/javascript"
            src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/js/list/list.js"></script>
    <style type="text/css">
        <!--
        @media print {
            .PrintDispNone {
                display: none;
            }
        }

        }

        a {
            word-break: normal;
            overflow-wrap: break-word;
            -ms-word-wrap: break-word;
            word-wrap: break-word;
        }

        a:link {
            color: #333333;
            text-decoration: none;
        }

        a:visited {
            color: #333333;
            text-decoration: none;
        }

        a:hover {
            color: #0068B7;
            text-decoration: underline;
        }

        a:active {
            background-color: transparent;
        }

        .table tbody tr td, .table tbody tr th, .table td, .table th, .table thead tr td, .table thead tr th, table tbody tr td, table tbody tr th, table td, table th, table thead tr td, table thead tr th {
            border-bottom: 1px solid rgba(0,0,0,.05);
        }


        -->
    </style>
    <?php
    if ($tyle_list == 2) {
        ?>
        <script>
            window.location = "<?php echo serviceCrSet::getPageSlug('nakama-request-service-list') . "&top_g_id=" . $tg_id . "&post_id=" . $post_id . "&page_no=" . $page_no; ?>"
        </script>
        <?php
    }
    ?>
</head>

<body>
<table class="h_width service_page" width="600" align="center" cellspacing="0" cellpadding="0" border="0"
       style="border:none">
    <td align="center" valign="top">
        <div id="body_div">
            <form name="mainForm" method="post">
                <script type="text/JavaScript">
                    <!--
                    function OnMouseOver(buttonObj) {
                        buttonObj.style.backgroundColor = "#abcdef";
                        buttonObj.style.color = "blue";
                    }

                    function OnMouseOut(buttonObj) {
                        buttonObj.style.backgroundColor = "buttonface";
                        buttonObj.style.color = "buttontext";
                    }

                    //-->
                </script>
                <div style="clear:both;"></div>
                <!--    <td align="left">&nbsp;会員PR支援サービス</td>-->
                <div class="seminarList">
                    <?php if ($service_list->Count > 0) { ?>
                        <?php
                        date_default_timezone_set("Asia/Tokyo");
                        $today = date("Y-m-d H:i:s");; ?>
                        <?php foreach ($post_date as $key => $value) { ?>
                            <?php
                            $count_data = 0;
                            foreach ($service_list->data as $res_service) {
                                ?>
                                <?php if (($res_service->SVC_NM == $type_svc) || $type_svc == null) { ?>
                                    <?php if (strtotime($today) <= strtotime($res_service->POST_END_DATE . $post_end_time[$key]) && strtotime($today) >= strtotime($res_service->POST_START_DATE . $post_start_time[$key])) { ?>
                                        <?php if ((strtotime($value . '-12-31') > strtotime($res_service->POST_START_DATE)) && (strtotime($res_service->POST_START_DATE) > strtotime($value . '-01-01'))) { ?>
                                            <?php $count_data++; ?>
                                        <?php } ?>
                                    <?php }
                                }
                            } ?>
                            <?php if ($count_data > 0) { ?>
                                <h3 class="yearTitle"><?= $value ?>年</h3>
                                <table border="0" cellspacing="0" cellpadding="0" width="95%" class="table">
                                <tr>
                                    <td class="dayTitle" width="20%">掲載日</td>
                                    <td class="nameTitle" width="80%">タイトル</td>
                                </tr>
                                <?php foreach ($service_list->data as $key => $res_service) { ?>
                                    <?php if (($res_service->SVC_NM == $type_svc) || $type_svc == null) { ?>

                                        <?php if (strtotime($today) <= strtotime($res_service->POST_END_DATE . $post_end_time[$key]) && strtotime($today) >= strtotime($res_service->POST_START_DATE . $post_start_time[$key])) { ?>
                                            <?php if ((strtotime($value . '-12-31') > strtotime($res_service->POST_START_DATE)) && (strtotime($res_service->POST_START_DATE) > strtotime($value . '-01-01'))) { ?>
                                                <?php if (isset($res_service->POST_TITLE)) { ?>
                                                    <tr>
                                                        <td class="dayList"><?= convert_date($res_service->POST_START_DATE) ?></td>
                                                        <td class="seminarNameList">
                                                            <a href="javascript:getDetailService('<?php echo get_permalink(get_page_by_path('nakama-service-detail')->ID) . getAliasService(); ?>&top_g_id=<?php echo $tg_id ?>&post_id=<?php echo $post_id; ?>&svc_id=<?php echo $res_service->SVC_ID ?>&svc_info_no=<?php echo $res_service->SVC_INFO_NO ?>&back_page=<?php echo $post->ID ?>')">
                                                                <?= $res_service->POST_TITLE ?></a>
                                                            <br><?= $res_service->POST_G_NAME ?>
                                                            <?php if ($tyle_list != 1) { ?>
                                                                <div style="width:100%;margin-bottom:2px;text-align:right;font-size:10pt;font-weight:bold;color:#999999;"><?= $res_service->TG_NAME ?></div><?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } ?>
                                        <?php }
                                    } ?>
                                <?php }
                            } ?>
                            </table>
                        <?php } ?>
                    <?php } ?>
                </div>
                <br>
                <table style="width:250px" align="center" cellpadding="7" cellspacing="1" bgcolor="#CCCCCC" class="p8">
                    <tr>
                        <td style="background-color:#8a96b6;" class="p8">
                            <div align="center">
                                <!--                                --><?php //$page_link_login = serviceCrSet::getPageSlug('nakama-t'); ?>
                                <!--                                --><?php //$login_page_link = serviceCrSet::getPageSlug('nakama-login')."page_request=request_service_select&page_redirect=request_service_select&post_id=".$post_id; ?>
                                <!--                               --><?php ////$a = javascript::showRequestServiceSelect($login_page_link); ?>
                                <!--                                   <a class="LRLink" href="javascript:getDetailService('-->
                                <?php ////echo get_permalink(get_page_by_path('nakama-login')->ID) . getAliasService(); ?><!--')">-->
                                <!--                                <a class="LRLink" href="javascript:showRequestServiceSelect('-->
                                <?php //echo $login_page_link ?><!--')">-->
                                <!--                                <font color="#FFFFFF" size="+1">掲載申込はこちら</font></a></div>-->
                                <?php $page_link = serviceCrSet::getPageSlug('nakama-request-service-list'); ?>
                                <a class="LRLink"
                                   href="javascript:showRequestServiceList('<?= $post_id; ?>','<?= $tg_id; ?>','<?php echo $page_no; ?>','<?php echo $page_link; ?>');">
                                    <font color="#FFFFFF" size="+1">掲載申込はこちら<font></font></a>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="svcinfo_no" value="">
        </div>
    </td>
    </tr>
    <!--    <tr>-->
    <!--        <td class="" align="left" valign="top">-->
    <!--            <table class="f_width" cellspacing="0" cellpadding="0" border="0" style="float: none;" align="center">-->
    <!--                <tr>-->
    <!--                    <td class="f_width">-->
    <!--                        <hr size="1">-->
    <!--                    </td>-->
    <!--                </tr>-->
    <!--            </table>-->
    <!--            <table class="f_width" align="center" width="600" cellspacing="0" cellpadding="1" style="float: none;">-->
    <!--                <tr class="footer">-->
    <!--                    <td align="left"><FONT size=2>〒150-0013　東京都渋谷区恵比寿４－１２－１２　Tel:０３－５４８８－７０３０　Fax:０３－５４８８－７０６３</FONT>-->
    <!--                    </td>-->
    <!--                </tr>-->
    <!--            </table>-->
    <!--            <br>-->
    <!--        </td>-->
    <!--    </tr>-->
</table>

<input type="hidden" name="patten_cd" value="41">
<input type="hidden" name="page_no" value="137">
<input type="hidden" name="mail_flg" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="company_nm" value="">
<input type="hidden" name="cls" value="2">
<input type="hidden" name="cmd" value="">

<input type="hidden" name="search_mode" value="">

</form>

<form name="pageForm" method="get" action="index.asp">
    <input type="hidden" name="page" value="">
    <input type="hidden" name="disp_page" value="">
    <input type="hidden" name="patten_cd" value="41">
    <input type="hidden" name="page_no" value="137">
</form>

<form name="dispForm" method="get" action=""></form>
</body>
</html>
