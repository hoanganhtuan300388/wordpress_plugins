<?php
if (!isset($_SESSION['arrSession'])) {
    include('service_session_error.php');
    exit;
} else {
    $service = new serviceController();
    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : "";
    $tg_id = isset($_POST['top_g_id']) ? $_POST['top_g_id'] : "";
    $p_id = isset($_POST['svc_req_p_id']) ? $_POST['svc_req_p_id'] : "";
    $svc_id = isset($_POST['svc_id']) ? $_POST['svc_id'] : "";
    $page_no = isset($_POST['page_no']) ? $_POST['page_no'] : 0;
    $reg_svc_id = isset($_POST['reg_svcid']) ? $_POST['reg_svcid'] : "";    //編集対象サービスID
    $reg_svc_info_no = isset($_POST['reg_svcinfono']) ? $_POST['reg_svcinfono'] : "";
    $file_name = isset($_POST['file_name']) ? $_POST['file_name'] : "";

    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $dataSetting = get_post_meta($post_id);
    $arrBody = array();
    $arrBody['TG_ID'] = $tg_id;
    $arrBody['P_ID'] = $p_id;
    //var_dump($arrBody);
    if (isset($_SESSION['service_select'])) {
        $s_kind = $_SESSION['service_select'];
    }

//入力項目タイトル、必須項目設定
    $input_item_titles = array(
        array("タイトル", 1),
        array("企業名", 1),
        array("商品名又はサービス名", 1),
        array("価格", 0),
        array("会員特典", 1),
        array("概要", 1),
        array("概要２", 0),
        array("詳細はこちら", 0),
        array("任意項目1", 0),
        array("任意項目2", 0),
        array("任意項目3", 0),
        array("任意項目4", 0),
        array("任意項目5", 0));

//入力値取得
    $input_data = array();
    if (isset($_POST)) {
        $input_data = $_POST;
    }

    $service_input = json_decode(html_entity_decode(stripslashes(htmlspecialchars($_POST['service_input']))));
    $_SESSION['service_input'] = $service_input;
//申請者情報を取得
    $request_service_applicant = array();
    $request_service_applicant = $service->getServiceApplicant($post_id, $arrBody);
    ?>
    <script type="text/javascript">
        <!--
        function cancel() {

            let form = document.backForm;
            form.method = "post";
            form.action = "<?php echo get_permalink(get_page_by_path('nakama-request-service-input')->ID); ?>";
            form.submit();
        }

        function move_next() {
            if (!confirm("画面の内容で申請します。よろしいですか？")) {
                return;
            }
            let form = document.mainForm;
            form.sendMail.value = 1;
            form.method = "post";
            form.action = "<?php echo get_permalink(get_page_by_path('nakama-request-service-complete')->ID); ?>";
            form.submit();
        }

        function move_next2() {
            let form = document.mainForm;
            form.tempFlg.value = 1;
            form.method = "post";
            form.action = "<?php echo get_permalink(get_page_by_path('nakama-request-service-complete')->ID); ?>";
            form.submit();
        }

        function OnDownloadFile(fileName) {
            //ここではダウンロードしない
        }


        //-->
    </script>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE11"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/default.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/req_service.css"/>
    <body>
    <form name="mainForm" id="mainForm">

        <table class="header" align="center">
            <tr>
                <td class="textwhite">■<?= $s_kind ?>&nbsp;入力確認</td>
            </tr>
        </table>
        <br>
        <div style="text-align:center">
		<span style="position: relative;">
			<img src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/step/step.png">
			<span class="progress_i">申請サービス選択</span>
		</span>
            <span style="position: relative;">
			<img src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/step/step.png">
			<span class="progress_i">申請内容入力</span>
		</span>
            <span style="position: relative;">
			<img src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/step/step_a.png">
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
                <td class="table_d"><?= $request_service_applicant->ORG_NAME ?></td>
            </tr>
            <tr>
                <td class="table_i">部署名</td>
                <td class="table_d"><?= $request_service_applicant->AFFILIATION_NAME ?></td>
            </tr>
            <tr>
                <td class="table_i">役職</td>
                <td class="table_d"><?= $request_service_applicant->OFFICIAL_POSITION ?></td>
            </tr>
            <tr>
                <td class="table_i">氏名</td>
                <td class="table_d"><?= $request_service_applicant->PER_NAME ?></td>
            </tr>
            <tr>
                <td class="table_i">TEL</td>
                <td class="table_d"><?= $request_service_applicant->G_TEL ?></td>
            </tr>
            <tr>
                <td class="table_i">E-MAIL</td>
                <td class="table_d"><?= $request_service_applicant->C_EMAIL ?></td>
            </tr>
            <?php $reason_flg = 0;
            if ($reason_flg !== "1"){ ?>
            <tr class="disp_none">
                <?php } else { ?>
            <tr>
                <?php } ?>
                <td class="table_i">申請理由</td>
                <td class="table_d"><input type="hidden" name="reason" value="<?= $reason_flg ?>">
                </td>
            </tr>

            <tr>
                <td class="table_i">掲載期間</td>
                <td class="table_d">
                    <?= $input_data["releaseFrom"] ?> <?= $input_data["timeFrom"] . '時 ' ?>
                    ～<?= $input_data["releaseTo"] ?>  <?= $input_data["timeTo"] . '時 ' ?>
                    <br>
                    会員へのメール通知：<?php echo ($input_data['send_mail'] == 1) ? "する" : "しない" ?>
                    <input type="hidden" name="releaseFrom" value=" <?= $input_data["releaseFrom"] ?>">
                    <input type="hidden" name="releaseTo" value=" <?= $input_data["releaseTo"] ?>">
                    <input type="hidden" name="timeFrom" value=" <?= $input_data["timeFrom"] ?>">
                    <input type="hidden" name="timeTo" value=" <?= $input_data["timeTo"] ?>">
                    <input type="hidden" name="send_mail" value="<?= $input_data['send_mail'] ?>">
                </td>
            </tr>
        </table>
        <br>
        <table class="table_b" align="center" cellpadding="4">
            <tr>
                <td class="table_h">
                    ■掲載内容
                </td>
            </tr>
            <tr>
                <td>
                    <table class="pre_table_t">
                        <tr>
                            <td>
                                <?php echo $service_input->service_title ?>
                                <input type="hidden" name="service_title" value="aaaa">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="pre_font_b">
                    <img src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/shinsei_icon.gif">&nbsp;内容詳細
                </td>
            </tr>
            <tr>
                <td>
                    <table class="pre_table_b" align="center" cellspacing="3">
                        <?php foreach ($service_input->service_item as $key => $value) { ?>
                            <tr class="<?php echo ($value != "" || ($service_input->service_date_time[$key]) != null) ? '' : 'empty-item' ?>">
                                <td class="pre_table_i_noline">
                                    <?= $_POST['item_title'][$key] ?>
                                    <input type="hidden" name="item_headline_flg"
                                           value="<?= $_POST['item_headline_flg'][$key] ?>">
                                    <input type="hidden" name="item_title[]" value="<?= $_POST['item_title'][$key] ?>">
                                </td>
                                <td class="pre_table_d">
                                    <?php if (!empty($_POST['item_' . ($key + 1) . '_file1'])) { ?>
                                        <div style="text-align: <?php echo $_POST['item_' . ($key + 1) . '_situation1'] ?>">
                                            <?php $file_item1_dir = !empty($_POST['item_' . ($key + 1) . '_file1_dir']) ? $_POST['item_' . ($key + 1) . '_file1_dir'] : "/temp/{$tg_id}"; ?>
                                            <img src="<?php echo $service->getServiceDisplayFile($_POST['item_' . ($key + 1) . '_file1'], $_POST['item_' . ($key + 1) . '_re_file1'], $file_item1_dir); ?>"
                                                 title="画像"
                                                 style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                        </div>
                                        <br clear="left">
                                    <?php } ?>
                                    <input type="hidden" name="item_<?= $key + 1 ?>_file1"
                                           value="<?= $_POST['item_' . ($key + 1) . '_file1'] ?>">
                                    <input type="hidden" name="item_<?= $key + 1 ?>_re_file1"
                                           value="<?= $_POST['item_' . ($key + 1) . '_re_file1'] ?>">
                                    <input type="hidden" name="item_<?= $key + 1 ?>_situation1"
                                           value="<?= $_POST['item_' . ($key + 1) . '_situation1'] ?>">
                                    <input type="hidden" name="item_<?= $key + 1 ?>_file1_dir"
                                           value="<?= $_POST['item_' . ($key + 1) . '_file1_dir'] ?>">
                                    <span name="service-item"><?php
                                        $date_time = $service_input->service_date_time[$key];
                                        $service_text = '';
                                        if($date_time){
                                            $date = convert_date($date_time->date);
                                            $time = $date_time->time? $date_time->time.'時 ':'';
                                            $service_text=  $date. ' '.$time.$date_time->des_date;
                                        }else{
                                            $service_text = $service_input->service_item[$key];
                                        }
                                        echo $service_text
                                        ?></span>
                                    <input type="hidden" name="item_<?= $key + 1 ?>_file2"
                                           value="<?= $_POST['item_' . ($key + 1) . '_file2'] ?>">
                                    <input type="hidden" name="item_<?= $key + 1 ?>_re_file2"
                                           value="<?= $_POST['item_' . ($key + 1) . '_re_file2'] ?>">
                                    <input type="hidden" name="item_<?= $key + 1 ?>_situation2"
                                           value="<?= $_POST['item_' . ($key + 1) . '_situation2'] ?>">
                                    <input type="hidden" name="item_<?= $key + 1 ?>_file2_dir"
                                           value="<?= $_POST['item_' . ($key + 1) . '_file2_dir'] ?>">
                                    <?php if (!empty($_POST['item_' . ($key + 1) . '_file2'])) { ?>
                                        <div style="text-align: <?php echo $_POST['item_' . ($key + 1) . '_situation2'] ?>">
                                            <?php $file_item2_dir = !empty($_POST['item_' . ($key + 1) . '_file2_dir']) ? $_POST['item_' . ($key + 1) . '_file2_dir'] : "/temp/{$tg_id}"; ?>
                                            <img src="<?php echo $service->getServiceDisplayFile($_POST['item_' . ($key + 1) . '_file2'], $_POST['item_' . ($key + 1) . '_re_file2'], $file_item2_dir); ?>"
                                                 title="画像"
                                                 style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                        </div>
                                        <br clear="left">
                                    <?php } ?>
                                </td>
                            </tr>
                            <input type="hidden" name="service_item[]"
                                   value="<?php echo $service->htmlToString($service_text) ?>">
                            <input type="hidden" name="valHTML[]" value="<?= $_POST['valHTML'][$key] ?>">
                            <input type="hidden" name="valItemNo[]" value="<?= $_POST['valItemNo'][$key] ?>">
                            <input type="hidden" name="valType[]" value="<?= $_POST['valType'][$key] ?>">
                        <?php } ?>

                        <!--                    free data-->
                        <tr id="free_data1"
                            class="<?php echo (($service_input->free_data[0]) == "") ? 'empty-item' : '' ?>">
                            <td class="pre_table_i_noline" name="item_101_title">
                                <?php echo $service_input->free_title[0] ?>
                            </td>
                            <td class="pre_table_d">
                                <?php if (!empty($_POST['item_101_file1'])) { ?>
                                    <?php $file_item1_free_dir = !empty($_POST['item_101_file1_dir']) ? $_POST['item_101_file1_dir'] : "/temp/{$tg_id}"; ?>
                                    <div style="text-align: <?php echo $_POST['item_101_situation1'] ?>">
                                        <img src="<?php echo $service->getServiceDisplayFile($_POST['item_101_file1'], $_POST['item_101_re_file1'], $file_item1_free_dir); ?>"
                                             title="画像"
                                             style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                    </div>
                                    <br clear="left">
                                <?php } ?>
                                <input type="hidden" name="item_101_file1"
                                       value="<?= $input_data["item_101_file1"] ?>">
                                <input type="hidden" name="item_101_re_file1"
                                       value="<?= $input_data["item_101_re_file1"] ?>">
                                <input type="hidden" name="item_101_situation1"
                                       value="<?= $input_data["item_101_situation1"] ?>">
                                <input type="hidden" name="item_101_file1_dir"
                                       value="<?= $input_data["item_101_file1_dir"] ?>">
                                <?php echo $service_input->free_data[0] ?>
                                <input type="hidden" name="item_101_file2"
                                       value="<?= $input_data["item_101_file2"] ?>">
                                <input type="hidden" name="item_101_re_file2"
                                       value="<?= $input_data["item_101_re_file2"] ?>">
                                <input type="hidden" name="item_101_situation2"
                                       value="<?= $input_data["item_101_situation2"] ?>">
                                <input type="hidden" name="item_101_file2_dir"
                                       value="<?= $input_data["item_101_file2_dir"] ?>">
                                <?php if (!empty($_POST['item_101_file2'])) { ?>
                                    <div style="text-align: <?php echo $_POST['item_101_situation2'] ?>">
                                        <?php $file_item2_free_dir = !empty($_POST['item_101_file2_dir']) ? $_POST['item_101_file2_dir'] : "/temp/{$tg_id}"; ?>
                                        <img src="<?php echo $service->getServiceDisplayFile($_POST['item_101_file2'], $_POST['item_101_re_file2'], $file_item2_free_dir); ?>"
                                             title="画像"
                                             style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                    </div>
                                    <br clear="left">
                                <?php } ?>
                            </td>
                        </tr>
                        <input type="hidden" name="free_title[]"
                               value="<?php echo $service_input->free_title[0] ?>">
                        <input type="hidden" name="free_data[]"
                               value="<?php echo $service->htmlToString($service_input->free_data[0]) ?>">
                        <input type="hidden" name="free_no[]" value="1">
                        <tr id="free_data2"
                            class="<?php echo (($service_input->free_data[1]) == "") ? 'empty-item' : '' ?>">
                            <td class="pre_table_i_noline" name="item_102_title">
                                <?php echo $service_input->free_title[1] ?>
                            </td>
                            <td class="pre_table_d">
                                <?php if (!empty($_POST['item_102_file1'])) { ?>
                                    <div style="text-align: <?php echo $_POST['item_102_situation1'] ?>">
                                        <?php $file_item1_free_dir = !empty($_POST['item_102_file1_dir']) ? $_POST['item_102_file1_dir'] : "/temp/{$tg_id}"; ?>
                                        <img src="<?php echo $service->getServiceDisplayFile($_POST['item_102_file1'], $_POST['item_102_re_file1'], $file_item1_free_dir); ?>"
                                             title="画像"
                                             style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                    </div>
                                    <br clear="left">
                                <?php } ?>
                                <input type="hidden" name="item_102_file1"
                                       value="<?= $input_data["item_102_file1"] ?>">
                                <input type="hidden" name="item_102_re_file1"
                                       value="<?= $input_data["item_102_re_file1"] ?>">
                                <input type="hidden" name="item_102_situation1"
                                       value="<?= $input_data["item_102_situation1"] ?>">
                                <input type="hidden" name="item_102_file1_dir"
                                       value="<?= $input_data["item_102_file1_dir"] ?>">
                                <?php echo $service_input->free_data[1] ?>
                                <input type="hidden" name="item_102_file2"
                                       value="<?= $input_data["item_102_file2"] ?>">
                                <input type="hidden" name="item_102_re_file2"
                                       value="<?= $input_data["item_102_re_file2"] ?>">
                                <input type="hidden" name="item_102_situation2"
                                       value="<?= $input_data["item_102_situation2"] ?>">
                                <input type="hidden" name="item_102_file2_dir"
                                       value="<?= $input_data["item_102_file2_dir"] ?>">
                                <?php if (!empty($_POST['item_102_file2'])) { ?>
                                    <div style="text-align: <?php echo $_POST['item_102_situation2'] ?>">
                                        <?php $file_item2_free_dir = !empty($_POST['item_102_file2_dir']) ? $_POST['item_102_file2_dir'] : "/temp/{$tg_id}"; ?>
                                        <img src="<?php echo $service->getServiceDisplayFile($_POST['item_102_file2'], $_POST['item_102_re_file2'], $file_item2_free_dir); ?>"
                                             title="画像"
                                             style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                    </div>
                                    <br clear="left">
                                <?php } ?>
                            </td>
                        </tr>
                        <input type="hidden" name="free_title[]"
                               value="<?php echo $service_input->free_title[1] ?>">
                        <input type="hidden" name="free_data[]"
                               value="<?php echo $service->htmlToString($service_input->free_data[1]) ?>">
                        <input type="hidden" name="free_no[]" value="2">
                        <tr id="free_data3"
                            class="<?php echo (($service_input->free_data[2]) == "") ? 'empty-item' : '' ?>">
                            <td class="pre_table_i_noline" name="item_103_title">
                                <?php echo $service_input->free_title[2] ?>
                            </td>
                            <td class="pre_table_d">
                                <?php if (!empty($_POST['item_103_file1'])) { ?>
                                    <?php $file_item1_free_dir = !empty($_POST['item_103_file1_dir']) ? $_POST['item_103_file1_dir'] : "/temp/{$tg_id}"; ?>
                                    <div style="text-align: <?php echo $_POST['item_103_situation1'] ?>">
                                        <img src="<?php echo $service->getServiceDisplayFile($_POST['item_103_file1'], $_POST['item_103_re_file1'], $file_item1_free_dir); ?>"
                                             title="画像"
                                             style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                    </div>
                                    <br clear="left">
                                <?php } ?>
                                <input type="hidden" name="item_103_file1"
                                       value="<?= $input_data["item_103_file1"] ?>">
                                <input type="hidden" name="item_103_re_file1"
                                       value="<?= $input_data["item_103_re_file1"] ?>">
                                <input type="hidden" name="item_103_situation1"
                                       value="<?= $input_data["item_103_situation1"] ?>">
                                <input type="hidden" name="item_103_file1_dir"
                                       value="<?= $input_data["item_103_file1_dir"] ?>">
                                <?php echo $service_input->free_data[2] ?>
                                <input type="hidden" name="item_103_file2"
                                       value="<?= $input_data["item_103_file2"] ?>">
                                <input type="hidden" name="item_103_re_file2"
                                       value="<?= $input_data["item_103_re_file2"] ?>">
                                <input type="hidden" name="item_103_situation2"
                                       value="<?= $input_data["item_103_situation2"] ?>">
                                <input type="hidden" name="item_103_file2_dir"
                                       value="<?= $input_data["item_103_file2_dir"] ?>">
                                <?php if (!empty($_POST['item_103_file2'])) { ?>
                                    <div style="text-align: <?php echo $_POST['item_103_situation2'] ?>">
                                        <?php $file_item2_free_dir = !empty($_POST['item_103_file2_dir']) ? $_POST['item_103_file2_dir'] : "/temp/{$tg_id}"; ?>
                                        <img src="<?php echo $service->getServiceDisplayFile($_POST['item_103_file2'], $_POST['item_103_re_file2'], $file_item2_free_dir); ?>"
                                             title="画像"
                                             style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                    </div>
                                    <br clear="left">
                                <?php } ?>
                            </td>
                        </tr>
                        <input type="hidden" name="free_title[]"
                               value="<?php echo $service_input->free_title[2] ?>">
                        <input type="hidden" name="free_data[]"
                               value="<?php echo $service->htmlToString($service_input->free_data[2]) ?>">
                        <input type="hidden" name="free_no[]" value="3">
                        <tr id="free_data4"
                            class="<?php echo (($service_input->free_data[3]) == "") ? 'empty-item' : '' ?>">
                            <td class="pre_table_i_noline" name="item_104_title">
                                <?php echo $service_input->free_title[3] ?>
                            </td>
                            <td class="pre_table_d">
                                <?php if (!empty($_POST['item_104_file1'])) { ?>
                                    <div style="text-align: <?php echo $_POST['item_104_situation1'] ?>">
                                        <?php $file_item1_free_dir = !empty($_POST['item_104_file1_dir']) ? $_POST['item_104_file1_dir'] : "/temp/{$tg_id}"; ?>
                                        <img src="<?php echo $service->getServiceDisplayFile($_POST['item_104_file1'], $_POST['item_104_re_file1'], $file_item1_free_dir); ?>"
                                             title="画像"
                                             style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                    </div>
                                    <br clear="left">
                                <?php } ?>
                                <input type="hidden" name="item_104_file1"
                                       value="<?= $input_data["item_104_file1"] ?>">
                                <input type="hidden" name="item_104_re_file1"
                                       value="<?= $input_data["item_104_re_file1"] ?>">
                                <input type="hidden" name="item_104_situation1"
                                       value="<?= $input_data["item_104_situation1"] ?>">
                                <input type="hidden" name="item_104_file1_dir"
                                       value="<?= $input_data["item_104_file1_dir"] ?>">
                                <?php echo $service_input->free_data[3] ?>
                                <input type="hidden" name="item_104_file2"
                                       value="<?= $input_data["item_104_file2"] ?>">
                                <input type="hidden" name="item_104_re_file2"
                                       value="<?= $input_data["item_104_re_file2"] ?>">
                                <input type="hidden" name="item_104_situation2"
                                       value="<?= $input_data["item_104_situation2"] ?>">
                                <input type="hidden" name="item_104_file2_dir"
                                       value="<?= $input_data["item_104_file2_dir"] ?>">
                                <?php if (!empty($_POST['item_104_file2'])) { ?>
                                    <div style="text-align: <?php echo $_POST['item_104_situation2'] ?>">
                                        <?php $file_item2_free_dir = !empty($_POST['item_104_file2_dir']) ? $_POST['item_104_file2_dir'] : "/temp/{$tg_id}"; ?>
                                        <img src="<?php echo $service->getServiceDisplayFile($_POST['item_104_file2'], $_POST['item_104_re_file2'], $file_item2_free_dir); ?>"
                                             title="画像"
                                             style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                    </div>
                                    <br clear="left">
                                <?php } ?>
                            </td>
                        </tr>
                        <input type="hidden" name="free_title[]"
                               value="<?php echo $service_input->free_title[3] ?>">
                        <input type="hidden" name="free_data[]"
                               value="<?php echo $service->htmlToString($service_input->free_data[3]) ?>">
                        <input type="hidden" name="free_no[]" value="4">
                        <tr id="free_data5"
                            class="<?php echo (($service_input->free_data[4]) == "") ? 'empty-item' : '' ?>">
                            <td class="pre_table_i_noline" name="item_105_title">
                                <?php echo $service_input->free_title[4] ?>
                            </td>
                            <td class="pre_table_d">
                                <?php if (!empty($_POST['item_105_file1'])) { ?>
                                    <div style="text-align: <?php echo $_POST['item_105_situation1'] ?>">
                                        <?php $file_item1_free_dir = !empty($_POST['item_105_file1_dir']) ? $_POST['item_105_file1_dir'] : "/temp/{$tg_id}"; ?>
                                        <img src="<?php echo $service->getServiceDisplayFile($_POST['item_105_file1'], $_POST['item_105_re_file1'], $file_item1_free_dir); ?>"
                                             title="画像"
                                             style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                    </div>
                                    <br clear="left">
                                <?php } ?>
                                <input type="hidden" name="item_105_file1"
                                       value="<?= $input_data["item_105_file1"] ?>">
                                <input type="hidden" name="item_105_re_file1"
                                       value="<?= $input_data["item_105_re_file1"] ?>">
                                <input type="hidden" name="item_105_situation1"
                                       value="<?= $input_data["item_105_situation1"] ?>">
                                <input type="hidden" name="item_105_file1_dir"
                                       value="<?= $input_data["item_105_file1_dir"] ?>">
                                <?php echo $service_input->free_data[4] ?>
                                <input type="hidden" name="item_105_file2"
                                       value="<?= $input_data["item_105_file2"] ?>">
                                <input type="hidden" name="item_105_re_file2"
                                       value="<?= $input_data["item_105_re_file2"] ?>">
                                <input type="hidden" name="item_105_situation2"
                                       value="<?= $input_data["item_105_situation2"] ?>">
                                <input type="hidden" name="item_105_file2_dir"
                                       value="<?= $input_data["item_105_file2_dir"] ?>">
                                <?php if (!empty($_POST['item_105_file2'])) { ?>
                                    <div style="text-align: <?php echo $_POST['item_105_situation2'] ?>">
                                        <?php $file_item2_free_dir = !empty($_POST['item_105_file2_dir']) ? $_POST['item_105_file2_dir'] : "/temp/{$tg_id}"; ?>
                                        <img src="<?php echo $service->getServiceDisplayFile($_POST['item_105_file2'], $_POST['item_105_re_file2'], $file_item2_free_dir); ?>"
                                             title="画像"
                                             style="margin:5px 0px 5px 0px;padding-top:10px;padding-right:10px;padding-bottom:10px;">
                                    </div>
                                    <br clear="left">
                                <?php } ?>
                            </td>
                        </tr>
                        <input type="hidden" name="free_title[]"
                               value="<?php echo $service_input->free_title[4] ?>">
                        <input type="hidden" name="free_data[]"
                               value="<?php echo $service->htmlToString($service_input->free_data[4]) ?>">
                        <input type="hidden" name="free_no[]" value="5">
                        <td class="pre_table_i_noline">
                            お問合せ先
                        </td>
                        <td class="pre_table_d">
                            <?php if (!empty($input_data['m_img'])) { ?>
                                <?php $contact_img_dir = !empty($_POST['m_img_dir']) ? $_POST['m_img_dir'] : "/temp/{$tg_id}"; ?>
                                <img src="<?php echo $service->getServiceDisplayFile($input_data['m_img'], '', $contact_img_dir); ?>"
                                     align="right" style="padding-top:10px;padding-right:10px;" width="250px">
                            <?php } ?>
                            <?= nl2br($input_data["service_inq"]) ?>
                            <br>
                            TEL:&nbsp;<?= $input_data["service_tel"] ?>
                            <br>
                            E-mail:&nbsp;<a
                                    href="mailto:<?= $input_data["service_mail"] ?>"><?= $input_data["service_mail"] ?></a>
                            <br>
                            <table class="pre_font_n" style="margin:5px 0px 0px 0px;">
                                <tr>
                                    <td>掲載日</td>
                                    <td><?= $input_data["releaseFrom"] ?></td>
                                </tr>
                                <tr>
                                    <td>掲載期限</td>
                                    <td><?= $input_data["releaseTo"] ?></td>
                                </tr>
                            </table>

                            <input type="hidden" name="service_inq"
                                   value="<?= $service->htmlToString($input_data["service_inq"]) ?>">
                            <input type="hidden" name="service_tel" value="<?= $input_data["service_tel"] ?>">
                            <input type="hidden" name="service_mail" value="<?= $input_data["service_mail"] ?>">
                            <input type="hidden" name="m_img" value="<?= $input_data["m_img"] ?>">
                            <input type="hidden" name="m_imgOld" value="<?= $input_data["m_imgOld"] ?>">
                            <input type="hidden" name="m_imgRegFlg" value="<?= $input_data["m_imgRegFlg"] ?>">
                            <input type="hidden" name="m_img_dir" value="<?= $input_data["m_img_dir"] ?>">
                        </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="table_btn" align="center">
            <tr>
                <td align="right">
                    <input align="right" type="button" class="btnClass" value=" 戻　る " onclick="cancel()">
                    <input align="right" type="button" class="btnClass" value="一時保存" onclick="move_next2()">
                    <input align="right" type="button" class="btnClass" value=" 申　請 " onclick="move_next()">
                </td>
            </tr>
        </table>
        <input type="hidden" name="shinsei_name" value="<?= $request_service_applicant->ORG_NAME ?>">
        <input type="hidden" name="shinsei_busho" value="<?= $request_service_applicant->AFFILIATION_NAME ?>">
        <input type="hidden" name="shinsei_yaku" value="<?= $request_service_applicant->OFFICIAL_POSITION ?>">
        <input type="hidden" name="shinsei_shimei" value="<?= $request_service_applicant->PER_NAME ?>">
        <input type="hidden" name="shinsei_tel" value="<?= $request_service_applicant->G_TEL ?>">
        <input type="hidden" name="shinsei_mail" value="<?= $request_service_applicant->C_EMAIL ?>">
        <input type="hidden" name="s_kind" value="<?= $s_kind ?>">
        <input type="hidden" name="mode" value="">
        <input type="hidden" name="g_id" value="dmshibuyablock01">
        <input type="hidden" name="a_id" value="dmshibuyag_none">
        <input type="hidden" name="forward_mail" value="<?= $input_data["service_mail"] ?>">
        <input type="hidden" name="page_no" value="<?= $page_no ?>">
        <input type="hidden" name="service_type" value="<?= $s_kind ?>">
        <input type="hidden" name="notice_flg" value="<?= $input_data['send_mail'] ?>">
        <input type="hidden" name="reason_flg" value="">
        <input type="hidden" name="list_disp_g_name" value="<?= $input_data["list_disp_g_name"] ?>">
        <input type="hidden" name="svc_id" value="<?= $svc_id ?>">
        <input type="hidden" name="reg_svcid" value="<?= $reg_svc_id ?>">
        <input type="hidden" name="reg_svcinfono" value="<?= $reg_svc_info_no ?>">
        <input type="hidden" name="fileName" value="<?= $file_name ?>">
        <input type="hidden" name="tempFlg" value="">
        <input type="hidden" name="sendMail" value="">
        <input type="hidden" name="judge_result" value="">
        <input type="hidden" name="top_g_id" value="<?= $tg_id ?>">
        <input type="hidden" name="chg_svc_id" value="">
        <input type="hidden" name="img_no" value="">
        <input type="hidden" name="svc_info_no" value="<?= $_POST['svc_info_no'] ?>">
        <input type="hidden" name="svc_req_p_id" value="<?= $p_id ?>">
        <input type="hidden" name="post_id" value="<?= $post_id ?>">
        <input type="hidden" name="type" value="<?= $type ?>">
    </form>
    <form name="backForm" id="backForm">
        <input type="hidden" name="s_kind" value="<?= $s_kind ?>">
        <input type="hidden" name="page_no" value="<?= $page_no ?>">
        <input type="hidden" name="top_g_id" value="<?= $tg_id ?>">
        <input type="hidden" name="svc_req_p_id" value="<?= $p_id ?>">
        <input type="hidden" name="g_id" value="dmshibuyablock01">
        <input type="hidden" name="a_id" value="dmshibuyag_none">
        <input type="hidden" name="service_url" value="">
        <input type="hidden" name="svc_id" value="<?= $svc_id ?>">
        <input type="hidden" name="reg_svcid" value="<?= $reg_svc_id ?>">
        <input type="hidden" name="reg_svcinfono" value="<?= $reg_svc_info_no ?>">
        <input type="hidden" name="svc_info_no" value="<?= $_POST['svc_info_no'] ?>">
        <input type="hidden" name="post_id" value="<?= $post_id ?>">
        <input type="hidden" name="send_mail" value="<?= $input_data['send_mail'] ?>">
        <input type="hidden" name="fileName" value="<?= $file_name ?>">
        <input type="hidden" name="func" value="back">
        <input type="hidden" name="type" value="<?= $type ?>">
        <input type="hidden" name="m_img" value="<?= $input_data["m_img"] ?>">
        <input type="hidden" name="m_imgOld" value="<?= $input_data["m_imgOld"] ?>">
        <input type="hidden" name="m_imgRegFlg" value="<?= $input_data["m_imgRegFlg"] ?>">
        <input type="hidden" name="m_img_dir" value="<?= $input_data["m_img_dir"] ?>">
    </form>
    </body>

<?php } ?>
