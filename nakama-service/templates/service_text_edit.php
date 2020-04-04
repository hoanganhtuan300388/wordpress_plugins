<?php
if (!isset($_SESSION['arrSession'])) {
    include('service_session_error.php');
    exit;
} else {
    $field = (isset($_GET['field']) ? $_GET['field'] : '');
    $maxlength = isset($_GET['maxlength']) ? $_GET['maxlength'] : 4000;
    $areatype =  '';
    $svc_id = isset($_GET['svc_id']) ? $_GET['svc_id'] : '';
    $svc_info_no = isset($_GET['svcinfono']) ? $_GET['svcinfono'] : '';
    $itemno = isset($_GET['itemno']) ? $_GET['itemno'] : '';
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE11" />
        <meta charset="utf-8">
        <script src="https://cdn.ckeditor.com/4.13.0/full-all/ckeditor.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css"
              href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/base.css"/>
        <link rel="stylesheet" type="text/css"
              href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/text_editor.css"/>
        <script src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>/assets/js/common.js" type="text/javascript"></script>
        <script type="text/JavaScript">
            <!--
            // 編集
            function mbStrWidth(input) {
                let len = 0;
                for (let i = 0; i < input.length; i++) {
                    let code = input.charCodeAt(i);
                    if ((code >= 0x0020 && code <= 0x1FFF) || (code >= 0xFF61 && code <= 0xFF9F)) {
                        len += 1;
                    } else if ((code >= 0x2000 && code <= 0xFF60) || (code >= 0xFFA0)) {
                        len += 2;
                    } else {
                        len += 1;
                    }
                }
                return len;
            }

            function btnClick(mode) {
                document.mainForm.mode.value = mode;
                var value = CKEDITOR.instances['text_editor'].getData();
                if(mbStrWidth(value)>'<?= $maxlength?>'){
                    alert('半角<?= $maxlength?>文字（全角<?= $maxlength/2 ?>文字）以内で入力して下さい。('+mbStrWidth(value)+'文字入力)');
                    return;
                }

                else{
                    document.mainForm.target = "DetailWnd";
                    window.opener.document.getElementById("disp_" + "<?= $field?>").innerHTML = value!=""?value:'[未入力]';
                    if ("<?= $field?>".indexOf('service_item')!=-1) {
                        window.opener.document.getElementsByName("service_item[]")[<?= $itemno - 1?>].value = value;
                    }
                    if ("<?= $field?>".indexOf('free_data')!=-1) {
                        window.opener.document.getElementsByName("free_data[]")[<?= $itemno - 101?>].value = value;
                    }
                    window.close();
                }
            }

            //-->
        </script>
    </head>
    <body onload="load()">
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" class="title" width="100">テキスト</td>
        </tr>
    </table>
    <table width="100%" border="0" cellpadding="1" cellspacing="0">
        <tr>
            <td class="title" align="right">
                <a href="javascript:window.close();"><font color="#FFFFFF">閉じる</font></a>
            </td>
        </tr>
    </table>
    <br>
    <div class="textAttention">
        ※「半角<?= $maxlength?>文字（全角<?= $maxlength/2 ?>文字）までが入力可能です。<br>
        &nbsp;&nbsp;ただし、文字装飾（太字、色つけなど）をされるとその分入力可能な文字数は少なくなります。」
    </div>
    <br>
    <div align="center">
        <form name="mainForm" id="mainForm" action="">
            <br>
            <br>
            <input id="oFile" onchange="load()" type="file" style="display:none">
            <input type="hidden" name="page_no" value="">
            <input type="hidden" name="patten_cd" value="">
            <input type="hidden" name="details_no" value="">
            <input type="hidden" name="field" value="<?= $field ?>">
            <input type="hidden" name="flg" value="">
            <input type="hidden" name="ym" value="">
            <input type="hidden" name="situation" value="<?= $field ?>">
            <input type="hidden" name="maxlength" value="4000">
            <input type="hidden" name="mode" value="">
            <input type="hidden" name="mode2" value="">
            <textarea name="text_editor" id="text_editor"><?php echo $areatype; ?></textarea>
        </form>
        <div align="center">
            <button title="登　録" onclick="btnClick('update')" id="edit"><b>登　録</b></button>&nbsp;&nbsp;
        </div>
    </div>
    </body>
    <script>
        function load() {
            var text = window.opener.document.getElementById("disp_" + "<?= $field?>").innerHTML;
            document.getElementById('text_editor').innerHTML = (text =="[未入力]" || text=="<font color=\"#330099\">[未入力]</font>")?"":text;
        }
        var editorInstance = CKEDITOR.replace(document.getElementById("text_editor"));
        CKEDITOR.config.autoParagraph = false;
    </script>
    </html>
<?php } ?>

