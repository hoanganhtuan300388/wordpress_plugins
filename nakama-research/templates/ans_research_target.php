<?php
$gLoginStatus = $_SESSION['LOGIN_STATUS'];
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__ . '/config/constant.php');
require_once(__ROOT__ . '/controller/researchController.php');
$researchs = new researchController();
$post_id = isset($_REQUEST['post_id'])?$_REQUEST['post_id']:""; 
$g_research_id = isset($_REQUEST['research_id'])?$_REQUEST['research_id']:"";
$m_tno = isset($_REQUEST['m_tno'])?$_REQUEST['m_tno']:""; 
$resultResearch = $researchs->getResearchAnswerTarget($post_id, $g_research_id,$m_tno);
$dataResearchInfo = $resultResearch->QuestionInfo;
$dataResearchTarget = $resultResearch->MemberInfo;
$_SESSION['LOGIN_TOPGID'] = get_post_meta($post_id, "research_meta_group_id", true);
$g_q_no = isset($_REQUEST['q_no']) ? $_REQUEST['q_no'] : 1;
$g_reserch_name = $dataResearchInfo->reserch_name;
$g_sign_flg = $dataResearchInfo->sign_flg;
$g_sign1 = $dataResearchInfo->sign1;
$g_sign3 = $dataResearchInfo->sign3;
$g_sign4 = $dataResearchInfo->sign4;
$g_sign6 = $dataResearchInfo->sign6;
$g_sign7 = $dataResearchInfo->sign7;
$g_sign8 = $dataResearchInfo->sign8;
if(isset($_REQUEST['cmd']) && $_REQUEST['cmd'] == "back"){
  $g_top_g_name = isset($_REQUEST['m_sign1'])?$_REQUEST['m_sign1']:"";
  $g_g_name = isset($_REQUEST['m_sign3'])?$_REQUEST['m_sign3']:"";
  $g_c_name = isset($_REQUEST['m_sign4'])?$_REQUEST['m_sign4']:"";
  $g_c_tel = [$_REQUEST['m_sign6_1'],$_REQUEST['m_sign6_2'],$_REQUEST['m_sign6_3']];
  $g_c_fax = [$_REQUEST['m_sign7_1'],$_REQUEST['m_sign7_2'],$_REQUEST['m_sign7_3']];
  $g_c_email = isset($_REQUEST['m_sign8'])?$_REQUEST['m_sign8']:"";
} else {
  $g_top_g_name = $dataResearchTarget->top_g_name;
  $g_g_name = $dataResearchTarget->g_name;
  $g_c_name = $dataResearchTarget->c_name;
  $g_c_tel = explode("-",$dataResearchTarget->c_tel);
  $g_c_fax = explode("-",$dataResearchTarget->c_fax);
  $g_c_email = $dataResearchTarget->c_email;
}

?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
  <title><?php echo $g_reserch_name; ?></title>
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/smart.css">
  <script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/common.js"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/inputCheck.js"></script>
  <script type="text/javascript">
    history.forward();

    function inputCheck() {
      var form = document.mainForm;

      <?php if ($gLoginStatus == NAK_research_LOGIN_MEMBER && $_SESSION['ResMemberFlg'] != "") : ?>
        return true;
      <?php endif; ?>
      <?php
      if ($g_sign1 != "0") :
        if ($g_sign1 == "2") :
          ?>
          if (IsNull(form.m_sign1.value, "会社名")) {
            form.m_sign1.focus();
            return false;
          }
        <?php endif; ?>
        if (IsLengthB(form.m_sign1.value, 0, 1000, "会社名") < 0) {
          return false;
        }
      <?php endif; ?>
      <?php
      if ($g_sign3 != "0") :
        if ($g_sign3 == "2") :
          ?>
          if (IsNull(form.m_sign3.value, "部署")) {
            form.m_sign3.focus();
            return false;
          }
        <?php endif; ?>
        if (IsLengthB(form.m_sign3.value, 0, 100, "部署") < 0) {
          return false;
        }
      <?php endif; ?>
      <?php
      if ($g_sign4 != "0") :
        if ($g_sign4 == "2") :
          ?>
          if (IsNull(form.m_sign4.value, "氏名")) {
            form.m_sign4.focus();
            return false;
          }
        <?php endif; ?>
        if (IsLengthB(form.m_sign4.value, 0, 200, "氏名") < 0) {
          return false;
        }
      <?php endif; ?>
      <?php
      if ($g_sign6 != "0") :
        if ($g_sign6 == "2") :
          ?>
          if (form.m_sign6_1.value == "" && form.m_sign6_2.value == "" && form.m_sign6_3.value == "") {
            alert("電話番号は全ての欄を入力して下さい");
            form.m_sign6_1.focus();
            return false;
          }
        <?php endif; ?>
        if (form.m_sign6_1.value != "" || form.m_sign6_2.value != "" || form.m_sign6_3.value != "") {
          if (form.m_sign6_1.value == "") {
            alert("電話番号は全ての欄を入力して下さい");
            form.m_sign6_1.focus();
            return false;
          }
          if (form.m_sign6_2.value == "") {
            alert("電話番号は全ての欄を入力して下さい");
            form.m_sign6_2.focus();
            return false;
          }
          if (form.m_sign6_3.value == "") {
            alert("電話番号は全ての欄を入力して下さい");
            form.m_sign6_3.focus();
            return false;
          }
          if (IsNarrowNum(form.m_sign6_1.value, "電話番号")) {
            form.m_sign6_1.focus();
            return false;
          }
          if (IsNarrowNum(form.m_sign6_2.value, "電話番号")) {
            form.m_sign6_2.focus();
            return false;
          }
          if (IsNarrowNum(form.m_sign6_3.value, "電話番号")) {
            form.m_sign6_3.focus();
            return false;
          }
        }
      <?php endif; ?>
      <?php
      if ($g_sign7 != "0") :
        if ($g_sign7 == "2") :
          ?>
          if (form.m_sign7_1.value == "" && form.m_sign7_2.value == "" && form.m_sign7_3.value == "") {
            alert("ＦＡＸ番号は全ての欄を入力して下さい");
            form.m_sign7_1.focus();
            return false;
          }
        <?php endif; ?>

        if (form.m_sign7_1.value != "" || form.m_sign7_2.value != "" || form.m_sign7_3.value != "") {
          if (form.m_sign7_1.value == "") {
            alert("ＦＡＸ番号は全ての欄を入力して下さい");
            form.m_sign7_1.focus();
            return false;
          }
          if (form.m_sign7_2.value == "") {
            alert("ＦＡＸ番号は全ての欄を入力して下さい");
            form.m_sign7_2.focus();
            return false;
          }
          if (form.m_sign7_3.value == "") {
            alert("ＦＡＸ番号は全ての欄を入力して下さい");
            form.m_sign7_3.focus();
            return false;
          }
          if (IsNarrowNum(form.m_sign7_1.value, "ＦＡＸ番号")) {
            form.m_sign7_1.focus();
            return false;
          }
          if (IsNarrowNum(form.m_sign7_2.value, "ＦＡＸ番号")) {
            form.m_sign7_2.focus();
            return false;
          }
          if (IsNarrowNum(form.m_sign7_3.value, "ＦＡＸ番号")) {
            form.m_sign7_3.focus();
            return false;
          }
        }
      <?php endif; ?>

      <?php
      if ($g_sign8 != "0") :
        if ($g_sign8 == "2") :
          ?>
          if (IsNull(form.m_sign8.value, "E-MAIL")) {
            form.m_sign8.focus();
            return false;
          }
        <?php endif; ?>
        if (IsLengthB(form.m_sign8.value, 0, 100, "E-MAIL") < 0) {
          form.m_sign8.focus();
          return false;
        }
        if (IsNarrowPlus(form.m_sign8.value, "E-MAIL") < 0) {
          form.m_sign8.focus();
          return false;
        }
        if (isMail(form.m_sign8.value, "E-MAIL") < 0) {
          form.m_sign8.focus();
          return false;
        }
      <?php endif; ?>
    }


    function OnCommand(cmd) {
      var form = document.mainForm;
      var i;
      switch (cmd) {
        case "back":
          form.cmd.value = cmd;
          form.action = "<?php echo get_permalink(get_page_by_path('nakama-research-ans-research')->ID); ?>";
          break;
        case "stop":
          if (!confirm("入力内容を破棄します。よろしいですか？")) {
            return;
          }
          window.close();
          break;
        case "next":
          if (inputCheck() == false) {
            return;
          }
          form.cmd.value = cmd;
          form.action = "<?php echo get_permalink(get_page_by_path('nakama-research-ans-research-complete')->ID); ?>";
          defalt:
            break;
      }
      form.submit();
    }
  </script>
  <style type="text/css">
    .RegValueTitle {
      font-size: 10pt;
      background-color: #dcdcdc;
      text-align: left;
    }
  </style>
</head>

<body>
  <div class="ans_target">
  <form id="mainForm" name="mainForm" method="post">
    <br>
    <!-- アンケート名 -->
    <table class="page_setup_width" width="600" align="center" border="0">

      <?php if (isset($_SESSION['arrSession'])) : ?>
        <tr>
          <td nowrap align="right">
            <div style="font-size:10pt;font-weight:bold;"><?php echo $_SESSION['arrSession']->USER_NAME; ?>&nbsp;様&nbsp;&nbsp;</div>
          </td>
        </tr>
      <?php endif; ?>

      <tr>
        <td valign="middle" height="40" class="RegValueTitle" style="border: 1px solid #6C6C6C;">
          <center>
            <font size="3"><b><?php echo $g_reserch_name; ?></b></font>
          </center>
        </td>
      </tr>
      <tr>
        <td>
          <hr size="1">
        </td>
      </tr>
    </table>
    <br>
    <table class="page_setup_width" width="500" align="center" border="0">
      <tr>
        <td>
          <?php if ($gLoginStatus == NAK_research_LOGIN_MEMBER && $_SESSION['ResMemberFlg'] != "") : ?>
          <?php else : ?>
            お手数ですが、下記項目にご記入願います。
            <?php
            if ($g_sign1 = "2" || $g_sign3 == "2" || $g_sign4 == "2" || $g_sign6 == "2" || $g_sign7 == "2") :
              ?>
              <br>
              <font color="red">「*」がついているものは必須項目です。</font>
            <?php
          endif;
          ?>
          <?php endif ?>
        </td>
      </tr>
    </table>
    <br>

    <table class="page_setup_width" width="500" align="center" border="0" cellspacing="1" cellpadding="3">
      <?php
      if ($g_sign1 != "0") :
        ?>
        <tr>
          <td class="RegItem" width="140"><?php echo ($g_sign1 == "2") ? "<font color='red'>*</font>" : ""; ?> 会社名</td>
          <td class="RegValue" width="350">
            <?php if ($gLoginStatus == NAK_research_LOGIN_MEMBER && $_SESSION['ResMemberFlg'] != "") : ?>
              <?php echo $g_top_g_name; ?>
              <input type="hidden" name="m_sign1" value="<?php echo $g_top_g_name; ?>">
            <?php else : ?>
              <input type="text" name="m_sign1" value="<?php echo empty($m_sign1) ? (empty($g_top_g_name) ? "" : $g_top_g_name) : $m_sign1; ?>" maxlength="500" class='w-100'>
            <?php endif; ?>
          </td>
        </tr>
      <?php else : ?>
        <input type="hidden" name="m_sign1" value="<?php echo $g_top_g_name; ?>">
      <?php endif; ?>
      <?php
      if ($g_sign3 != "0") :
        ?>
        <tr>
          <td class="RegItem" width="140"><?php echo ($g_sign3 == "2") ? "<font color='red'>*</font>" : ""; ?> 部署</td>
          <td class="RegValue" width="350">
            <?php if ($gLoginStatus == NAK_research_LOGIN_MEMBER && $_SESSION['ResMemberFlg'] != "") : ?>
              <?php echo empty($g_g_name) ? "" : $g_g_name; ?>
              <input type="hidden" name="m_sign3" value="<?php echo empty($g_g_name) ? "" : $g_g_name; ?>">
            <?php else : ?>
              <input type="text" name="m_sign3" value="<?php echo empty($m_sign3) ? (empty($g_g_name) ? "" : $g_g_name) : $m_sign3; ?>" maxlength="50" class='w-100'>
            <?php endif; ?>
          </td>
        </tr>
      <?php else : ?>
        <input type="hidden" name="m_sign3" value="<?php echo empty($g_g_name) ? "" : $g_g_name; ?>">
      <?php endif; ?>
      <?php
      if ($g_sign4 != "0") :
        ?>
        <tr>
          <td class="RegItem" width="140"><?php echo ($g_sign4 == "2") ? "<font color='red'>*</font>" : ""; ?> 氏名</td>
          <td class="RegValue" width="350">
            <?php if ($gLoginStatus == NAK_research_LOGIN_MEMBER && $_SESSION['ResMemberFlg'] != "") : ?>
              <?php echo empty($g_c_name) ? "" : $g_c_name; ?>
              <input type="hidden" name="m_sign4" value="<?php echo empty($g_c_name) ? "" : $g_c_name; ?>">
            <?php else : ?>
              <input type="text" name="m_sign4" value="<?php echo empty($m_sign4) ? (empty($g_c_name) ? "" : $g_c_name) : $m_sign4; ?>" maxlength="100" class='w-100'>
            <?php endif; ?>
          </td>
        </tr>
      <?php else : ?>
        <input type="hidden" name="m_sign4" value="<?php echo empty($g_c_name) ? "" : $g_c_name; ?>">
      <?php endif; ?>
      <?php
      if ($g_sign6 != "0") :
        ?>
        <tr>
          <td class="RegItem" width="140"><?php echo ($g_sign6 == "2") ? "<font color='red'>*</font>" : ""; ?> 電話番号</td>
          <td class="RegValue" width="350">
            <?php if ($gLoginStatus == NAK_research_LOGIN_MEMBER && $_SESSION['ResMemberFlg'] != "") : ?>
              <?php echo (empty($g_c_tel[0]) ? "" : $g_c_tel[0]) . "&nbsp;－&nbsp;" . (empty($g_c_tel[1]) ? "" : $g_c_tel[1]) . "&nbsp;－&nbsp;" . (empty($g_c_tel[2]) ? "" : $g_c_tel[2]); ?>
              <input type="hidden" name="m_sign6_1" value="<?php echo empty($g_c_tel[0]) ? "" : $g_c_tel[0]; ?>">
              <input type="hidden" name="m_sign6_2" value="<?php echo empty($g_c_tel[1]) ? "" : $g_c_tel[1]; ?>">
              <input type="hidden" name="m_sign6_3" value="<?php echo empty($g_c_tel[2]) ? "" : $g_c_tel[2]; ?>">
            <?php else : ?>
              <input type="text" name="m_sign6_1" value="<?php echo empty($m_sign6_1) ? (empty($g_c_tel[0]) ? "" : $g_c_tel[0]) : $m_sign6_1; ?>" maxlength="4" size="5"><?php echo ((empty($m_sign6_1) ? (empty($g_c_tel[0]) ? "" : $g_c_tel[0]) : $m_sign6_1) != "") ? "&nbsp;－&nbsp;" : ""; ?>
              <input type="text" name="m_sign6_2" value="<?php echo empty($m_sign6_2) ? (empty($g_c_tel[1]) ? "" : $g_c_tel[1]) : $m_sign6_2; ?>" maxlength="4" size="5"><?php echo ((empty($m_sign6_2) ? (empty($g_c_tel[1]) ? "" : $g_c_tel[1]) : $m_sign6_2) != "") ? "&nbsp;－&nbsp;" : ""; ?>
              <input type="text" name="m_sign6_3" value="<?php echo empty($m_sign6_3) ? (empty($g_c_tel[2]) ? "" : $g_c_tel[2]) : $m_sign6_3; ?>" maxlength="4" size="5">

            <?php endif; ?>
          </td>
        </tr>
      <?php else : ?>
        <input type="hidden" name="m_sign6_1" value="<?php echo empty($g_c_tel[0]) ? "" : $g_c_tel[0]; ?>">
        <input type="hidden" name="m_sign6_2" value="<?php echo empty($g_c_tel[1]) ? "" : $g_c_tel[1]; ?>">
        <input type="hidden" name="m_sign6_3" value="<?php echo empty($g_c_tel[2]) ? "" : $g_c_tel[2]; ?>">
      <?php endif; ?>
      <?php
      if ($g_sign7 != "0") :
        ?>
        <tr>
          <td class="RegItem" width="140"><?php echo ($g_sign7 == "2") ? "<font color='red'>*</font>" : ""; ?> ＦＡＸ番号</td>
          <td class="RegValue" width="350">
            <?php if ($gLoginStatus == NAK_research_LOGIN_MEMBER && $_SESSION['ResMemberFlg'] != "") : ?>
              <?php echo (empty($g_c_fax[0]) ? "" : $g_c_fax[0]) . "&nbsp;－&nbsp;" . (empty($g_c_fax[1]) ? "" : $g_c_fax[1]) . "&nbsp;－&nbsp;" . (empty($g_c_fax[2]) ? "" : $g_c_fax[2]); ?>
              <input type="hidden" name="m_sign7_1" value="<?php empty($g_c_fax[0]) ? "" : $g_c_fax[0]; ?>">
              <input type="hidden" name="m_sign7_2" value="<?php empty($g_c_fax[1]) ? "" : $g_c_fax[1]; ?>">
              <input type="hidden" name="m_sign7_3" value="<?php empty($g_c_fax[2]) ? "" : $g_c_fax[2]; ?>">
            <?php else : ?>
              <input type="text" name="m_sign7_1" value="<?php echo empty($m_sign7_1) ? (empty($g_c_fax[0]) ? "" : $g_c_fax[0]) : $m_sign7_1; ?>" maxlength="4" size="5"><?php echo ((empty($m_sign7_1) ? (empty($g_c_fax[0]) ? "" : $g_c_fax[0]) : $m_sign7_1) != "") ? "&nbsp;－&nbsp;" : ""; ?>
              <input type="text" name="m_sign7_2" value="<?php echo empty($m_sign7_2) ? (empty($g_c_fax[1]) ? "" : $g_c_fax[1]) : $m_sign7_2; ?>" maxlength="4" size="5"><?php echo ((empty($m_sign7_2) ? (empty($g_c_fax[1]) ? "" : $g_c_fax[1]) : $m_sign7_2) != "") ? "&nbsp;－&nbsp;" : ""; ?>
              <input type="text" name="m_sign7_3" value="<?php echo empty($m_sign7_3) ? (empty($g_c_fax[2]) ? "" : $g_c_fax[2]) : $m_sign7_3; ?>" maxlength="4" size="5">

            <?php endif; ?>
          </td>
        </tr>
      <?php else : ?>
        <input type="hidden" name="m_sign7_1" value="<?php empty($g_c_fax[0]) ? "" : $g_c_fax[0]; ?>">
        <input type="hidden" name="m_sign7_2" value="<?php empty($g_c_fax[1]) ? "" : $g_c_fax[1]; ?>">
        <input type="hidden" name="m_sign7_3" value="<?php empty($g_c_fax[2]) ? "" : $g_c_fax[2]; ?>">
      <?php endif; ?>
      <?php
      if ($g_sign8 != "0") :
        ?>
        <tr>
          <td class="RegItem" width="140"><?php echo ($g_sign8 == "2") ? "<font color='red'>*</font>" : ""; ?> E-MAIL</td>
          <td class="RegValue" width="350">
            <?php if ($gLoginStatus == NAK_research_LOGIN_MEMBER && $_SESSION['ResMemberFlg'] != "") : ?>
              <?php echo empty($g_c_email) ? "" : $g_c_email; ?>
              <input type="hidden" name="m_sign8" value="<?php echo empty($g_c_email) ? "" : $g_c_email; ?>">
            <?php else : ?>
              <input type="text" name="m_sign8" value="<?php echo empty($m_sign8) ? (empty($g_c_email) ? "" : $g_c_email) : $m_sign8; ?>" maxlength="100" class='w-100'>
            <?php endif; ?>
          </td>
        </tr>
      <?php else : ?>
        <input type="hidden" name="m_sign8" value="<?php echo empty($g_c_email) ? "" : $g_c_email; ?>">
      <?php endif; ?>
    </table>
    <br><br><br>

    <table class="page_setup_width" align="center">
      <tr>
        <td align="center">
          <input type="button" class="base_button" value="戻　る" onclick="javascript: OnCommand('back');">&nbsp;&nbsp;
          <input type="button" class="base_button" value="中　止" onclick="javascript: OnCommand('stop');">&nbsp;&nbsp;
          <input type="button" class="base_button" value="次　へ" onclick="javascript: OnCommand('next');">
        </td>
      </tr>
    </table>

    <input type="hidden" name="research_id" value="<?php echo $g_research_id; ?>">
    <input type="hidden" name="cmd" value="">
    <input type="hidden" name="max_q_no" value="<?php echo $max_q_no; ?>">
    <input type="hidden" name="m_tno" value="<?php echo $m_tno; ?>">
    <input type="hidden" name="set_id" value="<?php echo $g_set_id; ?>">
    <input type="hidden" name="targetFlag" value="1">
    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
    <input type="hidden" name="q_no" value="<?php echo $g_q_no; ?>">

  </form>
  </div>
</body>

</html>
