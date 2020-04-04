<?php
$controller = new uservoiceController();
$post = get_post();
$data_uv = $controller->getUserVoiceSend();
$confirm_page = get_page_by_path( 'nakama-confirm-uservoice', OBJECT, 'page' );

$validate_files = array();
if ( count( $file_type_list ) > 0 && count( $file_type_list ) > 0 ) {
    foreach ( $file_type_list as $category_type_file ) {
        if ( count( $category_type_file['types'] ) > 0 ) {
            foreach ( $category_type_file['types'] as $file_type ) {
                foreach ( $file_type_setting as $id ) {
                    if ( $file_type['id'] == $id ) {
                        $validate_files[] = $file_type['name'];
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
    <title><?php echo 'お客様の声'; ?></title>

    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'nakama-uservoice/assets/css/bootstrap.min.css' ); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'nakama-uservoice/assets/css/style.css' ); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'nakama-uservoice/assets/js/sweetalert2/dist/sweetalert2.min.css' ); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'nakama-uservoice/assets/js/bootstrap-slider-master/dist/css/bootstrap-slider.min.css' ); ?>" />

    <script src="<?php echo plugins_url( 'nakama-uservoice/assets/js/jquery-3.3.1.min.js' ); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill@7.1.0/dist/promise.min.js"></script>
    <script src="<?php echo plugins_url( 'nakama-uservoice/assets/js/bootstrap.min.js' ); ?>"></script>
    <script src="<?php echo plugins_url( 'nakama-uservoice/assets/js/jquery.validate.min.js' ); ?>"></script>
    <script src="<?php echo plugins_url( 'nakama-uservoice/assets/js/jquery.form.min.js' ); ?>"></script>
    <script src="<?php echo plugins_url( 'nakama-uservoice/assets/js/sweetalert2/dist/sweetalert2.min.js' ); ?>"></script>
    <script src="<?php echo plugins_url( 'nakama-uservoice/assets/js/bootstrap-slider-master/dist/bootstrap-slider.min.js' ); ?>"></script>
    <script src="<?php echo plugins_url( 'nakama-uservoice/assets/js/send-user-voice.js' ); ?>"></script>
</head>
<body>
<div class="error-info" style="color: red; display: none" align="center">
    <?php echo '正しく入力されていない項目があります。メッセージをご確認の上、もう一度ご入力ください。'; ?>
</div>
<div class="container send">
    <form method="POST" action="<?php echo get_permalink( $confirm_page->ID ); ?>" name="frm-send-user-voice" id="frm-send-user-voice">
        <div class="head">
            <div class="row">
                <div class="col-xs-12 col-sm-8">
                    <h3><?php echo 'お客様の声をお聞かせください'; ?></h3>
                </div>
                <div class="col-xs-12 col-sm-4" align="right">
                    <input type="hidden" id="uservoice_send_home_url" name="uservoice_send_home_url" value="<?php echo get_home_url(); ?>" />
                    <input type="hidden" id="uservoice_send_tg_id" name="uservoice_send_tg_id" value="<?php echo $tg_id; ?>" />
                    <input type="hidden" id="uservoice_send_dis_id" name="uservoice_send_dis_id" value="<?php echo $dis_id; ?>" />
                    <input type="hidden" id="uservoice_send_category" name="uservoice_send_category" value="<?php echo $category; ?>" />
                    <input type="hidden" id="uservoice_send_post_id" name="uservoice_send_post_id" value="<?php echo $postid; ?>" />
                    <input type="hidden" id="wordpress_post_id" name="wordpress_post_id" value="<?php echo $post->ID; ?>" />
                    <button type="submit" class="btn-user-voice" id="btn-send-user-voice">
                        <?php echo '投稿確認'; ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="table">
            <table width="100%" style="">
                <tbody>
                    <tr>
                        <td class="input_label">
                            <?php echo 'お問い合わせの種類'; ?>
                            <span class="info"></span>
                        </td>
                        <td class="input_data">
                            <?php if ( count( $inquiry_type_list ) > 0 && count( $inquiry_type_setting ) > 0 ) { ?>
                                <?php foreach ( $inquiry_type_list as $inquiry_type ) { ?>
                                    <?php foreach ( $inquiry_type_setting as $id ) { ?>
                                        <?php if ( $inquiry_type['id'] == $id ) { ?>
                                            <label><input type="radio" name="uservoice_send_inquiry_type[]" value="<?php echo $inquiry_type['name']; ?>" <?php if ( !empty ( $data_uv['inquiry_type'] ) && $data_uv['inquiry_type'] == $inquiry_type['name'] ) { echo 'checked="true"'; } ?> /> <?php echo $inquiry_type['name']; ?></label>
                                        <?php continue; } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } else { ?>
                                <?php echo '「お客様の声」の編集画面で設定ください。'; ?>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="input_label">
                            <?php echo '投稿者'; ?>
                            <span class="info"></span>
                        </td>
                        <td class="input_data">
                            <input type="text" id="uservoice_send_c_name" name="uservoice_send_c_name" maxlength="200" value="<?php if ( !empty ( $data_uv['c_name'] ) ) { echo $data_uv['c_name']; }; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="input_label">
                            <?php echo 'メールアドレス'; ?>
                            <span class="info"></span>
                        </td>
                        <td class="input_data">
                            <input type="text" id="uservoice_send_mail" name="uservoice_send_mail" maxlength="100" value="<?php if ( !empty ( $data_uv['mail'] ) ) { echo $data_uv['mail']; }; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="input_label">
                            <?php echo '機能'; ?>
                            <span class="info"></span>
                        </td>
                        <td class="input_data">
                            <?php if ( count( $function_list ) > 0 && count( $function_setting ) > 0 ) { ?>
                                <select name="uservoice_send_function" id="uservoice_send_function" required>
                                    <option value=""></option>
                                    <?php foreach ( $function_list as $function ) { ?>
                                        <?php foreach ( $function_setting as $id ) { ?>
                                            <?php if ( $function['id'] == $id ) { ?>
                                                <option value="<?php echo $function['name'] ?>" <?php if ( !empty ( $data_uv['function'] ) && $data_uv['function'] == $function['name'] ) { echo 'selected="true"'; } ?>>
                                                    <?php echo $function['name']; ?>
                                                </option>
                                            <?php continue; } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            <?php } else { ?>
                                <?php echo '「お客様の声」の編集画面で設定ください。'; ?>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="input_label">
                            <?php echo 'お声の内容'; ?>
                            <span class="info"></span>
                        </td>
                        <td class="input_data">
                            <textarea cols="20" id="uservoice_send_body" name="uservoice_send_body" rows="2" style="width:95%; height:300px;" required><?php if ( !empty ( $data_uv['body'] ) ) { echo $data_uv['body']; }; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="input_label">
                            <?php echo '添付ファイル'; ?>
                        </td>
                        <td class="input_data">
                            <div class="row">
                                <?php for ( $i = 1; $i <= 3; $i++ ) { ?>
                                    <div class="col-sm-4 col-xs-12">
                                        <?php
                                            $file_display = !empty ( $data_uv['file_display_' . $i] ) ? $data_uv['file_display_' . $i] : '';
                                            $file = !empty ( $data_uv['file_' . $i] ) ? $data_uv['file_' . $i] : '';
                                        ?>
                                        <input type="hidden" name="uservoice_send_file_<?php echo $i; ?>" id="uservoice_send_file_<?php echo $i; ?>" value="<?php if ( !empty ( $file ) ) { echo $file; }; ?>" />
                                        <input type="hidden" name="uservoice_send_file_display_<?php echo $i; ?>" id="uservoice_send_file_display_<?php echo $i; ?>" value="<?php if ( !empty ( $file_display ) ) { echo $file_display; }; ?>" />
                                        <button type="button" class="btn-upload-file" id="btn-upload-file-<?php echo $i; ?>" onclick="openAddFIleModal(<?php echo $i; ?>)" <?php if ( !empty ( $file_display ) ) { ?>style="display: none"<?php } ?>></button>
                                        <img src="<?php echo $controller->getDisplayFile( $file_display ); ?>" id="uservoice_img_display_<?php echo $i; ?>" align="middle" <?php if ( empty ( $file_display ) ) { ?>style="display: none; width: 96px"<?php } ?>>
                                        <br />
                                        <button type="button" class="btn-user-voice" id="btn_clear<?php echo $i; ?>" style="<?php if ( empty ( $file_display ) ) { ?>display:none;<?php } ?>padding: .5em 1.12em;" onclick="clearImageControl(<?php echo $i; ?>);">
                                            <?php echo 'クリア'; ?>
                                        </button>
                                    </div>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
<div class="modal-upload-file">
    <div class="modal-bg"></div>
    <div class="modal-box">
        <div class="box-head">
            <span class="btn-close">x</span>
        </div>
        <form method="POST" action="" id="frm-upload-file" name="frm-upload-file" enctype="multipart/form-data">
            <div class="box-content">
                <h2><?php echo 'ファイルのアップロード'; ?></h2>
                <div class="box-main">
                    <label for="photo">
                        <input type="file" name="uservoice_send_file_upload" id="uservoice_send_file_upload" />
                        <input type="hidden" id="uservoice_file_validate" name="uservoice_file_validate" value="<?php echo implode( ',', $validate_files ); ?>" />
                        <input type="hidden" id="uservoice_file_upload_post_id" name="uservoice_file_upload_post_id" value="<?php echo $postid; ?>" />
                        <input type="hidden" name="uservoice_send_file_number" id="uservoice_send_file_number" />
                        <input type="hidden" name="uservoice_send_file_folder" id="uservoice_send_file_folder" value="<?php echo plugins_url( 'nakama-uservoice/assets/img/' ); ?>" />
                        <input type="hidden" name="action" value="send_upload_file" />
                    </label>
                    <div style="width:100%;margin-top:5px;">
                        <button class="btn-user-voice" id="btn-uservoice-upload-file">
                            <?Php echo 'アップロード'; ?>
                        </button>
                        <br />
                        <br />
                        <?php echo '１．【参照】ボタンクリックでファイルを選択。'; ?>
                        <br />
                        <?php echo '２．【アップロード】ボタンクリックでファイルがアップロードされます。'; ?>
                        <table width="100%" border="0" cellspacing="1" cellpadding="3">
                            <tr>
                                <td colspan="2" align="left" class="textred">
                                    <?php echo '※【アップロード可能な拡張子】'; ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" class="textred" style="width: 140px; vertical-align: top">
                                    <?php if ( count( $file_type_list ) > 0 && count( $file_type_list ) > 0 ) { ?>
                                        <?php foreach ( $file_type_list as $category_type_file ) { ?>
                                            <?php $is_type_display = false; ?>
                                            <?php foreach( $category_type_file['types'] as $file_type ) { ?>
                                                <?php foreach ( $file_type_setting as $id ) { ?>
                                                    <?php if ( $file_type['id'] == $id ) { ?>
                                                        <?php $is_type_display = true; ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if ( count( $category_type_file['types'] ) > 0 && $is_type_display === true ) { ?>
                                                <?php echo '・' . $category_type_file['name']; ?>
                                                <br />
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                                <td align="left" class="textred">
                                    <?php if ( count( $file_type_list ) > 0 && count( $file_type_list ) > 0 ) { ?>
                                        <?php foreach ( $file_type_list as $category_type_file ) { ?>
                                            <?php if ( count( $category_type_file['types'] ) > 0 ) { ?>
                                                <?php $files = array(); ?>
                                                <?php foreach( $category_type_file['types'] as $file_type ) { ?>
                                                    <?php foreach ( $file_type_setting as $id ) { ?>
                                                        <?php if ( $file_type['id'] == $id ) { ?>
                                                            <?php $files[] = '「' . $file_type['name'] . '」'; ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php
                                                    if ( !empty( $files ) ) {
                                                        echo '：　' . implode( '', $files );
                                                ?>
                                                        <br />
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </form>
        <form method="POST" action="" id="frm-reside-file" name="frm-reside-file" style="display:none;">
            <div class="box-content">
                <h2><?php echo '画像の編集（縮小・回転）を行って、「画像確定」をクリックして下さい。'; ?></h2>
                <div class="box-main">
                    <input type="hidden" id="resize_action" name="action" value="send_reside_file" />
                    <input type="hidden" id="uservoice_send_image_file" name="uservoice_send_image_file" value="" />
                    <input id="uservoice_send_image_slide" data-slider-id='imageSlide' type="text" data-slider-min="30" data-slider-max="50" data-slider-step="1" data-slider-value="50"/>
                    <button type="button" class="btn-user-voice" id="btn-uservoice-choice-image" onclick="imageResizeUserVoice()">
                        <?Php echo '画像確定'; ?>
                    </button>
                    <button type="button" class="btn-user-voice" id="btn-uservoice-rotation-image" onclick="imageRotationUserVoice()">
                        <?Php echo '90ﾟ回転'; ?>
                    </button>
                    <div id="ImageResizeGrid" class="imageGrid"></div>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>