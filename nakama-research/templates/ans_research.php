<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__ . '/config/constant.php');
require_once(__ROOT__ . '/controller/researchController.php');
$agreement_path = get_permalink(get_page_by_path('nakama-research-agreement')->ID);
$ans_research_target_path = get_permalink(get_page_by_path('nakama-research-ans-research-target')->ID);
$researchs = new researchController();
$post_id = isset($_REQUEST['post_id'])?$_REQUEST['post_id']:""; 
$tg_id = get_post_meta($post_id, "research_meta_group_id", true);
$g_research_id = isset($_REQUEST['research_id'])?$_REQUEST['research_id']:""; 
$m_tno = isset($_REQUEST['m_tno'])?$_REQUEST['m_tno']:""; 
$ResearchQuestion = $researchs->getListResearchQuestion($post_id, $g_research_id);
$SelectTitleN = $researchs->research_SelectTitleN($post_id,get_post_meta($post_id, "research_meta_group_id", true));
$dataResearchInfo = $ResearchQuestion->ResearchInfo;
$_SESSION['LOGIN_TOPGID'] = get_post_meta($post_id, "research_meta_group_id", true);
$g_q_no = isset($_REQUEST['q_no']) ? $_REQUEST['q_no'] : 1;
$cmd = isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : '';

$max_q_no = $ResearchQuestion->MaxQuestion;
if ($cmd == "back") {
  $g_q_no = $_SESSION['BACK_NO'];
  if((int)$g_q_no > $max_q_no){
    $g_q_no = $max_q_no;
  }
  if((int)$g_q_no < 1)
    $g_q_no = 1;
  $_SESSION['BACK_NO'] = $g_q_no - 1;
} else {
  $_SESSION['BACK_NO'] = $g_q_no - 1;
}
if ($cmd == "next") {
  $tempvalue = '';
  if(is_array($_REQUEST['qvalue'])){
    $tempvalue = implode("|",$_REQUEST['qvalue']);
  }else {
    $tempvalue = $_REQUEST['qvalue'];
  }
  if($g_q_no > $max_q_no)
    $_SESSION['RESERCH_ANS'][$max_q_no -1] = $tempvalue;
  else{
    $_SESSION['RESERCH_ANS'][$g_q_no - 2] = $tempvalue;
  }
}

//
$g_err = true;
$dataQuestion = $ResearchQuestion->ListResearchQuestion[$g_q_no - 1];
$g_q_type = $dataQuestion->Q_TYPE;
$g_q_class = $dataQuestion->Q_CLS;
$g_q_name = $dataQuestion->Q_NAME;
$g_qplatformName = $dataQuestion->Q_PLATFORM_NAME;
$g_qplatformUrl = $dataQuestion->Q_PLATFORM_URL;
$g_q_text = $dataQuestion->Q_TEXT;
$g_q_must = $dataQuestion->Q_MUST;
$g_q_value = $dataQuestion->Q_VALUE;
$g_q_next = $dataQuestion->Q_NEXT;
$g_q_disp = $dataQuestion->Q_DISP;
$g_q_words = $dataQuestion->Q_WORDS;
$sign_flg = $dataResearchInfo->SIGN_FLG;
$g_q_select = '';
if ($cmd == "next" && ($g_q_no == "999" || $g_q_no > $max_q_no)) {
  if ($sign_flg != 0) {
    wp_redirect($researchs->getPageSlug("nakama-research-ans-research-target")."research_id=".$g_research_id."&q_no=".$g_q_no."&post_id=".$post_id."&m_tno=".$m_tno."&max_q_no=".$max_q_no);
  } else {
    wp_redirect($researchs->getPageSlug("nakama-research-ans-research-complete")."research_id=".$g_research_id."&q_no=".$g_q_no."&post_id=".$post_id."&m_tno=".$m_tno."&max_q_no=".$max_q_no);
    exit;
  }
}

?>
<!DOCTYPE HTML>
<html lang="ja">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
  <title><?php echo $dataResearchInfo->RESERCH_NAME; ?></title>
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/css/smart.css">
  <script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/common.js"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/inputCheck.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <style type="text/css">
    body {
      line-height: 150%;
    }

    select {
      font-size: 12pt;
      padding: 2px;
      width: 100%;
      height: 30px;
    }

    option {
      font-size: 12pt;
      padding: 7px;
    }
  </style>
  <script type="text/javascript">
    history.forward();

    function OnOtherRadio(val) {
      var form = document.mainForm;
      var i;
      if (val != "") {
        for (i = 0; i < form.other_qvalue.length; i++) {
          if (form.other_qvalue[i].id == val) {
            if (form.other_qvalue[i].type != "hidden") {
              form.other_qvalue[i].readOnly = "";
              form.other_qvalue[i].style.backgroundColor = "#FFFFFF";
            }
          } else {
            if (form.other_qvalue[i].type != "hidden") {
              form.other_qvalue[i].readOnly = "readonly";
              form.other_qvalue[i].style.backgroundColor = "#C0C0C0";
            }
          }
        }
      } else {
        for (i = 0; i < form.other_qvalue.length; i++) {
          if (form.other_qvalue[i].type != "hidden") {
            form.other_qvalue[i].readOnly = "readonly";
            form.other_qvalue[i].style.backgroundColor = "#C0C0C0";
          }
        }
      }
    }

    function OnOtherCheckBox(val) {
      var form = document.mainForm;
      if (val != "") {
        if (form[val].type != "hidden") {
          if (form[val].readOnly != true) {
            form[val].readOnly = "readonly";
            form[val].style.backgroundColor = "#C0C0C0";
          } else {
            form[val].readOnly = "";
            form[val].style.backgroundColor = "#FFFFFF";
          }
        }
      }
    }

    function OnCommand(cmd) {
      var form = document.mainForm;
      var i, j;
      var word;

      form.action = "";

      switch (cmd) {
        case "back":
          <?php if ($_SESSION['BACK_NO'] != 0) : ?>
            form.cmd.value = cmd;
          <?php else : ?>
            form.action = "<?php echo $agreement_path; ?>";
          <?php endif; ?>
          break;
        case "stop":
          if (!confirm("入力内容を破棄します。よろしいですか？")) {
            return;
          }
          window.close();
          break;
        case "next":
          form.cmd.value = cmd;
          <?php switch ($g_q_type) {
            case "S1":    ?>
            j = 0;
            for (i = 0; i < eval(form.qvalue.length); i++) {
              if (form.qvalue[i].checked == true) {
                form.q_no.value = form.qnext[i].value;
                j = 1;
              }
            }
            <?php if ($g_q_must != "0") : ?>
              if (j == 0) {
                alert("必ず回答するようお願いいたします。");
                return;
              }
            <?php endif;
          break; ?>
          <?php case "S2": ?>
            <?php if ($g_q_must != "0") : ?>
              if (form.qvalue.value == "") {
                alert("必ず回答するようお願いいたします。");
                form.qvalue.focus();
                return;
              }
            <?php endif; ?>
            for (i = 0; i < eval(form.qvalue.length); i++) {
              if (form.qvalue[i].selected == true) {
                form.q_no.value = form.qnext[i].value;
              }
            }
            <?php break; ?>
          <?php case "M": ?>
            j = 0;
            var cboxes = document.getElementsByName('qvalue[]');
            for (i = 0; i < eval(cboxes.length); i++) {
              if (cboxes[i].checked == true) {
                form.q_no.value = form.qnext[i].value;
                j = j + 1;
              }
            }
            <?php if ($g_q_must != "0") : ?>
              if (j == 0) {
                alert("必ず回答するようお願いいたします。");
                return;
              }
            <?php endif; ?>
            <?php if ($g_q_select != "" && $g_q_select != "0") : ?>
              if (j > <?php echo $g_q_select; ?>) {
                alert("選択できるのは<?php echo $g_q_select; ?>個までです。");
                return;
              }
            <?php endif; ?>
            <?php break; ?>
          <?php case "T1":    ?>
            form.q_no.value = form.qnext.value;
            <?php if ($g_q_must != "0") : ?>
              if (form.qvalue.value == "") {
                alert("必ず回答するようお願いいたします。");
                form.qvalue.select();
                form.qvalue.focus();
                return;
              }
            <?php endif; ?>
            if (form.qwords.value != "" && form.qwords.value != "0") {
              word = eval(form.qwords.value) * 2;
              if (IsLengthB(form.qvalue.value, 0, word, "回答項目") != 0) {
                form.qvalue.select();
                form.qvalue.focus();
                return;
              }
            }
            <?php break; ?>
          <?php case "T2": ?>
            form.q_no.value = form.qnext.value;
            <?php if ($g_q_must != "0") : ?>
              if (form.qvalue.value == "") {
                alert("必ず回答するようお願いいたします。");
                form.qvalue.select();
                form.qvalue.focus();
                return;
              }
            <?php endif; ?>
            if (form.qwords.value != "" && form.qwords.value != "0") {
              word = eval(form.qwords.value) * 2;
              if (IsLengthB(form.qvalue.value, 0, word, "回答項目") != 0) {
                form.qvalue.select();
                form.qvalue.focus();
                return;
              }
            }
            <?php break; ?>
          <?php case "N": ?>
            form.q_no.value = form.qnext.value;
            <?php if ($g_q_must != "0") : ?>
              if (form.qvalue.value == "") {
                alert("必ず回答するようお願いいたします。");
                form.qvalue.select();
                form.qvalue.focus();
                return;
              }
            <?php endif; ?>
            if (form.qvalue.value != "") {
              if (!$.isNumeric(form.qvalue.value, 0, false)) {
                alert("半角数字で入力してください。");
                form.qvalue.select();
                form.qvalue.focus();
                return;
              }
              if (form.qwords.value != "" && form.qwords.value != "0") {
                word = form.qwords.value;
                if (IsLengthB2(form.qvalue.value, 0, word, "回答項目") != 0) {
                  form.qvalue.select();
                  form.qvalue.focus();
                  return;
                }
              }
            }
            <?php break; ?>
          <?php case "R": ?>
            j = 0;
            jQuery("#arr_qvalue").html('');
            for (i = 0; i < eval(form.qvalue_R.length); i++) {
              jQuery("#arr_qvalue").append("<input type='hidden' name='qvalue[]' value='"+form.qvalue_R[i].value+"'>");
              form.q_no.value = form.qnext[i].value;
              if (form.qvalue_R[i].value != "") {
                j = j + 1;
              }
            }
            <?php if ($g_q_must != "0") : ?>
              if (j == 0) {
                alert("必ず回答するようお願いいたします。");
                form.qvalue_R[0].focus();
                return;
              }
            <?php endif; ?>
            if (j != 0) {
              var selectflg = false;
              for (i = 0; i < eval(form.qvalue_R.length); i++) {
                for (j = 0; j < eval(form.qvalue_R.length); j++) {
                  if (i != j) {
                    if (form.qvalue_R[i].selectedIndex == form.qvalue_R[j].selectedIndex &&
                      form.qvalue_R[i].selectedIndex > 0) {
                      alert("同じ項目を選択することはできません。（" + (i + 1) + "位と" + (j + 1) + "位）");
                      form.qvalue_R[i].focus();
                      return;
                    }
                  }
                }
                if (form.qvalue_R[i].selectedIndex > 0) {
                  if (!selectflg && i > 0) {
                    alert("上位から順に入力してください。");
                    form.qvalue_R[i].focus();
                    return;
                  } else {
                    selectflg = true;
                  }
                } else {
                  selectflg = false;
                }
              }
            }
            <?php break; ?>
          <?php default: ?>
            form.q_no.value = eval(form.q_no.value) + 1;
          <?php } ?>
          break;
        case "download":
          document.mainForm.action = "detailDownload.asp";
          break;

          defalt:
            break;
      }

      form.submit();

    }

    function IsLengthB2(tempStr, tempMin, tempMax, errorMsg) {
      var i;
      var len = 0;
      for (i = 0; i < tempStr.length; i++)
        (tempStr.charAt(i).match(/[ｱ-ﾝ]/) || escape(tempStr.charAt(i)).length < 4) ? len++ : len += 2;
      if ((len < tempMin) || (len > tempMax)) {
        alert(errorMsg + "は\n" + tempMin + "文字から" + tempMax + "文字\nの間で入力して下さい。");
        return -1;
      }
      return 0;
    }

    function DispDetail(top_g_id, g_research_id, filename, dl) {
      gToolWnd = open('<?php echo $researchs->getPageSlug("nakama-research-ans-research-disp-detail"); ?>top_g_id=' + top_g_id + '&research_id=' + g_research_id + '&filename=' + filename + '&dl=' + dl,
        'DispDetail',
        'width=850,menubar=no,status=no,scrollbars=yes,personalbar=no,resizable=yes');
    }

    function OnDownloadFile(fileName) {
      document.mainForm.filename.value = fileName;
      OnCommand("download");
    }
  </script>
  <style type="text/css">
    .RegValue {
      font-size: 10pt;
      background-color: #dcdcdc;
      text-align: left;
    }

    .button1 {
      width: 100px;
    }
  </style>
</head>

<body>
  <div class="ans_research">
  <form id="mainForm" name="mainForm" method="post" action="ans_research.asp">
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
        <td valign="middle" height="40" class="RegValue" style="border: 1px solid #6C6C6C;">
          <center>
            <font size="3"><b><?php echo $dataResearchInfo->RESERCH_NAME; ?></b></font>
          </center>
        </td>
      </tr>
      <?php if ($dataResearchInfo->DETAIL_URL != "") : ?>
        <tr>
          <td>
            <center><b><?php echo $researchs->research_convertNvl(str_replace("<br>", "",$researchs->research_renderTitleEnd($SelectTitleN,"アンケート参考ページＵＲＬ")), "アンケート参考ページＵＲＬ"); ?></b></center>
          </td>
        </tr>
        <tr>
          <td>
            <center><a href="<?php echo $dataResearchInfo->DETAIL_URL; ?>" target="_blank"><?php echo $dataResearchInfo->DETAIL_NAME; ?></a></center>
          </td>
        </tr>
      <?php endif; ?>
      <?php if ($dataResearchInfo->DETAIL_FILE != "") : ?>
        <tr>
          <td>
            <center><b><?php echo $researchs->research_convertNvl(str_replace("<br>", "",$researchs->research_renderTitleEnd($SelectTitleN,"参考添付ファイル")), "参考添付ファイル"); ?></b></center>
          </td>
        </tr>
        <tr>
          <td>
            <center><?php echo $researchs->getResearchFile($tg_id, $g_research_id,$dataResearchInfo->DETAIL_FILE); ?><center>
          </td>
        </tr>
      <?php endif; ?>
      <tr>
        <td>
          <hr size="1">
        </td>
      </tr>
    </table>
    <?php if($max_q_no > 0) : ?>
    <table class="page_setup_width" width="600" align="center" border="0">
      <tr>
        <td align="right">
          （<?php echo $g_q_no; ?>&nbsp;／&nbsp;<?php echo $max_q_no; ?>）
        </td>
      </tr>
    </table>
    <!-- 質問文 -->
    <table class="page_setup_width" width="600" align="center" border="0">
      <?php if ($g_q_class != "") : ?>
        <tr>
          <td colspan="3">
            <font size="3"><b><?php echo $g_q_class; ?></b></font>
          </td>
        </tr>
      <?php endif; ?>
      <tr>
        <td valign="middle" height="30" style="border: 1px solid #6C6C6C;">
          <table border="0" width="100%">
            <tr>
              <td valign="top" border="0" style="width: 65px;">
                &nbsp;<b>&nbsp;（<?php echo $g_q_no; ?>）</b>
              </td>
              <td valign="middle" border="0" style="line-height: 150%;">
                <b><?php echo str_replace("\r\n", "<br>", $g_q_name) ?></b>
              </td>
            </tr>
            <?php if ($g_qplatformName != "" && $g_qplatformUrl != "") : ?>
              <tr>
                <td>&nbsp;</td>
                <td class="align-left" nowrap style="line-height: 150%;">
                  <a href="<?php echo $g_qplatformUrl; ?>" target="_blank"><?php echo $g_qplatformName ?></a>
                  <br>
                </td>
              </tr>
            <?php endif; ?>
          </table>
        </td>
      </tr>
    </table>
    <table class="page_setup_width" width="600" align="center" border="0">
      <?php if ($g_q_text != "" || $g_q_must != "0") : ?>
        <tr>
          <td valign="middle" height="60" style="line-height: 150%;">
            <?php echo ($g_q_must != "0") ? "<font color='red'>（必ず回答してください。）</font>" : ""; ?>
            <?php echo ($g_q_words != "" && $g_q_words != "0") ? "<br><font color='red'>（" . $g_q_words . "文字まで入力可能です。）</font>" : ""; ?>
            <?php echo ($g_q_select != "" && $g_q_select != "0") ? "<br><font color='red'>（" . $g_q_select . "個まで選択可能です。）</font>" : ""; ?>
            <?php echo ($g_q_type == "N") ? "<br><font color='red'>（半角数字で入力してください。）</font>" : ""; ?>
            <?php echo "<br>" . str_replace("\r\n", "<br>", $g_q_text) ?>
            <br>
          </td>
        </tr>
      <?php endif; ?>
      <?php echo $researchs->DispQuestion($g_q_type, $g_q_value, $g_q_next, $g_q_no, $g_q_disp, $g_q_words); ?>
    </table>
    <?php endif; ?>
    <br><br><br>

    <table class="page_setup_width" align="center">
      <tr>
        <td align="center">
          <?php if ($g_err) : ?>
            <input type="button" class="base_button" value="戻　る" onclick="javascript: OnCommand('back');">&nbsp;&nbsp;
          <?php endif; ?>
          <input type="button" class="base_button" value="中　止" onclick="javascript: OnCommand('stop');">&nbsp;&nbsp;
          <?php if ($g_err) : ?>
            <input type="button" class="base_button" value="次　へ" onclick="javascript: OnCommand('next');">
          <?php endif ?>
        </td>
      </tr>
    </table>

    <input type="hidden" name="filename" value="">
    <input type="hidden" name="top_g_id" value="<?php echo $_SESSION['LOGIN_TOPGID']; ?>">
    <input type="hidden" name="research_id" value="<?php echo $g_research_id; ?>">
    <input type="hidden" name="q_no" value="<?php echo $g_q_no; ?>">
    <input type="hidden" name="now_q_no" value="<?php echo $g_q_no ?>">
    <input type="hidden" name="cmd" value="">
    <input type="hidden" name="max_q_no" value="<?php echo $max_q_no; ?>">
    <input type="hidden" name="m_tno" value="<?php echo $m_tno; ?>">
    <input type="hidden" name="site_id" value="<?php echo $g_site_id; ?>">
    <input type="hidden" name="page_no" value="<?php echo $g_page_no; ?>">
    <input type="hidden" name="set_id" value="<?php echo $g_set_id; ?>">
    <input type="hidden" name="h_back" value="1">
    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">

  </form>
  </div>
</body>

</html>