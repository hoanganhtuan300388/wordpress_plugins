<?php
define('__ROOT__', dirname(dirname(dirname(__FILE__))));
require_once(__ROOT__ . '/config/constant.php');
require_once(__ROOT__ . '/controller/researchController.php');
$researchs = new researchController();
$keyword = isset($_POST['search_select']) ? $_POST['search_select'] : "";
$columnSort = isset($_POST['sort']) ? $_POST['sort'] : "";
$orderBy = isset($_POST['order']) ? $_POST['order'] : "";
$current_page = (get_query_var('page') == 0) ? 1 : get_query_var('page');
$page_no = $current_page - 1;
$per_page = get_post_meta($postid, 'nak-research-per-page', true);
$word_back_color = get_post_meta($postid, 'word_back_color', true);
$now_year = date('Y');
$now_month = date('m');
$year_date = isset($_POST['year_date']) ? $_POST['year_date'] : $now_year;
$month_date = isset($_POST['month_date']) ? $_POST['month_date'] : $now_month;
$years = $researchs->getYearRange();
$top_type_visible = get_post_meta($postid, 'top_type_visible', true);
$top_type = isset($_POST['top_type'])?$_POST['top_type']:get_post_meta($postid, 'top_type', true);
$gTop_type = ($top_type == '')?1:$top_type;
if($gTop_type == 2){
    $top_sta = researchCrSet::research_get_Sta($postid, $tg_id);
    $top_sta = isset($_POST['top_sta'])?$_POST['top_sta']:$top_sta;
}else{
    $top_sta = "";
}
$listresearchs = $researchs->lists($postid,$tg_id,$top_sta,$PattenNo);
$pagination = $researchs->paginates($listresearchs,$per_page);
$header_list_research = $listresearchs->header;
$data_list_research = $listresearchs->body;
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
    <title><?php echo get_the_title($args['id']); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/css/smart.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/css/list.css?ver=1.3">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/css/h_menu.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/css/f_menu.css">
    <script type="text/javascript" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/js/common.js"></script>
    <script type="text/javascript" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/js/list/list.js"></script>
    <style>
        table.formloading, table.formloading td, table.formSearch, table.formSearch td {
            border: none!important;
        }
    </style>
</head>

<body>
<div class="container list_page">
    <form id="mainForm" name="mainForm" method="post">
        <table class="formloading" cellspacing="0" cellpadding="3">
            <tbody>
            <tr>
                <td>
                    <strong>
                        <font size="2">
                            <font color="#808080">■</font>　<?php echo get_the_title($postid); ?>
                        </font>
                    </strong>
                </td>
                <td align="center">
                    <div id="indidiv" style="display:none">
                        <img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/img/loading.gif" valign="middle">
                        <font size="2" color="#6699FF"><b>検索中です。しばらくお待ちください・・・</b>
                            <font>
                            </font>
                        </font>
                    </div>
                </td>
                <td align="right">
                    <font size="2"><b>※背景 <font color="#CCFF99">■</font> は会員のみの参加アンケートです。<?php echo $word_back_color; ?></b></font>
                </td>
            </tr>
            </tbody>
        </table>
        <ul class="list-form">
            <?php if($top_type_visible == "1" ): ?>
                <li class="top_type">
                    <label><input type="radio" name="top_type_radio" value="1" onClick="OnTopChange();" <?php echo ($gTop_type == 1)?"checked":""; ?>><b>当会</b></label>&nbsp;&nbsp;
                    <label><input type="radio" name="top_type_radio" value="2" onClick="OnTopChange();" <?php echo ($gTop_type == 2)?"checked":""; ?>><b>全国</b></label>
                    <input type="hidden" name="top_type" value="<?php echo $gTop_type; ?>">
                    &nbsp;&nbsp;
                    <?php if($gTop_type == "2" ): ?>
                        <?php echo researchCrSet::renderTopStation($top_sta); ?>
                    <?php endif; ?>
                </li>
            <?php else: ?>
                <input type="hidden" name="top_type" value="<?php echo $gTop_type; ?>">
            <?php endif; ?>
        </ul>
        <table width="100%" class="formSearch" cellspacing="0" cellpadding="3">
            <tbody>
            <tr>
                <td width="75%" align="right">
                    <select name="year_date" onchange="OnCommand();">
                        <option <?php if ($year_date == "") {
                            echo "selected";
                        } ?> value="">----</option>
                        <?php foreach ($years as $year) { ?>
                            <option <?php if ($year_date == $year) {
                                echo "selected";
                            } ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
                        <?php } ?>
                    </select>
                    年
                    <select name="month_date" onchange="OnCommand();">
                        <option <?php if ($month_date == "") {
                            echo "selected";
                        } ?> value="">--</option>
                        <?php
                        for ($month_start = 01; $month_start <= 12; $month_start++) { ?>
                            <option <?php if ($month_date == $month_start) {
                                echo "selected";
                            } ?> value="<?php echo $month_start ?>">
                                <?php echo ($month_start < 10) ? "0" . $month_start : $month_start ?>
                            </option>
                        <?php } ?>
                    </select>
                    月 &nbsp;&nbsp;
                    <span>キーワード <input type="text" class="inputSearch" name="search_select" value="<?php echo $keyword; ?>">
                <input type="button" class="btnSearch" value="　検索　" onclick="OnCommand();"></span>
                </td>
            </tr>
            </tbody>
        </table>
        <!-- ■■■ 一覧 ■■■ -->
        <b>
        </b>
        <div class="statics">
        <span class="left">【該当件数：<?php if ($listresearchs->count) echo $listresearchs->count;
            else echo 0; ?>件】</span>
            <ul class="right listOpe-right">
                <li class="nolink_startpage <?php if ($current_page == 1) echo 'none_event'; ?>">
                    <a href="Javascript:Pagination(1);"><span>先頭ページ</span> <img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>/assets/img/prevfirst.png" class="none-view"></a>
                </li>
                <li class="nolink_prevpage <?php if ($current_page == 1) echo 'none_event'; ?>">
                    <a href="Javascript:Pagination(<?php echo $current_page - 1; ?>);"><span>前ページ</span> <img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>/assets/img/prev.png" class="none-view"></a>
                </li>
                <li>
                    &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $pagination['current_page']; ?> / <?php echo $pagination['total_page']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
                </li>
                <li class="link_nextpage <?php if ($current_page == $pagination['total_page']) echo 'none_event'; ?>">
                    <a href="Javascript:Pagination(<?php echo $current_page + 1; ?>);"><span>次ページ</span> <img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>/assets/img/next.png" class="none-view"></a>
                </li>
                <li class="link_lastpage <?php if ($current_page == $pagination['total_page']) echo 'none_event'; ?>">
                    <a href="Javascript:Pagination(<?php echo $pagination['total_page']; ?>);"><span>最終ページ</span> <img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>/assets/img/nextend.png" class="none-view"></a>
                </li>
            </ul>
        </div>
        <div class="wrapper_table">
            <div class="table_list">
                <div class="tr">
                    <?php
                    $headers =array();
                    $headers[0][] = 'No';
                    $i=1;
                    foreach ($header_list_research as $key => $header){
                        $sort = '';
                        if (!empty($header->column_sort) && $header->column_sort == 'asc') {
                            $sort =  "▲".($header->column_sort_index + 1);
                        }elseif(!empty($header->column_sort) && $header->column_sort == 'desc'){
                            $sort =  "▼".($header->column_sort_index + 1);
                        }
                        if($header->column_sort != null){ $col_sort = $header->column_sort; }else{ $col_sort = ''; }
                        $html = '<a class="LHLink" id="lh_'.$key.'" href="javascript:OnSort(\''.$header->column_id.'\',\''.$col_sort.'\')">'.$header->column_name.$sort.'</a>';
                        $headers[$i][] = $html;
                        $i++;
                    }
                    if (!empty($data_list_research)) {
                        foreach ($data_list_research as $key => $data_researchs) {
                            $headers[0][] = ($page_no * get_option('nakama-member-list-per-page') + $key + 1);
                            $i=1;
                            foreach ($data_researchs->dispCol as $research) {
                                if ($research->column_id == 'RES_RESERCH_NAME'){
                                    $page_link = researchCrSet::getPageSlug('nakama-detail-research');
                                    $html_a = '<a class="LRLink" href="javascript:ShowResearchDetail(\''.$postid.'\',\''.$tg_id.'\', \''.$researchs->getKeyBasedOnName($data_researchs->nodispCol, 'NODISP_RESERCH_ID').'\',\''.$page_link.'\');">'.$research->column_data.'</a></td>';
                                } elseif ($research->column_format == 'System.Nullable`1[[System.DateTime, mscorlib, Version=4.0.0.0, Culture=neutral, PublicKeyToken=b77a5c561934e089]]'){
                                    if(!empty($research->column_data))
                                    {
                                        $dateTime = isset($research->column_data)?$researchs->convertDates($research->column_data, "Y", "年").$researchs->convertDates($research->column_data, "m", "月").$researchs->convertDates($research->column_data, "d", "日"):"";
                                        $html_a = $dateTime;
                                    }
                                }
                                //begin de tam khi co api list php se xoa
                                else if($research->column_id == 'RES_STATUS') {
                                    $research_id = $researchs->getKeyBasedOnName($data_researchs->nodispCol, 'NODISP_RESERCH_ID');
                                    $result = $researchs->getDetail($postid, $tg_id, $research_id);
                                    if (!empty($result)) {
                                        if ($research->column_data != '受付中断') {
                                            $start_date = $result->RES_START_DATE . ' ' . $result->RES_START_TIME;
                                            $end_date = $result->RES_END_DATE . ' ' . $result->RES_END_TIME;

                                            if (date("Y-m-d H:i:s", strtotime($start_date)) > date("Y-m-d H:i:s")) {
                                                $html_a = '受付前';
                                            } else {
                                                if (date("Y-m-d H:i:s", strtotime($end_date)) < date("Y-m-d H:i:s")) {
                                                    $html_a = '受付終了';
                                                } else {
                                                    $html_a = '受付中';
                                                }
                                            }
                                        } else {
                                            $html_a = '受付中断';
                                        }
                                    } else {
                                        $html_a = $research->column_data;
                                    }
                                }
                                //end de tam khi co api list php se xoa
                                else {
                                    $html_a = $research->column_data;
                                }
                                $headers[$i][] = $html_a;
                                $i++;
                            }
                        }
                    }
                    foreach ($headers as $items){
                        $i=0;
                        echo '<div class="td list-col-xs-12 ntd">';
                        foreach ($items as $item){
                            if($i==0){
                                echo '<div class="header_td">'.$item.'</div>';
                            }else{
                                echo '<div>'.$item.'&nbsp;</div>';
                            }
                            $i++;
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
            </div></div>
        <div class="statics">
            <ul class="right listOpe-right">
                <li class="nolink_startpage <?php if ($current_page == 1) echo 'none_event'; ?>">
                    <a href="Javascript:Pagination(1);"><span>先頭ページ</span> <img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>/assets/img/prevfirst.png" class="none-view"></a>
                </li>
                <li class="nolink_prevpage <?php if ($current_page == 1) echo 'none_event'; ?>">
                    <a href="Javascript:Pagination(<?php echo $current_page - 1; ?>);"><span>前ページ</span> <img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>/assets/img/prev.png" class="none-view"></a>
                </li>
                <li>
                    &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $pagination['current_page']; ?> / <?php echo $pagination['total_page']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
                </li>
                <li class="link_nextpage <?php if ($current_page == $pagination['total_page']) echo 'none_event'; ?>">
                    <a href="Javascript:Pagination(<?php echo $current_page + 1; ?>);"><span>次ページ</span> <img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>/assets/img/next.png" class="none-view"></a>
                </li>
                <li class="link_lastpage <?php if ($current_page == $pagination['total_page']) echo 'none_event'; ?>">
                    <a href="Javascript:Pagination(<?php echo $pagination['total_page']; ?>);"><span>最終ページ</span> <img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>/assets/img/nextend.png" class="none-view"></a>
                </li>
            </ul>
        </div>
        <br>
        <input type="hidden" name="sort" value="<?php echo $columnSort; ?>">
        <input type="hidden" name="order" value="<?php echo $orderBy; ?>">
        <input type="hidden" name="page" value="1">
        <input type="hidden" name="cmd" value="">
        <input type="hidden" name="max" value="1">
        <input type="hidden" name="top_g_id" value="dmshibuya">
        <input type="hidden" name="other_top_g_id" value="dmtachikawa,dmhino,dmhmurayama,hsdk0000,hkyg0000000000,naka,ryokanlist,dmmeguro,hjodawara">
        <input type="hidden" name="page_no" value="19">
        <input type="hidden" name="search_mode" value="">
        <input type="hidden" name="top_type_visible" value="0">
        <input type="hidden" name="lower_g_id" value="">
    </form>
</div>
</body>

</html>