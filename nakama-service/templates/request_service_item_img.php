<?php
if (!isset($_SESSION['arrSession'])) {
    include('service_session_error.php');
    exit;
} else {
    if (!isset($_GET['situation']) && !isset($_GET['itemno']) && !isset($_GET['no'])) {
        include('service_img.php');
        exit;
    } else {
        $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : '';
        $dataSetting = get_post_meta($post_id);
        $tg_id = $dataSetting['nakama_service_param_tg_id'][0];
        $svc_id = isset($_GET['svc_id']) ? $_GET['svc_id'] : '';
        $img = isset($_GET['img']) ? $_GET['img'] : '';
        $reimg = isset($_GET['reimg']) ? $_GET['reimg'] : '';
        $situation = !empty($_GET['situation']) ? $_GET['situation'] : 'left';
        $itemno = !empty($_GET['itemno']) ? $_GET['itemno'] : '';
        $no = !empty($_GET['no']) ? $_GET['no'] : '';
        $file_dir = !empty($_GET['file_dir']) ? $_GET['file_dir'] : "/temp/{$tg_id}";
        $resizeList = array('1200', '1000', '850', '800', '700', '600', '500', '450', '400', '350', '300', '250', '200', '150', '100', '80', '50', '30');
        $service = new serviceController();
?>
        <!DOCTYPE html>
        <html lang="ja">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
            <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
            <title><?php echo 'ファイル登録'; ?></title>

            <link rel="stylesheet" type="text/css"
                  href="<?php echo plugins_url('nakama-service/assets/css/base.css'); ?>"/>

            <script src="<?php echo plugins_url('nakama-service/assets/js/jquery-1.6.3.min.js'); ?>"></script>
            <script src="<?php echo plugins_url('nakama-service/assets/js/jquery.form.min.js'); ?>"></script>
            <script src="<?php echo plugins_url('nakama-service/assets/js/request_service_item_img.js'); ?>"></script>
        </head>
        <body>
        <table border="0" width="100%">
            <tbody>
            <tr>
                <td width="100%">
                    <p align="right" style="margin: 0px">
                        <input type="button" value="<?php echo '閉じる'; ?>" id="btn-service-close-popup">
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
        <br/>
        <center>
            <form method="POST" action="" id="frm-service-reside-file" name="frm-service-reside-file"
                  style="<?php if (empty($img)) { ?>display:none;<?php } ?>">
                <a href="javascript:void(0)" id="btn-service-showhide-action" onclick="showHideAction()" style="display: <?php echo $service->checkIsImage($service->getUrlImage($img, $tg_id, false)) ? '' : 'none'; ?>">
                    <?php echo !empty($img) ? '[ オプションを表示 ]' : '[ オプションを隠す ]'; ?>
                </a>
                <table cellspacing="1" cellpadding="2" border="1" bordercolor="#DBEBFB" width="500">
                    <tr>
                        <td align="center" bgcolor="#DBEBFB" colspan="2">
                            <?php echo '現在のファイル'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#EEEEEE" align="center" colspan="2">
                            <img id="service-display-image"
                                 src="<?php echo $service->getServiceDisplayFile($img, $reimg, $file_dir) ?>"
                                 title="<?php echo '画像'; ?>">
                        </td>
                    </tr>
                    <tr id="service-wrap-resize" style="<?php if (!empty($img)) { ?>display:none<?php } ?>">
                        <td nowrap="nowrap" bgcolor="#DBEBFB"><?php echo '画像サイズ変更'; ?></td>
                        <td>
                            <div id="service-resize-option">
                            <?php foreach ($resizeList as $size) { ?>
                                <input type="button" value="<?php echo "{$size}px"; ?>"
                                       onclick="resizeImageService(<?php echo $size; ?>)">
                            <?php } ?>
                            </div>
                            <br>
                            <br>
                            <?php echo 'サイズ'; ?>
                            <input type="text" id="service_chg_size" name="service_chg_size" value="" size="4"
                                   maxlength="4" style="ime-mode: inactive; text-align: right;">
                            <input type="button" value="　<?php echo '変　更'; ?>　" id="btn-service-image-edit-size">
                            <font size="2" color="red">
                                <br>
                                <?php echo '※アップロードする画像のサイズ（容量・寸法）が大き過ぎる場合、'; ?>
                                <br>
                                &nbsp;&nbsp;
                                <?php echo 'サイズ変更機能が使用できないことがあります。'; ?>
                                <br>
                                &nbsp;&nbsp;
                                <?php echo 'サイズを小さくして再度お試し下さい。'; ?>
                            </font>
                        </td>
                    </tr>
                    <tr id="service-wrap-rotation" style="<?php if (!empty($img)) { ?>display:none<?php } ?>">
                        <td nowrap="nowrap" bgcolor="#DBEBFB" width="80">
                            <?php echo '画像回転'; ?>
                        </td>
                        <td width="514">
                            <input type="button" value="<?php echo '左回り90度'; ?>" id="btn-service-rotation-left-image"
                                   onclick="rotationImageService(this, 'left')"/>
                            <input type="button" value="<?php echo '右回り90度'; ?>" id="btn-service-rotation-right-image"
                                   onclick="rotationImageService(this, 'right')"/><br>
                            <font size="2" color="red">
                                <?php echo '※回転後に画像サイズ変更すると、角度は初期状態に戻ります。'; ?>
                                <br>
                                &nbsp;&nbsp;&nbsp;
                                <?php echo '画像サイズ変更後に角度を決定してください。'; ?>
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap" align="center" colspan="2">
                            <input type="button" value="　　<?php echo '元　に　戻　す'; ?>　　" id="btn-service-reset-image"
                                   onclick="resetImageService('<?php echo SERVICE_URL_UPLOAD . "usr_data/temp/{$tg_id}/"; ?>')"
                                   style="<?php if (empty($reimg)) { ?>display: none<?php } ?>" <?php if(empty($itemno) && empty($no)) { ?>disabled="true"<?php } ?>>
                            <input type="button" value="　　　　<?php echo '削　除'; ?>　　　　" id="btn-service-delete-image">
                            &nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
                <br>

                <table cellspacing="1" cellpadding="2" border="1" bordercolor="#DBEBFB" width="500">
                    <tr>
                        <td nowrap="nowrap" width="150" bgcolor="#DBEBFB">
                            <?php echo '画像位置'; ?>
                        </td>
                        <td width="700">
                            <input type="radio" id="service-situation-left" name="situation"
                                   value="<?php echo 'left'; ?>"
                                   <?php if ($situation == 'left') { ?>checked="true"<?php } ?> <?php if(empty($itemno) && empty($no)) { ?>disabled="true"<?php } ?>><?php echo '左'; ?>
                            &nbsp;&nbsp;
                            <input type="radio" id="service-situation-center" name="situation"
                                   value="<?php echo 'center'; ?>"
                                   <?php if ($situation == 'center') { ?>checked="true"<?php } ?> <?php if(empty($itemno) && empty($no)) { ?>disabled="true"<?php } ?>><?php echo '中央'; ?>
                            &nbsp;&nbsp;
                            <input type="radio" id="service-situation-right" name="situation"
                                   value="<?php echo 'right'; ?>"
                                   <?php if ($situation == 'right') { ?>checked="true"<?php } ?> <?php if(empty($itemno) && empty($no)) { ?>disabled="true"<?php } ?>><?php echo '右'; ?>
                            &nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
                <br>
                <table cellspacing="1" cellpadding="2" width="100%" align="center">
                    <tr>
                        <td nowrap="nowrap" colspan="2" align="center">
                            <div align="center">
                                <?php echo '編集を終了する場合は下記ボタンを押してださい。'; ?>
                            </div>
                            <input type="button" value="　　<?php echo '編　集　終　了'; ?>　　" id="btn-service-choice-image">
                        </td>
                    </tr>
                </table>
                <br>
                <input type="hidden" name="mode" value="">
                <input type="hidden" id="service_assets_img_folder" name="service_assets_img_folder"
                       value="<?php echo plugins_url('nakama-service/assets/img/'); ?>"/>
                <input type="hidden" id="service_home_url" name="service_home_url"
                       value="<?php echo get_home_url(); ?>"/>
                <input type="hidden" id="service_top_g_id" name="service_top_g_id" value="<?php echo $tg_id; ?>">
                <input type="hidden" id="service_svc_id" name="service_svc_id" value="<?php echo $svc_id; ?>">
                <input type="hidden" id="service_itemno" name="service_itemno" value="<?php echo $itemno; ?>">
                <input type="hidden" id="service_no" name="service_no" value="<?php echo $no; ?>">
                <input type="hidden" id="service_field" name="service_field" value="">
                <input type="hidden" id="service_option" name="service_option" value="0">
                <input type="hidden" id="service_img" name="service_img" value="<?php echo $img; ?>">
                <input type="hidden" id="service_reimg" name="service_reimg" value="<?php echo $reimg; ?>">
                <input type="hidden" id="service_situation" name="service_situation" value="<?php echo $situation; ?>">
            </form>
            <!-- 画像が存在しない場合 -->
            <form method="POST" action="" id="frm-service-upload-file" name="frm-service-upload-file"
                  enctype="multipart/form-data" style="<?php if (!empty($img)) { ?>display:none<?php } ?>">
                <table cellspacing="1" cellpadding="2" border="1" bordercolor="#DBEBFB" width="500">
                    <tr>
                        <td align="center" bgcolor="#DBEBFB" colspan="2" width="500">
                            <?php echo 'ファイルアップロード'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#EEEEEE" align="center">
                            <br>
                            <input type="file" name="service_file_upload" id="service_file_upload"/>
                            <input type="hidden" name="service_file_upload_post_id" id="service_file_upload_post_id"
                                   value="<?php echo $post_id; ?>"/>
                            <input type="hidden" name="action" value="service_upload_file"/>
                            <br>
                            <br>
                            <?php echo '上の【参照】ボタンをおすとファイル選択画面が開きます。'; ?>
                            <br>
                            <br>
                        </td>
                    </tr>
                </table>
                <br>
                <?php echo '上記でファイルを選択した後、下記ボタンを押してください。'; ?>
                <br>
                <br>
                <input type="submit" id="btn-service-upload-file" value="　　　　<?php echo '追　加'; ?>　　　　">
                <br>
            </form>
        </center>
        </body>
        </html>
    <?php }
} ?>