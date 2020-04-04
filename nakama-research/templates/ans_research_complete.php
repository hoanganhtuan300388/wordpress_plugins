<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/config/constant.php');
require_once(__ROOT__.'/controller/researchController.php');
$researchs = new researchController();
$post_id = isset($_REQUEST['post_id'])?$_REQUEST['post_id']:"";
$tg_id = get_post_meta($post_id, "research_meta_group_id", true);
$research_id = isset($_REQUEST['research_id'])?$_REQUEST['research_id']:"";
$g_tno = isset($_REQUEST["m_tno"]) ? $_REQUEST["m_tno"] : 0;
$result = $researchs->getListResearchQuestion($post_id, $research_id);
$ResearchInfo = isset($result->ResearchInfo) ? $result->ResearchInfo : array();
$ListResearchQuestion = isset($result->ListResearchQuestion) ? $result->ListResearchQuestion : array();
$MaxQuestion = isset($result->MaxQuestion) ? $result->MaxQuestion : array();
if(!empty($ListResearchQuestion)){
    for ($i=0; $i < $MaxQuestion; $i++) { 
        $ListResearchQuestion[$i]->RESULT = $_SESSION['RESERCH_ANS'][$i];
    }
}
//init
$g_msg = '';
$g_reserch_name = isset($ResearchInfo->RESERCH_NAME) ? $ResearchInfo->RESERCH_NAME : '';
$g_word_res_comp_confirm = str_replace("\r\n", "<br>", researchNvl($ResearchInfo->DISP_WORD13, DEFAULT_WORD_RES_COMP_CONFIRM));
$targetFlag = isset($_REQUEST["targetFlag"]) ? $_REQUEST["targetFlag"] : 0;
$m_sign6_1 = isset($_REQUEST["m_sign6_1"]) ? $_REQUEST["m_sign6_1"] : "";
$m_sign6_2 = isset($_REQUEST["m_sign6_2"]) ? $_REQUEST["m_sign6_2"] : "";
$m_sign6_3 = isset($_REQUEST["m_sign6_3"]) ? $_REQUEST["m_sign6_3"] : "";
$m_sign7_1 = isset($_REQUEST["m_sign7_1"]) ? $_REQUEST["m_sign7_1"] : "";
$m_sign7_2 = isset($_REQUEST["m_sign7_2"]) ? $_REQUEST["m_sign7_2"] : "";
$m_sign7_3 = isset($_REQUEST["m_sign7_3"]) ? $_REQUEST["m_sign7_3"] : "";
$q_no = isset($_REQUEST["q_no"]) ? $_REQUEST["q_no"] : "";
$now_q_no = isset($_REQUEST["now_q_no"]) ? $_REQUEST["now_q_no"] : "";
$max_q_no = isset($_REQUEST["max_q_no"]) ? $_REQUEST["max_q_no"] : "";
$g_sign1 = isset($_REQUEST["m_sign1"]) ? $_REQUEST["m_sign1"] : "";
$g_sign3 = isset($_REQUEST["m_sign3"]) ? $_REQUEST["m_sign3"] : "";
$g_sign4 = isset($_REQUEST["m_sign4"]) ? $_REQUEST["m_sign4"] : "";
$g_sign6 = '';
if(!empty($m_sign6_1)){
    $g_sign6      = $m_sign6_1."-".$m_sign6_2."-".$m_sign6_3;
}
$g_sign7 = '';
if(!empty($m_sign7_1)){
    $g_sign7      = $m_sign7_1."-".$m_sign7_2."-".$m_sign7_3;
}
$g_sign8      = isset($_REQUEST["m_sign8"]) ? $_REQUEST["m_sign8"] : "";
$disp_loginuser = isset($_SESSION['arrSession']->USER_NAME) ? $_SESSION['arrSession']->USER_NAME : "";
$g_confirmFlag = isset($_REQUEST["confirmFlag"]) ? $_REQUEST["confirmFlag"] : 0;

if($g_confirmFlag == 1){
    $arrBody['p_id'] = isset($_SESSION['arrSession']->P_ID) ? $_SESSION['arrSession']->P_ID : "";
    $arrBody['tg_id'] = $tg_id;
    $arrBody['research_id'] = $research_id;
    $arrBody['t_no'] = ($g_tno == 0) ? '' : $g_tno;
    $MemberInfo = array(
        'top_g_name' => $g_sign1,
        'g_name' => $g_sign3,
        'c_name' => $g_sign4,
        'c_tel' => $g_sign6,
        'c_fax' => $g_sign7,
        'c_email' => $g_sign8,
    );
    $postAnswer = $researchs->postResearchAnswer($post_id, $arrBody, $ListResearchQuestion, $MemberInfo);
    $g_msg = isset($postAnswer->g_msg) ? $postAnswer->g_msg : "";
    $err = isset($postAnswer->Message) ? $postAnswer->Message : "";
    $email = isset($postAnswer->email) ? $postAnswer->email : "";
    $ans_return_mail_flg = isset($postAnswer->ans_return_mail_flg) ? $postAnswer->ans_return_mail_flg : "";
    if($email != "" && $ans_return_mail_flg == 1){
        $mailFrom = trim(get_post_meta($post_id, "mail_address", true));
        $pass_from = MAIL_PASSWORD;
        $sendMail = $researchs->SendAnsReturnMail($post_id, $tg_id, $research_id, $mailFrom, $pass_from, $email);
    }
}
//end init
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
        <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/common.js"></script>
        <script>
            function OnCommand(cmd){
              var form = document.mainForm;
              switch (cmd) {
              case "back":
                window.close();
                break;
              case "backpage":
            <?php  if($targetFlag == 1) : ?>
                form.action = "<?php echo get_permalink(get_page_by_path('nakama-research-ans-research-target')->ID); ?>";
            <?php  else : ?>
                form.action = "<?php echo get_permalink(get_page_by_path('nakama-research-ans-research')->ID); ?>";
            <?php  endif; ?>
                form.submit();
                break;

              case "next":
                form.action = "<?php echo get_permalink(get_page_by_path('nakama-research-ans-research-complete')->ID); ?>";
                form.confirmFlag.value = 1;
                form.submit();
                break;

              defalt:
                break;
              }
            }
        </script>
        <style type="text/css">
            .button1 {
            width: 100px;
            }
            .RegValue {
            font-size: 10pt;
            background-color: #dcdcdc;
            text-align: left;
            }
        </style>
    </head>
    <body>
        <div class="ans_complete">
        <form id="mainForm" name="mainForm" method="post" action="ans_research_complete.asp">
            <br>
            <table class="page_setup_width" width="600" align="center" border="0">
              <?php  if($disp_loginuser != "") : ?>
                <tr>
                    <td nowrap align="right">
                        <div style="font-size:10pt;font-weight:bold;"><?php echo $disp_loginuser; ?>&nbsp;様&nbsp;&nbsp;</div>
                    </td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td valign="middle" height="40" class="RegValue" style="border: 1px solid #6C6C6C;">
                        <center><font size="3"><b><?php echo $g_reserch_name; ?></b></font></center>
                    </td>
                </tr>
                <tr>
                    <td>
                        <hr size="1">
                    </td>
                </tr>
            </table>
            <br>
            <?php  if($g_confirmFlag == 1) : ?>
            <table class="page_setup_width" width="500" align="center" border="0">
                <?php  if(!empty($g_msg)) : ?>
                <tr>
                    <td valign="middle" height="30" style="border: 1px solid #6C6C6C;">
                        <center><b><br><?php echo $g_msg; ?></b><br><br></center>
                    </td>
                </tr>
                <?php  else : ?>
                <tr>
                    <td valign="middle" height="30">
                        <center><p class="red"><br><?php echo $err; ?></p><br><br></center>
                    </td>
                </tr>
                <?php  endif; ?>
            </table>
            <br><br><br>
            <table class="page_setup_width" align="center">
                <tr>
                    <td align="center">
                        <input type="button" class="base_button" value="閉じる" onclick="javascript: OnCommand('back');">
                    </td>
                </tr>
            </table>
            <?php  else : ?>
            <table class="page_setup_width" width="500" align="center" border="0">
                <tr>
                    <td valign="middle" height="30" style="border: 1px solid #6C6C6C;">
                        <center><b><font size="3"><br>
                            <?php echo $g_word_res_comp_confirm; ?>
                            <br><br>
                            </font></b>
                        </center>
                    </td>
                </tr>
            </table>
            <br>
            <table class="page_setup_width" align="center">
                <tr>
                    <td align="center">
                        <input type="button" class="base_button" value="戻る"   onclick="javascript: OnCommand('backpage');">&nbsp;&nbsp;
                        <input type="button" class="base_button" value="はい"   onclick="javascript: OnCommand('next');">
                    </td>
                </tr>
            </table>
            <?php  endif; ?>
            <input type="hidden" name="confirmFlag" value="">
            <input type="hidden" name="m_tno" value="<?php echo $g_tno; ?>">
            <input type="hidden" name="m_sign1" value="<?php echo $g_sign1; ?>">
            <input type="hidden" name="m_sign3" value="<?php echo $g_sign3; ?>">
            <input type="hidden" name="m_sign4" value="<?php echo $g_sign4; ?>">
            <input type="hidden" name="m_sign6_1" value="<?php echo $m_sign6_1; ?>">
            <input type="hidden" name="m_sign6_2" value="<?php echo $m_sign6_2; ?>">
            <input type="hidden" name="m_sign6_3" value="<?php echo $m_sign6_3; ?>">
            <input type="hidden" name="m_sign7_1" value="<?php echo $m_sign7_1; ?>">
            <input type="hidden" name="m_sign7_2" value="<?php echo $m_sign7_2; ?>">
            <input type="hidden" name="m_sign7_3" value="<?php echo $m_sign7_3; ?>">
            <input type="hidden" name="m_sign8" value="<?php echo $g_sign8; ?>">
            <input type="hidden" name="research_id" value="<?php echo $research_id; ?>">
            <input type="hidden" name="tg_id" value="<?php echo $tg_id; ?>">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <input type="hidden" name="q_no"       value="<?php echo $q_no; ?>">
            <input type="hidden" name="now_q_no"   value="<?php echo $now_q_no; ?>">
            <input type="hidden" name="max_q_no"   value="<?php echo $max_q_no; ?>">
            <input type="hidden" name="cmd"        value="back">
        </form>
        </div>
    </body>
</html>
