<?php
define('__ROOT__', dirname(dirname(dirname(__FILE__))));
require_once(__ROOT__.'/config/constant.php');
require_once(__ROOT__.'/controller/memberController.php');
$members = new memberController();
$header_file = $dataSetting['nak_member_magazine_disp_header_file_reg'][0];
$arrValue = array();
$tgId = (isset($_SESSION['arrSession']->TG_ID))?$_SESSION['arrSession']->TG_ID:$tg_id;
$p_id = (isset($_SESSION['arrSession']->P_ID))?$_SESSION['arrSession']->P_ID: '';
$gId = (isset($_SESSION['arrSession']->G_ID))?$_SESSION['arrSession']->G_ID:$g_id;
$lgId= (isset($_SESSION['arrSession']->LG_ID))?$_SESSION['arrSession']->LG_ID:"";
$mode = isset($_REQUEST['mode'])?$_REQUEST['mode']:'';
$G_NAME = isset($_REQUEST['G_NAME'])?$_REQUEST['G_NAME']:'';
$P_C_NAME = isset($_REQUEST['P_C_NAME'])?$_REQUEST['P_C_NAME']:'';
$C_NAME = explode("　", $P_C_NAME);
$P_C_KANA = isset($_REQUEST['P_C_KANA'])?$_REQUEST['P_C_KANA']:'';
$P_KANA = explode("　", $P_C_KANA);
$emailRegist = isset($_REQUEST['P_C_EMAIL'])?$_REQUEST['P_C_EMAIL']:'';
$page_link = MemberCrSet::getPageSlug('nakama-member-check-magazine');
if($mode == 'update'){
  $email = isset($_REQUEST['P_C_EMAIL'])?$_REQUEST['P_C_EMAIL']:'';
  $checkMailMagazine = MemberCrSet::registMailMagazine($postid,$tgId, $email, $gId, $lgId);
  if(isset($checkMailMagazine->status) && $checkMailMagazine->status == 'Success'){
    $members->SendRegistMail($postid,$tgId,$p_id, $P_C_NAME, $emailRegist);
  }
}elseif ($mode == 'insert') {
  $arrValue['email'] = isset($_REQUEST['P_C_EMAIL'])?$_REQUEST['P_C_EMAIL']:'';
  $relmail = isset($_REQUEST['P_C_EMAIL'])?$_REQUEST['P_C_EMAIL']:'';
  $arrValue['TG_ID'] = $tgId;
  $arrValue['LG_ID'] = $lgId;
  $arrValue['TG_USER_ID'] = "tgUserIdInsertData";
  $arrValue['TG_NAME'] = $G_NAME;
  $arrValue['C_FNAME'] = isset($C_NAME[0])?$C_NAME[0]:'';
  $arrValue['C_LNAME'] = isset($C_NAME[1])?$C_NAME[1]:'';
  $arrValue['C_FNAME_KN'] = isset($P_KANA[0])?$P_KANA[0]:'';
  $arrValue['C_LNAME_KN'] = isset($P_KANA[1])?$P_KANA[1]:'';
  $arrValue['USER_P_ID'] = "";
  $checkMailMagazine = $members->insertData($postid,$arrValue);
  if(isset($checkMailMagazine->Status) && $checkMailMagazine->Status == 0){
    $members->SendRegistMail($postid,$tgId,$p_id, $P_C_NAME, $emailRegist);
  }
}
?>
<!DOCTYPE html PUBLIC">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
<title>メルマガ会員登録</title>
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/smart.css">
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/css/magazine.css">
<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/common.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/sedai_link.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/inputcheck.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/magazine.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>../assets/js/jquery-1.6.3.min.js"></script>
<script>
  var ajaxurl = "<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>templates/ajax.php";
</script>
</head>
<body onunload="OnUnload();" onload="OnLoad();">

<div class="container">
<?php if(empty($mode)) : ?>
<?php
if(empty($header_file)){
  echo MemberCrSet::convertFile(dirname(dirname(dirname(__FILE__)))."/settingform/inc/mail_magazine_reg.inc");
} else {
  echo MemberCrSet::convertFile(dirname(dirname(dirname(__FILE__)))."/settingform/inc/".$header_file);
}
?>
<?php endif; ?>
<?php
if(!isset($_SESSION['arrSession'])): ?>
  <?php if (empty($mode)) : ?>
    <form name="mainForm" id="mainForm" enctype="multipart/form-data" method="post" action=""  autocomplete="off">
      <table border="0" cellspacing="0" cellpadding="5">
        <tbody>
          <tr>
            <td align="left"><span style="color:red;">※マークの項目は必須入力です。</span></td>
          </tr>
        </tbody>
      </table>
      <table class="input_table" width="800" border="0" align="center" cellpadding="0" cellspacing="0">
        <tbody>
          <tr class="flex-sp">
            <td class="input_title" width="180" nowrap="">入力項目</td>
            <td class="input_title" nowrap="">記入欄</td>
          </tr>
          <tr class="flex-sp">
            <td class="input_item"><span style="color: #000">会社又は組織名</span></td>
            <td class="input_value">
              <input size="50" maxlength="100" style="ime-mode: active;" type="text" name="G_NAME" value="">&nbsp;<span style="font-size:x-small">（入力例：(株)ダイナックス）</span>
            </td>
          </tr>
          <tr class="flex-sp">
            <td class="input_item_need">※氏名</td>
            <td class="input_value">
              <input size="50" maxlength="100" style="ime-mode: active;" type="text" name="P_C_NAME" value="">&nbsp;<span style="font-size:x-small">（入力例：山田　太郎）</span>
            </td>
          </tr>
          <tr class="flex-sp">
            <td class="input_item_need">※氏名フリガナ</td>
            <td class="input_value">
              <input size="50" maxlength="100" style="ime-mode: active;" type="text" name="P_C_KANA" value="">&nbsp;<span style="font-size:x-small">（入力例：ヤマダ　タロウ）</span>
            </td>
          </tr>
          <tr class="flex-sp">
            <td class="input_item_need">※E-MAIL</td>
            <td class="input_value">
              <input size="50" maxlength="100" style="ime-mode: disabled;" type="text" name="P_C_EMAIL" value="">&nbsp;<span style="font-size:x-small">（入力例：ytaro@dynax.co.jp）</span>
            </td>
          </tr>
          <tr class="flex-sp">
            <td class="input_item_need">※E-MAIL再入力</td>
            <td class="input_value">
              <input size="50" maxlength="100" style="ime-mode: disabled;" type="text" name="P_C_EMAIL2" value="" autocomplete="nope">&nbsp;<span style="font-size:x-small">（入力例：ytaro@dynax.co.jp）</span>
            </td>
          </tr>
        </tbody>
      </table>
      <br>
      <table width="100%" align="center" border="0" cellspacing="2" cellpadding="5">
        <tbody>
          <tr>
            <td align="center">
              <input type="button" class="base_button" value="送　信" onclick="OnConfirm('<?php echo $page_link; ?>');">
            </td>
          </tr>
        </tbody>
      </table>
      <input type="hidden" name="mode" value="insert">
      <input type="hidden" name="page_no" value="29">
      <input type="hidden" name="patten_cd" value="">
      <input type="hidden" name="set_lg_g_id" value="dmshibuyag064368">
      <input type="hidden" name="chg" value="0">
      <input type="hidden" name="top_g_id" value="dmshibuya">
      <input type="hidden" name="p_id" value="">
      <input type="hidden" name="forward_mail" value="info@dynax.co.jp">
      <input type="hidden" name="postid" value="<?php echo $postid; ?>">
      <input type="hidden" name="urlcheck" value="<?php echo $members->getPageSlug('nakama-member-check-magazine'); ?>">
      <iframe name="getData" src="" style="display:none"></iframe>
    </form>
  <?php endif; ?>
<!-- Magazine login -->
<?php else: ?>
  <?php
    $arrSelect = array();
    $arrSelect['G_Chg'] = "0";
    $arrSelect['Relmail'] = ($_SESSION['arrSession']->C_EMAIL)?$_SESSION['arrSession']->C_EMAIL:"";
    $arrSelect['Pgh_Relmail'] = ($_SESSION['arrSession']->C_CC_EMAIL)?$_SESSION['arrSession']->C_CC_EMAIL:"";
    $arrSelect['B_User_Id'] = "";
    $selectData = MemberCrSet::selectData($postid,$arrSelect);
    $EMail = ($_SESSION['arrSession']->C_EMAIL)?$_SESSION['arrSession']->C_EMAIL:"";
    $TG_ID = ($_SESSION['arrSession']->TG_ID)?$_SESSION['arrSession']->TG_ID:get_option('nakama-member-group-id');
    $G_ID = ($_SESSION['arrSession']->G_ID)?$_SESSION['arrSession']->G_ID:"";
    $checkMailMagazine = MemberCrSet::checkMailMagazine($postid,$TG_ID, $EMail, $G_ID);
    if(is_numeric($checkMailMagazine)) {
      $checkMail = $checkMailMagazine;
    }
    if($checkMail == -2 || $checkMail == -1):
  ?>
  <?php if (empty($mode)) : ?>
    <form name="mainForm" id="mainForm" enctype="multipart/form-data" method="post" action=""  autocomplete="off">
      <table border="0" cellspacing="0" cellpadding="5">
        <tbody>
          <tr>
            <td align="left"><span style="color:red;">※マークの項目は必須入力です。</span></td>
          </tr>
        </tbody>
      </table>
      <table class="input_table" width="800" border="0" align="center" cellpadding="0" cellspacing="0">
        <tbody>
          <tr class="flex-sp">
            <td class="input_title" width="180" nowrap="">入力項目</td>
            <td class="input_title" nowrap="">記入欄</td>
          </tr>
          <tr class="flex-sp">
            <td class="input_item"><span style="color: #000">会社又は組織名</span></td>
            <td class="input_value">
              <input size="50" maxlength="100" style="ime-mode: active;" type="text" name="G_NAME" value="<?php echo $selectData->GName; ?>">&nbsp;<span style="font-size:x-small">（入力例：(株)ダイナックス）</span>
            </td>
          </tr>
          <tr class="flex-sp">
            <td class="input_item_need">※氏名</td>
            <td class="input_value">
              <input size="50" maxlength="100" style="ime-mode: active;" type="text" name="P_C_NAME" value="<?php echo $selectData->PcName; ?>">&nbsp;<span style="font-size:x-small">（入力例：山田　太郎）</span>
            </td>
          </tr>
          <tr class="flex-sp">
            <td class="input_item_need">※氏名フリガナ</td>
            <td class="input_value">
              <input size="50" maxlength="100" style="ime-mode: active;" type="text" name="P_C_KANA" value="<?php echo $selectData->PcKana; ?>">&nbsp;<span style="font-size:x-small">（入力例：ヤマダ　タロウ）</span>
            </td>
          </tr>
          <tr class="flex-sp">
            <td class="input_item_need">※E-MAIL</td>
            <td class="input_value">
              <input size="50" maxlength="100" style="ime-mode: disabled;" type="text" name="P_C_EMAIL" value="<?php echo $selectData->PcEMail; ?>">&nbsp;<span style="font-size:x-small">（入力例：ytaro@dynax.co.jp）</span>
            </td>
          </tr>
          <tr class="flex-sp">
            <td class="input_item_need">※E-MAIL再入力</td>
            <td class="input_value">
              <input size="50" maxlength="100" style="ime-mode: disabled;" type="text" name="P_C_EMAIL2" value="" autocomplete="nope">&nbsp;<span style="font-size:x-small">（入力例：ytaro@dynax.co.jp）</span>
            </td>
          </tr>
        </tbody>
      </table>
      <br>
      <table width="100%" align="center" border="0" cellspacing="2" cellpadding="5">
        <tbody>
          <tr>
            <td align="center">
              <input type="button" class="base_button" value="送　信" onclick="OnConfirm('<?php echo $page_link; ?>');">
            </td>
          </tr>
        </tbody>
      </table>
      <input type="hidden" name="mode" value="insert">
      <input type="hidden" name="page_no" value="29">
      <input type="hidden" name="patten_cd" value="">
      <input type="hidden" name="set_lg_g_id" value="dmshibuyag064368">
      <input type="hidden" name="chg" value="0">
      <input type="hidden" name="top_g_id" value="dmshibuya">
      <input type="hidden" name="p_id" value="">
      <input type="hidden" name="forward_mail" value="info@dynax.co.jp">
      <input type="hidden" name="postid" value="<?php echo $postid; ?>">
      <input type="hidden" name="urlcheck" value="<?php echo $members->getPageSlug('nakama-member-check-magazine'); ?>">
      <iframe name="getData" src="" style="display:none"></iframe>
    </form>
  <?php endif; ?>
  <?php else:
    
  ?>
  <?php if (empty($mode)) : ?>
  <form name="mainForm2" id="mainForm2" enctype="multipart/form-data">
    <table border="0" cellspacing="0" cellpadding="5">
      <tbody>
        <tr>
          <td align="left"><span style="color:red;">※マークの項目は必須入力です。</span></td>
        </tr>
        <tr>
          <td colspan="3">既にメルマガ会員に登録されています。</td>
        </tr>
      </tbody>
    </table>
    <table class="input_table" border="0" align="center" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="flex-sp">
          <td class="input_title" width="180" nowrap="">入力項目</td>
          <td class="input_title" nowrap="">記入欄</td>
        </tr>
        <tr class="flex-sp">
          <td class="input_item"><span style="color: #000000">会社又は組織名</span></td>
          <td class="input_value">
            <?php echo $selectData->GName; ?>
            <input type="hidden" name="G_NAME" value="<?php echo $selectData->GName; ?>">
          </td>
        </tr>
        <tr class="flex-sp">
          <td class="input_item_need">※氏名</td>
          <td class="input_value">
            <?php echo $selectData->PcName; ?>
            <input type="hidden" name="P_C_NAME" value="<?php echo $selectData->PcName; ?>">
          </td>
        </tr>
        <tr class="flex-sp">
          <td class="input_item_need">※氏名フリガナ</td>
          <td class="input_value">
            <?php echo $selectData->PcKana; ?>
            <input type="hidden" name="P_C_KANA" value="<?php echo $selectData->PcKana; ?>">
          </td>
        </tr>
        <tr class="flex-sp">
          <td class="input_item_need">※E-MAIL</td>
          <td class="input_value">
            <?php echo $selectData->PcEMail; ?>
            <input type="hidden" name="P_C_EMAIL" value="<?php echo $selectData->PcEMail; ?>">
          </td>
        </tr>
      </tbody>
    </table>
    <br>
  </form>
  <?php endif; ?>
  <?php endif; ?>
<?php endif; ?>
<!-- Insert -->
<?php if($mode == "insert" || $mode == "update"): ?>
  <div align="center" class="">
  <h1 class="page_title">メルマガ会員登録完了</h1>
  </div>
  <br class=""><br class="">
  <table align="center" border="0" cellspacing="2" cellpadding="5" class="">
    <tbody class="">
      <tr class="">
        <td align="center" class="">
          <b class=""><div style="font-size:120%; color: #0000FF" class=""><br class="">メルマガ会員登録の申込みを送信しました。</div></b>
          <br class=""><br class="">下記のメールアドレスにメルマガ会員登録完了メールを送信しました<br class="">
          E-MAIL：<?php echo $emailRegist; ?>
          <br class=""><br class=""> 申込ありがとうございました。
        </td>
      </tr>
      <tr class="">
        <td align="center" class="">
          <br class=""><br class=""><br class="">
          <input type="button" class="base_button" value="トップへ戻る" onclick="window.location.href='<?php echo get_home_url(); ?>'">
        </td>
      </tr>
    </tbody>
  </table>
  <!-- End Insert -->
<?php endif; ?>
</div>
</body>
</html>
