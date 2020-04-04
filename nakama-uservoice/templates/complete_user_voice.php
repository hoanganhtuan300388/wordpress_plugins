<?php
$controller = new uservoiceController();
$response = $controller->postUserVoiceConfirm();
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
    <script>
        $(document).ready(function() {
            $('#btn-close-user-voice').click(function() {
                javascript:window.open('<?php echo get_home_url(); ?>', '_self').close();
            });
        });
    </script>
</head>
<body>
<div class="container complete">
    <div class="col-xs-12 col-sm-12" align="center" style="margin-top: 15%">
        <h4>
            <?php
                if ( isset( $response->Message ) ) {
                    echo $response->Message;
                } else {
                    echo 'お声を投稿しました。';
                }
            ?>
        </h4>
        <br />
        <br />
        <br />
        <button type="button" class="btn-user-voice" id="btn-close-user-voice">
            <?php echo '閉じる'; ?>
        </button>
    </div>
</div>
</body>
</html>