<?php
if (empty($_SESSION['arrSession'])) :
    $_SESSION['LOGIN_STATUS'] = NAK_research_LOGIN_ANONYMOUS;
else :
    $_SESSION['LOGIN_STATUS'] = NAK_research_LOGIN_MEMBER;
endif;
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/config/constant.php');
require_once(__ROOT__.'/controller/researchController.php');
unset($_SESSION['RESERCH_ANS']);
$researchs = new researchController();
$post_id = isset($_REQUEST['post_id'])?$_REQUEST['post_id']:"";
$tg_id = get_post_meta($post_id, "research_meta_group_id", true);
$research_id = isset($_REQUEST['research_id'])?$_REQUEST['research_id']:"";
$ans_type = isset($_REQUEST['ans_type'])?$_REQUEST['ans_type']:"";
$memreg_open = isset($_REQUEST['memreg_open'])?$_REQUEST['memreg_open']:"";
$p_id = isset($_SESSION['arrSession']->P_ID) ? $_SESSION['arrSession']->P_ID : "";
$result = $researchs->ResearchAgree($post_id, $tg_id, $research_id);
$research = isset($result->ResearchInfo) ? $result->ResearchInfo : "";

$CheckAnswer = $researchs->CheckAnswer($post_id, $tg_id, $research_id, $p_id);

$disp_loginuser = isset($_SESSION['arrSession']->USER_NAME) ? $_SESSION['arrSession']->USER_NAME : "";
$disp_gname = isset($_SESSION['arrSession']->GNAME) ? $_SESSION['arrSession']->GNAME : "";

$chgDic = $researchs->SelectTitleN($post_id, $tg_id);

$g_tno = isset($CheckAnswer->g_tno) ? $CheckAnswer->g_tno : '';
$g_msg = isset($CheckAnswer->g_msg) ? $CheckAnswer->g_msg : '';

if(isset($_REQUEST['member']) && $_REQUEST['member'] == "1" && !isset($_REQUEST['h_back'])) {
    $_SESSION["ResMemberFlg"] = 'Member';
}else{
    $_SESSION["ResMemberFlg"] = '';
}

$g_reserch_name = isset($research->RESERCH_NAME) ? $research->RESERCH_NAME : "";
$g_contents2    = isset($research->CONTENTS2) ? $research->CONTENTS2 : "";
$g_reqflg       = isset($research->REQUEST_FLG) ? $research->REQUEST_FLG : "0";
$g_reqnum       = isset($research->REQUEST_NUM) ? $research->REQUEST_NUM : "";
$g_Reanser      = isset($research->REANSER_FLG) ? $research->REANSER_FLG : "0";
$g_AgreeFlg     = isset($research->AGREE_FLG) ? $research->AGREE_FLG : "1";
$g_contents     = isset($research->CONTENTS) ? $research->CONTENTS : "";
$g_platformUrl  = isset($research->PLATFORM_URL) ? $research->PLATFORM_URL : "";
$g_platformName = isset($research->PLATFORM_NAME) ? $research->PLATFORM_NAME : "";
$g_word_res_agreement_title = str_replace("\r\n", "<br>", researchNvl($research->DISP_WORD04, DEFAULT_WORD_RES_AGREEMENT_TITLE));
$g_word_res_research_name   = str_replace("\r\n", "<br>", researchNvl($research->DISP_WORD05, DEFAULT_WORD_RES_RESEARCH_NAME));
$g_word_res_answer_again_ok = str_replace("\r\n", "<br>", researchNvl($research->DISP_WORD06, DEFAULT_WORD_RES_ANSWER_AGAIN_OK));
$g_word_res_already_answer  = str_replace("\r\n", "<br>", researchNvl($research->DISP_WORD08, DEFAULT_WORD_RES_ALREADY_ANSWER));
$g_word_res_preinitation    = str_replace("\r\n", "<br>", researchNvl($research->DISP_WORD09, DEFAULT_WORD_RES_PREINITATION));
$g_word_res_suspend         = str_replace("\r\n", "<br>", researchNvl($research->DISP_WORD10, DEFAULT_WORD_RES_SUSPEND));
$g_word_res_upper_limit     = str_replace("\r\n", "<br>", researchNvl($research->DISP_WORD11, DEFAULT_WORD_RES_UPPER_LIMIT));
$g_word_res_deadline        = str_replace("\r\n", "<br>", researchNvl($research->DISP_WORD12, DEFAULT_WORD_RES_DEADLINE));
$g_MRegOpen     = researchNvl($research->MEMBERREG_OPEN, "0");

$g_max_qno = isset($CheckAnswer->g_max_qno) ? $CheckAnswer->g_max_qno : 1;

if($g_Reanser == "1") :
    $g_Reanser_msg = $g_word_res_answer_again_ok;
else:
    $g_Reanser_msg = $g_word_res_answer_again_ng;
endif;

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
    <title><?php echo $g_reserch_name; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/smart.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/answer_common_confirm.css">
    <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/common.js"></script>
    <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/MemberList.js"></script>
    <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/answer_common_confirm.js"></script>
    <?php if (!empty($_SESSION['arrSession'])) { ?>
        <?php
            $resea = $researchs->getDetail($post_id, $tg_id, $research_id);
            $memberreg_open = !empty($resea->RES_MEMBERREG_OPEN) ? $resea->RES_MEMBERREG_OPEN : 0;
            if ($memberreg_open == 1) {
                $checkTarget = $researchs->CheckTarget($post_id, $tg_id, $research_id, $p_id, $memberreg_open);
                if (!empty($checkTarget->CheckTarget)) {
                    unset($_SESSION['arrSession']);
                    $_SESSION['checkTargetErrorSession'] = '申し訳ございませんが、この回答対象者ではないため参加することができません。';
        ?>
                <script>
                    window.location = "<?php echo researchCrSet::getPageSlug('nakama-login')."top_g_id=".$tg_id."&research_id=".$research_id."&post_id=".$post_id."&ans_type=".$ans_type; ?>"
                </script>
        <?php } ?>
    <?php }} ?>
    <script>
        var opener = window.opener;
        if(opener) {
            opener.location.reload();
        }
    </script>
    <?php
    if($g_AgreeFlg == 0){
        ?>
        <script>
            window.location = "<?php echo researchCrSet::getPageSlug('nakama-research-ans-research')."research_id=".$research_id."&q_no=1&m_tno=".$g_tno."&max_q_no=".$max_q_no."&tg_id=".$tg_id."&post_id=".$post_id."&ans_type=".$ans_type; ?>"
        </script>
        <?php
    }
    ?>
    <script>
    function OnCommand(cmd){
        var form = document.mainForm;
        switch (cmd) {
            case "next":
            <?php if ($g_AgreeFlg == "1") : ?>
                if(form.tos_consent[0].checked != true){
                alert("ご入力内容の取扱いに関する事項をよく確認し、必ず「同意する」を選択してください。");
                return;
                }
            <?php endif; ?>
                form.action = "<?php echo get_permalink(get_page_by_path('nakama-research-ans-research')->ID); ?>";
                break;
            default:
                form.action = "<?php echo get_permalink(get_page_by_path('nakama-research-agreement')->ID); ?>";
                break;
        }
        form.submit();
    }
    </script>
</head>
<body>
    <div class="container">
    <form id="mainForm" name="mainForm" method="post" action="">
        <br>
        <table class="page_setup_width" width="800" align="center" border="0">
        <?php  if($disp_loginuser != "") :  ?>
        <tr>
            <td nowrap align="right">
            <div style="font-size:10pt;font-weight:bold;"><?php echo $disp_gname;  ?>&nbsp;<?php echo $disp_loginuser;  ?>&nbsp;様&nbsp;&nbsp;</div>
            </td>
        </tr>
        <?php  endif;  ?>
        <tr>
            <td valign="middle" height="40" class="RegValue" style="border: 1px solid #6C6C6C;">
            <center><font size="3"><b>同意確認</b></font></center>
            </td>
        </tr>
        </table>
        <br>
        <b>
        <table class="page_setup_width" width="600" align="center" cellspacing="1" cellpadding="10" border="1" bordercolor="#6699FF">
        <tr>
            <td align="center">
            <div style="line-height:150%;">
                <?php
                $g_reserch_name_text = str_replace("[@アンケート名]", "<font size='4'><b>「".$g_reserch_name."」</b></font>", $g_word_res_research_name);
                $g_max_qno_text = str_replace("[@質問数]", "<font size='4'><b>「".$g_max_qno."問」</b></font>", $g_reserch_name_text);
                echo $g_max_qno_text;
                ?>
            </div>
            </td>
        </tr>
        </table>
        <?php if($g_AgreeFlg == "1") :  ?>
        <table class="page_setup_width" align="center" border="0" width="600">
        <tr>
            <td align="left">
            <div style="font-size:10pt; padding-left:10px;line-height:150%;">
                <?php
                echo $g_contents2;
                ?>
            </div><br>
            <div align="center">
                <?php if($g_platformUrl != "" && $g_platformName != "") : ?>
                <br>
                <center><a href="<?php echo $g_platformUrl;  ?>" target="_blank"><?php echo $g_platformName;  ?></a></center>
                <?php endif; ?>
                <?php if($_SESSION["ResMemberFlg"] != "") :  ?>
                <br>
                <font color="red"><?php echo $g_Reanser_msg;  ?></font>
                <?php endif;  ?>
                <br><br>
                <div style="font-size: 10pt; color: #000000; padding-left: 10px;">
                ご確認・ご同意いただいた場合は、「同意する」を選択してくだい。<br>
                </div>
                <input type="radio" name="tos_consent" value="1" />同意する&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="tos_consent" value="0" checked />同意しない
            </div>
            <table class="page_setup_width" align="center">
                <tr>
                <td align="left"></td>
                </tr>
            </table>
            <br>
            <table class="page_setup_width" align="center">
                <tr>
                <td align="center">
                    <?php if($g_msg != "") :  ?>
                    <?php echo $g_msg; ?>
                    <?php else : ?>
                    <input type="button" class="base_button" value="進む" onclick="javascript: OnCommand('next');">
                    <?php endif;  ?>
                </td>
                </tr>
            </table>
        <?php if($g_contents != "") : ?>
            ■<?php echo researchNvl(str_replace("<br>", "",$researchs->getNewTitle($chgDic,'res説明文')), '目的説明'); ?> ：
            <div align="center">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" align="center">
                <tr>
                <td style="border:1px solid #6C6C6C;line-height:150%;padding:5px;">
                    <?php
                    echo $g_contents;
                    ?>
                </td>
                </tr>
            </table>
            </div>
        <?php endif;  ?>
            </td>
        </tr>
        <tr>
        <td><hr size="1"></td>
        </tr>
        </table>
        <?php else : ?>
        <table class="page_setup_width" align="center" border="0" width="600">
        <tr>
            <td align="left">
            <div style="font-size:10pt; padding-left:10px;line-height:150%;">
                <?php
                echo $g_contents2;
                ?>
            </div>
            <br>
            </td>
        </tr>
        <?php if($g_platformUrl != "" && $g_platformName != "") : ?>
        <tr>
            <td align="center">
                <br>
                <center>
                <a href="<?php echo $g_platformUrl;  ?>" target="_blank"><?php echo $g_platformName;  ?></a>
                </center>
            </td>
        <tr>
        <?php endif; ?>
        <tr>
            <td align="center">
                <br>
                <font color="red"><?php echo $g_Reanser_msg; ?></font>
                <br>
            </td>
        <tr>
            <td><hr size="1"></td>
        </tr>
        </tr>
        </table>
        <table class="page_setup_width" align="center">
        <tr>
            <td align="left"></td>
        </tr>
        </table>
        <table class="page_setup_width" align="center">
        <tr>
            <td align="center">
            <?php if($g_msg != "") :  ?>
                <?php echo $g_msg; ?>
            <?php else : ?>
            <input type="button" class="base_button" value="進む" onclick="javascript: OnCommand('next');">
            <?php endif;  ?>
            </td>
        </tr>
        </table>
        <?php if ($g_contents != "") : ?>
        <table class="page_setup_width" align="center" border="0" width="600">
        <tr>
            <td align="left">
            ■<?php echo researchNvl(str_replace("<br>", "",$researchs->getNewTitle($chgDic,'res説明文')), '目的説明'); ?> ：
            <div align="center">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" align="center">
                <tr>
                <td style="border:1px solid #6C6C6C;line-height:150%;padding:5px;" align="left">
                    <?php
                    echo $g_contents;
                    ?>
                </td>
                </tr>
            </table>
            </div>
            </td>
        </tr>
        </table>
        <?php endif; ?>
        <?php endif; ?>
        <br>
        <div align="center">
        <a href="javascript: window.close();"><< 閉じる >></a>
        </div>
        <input type="hidden" name="research_id" value="<?php echo $research_id; ?>">
        <input type="hidden" name="ans_type" value="<?php echo $ans_type; ?>">
        <input type="hidden" name="memreg_open" value="<?php echo $memreg_open; ?>">
        <input type="hidden" name="q_no"       value="1">
        <input type="hidden" name="m_tno"      value="<?php echo $g_tno; ?>">
        <input type="hidden" name="max_q_no"   value="<?php echo $g_max_qno; ?>">
        <input type="hidden" name="site_id"    value="">
        <input type="hidden" name="page_no"    value="">
        <input type="hidden" name="tg_id"     value="<?php echo $tg_id; ?>">
        <input type="hidden" name="post_id"     value="<?php echo $post_id; ?>">
    </form>
    </div>
</body>
</html>