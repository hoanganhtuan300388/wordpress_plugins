<?php
if (!isset($_SESSION['arrSession'])) {
    include('service_session_error.php');
    exit;
} else {
    $service = new serviceController();
    $page_no = isset($_GET['page_no']) ? $_GET['page_no'] : "";
    $tg_id = isset($_GET['top_g_id']) ? $_GET['top_g_id'] : "";
    $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : "";
    $svc_id = isset($_GET['svc_id']) ? $_GET['svc_id'] : 0;    //サービスID
    $p_id = isset($_GET['p_id']) ? $_GET['p_id'] : 0;
    $reg_svc_id = isset($_GET['reg_svc_id']) ? $_GET['reg_svc_id'] : "";    //編集対象サービスID
    $svc_info_no = isset($_GET['svc_info_no']) ? $_GET['svc_info_no'] : "";    //編集対象サービス掲載番号
    $file_name = isset($_GET['file_name']) ? $_GET['file_name'] : "";
    $s_kind = isset($_GET['s_kind']) ? $_GET['s_kind'] : "";
    $type = isset($_GET['mode']) ? $_GET['mode'] : "";
    $type = isset($_GET['type']) ? $_GET['type'] : $type;
    $service = new serviceController();

    if (isset($_SESSION['auth'])) {
        $p_id = isset($_SESSION['auth']['p_id']) ? $_SESSION['auth']['p_id'] : "";
    }

    if (isset($_GET['func']) && isset($_SESSION['service_select'])) {
        $s_kind = $_SESSION['service_select'];

    }
    unset($_SESSION['service_input']);

//メンテナンス中の場合、メンテナス画面を表示する

//メンテナンスの場合、メンテナンス中画面を表示する(団体毎)

//引数チェック(page_no)
    if ($service->checkReqParamNumeric($page_no) === false) {
        //不正な呼び出し
    }
//引数チェック(svc_id)
    if ($service->checkReqParamNumeric($svc_id) === false) {
        //不正な呼び出し
    }
//引数チェック(reg_svc_id)
    if ($service->checkReqParamNumeric($reg_svc_id) === false) {
        //不正な呼び出し
    }
//引数チェック(reg_svcinfo_no)
    if ($service->checkReqParamNumeric($svc_info_no) === false) {
        //不正な呼び出し
    }
//選択サービスデータ取得
    $arrBody = array();

    $arrBody['TG_ID'] = $tg_id;

    $service_select_list = $service->getServiceSelect($post_id, $arrBody);


//ユーザ情報取得
    $arrBody = array();
    $arrBody['TG_ID'] = $tg_id;
    $arrBody['P_ID'] = $p_id;
    $request_service_applicant = $service->getServiceApplicant($post_id, $arrBody);


    if ($svc_id !== "" && $type == "update") {

        $arrBody['SVC_ID'] = $svc_id;
        $service_select_list = $service->getServiceSelect($post_id, $arrBody);
        //申請サービス情報の取得
        $arrBody = array();
        $arrBody['TG_ID'] = $tg_id;
        $arrBody['SVC_ID'] = $svc_id;
        $arrBody['SVC_INFO_NO'] = $svc_info_no;
        $request_service = $service->getApplyService($post_id, $arrBody);

        //掲載サービス項目データ取得
        $arrBody = array();
        $arrBody['TG_ID'] = $tg_id;
        $arrBody['SVC_ID'] = $svc_id;
        $arrBody['SVC_INFO_NO'] = $svc_info_no;
        $service_item_list = $service->getApplyServiceItem($post_id, $arrBody);
        $service_list = ($service_item_list->data);
        $service_item_list_free = $service->getApplyServiceItemFree($post_id, $arrBody);
        $service_list_free = $service_item_list_free->data;
        $arr = array();
        $arr['TG_ID'] = (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->TG_ID : $dataSetting['nakama_service_param_tg_id'][0];
        $arr['SVC_ID'] = $svc_id;

        $MServiceItemList = $service->getMServiceItem($post_id, $arr);
        $MServiceItem = $MServiceItemList->data;
        $size = 0;
        if(isset($service_list)){
            $size= count($service_list)>count($MServiceItem)?count($MServiceItem): count($service_list);
        }
    }
    ?>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE11" />
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/default.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/base.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/req_service.css"/>

    <script type="text/javascript">
        <!--
        function move_next() {
            var form = document.mainForm;
            var chk = 0;

            if (form.s_kind.length != undefined) {
                for (var i = 0; i < form.s_kind.length; i++) {
                    if (form.s_kind[i].checked == true) {
                        chk = 1;
                        form.svc_id.value = i + 1;
                        break;
                    }
                }
            } else {
                // chk = 1;
                if (form.s_kind != undefined) {
                    if (form.s_kind.type == 'hidden') {
                        chk = 1;
                    } else {
                        if (form.s_kind.checked) {
                            chk = 1;
                        }
                    }
                }
            }
            if (chk != 1) {
                alert("申請するサービスを選択して下さい。");
                return;
            }
            if ('<?= $type?>' == "update") {
                let service_item = [];
                let free_data = [];
                let free_title = [];
                let item_image = [];
                let item_image_free = [];
                var service_items = <?php echo json_encode($service_list)?>;
                let service_date_time = [];
                console.log(service_items)
                var free_datas = <?php echo json_encode($service_list_free)?>;
                for (let i = 0; i < service_items.length; i++) {
                    service_item[service_items[i].SVC_ITEM_NO - 1] = service_items[i].SVC_ITEM_VALUE;
                    if(service_items[i].MSVC_ITEM_TYPE=='DATE'){
                        let service_item_value = service_items[i].SVC_ITEM_VALUE.split(' ');
                        console.log(service_item_value)
                        let item = {
                            'date': service_item_value[0].substr(0,4) + '/' + service_item_value[0].substr(5,2) + '/' + service_item_value[0].substr(8,2),
                            'time': service_item_value[1]?service_item_value[1].substr(0,2): '',
                            'des_date': service_item_value[2]?service_item_value[2]: ''
                        };
                        service_date_time[service_items[i].SVC_ITEM_NO - 1] = item;
                        service_item[service_items[i].SVC_ITEM_NO - 1] = service_item_value[2];
                    }
                }

                for (let i = 0; i < free_datas.length; i++) {
                    free_data[free_datas[i].SVC_ITEM_NO - 101] = free_datas[i].SVC_ITEM_VALUE;
                }
                for (let i = 0; i < free_datas.length; i++) {
                    free_title[free_datas[i].SVC_ITEM_NO - 101] = free_datas[i].SVC_ITEM_NM;
                }
                for (let i = 0; i < service_items.length; i++) {
                    item_image[service_items[i].SVC_ITEM_NO - 1] = service_items[i]
                }

                for (let i = 0; i < free_datas.length; i++) {
                    item_image_free[free_datas[i].SVC_ITEM_NO - 101] = free_datas[i]
                }

                // console.log(service_item)
                const service_input = {
                    service_item: service_item,
                    service_title: form.service_title.value,
                    free_data: free_data,
                    free_title: free_title,
                    list_disp_g_name: form.list_disp_g_name.value,
                    service_inq: form.service_inq.value,
                    service_tel: form.service_tel.value,
                    service_mail: form.service_mail.value,
                    releaseFrom: form.releaseFrom.value,
                    timeFrom: form.timeFrom.value,
                    releaseTo: form.releaseTo.value,
                    timeTo: form.timeTo.value,
                    send_mail: form.send_mail.value,
                    item_image: item_image,
                    item_image_free: item_image_free,
                    service_date_time: service_date_time
                };
                form.service_input.value = JSON.stringify(service_input);
            }

            form.method = "post";
            form.action = "<?php echo get_permalink(get_page_by_path('nakama-request-service-input')->ID); ?>";
            form.submit();
        }

        function listBack() {
            var form = document.backForm;
            form.action = "<?php echo get_permalink(get_page_by_path('nakama-request-service-list')->ID); ?>";
            form.submit();
        }

        function OnDownloadFile(fileName) {
            document.mainForm.fileName.value = fileName;
            document.mainForm.target = "_self";
            document.mainForm.method = "post";
            document.mainForm.action = "service_select.asp";
            document.mainForm.submit();
        }

        //-->
    </script>
    <body onload="onload_page()">
    <form name="mainForm" id="mainForm" action="">
        <form name="mainForm" id="mainForm" action="" method="">
            <table cellspacing="0" cellpadding="3" border="0" width="100%">
                <tr>
                    <td class="header">■<?= $service_select_list->data[0]->SVC_NM ?>申請</td>
                </tr>
            </table>
            <br>
            <div style="text-align:center">
		<span style="position: relative;">
			<img src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/step/step_a.png">
			<span class="progress_i">申請サービス選択</span>
		</span>
                <span style="position: relative;">
			<img src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/step/step.png">
			<span class="progress_i">申請内容入力</span>
		</span>
                <span style="position: relative;">
			<img src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/step/step.png">
			<span class="progress_i">申請内容確認</span>
		</span>
                <span style="position: relative;">
			<img src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/step/step.png">
			<span class="progress_i">申請完了</span>
		</span>
            </div>
            <table class="table_b" align="center" cellpadding="4">
                <tr>
                    <td class="table_h" colspan="2">
                        ■申請者
                    </td>
                </tr>
                <tr>
                    <td class="table_i">会社名</td>
                    <td class="table_d">
                        <?= $request_service_applicant->ORG_NAME ?>
                        <input type="hidden" name="shinsei_name" value="<?= $request_service_applicant->ORG_NAME ?>">
                    </td>
                </tr>
                <tr>
                    <td class="table_i">部署名</td>
                    <td class="table_d">
                        <?= $request_service_applicant->AFFILIATION_NAME ?>
                        <input type="hidden" name="shinsei_busho"
                               value="<?= $request_service_applicant->AFFILIATION_NAME ?>">
                    </td>
                </tr>
                <tr>
                    <td class="table_i">役職</td>
                    <td class="table_d">
                        <?= $request_service_applicant->OFFICIAL_POSITION ?>
                        <input type="hidden" name="shinsei_yaku"
                               value="<?= $request_service_applicant->OFFICIAL_POSITION ?>">
                    </td>
                </tr>
                <tr>
                    <td class="table_i">氏名</td>
                    <td class="table_d">
                        <?= $request_service_applicant->PER_NAME ?>
                        <input type="hidden" name="shinsei_shimei" value="<?= $request_service_applicant->PER_NAME ?>">
                    </td>
                </tr>
                <tr>
                    <td class="table_i">TEL</td>
                    <td class="table_d">
                        <?= $request_service_applicant->G_TEL ?>
                        <input type="hidden" name="shinsei_tel" value="<?= $request_service_applicant->G_TEL ?>">
                    </td>
                </tr>
                <tr>
                    <td class="table_i">E-MAIL</td>
                    <td class="table_d">
                        <?= $request_service_applicant->C_EMAIL ?>
                        <input type="hidden" name="shinsei_mail" value="<?= $request_service_applicant->C_EMAIL ?>">
                    </td>
                </tr>
                <?php if ($svc_id === "" || $type !== "update") { ?>
                    <tr>
                        <td colspan="2">申請するサービスの種類を選択して下さい。</td>
                    </tr>
                    <tr>
                        <?php if ($service_select_list->Count > 0) { ?>
                            <td colspan="2">
                                <?php $i = 1; ?>
                                <?php foreach ($service_select_list->data as $res_select) { ?>
                                    <input type="radio" name="s_kind" value="<?= $res_select->SVC_NM ?>"
                                           id="kind<?= $i ?>">
                                    <label for="kind<?= $i ?>"><?= $res_select->SVC_NM ?></label><br>
                                    <?php $i++; ?>
                                <?php } ?>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td colspan="2">下記サービスの編集を開始します。</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="opn_table_t">
                                <tbody>
                                <tr>
                                    <td><?= $request_service[0]->POST_TITLE ?></td>
                                </tr>
                                <input type="hidden" name="service_title"
                                       value="<?= $request_service[0]->POST_TITLE ?>">
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="opn_font_b" colspan="2">　<img
                                    src="https://www.nakamacloud.com/dantai/nakama/img/shinsei_icon.gif">&nbsp;内容詳細
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="opn_table_b" align="center" cellspacing="3">
                                <?php if(isset($service_list)) {?>
                                <?php for ($i = 0; $i < $size; $i++) { ?>
                                    <tr>
                                        <td class="opn_table_i_noline" nowrap="">
                                            <?= $service_list[$i]->SVC_ITEM_NM ?>
                                        </td>
                                        <td class="opn_table_d">
                                            <?php if (!empty($service_list[$i]->FILE1) && $ServiceItemImg['item'. $i] == 1) { ?>
                                                <?php $file_item1_dir = "/ServiceData/{$service_list[$i]->TG_ID}/{$service_list[$i]->SVC_ID}/{$service_list[$i]->SVC_INFO_NO}/PublishItem/{$service_list[$i]->SVC_ITEM_NO}/1"; ?>
                                                <div style="text-align: <?php echo $service_list[$i]->SITUATION1 ?>">
                                                    <img src="<?php echo $service->getServiceDisplayFile($service_list[$i]->FILE1, $service_list[$i]->RE_FILE1, $file_item1_dir); ?>"
                                                         title="画像"
                                                         style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                                </div>
                                                <br clear="left">
                                            <?php } ?>
                                            <?= strpos($service_list[$i]->SVC_ITEM_VALUE,'\"')?str_replace('\"','',$service_list[$i]->SVC_ITEM_VALUE):$service_list[$i]->SVC_ITEM_VALUE ?>
                                            <?php if (!empty($service_list[$i]->FILE2) && $ServiceItemImg['item'. $i] == 1) { ?>
                                                <?php $file_item2_dir = "/ServiceData/{$service_list[$i]->TG_ID}/{$service_list[$i]->SVC_ID}/{$service_list[$i]->SVC_INFO_NO}/PublishItem/{$service_list[$i]->SVC_ITEM_NO}/2"; ?>
                                                <div style="text-align: <?php echo $service_list[$i]->SITUATION2 ?>">
                                                    <img src="<?php echo $service->getServiceDisplayFile($service_list[$i]->FILE2, $service_list[$i]->RE_FILE2, $file_item2_dir); ?>"
                                                         title="画像"
                                                         style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                                </div>
                                                <br clear="left">
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <input type="hidden" name="service_item[]"
                                           value="<?= $service-> htmlToString($service_list[$i]->SVC_ITEM_VALUE) ?>">
                                    <input type="hidden" name="item_title[]"
                                           value="<?= $service_list[$i]->SVC_ITEM_NM ?>">
                                    <input type="hidden" name="item_<?= $i + 1; ?>_file1"
                                           value="<?= $service_list[$i]->FILE1 ?>">
                                    <input type="hidden" name="item_<?= $i + 1; ?>_re_file1"
                                           value="<?= $service_list[$i]->RE_FILE1 ?>">
                                    <input type="hidden" name="item_<?= $i + 1; ?>_file2"
                                           value="<?= $service_list[$i]->FILE2 ?>">
                                    <input type="hidden" name="item_<?= $i + 1; ?>_re_file2"
                                           value="<?= $service_list[$i]->RE_FILE2 ?>">
                                    <input type="hidden" name="item_<?= $i + 1; ?>_situation1"
                                           value="<?= $service_list[$i]->SITUATION1 ?>">
                                    <input type="hidden" name="item_<?= $i + 1; ?>_situation2"
                                           value="<?= $service_list[$i]->SITUATION2 ?>">
                                <?php } }?>
                                <?php for ($j = 0; $j < sizeof($service_list_free); $j++) { ?>
                                    <tr>
                                        <td class="opn_table_i_noline" nowrap="">
                                            <?= $service_list_free[$j]->SVC_ITEM_NM ?>
                                        </td>
                                        <td class="opn_table_d">
                                            <?php if (!empty($service_list_free[$j]->FILE1) && $ServiceItemFreeImg['item'. $j] == 1) { ?>
                                                <?php $file_item1_free_dir = "/ServiceData/{$service_list_free[$j]->TG_ID}/{$service_list_free[$j]->SVC_ID}/{$service_list_free[$j]->SVC_INFO_NO}/PublishFreeItem/{$service_list_free[$j]->SVC_ITEM_NO}/1"; ?>
                                                <div style="text-align: <?php echo $service_list_free[$j]->SITUATION1 ?>">
                                                    <img src="<?php echo $service->getServiceDisplayFile($service_list_free[$j]->FILE1, $service_list_free[$j]->RE_FILE1, $file_item1_free_dir); ?>"
                                                         title="画像"
                                                         style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                                </div>
                                                <br clear="left">
                                            <?php } ?>
                                            <?= strpos($service_list_free[$j]->SVC_ITEM_VALUE,'\"')?str_replace('\"','',$service_list_free[$j]->SVC_ITEM_VALUE):$service_list_free[$j]->SVC_ITEM_VALUE  ?>
                                            <?php if (!empty($service_list_free[$j]->FILE2) && $ServiceItemFreeImg['item'. $j] == 1) { ?>
                                                <?php $file_item2_free_dir = "/ServiceData/{$service_list_free[$j]->TG_ID}/{$service_list_free[$j]->SVC_ID}/{$service_list_free[$j]->SVC_INFO_NO}/PublishFreeItem/{$service_list_free[$j]->SVC_ITEM_NO}/2"; ?>
                                                <div style="text-align: <?php echo $service_list_free[$j]->SITUATION2 ?>">
                                                    <img src="<?php echo $service->getServiceDisplayFile($service_list_free[$j]->FILE2, $service_list_free[$j]->RE_FILE2, $file_item2_free_dir); ?>"
                                                         title="画像"
                                                         style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                                </div>
                                                <br clear="left">
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <input type="hidden" name="free_data[]"
                                           value="<?= $service-> htmlToString($service_list_free[$j]->SVC_ITEM_VALUE) ?>">
                                    <input type="hidden" name="free_title[]"
                                           value="<?= $service_list_free[$j]->SVC_ITEM_NM ?>">
                                    <input type="hidden" name="item_<?= $j + 101; ?>_file1"
                                           value="<?= $service_list_free[$j]->FILE1 ?>">
                                    <input type="hidden" name="item_<?= $j + 101; ?>_re_file1"
                                           value="<?= $service_list_free[$j]->RE_FILE1 ?>">
                                    <input type="hidden" name="item_<?= $j + 101; ?>_file2"
                                           value="<?= $service_list_free[$j]->FILE2 ?>">
                                    <input type="hidden" name="item_<?= $j + 101; ?>_re_file2"
                                           value="<?= $service_list_free[$j]->RE_FILE2 ?>">
                                    <input type="hidden" name="item_<?= $j + 101; ?>_situation1"
                                           value="<?= $service_list_free[$j]->SITUATION1 ?>">
                                    <input type="hidden" name="item_<?= $j + 101; ?>_situation2"
                                           value="<?= $service_list_free[$j]->SITUATION2 ?>">
                                <?php } ?>
                                <tr>
                                    <td class="opn_table_i_noline">お問合せ先</td>
                                    <td class="opn_table_d">
                                        <?php if (!empty($request_service[0]->CONTACT_IMG)) { ?>
                                            <?php $file_contact_img_dir = "/ServiceData/{$request_service[0]->TG_ID}/{$request_service[0]->SVC_ID}/{$request_service[0]->SVC_INFO_NO}/ContactImage"; ?>
                                            <img src="<?php echo $service->getServiceDisplayFile($request_service[0]->CONTACT_IMG, '', $file_contact_img_dir); ?>" align="right" style="padding-top:10px;padding-right:10px;" width="250px">
                                        <?php } ?>
                                    
                                        <?= nl2br($request_service[0]->CONTACT_NAME) ?>
                                        <br>
                                        TEL: <?= $request_service[0]->CONTACT_TEL ?>
                                        <br>
                                        E-mail:&nbsp;<a
                                                href="mailto:<?= $request_service[0]->CONTACT_MAIL ?>"><?= $request_service[0]->CONTACT_MAIL ?></a>
                                        <br>
                                        <table class="opn_font_n" style="margin:5px 0px 0px 0px;">
                                            <tr>
                                                <td>掲載日</td>
                                                <td><?= convert_date($request_service[0]->POST_START_DATE) ?>
                                                    &nbsp;<?= $request_service[0]->POST_START_TIME ?>時
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>掲載期限</td>
                                                <td><?= convert_date($request_service[0]->POST_END_DATE) ?>
                                                    &nbsp;<?= $request_service[0]->POST_END_TIME ?>時
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" name="s_kind" value="<?= $service_select_list->data[0]->SVC_NM ?>">
                            <input type="hidden" name="img_no" value="<?= $request_service[0]->CONTACT_IMG_NO ?>">
                            <input type="hidden" name="service_inq" value="<?= $service->htmlToString($request_service[0]->CONTACT_NAME) ?>">
                            <input type="hidden" name="service_tel" value="<?= $request_service[0]->CONTACT_TEL ?>">
                            <input type="hidden" name="service_mail" value="<?= $request_service[0]->CONTACT_MAIL ?>">
                            <input type="hidden" name="m_img" value="<?= $request_service[0]->CONTACT_IMG ?>">
                            <input type="hidden" name="m_imgOld" value="">
                            <input type="hidden" name="m_imgRegFlg" value="">
                            <input type="hidden" name="m_img_dir" value="<?php echo !empty($request_service[0]->CONTACT_IMG) ? $file_contact_img_dir : ''; ?>">
                            <input type="hidden" name="reason" value="">
                            <input type="hidden" name="releaseFrom" value="<?= $request_service[0]->POST_START_DATE ?>">
                            <input type="hidden" name="timeFrom" value="<?= substr($request_service[0]->POST_START_TIME,0,2) ?>">
                            <input type="hidden" name="releaseTo" value="<?= $request_service[0]->POST_END_DATE ?>">
                            <input type="hidden" name="timeTo" value="<?= substr($request_service[0]->POST_END_TIME,0,2) ?>">
                            <input type="hidden" name="notice_flg" value="<?= $request_service[0]->NOTICE_FLG ?>">
                            <input type="hidden" name="reason_flg" value="<?= $request_service[0]->NOTICE_FLG ?>">
                            <input type="hidden" name="send_mail" value="<?= $request_service[0]->SEND_NOTICE_FLG ?>">
                            <input type="hidden" name="list_disp_g_name" value=" <?= $request_service[0]->POST_G_NAME ?>">
                            <input type="hidden" name="judge_result" value="">
                            <input type="hidden" name="type" value="<?= $type ?>">
                            <input type="hidden" name="service_input" value="">

                        </td>
                    </tr>
                <?php } ?>
            </table>
            <br>
            <table class="table_btn" align="center">
                <tr>
                    <td align="right">
                        <input align="right" type="button" class="btnClass" value="キャンセル" onclick="listBack();">
                        <input align="right" type="button" class="btnClass" value="次　へ" onclick="move_next()">
                    </td>
                </tr>
            </table>
            <input type="hidden" name="page_no" value="<?= $page_no ?>">
            <input type="hidden" name="top_g_id" value="<?= $tg_id ?>">
            <input type="hidden" name="svc_req_p_id" value="<?= $p_id ?>">
            <input type="hidden" name="g_id" value="dmshibuyablock01">
            <input type="hidden" name="a_id" value="dmshibuyag_none">
            <input type="hidden" name="service_url" value="">

            <input type="hidden" name="svc_id" value="<?= $svc_id ?>">
            <input type="hidden" name="reg_svcid" value="<?= $reg_svc_id ?>">
            <input type="hidden" name="svc_info_no" value="<?= $svc_info_no ?>">
            <input type="hidden" name="post_id" value="<?= $post_id ?>">
            <input type="hidden" name="fileName" value="<?= $file_name ?>">
        </form>
        <form name="backForm" id="backForm">
            <input type="hidden" name="page_id" value="<?= get_page_by_path('nakama-request-service-list')->ID ?>">
            <input type="hidden" name="page_no" value="<?= $page_no ?>">
            <input type="hidden" name="top_g_id" value="<?= $tg_id ?>">
            <input type="hidden" name="post_id" value="<?= $post_id ?>">
            <input type="hidden" name="svc_req_p_id" value="<?= $p_id ?>">
        </form>
    </body>

    <script type="text/javascript">
        function onload_page() {
            var form = document.mainForm;
            if (form.s_kind.length != undefined) {
                for (var i = 0; i < form.s_kind.length; i++) {
                    if (form.s_kind[i].value == "<?= $s_kind ?>") {
                        form.s_kind[i].checked = true;
                        break;
                    }
                }
            }
        }
    </script>
<?php } ?>