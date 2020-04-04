<?php

define('__ROOT__', dirname(dirname(dirname(__FILE__))));
require_once(__ROOT__.'/config/constant.php');
require_once(__ROOT__.'/controller/memberController.php');
$members = new memberController();
$header_file = $dataSetting['nak_member_magazine_disp_header_file_reg'][0];
$tg_id = (isset($_SESSION['arrSession']->TG_ID))?$_SESSION['arrSession']->TG_ID:$tg_id;
$p_id = (isset($_SESSION['arrSession']->P_ID))?$_SESSION['arrSession']->P_ID: '';
$g_chg = 0;
$pgh_relmail = '';
$b_user_id = "";
$mode = isset($_REQUEST['mode'])?$_REQUEST['mode']:'';
$arrSelect = array();
$arrSelect['G_Chg'] = "0";
$arrSelect['Relmail'] = isset($_SESSION['arrSession']->C_EMAIL)?$_SESSION['arrSession']->C_EMAIL:"";
$arrSelect['Pgh_Relmail'] = isset($_SESSION['arrSession']->C_CC_EMAIL)?$_SESSION['arrSession']->C_CC_EMAIL:"";
$arrSelect['B_User_Id'] = "";
$selectData = MemberCrSet::selectData($postid,$arrSelect);
if($mode == 'deleteConfirm' || $mode == 'delete'){
   $relmail = isset($_REQUEST['P_C_EMAIL'])?$_REQUEST['P_C_EMAIL']:'';
   $pgh_relmail = isset($_REQUEST['P_C_EMAIL2'])?$_REQUEST['P_C_EMAIL2']:'';
   $p_id = isset($_REQUEST['p_id'])?$_REQUEST['p_id']:'';
   $deleteMailMagazine = $members->deleteMailMagazine($postid, $p_id, $tg_id, $g_chg, $relmail, $pgh_relmail, $b_user_id);
   if($deleteMailMagazine->status == "Success"){
      $P_C_NAME = $selectData->PcName;
      $P_C_KANA = $selectData->PcKana;
      $emailfrom = get_post_meta($postid, "nak_member_magazine_mail_address", true);
      $members->SendDeleteMail($postid,$tg_id,$p_id, $P_C_NAME, $relmail);
   }
}
?>
<!DOCTYPE html">
<html lang="ja">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
  <title>メルマガ購読中止申請申込みメールの送信</title>
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname(dirname( __FILE__ ) )); ?>assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname(dirname( __FILE__ ) )); ?>assets/css/smart.css">
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname(dirname( __FILE__ ) )); ?>assets/css/del_magazine.css">
  <script type="text/javascript" src="<?php echo plugin_dir_url( dirname(dirname( __FILE__ ) )); ?>assets/js/common.js"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url( dirname(dirname( __FILE__ ) )); ?>assets/js/sedai_link.js"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url( dirname(dirname( __FILE__ ) )); ?>assets/js/inputcheck.js"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url( dirname(dirname( __FILE__ ) )); ?>assets/js/del_magazine.js"></script>
</head>
<body onunload="OnUnload();" onload="OnLoad();">
   <div class="container">
      <?php if(empty($mode)) : ?>
      <?php
        if(empty($header_file)){
          echo MemberCrSet::convertFile(dirname(dirname(dirname(__FILE__)))."/settingform/inc/mail_magazine_del.inc");
        } else {
          echo MemberCrSet::convertFile(dirname(dirname(dirname(__FILE__)))."/settingform/inc/".$header_file);
        }
      ?>
      <?php if(!isset($_SESSION['arrSession'])): ?>
      <form name="mainForm" id="mainForm" enctype="multipart/form-data" method="POST" autocomplete="off">
         <table border="0" cellspacing="0" cellpadding="5">
            <tbody>
               <tr>
                  <td colspan="3">購読中止の申請をする</td>
               </tr>
            </tbody>
         </table>
         <table class="input_table" border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody>
               <tr class="">
                  <td class="input_title" width="180" nowrap="">入力項目</td>
                  <td class="input_title" nowrap="">記入欄</td>
               </tr>
               <tr>
                  <td class="input_item_need">E-MAIL</td>
                  <td class="input_value">
                     <input size="50" maxlength="100" style="ime-mode: active;" type="text" name="P_C_EMAIL" value="">&nbsp;<span style="font-size:x-small">（入力例：ytaro@dynax.co.jp）</span>
                  </td>
               </tr>
               <tr>
                  <td class="input_item_need">※E-MAIL再入力</td>
                  <td class="input_value"><input size="50" maxlength="100" style="ime-mode: disabled;" type="text" name="P_C_EMAIL2" value="" autocomplete="nope">&nbsp;<span style="font-size:x-small">（入力例：ytaro@dynax.co.jp）</span></td>
               </tr>
               <input type="hidden" name="G_NAME" value="">
               <input type="hidden" name="P_C_NAME" value="">
               <input type="hidden" name="P_C_KANA" value="">
            </tbody>
         </table>
         <br>
         <table align="center" border="0" cellspacing="2" cellpadding="5">
            <tbody>
               <tr>
                  <td align="center">
                     <input type="button" class="base_button" value="キャンセル" onclick="moveBackPage();">
                     <input type="button" class="base_button" value="購読中止申請" onclick="OnConfirm(&#39;chk&#39;,'<?php echo $members->getPageSlug('nakama-member-check-magazine'); ?>');">
                  </td>
               </tr>
            </tbody>
         </table>
         <input type="hidden" name="mode" value="">
         <input type="hidden" name="page_no" value="">
         <input type="hidden" name="patten_cd" value="">
         <input type="hidden" name="set_lg_g_id" value="">
         <input type="hidden" name="chg" value="">
         <input type="hidden" name="top_g_id" value="">
         <input type="hidden" name="p_id" value="">
         <input type="hidden" name="forward_mail" value="">
         <input type="hidden" name="postid" value="<?php echo $postid; ?>">
         <iframe name="getData" src="<?php echo $members->getPageSlug('nakama-member-check-magazine'); ?>" style="display:none"></iframe>
      </form>
      <?php else: ?>
      <form name="mainForm" id="mainForm" enctype="multipart/form-data" method="POST">
         <table class="input_table" border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody>
               <tr>
                  <td class="input_title" width="180" nowrap="">入力項目</td>
                  <td class="input_title" nowrap="">記入欄</td>
               </tr>
               <tr>
                  <td class="input_item_need">E-MAIL</td>
                  <td class="input_value">
                     <?php echo $_SESSION['arrSession']->C_EMAIL; ?>
                     <input type="hidden" name="P_C_EMAIL" value="<?php echo $_SESSION['arrSession']->C_EMAIL; ?>">
                     <input type="hidden" name="P_C_EMAIL2" value="<?php echo $_SESSION['arrSession']->C_EMAIL; ?>">
                  </td>
               </tr>
               <input type="hidden" name="G_NAME" value="">
               <input type="hidden" name="P_C_NAME" value="">
               <input type="hidden" name="P_C_KANA" value="">
            </tbody>
         </table>
         <br>
         <table align="center" border="0" cellspacing="2" cellpadding="5">
            <tbody>
               <tr>
                  <td align="center">
                     <input type="button" class="base_button" value="購読中止申請" onclick="OnConfirm(&#39;&#39;,'');">
                  </td>
               </tr>
            </tbody>
         </table>
          <input type="hidden" name="mode" value="">
          <input type="hidden" name="page_no" value="">
          <input type="hidden" name="patten_cd" value="">
          <input type="hidden" name="set_lg_g_id" value="">          <!-- 設定下部組織ＩＤ -->

          <input type="hidden" name="chg" value="">
          <input type="hidden" name="top_g_id" value="">
          <input type="hidden" name="p_id" value="<?php echo $_SESSION['arrSession']->P_ID; ?>">
          <input type="hidden" name="forward_mail" value="">
          <input type="hidden" name="postid" value="<?php echo $postid; ?>">
          <iframe name="getData" src="<?php echo $members->getPageSlug('nakama-member-check-magazine'); ?>" style="display:none"></iframe>
      </form>
      <?php endif; ?>
      <?php endif; ?>
      <!-- Magazine login -->


      <!-- delete -->
      <?php if($mode == 'deleteConfirm' || $mode == 'delete') : ?>
      <div align="center">
      <h1 class="page_title" style="width:100%">メルマガ購読中止</h1>
      </div><br><br>
      <form name="mainForm3" id="mainForm3" enctype="multipart/form-data">
        <table width="800" align="center" border="0" cellspacing="2" cellpadding="5">
          <tbody><tr>
            <td align="center">
              <br><br>
              下記のメールアドレスにメルマガ購読中止申請メールを送信しました<br>
              E-MAIL：<?php echo $relmail; ?>
              <br><br>
              送信されたメールに記載されているＵＲＬより、購読中止を確定してください。
            </td>
          </tr>
          <tr>
            <td align="center">
              <br><br><br>
              <input type="button" class="base_button" value="トップへ戻る" onclick="window.location.href='<?php echo get_home_url(); ?>'">
            </td>
          </tr>
        </tbody></table>
      </form>
      <?php endif;?>
      <!-- delete -->
   </div>
</body>
</html>
