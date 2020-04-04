<?php
$service = new serviceController();
$tg_id = isset($_GET['top_g_id']) ? $_GET['top_g_id'] : "";
$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : "";
$svc_id = (int)isset($_GET['svc_id']) ? $_GET['svc_id'] : 0;
$svc_info_no = isset($_GET['svc_info_no']) ? $_GET['svc_info_no'] : "";
$back_page = isset($_GET['back_page']) ? $_GET['back_page'] : '';

//サービス一覧情報取得
$arrBody = array();
$arrBody["TG_ID"] = $tg_id;
$arrBody["SVC_ID"] = $svc_id;
$arrBody["SVC_INFO_NO"] = $svc_info_no;

$service_detail = $service->getServiceDetail($post_id, $arrBody);
$MService = $service->getMService($post_id, $arrBody)->data;

$start_date = $service_detail->POST_START_DATE;
$end_date = $service_detail->POST_END_DATE;
$start_time = substr($service_detail->POST_START_TIME, 0, 2);
$end_time = substr($service_detail->POST_END_TIME, 0, 2);

$service_item_list = $service->getApplyServiceItem($post_id, $arrBody);
$service_list = ($service_item_list->data);
$service_item_list_free = $service->getApplyServiceItemFree($post_id, $arrBody);
$service_list_free = $service_item_list_free->data;
?>
    <title><?= $service_detail->POST_TITLE ?></title>
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/req_service.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/base.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/service_detail.css"/>
    <style type="text/css">
        ol#breadcrumb {
            display: none!important;
        }
        .breadSection {
            border-bottom: 1px solid rgba(0,0,0,.05);
        }
        .breadcrumb {
            margin-top: 0;
            margin-bottom: 0;
            background: 0 0;
            padding: 6px 15px 6px 0;
            display: flex;
            flex-wrap: wrap;
        }
        .breadcrumb li {
            line-height: 1.2;
            margin-bottom: 0;
            list-style: none;
        }
        .breadcrumb a {
            color: #111;
            padding-bottom: 2px;
        }
        a:visited{
            text-decoration: none;
        }
        .breadcrumb span {
         
        }
        .breadcrumb span a{
            color: #111
        }

        .breadSection .breadcrumb a:hover {
            border-bottom: 1px solid #666;
            text-decoration: none;
        }
    </style>
    <script type="text/javascript"
            src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/list/list.js"></script>
    <script type="text/JavaScript">
        function jump(link_type, url, cd, domain, root) {
            if (link_type == 1) {
                if (url.indexOf("http", 0) == -1) {
                    window.open("http://" + url + "");
                } else {
                    window.open(url);
                }
            } else {
                if (cd == 7 || cd == 8) {

                    location.href = "https://" + domain + root + "/" + url;

                } else {

                    location.href = "http://" + domain + root + "/" + url;

                }
            }
        }

        function pass(cd, no) {
            var url = "https://www.nakamacloud.com/dantai/edit/password.asp?page_no=" + no;
            location.href = url;
        }


        function OnCommand(cmd) {
            document.mainForm.cmd.value = cmd;
            document.mainForm.submit();
        }

        function OnCommandSp(cmd) {
            document.mainForm.keyword.value = document.mainForm.keyword_sp.value;
            document.mainForm.cmd.value = cmd;
            document.mainForm.submit();
        }

        function OnSearch(cmd) {
            if (cmd == '1') {
                document.mainForm.search_mode.value = '1'
            } else {
                document.mainForm.search_mode.value = '0'
            }
            document.mainForm.cmd;
            document.mainForm.submit();
        }

        function OnTopChange() {
            if (document.mainForm.top_type.value == "2") {
                document.mainForm.top_type.value = "1";
            } else {
                document.mainForm.top_type.value = "2";
            }
            document.mainForm.submit();
        }

        function svcReceipt(svcid) {
            var buf;

            buf = 'https://www.nakamacloud.com/dantai/login.asp?patten_cd=41&page_no=137&svcid=' + svcid + '&svcrequest=1';

            gToolWnd = open(buf,
                'DetailWnd',
                'width=980, height=750,location=no,hotkeys=no,toolbar=no,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
        }

        function his_back2() {

//	history.back();

            history.back();

        }

        function SetHeightBySideMenu() {
            var l_table = 0;
            var r_table = 0;
            var head_div = 0;
            var body_div = 0;

            head_div = document.getElementById("head_div").offsetHeight;
            body_div = document.getElementById("body_div").offsetHeight;
            if (l_table > r_table) {
                if (l_table > head_div + body_div) {
                    document.getElementById("body_div").style.height = l_table - head_div;
                }
            } else {
                if (r_table > head_div + body_div) {
                    document.getElementById("body_div").style.height = r_table - head_div;
                }
            }
        }

        function OnDownloadFile(fileName) {
            document.mainForm.fileName.value = fileName;
            document.mainForm.target = "_self";
            document.mainForm.method = "post";
            document.mainForm.action = "index.asp";
            document.mainForm.submit();
        }


        //-->
    </script>

<?php get_header(); ?>
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
        <div class="breadSection">
            <div class="container">
                <div class="row">
<!--                    same 5-->
                   <ol class="breadcrumb" itemtype="http://schema.org/BreadcrumbList">
                        <li id="panHome" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a
                                    itemprop="item" href="<?php echo get_site_url() ; ?>"><span itemprop="name"><i
                                            class="fa fa-home"></i> HOME</span></a></li>
                        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a itemprop="item"
                                                                                                             href="<?php echo get_site_url() ?>"><span
                                        itemprop="name">なかま２API連携テストサイト</span></a></li>
                        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a itemprop="item"
                                                                                                             href="<?php echo get_permalink(get_page_by_title('会員PR支援サービス')->ID); ?>"><span
                                        itemprop="name">会員PR支援サービス</span></a></li>
                        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a itemprop="item"
                                                                                                             href="javascript:getURL('<?php echo get_permalink($back_page). getAliasService();?>?svc_id=<?php echo $svc_id?>')"><span
                                        itemprop="name"><?php echo $MService[0]->SVC_NM?></span></a></li>
                        <li><span>サービス詳細</span></li>
                    </ol>
                    <!--                    same 4-->

                    <!--  <ol class="breadcrumb" itemtype="http://schema.org/BreadcrumbList">
                        <li id="panHome" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                            <a
                                    itemprop="item" href="<?php //echo get_site_url(); ?>"><span itemprop="name"><i
                                            class="fas fa-home"></i>  ホーム</span></a><i class="arrow">&gt;</i></li>
                        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                            <i class="fas fa-folder"></i><a itemprop="item"
                                                            href="<?php //echo get_site_url() ?>"><span
                                        itemprop="name">会員個別サービス</span></a><i class="arrow">&gt;</i></li>
                        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                            <i class="fas fa-folder"></i><a itemprop="item"
                                                            href="<?php //echo get_permalink(get_page_by_title('会員からのお知らせ')->ID); ?>"><span
                                        itemprop="name">会員からのお知らせ</span></a><i class="arrow">&gt;</i></li>
                        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                            <i class="fas fa-folder"></i><a itemprop="item"
                                                            href="javascript:getURL('<?php //echo get_permalink($back_page) . getAliasService(); ?>?svc_id=<?php //echo $svc_id ?>')"><span
                                        itemprop="name">「会員からのお知らせ」<?php  //echo  $MService[0]->SVC_NM ?></span></a><i
                                    class="arrow">&gt;</i></li>
                        <li>
                            <i class="far fa-file"></i><span>サービス詳細</span></li>
                    </ol>
                    -->
                </div>
            </div>

        </div>

        <table class="page_setup_width" align="center" cellspacing="0" cellpadding="0" border="0" style="margin-top: 1.5em; border:none">
            <tr>
                <td>
                    <table width="100%" align="center" cellpadding="4" border="0" style="border: none">
                        <tr>
                            <td class="no-border">
                                <table class="opn_table_t">
                                    <tr>
                                        <td><?php echo $service_detail->POST_TITLE ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="opn_font_b no-border">　<img
                                        src="https://www.nakamacloud.com/dantai/nakama/img/shinsei_icon.gif">&nbsp;内容詳細
                            </td>
                        </tr>
                        <?php if (isset($service_list)) { ?>
                            <tr>
                                <td class="no-border"><?php if (count($service_list) > 0) { ?>
                                        <table class="opn_table_b" align="center">
                                            <?php foreach ($service_list as $key => $value) { ?>
                                                <tr>
                                                    <td class="opn_table_i" nowrap=""><?= $value->MSVC_ITEM_NM ?></td>
                                                    <?php
                                                    if(strpos($value->SVC_ITEM_VALUE,'|')!=false){
                                                        $arr_value = explode('|',$value->SVC_ITEM_VALUE);
                                                        foreach ($arr_value as $index => $svc_value){
                                                            $value->SVC_ITEM_VALUE = $arr_value[0].' '.$arr_value[1].'時'.$arr_value[2];
                                                        }
                                                    }?>
                                                    <td class="opn_table_d"><?= $value->SVC_ITEM_VALUE ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if (count($service_list_free) > 0) { ?>
                                                <?php foreach ($service_list_free as $key => $value) { ?>
                                                    <tr>
                                                        <td class="opn_table_i"
                                                            nowrap=""><?= $value->SVC_ITEM_NM ?></td>
                                                        <td class="opn_table_d"><?= $value->SVC_ITEM_VALUE ?></td>
                                                    </tr>
                                                <?php }
                                            } ?>
                                        </table>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="no-border">
                                <hr width="100%">
                            </td>
                        </tr>
                        <tr>
                            <td class="opn_font_b no-border">　<img
                                        src="https://www.nakamacloud.com/dantai/nakama/img/shinsei_icon.gif">&nbsp;お問い合せ
                            </td>
                        </tr>
                        <tr>
                            <td class="opn_font_n">
                                <?php if (!empty($service_detail->CONTACT_IMG)) { ?>
                                    <?php $file_contact_img_dir = "/ServiceData/{ $service_detail->TG_ID}/{ $service_detail->SVC_ID}/{ $service_detail->SVC_INFO_NO}/ContactImage"; ?>
                                    <img src="<?php echo $service->getServiceDisplayFile($service_detail->CONTACT_IMG, '', $file_contact_img_dir); ?>"
                                         align="right" style="padding-top:10px;padding-right:10px;" width="250px">
                                <?php } ?>

                                <?= nl2br($service_detail->CONTACT_NAME) ?>
                                <br>
                                TEL: <?= $service_detail->CONTACT_TEL ?>
                                <br>
                                E-mail:&nbsp;<a
                                        href="mailto:<?php echo $service_detail->CONTACT_MAIL ?>"><?php echo $service_detail->CONTACT_MAIL ?></a>
                                <br>
                                <table class="opn_font_n no-border" style="margin:5px 0px 0px 0px;">
                                    <tr>
                                        <td>掲載日</td>
                                        <td><?php echo convert_date($start_date) . $start_time . '時' ?></td>
                                    </tr>
                                    <tr>
                                        <td>掲載期限</td>
                                        <td><?php echo convert_date($end_date) . $end_time . '時' ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="svc_id" value="4">
                    <input type="hidden" name="tg_id" value="">
                    <input type="hidden" name="fileName" value="">

                    <input type="hidden" name="category" value="">
                    <input type="hidden" name="keyword" value="">
                </td>
            </tr>
        </table>
        <div style="clear:both;"></div>
        <input type="hidden" name="svcinfo_no" value="3">
    </form>
<?php get_footer(); ?>