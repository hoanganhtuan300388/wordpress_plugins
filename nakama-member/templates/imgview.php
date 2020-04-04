<!DOCTYPE HTML>
<html lang="ja">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
  <title>画像詳細</title>
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/smart.css">
</head>
<body>
  <?php
    $img = ROOT_IMG."/data/imgs/".$_GET['path'];
  ?>
  <form>
  <input type="button" value="　戻る　" onClick="history.back();"><br>
  <img src="<?php echo $img; ?>">
  </form>
</body>
</html>