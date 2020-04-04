<?php
if (!isset($_SESSION['arrSession'])) {
    include('service_session_error.php');
    exit;
} else {
    $service = new serviceController();
    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : "";
    $tg_id = isset($_POST['top_g_id']) ? $_POST['top_g_id'] : "";
    $svc_id = isset($_POST['svc_id']) ? $_POST['svc_id'] : "";
    $p_id = isset($_POST['svc_req_p_id']) ? $_POST['svc_req_p_id'] : "";
    $text_edit = isset($_POST['text_editor']) ? $_POST['text_editor'] : "";
    $situation = isset($_POST['situation']) ? $_POST['situation'] : "";
    $s_kind = isset($_POST['s_kind']) ? $_POST['s_kind'] : '';
    $shinsei_name = isset($_POST['shinsei_name']) ? $_POST['shinsei_name'] : '';
    $shinsei_busho = isset($_POST['shinsei_busho']) ? $_POST['shinsei_busho'] : '';
    $shinsei_yaku = isset($_POST['shinsei_yaku']) ? $_POST['shinsei_yaku'] : '';
    $shinsei_shimei = isset($_POST['shinsei_shimei']) ? $_POST['shinsei_shimei'] : '';
    $shinsei_tel = isset($_POST['shinsei_tel']) ? $_POST['shinsei_tel'] : '';
    $shinsei_mail = isset($_POST['shinsei_mail']) ? $_POST['shinsei_mail'] : '';
    $g_id = isset($_POST['g_id']) ? $_POST['g_id'] : '';
    $page_no = isset($_POST['page_no']) ? $_POST['page_no'] : '';
    $reg_svc_id = isset($_POST['reg_svcid']) ? $_POST['reg_svcid'] : "";    //編集対象サービスID
    $reg_svc_info_no = isset($_POST['reg_svcinfono']) ? $_POST['reg_svcinfono'] : "";
    $file_name = isset($_POST['file_name']) ? $_POST['file_name'] : "";
    $dataSetting = get_post_meta($post_id);
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    if (isset($_SESSION['auth'])) {
        $p_id = isset($_SESSION['auth']['p_id']) ? $_SESSION['auth']['p_id'] : "";
    }

    if (isset($_POST['func']) && isset($_SESSION['service_input'])) {
        $service_input = $_SESSION['service_input'];
    }
    //  var_dump($_POST);
    $arrBody = array();
    if (!isset($_POST['func'])) {
        $_SESSION['service_select'] = $s_kind;
    }
//    unset($_SESSION['service_input']);
    $arrBody['TG_ID'] = (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->TG_ID : $dataSetting['nakama_service_param_tg_id'][0];
    if ($type == "update") {
        $service_input = json_decode(html_entity_decode(stripslashes(htmlspecialchars($_POST['service_input']))));
    }
    $service_type = "";

//申請者情報を取得
    $request_service_applicant = $service->getServiceApplicant($post_id, $arrBody);

//申請サービス情報掲載項目データを取得
    $request_service_item = $service->getApplyServiceItem($post_id, $arrBody);

//申請サービス情報掲載項目データ（任意項目）を取得
    $request_service_item_free = $service->getApplyServiceItemFree($post_id, $arrBody);
    $arr = array();
    $arr['TG_ID'] = (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->TG_ID : $dataSetting['nakama_service_param_tg_id'][0];
    $arr['SVC_ID'] = $svc_id;

    $MServiceItemList = $service->getMServiceItem($post_id, $arr);
    $MServiceItem = $MServiceItemList->data;
    ?>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE11"/>
    <script src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>/assets/js/jquery-1.6.3.min.js"
            type="text/javascript"></script>
    <script src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>/assets/js/common.js" type="text/javascript"></script>
    <script src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>/assets/js/calendarlay.js"
            type="text/javascript"></script>
    <script type="text/javascript">
        <!--

        var msg_link = '<a href="ここにリンク先を記述して下さい" target="_blank">ここに画面に表示する文章を記述して下さい</a>';
        var msg_mail = '<a href="mailto:ここにメールアドレスを記述して下さい">ここに画面に表示する文章を記述して下さい</a>';

        $(function () {

            $.fn.extend({
                insertAtCaret: function (v) {
                    var o = this.get(0);
                    o.focus();
                    if (jQuery.browser.msie) {
                        var r = document.selection.createRange();
                        r.text = v;
                        r.select();
                    } else {
                        var s = o.value;
                        var p = o.selectionStart;
                        var np = p + v.length;
                        o.value = s.substr(0, p) + v + s.substr(p);
                        o.setSelectionRange(np, np);
                    }
                }
            });

            $(".confLinkTag").click(function () {
                for (var i = 0; i < $('.confLinkTag').length; i++) {
                    if ($('.confLinkTag')[i].id == this.id) {
                        $('#service_item' + (i + 1)).insertAtCaret(msg_link);
                    }
                }
            });
            $(".confMailTag").click(function () {
                for (var i = 0; i < $('.confMailTag').length; i++) {
                    if ($('.confMailTag')[i].id == this.id) {
                        $('#service_item' + (i + 1)).insertAtCaret(msg_mail);
                    }
                }
            });
            $(".freeLinkTag").click(function () {
                for (var i = 0; i < $('.freeLinkTag').length; i++) {
                    if ($('.freeLinkTag')[i].id == this.id) {
                        $('#free_data' + (i + 1)).insertAtCaret(msg_link);
                    }
                }
            });
            $(".freeMailTag").click(function () {
                for (var i = 0; i < $('.freeMailTag').length; i++) {
                    if ($('.freeMailTag')[i].id == this.id) {
                        $('#free_data' + (i + 1)).insertAtCaret(msg_mail);
                    }
                }
            });
            $(".inqLinkTag").click(function () {
                $('#inq_data').insertAtCaret(msg_link);
            });
            $(".inqMailTag").click(function () {
                $('#inq_data').insertAtCaret(msg_mail);
            });
        });


        function cancel() {
            var form = document.backForm;
            // form.method = "post";
            form.target = "_self";
            form.action = "<?php echo get_permalink(get_page_by_path('nakama-request-service-select')->ID); ?>";
            form.submit();
        }


        function move_next() {

            if (chkInput() == false) {
                return;
            }
            let service_item = [];
            let free_data = [];
            let free_title = [];
            let item_image = [];
            let item_image_free = [];
            var form = document.mainForm;
            var service_items = document.getElementsByName('service_item[]');
            var free_titles = document.getElementsByName('free_title[]');
            var free_datas = document.getElementsByName('free_data[]');
            var valTypes = document.getElementsByName('valType[]')
            let service_date_time = [];
            for (let i = 0; i < service_items.length; i++) {
                service_item[i] = service_items[i].value
            }

            for (let i = 0; i < free_datas.length; i++) {
                free_data[i] = free_datas[i].value
            }
            for (let i = 0; i < free_titles.length; i++) {
                free_title[i] = free_titles[i].value
            }

            for (let i = 0; i < service_items.length; i++) {
                item_image[i] = {
                    'FILE1': document.getElementsByName('item_' + (i + 1) + '_file1')[0].value,
                    'RE_FILE1': document.getElementsByName('item_' + (i + 1) + '_re_file1')[0].value,
                    'SITUATION1': document.getElementsByName('item_' + (i + 1) + '_situation1')[0].value,
                    'FILE1_DIR': document.getElementsByName('item_' + (i + 1) + '_file1_dir')[0].value,
                    'FILE2': document.getElementsByName('item_' + (i + 1) + '_file2')[0].value,
                    'RE_FILE2': document.getElementsByName('item_' + (i + 1) + '_re_file2')[0].value,
                    'SITUATION2': document.getElementsByName('item_' + (i + 1) + '_situation2')[0].value,
                    'FILE2_DIR': document.getElementsByName('item_' + (i + 1) + '_file2_dir')[0].value,
                };

                if (valTypes[i].value == "DATE") {
                    let date = document.getElementsByName('service_date_item' + (i + 1))[0].value;
                    let time = document.getElementsByName('service_time_item' + (i + 1))[0].value;
                    let item = {
                        'date': date,
                        'time': time,
                        'des_date': service_items[i].value
                    };
                    service_date_time[i] = item
                }
            }

            for (let i = 0; i < free_datas.length; i++) {
                item_image_free[i] = {
                    'FILE1': document.getElementsByName('item_' + (i + 101) + '_file1')[0].value,
                    'RE_FILE1': document.getElementsByName('item_' + (i + 101) + '_re_file1')[0].value,
                    'SITUATION1': document.getElementsByName('item_' + (i + 101) + '_situation1')[0].value,
                    'FILE1_DIR': document.getElementsByName('item_' + (i + 101) + '_file1_dir')[0].value,
                    'FILE2': document.getElementsByName('item_' + (i + 101) + '_file2')[0].value,
                    'RE_FILE2': document.getElementsByName('item_' + (i + 101) + '_re_file2')[0].value,
                    'SITUATION2': document.getElementsByName('item_' + (i + 101) + '_situation2')[0].value,
                    'FILE2_DIR': document.getElementsByName('item_' + (i + 101) + '_file2_dir')[0].value,
                }
            }
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
                send_mail: form.send_mail ? form.send_mail.value : 0,
                item_image_free: item_image_free,
                item_image: item_image,
                service_date_time: service_date_time
            };

            // localStorage.setItem('service_input', JSON.stringify(service_input));
            // console.log(service_input)
            form.service_input.value = JSON.stringify(service_input);
            form.method = "post";
            form.action = "<?php echo get_permalink(get_page_by_path('nakama-request-service-confirm')->ID); ?>";
            form.submit();
        }

        function chkInput() {

            var data1;
            var data2;
            var form = document.mainForm;

            var chkMoji = "";

            var service_items = document.getElementsByName('service_item[]');
            var free_titles = document.getElementsByName('free_title[]');
            var free_datas = document.getElementsByName('free_data[]');
            var valMust = document.getElementsByName('valMust[]');
            var valType = document.getElementsByName('valType[]');
            var valLength = document.getElementsByName('valLength[]');
            var item_title = document.getElementsByName('item_title[]');
            // console.log(service_items);
            for (var i = 0; i < service_items.length; i++) {
                if (document.getElementById('disp_service_item' + (i + 1).innerText != "[未入力]")) {
                    service_items[i].value = document.getElementById('disp_service_item' + (i + 1)).innerText;
                }
                if (service_items[i].value.length > valLength[i].value) {
                    alert(item_title[i].value + "は、" + valLength[i].value + "文字以内で入力して下さい。");
                    service_items[i].focus();
                    return false;
                }

                chkMoji = service_items[i].value;
                if (chkMoji.length > 0) {
                    if (chkMoji.indexOf("<a") >= 0) {
                        if (chkMoji.split("<a").length != chkMoji.split("/a>").length) {
                            alert(item_title[i].value + "に入力されているリンクタグに誤りがあります。");
                            service_items[i].focus();
                            return false;
                        }
                    }
                }

                if (!ScriptInputCheck(item_title[i].value, item_title[i].value, item_title[i])) {
                    return false;
                }
                if (!ScriptInputCheck(service_items[i].value, item_title[i].value, service_items[i])) {
                    return false;
                }

                if (valType[i].value == 'DATE') {
                    var obj = document.getElementsByName("service_date_item" + (i + 1))[0];
                    if (valMust[i].value == 1) {
                        if (Trim(obj.value) == "") {
                            alert(item_title[i].value + "は必須入力です。");
                            obj.focus();
                            return false;
                        }
                    }
                    if (obj.value != "") {
                        if (obj.value.indexOf("/") != -1) {
                            var ymd = obj.value.split("/");
                            if (ymd.length != 3) {
                                alert(item_title[i].value + "は、YYYY/MM/DD形式で入力して下さい。");
                                obj.focus();
                                return false;
                            } else {
                                if (isDate(ymd[0], ymd[1], ymd[2]) == false) {
                                    alert(item_title[i].value + "は、YYYY/MM/DD形式で入力して下さい。");
                                    obj.focus();
                                    return false
                                }
                            }
                        } else {
                            alert(item_title[i].value + "は、YYYY/MM/DD形式で入力して下さい。");
                            obj.focus();
                            return false
                        }
                    }
                } else {
                    if (Trim(service_items[i].value) == "" && valMust[i].value == 1) {
                        alert(item_title[i].value + "は必須入力です。");
                        service_items[i].focus();
                        return false;
                    }
                }
            }

            for (var i = 0; i < free_titles.length; i++) {
                if ((Trim(free_titles[i].value) != "") || (Trim(free_datas[i].value) != "")) {

                    if (Trim(free_titles[i].value) == "") {
                        alert("任意入力" + (i + 1) + "タイトルを入力して下さい。");
                        free_titles[i].focus();
                        return false;
                    } else {
                        if (free_titles[i].value.length > 500) {
                            alert("任意入力" + (i + 1) + "タイトルは500文字以内で入力して下さい。");
                            free_titles[i].focus();
                            return false;
                        }

                        if (!ScriptInputCheck(free_titles[i].value, free_titles[i].value, free_titles[i])) {
                            return false;
                        }
                    }


                    if (Trim(free_datas[i].value) == "") {
                        alert("任意入力" + (i + 1) + "データを入力して下さい。");
                        free_datas[i].focus();
                        return false;
                    } else {
                        if (free_datas[i].value.length > 4000) {
                            alert("任意入力" + (i + 1) + "データは4000文字以内で入力して下さい。");
                            free_datas[i].focus();
                            return false;
                        }

                        chkMoji = free_datas[i].value;
                        if (chkMoji.indexOf("<a") > 0) {
                            if (chkMoji.split("<a").length != chkMoji.split("</a>").length) {
                                alert("任意入力" + (i + 1) + "データのリンクタグ入力に誤りがあります。");
                                free_datas[i].focus();
                                return false;
                            }
                        }

                        if (!ScriptInputCheck(free_datas[i].value, free_titles[i].value, free_datas[i])) {
                            return false;
                        }

                    }
                }
            }

            if (Trim(form.service_title.value) == '') {
                alert("タイトルは必須入力です。");
                form.service_title.focus();
                return false;
            }

            if (!ScriptInputCheck(form.service_title.value, "タイトル", form.service_title)) {
                return false;
            }

            if (Trim(form.list_disp_g_name.value) == '') {
                alert("会社名(一覧表示用)は必須入力です。");
                form.list_disp_g_name.focus();
                return false;
            }

            if (!ScriptInputCheck(form.list_disp_g_name.value, "会社名(一覧表示用)", form.list_disp_g_name)) {
                return false;
            }

            if (Trim(form.service_inq.value) == '') {
                alert("問い合わせ先は必須入力です。");
                form.service_inq.focus();
                return false;
            }

            chkMoji = form.service_inq.value;
            if (chkMoji.length > 0) {
                if (chkMoji.indexOf("<a") >= 0) {
                    if (chkMoji.split("<a").length != chkMoji.split("/a>").length) {
                        alert("問い合わせ先に入力されているリンクタグに誤りがあります。");
                        form.service_inq.focus();
                        return false;
                    }
                }
            }

            if (!ScriptInputCheck(form.service_inq.value, "問い合わせ先", form.service_inq)) {
                return false;
            }

            if (form.service_tel.value.length > 20) {
                alert("問い合わせ先TELは20文字以内で入力してください。");
                form.service_tel.focus();
                return false;
            }
            if (form.service_mail.value.length > 200) {
                alert("問い合わせ先E-Mailは200文字以内で入力してください。");
                form.service_mail.focus();
                return false;
            }

            data1 = form.releaseFrom.value;
            data1 = data1.match(/(\d{4})\/(\d{2})\/(\d{2})/);
            if (!data1) {
                alert("掲載開始日時にYYYY/MM/DD形式で日付を入力して下さい。");
                form.releaseFrom.focus();
                return false;
            }
            data1 = form.releaseFrom.value.replace(/\//g, "");
            if (!isDate(data1.substr(0, 4), data1.substr(4, 2), data1.substr(6, 2))) {
                alert("掲載開始日時に正しい日付を入力して下さい。");
                form.releaseFrom.focus();
                return false;
            }
            if (form.timeFrom.value == "") {
                alert("掲載開始時刻を選択して下さい。");
                form.timeFrom.focus();
                return false;
            }
            data1 = "" + data1 + ("0" + form.timeFrom.value).slice(-2);

            data2 = form.releaseTo.value;
            data2 = data2.match(/(\d{4})\/(\d{2})\/(\d{2})/);
            if (!data2) {
                alert("掲載終了日時にYYYY/MM/DD形式で日付を入力して下さい。");
                form.releaseTo.focus();
                return false;
            }
            data2 = form.releaseTo.value.replace(/\//g, "");
            if (!isDate(data2.substr(0, 4), data2.substr(4, 2), data2.substr(6, 2))) {
                alert("掲載終了日時に正しい日付を入力して下さい。");
                form.releaseTo.focus();
                return false;
            }
            if (form.timeTo.value == "") {
                alert("掲載終了時刻を選択して下さい。");
                form.timeTo.focus();
                return false;
            }
            data2 = "" + data2 + ("0" + form.timeTo.value).slice(-2);

            var d = new Date();
            nowDate = "" + d.getYear()
                + ("0" + (d.getMonth() + 1)).slice(-2)
                + ("0" + d.getDate()).slice(-2)
                + ("0" + d.getHours()).slice(-2);


            if (data1 > data2) {
                alert("掲載終了日時は、掲載開始日時以降を指定して下さい。");
                form.releaseTo.focus();
                return false;
            }
            return true;
        }

        function ScriptInputCheck(val, itemName, itemObj) {
            var chkMoji = val.toUpperCase();
            if (chkMoji.length > 0) {
                if (chkMoji.indexOf("SCRIPT") >= 0) {
                    alert(itemName + "に不正な文字が含まれています。Javascriptのようなスクリプトは含めることができません。");
                    itemObj.focus();
                    return false;
                }
            }
            return true;
        }

        function DispSwitch(line) {
            document.getElementById("free_head_area" + line).style.display = "none";
            document.getElementById("free_data_area" + line).style.display = "";
            /*
            if (word.innerHTML == "＋") {
                word.innerHTML = "－";
                view.style.display = "";
            } else {
                word.innerHTML = "＋";
                view.style.display = "none";
            }
            */
        }

        function HideSwitch(line) {
            document.getElementById("free_head_area" + line).style.display = "";
            document.getElementById("free_data_area" + line).style.display = "none";
        }

        function openHelp() {
            <?php $page_link = serviceCrSet::getPageSlug('nakama-request-service-dateinput')?>
            window.open('<?= $page_link?>', '_blank', 'width=800,height=400');
        }

        function img_hensyu(img, img_dir) {
            var x, y;
            var wnd;
            x = (screen.width - 600) / 2;
            y = (screen.height - 500) / 2;
            <?php $page_link = serviceCrSet::getPageSlug('nakama-request-service-item-img'); ?>
            wnd = window.open("<?= $page_link ?>post_id=<?php echo $post_id; ?>&svc_id=4&img=" + img + '&file_dir=' + img_dir, "pagesetup", "left=" + x + ", top=" + y + ", width=600, height=500, scrollbars=yes,location=no, menubar=no, status=yes, resizable=yes");
            wnd.focus();
        }

        function item_img_hensyu(img, reimg, situation, itemno, no, file_dir) {
            var x, y;
            var wnd;
            x = (screen.width - 600) / 2;
            y = (screen.height - 500) / 2;
            <?php $page_link = serviceCrSet::getPageSlug('nakama-request-service-item-img'); ?>
            wnd = window.open("<?= $page_link ?>svc_id=4&img=" + img + "&reimg=" + reimg + "&situation=" + situation + "&itemno=" + itemno + "&no=" + no + "&post_id=<?php echo $post_id; ?>&file_dir=" + file_dir, "pagesetup", "left=" + x + ", top=" + y + ", width=600, height=500, scrollbars=yes,location=no, menubar=no, status=yes, resizable=yes");
            wnd.focus();
        }

        //テキスト編集ウィンドウ表示
        function item_hensyu(field, areatype, itemno, maxlength) {
            var x, y;
            var wnd;
            x = (screen.width - 700) / 2;
            y = (screen.height - 650) / 2;
            // var input = document.getElementById('disp_' + field).innerText != '[未入力]' ? document.getElementById('disp_' + field).innerText : "";
            <?php $page_link = serviceCrSet::getPageSlug('nakama-request-service-text-edit'); ?>
            wnd = window.open("<?= $page_link ?>?svc_id=4&svcinfono=&field=" + field + "&areatype=" + areatype + "&itemno=" + itemno + "&maxlength=" + maxlength, "service_input", "left=" + x + ", top=" + y + ", width=700, height=650, scrollbars=yes,location=no, menubar=no, status=yes, resizable=yes");
            wnd.focus();
        }

        function text() {
            document.mainForm.target = "_self";
            document.mainForm.method = "post";
            document.mainForm.action = "service_input['asp";
            document.mainForm.submit();
        }

        function onKeyUpTitle() {
            document.mainForm.service_title.value = document.getElementsByName('service_title')[0].value
        }

        function change_send_mail() {
            if (document.getElementById('send_mail').checked) {
                document.getElementById('send_mail').value = 1
            } else {
                document.getElementById('send_mail').value = 0
            }
        }

        //-->
    </script>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE11"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/default.css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/req_service.css"/>
    <body onload="load_input()">
    <form name="mainForm" id="mainForm" action="">
        <table class="header" align="center">
            <tr>
                <td class="textwhite">■<?= $s_kind ?>&nbsp;申請入力</td>
            </tr>
        </table>
        <br>
        <div style="text-align:center">
		<span style="position: relative;">
			<img src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/step/step.png">
			<span class="progress_i">申請サービス選択</span>
		</span>
            <span style="position: relative;">
			<img src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/step/step_a.png">
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
                    ■<?= $s_kind ?>掲載内容
                </td>
            </tr>
            <tr>
                <td class="table_i">
                    タイトル
                    <span class="requires">※</span>
                </td>
                <td class="table_d">
                    <input type="text" name="service_title" class="input_text" maxlength="200" value=""
                           onkeyup="onKeyUpTitle()">
                </td>
            </tr>
            <?php foreach ($MServiceItem as $key => $mServiceItem) { ?>
                <tr>
                    <td class="table_i table_i">
                        <?= $mServiceItem->SVC_ITEM_NM; ?>
                        <?php if (($mServiceItem->SVC_ITEM_MUST) == 1) { ?><span class="requires">※</span> <?php } ?>
                        <input type="hidden" name="item_headline_flg[]"
                               value="<?= $mServiceItem->SVC_ITEM_HEADLINE_FLG; ?>">
                        <input type="hidden" name="item_title[]" value="<?= $mServiceItem->SVC_ITEM_NM; ?>">
                    </td>
                    <td class="table_d">
                        <?php if ($ServiceItemImg['item' . $key] == 1) { ?>
                            <input type="button" value="画像登録"
                                   onclick="item_img_hensyu(document.mainForm.item_<?= $key + 1 ?>_file1.value, document.mainForm.item_<?= $key + 1 ?>_re_file1.value, document.mainForm.item_<?= $key + 1 ?>_situation1.value, <?= $key + 1 ?>, 1, document.mainForm.item_<?= $key + 1 ?>_file1_dir.value);">
                            <span id="disp_<?= $key + 1 ?>_file1"></span>
                            <br>
                        <?php } ?>
                        <input type="hidden" name="item_<?= $key + 1 ?>_file1" value="">
                        <input type="hidden" name="item_<?= $key + 1 ?>_re_file1" value="">
                        <input type="hidden" name="item_<?= $key + 1 ?>_situation1" value="">
                        <input type="hidden" name="item_<?= $key + 1 ?>_file1_dir" value="">
                        <?php switch ($mServiceItem->SVC_ITEM_TYPE) {
                            case ($mServiceItem->SVC_ITEM_TYPE == "TEXT" || $mServiceItem->SVC_ITEM_TYPE == 'TEXTAREA'):
                                ?>
                                <a href="javascript:item_hensyu('service_item<?= $key + 1 ?>','0','<?= $key + 1 ?>',<?= $mServiceItem->SVC_ITEM_MAX_LENGTH ?>)"><img
                                            src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/service/edit.gif"
                                            border="0"
                                            vspace="0" hspace="0"></a>
                                <div class="inputField"><span
                                            id="disp_service_item<?= $key + 1 ?>"
                                            name="<?php echo ($mServiceItem->SVC_ITEM_TYPE == "DATE") ? "service_date_item" . ($key + 1) : "" ?>">[未入力]</span>
                                    <input
                                            type="hidden"
                                            name="service_item[]"
                                            value=""></div>
                                <?php break;
                            case "DATE": ?>
                                <div style="float:left;"><input type="text" name="service_date_item<?= $key + 1 ?>"
                                                                maxlength="<?= $mServiceItem->SVC_ITEM_MAX_LENGTH ?>"
                                                                size="15" class="alphameric" value=""><input
                                            type="button" value="..."
                                            onclick="wrtCalendarLay(this.form.service_date_item<?= $key + 1 ?>,event)">
                                    <select name="service_time_item<?= $key + 1 ?>">
                                        <option value="" selected="">--</option>
                                        <option value="00">00</option>
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                    </select>時
                                </div>
                                <div style="float:right;"><a href="Javascript:openHelp();">入力例</a></div>
                                <br>
                                <!--                                <div class="inputField">-->
                                <!--                                    <span-->
                                <!--                                            id="disp_service_item--><?//= $key + 1 ?><!--"-->
                                <!--                                            name="--><?php //echo ($mServiceItem->SVC_ITEM_TYPE == "DATE") ? "service_date_item" . ($key + 1) : "" ?><!--">[未入力]</span>-->
                                <input type="text" class="input_text" name="service_item[]" maxlength="<?= $mServiceItem->SVC_ITEM_MAX_LENGTH ?>" value="">
                                <!--                                </div>-->
                                <?php break;
                            case "URL": ?>
                                <!--                                <a href="javascript:item_hensyu('service_item--><?//= $key + 1 ?><!--','0','--><?//= $key + 1 ?><!--','4000')"><img-->
                                <!--                                            src="--><?//= plugin_dir_url(dirname(__FILE__)) ?><!--assets/img/service/edit.gif"-->
                                <!--                                            border="0"-->
                                <!--                                            vspace="0" hspace="0"></a>-->
                                <div class="inputField">
                                    <!--                                    <span-->
                                    <!--                                            id="disp_service_item-->
                                    <?//= $key + 1 ?><!--"-->
                                    <!--                                            name="-->
                                    <?php //echo ($mServiceItem->SVC_ITEM_TYPE == "DATE") ? "service_date_item" . ($key + 1) : "" ?><!--">[未入力]</span>-->
                                    <input
                                            type="text"
                                            name="service_item[]"
                                            class="input_text"
                                            maxlength="<?= $mServiceItem->SVC_ITEM_MAX_LENGTH ?>"
                                            value=""></div>
                                <?php break;
                            case "MAIL": ?>
                                <div class="inputField">
                                    <!--                                    <span-->
                                    <!--                                            id="disp_service_item-->
                                    <? //= $key + 1 ?><!--"-->
                                    <!--                                            name="-->
                                    <?php //echo ($mServiceItem->SVC_ITEM_TYPE == "DATE") ? "service_date_item" . ($key + 1) : "" ?><!--">[未入力]</span>-->
                                    <input type="text" name="service_item[]" class="input_text"
                                           maxlength="<?= $mServiceItem->SVC_ITEM_MAX_LENGTH ?>"
                                           value=""></div>
                            <?php } ?>

                        <?php if ($ServiceItemImg['item' . $key] == 1) { ?>
                            <input type="button" value="画像登録"
                                   onclick="item_img_hensyu(document.mainForm.item_<?= $key + 1 ?>_file2.value, document.mainForm.item_<?= $key + 1 ?>_re_file2.value, document.mainForm.item_<?= $key + 1 ?>_situation2.value, <?= $key + 1 ?>, 2, document.mainForm.item_<?= $key + 1 ?>_file2_dir.value);">
                            <span id="disp_<?= $key + 1 ?>_file2"></span>
                        <?php } ?>
                        <input type="hidden" name="item_<?= $key + 1 ?>_file2" value="">
                        <input type="hidden" name="item_<?= $key + 1 ?>_re_file2" value="">
                        <input type="hidden" name="item_<?= $key + 1 ?>_situation2" value="">
                        <input type="hidden" name="item_<?= $key + 1 ?>_file2_dir" value="">

                        <input type="hidden" name="valItemNo[]" value="<?= $mServiceItem->SVC_ITEM_NO ?>">
                        <input type="hidden" name="valLength[]" value="<?= $mServiceItem->SVC_ITEM_MAX_LENGTH ?>">
                        <input type="hidden" name="valType[]" value="<?= $mServiceItem->SVC_ITEM_TYPE ?>">
                        <input type="hidden" name="valHTML[]" value="<?= $mServiceItem->SVC_ITEM_HTML ?>">
                        <input type="hidden" name="valMust[]" value="<?= $mServiceItem->SVC_ITEM_MUST ?>">
                    </td>
                </tr>
            <?php } ?>
            <?php for ($i = 0; $i < 5; $i++) { ?>
                <tr id="free_head_area<?= $i + 1 ?>" style="">
                    <td class="table_i" style="text-align:left;" colspan="2">
                        <a href="JavaScript:DispSwitch(<?= $i + 1 ?>);">[＋]</a>任意項目<?= $i + 1 ?>
                    </td>
                </tr>
                <tr id="free_data_area<?= $i + 1 ?>" style="display:none;">
                    <td class="table_i" style="text-align:left; vertical-align:top;">
                        <a href="JavaScript:HideSwitch(<?= $i + 1 ?>);">[－]</a>任意項目<?= $i + 1 ?><br>
                        <input type="text" class="input_text_title" name="free_title[]" id="free_title" maxlength="100">
                    </td>
                    <td class="table_d">
                        <?php if ($ServiceItemFreeImg['item' . $i] == 1) { ?>
                            <input type="button" value="画像登録"
                                   onclick="item_img_hensyu(document.mainForm.item_10<?= $i + 1 ?>_file1.value, document.mainForm.item_10<?= $i + 1 ?>_re_file1.value, document.mainForm.item_10<?= $i + 1 ?>_situation1.value, 10<?= $i + 1 ?>, 1, document.mainForm.item_10<?= $i + 1 ?>_file1_dir.value);">
                            <span id="disp_10<?= $i + 1 ?>_file1"></span><br>
                        <?php } ?>
                        <input type="hidden" name="item_10<?= $i + 1 ?>_file1" value="">
                        <input type="hidden" name="item_10<?= $i + 1 ?>_re_file1" value="">
                        <input type="hidden" name="item_10<?= $i + 1 ?>_situation1" value="">
                        <input type="hidden" name="item_10<?= $i + 1 ?>_file1_dir" value="">

                        <a href="javascript:item_hensyu('free_data10<?= $i + 1 ?>','<?= $i + 1 ?>','10<?= $i + 1 ?>','2000')"><img
                                    src="<?= plugin_dir_url(dirname(__FILE__)) ?>assets/img/service/edit.gif" border="0"
                                    vspace="0" hspace="0"></a>
                        <div class="inputField"><span id="disp_free_data10<?= $i + 1 ?>"><font
                                        color="#330099">[未入力]</font></span><input
                                    type="hidden" name="free_data[]" value=""></div>
                        <?php if ($ServiceItemFreeImg['item' . $i] == 1) { ?>
                            <input type="button" value="画像登録"
                                   onclick="item_img_hensyu(document.mainForm.item_10<?= $i + 1 ?>_file2.value, document.mainForm.item_10<?= $i + 1 ?>_re_file2.value, document.mainForm.item_10<?= $i + 1 ?>_situation2.value, 10<?= $i + 1 ?>, 2, document.mainForm.item_10<?= $i + 1 ?>_file2_dir.value);">
                            <span id="disp_10<?= $i + 1 ?>_file2"></span><br>
                        <?php } ?>
                        <input type="hidden" name="item_10<?= $i + 1 ?>_file2" value="">
                        <input type="hidden" name="item_10<?= $i + 1 ?>_re_file2" value="">
                        <input type="hidden" name="item_10<?= $i + 1 ?>_situation2" value="">
                        <input type="hidden" name="item_10<?= $i + 1 ?>_file2_dir" value="">

                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td class="table_i">
                    会社名(一覧表示用)
                    <span class="requires">※</span>
                </td>
                <td class="table_d">
                    <input type="text" class="input_text" maxlength="200" name="list_disp_g_name"
                           value="<?= $shinsei_name != "" ? $shinsei_name : 'デモ株式会社' ?>">
                </td>
            </tr>
            <tr>
                <td class="table_i">
                    問合せ先（会社名・部署・担当者・受付時間など）
                    <span class="requires">※</span>
                </td>
                <td class="table_d">
                    <?php
                        $shinsei_name_txt = !empty($shinsei_name) ? $shinsei_name . "\n" : "デモ株式会社\n";
                        $shinsei_shimei_txt = !empty($shinsei_shimei) ? $shinsei_shimei : "花子";
                    ?>
                    <input type="button" value="リンクタグ" id="inqLinkTag" class="inqLinkTag">
                    <input type="button" value="メールタグ" id="inqMailTag" class="inqMailTag">
                    <textarea class="input_textarea" rows="15" name="service_inq" id="inq_data"><?php echo $shinsei_name_txt . $shinsei_shimei_txt; ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="table_i">
                    問合せ先TEL
                </td>
                <td class="table_d">
                    <input type="text" class="input_text" maxlength="200" name="service_tel"
                           value="<?= $shinsei_tel ?>">
                </td>
            </tr>
            <tr>
                <td class="table_i">
                    問合せ先E-Mail
                </td>
                <td class="table_d">
                    <input type="text" class="input_text" maxlength="200" name="service_mail"
                           value="<?= $shinsei_mail ?>">
                </td>
            </tr>
            <tr>
                <td class="table_i">
                    問合せ先掲載画像
                </td>
                <td class="table_d">
                    <input type="button" value="画像登録"
                           onclick="item_img_hensyu(document.mainForm.m_img.value, '', '', '', '', document.mainForm.m_img_dir.value);">
                    <span id="disp_img"></span>
                    <input type="hidden" name="m_img" value="">
                    <input type="hidden" name="m_imgOld" value="">
                    <input type="hidden" name="m_imgRegFlg" value="">
                    <input type="hidden" name="m_img_dir" value="">
                </td>
            </tr>
            <tr class="disp_none">
                <td class="table_i">
                    掲載理由
                    <span class="requires">※</span>
                </td>
                <td class="table_d">
                    <textarea class="input_textarea" rows="15" name="reason"></textarea>
                </td>
            </tr>
            <tr>
                <td class="table_i">
                    掲載期間
                    <span class="requires">※</span>
                </td>
                <td class="table_d">
                    <input type="text" name="releaseFrom" maxlength="10" size="15" class="alphameric"
                           value="<?php echo date('Y/m/d') ?>">
                    <input type="button" value="..." onclick="wrtCalendarLay(this.form.releaseFrom,event)">
                    <select name="timeFrom">
                        <option value="00" selected="">00</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                    </select>時　～　
                    <input type="text" name="releaseTo" maxlength="10" size="15" class="alphameric"
                           value="<?php echo date('Y/m/d') ?>">
                    <input type="button" value="..." onclick="wrtCalendarLay(this.form.releaseTo,event)">
                    <select name="timeTo">
                        <option value="00">00</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23" selected="">23</option>
                    </select>時
                    <br>
                    <?php if (SERVICE_USER_ID != $tg_id) { ?>
                        <input type="checkbox" name="send_mail" id="send_mail" value="" onclick="change_send_mail()">
                        <label for="send_mail">会員へ通知する</label>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <table class="table_btn" align="center">
            <tr>
                <td align="left">
                    <span class="requires">※</span>印は必須入力項目です。
                    <span class="nodisp">※</span>印は非表示項目タイトルです。
                </td>
                <td align="right">
                    <input align="right" type="button" class="btnClass" value="戻　る" onclick="cancel()">
                    <input align="right" type="button" class="btnClass" value="次　へ" onclick="move_next()">
                </td>
            </tr>
        </table>
        <br><br><br><br><br><br><br><br>
        <input type="hidden" name="shinsei_name" value="<?= $shinsei_name ?>">
        <input type="hidden" name="shinsei_busho" value="<?= $shinsei_busho ?>">
        <input type="hidden" name="shinsei_yaku" value="<?= $shinsei_yaku ?>">
        <input type="hidden" name="shinsei_shimei" value="<?= $shinsei_shimei ?>">
        <input type="hidden" name="shinsei_tel" value="<?= $shinsei_tel ?>">
        <input type="hidden" name="shinsei_mail" value="<?= $shinsei_mail ?>">
        <input type="hidden" name="s_kind" value="<?= $s_kind ?>">
        <input type="hidden" name="g_id" value="">
        <input type="hidden" name="a_id" value="dmshibuyag_none">
        <input type="hidden" name="forward_mail" value="">
        <input type="hidden" name="page_no" value="137">
        <input type="hidden" name="service_type" value="ビジネス">
        <input type="hidden" name="page_no" value="<?= $page_no ?>">
        <input type="hidden" name="top_g_id" value="<?= $tg_id ?>">
        <input type="hidden" name="svc_req_p_id" value="<?= $p_id ?>">
        <input type="hidden" name="notice_flg" value="1">
        <input type="hidden" name="reason_flg" value="0">
        <input type="hidden" name="post_id" value="<?= $post_id ?>"
        <input type="hidden" name="svc_id" value="<?= $svc_id ?>">
        <input type="hidden" name="reg_svcid" value="<?= $reg_svc_id ?>">
        <input type="hidden" name="reg_svcinfono" value="<?= $reg_svc_info_no ?>">
        <input type="hidden" name="svc_info_no" value="<?= $_POST['svc_info_no'] ?>">
        <input type="hidden" name="judge_result" value="">
        <input type="hidden" name="service_input" value="">
        <input type="hidden" name="edit_name" value="">
        <input type="hidden" name="chg_svc_id" value="">
        <input type="hidden" name="img_no" value="">
        <input type="hidden" name="top_g_id" value="<?= $tg_id ?>">
        <input type="hidden" name="svc_id" value="<?= $svc_id ?>">
        <input type="hidden" name="p_id" value="<?= $p_id ?>">
        <input type="hidden" name="fileName" value="<?= $file_name ?>">
        <input type="hidden" name="type" value="<?= $type ?>">
    </form>
    <form name="backForm" id="backForm">
        <input type="hidden" name="page_id" value="<?= get_page_by_path('nakama-request-service-select')->ID ?>">
        <input type="hidden" name="page_no" value="<?= $page_no ?>">
        <input type="hidden" name="top_g_id" value="<?= $tg_id ?>">
        <input type="hidden" name="p_id" value="<?= $p_id ?>">
        <input type="hidden" name="svc_id" value="<?= $svc_id ?>">
        <input type="hidden" name="post_id" value="<?= $post_id ?>">
        <input type="hidden" name="svc_info_no" value="<?= $_POST['svc_info_no'] ?>">
        <input type="hidden" name="func" value="back">
        <input type="hidden" name="type" value="<?= $type ?>">
    </form>
    </body>
    <script type="text/javascript">
        function load_input() {
            if ('<?php echo isset($_POST['func'])?>' != "" || '<?php echo isset($_POST['type'])?>' != "") {
                let service_input = <?php echo json_encode($_SESSION['service_input']); ?>?<?php echo json_encode($_SESSION['service_input']); ?>:<?php echo json_encode($service_input) ?>;
                // service_title
                //console.log(service_input)
                document.getElementsByName('service_title')[0].value = service_input.service_title;
                //service item
                let service_items = document.getElementsByName('service_item[]');
                let valTypes = document.getElementsByName('valType[]')
                console.log(service_input.service_date_time);
                for (let i = 0; i < service_items.length; i++) {
                    service_items[i].value = service_input.service_item[i] == undefined ? "" : service_input.service_item[i];
                    // if (i != service_items.length - 1) {
                    let title = document.getElementById('disp_service_item' + (i + 1));
                    if ((service_items[i].value).indexOf('\"') != -1) {
                        service_items[i].value = (service_items[i].value).replace(/\\"/g, '"');
                    }
                    if (title) {
                        title.innerHTML = (service_items[i].value == "") ? '[未入力]' : service_items[i].value;
                    }
                    if (valTypes[i].value == 'DATE') {
                        let service_date = document.getElementsByName('service_date_item' + (i + 1));
                        let service_time = document.getElementsByName('service_time_item' + (i + 1));
                        service_date[0].value = service_input.service_date_time[i].date;
                        service_time[0].value = service_input.service_date_time[i].time;
                    }
                    // }
                }
                //    free data
                let free_datas = document.getElementsByName('free_data[]');
                for (let i = 0; i < free_datas.length; i++) {
                    free_datas[i].value = service_input.free_data[i] == undefined ? "" : service_input.free_data[i];
                    let title = document.getElementById('disp_free_data10' + (i + 1));
                    if ((free_datas[i].value).indexOf('\"') != -1) {
                        free_datas[i].value = (free_datas[i].value).replace(/\\"/g, '"');
                    }
                    title.innerHTML = (free_datas[i].value == "") ? '[未入力]' : free_datas[i].value;
                }
                //    free title
                let free_titles = document.getElementsByName('free_title[]');
                for (let i = 0; i < free_titles.length; i++) {
                    free_titles[i].value = service_input.free_title[i] == undefined ? "" : service_input.free_title[i];
                    if (free_titles[i].value) {
                        document.getElementById('free_data_area' + (i + 1)).style.display = "";
                        document.getElementById('free_head_area' + (i + 1)).style.display = "none";
                    }
                }
                var item_image = service_input.item_image;
                var item_image_free = service_input.item_image_free;
                // item image
                var list_input = '<?php echo($MServiceItem);?>';
                var size = item_image.length > list_input.length ? list_input.length : item_image.length;
                console.log('item_image', item_image);
                for (let i = 0; i < size; i++) {
                    if (item_image[i] && document.getElementById('disp_' + (i + 1) + '_file1')) {
                        document.getElementsByName('item_' + (i + 1) + '_file1')[0].value = item_image[i].FILE1 == undefined ? "" : item_image[i].FILE1;
                        document.getElementsByName('item_' + (i + 1) + '_re_file1')[0].value = item_image[i].RE_FILE1 == undefined ? "" : item_image[i].RE_FILE1;
                        document.getElementsByName('item_' + (i + 1) + '_situation1')[0].value = item_image[i].SITUATION1 == undefined ? "" : item_image[i].SITUATION1;
                        document.getElementsByName('item_' + (i + 1) + '_file2')[0].value = item_image[i].FILE2 == undefined ? "" : item_image[i].FILE2;
                        document.getElementsByName('item_' + (i + 1) + '_re_file2')[0].value = item_image[i].RE_FILE2 == undefined ? "" : item_image[i].RE_FILE2;
                        document.getElementsByName('item_' + (i + 1) + '_situation2')[0].value = item_image[i].SITUATION2 == undefined ? "" : item_image[i].SITUATION2;
                        if (document.getElementById('disp_' + (i + 1) + '_file1')) {
                            document.getElementById('disp_' + (i + 1) + '_file1').innerText = (item_image[i].FILE1 == undefined) ? '' : item_image[i].FILE1;
                        }
                        if (document.getElementById('disp_' + (i + 1) + '_file2')) {
                            document.getElementById('disp_' + (i + 1) + '_file2').innerText = (item_image[i].FILE2 == undefined) ? '' : item_image[i].FILE2;
                        }
                        if (item_image[i].FILE1 == undefined || item_image[i].FILE1 == '') {
                            document.getElementsByName('item_' + (i + 1) + '_file1_dir')[0].value = "/temp/<?= $tg_id ?>";
                        } else {
                            if (item_image[i].FILE1_DIR == undefined) {
                                document.getElementsByName('item_' + (i + 1) + '_file1_dir')[0].value = "/ServiceData/<?= $tg_id ?>/<?= $svc_id ?>/" + item_image[i].SVC_INFO_NO + "/PublishItem/" + (i + 1) + "/1";
                            } else {
                                document.getElementsByName('item_' + (i + 1) + '_file1_dir')[0].value = item_image[i].FILE1_DIR;
                            }
                        }
                        if (item_image[i].FILE2 == undefined || item_image[i].FILE2 == '') {
                            document.getElementsByName('item_' + (i + 1) + '_file2_dir')[0].value = "/temp/<?= $tg_id ?>";
                        } else {
                            if (item_image[i].FILE2_DIR == undefined) {
                                document.getElementsByName('item_' + (i + 1) + '_file2_dir')[0].value = "/ServiceData/<?= $tg_id ?>/<?= $svc_id ?>/" + item_image[i].SVC_INFO_NO + "/PublishItem/" + (i + 1) + "/2";
                            } else {
                                document.getElementsByName('item_' + (i + 1) + '_file2_dir')[0].value = item_image[i].FILE2_DIR;
                            }
                        }
                    }
                }

                // item_free_image
                for (let i = 0; i < item_image_free.length; i++) {
                    if (item_image_free[i] && document.getElementById('disp_' + (i + 101) + '_file1')) {
                        document.getElementsByName('item_' + (i + 101) + '_file1')[0].value = item_image_free[i].FILE1 == undefined ? "" : item_image_free[i].FILE1;
                        document.getElementsByName('item_' + (i + 101) + '_re_file1')[0].value = item_image_free[i].RE_FILE1 == undefined ? "" : item_image_free[i].RE_FILE1;
                        document.getElementsByName('item_' + (i + 101) + '_situation1')[0].value = item_image_free[i].SITUATION1 == undefined ? "" : item_image_free[i].SITUATION1;
                        document.getElementsByName('item_' + (i + 101) + '_file2')[0].value = item_image_free[i].FILE2 == undefined ? "" : item_image_free[i].FILE2;
                        document.getElementsByName('item_' + (i + 101) + '_re_file2')[0].value = item_image_free[i].RE_FILE2 == undefined ? "" : item_image_free[i].RE_FILE2;
                        document.getElementsByName('item_' + (i + 101) + '_situation2')[0].value = item_image_free[i].SITUATION2 == undefined ? "" : item_image_free[i].SITUATION2;

                        if (document.getElementById('disp_' + (i + 101) + '_file1')) {
                            document.getElementById('disp_' + (i + 101) + '_file1').innerText = (item_image_free[i].FILE1 == undefined) ? '' : item_image_free[i].FILE1;
                        }
                        if (document.getElementById('disp_' + (i + 101) + '_file2')) {
                            document.getElementById('disp_' + (i + 101) + '_file2').innerText = (item_image_free[i].FILE2 == undefined) ? '' : item_image_free[i].FILE2;
                        }
                        if (item_image_free[i].FILE1 == undefined || item_image_free[i].FILE1 == '') {
                            document.getElementsByName('item_' + (i + 101) + '_file1_dir')[0].value = "/temp/" + item_image_free[i].TG_ID;
                        } else {
                            if (item_image_free[i].FILE1_DIR == undefined) {
                                document.getElementsByName('item_' + (i + 101) + '_file1_dir')[0].value = "/ServiceData/<?= $tg_id ?>/<?= $svc_id ?>/" + item_image_free[i].SVC_INFO_NO + "/PublishFreeItem/" + (i + 101) + "/1";
                            } else {
                                document.getElementsByName('item_' + (i + 101) + '_file1_dir')[0].value = item_image_free[i].FILE1_DIR;
                            }
                        }
                        if (item_image_free[i].FILE2 == undefined || item_image_free[i].FILE2 == '') {
                            document.getElementsByName('item_' + (i + 101) + '_file2_dir')[0].value = "/temp/<?= $tg_id ?>";
                        } else {
                            if (item_image_free[i].FILE2_DIR == undefined) {
                                document.getElementsByName('item_' + (i + 101) + '_file2_dir')[0].value = "/ServiceData/<?= $tg_id ?>/<?= $svc_id ?>/" + item_image_free[i].SVC_INFO_NO + "/PublishFreeItem/" + (i + 101) + "/2";
                            } else {
                                document.getElementsByName('item_' + (i + 101) + '_file2_dir')[0].value = item_image_free[i].FILE2_DIR;
                            }
                        }
                    }
                }


                //    list_disp_g_name
                document.getElementsByName('list_disp_g_name')[0].value = service_input.list_disp_g_name;
                //        releaseFrom
                document.getElementsByName('releaseFrom')[0].value = (service_input.releaseFrom).indexOf('-') != -1 ? (service_input.releaseFrom).split('-').join('/') : (service_input.releaseFrom);
                //        releaseTo
                document.getElementsByName('releaseTo')[0].value = (service_input.releaseTo).indexOf('-') != -1 ? (service_input.releaseTo).split('-').join('/') : (service_input.releaseTo);
                //service_inq
                document.getElementsByName('service_inq')[0].value = service_input.service_inq;
                //service_mail
                document.getElementsByName('service_mail')[0].value = service_input.service_mail;
                //service_tel
                document.getElementsByName('service_tel')[0].value = service_input.service_tel;
                //timeFrom
                document.getElementsByName('timeFrom')[0].value = service_input.timeFrom;
                //timeTo
                document.getElementsByName('timeTo')[0].value = service_input.timeTo;
                //timeTo
                if (document.getElementsByName('send_mail')[0]) {
                    document.getElementsByName('send_mail')[0].value = service_input.send_mail;
                    if (document.getElementsByName('send_mail')[0].value == 1 || ('<?= $_POST['send_mail']?>' == 1)) {
                        document.getElementsByName('send_mail')[0].checked = "checked"
                    } else {
                        document.getElementsByName('send_mail')[0].checked = false
                    }
                }
                document.getElementsByName('service_input')[0].value = service_input;

                //image service
                if ('<?php echo $_POST['m_img']?>' != "") {
                    document.getElementById('disp_img').innerText = '<?= $_POST['m_img']?>';
                    document.getElementsByName('m_img')[0].value = '<?= $_POST['m_img']?>';

                    if ('<?php echo $_POST['m_img_dir']?>' != "") {
                        document.getElementsByName('m_img_dir')[0].value = '<?= $_POST['m_img_dir']?>';
                    }
                }
            }
        }
    </script>
<?php } ?>