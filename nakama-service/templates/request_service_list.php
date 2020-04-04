<?php
$service = new serviceController();
$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : "";
$tg_id = isset($_GET['top_g_id']) ? $_GET['top_g_id'] : "";
$svc_id = isset($_GET['svc_id']) ? $_GET['svc_id'] : "";
$svc_info_no = isset($_GET['svc_info_no']) ? $_GET['svc_info_no'] : "";
$cmd = isset($_GET['cmd']) ? $_GET['cmd'] : "";
$page_no = isset($_GET['page_no']) ? $_GET['page_no'] : "";

$auth = array();
$auth['page_no'] = $page_no;
$auth['top_g_id'] = $_SESSION['arrSession']->TG_ID;
$auth['svc_id'] = $svc_id;
$auth['p_id'] = $_SESSION['arrSession']->P_ID;

// save session data
$_SESSION['auth'] = $auth;
$p_id = isset($_SESSION['auth']['p_id']) ? $_SESSION['auth']['p_id'] : $_SESSION['arrSession']->P_ID;;
$pre_page = 10;

//login
$path_page = get_page_uri();
$request_service_list = serviceCrSet::getPageSlug('nakama-request-service-list');
$page_link = serviceCrSet::getPageSlug('nakama-login') . "page_request=request_service_select&post_id=" . $post_id . "&top_g_id=" . $tg_id . "&page_no=" . $page_no;

if (!isset($_SESSION['arrSession'])) {
    ?>
    <script>window.location = "<?php echo $page_link . '&page_redirect=' . $path_page; ?>"; </script>
    <?php
} else {
    $nakama_service_param_lg_g_id = get_post_meta($post_id, 'set_lg_g_id', true);
    if ($_SESSION['arrSession']->LTG_ID != $nakama_service_param_lg_g_id) {
        $UpLgId_flg = get_post_meta($post_id, 'lg_login', true);
        if (!empty($nakama_service_param_lg_g_id)) {
            $arrBodyLogin = array(
                "tgId" => $_SESSION['arrSession']->TG_ID,
                "userId" => $_SESSION['arrSession']->USERID,
                "password" => $_SESSION['arrSession']->PASSWORD,
                "rememberMe" => false,
                "loginStyle" => 1,
                "lg_id" => $nakama_service_param_lg_g_id,
                "lg_login" => 1,
                "UpLgId_flg" => $UpLgId_flg
            );
            $login = $service->logins($post_id, $arrBodyLogin);
            if (isset($login->Message)) {
                unset($_SESSION['arrSession']);
?>
                <script>window.location = "<?php echo $path_page; ?>"; </script>
<?php
            }
        }
    }
}
//end-login

//初期設定
//セッション情報チェック(サイトID)
//引数チェック
//ページ番号
if ($service->checkReqParamNumeric($page_no) === false) {
    //不正な呼び出し
}
//サービスID
if ($svc_id !== "") {
    if ($service->checkReqParamNumeric($svc_id) === false) {
        //不正な呼び出し
    }
}
//コマンドボタン処理
switch ($cmd) {
    //サービス編集
    case 'update':
        if ($tg_id === "csaj") {
            $page_link = serviceCrSet::getPageSlug('nakama-service-select-csaj');

        } else {
            $page_link = serviceCrSet::getPageSlug('nakama-service-select');
        }
        break;

    //サービス削除
    case 'delete':
        $chkDel = isset($_GET['chkDel']) ? ($_GET['chkDel']) : "";

        //サービスデータ削除
        $arrBody = array();
        $arrBody['TG_ID'] =  $_SESSION['arrSession']->TG_ID;
        $arrBody['SVC_ID'] = $svc_id;
        $arrBody['P_ID'] = $_SESSION['arrSession']->P_ID;

        foreach ($chkDel as $key => $value) {
            //$check = str_replace(``,'', $chkDel);
            list($svcId, $svcInfoNo) = explode('_', $value);
            $arrBody['SVC_ID'] = $svcId;
            $arrBody['SVC_INFO_NO'] = $svcInfoNo;

            $request_service_list = $service->delService($post_id, $arrBody);
        }
        break;
}
$custom_logo_id = get_theme_mod('custom_logo');
$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
//var_dump($image[0]);
//$tg_id = $_SESSION['arrSession']->TG_ID;
//$p_id= $_SESSION['arrSession']->P_ID;
//データ取得(申請中サービス一覧)
$arrUri = array();
$arrUri['page_no'] = $page_no;
$arrUri['pre_page'] = $pre_page;

$arrBody = array();
$arrBody['TG_ID'] = $tg_id;

if (isset($svc_id) && $svc_id != "") {
    $arrBody['SVC_ID'] = $svc_id;
}
if (isset($p_id) && $p_id != "") {
    $arrBody['SVC_REQ_P_ID'] = $p_id;
}
//var_dump($arrBody);
$request_service_list = $service->getApplyServiceList($post_id, $arrUri, $arrBody);

//一覧件数取得
$max_cnt = 0;
if (isset($request_service_list->Count)) {
    $max_cnt = $request_service_list->Count;
}
?>

<script type="text/javascript">
    localStorage.clear();

    function chkAll() {
        var form = document.mainForm;
        checkDel = document.getElementsByName('chkDel[]');
        if (checkDel.length == undefined && checkDel != undefined) {
            checkDel.checked = form.allchk.checked;
            return;
        }
        if (checkDel.length <= 0) {
            return;
        }

        var chk = form.allchk.checked;

        for (var i = 0; i < checkDel.length; i++) {
            checkDel[i].checked = chk;
        }

    }

    function OnSort(no) {
        var form = document.mainForm;

        if (form.f_sort.value != no) {
            form.f_sort.value = no;
            form.f_sortorder.value = "ASC";
        } else {
            if (form.f_sortorder.value != "ASC") {
                form.f_sortorder.value = "ASC";
            } else {
                form.f_sortorder.value = "DESC";
            }
        }

        OnCommand("");

    }

    function OnUpdate(rowid) {
        var form = document.mainForm;
        var row_up = rowid.split("_");
        form.svc_id.value = row_up[0];
        form.svc_info_no.value = row_up[1];
        form.mode.value = "update";
        form.page_id.value = "<?= get_page_by_path('nakama-request-service-select')->ID ?>";
        form.action = "<?= get_permalink(get_page_by_path('nakama-request-service-select')->ID); ?>";
        form.submit();

    }

    function OnCommand(cmd) {
        switch (cmd) {
            case "regist":
                document.registForm.page_id.value = "<?= get_page_by_path('nakama-request-service-select')->ID ?>";
                document.registForm.action = "<?= get_permalink(get_page_by_path('nakama-request-service-select')->ID) ?>";
                document.registForm.submit();
                return;
                break;
            case "delete":
                if (!confirm("選択されているデータを削除します。よろしいですか？")) {
                    return;
                }
                document.mainForm.cmd.value = cmd;
                // document.mainForm.method = "post";
                document.mainForm.page_id.value = "<?= get_page_by_path('nakama-request-service-list')->ID ?>";
                document.mainForm.action = "<?= get_permalink(get_page_by_path('nakama-request-service-list')->ID); ?>";
                document.mainForm.submit();
            default:
                document.mainForm.action = "<?= get_permalink(get_page_by_path('nakama-request-service-list')->ID); ?>";
                break;
        }
    }

    //-->
</script>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE11"/>
<link rel="stylesheet" type="text/css"
      href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/base.css">
<link rel="stylesheet" type="text/css"
      href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/h_menu.css"/>

<link rel="stylesheet" type="text/css"
      href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/req_service_list.css"/>


<form id="mainForm" name="mainForm" action="">
    <center>
        <div class="header-list">
            <?php //lightning_print_headlogo(); ?>
            <?php echo $service->getLogo(); ?>
            <table cellspacing="0" cellpadding="3" >
                <tr>
                    <td width="90%" valign="top" align="right">
                        <font size="2"><b>
                          <?php if(!empty($_SESSION['arrSession'])) : ?>
                          <?php echo $_SESSION['login_TG_NAME']; ?>　ようこそ&nbsp;
                          <?php echo $_SESSION['arrSession']->GNAME; ?>&nbsp;
                          <?php echo $_SESSION['arrSession']->USER_NAME; ?>&nbsp;さん
                          <?php endif; ?>
                        </b></font>
                        <a class="btn_color" href="Javascript:window.close();"><img
                                    src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/close.gif" title="閉じる"
                                    border="0" style="height: auto"></a>
                    </td>
                </tr>
            </table>
        </div>
        <table class="h_width" align="left" cellspacing="0" cellpadding="0" border="0" style="float: none;">
            <tr>
                <td class="h_width">
                    <hr size="1">
                </td>
            </tr>
        </table>
    </center>
    <div style="clear:both"></div>

    <table width="98%" class="btn-group">
        <tr>
            <td align="left">
                【件数：<?= $max_cnt ?>件】
            </td>
            <td align="right">
                <input type="button" style="width:100px; height:25px;" name="btnRegist" value="新規登録"
                       onclick="OnCommand('regist');">
                <?php if ($max_cnt > 0) { ?>
                    <input type="button" style="width:100px; height:25px;" name="btnDelete" value="×削除"
                           onclick="OnCommand('delete');">
                <?php } ?>
            </td>
        </tr>
    </table>

    <table class="ListTable" width="98%">
        <tr class="ListHeader">
            <td class="ListItem_c" style="padding:0px;" width="25"><input type="checkbox" name="allchk"
                                                                          onclick="chkAll();"></td>
            <td class="ListItem_c" width="30">No</td>
            <td class="ListItem_l" width="180" style="vertical-align: middle">サービスの種類</td>
            <td class="ListItem_l"  style="vertical-align: middle">件名</td>
            <td class="ListItem_l" width="160">更新日時<br>登録日時</td>
            <td class="ListItem_l" width="55">更新<br>回数</td>
        </tr>
        <?php $i = 0; ?>
        <?php
        if (!isset($request_service_list->messages)) {
            foreach ($request_service_list->data as $res_service) {
                $i++; ?>
                <?php if ($i % 2 !== 0) {
                    $rowClass = "Row1";
                } else {
                    $rowClass = "Row2";
                } ?>
                <tr class="<?= $rowClass ?>">
                    <td class="ListValue" style="padding:0px;" align="center">
                        <input type="checkbox" name="chkDel[]"
                               value='<?php echo $res_service->SVC_ID . '_' . $res_service->SVC_INFO_NO; ?>'>
                    </td>
                    <td class="ListValue" align="right"><?= $i ?></td>
                    <td class="ListValue"><?= $res_service->SVC_NM ?></td>
                    <td class="ListValue">
                        <a href="Javascript:OnUpdate('<?= $res_service->SVC_ID . '_' . $res_service->SVC_INFO_NO; ?>');"><?= $res_service->POST_TITLE ?></a>
                    </td>
                    <td class="ListValue">
                        <?= str_replace('-', '/', substr($res_service->U_DATETIME, 0, 16)) ?>
                        <br>
                        <?= str_replace('-', '/', substr($res_service->R_DATETIME, 0, 16)) ?>
                    </td>
                    <td class="ListValue" align="right">
                        <?= $res_service->U_CNT ?>
                    </td>
                </tr>
            <?php }
        } ?>
    </table>
    <br>
    <center>
    </center>
    <input type="hidden" name="cmd" value="<?= $cmd ?>">
    <input type="hidden" name="page_id" value="">
    <input type="hidden" name="post_id" value="<?= $post_id ?>">
    <input type="hidden" name="top_g_id" value="<?= $tg_id ?>">
    <input type="hidden" name="page_no" value="<?= $page_no ?>">
    <input type="hidden" name="mode" value="">
    <input type="hidden" name="f_sort" value="">
    <input type="hidden" name="f_sortorder" value="">
    <input type="hidden" name="svc_id" value="<?= $svc_id ?>">


    <input type="hidden" name="svc_info_no" value="">
</form>
<form id="registForm" name="registForm" action="">
    <input type="hidden" name="page_no" value="<?= $page_no ?>">
    <input type="hidden" name="page_id" value="">
    <input type="hidden" name="post_id" value="<?= $post_id ?>">
    <input type="hidden" name="p_id" value="<?= $p_id ?>">
    <input type="hidden" name="svc_id" value="<?= $svc_id ?>">
    <input type="hidden" name="top_g_id" value="<?= $tg_id ?>">
</form>
