<?php
$post_id = isset( $_GET['post_id'] ) ? $_GET['post_id'] : '';
$dataSetting = get_post_meta( $post_id );
$tg_id = $dataSetting['nakama_service_param_tg_id'][0];
$img = isset( $_GET['img'] ) ? $_GET['img'] : '';
$file_dir = !empty($_GET['file_dir']) ? $_GET['file_dir'] : "/temp/{$tg_id}";
$service = new serviceController();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">
<head>
    <!--[if lt IE 9 ]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta name="robots" content="none">
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=yes" />
    <meta http-equiv="Content-Language" content="ja">
    <meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <!-- <meta http-equiv="Pragma" content="no-cache"> -->
    <!-- <meta http-equiv="Expires" content="-1"> -->
    <!-- CSS File -->
    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'nakama-service/assets/css/base.css' ); ?>" />
    <!-- JavaScript File -->
    <script src="<?php echo plugins_url( 'nakama-service/assets/js/jquery-1.6.3.min.js' ); ?>"></script>
    <script src="<?php echo plugins_url( 'nakama-service/assets/js/jquery.form.min.js' ); ?>"></script>
    <script src="<?php echo plugins_url( 'nakama-service/assets/js/service_img.js' ); ?>"></script>

    <title><?php echo '画像登録'; ?></title>
    <style type="text/css">
        <!--
        .naviHeader {
            height            : 33px;
            border            : 1px solid #a9a9a9;
            border-color      : #a9a9a9;
            background-color  : #f5f5f5;
            font-size         : 12pt;
            font-weight       : bold;
            vertical-align : middle;
        }
        -->
    </style>
<body>
<div id="wrapper">
    <form method="POST" action="" id="frm-service-upload-img" name="frm-service-upload-img" enctype="multipart/form-data">
        <!-- function bar -->
        <table width="100%" class="naviHeader">
            <tr>
                <td align="left" width="25%">
                </td>
                <td align="center" width="50%">
                    <?php echo '画像登録'; ?>
                </td>
                <td align="right" width="25%">
                    <a class="btn_close" href="Javascript:window.close();"></a>
                </td>
            </tr>
        </table>
        <br>
        <div class="table_center">
            <table width="520" align="center" border="0" cellspacing="1" cellpadding="3">
                <tr>
                    <td class="input_item" width="120">
                        <?php echo '登録する画像'; ?></td>
                    <td class="input_data" width="400">
                        <input type="file" size="64" name="service_file_upload" id="service_file_upload">
                    </td>
                </tr>
                <tr>
                    <td class="input_item" width="100"></td>
                    <td class="input_data" width="400">
                        <span id="txt-display-image"><?php echo $img; ?></span>
                    </td>
                </tr>
                <?php if ( !empty( $img ) ) { ?>
                    <tr id="wrap-display-image">
                        <td class="input_data" colspan="2">
                            <table align="center" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td><img src="<?php echo $service->getServiceDisplayFile($img, '', $file_dir) ?>"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php } ?>
            </table>

        </div> <!-- table_center end -->
        <br>
        <div style="text-align:right;">
            <input type="submit" id="btn-service-upload-image" value="<?php echo '登　録'; ?>">
            <?php if ( !empty( $img ) ) { ?>
                <input type="button" id="btn-service-delete-image" value="<?php echo '削　除'; ?>">
            <?php } ?>
        </div>
        <input type="hidden" id="service_home_url" name="service_home_url" value="<?php echo get_home_url(); ?>" />
        <input type="hidden" name="service_file_upload_post_id" id="service_file_upload_post_id" value="<?php echo $post_id; ?>" />
        <input type="hidden" name="action" value="service_upload_file" />
    </form>
    <center id="wrap-file-not-image" style="display: none">
        <br />
        <br />
        <br />
        <div style="font-size:120%">
            <?php echo '登録することができない形式のファイルです。'; ?>
            <br>
            <?php echo '画像ファイルを登録してください。'; ?>
        </div>
        <br>
        <input type="button" id="btn-service-back-image" value="<?php echo '　戻る　'; ?>">
    </center>
</div> <!-- wrapper end -->
</body>
</html>
