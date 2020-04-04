<!DOCTYPE HTML>
<html lang="ja">
<head>
  <title>アンケート参考添付ファイル</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0" />
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/smart.css">
  <script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/common.js"></script>
  <script type="text/javascript">
    function OnDownloadFile(top_g_id, reserch_id, fileName) {
      document.frmDownload.top_g_id.value = top_g_id;
      document.frmDownload.reserch_id.value = reserch_id;
      document.frmDownload.filename.value = fileName;
      document.frmDownload.action = "detailDownload.asp";
      document.frmDownload.submit();
    }
  </script>
</head>
<body>
  <form id="mainForm" name="mainForm" method="post">
    <table border="0" align="center">
      <tr>
        <td align="center">
          <img src="" allowtransparency="true">
        </td>
      </tr>
      <tr>
        <td><br></td>
      </tr>
      <tr>
        <td align="center">
          <?php if ($_REQUEST["dl"] == "1") : ?>
            <input type="button" class="base_button" value="ダウンロード" onClick="Javascript: OnDownloadFile('');">&nbsp;&nbsp;
          <?php endif; ?>
          <input type="button" class="base_button" value="閉じる" OnClick="window.close();">
        </td>
      </tr>
    </table>
  </form>
  <form id="frmDownload" name="frmDownload" method="post" action="detailDownload.asp">
    <input type="hidden" name="top_g_id" value="">
    <input type="hidden" name="reserch_id" value="">
    <input type="hidden" name="filename" value="">
  </form>
</body>
</html>