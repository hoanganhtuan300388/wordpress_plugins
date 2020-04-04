<?php
$login_user = isset($_SESSION['arrSession']) ? $_SESSION['arrSession'] : array();

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__ . '/config/constant.php');
require_once(__ROOT__ . '/controller/researchController.php');

define('RESEARCH_ANSWER_DISPLAY_ALL', 1);
define('RESEARCH_ANSWER_DISPLAY_ONE', 0);
define('RESEARCH_QUESTION_INFO', 999);
define('RESEARCH_QUESTION_CONFIRM', 'confirm');
define('RESEARCH_QUESTION_COMPLETE', 'complete');
define('RESEARCH_QUESTION_MODE_BACK', 'back');
define('RESEARCH_QUESTION_MODE_NEXT', 'next');
define('RESEARCH_QUESTION_MODE_STOP', 'stop');
define('RESEARCH_QUESTION_MODE_INTERRUPT', 'interrupt');
define('RESEARCH_QUESTION_MODE_CLOSE', 'close');
define('MAX_WIDTH_TEXT_INPUT', 650);

$tg_id = isset($_REQUEST['tg_id']) ? $_REQUEST['tg_id'] : '';//get_post_meta( $post_id, 'research_meta_group_id', true );
$post_id = isset($_REQUEST['post_id']) ? $_REQUEST['post_id'] : '';
$research_id = isset($_REQUEST['research_id']) ? $_REQUEST['research_id'] : '';

//専用アンケートからの会員情報確認変更▼
const CSAJ_TGID = 'tu0001';
const NAKAMA2_TGID = 'nakama2';
const CSAJ_RESEARCH_ID = '2';
const NAKAMA2_RESEARCH_ID = '72';
const CSAJ_UPDATEADDRESS_PAGE_NO = '761';
const NAKAMA2_UPDATEADDRESS_PAGE_NO = '756';
$applyUpdateMember_url = "https://dev.nakamacloud.com/nakama2/ApplyMember/ApplyUpdateMember?tgId=" . base64_encode($tg_id) . "&gId=" . base64_encode($login_user->G_ID) . "&pId=" . base64_encode($login_user->P_ID);
/* CSAJ 
$UpdateAddress_url = "https://www.nakamacloud.com/csaj2/?page_id=761";
*/
/* TEST */
$UpdateAddress_url = "http://dev.nakamacloud.com/sample4/?page_id=756";
//専用アンケートからの会員情報確認変更▲

//check login
/*if ( empty( $login_user ) ) {
    $login_link = researchCrSet::getPageSlug('nakama-login') . "tg_id=" . $tg_id . "&research_id=" . $research_id . "&post_id=" . $post_id;
    wp_redirect( $login_link );
    exit;
}*/

$m_tno = isset($_REQUEST['m_tno']) ? $_REQUEST['m_tno'] : '';
$cmd = isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : '';
$memreg_open = isset($_REQUEST['memreg_open']) ? $_REQUEST['memreg_open'] : '';
$interrupt_q_no = '';
$g_sign1 = isset($_REQUEST['m_sign1']) ? $_REQUEST['m_sign1'] : '';
$g_sign3 = isset($_REQUEST['m_sign3']) ? $_REQUEST['m_sign3'] : '';
$g_sign4 = isset($_REQUEST['m_sign4']) ? $_REQUEST['m_sign4'] : '';
$g_sign6 = isset($_REQUEST['m_sign6']) ? $_REQUEST['m_sign6'] : '';
$g_sign7 = isset($_REQUEST['m_sign7']) ? $_REQUEST['m_sign7'] : '';
$g_sign8 = isset($_REQUEST['m_sign8']) ? $_REQUEST['m_sign8'] : '';
$g_pos = isset($_REQUEST['m_pos']) ? $_REQUEST['m_pos'] : '';
$is_display_all = isset($_REQUEST['ans_type']) ? $_REQUEST['ans_type'] : 0;//display all = 1, display 1 question = 0

$research = new researchController();
$researchQuestion = $research->getListResearchQuestion($post_id, $research_id);
$selectTitleN = $research->research_SelectTitleN($post_id, get_post_meta($post_id, 'research_meta_group_id', true));
$dataResearchInfo = $researchQuestion->ResearchInfo;
$max_q_no = $researchQuestion->MaxQuestion;
$sign_flg = $dataResearchInfo->SIGN_FLG;
$g_agree_flg = isset($dataResearchInfo->AGREE_FLG) ? $dataResearchInfo->AGREE_FLG : '1';
function getFirstKey($arr)
{
    return isset(array_keys($arr)[0]) ? array_keys($arr)[0] : '';
}

function getQuestionDetail($g_q_no, $listQuestion, $is_display_all)
{
    if ($is_display_all == RESEARCH_ANSWER_DISPLAY_ONE) {
        foreach ($listQuestion as $key => $question) {
            if ($question->Q_NO == $g_q_no) {
                return array($key => $question);
            }
        }
    } else {
        return $listQuestion;
    }

    return array();
}

function displayEditDataQuestion($listQuestion)
{
    if (!empty($_SESSION['arrSession']) && empty($_REQUEST['memreg_open'])) {
        $listQuestion = !empty($listQuestion) ? $listQuestion : array();
        foreach ($listQuestion as $question) {
            $result = '';
            $other_value = '';
            $_SESSION['RESERCH_ANS'][$question->Q_NO] = array(
                'q_no' => $question->Q_NO,
                'q_name' => $question->Q_NAME,
                'q_type' => $question->Q_TYPE,
                'q_must' => $question->Q_MUST,
                'q_select' => $question->Q_SELECT,
                'q_words' => $question->Q_WORDS
            );

            //set data for q_value
            if (!empty($question->RESULT)) {
                $value_explode = explode('|', $question->Q_VALUE);
                if ($question->Q_TYPE == 'M') {
                    $result = array();
                    $result_explode = explode('|', $question->RESULT);

                    foreach ($result_explode as $key => $val) {
                        if (!empty($val)) {
                            $index = ( int )$val - 1;
                            $result[] = $value_explode[$index];
                        }
                    }
                } else {
                    $result = $question->RESULT;
                    if ($question->Q_TYPE == 'S1' || $question->Q_TYPE == 'S2') {
                        if (isset($value_explode[( int )$question->RESULT - 1])) {
                            $result = $value_explode[( int )$question->RESULT - 1];
                        }
                    }

                    if ($question->Q_TYPE == 'R') {
                        $result = explode('|', $question->RESULT);
                    }
                }

                $_SESSION['RESERCH_ANS'][$question->Q_NO]['q_value'] = $result;
            }

            //set data for q_other_value
            if (!empty($question->RESULT_OTHER)) {
                if ($question->Q_TYPE == 'S1' || $question->Q_TYPE == 'M' || $question->Q_TYPE == 'S2') {
                    $other_value = explode('|', $question->RESULT_OTHER);
                }

                $_SESSION['RESERCH_ANS'][$question->Q_NO]['q_other_value'] = $other_value;
            }
        }
    }
}

function displayQuestion($g_q_type, $g_q_value, $g_q_other_text, $g_q_no, $g_q_disp, $g_q_words, $g_q_unit, $g_q_other_unit, $g_q_width, $g_q_other_width, $g_q_next)
{
    $str_result = '';
    $q_value = $q_next = $i = $j = $sel = $sel2 = $maxlength = $size = $other_qvalue = '';
    $RESERCH_ANS = isset($_SESSION['RESERCH_ANS']) ? $_SESSION['RESERCH_ANS'] : array();
    switch ($g_q_type) {
        case "S1":
            $q_value = explode('|', $g_q_value);
            $q_other_text = explode('|', $g_q_other_text);
            $g_q_other_unit = explode('|', $g_q_other_unit);
            $g_q_other_width = explode('|', $g_q_other_width);
            $g_q_next = explode('|', $g_q_next);
            $q_other_value = isset($RESERCH_ANS[$g_q_no]['q_other_value']) ? $RESERCH_ANS[$g_q_no]['q_other_value'] : '';
            for ($i = 0; $i < count($q_value); $i++) {
                $sel = (isset($RESERCH_ANS[$g_q_no]) && $RESERCH_ANS[$g_q_no]['q_value'] == $q_value[$i]) ? " checked='checked'" : "";
                if ($i != 0) {
                    $str_result .= (($g_q_disp == "0") ? "<br>" : "&nbsp;&nbsp;");
                }
                $other_id = ($q_other_text[$i] > 0) ? "other_{$g_q_no}_{$i}" : "";
                $str_result .= "<label><input type='radio' name='answers[" . $g_q_no . "][q_value]' value='" . $q_value[$i] . "'" . $sel . " onClick='OnOtherRadio(\"" . $other_id . "\", " . $g_q_no . ", " . $g_q_next[$i] . ")'>" . str_replace("[@テキスト]", "", $q_value[$i]) . "</label>\r\n";

                if ($q_other_text[$i] > 0) {
                    $other_unit = !empty($g_q_other_unit[$i]) ? $g_q_other_unit[$i] : '';
                    $other_width = !empty($g_q_other_width[$i]) ? $g_q_other_width[$i] : '';
                    if (!empty ($sel)) {
                        $str_result .= (($g_q_disp == "0") ? "<br>" : "") . "<input type='text' style='width:" . (!empty($other_width) ? (($other_width >= MAX_WIDTH_TEXT_INPUT) ? '100%' : ($other_width . 'px')) : 'inherit') . "; background-color: #FFFFFF; box-sizing: border-box' id='" . $other_id . "' name='answers[" . $g_q_no . "][q_other_value][]' value='" . $q_other_value[$i] . "'> " . $other_unit . "\r\n";
                    } else {
                        $str_result .= (($g_q_disp == "0") ? "<br>" : "") . "<input type='text' style='width:" . (!empty($other_width) ? (($other_width >= MAX_WIDTH_TEXT_INPUT) ? '100%' : ($other_width . 'px')) : 'inherit') . "; background-color: #C0C0C0; box-sizing: border-box' id='" . $other_id . "' name='answers[" . $g_q_no . "][q_other_value][]' value='' readonly='readonly'> " . $other_unit . "\r\n";
                    }
                } else {
                    $str_result .= "<input type='hidden' name='answers[" . $g_q_no . "][q_other_value][]' value=''>\r\n";
                }
            }
            break;
        case "S2":
            $q_value = explode('|', $g_q_value);
            $q_other_text = explode('|', $g_q_other_text);
            $g_q_other_unit = explode('|', $g_q_other_unit);
            $g_q_other_width = explode('|', $g_q_other_width);
            $g_q_next = explode('|', $g_q_next);
            $q_other_value = isset($RESERCH_ANS[$g_q_no]['q_other_value']) ? $RESERCH_ANS[$g_q_no]['q_other_value'] : '';
            $str_result_other = '';
            $str_result .= "<select name='answers[" . $g_q_no . "][q_value]' onChange='OnOtherSelect(" . $g_q_no . ")'>\r\n";
            $str_result .= "<option value=''></option>\r\n";
            for ($i = 0; $i < count($q_value); $i++) {
                $sel = (isset($RESERCH_ANS[$g_q_no]) && $RESERCH_ANS[$g_q_no]['q_value'] == $q_value[$i]) ? "selected='true'" : "";
                $other_id = ($q_other_text[$i] > 0) ? "other_{$g_q_no}_{$i}" : "";
                $str_result .= "<option data-other-id='" . $other_id . "' data-q-next='" . $g_q_next[$i] . "' value='" . $q_value[$i] . "' " . $sel . ">" . $q_value[$i] . "</option>\r\n";
                if ($q_other_text[$i] > 0) {
                    $other_unit = !empty($g_q_other_unit[$i]) ? $g_q_other_unit[$i] : '';
                    $other_width = !empty($g_q_other_width[$i]) ? $g_q_other_width[$i] : '';
                    if (!empty ($sel)) {
                        $str_result_other .= "<br>" . $q_value[$i] . " <input type='text' style='width:" . (!empty($other_width) ? (($other_width >= MAX_WIDTH_TEXT_INPUT) ? '100%' : ($other_width . 'px')) : 'inherit') . ";background-color:#FFFFFF;margin-top:5px;box-sizing:border-box' id='" . $other_id . "' name='answers[" . $g_q_no . "][q_other_value][]' value='" . $q_other_value[$i] . "'> " . $other_unit . "\r\n";
                    } else {
                        $str_result_other .= "<br>" . $q_value[$i] . " <input type='text' style='width:" . (!empty($other_width) ? (($other_width >= MAX_WIDTH_TEXT_INPUT) ? '100%' : ($other_width . 'px')) : 'inherit') . ";background-color:#C0C0C0;margin-top:5px;box-sizing:border-box' id='" . $other_id . "' name='answers[" . $g_q_no . "][q_other_value][]' value='' readonly='readonly'> " . $other_unit . "\r\n";
                    }
                } else {
                    $str_result_other .= "<input type='hidden' name='answers[" . $g_q_no . "][q_other_value][]' value=''>\r\n";
                }
            }
            $str_result .= "</select>\r\n";
            $str_result .= $str_result_other;
            break;
        case "M":
            $q_value = explode('|', $g_q_value);
            $q_other_text = explode('|', $g_q_other_text);
            $g_q_other_unit = explode('|', $g_q_other_unit);
            $g_q_other_width = explode('|', $g_q_other_width);
            $q_other_value = isset($RESERCH_ANS[$g_q_no]['q_other_value']) ? $RESERCH_ANS[$g_q_no]['q_other_value'] : '';
            $sel = (isset($RESERCH_ANS[$g_q_no]['q_value']) && is_array($RESERCH_ANS[$g_q_no]['q_value'])) ? $RESERCH_ANS[$g_q_no]['q_value'] : array();
            for ($i = 0; $i < count($q_value); $i++) {
                $sel2 = '';
                $value = '';
                for ($j = 0; $j < count($sel); $j++) {
                    if ($sel[$j] == $q_value[$i]) {
                        $sel2 = " checked='checked'";
                        $value = $q_other_value[$i];
                    }
                }

                $other_id = ($q_other_text[$i] > 0) ? "other_{$g_q_no}_{$i}" : "";
                $str_result .= "<label><input type='checkbox' name='answers[" . $g_q_no . "][q_value][]' value='" . $q_value[$i] . "'" . $sel2 . " onClick='OnOtherCheckBox(\"" . $other_id . "\", this)'>";
                $str_result .= str_replace("[@テキスト]", "", $q_value[$i]) . "</label>\r\n";

                if ($q_other_text[$i] > 0) {
                    $other_unit = !empty($g_q_other_unit[$i]) ? $g_q_other_unit[$i] : '';
                    $other_width = !empty($g_q_other_width[$i]) ? $g_q_other_width[$i] : '';
                    if (!empty ($sel2)) {
                        $str_result .= (($g_q_disp == "0") ? "<br>" : "") . "<input type='text' size='20' style=' box-sizing: border-box;background-color: #FFFFFF;width:" . (!empty($other_width) ? (($other_width >= MAX_WIDTH_TEXT_INPUT) ? '100%' : ($other_width . 'px')) : 'inherit') . "' id='" . $other_id . "' name='answers[" . $g_q_no . "][q_other_value][]' value='" . $value . "'> " . $other_unit . "\r\n";
                    } else {
                        $str_result .= (($g_q_disp == "0") ? "<br>" : "") . "<input type='text' size='20' style=' box-sizing: border-box;background-color: #C0C0C0;width:" . (!empty($other_width) ? (($other_width >= MAX_WIDTH_TEXT_INPUT) ? '100%' : ($other_width . 'px')) : 'inherit') . "' id='" . $other_id . "' name='answers[" . $g_q_no . "][q_other_value][]' value='' readonly='readonly'> " . $other_unit . "\r\n";
                    }
                } else {
                    $str_result .= "<input type='hidden' name='answers[" . $g_q_no . "][q_other_value][]' value=''>\r\n";
                }

                $str_result .= (($g_q_disp == "0") ? "<br>" : "&nbsp;&nbsp;");
            }
            break;
        case "T1":
            $maxlength = (!empty($g_q_words)) ? "maxlength='" . ($g_q_words * 2) . "'" : "maxlength='2000'";
            //$size = ( !empty( $g_q_words ) ) ? "size='" . ( ( CLngEx( $g_q_words ) * 2 > 110 ) ? "110" : CLngEx( $g_q_words ) * 2 ) : "size='110'";
            $value = isset($RESERCH_ANS[$g_q_no]) ? $RESERCH_ANS[$g_q_no]['q_value'] : '';
            $str_result .= "<input type='text' name='answers[" . $g_q_no . "][q_value]' " . $maxlength . " value='" . $value . "' style='box-sizing: border-box;width: " . (!empty($g_q_width) ? (($g_q_width >= MAX_WIDTH_TEXT_INPUT) ? '100%' : ($g_q_width . 'px')) : 'inherit') . ";'> {$g_q_unit}\r\n";
            break;
        case "T2":
            $maxlength = (!empty($g_q_words)) ? "maxlength='" . $g_q_words . "'" : '';
            $value = isset($RESERCH_ANS[$g_q_no]) ? $RESERCH_ANS[$g_q_no]['q_value'] : '';
            $str_result .= "<textarea name='answers[" . $g_q_no . "][q_value]' cols='20' rows='2' " . $maxlength . " style='box-sizing: border-box;width: " . (!empty($g_q_width) ? (($g_q_width >= MAX_WIDTH_TEXT_INPUT) ? '100%' : ($g_q_width . 'px')) : '100%') . ";height: 250px'>" . $value . "</textarea> {$g_q_unit}\r\n";
            break;
        case "N":
            $maxlength = (!empty($g_q_words)) ? "maxlength='" . $g_q_words . "'" : "maxlength='2000'";
            $value = isset($RESERCH_ANS[$g_q_no]) ? $RESERCH_ANS[$g_q_no]['q_value'] : '';
            $str_result .= "<input type='text' class='number_question' name='answers[" . $g_q_no . "][q_value]' " . $maxlength . " value='" . $value . "' style='box-sizing: border-box;width: " . (!empty($g_q_width) ? (($g_q_width >= MAX_WIDTH_TEXT_INPUT) ? '100%' : ($g_q_width . 'px')) : 'inherit') . ";'> {$g_q_unit}\r\n";
            break;
        case "R":
            $q_value = explode('|', $g_q_value);
            $sel = (isset($RESERCH_ANS[$g_q_no]['q_value']) && is_array($RESERCH_ANS[$g_q_no]['q_value'])) ? $RESERCH_ANS[$g_q_no]['q_value'] : array();
            for ($i = 0; $i < count($q_value); $i++) {
                if ($i != 0) {
                    $str_result .= (($g_q_disp == "0") ? "<br>" : "&nbsp;&nbsp;");
                }
                $str_result .= ($i + 1) . "位\r\n";
                $str_result .= "&nbsp;<select name='answers[" . $g_q_no . "][q_value][]'>\r\n";
                $str_result .= "<option value=''></option>\r\n";
                for ($j = 0; $j < count($q_value); $j++) {
                    $sel2 = '';
                    for ($x = 0; $x < count($sel); $x++) {
                        if ($i == $x && ($sel[$x] == $q_value[$j])) {
                            $sel2 = " selected='selected'";
                        }
                    }

                    $str_result = $str_result . "<option value='" . $q_value[$j] . "' " . $sel2 . ">" . $q_value[$j] . "</option>\r\n";
                }
                $str_result .= "</select>\r\n";
            }
            break;
        default:
            $str_result = 'Error';
            break;
    }

    return $str_result;
}

if (empty ($cmd)) {
    if (!empty($_SESSION['arrSession']) && empty($_REQUEST['memreg_open'])) {
        if (!empty($_COOKIE['interrupt']["{$login_user->P_ID}_{$research_id}"])) {
            $interrupt_q_no = $_COOKIE['interrupt']["{$login_user->P_ID}_{$research_id}"];
        }
    }

    displayEditDataQuestion($researchQuestion->ListResearchQuestion);

    //before question
    if (!empty($interrupt_q_no) && $is_display_all == RESEARCH_ANSWER_DISPLAY_ONE) {
        $g_q_no = $interrupt_q_no;
        $dataQuestions = getQuestionDetail($g_q_no, $researchQuestion->ListResearchQuestion, $is_display_all);
        $key = getFirstKey($dataQuestions);
        $next = explode('|', $dataQuestions[$key]->Q_NEXT);
        $g_q_no_next = ($is_display_all == RESEARCH_ANSWER_DISPLAY_ONE) ? $next[0] : RESEARCH_QUESTION_INFO;
        $g_q_no_back = $interrupt_q_no - 1;
    } else {
        $g_q_no = 1;
        $dataQuestions = getQuestionDetail($g_q_no, $researchQuestion->ListResearchQuestion, $is_display_all);
        $g_q_no_next = ($is_display_all == RESEARCH_ANSWER_DISPLAY_ONE) ? 2 : RESEARCH_QUESTION_INFO;
        $g_q_no_back = 0;
    }

    $resultResearch = $research->getResearchAnswerTarget($post_id, $research_id, $m_tno);
    $dataResearchTarget = $resultResearch->MemberInfo;
    $g_sign1 = $dataResearchTarget->top_g_name;
    $g_sign3 = $dataResearchTarget->g_name;
    $g_pos = $dataResearchTarget->c_position;
    $g_sign4 = $dataResearchTarget->c_name;
    $g_sign6 = $dataResearchTarget->c_tel;
    $g_sign7 = $dataResearchTarget->c_fax;
    $g_sign8 = $dataResearchTarget->c_email;
} else {
    if ($cmd === RESEARCH_QUESTION_MODE_BACK) {
        if ($_REQUEST['q_no_back'] > 0) {
            if ($_REQUEST['q_no_back'] == RESEARCH_QUESTION_INFO) {
                $g_q_no = RESEARCH_QUESTION_INFO;
                $g_q_no_next = RESEARCH_QUESTION_CONFIRM;
                $g_q_no_back = $_REQUEST['max_q_no'];
            } else {
                $g_q_no = $_REQUEST['q_no_back'];
                $dataQuestions = getQuestionDetail($g_q_no, $researchQuestion->ListResearchQuestion, $is_display_all);
                $key = getFirstKey($dataQuestions);
                $next = explode('|', $dataQuestions[$key]->Q_NEXT);
                $g_q_no_next = ($is_display_all == RESEARCH_ANSWER_DISPLAY_ONE) ? $next[0] : RESEARCH_QUESTION_INFO;
                $g_q_no_back = $dataQuestions[$key]->Q_NO - 1;
            }
        }
    }

    if ($cmd === RESEARCH_QUESTION_MODE_NEXT) {
        if (is_numeric($_REQUEST['q_no_next']) && $_REQUEST['q_no_next'] <= RESEARCH_QUESTION_INFO) {
            if (($_REQUEST['q_no_next'] == RESEARCH_QUESTION_INFO) || ($_REQUEST['q_no_next'] > $max_q_no)) {
                if ($sign_flg != 0) {
                    $g_q_no = RESEARCH_QUESTION_INFO;
                    $g_q_no_next = RESEARCH_QUESTION_CONFIRM;
                    $g_q_no_back = $_REQUEST['max_q_no'];
                } else {
                    $g_q_no = RESEARCH_QUESTION_CONFIRM;
                    $g_q_no_next = RESEARCH_QUESTION_COMPLETE;
                    $g_q_no_back = $_REQUEST['max_q_no'];
                }
            } else {
                $g_q_no = $_REQUEST['q_no_next'];
                $dataQuestions = getQuestionDetail($g_q_no, $researchQuestion->ListResearchQuestion, $is_display_all);
                $key = getFirstKey($dataQuestions);
                $next = explode('|', $dataQuestions[$key]->Q_NEXT);
                $g_q_no_next = $next[0];
                $g_q_no_back = $dataQuestions[$key]->Q_NO - 1;
            }

            if (isset($_REQUEST['answers'])) {
                $answers = $_REQUEST['answers'];
                if ($is_display_all == RESEARCH_ANSWER_DISPLAY_ONE) {
                    $key = getFirstKey($answers);
                    $_SESSION['RESERCH_ANS'][$key] = $answers[$key];
                } else {
                    $_SESSION['RESERCH_ANS'] = $answers;
                }
            }
        } else if ($_REQUEST['q_no_next'] == RESEARCH_QUESTION_CONFIRM) {
            $g_q_no = RESEARCH_QUESTION_CONFIRM;
            $g_q_no_next = RESEARCH_QUESTION_COMPLETE;
            $g_q_no_back = 999;
        } else if ($_REQUEST['q_no_next'] == RESEARCH_QUESTION_COMPLETE) {
            if (!empty($_SESSION['RESERCH_ANS'])) {
                $g_q_no = RESEARCH_QUESTION_COMPLETE;
                $arrBody = array(
                    'p_id' => isset($login_user->P_ID) && empty($_REQUEST['memreg_open']) ? $login_user->P_ID : '',
                    'tg_id' => $tg_id,
                    'research_id' => $research_id,
                    't_no' => $m_tno
                );
                $memberInfo = array(
                    'top_g_name' => $g_sign1,
                    'g_name' => $g_sign3,
                    'c_position' => $g_pos,
                    'c_name' => $g_sign4,
                    'c_tel' => $g_sign6,
                    'c_fax' => $g_sign7,
                    'c_email' => $g_sign8
                );
                $mailInfo = array(
                    'top_g_name' => $dataResearchInfo->SIGN1 != 0 ? $g_sign1 : null,
                    'g_name' => $dataResearchInfo->SIGN3 != 0 ? $g_sign3 : null,
                    'c_name' => $dataResearchInfo->SIGN4 != 0 ? $g_sign4 : null,
                    'c_tel' => $dataResearchInfo->SIGN6 != 0 ? $g_sign6 : null,
                    'c_fax' => $dataResearchInfo->SIGN7 != 0 ? $g_sign7 : null,
                    'c_email' => $dataResearchInfo->SIGN8 != 0 ? $g_sign8 : null,
                    'c_position' => $dataResearchInfo->SIGN23 != 0 ? $g_pos : null
                );
                $listResearchQuestion = $researchQuestion->ListResearchQuestion;
                if (!empty($listResearchQuestion)) {
                    foreach ($listResearchQuestion as $i => $question) {
                        if (!empty($_SESSION['RESERCH_ANS'][$question->Q_NO])) {
                            $result = $_SESSION['RESERCH_ANS'][$question->Q_NO]['q_value'];
                            $result_other = !empty($_SESSION['RESERCH_ANS'][$question->Q_NO]['q_other_value']) ? $_SESSION['RESERCH_ANS'][$question->Q_NO]['q_other_value'] : '';
                            if (!empty($result)) {
                                if (is_array($result)) {
                                    $listResearchQuestion[$i]->RESULT = implode('|', $result);
                                } else {
                                    $listResearchQuestion[$i]->RESULT = $result;
                                }
                            }
                            if (!empty($result_other)) {
                                if (is_array($result_other)) {
                                    $listResearchQuestion[$i]->RESULT_OTHER = implode('|', $result_other);
                                } else {
                                    $listResearchQuestion[$i]->RESULT_OTHER = $result_other;
                                }
                            }
                        }
                    }
                }
                $post_answer = $research->postResearchAnswer($post_id, $arrBody, $listResearchQuestion, $memberInfo);
                if (isset($login_user->P_ID) && empty($_REQUEST['memreg_open'])) {
                    $check_answer = $research->CheckAnswer($post_id, $tg_id, $research_id, $login_user->P_ID);
                    $m_tno = isset($check_answer->g_tno) ? $check_answer->g_tno : '';
                }
                $g_msg = isset($post_answer->g_msg) ? $post_answer->g_msg : '';
                $err = isset($post_answer->Message) ? $post_answer->Message : '';
                $email = isset($post_answer->email) ? $post_answer->email : '';
                $ans_return_mail_flg = isset($post_answer->ans_return_mail_flg) ? $post_answer->ans_return_mail_flg : '';
                /*if ( !empty( $email ) && $ans_return_mail_flg == 1 ) {
                    $mail_from = trim( get_post_meta( $post_id, 'mail_address', true ) );
                    $pass_from = MAIL_PASSWORD;
                    $send_mail = $research->SendAnsReturnMail( $post_id, $tg_id, $research_id, $mail_from, $pass_from, $email );
                }*/
                $mail_from = trim(get_post_meta($post_id, 'mail_address', true));
                if (!empty($email)) {
                    $mail_from = $mail_from . ',' . $email;
                }
                $env = $research->getEnv();
                $send_mail = $research->SendAnsReturnMail($post_id, $tg_id, $research_id, "", "", $mail_from, $m_tno, $env, rawurlencode(serialize($mailInfo)));

                if (!empty($_SESSION['arrSession'])) {
                    setcookie("interrupt[{$login_user->P_ID}_{$research_id}]", '', -1, '/');
                }
            } else {
                $g_q_no = 1;
                $dataQuestions = getQuestionDetail($g_q_no, $researchQuestion->ListResearchQuestion, $is_display_all);
                $g_q_no_next = ($is_display_all == RESEARCH_ANSWER_DISPLAY_ONE) ? 2 : RESEARCH_QUESTION_INFO;
                $g_q_no_back = 0;
            }
        } else if ($_REQUEST['q_no_next'] == RESEARCH_QUESTION_MODE_INTERRUPT) {
            if (isset($_REQUEST['answers'])) {
                $g_q_no = RESEARCH_QUESTION_MODE_INTERRUPT;
                $answers = $_REQUEST['answers'];
                if ($is_display_all == RESEARCH_ANSWER_DISPLAY_ONE) {
                    $key = getFirstKey($answers);
                    $_SESSION['RESERCH_ANS'][$key] = $answers[$key];
                } else {
                    $_SESSION['RESERCH_ANS'] = $answers;
                }

                $arrBody = array(
                    'p_id' => $login_user->P_ID,
                    'tg_id' => $tg_id,
                    'research_id' => $research_id,
                    't_no' => $m_tno
                );
                $memberInfo = array(
                    'top_g_name' => $g_sign1,
                    'g_name' => $g_sign3,
                    'c_position' => $g_pos,
                    'c_name' => $g_sign4,
                    'c_tel' => $g_sign6,
                    'c_fax' => $g_sign7,
                    'c_email' => $g_sign8
                );
                $mailInfo = array(
                    'top_g_name' => $dataResearchInfo->SIGN1 != 0 ? $g_sign1 : null,
                    'g_name' => $dataResearchInfo->SIGN3 != 0 ? $g_sign3 : null,
                    'c_name' => $dataResearchInfo->SIGN4 != 0 ? $g_sign4 : null,
                    'c_tel' => $dataResearchInfo->SIGN6 != 0 ? $g_sign6 : null,
                    'c_fax' => $dataResearchInfo->SIGN7 != 0 ? $g_sign7 : null,
                    'c_email' => $dataResearchInfo->SIGN8 != 0 ? $g_sign8 : null,
                    'c_position' => $dataResearchInfo->SIGN23 != 0 ? $g_pos : null
                );
                $listResearchQuestion = $researchQuestion->ListResearchQuestion;
                if (!empty($listResearchQuestion)) {
                    foreach ($listResearchQuestion as $i => $question) {
                        if ($question->Q_NO <= $_REQUEST['interrupt']) {
                            if (!empty($_SESSION['RESERCH_ANS'][$question->Q_NO])) {
                                $result = $_SESSION['RESERCH_ANS'][$question->Q_NO]['q_value'];
                                $result_other = !empty($_SESSION['RESERCH_ANS'][$question->Q_NO]['q_other_value']) ? $_SESSION['RESERCH_ANS'][$question->Q_NO]['q_other_value'] : '';
                                if (!empty($result)) {
                                    if (is_array($result)) {
                                        $listResearchQuestion[$i]->RESULT = implode('|', $result);
                                    } else {
                                        $listResearchQuestion[$i]->RESULT = $result;
                                    }
                                }
                                if (!empty($result_other)) {
                                    if (is_array($result_other)) {
                                        $listResearchQuestion[$i]->RESULT_OTHER = implode('|', $result_other);
                                    } else {
                                        $listResearchQuestion[$i]->RESULT_OTHER = $result_other;
                                    }
                                }
                            }
                        } else {
                            unset($listResearchQuestion[$i]);
                        }
                    }
                }
                $post_answer = $research->postResearchAnswer($post_id, $arrBody, $listResearchQuestion, $memberInfo);
                if (isset($login_user->P_ID) && empty($_REQUEST['memreg_open'])) {
                    $check_answer = $research->CheckAnswer($post_id, $tg_id, $research_id, $login_user->P_ID);
                    $m_tno = isset($check_answer->g_tno) ? $check_answer->g_tno : '';
                }
                $g_msg = isset($post_answer->g_msg) ? $post_answer->g_msg : '';
                $err = isset($post_answer->Message) ? $post_answer->Message : '';
                $email = isset($post_answer->email) ? $post_answer->email : '';
                $ans_return_mail_flg = isset($post_answer->ans_return_mail_flg) ? $post_answer->ans_return_mail_flg : '';
                $env = $research->getEnv();
                $send_mail = $research->SendAnsReturnMail($post_id, $tg_id, $research_id, "", "", $email, $m_tno, $env, rawurlencode(serialize($mailInfo)));

                setcookie("interrupt[{$login_user->P_ID}_{$research_id}]", $_REQUEST['interrupt'], time() + (86400 * 30), '/');
            } else {
                $g_q_no = 1;
                $dataQuestions = getQuestionDetail($g_q_no, $researchQuestion->ListResearchQuestion, $is_display_all);
                $g_q_no_next = ($is_display_all == RESEARCH_ANSWER_DISPLAY_ONE) ? 2 : RESEARCH_QUESTION_INFO;
                $g_q_no_back = 0;
            }
        } else {
            echo 'Research not found.';
            exit;
        }
    }
}

/*echo '<pre>';
print_r($researchQuestion);
echo '</pre>';
exit;*/
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
    <title><?php echo $dataResearchInfo->RESERCH_NAME; ?></title>

    <link rel="stylesheet" type="text/css"
          href="https://dev.nakamacloud.com/nakama2/Content/temp/body.min.css?uc=201911151536"/>
    <link rel="stylesheet" type="text/css"
          href="https://dev.nakamacloud.com/nakama2/Content/master_import.min.css?uc=201911151536"/>
    <link rel="stylesheet" type="text/css"
          href="https://dev.nakamacloud.com/nakama2/Content/temp/nakama.min.css?uc=201911151536"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/sweetalert2/dist/sweetalert2.min.css"/>

    <script src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill@7.1.0/dist/promise.min.js"></script>
    <script type="text/javascript" charset="utf-8"
            src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/sweetalert2/dist/sweetalert2.min.js"></script>
    <script type="text/javascript"
            src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>assets/js/inputCheck.js"></script>

    <style type="text/css">
        table {
            width: 95%;
            margin: 0 auto;
        }

        table#enq_title {
            background: #dcdcdc;
            border: 1px solid #a0a0a0;
        }

        table#enq_title td {
            text-align: center;
        }

        table td.ans_title {
            margin-left: 1em;
        }

        table td.research_data {
            font-weight: bold;
            font-size: 12pt;
        }

        .required {
            color: red
        }
    </style>

    <script type="text/javascript">
        <?php if ( empty($_REQUEST['memreg_open']) && empty($_SESSION['arrSession']) ) { ?>
        window.location = "<?php echo researchCrSet::getPageSlug('nakama-login') . "top_g_id={$tg_id}&research_id={$research_id}&post_id={$post_id}&ans_type={$is_display_all}"; ?>";
        <?php } ?>

        var ques = '<?php echo json_encode($dataQuestions); ?>';
        ques = ques.indexOf("\n") != -1 ? ques.replace(/\n/g, "") : ques;
        ques = ques.indexOf("\r") != -1 ? ques.replace(/\r/g, "") : ques;
        var data_questions = JSON.parse(ques);
        var display_all = '<?php echo $is_display_all; ?>';
        //console.log('DATA QUESTIONS', data_questions);
        //console.log('SESSSION RESERCH_ANS', JSON.parse('<?php echo json_encode($_SESSION['RESERCH_ANS']); ?>'));

        $(document).ready(function () {
            $('input.number_question').bind('keypress', function (e) {
                return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
            });

            <?php if ( !empty($interrupt_q_no) && $is_display_all == RESEARCH_ANSWER_DISPLAY_ALL ) { ?>
            $('html, body').animate({
                scrollTop: ($('#wrap-q-value-' + <?php echo $interrupt_q_no; ?>).offset().top - 230)
            }, 100, function () {
                $("input[name='answers[" + <?php echo $interrupt_q_no; ?> +"][q_value]']").select();
                $("textarea[name='answers[" + <?php echo $interrupt_q_no; ?> +"][q_value]'], input[name='answers[" + <?php echo $interrupt_q_no; ?> +"][q_value]']").focus();
            });
            <?php } ?>
        });

        function OnOtherRadio(other_id, q_no, q_next) {
            OnFreeNextQuestion(q_next, q_no);
            if (other_id != "") {
                $("input[name='answers[" + q_no + "][q_other_value][]").each(function () {
                    var id = $(this).attr('id');
                    var type = $(this).attr('type');
                    if (type !== "hidden") {
                        if (id == other_id) {
                            $(this).removeAttr('readonly').css({'background-color': '#FFFFFF'});
                        } else {
                            $(this).attr('readonly', 'readonly').css({'background-color': '#C0C0C0'});
                        }
                    }
                });
            }
        }

        function OnOtherSelect(q_no) {
            var other_id = $('#wrap-q-value-' + q_no + ' select').find(':selected').data('other-id');
            var q_next = $('#wrap-q-value-' + q_no + ' select').find(':selected').data('q-next');
            OnFreeNextQuestion(q_next, q_no);
            $("input[name='answers[" + q_no + "][q_other_value][]").each(function () {
                var id = $(this).attr('id');
                var type = $(this).attr('type');
                if (type !== "hidden") {
                    if (id == other_id) {
                        $(this).removeAttr('readonly').css({'background-color': '#FFFFFF'});
                    } else {
                        $(this).attr('readonly', 'readonly').css({'background-color': '#C0C0C0'});
                    }
                }
            });
        }

        function OnOtherCheckBox(other_id, _this) {
            if (other_id != "") {
                var type = $('#' + other_id).attr('type');
                if (type !== "hidden") {
                    if ($(_this).is(':checked')) {
                        $('#' + other_id).removeAttr('readonly').css({'background-color': '#FFFFFF'});
                    } else {
                        $('#' + other_id).attr('readonly', 'readonly').css({'background-color': '#C0C0C0'});
                    }
                }
            }
        }

        function OnFreeNextQuestion(q_next, q_no) {
            console.log('q_next', q_next);
            if (q_next != '') {
                <?php if ( $is_display_all == RESEARCH_ANSWER_DISPLAY_ALL ) { ?>
                if (q_next > (q_no + 1) && q_next < 999) {
                    $('html, body').animate({
                        scrollTop: ($('#wrap-q-value-' + q_next).offset().top - 230)
                    }, 500, function () {
                        $("input[name='answers[" + q_next + "][q_value]']").select();
                        $("textarea[name='answers[" + q_next + "][q_value]'], input[name='answers[" + q_next + "][q_value]']").focus();
                    });
                }
                <?php } else { ?>
                $('#q_no_next').val(q_next);
                <?php } ?>
            }
        }

        function OnCommand(action, interrupt_q_no) {
            var interrupt_q_no = interrupt_q_no || '';
            $('#cmd').val(action);
            switch (action) {
                case '<?php echo RESEARCH_QUESTION_MODE_NEXT; ?>':
                    var valid = true;

                    $.each(data_questions, function (i, question) {
                        var q_no = question.Q_NO;
                        var q_type = question.Q_TYPE;
                        var q_must = question.Q_MUST;
                        var q_words = question.Q_WORDS;
                        var q_name = (display_all == '1') ? (question.Q_NAME + 'は') : '';

                        if (interrupt_q_no != '' && q_no > interrupt_q_no) {
                            return false;
                        }

                        switch (q_type) {
                            case 'S1':
                                if (q_must != 0) {
                                    var val = $("input[name='answers[" + q_no + "][q_value]']:checked").val();
                                    if (typeof val === 'undefined') {
                                        notice('error', q_name + '必ず回答するようお願いいたします。', function () {
                                        }, q_no);
                                        valid = false;
                                    }
                                }
                                break;
                            case 'S2':
                                if (q_must != 0) {
                                    var val = $("select[name='answers[" + q_no + "][q_value]']").val();
                                    if (val == "") {
                                        $("select[name='answers[" + q_no + "][q_value]']").focus();
                                        notice('error', q_name + '必ず回答するようお願いいたします。', function () {
                                        }, q_no);
                                        valid = false;
                                    }
                                }
                                break;
                            case 'M':
                                var select_checked = $("input[name='answers[" + q_no + "][q_value][]']:checked").length;

                                if (q_must != 0) {
                                    if (select_checked == 0) {
                                        notice('error', q_name + '必ず回答するようお願いいたします。', function () {
                                        }, q_no);
                                        valid = false;
                                    }
                                }

                                var select = question.Q_SELECT;
                                if (select_checked < select) {
                                    notice('error', q_name + "選択できるのは" + select + "個までです。", function () {
                                    }, q_no);
                                    valid = false;
                                }
                                break;
                            case 'T1':
                                var val = $("input[name='answers[" + q_no + "][q_value]']").val();

                                if (q_must != 0) {
                                    if (val == "") {
                                        notice('error', q_name + '必ず回答するようお願いいたします。', function () {
                                        }, q_no);
                                        valid = false;
                                    }
                                }

                                if (q_words != null) {
                                    var word = eval(q_words) * 2;
                                    if (IsLengthB(val, 0, word, '回答項目') != 0) {
                                        $("input[name='answers[" + q_no + "][q_value]']").select();
                                        $("input[name='answers[" + q_no + "][q_value]']").focus();
                                        valid = false;
                                    }
                                }
                                break;
                            case 'T2':
                                var val = $("textarea[name='answers[" + q_no + "][q_value]']").val();

                                if (q_must != 0) {
                                    if (val == "") {
                                        notice('error', q_name + '必ず回答するようお願いいたします。', function () {
                                        }, q_no);
                                        valid = false;
                                    }
                                }

                                if (q_words != null) {
                                    var word = eval(q_words) * 2;
                                    if (IsLengthB(val, 0, word, '回答項目') != 0) {
                                        $("textarea[name='answers[" + q_no + "][q_value]']").select();
                                        $("textarea[name='answers[" + q_no + "][q_value]']").focus();
                                        valid = false;
                                    }
                                }
                                break;
                            case 'N':
                                var val = $("input[name='answers[" + q_no + "][q_value]']").val();

                                if (q_must != 0) {
                                    if (val == "") {
                                        notice('error', q_name + '必ず回答するようお願いいたします。', function () {
                                        }, q_no);
                                        valid = false;
                                    }
                                }

                                if (val != "") {
                                    if (!$.isNumeric(val, 0, false)) {
                                        notice('error', q_name + '半角数字で入力してください。', function () {
                                        }, q_no);
                                        valid = false;
                                    }
                                }

                                if (q_words != null) {
                                    var word = eval(q_words);
                                    if (IsLengthB(val, 0, word, '回答項目') != 0) {
                                        $("input[name='answers[" + q_no + "][q_value]']").select();
                                        $("input[name='answers[" + q_no + "][q_value]']").focus();
                                        valid = false;
                                    }
                                }
                                break;
                            case 'R':
                                console.log('R');
                                break;
                            default:
                                break;
                        }

                        if (valid === false) {
                            return valid;
                        }
                    });

                    if (valid === false) {
                        return valid;
                    }
                    var valMust = document.getElementsByName('valMust[]');
                    for (let i = 0; i < valMust.length; i++) {
                        var obj = document.getElementsByClassName("item_info")[i];
                        var titleInfo = document.getElementsByClassName("titleInfo")[i];
                        if (valMust[i].value == 1) {
                            if (obj.value.trim() == "") {
                                alert(titleInfo.innerText + "は必須入力です。");
                                obj.focus();
                                return false;
                            }
                        }
                    }
                    break;
                case '<?php echo RESEARCH_QUESTION_MODE_BACK; ?>':
                    //redirect to detail
                    if ($('#q_no_back').val() === "0") {
                        <?php if ( $g_agree_flg == 0 ) { ?>
                        window.location = "<?php echo researchCrSet::getPageSlug('nakama-detail-research') . "top_g_id={$tg_id}&research_id={$research_id}&post_id={$post_id}"; ?>";
                        return;
                        <?php } else { ?>
                        window.location = "<?php echo $research->getPageSlug('nakama-research-agreement') . "tg_id={$tg_id}&research_id={$research_id}&post_id={$post_id}&ans_type={$is_display_all}"; ?>";
                        return;
                        <?php } ?>
                    }
                    break;
                case '<?php echo RESEARCH_QUESTION_MODE_STOP; ?>':
                    notice('confirm', '入力内容を破棄します。よろしいですか？', function () {
                        window.close();
                    }, '');
                    return;
                    break;
                case '<?php echo RESEARCH_QUESTION_MODE_CLOSE; ?>':
                    window.close();
                    return;
                    break;
            }

            if (interrupt_q_no != '') {
                $('#q_no_next').val('<?php echo RESEARCH_QUESTION_MODE_INTERRUPT; ?>');
            }

            $('#mainForm').submit();
        }

        function OnInterrupt(interrupt_q_no) {
            $('#interrupt').val(interrupt_q_no);
            //$('#cmd').val('<?php echo RESEARCH_QUESTION_MODE_NEXT; ?>');
            //$('#q_no_next').val('<?php echo RESEARCH_QUESTION_MODE_INTERRUPT; ?>');
            //$('#mainForm').submit();
            OnCommand('<?php echo RESEARCH_QUESTION_MODE_NEXT; ?>', interrupt_q_no);
            return true;
        }

        function notice(type, message, callBackFunc, q_no) {
            var type = type || 'error';
            var message = message || '';
            var callBackFunc = callBackFunc || function () {
            };
            var q_no = q_no || '';

            if (type === 'error') {
                swal({
                    title: '',
                    type: 'error',
                    html: message
                }).then(function (e) {
                    callBackFunc();
                    $('html, body').animate({
                        scrollTop: ($('#wrap-q-value-' + q_no).offset().top - 50)
                    }, 100, function () {
                        $("input[name='answers[" + q_no + "][q_value]']").select();
                        $("textarea[name='answers[" + q_no + "][q_value]'], input[name='answers[" + q_no + "][q_value]']").focus();
                    });
                });
            } else if (type === 'confirm') {
                swal({
                    title: '',
                    type: 'question',
                    html: message,
                    confirmButtonText: 'はい',
                    cancelButtonText: 'いいえ',
                    showCancelButton: true
                }).then(function (e) {
                    if (e == true) {
                        callBackFunc();
                    }
                });
            }
        }

        //専用アンケートからの会員情報確認変更▼
        function openApplyUpdateMember() {
            var um_win = window.open('<?php echo $applyUpdateMember_url; ?>', '_blank');
            um_win.focus();
        }

        //専用アンケートからの会員情報確認変更▼
        function openUpdateAddress() {
            var um_win = window.open('<?php echo $UpdateAddress_url; ?>', '_blank');
            um_win.focus();
        }

    </script>
</head>
<body class="main">
<div id="container-main" style="min-width:600px;">
    <div id="wrapper" style="padding-bottom:0;">
        <div id="maincontent">
            <div id="contents" style="min-width:600px;padding-left:0;">
                <form action="" id="mainForm" name="mainForm" method="post">
                    <input type="hidden" name="page_id" id="page_id"
                           value="<?php echo get_page_by_path('nakama-research-ans-research')->ID; ?>"/>
                    <input type="hidden" name="tg_id" id="tg_id" value="<?php echo $tg_id; ?>"/>
                    <input type="hidden" name="post_id" id="post_id" value="<?php echo $post_id; ?>"/>
                    <input type="hidden" name="research_id" id="research_id" value="<?php echo $research_id; ?>"/>
                    <input type="hidden" name="ans_type" id="ans_type" value="<?php echo $is_display_all; ?>"/>
                    <input type="hidden" name="memreg_open" id="memreg_open" value="<?php echo $memreg_open; ?>"/>
                    <input type="hidden" name="interrupt" id="interrupt" value=""/>
                    <input type="hidden" name="max_q_no" id="max_q_no" value="<?php echo $max_q_no; ?>">
                    <input type="hidden" name="m_tno" id="m_tno" value="<?php echo $m_tno; ?>">
                    <input type="hidden" name="q_no_back" id="q_no_back" value="<?php echo $g_q_no_back; ?>"/>
                    <input type="hidden" name="q_no_next" id="q_no_next" value="<?php echo $g_q_no_next; ?>"/>
                    <input type="hidden" name="cmd" id="cmd" value="<?php echo $cmd; ?>"/>
                    <div id="research_contents">
                        <!--<br/>
                        <table width="800" class="align-center" cellspacing="1" cellpadding="10" border="1">
                            <tr>
                                <td class="attention">
                                    <?php echo 'この画面は登録されたアンケートの確認用の画面です。'; ?>
                                    <br/>
                                    <?php echo '実際の回答結果には影響されません。'; ?>
                                </td>
                            </tr>
                        </table>-->
                        <br/>
                        <?php if ($g_q_no != RESEARCH_QUESTION_COMPLETE && $g_q_no != RESEARCH_QUESTION_MODE_INTERRUPT) { ?>
                            <table width="800" style="margin-top: 7px; margin-bottom: 4px; position: relative">
                                <tr>
                                    <td style="font-size: 11px">
                                        <?php echo nl2br($dataResearchInfo->CONTENTS); ?>
                                        <?php if ($is_display_all == RESEARCH_ANSWER_DISPLAY_ONE && empty($_REQUEST['memreg_open'])) { ?>
                                            <?php if (is_numeric($g_q_no) && $g_q_no < RESEARCH_QUESTION_INFO) { ?>
                                                <button type="button"
                                                        style="position: absolute; bottom: 0px; right: -9px"
                                                        class="negative regular" name="btn_interrupt"
                                                        onclick="OnInterrupt(<?php echo $g_q_no; ?>);">
                                                    <?php echo '中　断'; ?>
                                                </button>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            </table>
                            <br/>
                        <?php } ?>
                        <table id="enq_title" class="align-center">
                            <tr>
                                <td valign="middle" height="40" class="research_data">
                                    <?php echo $dataResearchInfo->RESERCH_NAME; ?>
                                </td>
                            </tr>
                            <?php if ($g_q_no !== RESEARCH_QUESTION_CONFIRM && !empty($dataResearchInfo->DETAIL_URL)) { ?>
                                <tr>
                                    <td>
                                        <?php echo $research->research_convertNvl(nl2br($research->research_renderTitleEnd($selectTitleN, 'アンケート参考ページＵＲＬ')), 'アンケート参考ページＵＲＬ'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="<?php echo $dataResearchInfo->DETAIL_URL; ?>" target="_blank">
                                            <?php echo $dataResearchInfo->DETAIL_NAME; ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                        <?php if (is_numeric($g_q_no) && $g_q_no < RESEARCH_QUESTION_INFO) { ?>
                            <?php $first_key = getFirstKey($dataQuestions); ?>
                            <?php foreach ($dataQuestions as $key => $dataQuestion) { ?>
                                <?php if ($key == $first_key && !empty($dataResearchInfo->DETAIL_FILE)) { ?>
                                    <table class="align-center" border="0">
                                        <tr>
                                            <td align="center">
                                                <?php echo $research->research_convertNvl(nl2br($research->research_renderTitleEnd($selectTitleN, '参考添付ファイル')), '参考添付ファイル'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <?php echo $research->getResearchFile($tg_id, $research_id, $dataResearchInfo->DETAIL_FILE); ?>
                                            </td>
                                        </tr>
                                    </table>
                                <?php } else { ?>
                                    <br/>
                                <?php } ?>
                                <?php if ($is_display_all == RESEARCH_ANSWER_DISPLAY_ALL && empty($_REQUEST['memreg_open'])) { ?>
                                    <table class="align-center" style="width:80%">
                                        <tr>
                                            <td align="right">
                                                <button type="button"
                                                        style="float: none; display: inline; margin-right: 0px"
                                                        class="negative regular" name="btn_interrupt"
                                                        onclick="OnInterrupt(<?php echo $dataQuestion->Q_NO; ?>);">
                                                    <?php echo '中　断'; ?>
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                <?php } ?>
                                <table class="align-center" border="0">
                                    <?php if ($max_q_no > 0) { ?>
                                        <tr>
                                            <td align="right">
                                                （<?php echo $dataQuestion->Q_NO; ?>／<?php echo $max_q_no; ?>）
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                                <input type="hidden" name="answers[<?php echo $dataQuestion->Q_NO; ?>][q_no]"
                                       id="q_no_<?php echo $dataQuestion->Q_NO; ?>"
                                       value="<?php echo $dataQuestion->Q_NO; ?>"/>
                                <input type="hidden" name="answers[<?php echo $dataQuestion->Q_NO; ?>][q_name]"
                                       id="q_type_<?php echo $dataQuestion->Q_NO; ?>"
                                       value="<?php echo $dataQuestion->Q_NAME; ?>"/>
                                <input type="hidden" name="answers[<?php echo $dataQuestion->Q_NO; ?>][q_type]"
                                       id="q_type_<?php echo $dataQuestion->Q_NO; ?>"
                                       value="<?php echo $dataQuestion->Q_TYPE; ?>"/>
                                <input type="hidden" name="answers[<?php echo $dataQuestion->Q_NO; ?>][q_must]"
                                       id="q_must_<?php echo $dataQuestion->Q_NO; ?>"
                                       value="<?php echo $dataQuestion->Q_MUST; ?>"/>
                                <input type="hidden" name="answers[<?php echo $dataQuestion->Q_NO; ?>][q_select]"
                                       id="q_select_<?php echo $dataQuestion->Q_NO; ?>"
                                       value="<?php echo $dataQuestion->Q_SELECT; ?>"/>
                                <input type="hidden" name="answers[<?php echo $dataQuestion->Q_NO; ?>][q_words]"
                                       id="q_words_<?php echo $dataQuestion->Q_NO; ?>"
                                       value="<?php echo $dataQuestion->Q_WORDS; ?>"/>
                                <table class="align-center" style="width:80%;border:1px solid #c0c0c0;">
                                    <?php if (!empty($dataQuestion->Q_CLS)) { ?>
                                        <tr>
                                            <td colspan="3" class="align-left">
                                                <?php echo nl2br($dataQuestion->Q_CLS); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td class="ans_title">
                                            <table border="0" width="100%">
                                                <tr>
                                                    <td valign="top" width="41" border="0">
                                                        &nbsp;&nbsp;（<?php echo $dataQuestion->Q_NO; ?>）
                                                    </td>
                                                    <td valign="middle" class="align-left" border="0">
                                                        <?php echo nl2br($dataQuestion->Q_NAME); ?>
                                                    </td>
                                                    <?php if (!empty($dataQuestion->Q_PLATFORM_NAME) && !empty($dataQuestion->Q_PLATFORM_URL)) { ?>
                                                        <td class="align-right" nowrap>
                                                            <a href="<?php echo $dataQuestion->Q_PLATFORM_URL; ?>"
                                                               target="_blank">
                                                                <?php echo $dataQuestion->Q_PLATFORM_NAME; ?>
                                                            </a>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table class="align-center" border="0" style="width:80%;">
                                    <tr>
                                        <td class="align-left valign-middle" height="40">
                                            <?php echo (!empty ($dataQuestion->Q_MUST)) ? "<div class='need'><font color='red'>（必ず回答してください。）</font></div>" : ""; ?>
                                            <?php if ($dataQuestion->Q_TYPE == "N") { ?>
                                                <?php echo (!empty ($dataQuestion->Q_WORDS)) ? "<br><font>（半角で" . $dataQuestion->Q_WORDS . "文字まで入力可能です。）</font>" : ""; ?>
                                            <?php } else { ?>
                                                <?php echo (!empty ($dataQuestion->Q_WORDS)) ? "<br><font>（全角で" . ($dataQuestion->Q_WORDS / 2) . "文字まで入力可能です。）</font>" : ""; ?>
                                            <?php } ?>
                                            <?php echo ($dataQuestion->Q_TYPE == "M" && !empty ($dataQuestion->Q_SELECT)) ? "<br><font>（" . $dataQuestion->Q_SELECT . "個まで選択可能です。）</font>" : ""; ?>
                                            <?php echo ($dataQuestion->Q_TYPE == "N") ? "<br><font>（半角数字で入力してください。）</font>" : ""; ?>
                                            <?php echo "<br>" . nl2br($dataQuestion->Q_TEXT); ?>
                                            <br/>
                                            <br/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-left" id="wrap-q-value-<?php echo $dataQuestion->Q_NO; ?>">
                                            <?php echo displayQuestion($dataQuestion->Q_TYPE, $dataQuestion->Q_VALUE, $dataQuestion->Q_OTHER_TEXT, $dataQuestion->Q_NO, $dataQuestion->Q_DISP, $dataQuestion->Q_WORDS, $dataQuestion->Q_UNIT, $dataQuestion->Q_OTHER_UNIT, $dataQuestion->Q_WIDTH, $dataQuestion->Q_OTHER_WIDTH, $dataQuestion->Q_NEXT); ?>
                                        </td>
                                    </tr>
                                </table>
                                <br/>
                                <br/>
                            <?php } ?>
                            <input id="m_sign1" name="m_sign1" type="hidden" value="<?php echo $g_sign1; ?>">
                            <input id="m_sign1" name="m_sign3" type="hidden" value="<?php echo $g_sign3; ?>">
                            <input id="m_sign1" name="m_sign4" type="hidden" value="<?php echo $g_sign4; ?>">
                            <input id="m_sign1" name="m_sign6" type="hidden" value="<?php echo $g_sign6; ?>">
                            <input id="m_sign1" name="m_sign7" type="hidden" value="<?php echo $g_sign7; ?>">
                            <input id="m_sign1" name="m_sign8" type="hidden" value="<?php echo $g_sign8; ?>">
                            <input id="m_pos" name="m_pos" type="hidden" value="<?php echo $g_pos; ?>">
                        <?php } else if (is_numeric($g_q_no) && $g_q_no === RESEARCH_QUESTION_INFO) { ?>
                            <table class="align-center" border="0">
                                <?php if (!empty($dataResearchInfo->DETAIL_FILE)) { ?>
                                    <tr>
                                        <td align="center">
                                            <?php echo $research->research_convertNvl(nl2br($research->research_renderTitleEnd($selectTitleN, '参考添付ファイル')), '参考添付ファイル'); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <?php echo $research->getResearchFile($tg_id, $research_id, $dataResearchInfo->DETAIL_FILE); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td align="right">
                                        （<?php echo $max_q_no; ?>／<?php echo $max_q_no; ?>）
                                    </td>
                                </tr>
                            </table>
                            <table class="align-center" width="80%" border="0">
                                <tr>
                                    <td class="ans_title">
                                        <table border="0" width="100%">
                                            <tr>
                                                <td valign="middle" class="align-left" border="0" colspan="2">
                                                    <?php echo 'お手数ですが、下記項目にご記入願います。がついているものは必須項目です。'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                            </tr>
                                            <?php if ($dataResearchInfo->SIGN1 != 0) { ?>
                                                <tr>
                                                    <td><span class="titleInfo"><?php echo '会社名'; ?></span>
                                                        <?php if ($dataResearchInfo->SIGN1 == 2) { ?>
                                                            <span class="required">*</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <input id="m_sign1" name="m_sign1" size="40" type="text"
                                                               value="<?php echo $g_sign1; ?>" class="item_info">
                                                        <input type="hidden" name="valMust[]"
                                                               value=" <?= ($dataResearchInfo->SIGN1 == 2) ? 1 : 0 ?>">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($dataResearchInfo->SIGN3 != 0) { ?>
                                                <tr>
                                                    <td><span class="titleInfo"><?php echo '部署'; ?></span>
                                                        <?php if ($dataResearchInfo->SIGN3 == 2) { ?>
                                                            <span class="required">*</span>
                                                        <?php } ?></td>
                                                    <td>
                                                        <input id="m_sign3" name="m_sign3" size="40" type="text"
                                                               value="<?php echo $g_sign3; ?>" class="item_info">
                                                        <input type="hidden" name="valMust[]"
                                                               value=" <?= ($dataResearchInfo->SIGN3 == 2) ? 1 : 0 ?>">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($dataResearchInfo->SIGN23 != 0) { ?>
                                                <tr>
                                                    <td><span class="titleInfo"><?php echo '役職'; ?></span>
                                                        <?php if ($dataResearchInfo->SIGN23 == 2) { ?>
                                                            <span class="required">*</span>
                                                        <?php } ?></td>
                                                    <td>
                                                        <input id="m_pos" name="m_pos" size="40" type="text"
                                                               value="<?php echo $g_pos; ?>" class="item_info">
                                                        <input type="hidden" name="valMust[]"
                                                               value=" <?= ($dataResearchInfo->SIGN23 == 2) ? 1 : 0 ?>">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($dataResearchInfo->SIGN4 != 0) { ?>
                                                <tr>
                                                    <td><span class="titleInfo"><?php echo '氏名'; ?></span>
                                                        <?php if ($dataResearchInfo->SIGN4 == 2) { ?>
                                                            <span class="required">*</span>
                                                        <?php } ?></td>
                                                    <td>
                                                        <input id="m_sign4" name="m_sign4" size="20" type="text"
                                                               value="<?php echo $g_sign4; ?>" class="item_info">
                                                        <input type="hidden" name="valMust[]"
                                                               value=" <?= ($dataResearchInfo->SIGN4 == 2) ? 1 : 0 ?>">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($dataResearchInfo->SIGN6 != 0) { ?>
                                                <tr>
                                                    <td><span class="titleInfo"><?php echo '電話番号'; ?></span>
                                                        <?php if ($dataResearchInfo->SIGN6 == 2) { ?>
                                                            <span class="required">*</span>
                                                        <?php } ?></td>
                                                    <td>
                                                        <input id="m_sign6" maxlength="20" name="m_sign6" size="20"
                                                               type="text" value="<?php echo $g_sign6; ?>"
                                                               class="item_info">
                                                        <input type="hidden" name="valMust[]"
                                                               value=" <?= ($dataResearchInfo->SIGN6 == 2) ? 1 : 0 ?>">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($dataResearchInfo->SIGN7 != 0) { ?>
                                                <tr>
                                                    <td><span class="titleInfo"><?php echo 'FAX番号'; ?></span>
                                                        <?php if ($dataResearchInfo->SIGN7 == 2) { ?>
                                                            <span class="required">*</span>
                                                        <?php } ?></td>
                                                    <td>
                                                        <input id="m_sign7" maxlength="20" name="m_sign7" size="20"
                                                               type="text" value="<?php echo $g_sign7; ?>"
                                                               class="item_info">
                                                        <input type="hidden" name="valMust[]"
                                                               value=" <?= ($dataResearchInfo->SIGN7 == 2) ? 1 : 0 ?>">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($dataResearchInfo->SIGN8 != 0) { ?>
                                                <tr>
                                                    <td><span class="titleInfo"><?php echo 'メールアドレス'; ?></span>
                                                        <?php if ($dataResearchInfo->SIGN8 == 2) { ?>
                                                            <span class="required">*</span>
                                                        <?php } ?></td>
                                                    <td>
                                                        <input id="m_sign8" name="m_sign8" size="50"
                                                               style="width:480px;" type="text"
                                                               value="<?php echo $g_sign8; ?>" class="item_info">
                                                        <input type="hidden" name="valMust[]"
                                                               value=" <?= ($dataResearchInfo->SIGN8 == 2) ? 1 : 0 ?>">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <br/>
                        <?php } else if ($g_q_no === RESEARCH_QUESTION_CONFIRM) { ?>
                            <br/>
                            <table class="align-center" style="width:80%;border:1px solid #c0c0c0;">
                                <tr>
                                    <td class="ans_title">
                                        <table border="0" width="100%">
                                            <tr>
                                                <td valign="middle" style="text-align:center;;font-size:15px">
                                                    <?php echo nl2br(researchNvl($dataResearchInfo->DISP_WORD13, DEFAULT_WORD_RES_COMP_CONFIRM)); ?>
                                                    <input id="m_sign1" name="m_sign1" type="hidden"
                                                           value="<?php echo $g_sign1; ?>">
                                                    <input id="m_sign1" name="m_sign3" type="hidden"
                                                           value="<?php echo $g_sign3; ?>">
                                                    <input id="m_sign1" name="m_sign4" type="hidden"
                                                           value="<?php echo $g_sign4; ?>">
                                                    <input id="m_sign1" name="m_sign6" type="hidden"
                                                           value="<?php echo $g_sign6; ?>">
                                                    <input id="m_sign1" name="m_sign7" type="hidden"
                                                           value="<?php echo $g_sign7; ?>">
                                                    <input id="m_sign1" name="m_sign8" type="hidden"
                                                           value="<?php echo $g_sign8; ?>">
                                                    <input id="m_pos" name="m_pos" type="hidden"
                                                           value="<?php echo $g_pos; ?>">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <br/>
                        <?php } else if ($g_q_no === RESEARCH_QUESTION_COMPLETE) { ?>
                            <br/>
                            <table class="align-center" style="width:80%;border:1px solid #c0c0c0;">
                                <tr>
                                    <td class="ans_title">
                                        <table border="0" width="100%">
                                            <tr>
                                                <td valign="middle" style="text-align:center;font-size:15px">
                                                    <?php echo nl2br(researchNvl($dataResearchInfo->DISP_WORD18, DEFAULT_WORD_RES_COMP)); ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <br/>
                        <?php } else if ($g_q_no === RESEARCH_QUESTION_MODE_INTERRUPT) { ?>
                            <br/>
                            <table class="align-center" style="width:80%;border:1px solid #c0c0c0;">
                                <tr>
                                    <td class="ans_title">
                                        <table border="0" width="100%">
                                            <tr>
                                                <td valign="middle" style="text-align:center;font-size:15px">
                                                    <?php echo nl2br(researchNvl($dataResearchInfo->DISP_WORD18, DEFAULT_WORD_RES_COMP)); ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <br/>
                        <?php } ?>
                        <table width="800" style="margin-top: 7px; margin-bottom: 4px; position: relative">
                            <tr>
                                <td style="vertical-align: top; display: flex; justify-content: center">
                                    <?php if ($g_q_no != RESEARCH_QUESTION_COMPLETE && $g_q_no != RESEARCH_QUESTION_MODE_INTERRUPT) { ?>
                                        <button type="button" style="padding-left:16px;padding-right:16px;"
                                                class="negative regular" name="btn_cancel"
                                                onclick="OnCommand('<?php echo RESEARCH_QUESTION_MODE_STOP; ?>', '');">
                                            <?php echo '中　止'; ?>
                                        </button>
                                        <button type="button" style="padding-left:16px;padding-right:16px;"
                                                class="regular" name="btn_back"
                                                onclick="OnCommand('<?php echo RESEARCH_QUESTION_MODE_BACK; ?>', '');">
                                            <?php echo '戻　る'; ?>
                                        </button>
                                        <button type="button" style="padding-left:16px;padding-right:16px;"
                                                class="regular" name="btn_next"
                                                onclick="OnCommand('<?php echo RESEARCH_QUESTION_MODE_NEXT; ?>', '');">
                                            <?php echo ($g_q_no === RESEARCH_QUESTION_CONFIRM) ? 'は　い' : '次　へ'; ?>
                                        </button>
                                    <?php } else { ?>
                                        <!-- 専用アンケートからの会員情報確認変更▼ -->
                                        <div style="float:left; text-align: center; width: 100%;">
                                            <?php if (($tg_id == CSAJ_TGID && $research_id == CSAJ_RESEARCH_ID) || ($tg_id == NAKAMA2_TGID && $research_id == NAKAMA2_RESEARCH_ID)) {
                                                echo '<div style="float:left; text-align: center; width: 100%;">';
                                                echo '続いて今現在の下の登録内容の確認をお願いします。<br />';
                                                echo '（代表者/担当代表者/連絡担当者/セキュリティ担当者）<br />';
                                                echo '</div>';
                                                echo '<div style="width: 100%; display:flex; justify-content:center;">';
                                                echo '<button type="button" style="padding-left:42px; padding-right:42px; margin-top:32px; margin-bottom:32px;" class="regular" id="btn_applyUpdateMember" onclick="openApplyUpdateMember();" >担当者別内容確認/変更</button>';
                                                echo '</div>';
                                                echo '<div style="width: 100%; display:flex; justify-content:center;">';
                                                echo '<img style="margin-left:-15px;" src="';
                                                echo plugin_dir_url(dirname(__FILE__));
                                                echo '/assets/img/arrow.png" class="none-view">';
                                                echo '</div>';
                                                echo '<div style="float:left; text-align: center; width: 100%;">';
                                                echo '<br />担当者以外のメールアドレス登録ご希望の場合はこちらから登録してください。<br />';
                                                echo '委員会・研究会への参加希望の場合はこちらから登録してください。<br />';
                                                echo '興味分野についてこちらよりご回答ください。続いて今現在の下の登録内容の確認をお願いします。<br />';
                                                echo '</div>';
                                                echo '<div style="width:100%; display:flex; justify-content:center;">';
                                                echo '<button type="button" style="padding-left:42px; padding-right:42px; margin-top:32px; margin-bottom:32px; height:100px;" class="regular" id="btn_UpdateAddress" onclick="openUpdateAddress();" >メールアドレス登録<br/>委員会・研究会参加登録<br/>登録の｢興味分野｣確認/変更</button>';
                                                echo '</div>';
                                                ?>
                                                <div style="text-align:width: 100%; display:flex; justify-content:center;">
                                                    <button type="button"
                                                            style="padding-left:16px;padding-right:16px; margin-top:32px; margin-bottom:32px;"
                                                            class="regular" name="btn_back"
                                                            onclick="OnCommand('<?php echo RESEARCH_QUESTION_MODE_CLOSE; ?>', '');">
                                                        <?php echo '終了する'; ?>
                                                    </button>
                                                </div>
                                            <?php } else { ?>
                                                <!-- 専用アンケートからの会員情報確認変更▲ -->
                                                <button type="button"
                                                        style="margin-left:160px; padding-left:16px;padding-right:16px;"
                                                        class="regular" name="btn_back"
                                                        onclick="OnCommand('<?php echo RESEARCH_QUESTION_MODE_CLOSE; ?>', '');">
                                                    <?php echo '閉じる'; ?>
                                                </button>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                        <br/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>