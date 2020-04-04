<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/config/constant.php');
require_once(__ROOT__.'/controller/memberController.php');
$members = new memberController();
$checkMail = '';
$checkMailMagazine = '';
$delMailMagazine = '';
$func = isset($_REQUEST['func'])?$_REQUEST['func']:'';
$postid = isset($_REQUEST['postid'])?$_REQUEST['postid']:'';
$tgId = (isset($_SESSION['arrSession']->TG_ID))?$_SESSION['arrSession']->TG_ID:get_post_meta($postid,"nak_member_magazine_tg_id",true);
$gId = (isset($_SESSION['arrSession']->G_ID))?$_SESSION['arrSession']->G_ID:get_post_meta($postid,"nak_member_magazine_g_id",true);
$p_id = (isset($_SESSION['arrSession']->P_ID))?$_SESSION['P_ID']->G_ID:"";
if ($func == 'registMail') {
  $email = isset($_REQUEST['value'])?$_REQUEST['value']:'';
  $checkMailMagazine = $members->checkMailMagazine($postid,$tgId, $email, $gId);
  if(is_numeric($checkMailMagazine)) {
    $checkMail = $checkMailMagazine;
  }
}
if ($func == 'CheckMailMagazineDel') {
  $email = isset($_REQUEST['value'])?$_REQUEST['value']:'';
  $delMailMagazine = $members->checkMailMagazineDelete($postid, $tgId, $email, $gId,$p_id);
}

?>
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html;charset=shift_jis">
<meta name="robots" content="none">
<meta name="robots" content="noindex,nofollow">
<meta name="robots" content="noarchive">
<title></title>
<script language="JavaScript">
function setData() {
  <?php if($func == 'registMail') { ?>
  parent.retSearchVal_CheckMailMagazine('<?php echo $checkMail; ?>', '');
  <?php }
  if($func == 'CheckMailMagazineDel') { ?>
  parent.retSearchVal_CheckMailMagazineDel('<?php echo $delMailMagazine; ?>', '');
  <?php } ?>
}
</script>
</head>
<body onload="setData();">
<form></form>
</body>
</html>
