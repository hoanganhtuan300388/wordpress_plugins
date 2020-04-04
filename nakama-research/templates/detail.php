<?php
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$_SESSION['url_org'] = $actual_link;
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/config/constant.php');
require_once(__ROOT__.'/controller/researchController.php');
$researchs = new researchController();
$tg_id = isset($_REQUEST['top_g_id'])?$_REQUEST['top_g_id']:"";
$research_id = isset($_REQUEST['research_id'])?$_REQUEST['research_id']:"";
$post_id = isset($_REQUEST['post_id'])?$_REQUEST['post_id']:"";
$result = $researchs->getDetail($post_id, $tg_id, $research_id);

$capacityCheck = $researchs->CapacityCheck($post_id, $tg_id, $research_id);
$memberreg_open = !empty($result->RES_MEMBERREG_OPEN) ? $result->RES_MEMBERREG_OPEN : 0;
$ans_type = isset($result->RES_ANS_TYPE) ? $result->RES_ANS_TYPE : '';
$memreg_open = '';
$login_id = (!empty($_SESSION['arrSession'])) ? $_SESSION['arrSession']->USERID : '';
$CheckTarget = $researchs->CheckTarget($post_id, $tg_id, $research_id, $login_id, $memberreg_open);
$target = '';
if(!empty($CheckTarget)){
   $target = $CheckTarget->CheckTarget;
}

$capaFlg = '';
if (!empty($capacityCheck)) {
   $capaFlg = $capacityCheck->Capa_Flg;
}
$inqMail = !empty($result->PREPARED_MAIL) ? $result->PREPARED_MAIL : "";
?>
<!DOCTYPE html>
<html lang="ja">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
      <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7;IE=9; IE=8; IE=7; IE=EDGE">
      <?php if(!empty($result->RES_RESERCH_NAME)) : ?>
        <title><?php echo $result->RES_RESERCH_NAME; ?></title>
      <?php endif; ?>
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/style.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/smart.css">
      <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/css/detail.css">
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/common.js"></script>
      <script type="text/javascript" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/js/detail.js"></script>

       <script>
           function mainSubmit() {
               document.mainForm.submit();
           }

           function mainSubmitMemregOpen() {
               document.mainForm.action = "<?php echo researchCrSet::getPageSlug('nakama-research-agreement'); ?>";
               document.mainForm.page_id.value = "<?php echo get_page_by_path('nakama-research-agreement')->ID; ?>";
               document.mainForm.submit();
           }
       </script>
   </head>
   <body>
   <?php
   $opnSignFlg = 0;
   $page_id = get_page_by_path('nakama-login')->ID;
   $offer1 = researchCrSet::getPageSlug('nakama-login');//."tg_id=".$tg_id."&research_id=".$research_id."&post_id=".$post_id;
   if(($result->RES_MEMBERREG_OPEN == 0 && $result->RES_SEDAI_DISP_UMU == 0) || ($result->RES_MEMBERREG_OPEN == 1 && $result->RES_SEDAI_DISP_UMU == 0)) :
   elseif($result->RES_MEMBERREG_OPEN == 0 && $result->RES_SEDAI_DISP_UMU == 1) :
       //$page_id = get_page_by_path('nakama-research-agreement')->ID;
       //$offer1 = researchCrSet::getPageSlug('nakama-research-agreement');//."tg_id=".$tg_id."&research_id=".$research_id."&post_id=".$post_id;
       $opnSignFlg = 1;
   endif;
   ?>
      <div class="container detail_page">
        <form id="mainForm" name="mainForm" action="<?php echo $offer1; ?>" method="GET">
         <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
            <tbody>
               <tr>
                  <td align="right">
                     <a class="btn_color" href="Javascript:window.close();"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>assets/img/close.gif" title="閉じる" border="0"></a>
                  </td>
               </tr>
            </tbody>
         </table>
         <br>
         <div align="center">
            <h1 class="page_title">
                <?php
                    echo $result->SOC_TG_NAME . '&nbsp;&nbsp;';
                    echo ($result->RES_DISP_WORD01) ? $result->RES_DISP_WORD01 : 'アンケート詳細';
                ?>
            </h1>
         </div>
         <br>
         <table class="detail_table" align="center" border="0" cellspacing="1" cellpadding="1">
            <tbody>
               <?php if(!empty($result->RES_RESERCH_NAME)) : ?>
               <tr>
                  <td class="DetailItem first">アンケート名</td>
                  <td class="DetailValue"><?php echo $result->RES_RESERCH_NAME; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($result->RES_SUB_TITLE)) : ?>
               <tr>
                  <td class="DetailItem">サブタイトル</td>
                  <td class="DetailValue"><?php echo $result->RES_SUB_TITLE; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($result->RES_START_DATE) || !empty($result->RES_END_DATE)) : ?>
               <tr>
                  <td class="DetailItem">開催日時</td>
                  <td class="DetailValue">
                  <?php
                     $startDate = isset($result->RES_START_DATE)?$researchs->convertDates($result->RES_START_DATE, "Y", "年").$researchs->convertDates($result->RES_START_DATE, "m", "月").$researchs->convertDates($result->RES_START_DATE, "d", "日").$result->RES_START_TIME."(".$researchs->convertDates($result->RES_START_DATE, "l", "").")":"";
                     $endDate = isset($result->RES_END_DATE)?$researchs->convertDates($result->RES_END_DATE, "Y", "年").$researchs->convertDates($result->RES_END_DATE, "m", "月").$researchs->convertDates($result->RES_END_DATE, "d", "日").$result->RES_END_TIME."(".$researchs->convertDates($result->RES_END_DATE, "l", "").")":"";
                     echo $startDate." ～ ".$endDate;
                  ?>
                  </td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($result->RES_PREPARED_NAME)) : ?>
               <tr>
                  <td class="DetailItem">担当者</td>
                  <td class="DetailValue"><?php echo $result->RES_PREPARED_NAME; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($result->RES_PREPARED_MAIL)) : ?>
               <tr>
                  <td class="DetailItem">担当E-MAIL</td>
                  <td class="DetailValue"><?php echo $result->RES_PREPARED_MAIL; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($result->RES_PREPARED_TEL)) : ?>
               <tr>
                  <td class="DetailItem">担当TEL</td>
                  <td class="DetailValue"><?php echo $result->RES_PREPARED_TEL; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($result->RES_PREPARED_FAX)) : ?>
               <tr>
                  <td class="DetailItem">担当FAX</td>
                  <td class="DetailValue"><?php echo $result->RES_PREPARED_FAX; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($result->RES_HOST_G_ID) && !empty($result->RES_HOST_NAME)) : ?>
               <tr>
                  <td class="DetailItem">主催</td>
                  <td class="DetailValue"><?php echo $result->RES_HOST_NAME; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($result->RES_CONTENTS)) : ?>
               <tr>
                  <td class="DetailItem">説明文</td>
                  <td class="DetailValue"><?php echo $result->RES_CONTENTS; ?></td>
               </tr>
               <?php endif; ?>
               <?php if(!empty($result->RES_CONTENTS2)) : ?>
               <tr>
                  <td class="DetailItem">アンケートに関する<br>目的掲載欄</td>
                  <td class="DetailValue"><?php echo $result->RES_CONTENTS2; ?></td>
               </tr>
               <?php endif; ?>
            </tbody>
         </table>
         <br>

<!-- Link -->
<?php
  //$offer3 = researchCrSet::getPageSlug('nakama-research-agreement')."tg_id=".$tg_id."&research_id=".$research_id."&post_id=".$post_id."&ans_type=".$ans_type."&memreg_open=1";

  $now_date = date('Y/m/d H:i:s');
  if(!empty($result->RES_END_TIME)){
      $end_time = date('Y/m/d', strtotime($result->RES_END_DATE))." ".$result->RES_END_TIME;
  }else{
      $end_time = date('Y/m/d', strtotime($result->RES_END_DATE))." 23:59:59";
  }
  if(!empty($result->RES_START_TIME)){
      $start_time = date('Y/m/d', strtotime($result->RES_START_DATE))." ".$result->RES_START_TIME;
  }else{
      $start_time = date('Y/m/d', strtotime($result->RES_START_DATE))." 00:00:00";
  }
   if ($ans_type >= 2 && $end_time > $now_date) { ?>
    <div style="text-align: center">
        <label><input type="radio" name="ans_type" value="0"> <?php echo '１問１ページで回答する'; ?></label>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <label><input type="radio" name="ans_type" value="1" checked="checked"> <?php echo '全問１ページで回答する'; ?></label>
    </div>
    <?php } else {  ?>
    <input type="hidden" name="ans_type" value="<?php echo $ans_type; ?>">
<?php }

  if($capaFlg == 0) :
    if($end_time >= $now_date) :
      if($start_time > $now_date) :
        if(!empty($result->RES_DISP_WORD09)){
           echo "<center><b>".$result->RES_DISP_WORD09."</b></center>";
        }else{
           echo "<center><b>".DEFAULT_WORD_RES_PREINITATION."</b></center>";
        }
      elseif($result->RES_STATUS == 2) :
        if(!empty($result->RES_DISP_WORD10)){
           echo "<center><b>".$result->RES_DISP_WORD10."</b></center>";
        }else{
           echo "<center><b>".DEFAULT_WORD_RES_SUSPEND."</b></center>";
        }
      else :
?>
  <table width="640" align="center" border="0" cellspacing="1" cellpadding="1">
  <?php
  $sedai_disp_umu = !empty($result->RES_SEDAI_DISP_UMU) ? $result->RES_SEDAI_DISP_UMU : 0;
  if($sedai_disp_umu != 2) : ?>
    <tr>
      <td width="220" colspan="3">
<?php
          $memberreg_open = !empty($result->RES_MEMBERREG_OPEN) ? $result->RES_MEMBERREG_OPEN : 0;
          if($target) :  //CheckTarget($memberreg_open)
            if($opnSignFlg == 1) :
?>
        <div style="line-height: 25px; padding-top: 3px;">
<?php     if($tg_id == "activemember" && $research_id == 'ACTIVEMEMBER_EX_RESEARCH_ID') : ?>
          <font color="#FFBB88">■</font><a style="text-decoration: none;" onclick="mainSubmit()" href="javascript:void(0)<?php //echo $offer1; ?>">アンケートに参加する</a>
<?php     else :  ?>
          <font color="#FFBB88">■</font><a style="text-decoration: none;" onclick="mainSubmit()" href="javascript:void(0)<?php //echo $offer1; ?>">
            <?php $disp_word02 = !empty($result->RES_DISP_WORD02) ? $result->RES_DISP_WORD02 : 'DEFAULT_WORD_RES_MEMBER_ANSWER';
                echo ($sedai_disp_umu == 1) ? $disp_word02 : '回答はこちら';
            ?>
          </a>
<?php     endif; ?>
        </div>
      </td>
    </tr>
<?php
        else:
?>
        <div style="line-height: 25px; padding-top: 3px;">
<?php     if($tg_id == "activemember" && $research_id == 'ACTIVEMEMBER_EX_RESEARCH_ID') : ?>
          <font color="#FFBB88">■</font><a style="text-decoration: none;" onclick="mainSubmit()" href="javascript:void(0)<?php //echo $offer1; ?>">アンケートに参加する</a>
<?php     else :  ?>
          <font color="#FFBB88">■</font><a style="text-decoration: none;" onclick="mainSubmit()" href="javascript:void(0)<?php //echo $offer1; ?>">
            <?php $disp_word02 = !empty($result->RES_DISP_WORD02) ? $result->RES_DISP_WORD02 : 'DEFAULT_WORD_RES_MEMBER_ANSWER';
              echo ($sedai_disp_umu == 1) ? $disp_word02 : '回答はこちら';
            ?>
          </a>
<?php     endif; ?>
        </div>
      </td>
    </tr>
<?php
      endif;
    endif;
  endif;
  $memberreg_open = !empty($result->RES_MEMBERREG_OPEN) ? $result->RES_MEMBERREG_OPEN : 0;
  if($memberreg_open == 1 && $tg_id == "activemember" && $research_id == "ACTIVEMEMBER_EX_RESEARCH_ID") :
  else :
    $sedai_disp_umu = !empty($result->RES_SEDAI_DISP_UMU) ? $result->RES_SEDAI_DISP_UMU : 0;
    if($sedai_disp_umu == 1 || $sedai_disp_umu == 2) :
      if($opnSignFlg == 1) :
?>
    <tr>
      <td width="220">
        <div style="line-height: 23px; padding-top: 3px;">
          <?php $memreg_open = 1; ?>
          <font color="#FFBB88">■</font><a style="text-decoration: none;" onclick="mainSubmitMemregOpen()" href="javascript:void(0)">
            <?php
            $sedai_disp_umu = !empty($result->RES_SEDAI_DISP_UMU) ? $result->RES_SEDAI_DISP_UMU : 0;
            $disp_word03 = !empty($result->RES_DISP_WORD03) ? $result->RES_DISP_WORD03 : DEFAULT_WORD_RES_PUBLIC_ANSWER;
            if($sedai_disp_umu == 2 && $disp_word03 == DEFAULT_WORD_RES_PUBLIC_ANSWER){
              echo "アンケートはこちら";
            }else{
              echo $disp_word03;
            }
            ?>
            </a>
        </div>
      </td>
      <td width="280"></td>
      <td width="120">
<?php if( $inqMail != "" ) : ?>
        <div>
          <a style="text-decoration: none;" href="mailto:<?php echo $inqMail; ?>?subject=『<?php echo $result->RES_RESERCH_NAME; ?>』のお問合せ">お問合せ</a>
        </div>
<?php endif; ?>
      </td>
    </tr>
<?php
  else :
?>
    <tr>
      <td width="220">　</td>
      <td width="280"></td>
      <td width="120">
<?php if( $inqMail != "" ) : ?>
        <div>
          <a style="text-decoration: none;" href="mailto:<?php echo $inqMail; ?>?subject=『<?php echo $result->RES_RESERCH_NAME; ?>』のお問合せ">お問合せ</a>
        </div>
<?php endif; ?>
      </td>
    </tr>
<?php
          endif;
        endif;
      endif;
    endif;
?>
  </table>
<?php
    else :
      if($end_time <= $now_date):
        if(!empty($result->RES_DISP_WORD12)){
          echo "<center><b>".$result->RES_DISP_WORD12."</center>";
        }else{
          echo "<center><b>".DEFAULT_WORD_RES_DEADLINE."</center>";
        }
      else :
        if(!empty($result->RES_DISP_WORD12)){
          echo "<center><b>".$result->RES_DISP_WORD12."</center>";
        }else{
          echo "<center><b>".DEFAULT_WORD_RES_DEADLINE."</b></center>";
        }
      endif;
    endif;
  else:
    if(!empty($result->RES_DISP_WORD11)){
      echo "<center><b>".$result->RES_DISP_WORD11."</center>";
    }else{
      echo "<center><b>".DEFAULT_WORD_RES_UPPER_LIMIT."</b></center>";
    }
  endif;
?>
<!-- End link -->
         <br>
        <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
         <input type="hidden" name="memreg_open" value="<?php echo $memreg_open; ?>">
         <input type="hidden" name="tg_id" value="<?php echo $tg_id; ?>">
         <input type="hidden" name="research_id" value="<?php echo $research_id; ?>">
         <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
      </form>
      </div>
   </body>
</html>
