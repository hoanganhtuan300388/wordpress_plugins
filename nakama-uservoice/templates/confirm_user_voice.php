<?php
$controller = new uservoiceController();
$user_voice_data = $controller->postUserVoiceSend();
$complete_page = get_page_by_path( 'nakama-complete-uservoice', OBJECT, 'page' );
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

    <script src="<?php echo plugins_url( 'nakama-uservoice/assets/js/jquery-3.3.1.min.js' ); ?>"></script>
    <script src="<?php echo plugins_url( 'nakama-uservoice/assets/js/bootstrap.min.js' ); ?>"></script>
</head>
<body>
<div class="container confirm">
    <form method="POST" action="<?php echo get_permalink( $complete_page->ID ); ?>" name="frm-confirm-user-voice" id="frm-confirm-user-voice">
        <div class="table">
            <table>
                <tr>
                    <td class="input_label">
                        <?php echo 'お問い合わせの種類'; ?>
                    </td>
                    <td class="input_data">
                        <?php echo $user_voice_data['inquiry_type']; ?>
                        <input type="hidden" id="uservoice_send_tg_id" name="uservoice_send_tg_id" value="<?php echo $user_voice_data['tg_id']; ?>" />
                        <input type="hidden" id="uservoice_send_dis_id" name="uservoice_send_dis_id" value="<?php echo $user_voice_data['dis_id']; ?>" />
                        <input type="hidden" id="uservoice_send_category" name="uservoice_send_category" value="<?php echo $user_voice_data['category']; ?>" />
                        <input type="hidden" id="uservoice_send_post_id" name="uservoice_send_post_id" value="<?php echo $user_voice_data['post_id']; ?>" />
                        <input type="hidden" id="uservoice_send_inquiry_type" name="uservoice_send_inquiry_type" value="<?php echo $user_voice_data['inquiry_type']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="input_label">
                        <?php echo '投稿者'; ?>
                    </td>
                    <td class="input_data">
                        <?php echo $user_voice_data['c_name']; ?>
                        <input type="hidden" id="uservoice_send_c_name" name="uservoice_send_c_name" value="<?php echo $user_voice_data['c_name']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="input_label">
                        <?php echo 'メールアドレス'; ?>
                    </td>
                    <td class="input_data">
                        <?php echo $user_voice_data['mail']; ?>
                        <input type="hidden" id="uservoice_send_mail" name="uservoice_send_mail" value="<?php echo $user_voice_data['mail']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="input_label">
                        <?php echo '機能'; ?>
                    </td>
                    <td class="input_data">
                        <?php echo $user_voice_data['function']; ?>
                        <input type="hidden" id="uservoice_send_function" name="uservoice_send_function" value="<?php echo $user_voice_data['function']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="input_label">
                        <?php echo 'お声の内容'; ?>
                    </td>
                    <td class="input_data">
                        <?php echo nl2br( $user_voice_data['body'] ); ?>
                        <input type="hidden" id="uservoice_send_body" name="uservoice_send_body" value="<?php echo $user_voice_data['body']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="input_label">
                        <?php echo '★添付ファイル'; ?>
                    </td>
                    <td class="input_data">
                        <?php if ( !empty( $user_voice_data['file_1'] ) ) { ?>
                            <?php echo basename( $user_voice_data['file_1'] ); ?>
                            <br />
                        <?php } ?>
                        <?php if ( !empty( $user_voice_data['file_2'] ) ) { ?>
                            <?php echo basename( $user_voice_data['file_2'] ); ?>
                            <br />
                        <?php } ?>
                        <?php if ( !empty( $user_voice_data['file_3'] ) ) { ?>
                            <?php echo basename( $user_voice_data['file_3'] ); ?>
                            <br />
                        <?php } ?>
                        <input type="hidden" id="uservoice_send_file_1" name="uservoice_send_file_1" value="<?php echo $user_voice_data['file_1']; ?>" />
                        <input type="hidden" id="uservoice_send_file_2" name="uservoice_send_file_2" value="<?php echo $user_voice_data['file_2']; ?>" />
                        <input type="hidden" id="uservoice_send_file_3" name="uservoice_send_file_3" value="<?php echo $user_voice_data['file_3']; ?>" />
                    </td>
                </tr>
            </table>
        </div>
        <div class="row" align="center">
            <div class="col-xs-12 col-sm-12">
                <?php echo 'この内容でお声を投稿します。'; ?>
                <br />
                <?php echo 'よろしいですか？'; ?>
            </div>
        </div>
        <br />
        <div class="row" align="center">
            <button type="submit" class="btn-user-voice" id="btn-confirm-user-voice">
                <?php echo 'この内容で投稿する'; ?>
            </button>
            <?php
                $previous = "javascript:history.go(-1)";
                if( isset( $_POST['wordpress_post_id'] ) ) {
                    $fun = count( $_GET ) == 0 ? "?func=back" : "&func=back";
                    $previous = "location.href='" . get_permalink( $_POST['wordpress_post_id'] ) . $fun . "'";
                }
            ?>
            <button type="button" class="btn-user-voice" id="btn-back-user-voice" onclick="<?php echo $previous; ?>">
                <?php echo '入力し直す'; ?>
            </button>
        </div>
    </form>
</div>
</body>
</html>