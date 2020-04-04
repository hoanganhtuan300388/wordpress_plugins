<?php
if (!isset($_SESSION['arrSession'])) {
    include('service_session_error.php');
    exit;
} else {
    $service = new serviceController();
    $tg_id = isset($_POST['top_g_id']) ? $_POST['top_g_id'] : "";
    $svc_id = isset($_POST['svc_id']) ? $_POST['svc_id'] : 0;
    $p_id = isset($_POST['svc_req_p_id']) ? $_POST['svc_req_p_id'] : "";
    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : "";
    $page_no = isset($_POST['page_no']) ? $_POST['page_no'] : 0;
    $input_data = $_SESSION['service_input'];
    $item_title = isset($_POST['item_title']) ? $_POST['item_title'] : [];
    $free_title = isset($_POST['free_title']) ? $_POST['free_title'] : [];
    $service_item = isset($_POST['service_item']) ? $_POST['service_item'] : [];
    $free_data = isset($_POST['free_data']) ? $_POST['free_data'] : [];
    $free_no = isset($_POST['free_no']) ? $_POST['free_no'] : [];
    $svc_info_no = isset($_POST['svc_info_no']) ? $_POST['svc_info_no'] : 0;
    $type = isset($_POST['type']) ? $_POST['type'] : '';

//$svc_nms = array_merge($item_title,$free_title) ;
//$svc_value = array_merge($service_item,$free_data);

    $arrBody = array();
    $arrBody["TG_ID"] = $tg_id;
    $arrBody["SVC_ID"] = $svc_id;
    $arrBody["R_USER"] = $_SESSION['arrSession']->USERID;
    $arrBody["U_USER"] = $_SESSION['arrSession']->P_ID;
    $arrBody["SVC_REQ_LG_TYPE"] = $_SESSION['arrSession']->LG_TYPE;
    $arrBody["SVC_REQ_LG_ID"] = $_SESSION['arrSession']->LG_ID;
    $arrBody["SVC_REQ_P_ID"] = $_SESSION['arrSession']->P_ID;
    $arrBody["SVC_REQ_G_ID"] = $_SESSION['arrSession']->G_ID;
    $arrBody["SVC_REQ_REASON"] = "";
    $arrBody["SVC_REQ_DATE"] = $input_data->releaseTo;;
    $arrBody["POST_TITLE"] = $input_data->service_title;
    $arrBody["POST_G_NAME"] = $input_data->list_disp_g_name;
    $arrBody["POST_START_DATE"] = $input_data->releaseFrom;
    $arrBody["POST_START_TIME"] = $input_data->timeFrom;
    $arrBody["POST_END_DATE"] = $input_data->releaseTo;
    $arrBody["POST_END_TIME"] = $input_data->timeTo;
    $arrBody["POST_URL"] = "";
    $arrBody["NOTICE"] = $_POST['sendMail']!="" ?1:0 ;
    $arrBody["SEND_NOTICE_FLG"] =  $_POST['notice_flg']==1?1:0 ;
    $arrBody["CONTACT_NAME"] = $_POST['service_inq'];
    $arrBody["CONTACT_TEL"] = $_POST['service_tel'];
    $arrBody["CONTACT_MAIL"] = $_POST['service_mail'];
    $arrBody["CONTACT_IMG"] = $_POST['m_img'];
    $arrBody["CONTACT_IMG_DIR"] = $_POST['m_img_dir'];
    $arrBody["JUDGE_RESULT"] = 0;
    $nakama_contact_mail = get_post_meta($_POST['post_id'], 'nakama_service_param_contact_mail');
    $arrBody["CONFIG_CONTACT_MAIL"] = $nakama_contact_mail[0];
    $arrBody["ENV"] = $service->getEnv();
    $arrBodyItem = array();
    $arrBodyItem ["TG_ID"] = $tg_id;
    $arrBodyItem ["SVC_ID"] = $svc_id;

    $arrBodyItem  ["R_USER"] = $_SESSION['arrSession']->USERID;
    $arrBodyItem  ["U_USER"] = $_SESSION['arrSession']->USERID;
    $arrBodyItem['ServiceItemList'] = array();
    $button_back = "no_back";

    for ($i = 0; $i < sizeof($service_item); $i++) {
        if ($service_item[$i] != "") {
            $arrBodyItem['ServiceItemList'][$i]["SVC_ITEM_NO"] = $i + 1;
            $arrBodyItem['ServiceItemList'][$i]["SVC_ITEM_NM"] = $item_title[$i];
            $arrBodyItem['ServiceItemList'][$i]["SVC_ITEM_VALUE"] = $service_item[$i];
            $arrBodyItem['ServiceItemList'][$i]["FILE1"] = $_POST['item_' . ($i + 1) . '_file1'];
            $arrBodyItem['ServiceItemList'][$i]["FILE1_DIR"] = $_POST['item_' . ($i + 1) . '_file1_dir'];
            $arrBodyItem['ServiceItemList'][$i]["RE_FILE1"] = $_POST['item_' . ($i + 1) . '_re_file1'];
            $arrBodyItem['ServiceItemList'][$i]["SITUATION1"] = $_POST['item_' . ($i + 1) . '_situation1'];
            $arrBodyItem['ServiceItemList'][$i]["FILE2"] = $_POST['item_' . ($i + 1) . '_file2'];
            $arrBodyItem['ServiceItemList'][$i]["RE_FILE2"] = $_POST['item_' . ($i + 1) . '_re_file2'];
            $arrBodyItem['ServiceItemList'][$i]["SITUATION2"] = $_POST['item_' . ($i + 1) . '_situation2'];
            $arrBodyItem['ServiceItemList'][$i]["FILE2_DIR"] = $_POST['item_' . ($i + 1) . '_file2_dir'];
        }
    }

    for ($j = 0; $j < sizeof($free_data); $j++) {
        if ($free_data[$j] != "") {
            $item_no = $free_no[$j];
            $arrBodyItem['ServiceItemFreeList'][$j]["SVC_ITEM_NO"] = 100 + (int)$item_no;
            $arrBodyItem['ServiceItemFreeList'][$j]["SVC_ITEM_NM"] = $free_title[$j];
            $arrBodyItem['ServiceItemFreeList'][$j]["SVC_ITEM_VALUE"] = $free_data[$j];
            $arrBodyItem['ServiceItemFreeList'][$j]["FILE1"] = $_POST['item_10' . $item_no . '_file1'];
            $arrBodyItem['ServiceItemFreeList'][$j]["RE_FILE1"] = $_POST['item_10' . $item_no . '_re_file1'];
            $arrBodyItem['ServiceItemFreeList'][$j]["SITUATION1"] = $_POST['item_10' . $item_no . '_situation1'];
            $arrBodyItem['ServiceItemFreeList'][$j]["FILE1_DIR"] = $_POST['item_10' . $item_no . '_file1_dir'];
            $arrBodyItem['ServiceItemFreeList'][$j]["FILE2"] = $_POST['item_10' . $item_no . '_file2'];
            $arrBodyItem['ServiceItemFreeList'][$j]["RE_FILE2"] = $_POST['item_10' . $item_no . '_re_file2'];
            $arrBodyItem['ServiceItemFreeList'][$j]["SITUATION2"] = $_POST['item_10' . $item_no . '_situation2'];
            $arrBodyItem['ServiceItemFreeList'][$j]["FILE2_DIR"] = $_POST['item_10' . $item_no . '_file2_dir'];
        }
    }
    $arrBodyItem['ServiceItemList'] = array_values($arrBodyItem['ServiceItemList']);
    $arrBodyItem['ServiceItemFreeList'] = $arrBodyItem['ServiceItemFreeList'] ? array_values($arrBodyItem['ServiceItemFreeList']) : [];
  
    if ($type != 'update') {
        $request_service_list = $service->addService($post_id, $arrBody);
        //var_dump($post_id,$arrBody,$request_service_list);
        $req_svc_info_no = $request_service_list->REG_SVC_INFO_NO;
        $arrBodyItem ["SVC_INFO_NO"] = (int)$req_svc_info_no;
        $response = $service->addServiceItem($post_id, $arrBodyItem);
        $response_free = $service->addServiceItemFree($post_id, $arrBodyItem);
        if($response_free->status =="success"){
            $button_back = "back";
        }
    } else {
        $arrBody ["SVC_INFO_NO"] = $_POST['svc_info_no'];
        $arrBodyItem ["SVC_INFO_NO"] = $_POST['svc_info_no'];
        $up_service_list = $service->upService($post_id, $arrBody);
        $response_up = $service->upServiceItem($post_id, $arrBodyItem);
        $response_up_free = $service->upServiceItemFree($post_id, $arrBodyItem);
        if($response_up_free->status =="success"){
            $button_back = "back";
        }
    }

    ?>

    <script type="text/javascript">
        <!--
        function move_return() {

            var form = document.backForm;
            // form.method = "post";
            form.action = "<?= get_permalink(get_page_by_path('nakama-request-service-list')->ID); ?>";
            form.submit();
        }

        //-->
    </script>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE11" />
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/default.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/req_service.css"/>
    <body onload="reload_page()">
    <form name="mainForm" id="mainForm">
        <table class="header" align="center">
            <tr>
                <td>■ビジネス&nbsp;入力完了</td>
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
			<img src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/step/step.png">
			<span class="progress_i">申請内容確認</span>
		</span>
            <span style="position: relative;">
			<img src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/step/step_a.png">
			<span class="progress_i">申請完了</span>
		</span>
        </div>
        <table class="header" align="center" width="300" border="0">
            <tr>
                <td class="table_i">
                    サービス情報申請が完了しました。
                </td>
            </tr>
        </table>
        <br>
        <p align="center">
            <input type="button" class="btnClass" id="back"  value="戻る" onclick="move_return();" disabled >
        </p>
        <input type="hidden" name="forward_mail" value="">
    </form>
    <form id="backForm" name="backForm" method="" action="">
        <input type="hidden" name="page_id" value="<?= get_page_by_path('nakama-request-service-list')->ID ?>">
        <input type="hidden" name="page_no" value="<?= $page_no ?>">
        <input type="hidden" name="top_g_id" value="<?= $tg_id ?>">
        <input type="hidden" name="p_id" value="<?= $p_id ?>">
        <input type="hidden" name="post_id" value="<?= $post_id ?>">
    </form>
    <script>
        function reload_page() {
            if('<?=$button_back?>'=='back'){
                document.getElementById('back').disabled = false
            }
        }
    </script>
    </body>

<?php } ?>